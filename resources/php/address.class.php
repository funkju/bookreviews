<?php

class Address extends Table {

    protected $table = "address";

    /**
     * updateByCleanse
     *
     * @param person_id
     * @param array Address Object
     * @return boolean
     */
     static function updateByCleanse($person_id, $cleansed){
        $c_addr = $cleansed['Address'];
        $address = new Address();
        $addr = $address->find(array(array('person_id',$person_id)));


        if(isset($addr[0])){        
            $addr[0]->Address1 = (isset($c_addr['Address1'])) ? $c_addr['Address1'] : $addr[0]->Address1;
            $addr[0]->Address2 = (isset($c_addr['Address2'])) ? $c_addr['Address2'] : $addr[0]->Address2;
            $addr[0]->City     = (isset($c_addr['City']))     ? $c_addr['City']     : $addr[0]->City;
            $addr[0]->State    = (isset($c_addr['State']))    ? $c_addr['State']    : $addr[0]->State;
            $addr[0]->ZIPCode  = (isset($c_addr['ZIPCode']))  ? $c_addr['ZIPCode']  : $addr[0]->ZIPCode;
            $addr[0]->Country  = (isset($c_addr['Country']))  ? $c_addr['Country']  : $addr[0]->Country;
            return true;
        }

        return false;

    
    }



    /**
     * can
     *
     * RULES:
     *   FIND, READ: ALL but REVIEWERS allowed
     *   UPDATE: A USER may update their own
     *           EDITORS and ADMINISTATORS allowd
     *   CREATE: A USER may create their own
     *           ADMINISTRATORS are allowed
     *   DELETE: Only ADMINISTRATORS are allowed
     *
     * @return boolean
     */
    function can($action, $params=array()){
        $logger = Logger::getInstance();

        $user = false;
        if(isset($_SESSION['JR'])){
            //who is logged in.
            $user = $_SESSION['JR']->getUser();
        }

        //If someone is logged in
        if($user !== false){
            switch($action){
                case 'find':
                   //IF searching for own address, return true;
                   $ret = true;
                   foreach($params[0] as $p){
                       if($p[0] == "person_id"){
                           if(count($p) == 2 && $p[1] == $user['person_id']) $ret = true && $ret;
                           else if(count($p) == 3 && $p[2] == $user['person_id']) $ret = true && $ret;
                           else $ret = false;
                       }
                   }
                   if($ret) return true;
                   //Otherwise let anyone but reviewers search addresses
                   return $user['role_id']  != ROLE::REVIEWER;

                case 'read':
                    //Let anyone but reviewers read addresses, except their own
                    return $this->vals['person_id'] == $user['person_id'] || $user['role_id'] != ROLE::REVIEWER;
                    break;
                case 'update':
                    //A user can update their own address
                    if($user['person_id'] == $this->person_id) return true;
                    
                    //Administrators and Editors can edit addresses
                    return $user['role_id'] == ROLE::ADMINISTRATOR || $user['role_id'] == ROLE::EDITOR;
                    break;
                case 'create':
                    if($user['person_id'] == $params['person_id']) return true;
                case 'delete':
                    //Administrators, Editors can create and delete
                    return $user['role_id'] == ROLE::ADMINISTRATOR || $user['role_id'] == ROLE::EDITOR;
                    break;
            }
        }

        $logger->log("Permission Denied!",Logger::DEBUG,true);
        return false;


    }
}
