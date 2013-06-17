<?php

require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/table.class.php");
require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/DB.class.php");
require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/Logger.class.php");

//Associated classes
require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/address.class.php");
require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/role.class.php");

class Person extends Table {

    protected $table = "person";

		function updateLastLogin($user_id){
			$p = new Person($user_id);
			$p->last_login = time();

			//This effectively dismisses new Attachments, which is the only point of this method 
			if(isset($_SESSION['JR'])) $_SESSION['JR']->user['previous_login'] = $p->last_login;

			return true;
		}		


    /**
     * getPersonForm
     * 
     * Returns a form to edit person object
     *
     * @return string  JSON Encoded Return Array
     */
    function getPersonForm() {
        if(isset($_SESSION['JR'])){

            $smarty = $_SESSION['JR']->getSmarty();
            $smarty->assign('person',$this->getRecord());

            //Get user address
            $address = new Address();
            $addr = $address->find(array(array('person_id',$this->person_id)));
            if(isset($addr[0])){
                $smarty->assign('address',$addr[0]->getRecord());
            }

            //Assign Roles
            $role = new Role();
            $smarty->assign('roles',$role->getAll());

            //Fetch HTML form
            $html = $smarty->fetch('person_form.tpl');

            //Build, Encode, and Return Array
            return json_encode(array(
                array(
                    'id'   => "people_center",
                    'html' => $html
                ),
                array(
                    'js'   => '$("#people_save_button").bind("click", savePerson);
                    $("#people_discard_button").bind("click", function(){
                        if($("#person__person_id").val()) loadPerson($("#person__person_id").val());
                    });
                    $("#change_password").bind("click", passwordModal);
                    initInputs("#people_center input, #people_center textarea, #people_center select");'
                )
            ));
        }


    }


    /**
     * delete
     *
     * Overrides table::delete because 
     * a person's addresses must be deleted
     * before the person's object will successfully
     * delete.
     * 
     * @return boolean
     */
    function delete() {
        $logger = Logger::getInstance();

        if ($this->key === false) {
            $logger->log("Key not loaded for delete() method.", Logger::NORMAL);
            throw new Exception("Key not loaded for delete() method");
        }

        //delete all addresses
        $addr = new Address();
        $logger->log("Finding and deleting each address.", Logger::DEBUG);
        $addrs = $addr->find(array(array('person_id',$this->person_id)));
        foreach($addrs as $a){
            $a->delete();
        }

        return parent::delete();
    }

    


    function formatForDisplay($k,$v){
        //if asking for the password, don't give it
        if($k == "password") return '';
        
        return $v;
    }

    function formatForStore($k,$v){
        //if storing the password, hash it first
        if($k == "password") return sha1($v);
        
        return $v;
        
    }

    /**
     * can
     *
     * can() function
     * 
     * RULES:
     *   User Doesn't Exist:
     *      ONLY CAN READ WHAT IS NEEDED TO LOGIN
     *   User Exists:
     *      FIND: Everyone but reviewers are allowed
     *      
     *      READ: A user can read everything about themselves
     *            Everyone can see "person_id"
     *            Then everyone but reviewers are allowed
     *      
     *      UPDATE: ADMINISTRATORS can update anything
     *              EDITORS can update anything except roles
     *              A USER can update anything about themselevs
     *                except their username or role
     *      
     *      CREATE: Only ADMINISTRATORS
     *      DELETE: No body!
     *
     *
     * @return boolean
     */
    function can($action, $params = array()){
        $logger = Logger::getInstance();

        $user = false;
        if(isset($_SESSION['JR'])){
            //who is logged in.
            $user = $_SESSION['JR']->getUser();
        } 

        //No one is logged in
        if($user === false){
            switch($action){
                case 'find':
                    //This is the LOGIN params. 
                    if(isset($params[0][0][0]) && $params[0][0][0] == "username" 
                       && isset($params[0][2][0]) && $params[0][2][0] == "password" 
                       && isset($params[1]) && $params[1] === false 
                       && isset($params[2][0]) && $params[2][0] == "person_id"){
                        return true;
                    }
                case 'read':
                		if($params == "role_id") return true;
                		if($params == "last_login") return true;
								case 'create':
                case 'update':
										if(isset($params[0]) && $params[0] == "last_login") return true;
                case 'delete':
                    return false;
                    break;
            }
            
        } else {
            $ret = false;
            switch($action){
                case 'find':
                    //Only Reviewers can't search people
                    return $user['role_id'] != ROLE::REVIEWER;
                    break;
                case 'read':
                    if($params == "person_id") return true;

                    //A user can read anything about themselves
                    if($this->key == $user['person_id']) return true;

                    //Everyone but reviewers can read people
                    return $user['role_id'] != ROLE::REVIEWER;
                    break;
                case 'update':
                    //Administrators can update anything
                    if($user['role_id'] == ROLE::ADMINISTRATOR || $user['role_id'] == ROLE::EDITOR) return true;

                    //Users can update everything about themselves,
                    //save username and role_id
                    if($user['person_id'] == $params[2]->person_id){
                        switch($params[0]){
                            case "username":
                            case "role_id":
                                return false;
                                break;
                            default: 
                                return true;
                                break;
                        }
                    }

                    //Everyone Else Fails
                    return false;

                    break;
                case 'create':
                    //Administrators can create 
                    return $user['role_id'] == ROLE::ADMINISTRATOR || $user['role_id'] == ROLE::EDITOR;
                    break;
                
                case 'delete':
                    return false;
                    break;
            }
        }

        $logger->log("Permission Denied!",Logger::DEBUG,true);
        return false;

    }



    /*****************
     *Static Functions
     *****************/

    static function findPersonByUsernamePassword($u, $p){
        $person = new Person();

        $ret = $person->find(array(array("username",$u),"AND",array("password",sha1($p))),null, false,array("person_id"));
        return (isset($ret[0])) ? $ret[0]['person_id'] : false;
    }

    static function getPeopleList(){

        $person = new Person();

        $people = $person->find(array(array(1,1)),array('ORDER','last_name'),false);

        return $people;
    }

    static function sortPeopleList($a, $b){
        $sort1 = "";
        $sort2 = "";
        if(isset($a['last_name'])){
            $sort1 = $a['last_name'];
        } else {
            $sort1 = $a['institution'];
        }

        if(isset($b['last_name'])){
            $sort2 = $b['last_name'];
        } else {
            $sort2 = $b['institution'];
        }

        return strtolower($sort1) > strtolower($sort2);

    }

    static function getPasswordModal(){

        if(isset($_SESSION['JR'])){
            $smarty = $_SESSION['JR']->getSmarty();

            $html = $smarty->fetch('password_modal.tpl');


            $id = "password_modal_".time();
            $ret = array(
                array(
                    'js'   => "$('#body').height($(window).height());
                               $('#body').css('overflow-y','hidden');
                               var div = document.createElement('div');
                               div.id = '$id';
                               $('body').append(div);
                               //$(div).css('height', $(document).height());
                               "
                ),
                array(
                    'id'   => $id,
                    'html' => $html
                )
            );

            return json_encode($ret);


        }

    }

    
}
