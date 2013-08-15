<?php

require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/book_marketing_info.class.php");
require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/journal.class.php");
require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/review_type.class.php");
require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/book_review.class.php");


class Book extends Table {

    protected $table = "book";


    protected $author_exclude = array("and","the","or","if","but","then");


		function create($data){
            $b = new Book();
            $s = $b->db->exec("SELECT MAX(b.book_num) as m, MAX(m.book_num) as m2 FROM book b, material m");

            if(!empty($s)){
                $new_book_num = ($s[0]['m'] > $s[0]['m2']) ? $s[0]['m'] + 1 : $s[0]['m2']+1;
                $data['book_num'] = $new_book_num;
            }

			return parent::create($data);
		}

    function delete() {
        //Have to delete all the And book reviews!
        $br = new BookReview();
        $brs = $br->find(array(array('book_id',$this->book_id)));
        if($brs) foreach($brs as $b) $b->delete();

        //We should also delete the book_marketing_infos
        if($this->book_marketing_info_id) {
            $bmi = new BookMarketingInfo($this->book_marketing_info_id);
            $bmi->delete();
        }
        if($this->extra_book_marketing_info_id){
            $bmi = new BookMarketingInfo($this->extra_book_marketing_info_id);
            $bmi->delete();
        }

        //We should also delete it from the distribution lists it is on
        $dlb = new DistributionListBook();
        $dlbs = $dlb->find(array(array('book_id',$this->book_id)));
        if($dlbs) foreach($dlbs as $dlb){
            //And any preferences
            $dlp = new DistributionListPreference();
            $dlps = $dlp->find(array(array('distribution_list_book_id',$dlb->distribution_list_book_id)));
            if($dlps) foreach($dlps as $dlp) $dlp->delete();
            
            $dlb->delete();
        }


        //Call Parent
        return parent::delete();



    }


    static function getNonDistributed(){

        $br = new BookReview();

        $brs = $br->find(array(array(array('journal_id', '=',1),'OR',array('journal_id','=',2)),'AND',array('assoc_editor_id','is',null), 'AND', array('book_id','is not',null)),null,false);

        $nonDistributed = array();
        foreach($brs as $b){
            if($b['book_or_material'] == 0){
                $book = new Book($b['book_id']);
                $book = $book->getRecord();
                $nonDistributed[] = $book;
            } else {
                $mat = new Material($b['book_id']);
                $mat = $mat->getRecord();
                $nonDistributed[] = $mat;
            }
        }


        usort($nonDistributed, array('Book','sortBookList'));


        return $nonDistributed;

    }

    static function getBooksAssignReviews() {

        $db = new DB("book");

        $sql = "SELECT book_id, title, br2.book_review_id as book_review_id, br2.journal_id as journal_id, br2.review_type_id as review_type_id FROM book b LEFT JOIN (SELECT book_id FROM book_review WHERE (journal_id is not null AND review_type_id is not null) OR journal_id = 3) br USING (book_id) LEFT JOIN book_review br2 USING(book_id) WHERE br.book_id is null ORDER BY title";

        $books = $db->exec($sql);

        if($books === false) print_r($db->getError());
/*
        foreach($books as &$b){
            $br = new BookReview();
            $br = $br->find(array(array('book_id',$b['book_id'])));
            if($br){
                $b['book_review_id'] = $br[0]->book_review_id;
                $b['review_type_id'] = $br[0]->review_type_id;
                $b['journal_id'] = $br[0]->journal_id;
            }
        }*/

        return $books;
    }


    static function getBookModal($book_id){
        $b = new Book($book_id);
      
        
        $bmi = null;
        if($b->book_marketing_info_id){
            $bmi = new BookMarketingInfo($b->book_marketing_info_id);
            $bmi = $bmi->getRecord();
        }
        $br = new BookReview();
        $brs = $br->find(array(array('book_id',$book_id),'AND',array('book_or_material',0)));
        $br = null;
        if($brs){
            $br = $brs[0]->getRecord();
        }

        if(isset($_SESSION['JR'])){
            $smarty = $_SESSION['JR']->getSmarty();
            $smarty->assign('book', $b->getRecord());
            $smarty->assign('book_marketing_info', $bmi);
            $smarty->assign('book_review',$br);
            $smarty->assign('journals',array(
                Journal::JASA => 'JASA',
                Journal::TAS  => 'TAS',
                Journal::NO_REVIEW => 'No Review'
            ));
            $smarty->assign('review_types',array(
                ReviewType::TELEGRAPHIC => 'Telegraphic',
                ReviewType::SHORT  => 'Short',
                ReviewType::MEDIUM => 'Medium',
                ReviewType::LONG => 'Long'
            ));

            $html = $smarty->fetch('book_modal.tpl');


            $id = "book_modal_".time();
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
                )
            );


