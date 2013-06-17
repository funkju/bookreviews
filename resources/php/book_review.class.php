<?php

require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/table.class.php");
require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/DB.class.php");

class BookReview extends Table {

    protected $table = "book_review";


    function getReviewsByIssue($journal_id, $issue_month, $issue_year){
        $br = new BookReview();
        
        $sql = "SELECT book_review_id, publish_order, br.notes as notes , book.book_id as book_id, book.title as title, ae.person_id as ae_id, ae.first_name as ae_first_name, 
                       ae.last_name as ae_last_name, rev.person_id as rev_id, rev.first_name as rev_first_name, rev.last_name as rev_last_name, journal.journal as journal
                FROM book_review br, person ae, person rev, book, journal
                WHERE br.journal_id = ? AND issue_month = ? AND issue_year = ? AND
                      br.book_id = book.book_id AND br.assoc_editor_id = ae.person_id AND
                      br.reviewer_id = rev.person_id AND br.journal_id = journal.journal_id
                ORDER BY publish_order ASC";
        $brs = $br->db->exec($sql, array($journal_id, $issue_month, $issue_year));

        if(isset($_SESSION['JR'])){
            $smarty = $_SESSION['JR']->getSmarty();
            $smarty->assign('reviews', $brs);

            $j = new Journal($journal_id);
            $smarty->assign('journal',$j->journal);

            $months = array('January',"Febuary",'March',"April",'May',"June",'July',"August",'September',"October",'November',"December");
            $smarty->assign('month',$months[$issue_month-1]);

            $smarty->assign('year',$issue_year);
    
            $html = $smarty->fetch('reviews_by_issue.tpl');
       
            $ret = array(
                array(
                    'id' => 'reviews_by_issue',
                    'html' => $html
                )
            );
            $ret[] = array(
                'js' => '$(".publish_order").bind("change",function(){changePubOrder(this);});'
            );

            return json_encode($ret);
        }

    
    }
		function getOrderToPublishList($journal_id, $issue_month, $issue_year){
        $br = new BookReview();
        
        $sql = "SELECT book.title as title, book.book_num as book_num, book.authors as author, book.publisher as publisher, book.year as publish_date,
				               book_marketing_info.isbn as isbn, book_marketing_info.pages as pages, book_marketing_info.price as price,
											 book_marketing_info.binding_type as binding_type
                FROM book_review br, person ae, person rev, book, journal, book_marketing_info
                WHERE br.journal_id = ? AND issue_month = ? AND issue_year = ? AND
                      br.book_id = book.book_id AND br.assoc_editor_id = ae.person_id AND
                      br.reviewer_id = rev.person_id AND br.journal_id = journal.journal_id
											AND book_marketing_info.book_marketing_info_id = book.book_marketing_info_id
                ORDER BY publish_order ASC";
        $brs = $br->db->exec($sql, array($journal_id, $issue_month, $issue_year));

				foreach($brs as &$br){
					if(strlen($br['isbn']) == 13){
						$i = $br['isbn'];
						$br['isbn'] = substr($i,0,3)."-".substr($i,3,1)."-".substr($i,4,2)."-".substr($i,6,6)."-".substr($i,12);
					}
					if(strlen($br['isbn']) == 10){
						$i = $br['isbn'];
						$br['isbn'] = substr($i,0,1)."-".substr($i,1,2)."-".substr($i,3,6)."-".substr($i,9);
					}

					if($br['price'] != "") $br['price'] = number_format($br['price'], 2, '.', '');
				}	
				

        if(isset($_SESSION['JR'])){
            $smarty = $_SESSION['JR']->getSmarty();
            $smarty->assign('reviews', $brs);

            $j = new Journal($journal_id);
            $smarty->assign('journal',$j->journal);

            $months = array('January',"Febuary",'March',"April",'May',"June",'July',"August",'September',"October",'November',"December");
            $smarty->assign('month',$months[$issue_month-1]);

            $smarty->assign('year',$issue_year);
    
            $html = $smarty->fetch('order_to_publish.tpl');
       
            $ret = array(
                array(
                    'id' => 'order_to_publish_list',
                    'html' => utf8_encode($html)
                )
       			);

						return json_encode($ret);
        }
			}


