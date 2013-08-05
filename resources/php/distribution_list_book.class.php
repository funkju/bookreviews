<?php

require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/table.class.php");
require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/DB.class.php");

class DistributionListBook extends Table {

    protected $table = "distribution_list_book";

    const BOOK = 0;
    const MATERIAL = 1;

    /**
     * can
     *
     * RULES:
     *   FIND, READ: ALL are allowed
     *   CREATE, UPDATE, DELETE: ONLY Editor and Admin
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
                    //everyone can find and read;
                    return true;
                    break;
                case 'create':
                case 'update':
                case 'delete':
                    //Editors and Administrators can
                    return $user['role_id'] == ROLE::EDITOR || $user['role_id'] == ROLE::ADMINISTRATOR;
                    break;
            }
        }
        
        return false;
    }

}
