<?php

require_once("table.class.php");
require_once("DB.class.php");

class BookPreference extends Table {

    protected $table = "book_preference";

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
                    if(isset($params['person_id']) && $params['person_id'] == $user['person_id']) return true;
                    return $user['role_id'] != ROLE::REVIEWER;
                    break;
                case 'update':
                case 'delete':
                    //Users can do their own
                    if($user['person_id'] == $this->person_id) return true;
                    
                    //Editors and Administrators can
                    return $user['role_id'] != ROLE::REVIEWER;
                    break;
            }
        }
        
        return false;
    }



    static function createOrUpdate($ranks){
        $person_id = $_SESSION['JR']->user['person_id'];


        foreach($ranks as $r){

            $book_id = $r[0];
            $rank = $r[1];

            $bp = new BookPreference();
            $bps = $bp->find(array(array('person_id',$person_id),'AND',array('book_id',$book_id)));

            if(isset($bps[0])){

                if($rank != 0){
                    //Update existing
                    $new_rank = ($bps[0]->rank = $rank);

                    //Check save.
                    if($new_rank != $rank) return -1;
    
                //Rank == 0 , delete
                } else {
                    $bps[0]->delete();
                }
            } else {
                //Create New
                if(!$bp->create(array(
                    'person_id'=>$person_id,
                    'book_id'=>$book_id,
                    'rank'=>$rank
                ))){
                    return -1;
                }
            }
        }

        return 1;
    }


    static function sortByRank($a,$b){

        return $a['rank'] > $b['rank'];
    }

}
