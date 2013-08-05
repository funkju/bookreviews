<?php


require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/table.class.php");
require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/DB.class.php");

class Material extends Table {

    protected $table = "material";

    const UNDECIDED = 1;
    const REJECTED = 2;
    const DISTRIBUTE = 3;
    const ASSIGN = 4; 


    /**
     * getMaterialForm
     * 
     * Returns a form to edit material object
     *
     * @return string  JSON Encoded Return Array
     */
    function getMaterialForm() {
        if(isset($_SESSION['JR'])){

            $smarty = $_SESSION['JR']->getSmarty();
            //Get all AEs
            $person = new Person();
            $smarty->assign('aes', $person->find(array(array('role_id',Role::ASSOC_EDITOR)),array('ORDER'=>'last_name'), false)); 
            $smarty->assign('material',$this->getRecord());


            //Assign Roles
            $role = new Role();
            $smarty->assign('roles',$role->getAll());

            //Fetch HTML form
            $html = $smarty->fetch('material_form.tpl');

            //Build, Encode, and Return Array
            return json_encode(array(
                array(
                    'id'   => "submitted_center",
                    'html' => $html
                ),
                array(
                    'js'   => ' $("#material_save_button").bind("click", saveMaterial);
                                $("#material_discard_button").bind("click", function(){
                                    if($("#material__material_id").val()) loadMaterial($("#material__material_id").val());
                                });
                                
                                $("#material__screen_status").change(function(){
                                    if($(this).val() == 4) { $("#material__ae_id, #material_ae_label").show(); }
                                    else { $("#material__ae_id, #material_ae_label").hide(); }
                                });

                                $("#material__author_is_submitter").click(function(){
                                    $("#material_form_line_3").toggle();
                                    if(!$("#material_form_line_3").is(":visible")){
                                        $("#material__submitter_name, #material__submitter_email").addClass("dirty_input");
                                    }  
                                })
                                initInputs("#submitted_center input, #submitted_center textarea, #submitted_center select");'
                )
            ));
        }


    }

    static function getNonDistributed(){

        $br = new Material();

        $brs = $br->find(array(array(array('screen_status', '=',),'OR',array('journal_id','=',2)),'AND',array('assoc_editor_id','is',null), 'AND', array('book_id','is not',null)),null,false);

        $nonDistributed = array();
        foreach($brs as $b){
            $book = new Book($b['book_id']);
            $book = $book->getRecord();
            $nonDistributed[] = $book;
        }

        usort($nonDistributed, array('Book','sortBookList'));


        return $nonDistributed;

    }



    /**
     * can
     *
     * RULES:
     *   CREATE: ALL are allowed
     *	 FIND, READ: ALL but reviewers
     *   UPDATE, DELETE: ALL but reviewers
     *
     * @return boolean
     */
    function can($action, $params = array()){
        $logger = Logger::getInstance();

        $user = false;
        if(isset($_SESSION['JR'])){
            //who is logged in?
            $user = $_SESSION['JR']->getUser();
        }

        if($user !== false){
            switch($action){
                case 'find':
                case 'read':
                case 'create':
                    //everyone can find and read;
                    return true;
                    break;
                case 'update':
                case 'delete':                    
                    //Editors and Administrators can
                    return $user['role_id'] != ROLE::REVIEWER && $user['role_id'] != ROLE::ASSOC_EDITOR;
                    break;
            }
        } else {
		if($action == "create") return true;

	}
        
        return false;
    }


}
