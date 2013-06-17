<?php

require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/table.class.php");
require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/DB.class.php");

class BookReviewAttachment extends Table {

    protected $table = "book_review_attachment";


		/**
		 * newAttachmentsSince
		 *
		 * Get new uploads since a given timestamp
		 *
		 * @param timestamp int
		 * @return array
		 *
		 */
		function newAttachmentsSince($timestamp){
			return $this->find(array(array('uploaded_date','>=',$timestamp)), null, false);
		}

    /**
     * delete
     *
     * Overrides table::delete 
     *
     * @return boolean
     */
    function delete() {
        $logger = Logger::getInstance();

        if ($this->key === false) {
            $logger->log("Key not loaded for delete() method.", Logger::NORMAL);
            throw new Exception("Key not loaded for delete() method");
        }

        //delete file
				$suc = true;
				if(file_exists(UPLOAD_ROOT.'/'.$this->book_review_id."/".$this->uploaded_date."/".$this->filename)){
					$suc = unlink(UPLOAD_ROOT.'/'.$this->book_review_id."/".$this->uploaded_date."/".$this->filename);
				}

			
				if($suc) return parent::delete();
				else return false;
    }


    /**
     * can
     *
     * RULES:
     *   FIND, READ: ALL are allowed
     *   CREATE, UPDATE, DELETE: ALL but reviewers
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
                    return $user['role_id'] != ROLE::REVIEWER;
                    break;
                case 'update':
                case 'delete':
                    
                    //Editors and Administrators can
                    return $user['role_id'] != ROLE::REVIEWER && $user['role_id'] != ROLE::ASSOC_EDITOR;
                    break;
            }
        }
        
        return false;
    }

}
