<?php

require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/table.class.php");
require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/DB.class.php");

class Journal extends Table {

    protected $table = "journal";

    const JASA = 1;
    const TAS = 2;
    const NO_REVIEW = 3;

    /**
     * can
     *
     * RULES:
     *    FIND, READ: ALL are allowed
     *    CREATE, UPDATE, DELETE: Only ADMINISTRATORS are allowed
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
                    //Anyone can look up and search for journals
                    return true;
                    break;
                case 'create':
                case 'update':
                case 'delete':
                    //Administrators can create/update/delete journal
                    return $user['role_id'] == ROLE::ADMINISTRATOR;
                    break;
            }
        }
        
        return false;
    }

}
