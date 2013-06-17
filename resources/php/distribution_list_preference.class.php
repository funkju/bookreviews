<?php

require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/table.class.php");
require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/DB.class.php");

class DistributionListPreference extends Table {

    protected $table = "distribution_list_preference";



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
                    //Users can create their own preferences, 
                    if(isset($params['person_id']) && $params['person_id'] == $user['person_id']) return true;
                    //ADMINS AND EDITORS can create
                    return $user['role_id'] == ROLE::EDITOR || $user['role_id'] == ROLE::ADMINISTRATOR;
                    break;
                case 'update':
                case 'delete':
                    if($this->person_id == $user['person_id']){
                        return true;
                    }
                    //Editors and Administrators can
                    return $user['role_id'] == ROLE::EDITOR || $user['role_id'] == ROLE::ADMINISTRATOR;
                    break;
            }
        }
        
        return false;
    }

    static function assignBook($id, $assign){
        $this_dlp = new DistributionListPreference($id);
        
        $br = new BookReview();
        $dlb = new DistributionListBook($this_dlp->distribution_list_book_id);

        if($assign){
            //Unassign this book from everyone else
            $dlps = $this_dlp->find(array(array('distribution_list_book_id',$this_dlp->distribution_list_book_id), 'AND', array('assigned',1)));
            if($dlps){
                foreach($dlps as $dlp){
                    $dlp->assigned = 0;
                }
            }

            $brs = $br->find(array(array('book_id',$dlb->book_id)));
            if($brs){
                $brs[0]->assoc_editor_id = $this_dlp->person_id;
            }
            $this_dlp->assigned = 1;
        } else {
            $brs = $br->find(array(array('book_id',$dlb->book_id)));
            if($brs){
                $brs[0]->assoc_editor_id = null;
            }

            //If rank is 0, delete the preference.
            //Preferences with rank == 0 are when an AE
            //is assigned a book they didn't select
            if($this_dlp->rank == 0){
                $this_dlp->delete();
            } else {
                $this_dlp->assigned = 0;
            }
         }

       $smarty = $_SESSION['JR']->getSmarty();
    
       $aes = DistributionListPreference::getAllPreferencesByAE($dlb->distribution_list_id);            
       $smarty->assign('aes',$aes);

       $dlbs = DistributionListPreference::getAllPreferencesByBook($dlb->distribution_list_id);   
       $smarty->assign('books',$dlbs);

       $dl = new DistributionList($dlb->distribution_list_id);
       $smarty->assign('distribution_list',$dl->getRecord());

       $person = new Person();
       $smarty->assign('all_aes', $person->find(array(array('role_id',Role::ASSOC_EDITOR)),array('ORDER'=>'last_name'),false));

       $smarty->assign('assign_by',$_SESSION['JR']->ds->assign_by);
       if($_SESSION['JR']->ds->assign_by =="BOOK"){
            $smarty->assign('only',$dlb->book_id);


            $ret = array(
                array(
                    'id'   => 'dist_assign_'.$dlb->book_id,
                    'html' => $smarty->fetch('distribution_assign.tpl')
                )
            );
       } else {
        $ret = array(
            array(
                'js'  => 'showStatus("Success!",false,function(){location.reload(true)})'
            )
        );


       }

        
        return json_encode($ret);

    }

    static function getAllPreferencesByAE($distribution_list_id){
            //get AEs
            $person = new Person();
            $aes = $person->find(array(array('role_id','=',Role::ASSOC_EDITOR)),array('ORDER'=>'last_name'), false);

            $dlp = new DistributionListPreference();
            //get AE selections
            foreach($aes as &$a){   
               $a['prefs'] = $dlp->getPreferencesByPerson($distribution_list_id, $a['person_id']);
               foreach($a['prefs'] as &$p){
                   if($p['book_id']){
                       $b = new Book($p['book_id']);
                       $p['book'] = $b->getRecord();

                       $br = new BookReview();
                       $p['book_review'] = $br->find(array(array('book_id',$b->book_id)),null,false);
                   }
               }
            }
            return $aes;
    }
    
    static function getAllPreferencesByBook($distribution_list_id){
        $dlb = new DistributionListBook();

        $dlbs = $dlb->find(array(array('distribution_list_id',$distribution_list_id)), null, false);
        if($dlbs){
            $dlp = new DistributionListPreference();
            foreach($dlbs as &$tdlb){
                if($tdlb['book_id']){
                    $book = new Book($tdlb['book_id']);
                    $tdlb = array_merge($tdlb, $book->getRecord());
                    if($book->book_marketing_info_id){
                        $bmi = new BookMarketingInfo($book->book_marketing_info_id);
                        $tdlb['book_marketing_info'] = $bmi->getRecord();
                    }
                    $tdlb['prefs'] = $dlp->getPreferencesByBook($tdlb['distribution_list_book_id']);
                    if($tdlb['prefs']){
                        foreach($tdlb['prefs'] as &$p){
                            if($p['person_id']){
                                $per = new Person($p['person_id']);
                                $p['person'] = $per->getRecord();
                            }
                        }
                    }

                    $br = new BookReview();
                    $tdlb['book_review'] = $br->find(array(array('book_id',$book->book_id)),null, false);

                }
            }
        }

        return $dlbs;
     }
        
    function getPreferencesByPerson($distribution_list_id, $person_id){
        $sql = "SELECT distribution_list_preference_id, book_id, rank, assigned, (SELECT COUNT(*) FROM distribution_list_preference dlp2, distribution_list_book dlb2 WHERE dlp2.distribution_list_book_id = dlb2.distribution_list_book_id AND assigned = 1 AND dlb2.book_id = dlb.book_id) as other_assigned FROM distribution_list_preference dlp, distribution_list_book dlb WHERE dlp.distribution_list_book_id = dlb.distribution_list_book_id AND person_id = ? AND distribution_list_id = ? ORDER BY rank ASC";
        $rows = $this->db->exec($sql,array($person_id, $distribution_list_id));

        return $rows;
    }

    function getPreferencesByBook($distribution_list_book_id){
        $sql = 'SELECT distribution_list_preference_id, person_id, rank, assigned, (SELECT COUNT(*) FROM distribution_list_preference dlp2, distribution_list_book dlb2 WHERE dlp2.distribution_list_book_id = dlb2.distribution_list_book_id AND assigned = 1 AND dlb2.book_id = dlb.book_id) as other_assigned 
                FROM distribution_list_preference dlp, distribution_list_book dlb
                WHERE dlp.distribution_list_book_id = dlb.distribution_list_book_id AND dlb.distribution_list_book_id = ? ORDER BY rank ASC';
        $rows = $this->db->exec($sql,array($distribution_list_book_id));



        return $rows;
    }

    function updatePreferences($distribution_list_id, $book_id, $rank){
        $ret = array();

        //Delete preferences with this book
        $sql = "SELECT distribution_list_preference_id FROM distribution_list_preference dlp, distribution_list_book dlb ".
               "    WHERE dlp.distribution_list_book_id = dlb.distribution_list_book_id AND ".
               "          person_id = ? AND book_id = ? AND distribution_list_id = ?";
        $dlps = $this->db->exec($sql, array($_SESSION['JR']->user['person_id'], $book_id, $distribution_list_id));
        if(isset($dlps[0])){
            $dlp = new DistributionListPreference($dlps[0]['distribution_list_preference_id']);
            $dlp->delete();
        }


        //Delete preferences with this rank        
        $sql = "SELECT distribution_list_preference_id FROM distribution_list_preference dlp, distribution_list_book dlb ".
               "    WHERE dlp.distribution_list_book_id = dlb.distribution_list_book_id AND ".
               "          person_id = ? AND rank = ? AND distribution_list_id = ?";
        $dlps = $this->db->exec($sql, array($_SESSION['JR']->user['person_id'], $rank, $distribution_list_id));
        if(isset($dlps[0])){
            $dlp = new DistributionListPreference($dlps[0]['distribution_list_preference_id']);
            $dlp->delete();
        }


        //Create new Preference if Rank is not 0
        if($rank != 0){
            $dlb = new DistributionListBook();
            $dlbs =$dlb->find(array(
                array('distribution_list_id',$distribution_list_id),
                'AND',
                array('book_id',$book_id)
            ));
            $dlp = new DistributionListPreference();
            if(isset($dlbs[0])){
                $dlp->create(array(
                    'distribution_list_book_id' => $dlbs[0]->distribution_list_book_id,
                    'person_id'                 => $_SESSION['JR']->user['person_id'],
                    'rank'                      => $rank
                ));
            }
        }


        //Get new complete ranks for this distribution list, person
        $dlp = new DistributionListPreference();
        $ranks = $dlp->getPreferencesByPerson($distribution_list_id, $_SESSION['JR']->user['person_id']);
        foreach($ranks as &$r){
            $bk = new Book($r['book_id']);
            $r = array_merge($r,$bk->getRecord());

            if($r['book_marketing_info_id']){
                $bm = new BookMarketingInfo($r['book_marketing_info_id']);
                $r = array_merge($r,$bm->getRecord());
            }
        }

        //Set up and fetch HTML for the ranks, and return it
        $smarty = $_SESSION['JR']->getSmarty();
        $smarty->assign('distribution_ranks', $ranks);
        $html = $smarty->fetch('distribution_ranks.tpl');
        $ret[] = array(
            'id' =>'distribution_ranks',
            'html' => $html
        ); 
        return json_encode($ret);

    }


    function formatForDisplay($k,$v){
        switch($k){
            case "rank":
                if($v == 0) $v = "";
                break;
        }

        return $v;
    }

    function formatForStore($k,$v){
        switch($k){
            case "rank":
                if(!$v) $v = 0;
                break;
        }

        return $v;
    }

}
