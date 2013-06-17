<?php

require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/table.class.php");
require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/DB.class.php");

class ReviewType extends Table {

    protected $table = "review_type";

    const TELEGRAPHIC = 1;
    const SHORT = 2;
    const MEDIUM = 3;
    const LONG = 4;

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
                    //No reason to edit this table.... (when you come back to change this... :P )
                    return false;
                    break;
            }
        }
        
        return false;
    }

}
