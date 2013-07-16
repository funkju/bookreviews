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
