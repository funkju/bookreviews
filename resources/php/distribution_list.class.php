<?php

require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/table.class.php");
require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/DB.class.php");

class DistributionList extends Table {

    protected $table = "distribution_list";

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



    function formatForStore($key,$param){
        switch($key){
            case "expires":
                $param = ($param == "") ? null : strtotime($param) + (23*60 + 59)*60;
                break;
            case "created":
                $param = ($param == "") ? null : strtotime($param);
                break;
        }

        return $param;
    }
    function formatForDisplay($key, $param){
        switch($key){
            case "expires":
            case "created":
                $param = ($param == 0 || $param == null) ? false : date("m/d/Y",$param);
                break;
        }

        return $param;
    }

    function create($vars){
    	$name = $vars['name'];
	$expires = $vars['expires'];
	$books = $vars['books'];

        //Deactivate all distribution lists
        $dl = new DistributionList();
        $dls = $dl->find(array(array('active',1)));
        if($dls){
            foreach($dls as $d){
                $d->active = 0;
            }
        }
        $dls = null; 
        $dl = null;

        //Create Distribution List
        //Set it to Active
        $dl_id = parent::create(array(
            'name'    => $name,
            'expires' => $expires,
            'created' => time(),
            'active'  => 1
        ));

        if($dl_id === false) return -1;

        //Add books to Distribution List
        $dlb = new DistributionListBook();
        foreach($books as $book_id){
            $ret = $dlb->create(array(
                'distribution_list_id' => $dl_id,
                'book_id'              => $book_id
            ));
            if($ret === false) return -1;
        }



        return $dl_id;
    }


    
    static function alter($id, $name, $expires, $rem_books, $add_books){
        $dl = new DistributionList($id);

        //Update names and expirations
        if($dl->name != $name) $dl->name = $name;
        if($dl->expires != $expires) $dl->expires = $expires;

        if($rem_books != -1){
            //Remove these books
            foreach($rem_books as $b){
                $dlb = new DistributionListBook();
                $dlbs = $dlb->find(array(array('book_id',$b),'AND',array('distribution_list_id',$id)));


                if(isset($dlbs[0])){
                    $dlb = $dlbs[0];

                    $dlp = new DistributionListPreference();
                    $dlps = $dlp->find(array(array('distribution_list_book_id', $dlb->distribution_list_book_id)));
    
                    foreach($dlps as $dlp){
                        $dlp->delete();
                    }
    
                    $dlb->delete();
                } else {
                    return -1;
                }
            }   
        }

        
        if($add_books != -1){
            //Add these books
            foreach($add_books as $b){
                $dlb = new DistributionListBook();
                $dlbs = $dlb->find(array(array('book_id',$b),'AND',array('distribution_list_id',$id)));

                if(!isset($dlbs[0])){
                    $dlb->create(array(
                        'book_id' => $b,
                        'distribution_list_id' => $id
                    ));
                }
            }
        }
        
        return $id;
    }


    static function getPersonHistory($person_id){
        $p = new Person($person_id);

        if(isset($_SESSION['JR'])){
            $smarty = $_SESSION['JR']->getSmarty();
            $smarty->assign('person', $p->getRecord());

            //Get Last 50 (at most) Book Reviews
            $br = new BookReview();
            $brs = $br->find(array(array(array('journal_id','!=',Journal::NO_REVIEW),'OR',array('journal_id','is',null)),'AND', array('assoc_editor_id',$p->person_id)),array('ORDER'=>'date_received ASC,date_promised ASC','LIMIT'=>50),false);
            
            $pending = 0;
            $avg_rank = array(0,0);
            foreach($brs as &$b){
                if(!$b['date_received']) $pending++;

                //Set Journal Name
                switch($b['journal_id']){
                    case Journal::JASA:
                        $b['journal'] = "JASA";
                        break;
                    case Journal::TAS:
                        $b['journal'] = "TAS";
                        break;
                }

                //Get book Title
                if($b['book_id']){
                    $book = new Book($b['book_id']);
                    $b['book'] = array('title' => $book->title, 'book_id'=>$book->book_id);
                
                    //find rank
                    $dlb = new DistributionListBook();
                    $dlbs = $dlb->find(array(array('book_id',$b['book_id'])));
                    $dlp = new DistributionListPreference();
                    foreach($dlbs as $dlb){
                        $dlps = $dlp->find(array(array('distribution_list_book_id',$dlb->distribution_list_book_id),'AND',array('person_id',$p->person_id)));
                        if(isset($dlps[0])){
                            $avg_rank[0]++;
                            $avg_rank[1] += $dlps[0]->rank;
                            $b['rank'] = $dlps[0]->rank;
                        }
                    }
                } else {
                    $b['book'] = array('title'=>'','book_id'=>null);
                }

            }

            $smarty->assign('reviews',$brs);
            $smarty->assign('pending',$pending);
            $avg_rank = ($avg_rank[0] == 0) ? 0 : round($avg_rank[1]/$avg_rank[0],2);
            $smarty->assign('avg_rank', $avg_rank);


            $html = $smarty->fetch('dist_person_hist_modal.tpl');


            $id = "dist_person_hist_modal_".time();
            $ret = array(
                array(
                    'js'   => "$('#body').height($(window).height());
                               $('#body').css('overflow-y','hidden');
                               var div = document.createElement('div');
                               div.id = '$id';
                               $('body').append(div);
                               //$(div).css('height', $(document).height());
                               "
                ),
                array(
                    'id'   => $id,
                    'html' => $html
                ),
                array(
                    'js'    => "$.tablesorter.addParser({
                                    id: 'img',
                                    is: function(s) { return false;},
                                    format: function(s){
                                        return s != '';
                                    },
                                    type: 'numeric'
                                });
                                $('table.dist_hist').tablesorter({
                                    'headers' : {
                                        0 : {
                                            'sorter' : 'img'
                                        }
                                    }
                                })"
                )
            );


            return json_encode($ret);


        }

    }

    function activate($distribution_list_id, $activate){
        
        if($activate){
            $dl = new DistributionList();
            $dls = $dl->find(array(array('active',1)));
            if($dls){
                $dls[0]->active = 0;
            }
        }
       
        $dl = new DistributionList($distribution_list_id);
        $dl->active = $activate;

        
        return ($activate) ? $dl->distribution_list_id : -1;
    }
}
