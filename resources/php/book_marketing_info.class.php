<?php

require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/table.class.php");
require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/DB.class.php");

class BookMarketingInfo extends Table {

    protected $table = "book_marketing_info";


    /**
     * can
     *
     * RULES:
     *     FIND, READ: ALL are allowed
     *     CREATE, UPDATE, DELETE: ADMINISTRATORS and EDITORS are allowed
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
                    //Anyone can look up and search for books
                    return true;
                    break;
                case 'create':
                case 'update':
                case 'delete':
                    //Editors and Administrators can create/update/delete books
                    return $user['role_id'] == ROLE::EDITOR || $user['role_id'] == ROLE::ADMINISTRATOR;
                    break;
            }
        }
        
        return false;
    }

    function formatForStore($k,$v){
        if($k == "price"){
          $v = str_replace("$","",$v);
        }

        return $v;
    }


}