			function getAuthorInformationList($journal_id, $issue_month, $issue_year){
        $br = new BookReview();
        
        $sql = "SELECT book_review_id, book.title as title, book.book_num as num rev.person_id,
                       rev.first_name as first_name, rev.last_name as last_name
                FROM book_review br, person rev, book, journal
                WHERE br.journal_id = ? AND issue_month = ? AND issue_year = ? AND
                      br.book_id = book.book_id AND br.reviewer_id = rev.person_id 
											AND br.journal_id = journal.journal_id
                ORDER BY publish_order ASC";
        $brs = $br->db->exec($sql, array($journal_id, $issue_month, $issue_year));

				$addr = new Address();
				foreach($brs as &$br){
					$sql = "SELECT Address1, Address2, City, State,Country, ZIPCode, voice, email,Company, Department
					        FROM address
									WHERE person_id = ?";
					$addrs = $addr->db->exec($sql, array($br['person_id']));

					if(isset($addrs[0])){
						$br['Address1'] = $addrs[0]['Address1'];
						$br['Address2'] = $addrs[0]['Address2'];
						$br['Company'] = $addrs[0]['Company'];
						$br['Department'] = $addrs[0]['Department'];
						$br['City'] = $addrs[0]['City'];
						$br['State'] = $addrs[0]['State'];
						$br['Country'] = $addrs[0]['Country'];
						$br['ZIPCode'] = $addrs[0]['ZIPCode'];
						$br['voice'] = $addrs[0]['voice'];
						$br['email'] = $addrs[0]['email'];
					}
				}

        if(isset($_SESSION['JR'])){
            $smarty = $_SESSION['JR']->getSmarty();
            $smarty->assign('reviews', $brs);

            $j = new Journal($journal_id);
            $smarty->assign('journal',$j->journal);

            $months = array('January',"Febuary",'March',"April",'May',"June",'July',"August",'September',"October",'November',"December");
            $smarty->assign('month',$months[$issue_month-1]);

            $smarty->assign('year',$issue_year);
    
            $html = $smarty->fetch('author_information.tpl');
       
            $ret = array(
                array(
                    'id' => 'author_information_list',
                    'html' => $html
                )
            );

            return json_encode($ret);
        }

    
    }