            return json_encode($ret);


        }

    }

    /**
     * getBookForm
     *
     * Returns a form to edit book object
     *
     * @return string  JSON Encoded Return Array
     */
    function getBookForm() {
        if(isset($_SESSION['JR'])){
            $smarty = $_SESSION['JR']->getSmarty();
            $smarty->assign('book',$this->getRecord());

            //Get book's marketing info 
            $book_marketing_info = new BookMarketingInfo($this->book_marketing_info_id);
            $extra_book_marketing_info = new BookMarketingInfo($this->extra_book_marketing_info_id);
            if($book_marketing_info){
                $smarty->assign('book_marketing_info',$book_marketing_info->getRecord());
            }
            if($extra_book_marketing_info){
                $smarty->assign('extra_book_marketing_info',$extra_book_marketing_info->getRecord());
            }

            //Get book's review info
            $j = new Journal();
            $smarty->assign('journals', $j->getAll());
            $rt = new ReviewType();
            $smarty->assign('review_types',$rt->getAll());
            $book_review = new BookReview();
            $book_review = $book_review->find(array(array('book_id',$this->book_id)));
            if(isset($book_review[0])){
                $smarty->assign('book_review',$book_review[0]->getRecord());
            }


            //Assign Roles
            $role = new Role();
            $smarty->assign('roles',$role->getAll());

            $html = $smarty->fetch('book_form.tpl');


            $ret = array(
                array(
                    'id'   => "book_center",
                    'html' => $html
                ),
                array(
                    
                    'js'   => '$("#book_save_button").bind("click", saveBook);
                    $("#book_discard_button").bind("click", function(){
                        if($("#book__book_id").val()) loadBook($("#book__book_id").val());
                    });
                    initInputs("#book_center input, #book_center textarea, #book_center select");'
                )
            );

            if(isset($book_review[0])){
                $ret[] = array('js'=>'$("#load_review").bind("click",function(){
                    showStatus("Loading...",false,function(){
                      window.location = "'.URI.'/reviews/edit/'.$book_review[0]->book_review_id.'";
                    });
                });');
            }

            return json_encode($ret);
        }


    }

    function searchBooks($params){
        $db = new DB("book");

        $limit = false;
        $offset = false;
        $order = "title";



        $sql = "SELECT book_id, title, authors FROM book WHERE title like '".trim($params['query'])."%'";
        $alist = explode(" ",$params['query']);
        foreach($alist as $a){
            if(strlen($a) >= 3 && array_search(strtolower($a),$this->author_exclude) === false) $sql .= " OR authors REGEXP '[[:<:]]".$a."[[:>:]]' = 1  ";
        }
        $sql .= " ORDER BY $order ASC";
        
        if(isset($params['limit'])){
            $sql.= " LIMIT ".$params['limit'];
            $limit = $params['limit'];
            
            if(isset($params['offset'])){
                $sql.=" OFFSET ".$params['offset'];
                $offset = $params['offset'];
            }
        }
        
        $books = $db->exec($sql);
        if($books === false) print_r($db->getError());

        $sql = "SELECT COUNT(*) as total FROM book WHERE title like '".trim($params['query'])."%'";
        $alist = explode(" ",$params['query']);
        foreach($alist as $a){
            if(strlen($a) >= 3 && array_search(strtolower($a),$this->author_exclude) === false) $sql .= " OR authors REGEXP '[[:<:]]".$a."[[:>:]]' = 1  ";
        }
        $count = $db->exec($sql);
        if($count === false) print_r($db->getError());
        $count = $count[0]['total'];
        

        $ex = explode(" ",$params['query']);
        foreach($books as &$b){
                $b['authors'] = utf8_encode($b['authors']);
                $b['title'] = utf8_encode($b['title']);

                $b['title_highlight'] = $b['title'];
                $b['authors_highlight'] = $b['authors'];
            foreach($ex as $e){
                $b['title_highlight'] = highlightStr($b['title_highlight'],$e);
                $b['authors_highlight'] = highlightStr($b['authors_highlight'],$e);
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
    
        $smarty->assign('books', $books);
        $smarty->assign('query', $params['query']);
        $smarty->assign('total', $count);
        $smarty->assign('begin', $begin);
        $smarty->assign('end',   $end);
        $smarty->assign('limit', $limit);
        $smarty->assign('offset',$offset);
        $smarty->assign('order', $order);

        $html = $smarty->fetch('book_search_results.tpl');

        $ret = array(array('id'=>'book_center','html'=>$html));
        $ret[] =  array(
            'js' => "
                $('#book_list>ul>li').bind('click',function(){
                    window.location = uri+'/books/edit/'+this.id;
                });
            "
        );

        return $ret;


    }



    /**
     * can
     *
     * RULES:
     *   FIND, READ: ANYONE allowed
     *   CREATE, UPDATE, DELETE: only EDITORS and ADMINISTRATORS allowed
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



    /******************
     * Static Functions
     ******************/

     /** 
      * getBookList
      *
      * returns a list of all the books id, title and authors
      *
      * @return array
      */
     static function getBookList(){
        $db = new DB("book");

        $ret = $db->read(array(),array('book_id','title','authors'),array('ORDER'=>'title'));

        return $ret;
     }


    static function sortBookList($a,$b){
        
        return isset($a['title']) && isset($b['title']) && $a['title'] > $b['title'];
    
    }
}
