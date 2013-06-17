<?php

  include_once("resources/php/DB.class.php");

  class Report {

      private $db;

      private $start_date;
      private $end_date;

      function __construct(){
        $this->db = new DB('book');

        $this->start_date = null;
        $this->end_date = null;
      }


			function setDateRange($start, $end){
				if(!is_int($start)){
					$start = strtotime($start);
				}
				if(!is_int($end)){
					$end = strtotime($end);
				}

				if(!is_int($start) || !is_int($end)) return false;

				$this->start_date = $start;
				$this->end_date = $end;

				return true;

			}


      function getNumNewBooks(){
        if($this->start_date == null ||  $this->end_date == null) return null;

        $count = array_pop($this->db->exec("SELECT COUNT(*) as c FROM book WHERE date_added >= {$this->start_date} AND date_added <= {$this->end_date};"));
        if(isset($count['c'])) return $count['c'];
        return 0;
      }


      function getNumNewBooksWithAE(){
        if($this->start_date == null ||  $this->end_date == null) return null;


        $n = array_pop($this->db->exec("SELECT COUNT(*) as c FROM book_review,book WHERE book.book_id = book_review.book_id AND
																																							date_added >= {$this->start_date} AND 
                                                                              date_added <= {$this->end_date} AND 
                                                                              assoc_editor_id is not null"));
				
        if(isset($n['c'])) return $n['c'];
        return 0;
      }

      function getNumNewBookWithoutAE() {
        if($this->start_date == null ||  $this->end_date == null) return null;

        $n = array_pop($this->db->exec("SELECT COUNT(*) as c FROM book_review, book WHERE book.book_id = book_review.book_id AND
																																	date_added >= {$this->start_date} AND 
                                                                  date_added <= {$this->end_date} AND 
																																	journal_id != 3 AND assoc_editor_id is null;"));
        if(isset($n['c'])) return $n['c'];
        return 0;
      }

      function getNumNewBooksPerJournal() {
        if($this->start_date == null ||  $this->end_date == null) return null;

        $ret = array();

        $n = $this->db->exec("SELECT journal_id, COUNT(*) as c FROM book_review, book WHERE book.book_id = book_review.book_id AND
																																	date_added >= {$this->start_date} AND 
                                                                  date_added <= {$this->end_date} 
															GROUP BY journal_id");



        foreach($n as $r){
          $ret[$r['journal_id']] = $r['c'];
        }

        return $ret;
      }

      function getNumBooksAwaitingAssignment() {
         if($this->start_date == null ||  $this->end_date == null) return null;

         $n = array_pop($this->db->exec("SELECT COUNT(*) as c FROM book b LEFT JOIN (SELECT book_id FROM book_review WHERE (journal_id is not null AND review_type_id is not null) OR journal_id = 3) br USING (book_id) LEFT JOIN book_review br2 USING(book_id) WHERE br.book_id is null ORDER BY title"));
         if(isset($n['c'])) return $n['c'];
         return 0;
      }
   

      function getNumBooksPerIssueByJournal(){

         if($this->start_date == null ||  $this->end_date == null) return null;


         $n = $this->db->exec("SELECT journal_id, issue_month, issue_year, COUNT( * ) AS c
                  FROM book_review
                  WHERE issue_year IS NOT NULL && issue_month IS NOT NULL 
                  GROUP BY journal_id, issue_year, issue_month
                  ORDER BY issue_year DESC, issue_month DESC, journal_id");

         $ret = array();
         foreach($n as $r){
            if(!isset($ret[$r['journal_id']])) $ret[$r['journal_id']] = array();
            if(!isset($ret[$r['journal_id']][$r['issue_year']])) $ret[$r['journal_id']][$r['issue_year']] = array();

           $ret[$r['journal_id']][$r['issue_year']][$r['issue_month']] = $r['c'];
         }
         
         return $ret;
      }

  }


?>