		    function searchBookReviews($params){

        $limit = false;
        $offset = false;

        if(isset($params['order'])){
            switch($params['order']){
                case "book_title":
                    $order = "book_title";
                    $order_by = "book_title ASC";
                    break;
                case "ae_name":
                    $order    = "ae_name";
                    $order_by = "ae.last_name, ae.first_name ASC";
                    break;
                case "rev_name":
                    $order = "rev_name";
                    $order_by = "rev.last_name, rev.first_name ASC";
                    break;
                case "date_promised":
                case "date_received":
                    $order = $params['order'];
                    $order_by = $params['order'] . " DESC";
                    break;
                default:
                    $order = "book_title";
                    $order_by = "book_title ASC";
                    
            }
        } else {
            $order = "book_title";
            $order_by = "book_title ASC";
        }

        if(!isset($params['search'])){
            $params['search'] = "Everything";
        }

        $select = "SELECT br.book_review_id as book_review_id, b.title as book_title,
                          j.journal as journal, rt.review_type as review_type,
                          br.date_promised as date_promised, br.date_received as date_received,
                          CONCAT(ae.first_name, ' ', ae.last_name) as ae_name, 
                          CONCAT(rev.first_name, ' ', rev.last_name) as rev_name ";

        $from = "FROM book_review br
                  LEFT OUTER JOIN person ae
                    ON ae.person_id = br.assoc_editor_id
                  LEFT OUTER JOIN person rev
                    ON rev.person_id = br.reviewer_id
                  LEFT OUTER JOIN book b
                    ON b.book_id = br.book_id
                  LEFT OUTER JOIN journal j
                    ON br.journal_id = j.journal_id
                  LEFT OUTER JOIN review_type rt
                    ON br.review_type_id = rt.review_type_id ";

        switch($params['search']){
            case "Everything":
                $where = " WHERE b.title like '".addslashes($params['query'])."%'";
                $ex = explode(" ",$params['query']);
                foreach($ex as $e){
                    $where .= " OR rev.last_name like '%".addslashes($e)."%'
                                OR rev.first_name like '%".addslashes($e)."%' 
                                OR ae.last_name like '%".addslashes($e)."%'
                                OR ae.first_name like '%".addslashes($e)."%'";
                    
                }
                break;
            case "Book Title":
                $where = " WHERE b.title like '".addslashes($params['query'])."%'";
                break;
            case "Reviewer":
                $ex = explode(" ",$params['query']);
                $where = " WHERE ";
                foreach($ex as $e){
                    $where .= " rev.last_name like '%".addslashes($e)."%'
                               OR rev.first_name like '%".addslashes($e)."%' OR";
                }
                $where = substr($where,0,-2);
                break;
            case "Assoc Editor":
                $ex = explode(" ",$params['query']);
                $where = " WHERE ";
                foreach($ex as $e){
                    $where .= " ae.last_name like '%".addslashes($e)."%'
                               OR ae.first_name like '%".addslashes($e)."%' OR";
                }
                $where = substr($where,0,-2);
                break;
            default:
                //Default means something is wrong- so let us just abort the whole thing- for safety.
                return array();
                break;
        }
        $post = "";
        if(isset($params['order'])){
            $post .= " ORDER BY ".$order_by;
        }
        if(isset($params['limit'])){
            $post .= " LIMIT ".$params['limit'];
            $limit = $params['limit'];
            
            if(isset($params['offset'])){
                $post .=" OFFSET ".$params['offset'];
                $offset = $params['offset'];
            }
        }

        

        $db = new DB("book_review");

        $revs = $db->exec($select.$from.$where.$post);
        if(empty($revs)) print_r($db->getError());

        $count = $db->exec("SELECT COUNT(*) as total ".$from.$where);
        if(empty($count)) print_r($db->getError());
        $count = $count[0]['total'];


        $ex = explode(" ",$params['query']);
        foreach($revs as &$r){
            $r['rev_name']   = utf8_encode($r['rev_name']);
            $r['ae_name']    = utf8_encode($r['ae_name']);
            $r['book_title'] = utf8_encode($r['book_title']);
            if($r['date_received']) $r['date_received'] = date("n/d/Y", $r['date_received']); 
            if($r['date_promised']) $r['date_promised'] = date("n/d/Y", $r['date_promised']);

            
            foreach($ex as $e){
                switch($params['search']){
                    case "Everything":
                        $r['rev_name']   = highlightStr($r['rev_name'],$e);
                        $r['ae_name']    = highlightStr($r['ae_name'],$e);
                        $r['book_title'] = highlightStr($r['book_title'],$e);
                        break;
                    case "Book Title":
                       $r['book_title'] = highlightStr($r['book_title'],$e); 
                        break;
                    case "Assoc Editor":
                        $r['ae_name']    = highlightStr($r['ae_name'],$e);
                        break;
                    case "Reviewer":
                        $r['rev_name']   = highlightStr($r['rev_name'],$e);
                        break;
                }
            }
        }


        $smarty = $_SESSION['JR']->getSmarty();
        if($limit === false){
            $begin = 0;
            $end = $count;
        } else {
            if($offset !== false){
                $begin = ($offset == 0) ? 0 : $offset;
                $end = ($begin+$limit > $count) ? $count : $begin+$limit;
            } else {
                $begin = 0;
                $end = $limit;
            }
        }

        $smarty->assign('reviews', $revs);
        $smarty->assign('query', $params['query']);
        $smarty->assign('search', $params['search']);
        $smarty->assign('total', $count);
        $smarty->assign('begin', $begin);
        $smarty->assign('end',   $end);
        $smarty->assign('limit', $limit);
        $smarty->assign('offset', $offset);
        $smarty->assign('order', $order);

        $html = $smarty->fetch('reviews_search_results.tpl');
        
        $ret = array(array('id'=>'reviews_content','html'=>$html));
        $ret[] = array(
            'js' => "
                $('#reviews_list>ul>li').bind('click',function(){
                    window.location = uri+'/reviews/edit/'+this.id;
                });
            "
        );


        return $ret;

    }



    function getMyReviews($user_id){

        $select = "SELECT br.book_review_id as book_review_id, b.title as book_title,
                          j.journal as journal, rt.review_type as review_type,
                          FROM_UNIXTIME(br.date_promised, '%Y-%m-%d') as date_promised, 
													FROM_UNIXTIME(br.date_received, '%Y-%m-%d') as date_received,
                          CONCAT(ae.first_name, ' ', ae.last_name) as ae_name, 
                          CONCAT(rev.first_name, ' ', rev.last_name) as rev_name ";

        $from = "FROM book_review br
                  LEFT OUTER JOIN person ae
                    ON ae.person_id = br.assoc_editor_id
                  LEFT OUTER JOIN person rev
                    ON rev.person_id = br.reviewer_id
                  LEFT OUTER JOIN book b
                    ON b.book_id = br.book_id
                  LEFT OUTER JOIN journal j
                    ON br.journal_id = j.journal_id
                  LEFT OUTER JOIN review_type rt
                    ON br.review_type_id = rt.review_type_id ";

        $where = " WHERE ae.person_id = ".$user_id;
        
				$post = " ORDER BY IF(date_received is null, 9999999999, date_received) DESC, date_promised DESC ";
        
        $db = new DB("book_review");

        $revs = $db->exec($select.$from.$where.$post);
        if(empty($revs)) print_r($db->getError());

				return $revs;
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
                    //Anyone can look up and search for book reviews
                    return true;
                    break;
                case 'create':
                case 'update':
                case 'delete':
                    //Editors and Administrators can create/update/delete book reviews
                    return $user['role_id'] != ROLE::REVIEWER;
                    break;
            }
        }
        
        return false;
    }


    function formatForDisplay($k, $v){
        switch($k){
            case "date_promised":
            case "date_received":
            case "date_sent":
            case "date_ct_sent":
            case "date_ct_received":
                if($v) $v = date("m/d/Y",$v);
                break;
        }

        return $v;
    }

    function formatForStore($k, $v){
        switch($k){
            case "date_promised":
            case "date_received":
            case "date_sent":
            case "date_ct_sent":
            case "date_ct_received":
                if(!is_numeric($v)) $v = strtotime($v);
                break;
            case "issue_year":
                $v = intval($v);

                if($v == 0){
                    return "";
                } else {
                    //2 digit years
                    if($v < 80){
                        $v += 2000;
                    }
                }
        }

        return $v;
    }

    function sortByBookTitle($a, $b){
        $dr1 = $a['date_received'];
        $dr2 = $b['date_received'];
        $t1 = $a['book']['title'];
        $t2 = $b['book']['title'];



        /*
         *  My interpretation of the following statement:
         *   Order the books by title, but put recieved reviews
         *   below non-received
         */

        return (($dr1 && $dr2)&&($t1>$t2)) ||
                (!($dr1&&$dr2)&&($t1>$t2)) ||
                ($dr1&&!$dr2);

    }

    function sortPendingReviews($a,$b){
        return ($a['book']['title'] < 
               $b['book']['title']); 

    }

}
