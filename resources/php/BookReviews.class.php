<?php
    ini_set('display_errors',1);

	switch($_SERVER['SERVER_NAME']){
		case "bookreviews.bradleyclothing.com":
	    	$_SERVER['DOCUMENT_ROOT'] = "/home1/saltcomp/www/bookreviews";
        	define('URI', '');
			define('URL', 'http://bookreviews.bradleyclothing.com');
    		define("UPLOAD_ROOT", "/home1/saltcomp/www/bookreviews/resources/attachments");
			break;
		case "bookreviews.dev":
            $_SERVER['DOCUMENT_ROOT'] = "/Users/justinfunk/WWW/bookreviews";
            define('URI', '');
            define('URL', 'http://bookreviews.dev');
            define("UPLOAD_ROOT", "/Users/justinfunk/WWW/bookreviews/resources/attachments");
            break;
		default:
			define("URI", "/bookreviews");
			define('URL', 'http://magazine.amstat.org');
	    	define("UPLOAD_ROOT", "E:/inetpub/wwwroot/bookreviews/resources/attachments");
	}
  	

    //Set SMTP for Mailing
    ini_set('SMTP','localhost'); 


    /*** DATABASE CLASSES **/
    require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/person.class.php");
    require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/address.class.php");
    require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/role.class.php");
    require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/book.class.php");
    require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/book_marketing_info.class.php");
    require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/book_review.class.php");
    require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/book_review_attachment.class.php");
    require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/distribution_list.class.php");
    require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/distribution_list_book.class.php");
    require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/distribution_list_preference.class.php");
    require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/review_type.class.php");
    require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/journal.class.php");
    require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/material.class.php");



    /** OTHER CLASSES **/
    require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/DB.class.php");
    require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/DataStore.class.php");
    require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/Logger.class.php");
    require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/Postage.class.php");
    require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/JSON.php");
    require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/libs/Smarty.class.php");
    require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/Report.class.php");
    require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/texFunction.php");
    

    class BookReviews {
        
        const LOG_LEVEL = Logger::DEBUG;


        //DataStore object
        public $ds;
        public $user;

        function __construct() {
            $this->user = false;

            //Initialize DataStore 
            $this->ds = new DataStore();
            $this->ds->page = 1;
            $this->ds->pageSize = 10;



            //Debugging
        }



        /**
         * hub
         *
         * Directs the flow of usage through the
         * various use-cases via the URL parameters
         *
         * @param $params array the exploded parts of the URL from index.php
         * @return void
         */
        public function hub($params) {
              

            //get logger instance;
            $logger = Logger::getInstance();
                
            //Clear the type value in the store.
            $this->ds->clear(array('type', 'books_to_assign', 'back'));

            if(isset($params[0])){
                //Some services aren't protected (like login)
                if($this->user === false && 
                    !($params[0] == "svc" && isset($params[1]) && ($params[1] == "doLogin" || $params[1] == "submitContent")) &&  
                    $params[0] != "login"){
                    $_SESSION['JR_Redirect'] = implode($params,"/");
                    $href = URL.URI."/login";
                    header("Location: $href");
                    exit;
                }


                switch($params[0]){
                    //Services
                    case "svc":
                        array_shift($params);
                        if(isset($params[0])){
                            switch($params[0]){
                                //Logout service destroys the session
                                //And sends the user home
                                case "logout":
                                    $logger->log("User Logging Out",Logger::DEBUG);
                                    $this->user = false;
                                    $this->ds = false;
                                    unset($_SESSION['JR']);
                                    session_destroy();
                                    $href = URL.URI."/";
                                    header("Location: $href");

                                    break;
                                case "create":
                                    print $this->svcCreate($_POST);
                                    exit;
                                    break;
                                case "read":
                                    print $this->svcRead($_GET);
                                    exit;
                                    break;
                                case "update":
                                    print $this->svcUpdate($_POST);
                                    exit;
                                    break;
                                case "delete":
                                    print $this->svcDelete($_POST);
                                    exit;
                                    break;
                                case "call":
                                    print $this->svcCall($_GET);
                                    exit;
                                    break;
                                case "complete":
                                    array_shift($params);
                                    print $this->getComplete($params);
                                    exit;
                                    break;
                                case "doLogin":
                                    print $this->doLogin($_POST);
                                    exit;
                                    break;
                                case "bookByISBN":
                                    print $this->getBookByISBN($_POST['isbn']);
                                    exit;
                                    break;
				case "submitContent":
				    print $this->submitContent($_GET);
				    exit;
				    break;
                            }
                        } else {
                            array_shift($params);
                        }

                    //Show Main Menu
                    case "home":
                        $logger->log("Going to home",Logger::DEBUG);

						if($this->user['role_id'] == Role::ADMINISTRATOR || $this->user['role_id'] == Role::EDITOR){
							$bra = new BookReviewAttachment();
							$new = $bra->newAttachmentsSince($this->user['previous_login']);
							$this->ds->num_new_attachments = count($new);
						}


                        $this->ds->type = "home";
                        break;

                    //Show LOGIN
                    case "login":
                        $this->ds->type = "login";
                        break;

                    //Show Postage
                    case "postage":
                        array_shift($params);

                        $this->ds->postage = new Postage();
                        $this->ds->type = "postage";
                        if(isset($params[0])){
                            try {
                               switch($params[0]){
                                   case "cleanseAddress":
                                        $address = $_POST['address'];
                                        $ret = $this->ds->postage->cleanseAddress($address);
                                        if($ret['CityStateZipOK']) $this->ds->to_address = $ret['Address'];
                                        else $this->ds->to_address = array();
                                        if($ret['AddressMatch'] && isset($_POST['person_id'])){
                                            Address::updateByCleanse($_POST['person_id'], $ret);
                                        }
                                        print json_encode($ret);
                                        exit;
                                        break;
                                   case "getRates":     
                                        $rate = $_POST['rate'];
                                        $rate['FromZIPCode'] = Postage::FromZIPCode;
                                        $ret = $this->ds->postage->getRates($rate);
                                        if(isset($ret['Rate'])){
                                            $this->ds->rates = $ret['Rate'];
                                        }
                                        print json_encode($ret);
                                        exit;
                                        break;
                                   case "createIndicium":
                                        $rate       = $this->ds->rates[$_POST['rate_num']];
                                        unset($rate['AddOns']);
                                        $to         = $this->ds->to_address;
                                        $from       = Postage::$FROM_ADDRESS;
                                        $imageType  = Postage::IMAGE_TYPE;


                                        if($from && $rate){
                                            $ret = $this->ds->postage->createIndicium($rate, $from, $to, $imageType);
                                        } else {
                                            if(!$from) $ret = array('Error'=>'From Address not valid.');
                                            if(!$rate) $ret = array('Error'=>'Rate is not valid.');
                                        }
                                        print json_encode($ret);
                                        exit;
                                        break;
                                }
                            } catch(Exception $e) {
                                $ret = array(
                                    'Error' => $e->getMessage()
                                );  
                                print json_encode($ret);
                                exit;
                            }
                        }
                        break;

                    //Show PEOPLE Page
                    case "people":
                        array_shift($params);

                        $this->ds->clear('person');
                        $this->ds->clear('address');

                        if(isset($_SERVER['HTTP_REFERER'])){
                        	$hr = $_SERVER['HTTP_REFERER'];
													if(strpos($hr,'reviews/reviewsByIssue') !== false){
                              $this->ds->back = array(
                                  'name' => 'Back to Reviews By Issue',
                                  'url'  => $hr
                              );
                          } else {
                            $this->ds->back = array(
                                'name' => 'Back To Book Review',
                                'url'  => $_SERVER['HTTP_REFERER']
                            );
                          }
                        }
                        
                        //Load the PEOPLE home page
                        if(!isset($params[0])){
                            $logger->log("Going to people",Logger::DEBUG);
                            $this->ds->type = "people";

                            $role = new Role();
                            $this->ds->roles = $role->getAll();

                            if($this->user['role_id'] != Role::ADMINISTRATOR || $this->user['role_id'] != Role::EDITOR){ 
                                $this->ds->person = $this->user;

                                $role = new Role();
                                $this->ds->roles = $role->getAll();


                                $this->ds->person = $this->user;

                                $address = new Address();
                                $addr = $address->find(array(array('person_id',$this->user['person_id'])));
                                if(isset($addr[0])){
                                    $this->ds->address = $addr[0]->getRecord();
                                }

                            }



                        
                        //PEOPLE sub functions
                        } else {
                            switch($params[0]){
                                case "edit":
                                    array_shift($params);
                                    if(isset($params[0]) && $params[0]){
                                        $person = new Person($params[0]);
                                        $this->ds->person = $person->getRecord();
                                    
                                        $this->ds->type = "people";
                                        
                                        $role = new Role();
                                        $this->ds->roles = $role->getAll();


                                        //Get user address
                                        $address = new Address();
                                        $addr = $address->find(array(array('person_id',$params[0])));
                                        if(isset($addr[0])){
                                            $this->ds->address = $addr[0]->getRecord();
                                        }


                                    } else {
                                        header("Location: ".URL.URI."/people");
                                        exit;
                                        //TODO Warning- no person
                                    }
                        
                                    break;

                                //Load the person form for a person_id
                                case "loadPerson":
                                    $person = new Person($_GET['person_id']);
                                    if($person){
                                        print $person->getPersonForm();
                                    }
                                    exit;
                                    break;
                            }
                        }
                        break;
                    //Show PROFILE Page
                    case "profile":
                        //This is basically the same as the PEOPLE page
                        //except there is no user search

                        $logger->log("Going to profile", Logger::DEBUG);
                        $this->ds->type = "profile";
                        $this->ds->person = $this->user;

                        //Get user address
                        $address = new Address();
                        $addr = $address->find(array(array('person_id',$this->user['person_id'])));
                        if(isset($addr[0])){
                            $this->ds->address = $addr[0]->getRecord();
                        }
                        
                        //Get ROLE
                        $role = new Role();
                        $this->ds->roles = $role->getAll();
                        break;

		    //Show Submitted Page
		    case "submitted":
    			array_shift($params);

    			if(!isset($params[0])){
    				$logger->log("Going to submitted", Logger::DEBUG);
    				if(isset($_GET['query'])){
    					$this->ds->query = $_GET;
    				}	
    				$this->ds->type = "submitted";

    				//Get submitted content
    				$m = new Material();
    				$ms = $m->find(array(array('screen_status','=',Material::UNDECIDED)));
    				$mats = array();
    				foreach($ms as $m) $mats[] = $m->getRecord();
    				$this->ds->mats  = $mats;


    			
    			} else {
                        switch($params[0]){
                                //Load the person form for a person_id
                                case "loadMaterial":
                                    $mat = new Material($_GET['material_id']);
                                    if($mat){
                                        print $mat->getMaterialForm();
                                    }
                                    exit;
                                    break;

                        }
                }
    			break;

                    //Show BOOKS Page
                    case "books":
                        array_shift($params);
                        
                        //Get Journals for combo
                        $j = new Journal();
                        $this->ds->journals = $j->getAll();

                        //Get Review Types for combo
                        $rt = new ReviewType();
                        $this->ds->review_types = $rt->getAll();

                        //Clear Book subtype
                        $this->ds->clear("books_to_assign");
                        $this->ds->clear("book");
                        $this->ds->clear("book_review");
                        $this->ds->clear("book_marketing_info");
                        $this->ds->clear("extra_book_marketing_info");
                        $this->ds->clear("query");

                        //Go to the BOOKS homepage
                        if(!isset($params[0])){
                            $logger->log("Going to books", Logger::DEBUG);
                            if(isset($_GET['query'])){
                                $this->ds->query = $_GET;
                            }
                            $this->ds->type = "books";

                        //handle BOOKS sub-functions
                        } else {
                            switch($params[0]){
                                case "edit":
                                    array_shift($params);

                                    if(isset($_SERVER['HTTP_REFERER'])){
                                        $hr = $_SERVER['HTTP_REFERER'];
                                        if(strpos($hr,'reviews/edit') !== false){
                                            $this->ds->back = array(
                                                'name' => 'Back to Book Review',
                                                'url'  => $hr
                                            );
                                        } else if(strpos($hr,'assignReviews') !== false){
                                            $this->ds->back = array(
                                                'name' => 'Back to Assigning Reviews',
                                                'url'  => URI.'/books/assignReviews?rem_scroll=1'
                                            );
                                        } else {
                                            $url = URI."/books";
                                            if($this->ds->exists("book_query")){
                                                if(isset($this->ds->book_query['query'])){
                                                    $url .= "?query=".urlencode($this->ds->book_query['query']);
                                                    if(isset($this->ds->book_query['offset'])) $url.="&offset=".$this->ds->book_query['offset'];
                                                    if(isset($this->ds->book_query['order'])) $url.="&order=".$this->ds->book_query['order'];
                                                }
                                            }
                                            $this->ds->back = array(
                                                'name' => 'Back to Book Search',
                                                'url'  => $url
                                            );
                                        }
                                    }       

                                    if(isset($params[0]) && $params[0]){
                                        $book = new Book($params[0]);
                                        $this->ds->book = $book->getRecord();
                                        $this->ds->type = "books";

                                         //Get book's marketing info
                                        $book_marketing_info = new BookMarketingInfo($book->book_marketing_info_id);
                                        $extra_book_marketing_info = new BookMarketingInfo($book->extra_book_marketing_info_id);
                                        if($book_marketing_info){
                                            $this->ds->book_marketing_info = $book_marketing_info->getRecord();
                                        }
                                        if($extra_book_marketing_info){
                                            $this->ds->extra_book_marketing_info =$extra_book_marketing_info->getRecord();
                                        }
                                    
                                        //Get review info
                                        $book_review = new BookReview();
                                        $book_review = $book_review->find(array(array('book_id',$book->book_id)));
                                        if(isset($book_review[0])){
                                            $this->ds->book_review = $book_review[0]->getRecord();
                                        }




                                    } else {
                                        header("Location: ".URL.URI."/books");
                                        exit;
                                    }

                                    break;
                                case "loadBook":
                                    $book = new Book($_GET['book_id']);
                                    if($book){
                                        print $book->getBookForm();
                                    }
                                    exit;
                                    break;
                                case "assignReviews":
                                    $this->ds->type = "books";
                                    $this->ds->clear('rem_scroll');

                                    $url = URI."/books";
                                    if($this->ds->exists("book_query")){
                                         if(isset($this->ds->book_query['query'])){
                                            $url .= "?query=".urlencode($this->ds->book_query['query']);
                                            if(isset($this->ds->book_query['offset'])) $url.="&offset=".$this->ds->book_query['offset'];
                                            if(isset($this->ds->book_query['order'])) $url.="&order=".$this->ds->book_query['order'];
                                         }
                                    }
                                    $this->ds->back = array(
                                        'name' => 'Back to Book Search',
                                        'url'  => $url
                                    );

                                    $books = Book::getBooksAssignReviews();

                                    if(isset($_GET['rem_scroll'])) $this->ds->rem_scroll = 1;
                                    $this->ds->books_to_assign = $books;

                                    break;
                                case "searchBooks":
                                    $book = new Book();
                                    $this->ds->book_query = $_GET;

                                    print json_encode($book->searchBooks($_GET));
                                    exit;
                                    break;
                            }
                        }   

                        break; 

                    //Show REVIEWS Page
                    case "reviews":
                        array_shift($params);
                        $this->ds->type = "reviews";
                        $this->ds->clear("query");
                        $this->ds->clear("pending_reviews");
                        $this->ds->clear("book_review");

                        //Go to the REVIEWS home page
                        if(!isset($params[0])){
                            $logger->log("Going to reviews.", Logger::DEBUG);
                            if(isset($_GET['query'])){
                                $this->ds->query = $_GET;
                            }
                            $this->ds->reviews_type = "home";
                            $this->ds->clear('reviews_query');
                        
                        //Handle REVIEWS sub-functions
                        } else {
                            switch($params[0]){

                                //Load a form for editing a book review
                                case "edit":
                                    if(isset($_SERVER['HTTP_REFERER'])){
                                        $hr = $_SERVER['HTTP_REFERER'];
                                        
                                        if(strpos($hr,"pendingReviews")!==false){
                                            $this->ds->back = array(
                                                'name' => 'Back To Pending Reviews',
                                                'url'  => URI.'/reviews/pendingReviews?rem_ae=1'
                                            );
                                        } else if(strpos($hr,"books/edit") !== false){
                                            $this->ds->back = array(
                                                'name' => 'Back To Book',
                                                'url'  => $hr
                                            );
                                        } else if(strpos($hr,"unpublishedReviews") !== false){
                                            $this->ds->back = array(
                                                'name' => 'Back To Unpublished Reviews',
                                                'url'  => $hr
                                            );
                                        } else if(strpos($hr, "reviewsByIssue") !== false){
                                            $this->ds->back = array(
                                                'name' => 'Back to Reviews By Issue',
                                                'url'  => $hr
                                            );
																				} else if(strpos($hr, "myReviews") !== false){
																						$this->ds->back = array(
																								'name' => "Back To My Reviews",
																								'url'  => $hr
																						);
                                        } else if(strpos($hr, "mailing") !== false){
                                            $this->ds->back = array(
                                                'name' => 'Back to Mailings',
                                                'url'  => $hr
                                            );
																				} else if(strpos($hr, 'newAttachments') !== false){
																						$this->ds->back = array(
																								'name' => 'Back to New Attachments',
																								'url'  => $hr
																						);
                                        } else {
                                            $url = URI.'/reviews';
                                            if($this->ds->exists("reviews_query")){
                                               if(isset($this->ds->reviews_query['query'])){
                                                   $url .= "?query=".$this->ds->reviews_query['query'];
                                                   if(isset($this->ds->reviews_query['search'])) $url .= "&search=".urlencode($this->ds->reviews_query['search']);
                                                   if(isset($this->ds->reviews_query['offset'])) $url .= "&offset=".$this->ds->reviews_query['offset'];
                                                   if(isset($this->ds->reviews_query['order'])) $url .= "&order=".urlencode($this->ds->reviews_query['order']);
                                               }
                                            }

                                            $this->ds->back = array(
                                                'name' => "Back To Book Reviews",
                                                'url'  => $url
                                            );
                                        }
                                    }



                                    array_shift($params);
                                    if(isset($params[0])){
                                        $this->ds->reviews_type = "edit";
                                        
																				//Get Requested Object
                                        $br = new BookReview($params[0]);
                                        $br = $br->getRecord();

																				
																				$this->ds->clear('upload_error');
																				if(isset($_FILES['review_attachment_file'])){
																						
																						
																						$name = $_FILES["review_attachment_file"]['name'];
																						switch(substr($name, -4)){
																								case ".doc":
																								case "docx":
																								case ".pdf":
																								case ".tex":
																								case "atex":
																									break;
																								default:
																									$this->ds->upload_error = "Invalid File Type";
																									break;
																						}

																						if(!$this->ds->exists("upload_error")){
																							$uploaded_date = time();
																							$person_id = $this->user['person_id'];
																							$book_review_id = $br['book_review_id'];
																							$note = (isset($_POST['review_attachment_note'])) ? $_POST['review_attachment_note'] : null;
	
																							$bra = new BookReviewAttachment();
	
																							@mkdir(UPLOAD_ROOT."/".$book_review_id."/".$uploaded_date, 0777,TRUE);
																							$move_suc	=	@move_uploaded_file($_FILES["review_attachment_file"]["tmp_name"], UPLOAD_ROOT."/".$book_review_id."/".$uploaded_date."/".$_FILES["review_attachment_file"]["name"]);

																							if($move_suc){
																								$c = $bra->create(array(
																										'uploaded_date' => $uploaded_date,
																										'person_id'     => $person_id,
																										'book_review_id' => $book_review_id,
																										'note'					 => $note,
																										'filename'		   => $_FILES['review_attachment_file']['name']
																								));
		
																								$b = new Book($br['book_id']);
																								$ae = new Person($br['assoc_editor_id']);

																								//Send Notification Email
																								ini_set ( "SMTP", "exchange2010.amstat.org" ); 
																								$to = 'riker@iastate.edu, awilson@ida.org, funkju@gmail.com';
																								$headers = 'From: bookreviews@amstat.org' . "\r\n" .
  																											   'Reply-To: noreply@amstat.org' . "\r\n" .
  																											   'X-Mailer: PHP/' . phpversion();
																								
																								$subject = 'New Document Uploaded';
																								$body  = $this->user['first_name']." ".$this->user['last_name']. " uploaded a new attachment.\n\n";
																							  $body .= "Filename: ".$_FILES['review_attachment_file']['name']."\n";
																								$body .= "AE: ".$ae->first_name. " " . $ae->last_name."\n";
																								$body .= "Book: ".$b->title."\n";
																								$body .= "Note: ".$note."\n\n";
																								$body .= "See The Review: http://".$_SERVER['HTTP_HOST'].URI."/reviews/edit/".$book_review_id;
																								mail($to, $subject , $body, $headers, '-fnoreply@amstat.org');
		
																							} else {
																								$this->ds->upload_error = "Error Uploading Attachment";
																							}
																						}
																				} 

                                        
                                        //Add Book details    
                                        $book = new Book($br['book_id']);
                                        $book = $book->getRecord();
                                        $br['book'] = $book;

                                        //Add Reviewer Details
                                        $person = new Person($br['reviewer_id']);
                                        $person = $person->getRecord();
                                        $br['reviewer'] = $person;
                                        //Add Address Details
                                        $address = new Address();
                                        $address = $address->find(array(array("person_id", $br['reviewer_id'])),null,false);
                                        if(isset($address[0])) $br['reviewer']['address'] = $address[0];

                                        //Add AE Details
                                        $ae = new Person($br['assoc_editor_id']);
                                        $ae = $ae->getRecord();
                                        $br['assoc_editor'] = $ae;
                                        $address = new Address();
                                        $address = $address->find(array(array("person_id",$br['assoc_editor_id'])),null,false);
                                        if(isset($address[0])) $br['assoc_editor']['address'] = $address[0];

																				//Get Attachments
																				$attach = new BookReviewAttachment();
																				$attachments = $attach->find(array(array('book_review_id', $br['book_review_id'])),array('ORDER'=>'uploaded_date DESC'),false);
																				foreach($attachments as &$a){
																					$a['link_href'] = URI.'/resources/attachments/'.$a['book_review_id'].'/'.$a['uploaded_date'].'/'.$a['filename'];
																					$p = new Person($a['person_id']);
																					$a['first_name'] = $p->first_name;
																					$a['last_name'] = $p->last_name;
																					$a['uploaded_date'] = date('m-d-y h:i',$a['uploaded_date']);
																				}
																				$this->ds->attachments = $attachments;
																					
                                        //Put in Data Store
                                        $this->ds->book_review = $br;


                                        //Get Journals for combo
                                        $journals = array();
                                        $j = new Journal(Journal::JASA);
                                        $journals[] = $j->getRecord();
                                        $j = new Journal(Journal::TAS);
                                        $journals[] = $j->getRecord();
                                        $j = new Journal(Journal::NO_REVIEW);
                                        $journals[] = $j->getRecord();
                                        
                                        $this->ds->journals = $journals;

                                        //Get Review Types for combo
                                        $rt = new ReviewType();
                                        $this->ds->review_types = $rt->getAll();

                                    }
                                    break;

                               //Load Search Results
                               case "searchReviews":
                                    $r = new BookReview();
                                    $revs = $r->searchBookReviews($_GET);
                                    $this->ds->reviews_query = $_GET;
                                    
                                    print json_encode($revs);
                                    exit;
                                    break;
															 case "myReviews":
																		$this->ds->back = array(
                                        'name' => 'Back To Reviews',
                                        'url'  => URI.'/reviews'
                                     );


																		$r = new BookReview();
																		$revs = $r->getMyReviews($this->user['person_id']);

																		$this->ds->reviews_type = "myReviews";
																		$this->ds->reviews = $revs;
																		$this->ds->total = count($revs);
																		break;

                               case "pendingReviews":
                                    $this->ds->clear("rem_ae");

                                    //Set Back
                                    $url = URI.'/reviews';
                                    if($this->ds->exists("reviews_query")){
                                       if(isset($this->ds->reviews_query['query'])){
                                           $url .= "?query=".$this->ds->reviews_query['query'];
                                           if(isset($this->ds->reviews_query['search'])) $url .= "&search=".urlencode($this->ds->reviews_query['search']);
                                           if(isset($this->ds->reviews_query['offset'])) $url .= "&offset=".$this->ds->reviews_query['offset'];
                                           if(isset($this->ds->reviews_query['order'])) $url .= "&order=".urlencode($this->ds->reviews_query['order']);
                                       }
                                    }
                                    $this->ds->back = array(
                                        'name' => "Back To Book Reviews",
                                        'url'  => $url
                                    );

                                    $br = new BookReview();
                                    $brs = $br->find(array(array('date_received','is',null),'AND',array(array('journal_id','!=',Journal::NO_REVIEW),
                                                           'OR',array('journal_id','is',null))),null,false);

                                    $this->ds->num_pending = count($brs);
                                    $reviewsByAE = array();
                                    foreach($brs as &$b){
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
                                         } else {
                                            $b['book'] = array('title'=>'','book_id'=>null);
                                         }
                                        
                                         //GET AE
                                         if($b['assoc_editor_id']){
                                            $ae = new Person($b['assoc_editor_id']);
                                            $b['ae'] = array('last_name'=>$ae->last_name, 'first_name'=>$ae->first_name);
                                         }
                                         //Get Reviewer
                                         if($b['reviewer_id']){
                                            $rvwer = new Person($b['reviewer_id']);
                                            $b['reviewer'] = array('last_name'=>$rvwer->last_name, 'first_name'=>$rvwer->first_name);
                                         }

                                         //Get Review Type
                                         if($b['review_type_id']){
                                            $rt = new ReviewType($b['review_type_id']);
                                            $b['review_type'] =  $rt->review_type;
                                         }

                                         if(isset($b['ae'])){
                                            $ae = $b['ae']['last_name'].", ".$b['ae']['first_name'];
                                         } else {
                                            $ae = "*";
                                         }

                                         if(!isset($reviewsByAE[$ae])){
                                            $reviewsByAE[$ae] = array();
                                         }
                                         $reviewsByAE[$ae][] = $b;
                                    }
                                    ksort($reviewsByAE);
                                    foreach($reviewsByAE as &$r){
                                        usort($r, array("BookReview","sortPendingReviews"));
                                    }

                                    if(isset($_GET['rem_ae'])){
                                        $this->ds->rem_ae = 1;
                                    }
                                    //echo"<pre>";print_r($reviewsByAE);echo"</pre>";
                                    $this->ds->pending_reviews = $reviewsByAE;

                                    $this->ds->reviews_type = "pendingReviews";
                                    break;
                               case "unpublishedReviews":

                                    $br = new BookReview();
                                    $brs = $br->find(array(array('journal_id','!=',3), 'AND', array('date_received','is not',null),'AND',array('issue_year', 'is', null)),array('ORDER'=>'date_received'),false);
                                    
                                    $unpublished = array(
                                        Journal::JASA => array(
                                           ReviewType::TELEGRAPHIC => array(),
                                           ReviewType::SHORT       => array(),
                                           ReviewType::MEDIUM      => array(),
                                           ReviewType::LONG        => array()
                                        ),
                                        Journal::TAS => array(
                                           ReviewType::TELEGRAPHIC => array(),
                                           ReviewType::SHORT       => array(),
                                           ReviewType::MEDIUM      => array(),
                                           ReviewType::LONG        => array()
                                        )
                                    );

                                    
                                    foreach($brs as &$br){
                                        //Fill Book
                                        if($br['book_id']){
                                            $b = new Book($br['book_id']);
                                            $br['book'] = $b->getRecord();
                                        }
                                        //Fill Review
                                        if($br['assoc_editor_id']){
                                            $ae = new Person($br['assoc_editor_id']);
                                            $br['assoc_editor'] = $ae->getRecord();
                                        }
                                        if($br['reviewer_id']){
                                            $rev = new Person($br['reviewer_id']);
                                            $br['reviewer'] = $rev->getRecord();
                                        }

                                        //Fill Journal
                                        switch($br['journal_id']){
                                            case Journal::JASA: $br['journal'] = 'JASA'; break;
                                            case Journal::TAS:  $br['journal'] = 'TAS';  break;
                                        }
                                        //Fill Review Type
                                        switch($br['review_type_id']){
                                            case ReviewType::TELEGRAPHIC: $br['review_type'] = 'Telegraphic'; break;
                                            case ReviewType::SHORT:       $br['review_type'] = 'Short';       break;
                                            case ReviewType::MEDIUM:      $br['review_type'] = 'Medium';      break;
                                            case ReviewType::LONG:        $br['review_type'] = 'Long';        break;
                                        }
                                        $unpublished[$br['journal_id']][$br['review_type_id']][] = $br;
                                    }

                                    foreach($unpublished as $k=>$v){
                                        $count = 0;
                                        foreach($v as $v2){
                                            $count += count($v2);
                                        }

                                        if($count == 0){
                                            unset($unpublished[$k]);
                                        }       
                                    }

                                    $this->ds->unpublished_reviews = $unpublished;
                                    
                                    $this->ds->journals = array(
                                        array('journal'=>'JASA', 'journal_id' => Journal::JASA),
                                        array('journal'=>'TAS', 'journal_id'=> Journal::TAS)
                                    );

                                    $rt = new ReviewType();
                                    $this->ds->review_types = $rt->getAll();

                                    $this->ds->reviews_type = "unpublishedReviews";

                                    $this->ds->back = array(
                                        'name' => 'Back to Book Reviews',
                                        'url'  => URI."/reviews"
                                    );
                                    break;
															 case "orderToPublish":
															 			$this->ds->back = array(
																				'name' => 'Back to Reviews By Issue',
																				'url'  => URI."/reviews/reviewsByIssue"
																		);

																		$this->ds->reviews_type = "orderToPublish";
																		
																		break;
															 case "authorInformation":
															 			$this->ds->back = array(
																			  'name' => 'Back to Reviews By Issue',
																				'url'  => URI.'/reviews/reviewsByIssue'
																		);

																		$this->ds->reviews_type = "authorInformation";

																		break;
                               case "reviewsByIssue":
                                    $this->ds->back = array(
                                        'name' => 'Back to Book Reviews',
                                        'url'  => URI."/reviews"
                                    );

                                    if(!$this->ds->exists('journals')){
                                        //Get Journals for combo
                                        $journals = array();
                                        $j = new Journal(Journal::JASA);
                                        $journals[] = $j->getRecord();
                                        $j = new Journal(Journal::TAS);
                                        $journals[] = $j->getRecord();
                                        $this->ds->journals = $journals;
                                    }


                                    $this->ds->reviews_type = "reviewsByIssue";

                                    break;

																case "newAttachments":
												
																	if($this->user['role_id'] == Role::ADMINISTRATOR || $this->user['role_id'] == Role::EDITOR){
																		$bra = new BookReviewAttachment();
																		$new = $bra->newAttachmentsSince($this->user['previous_login']);
																		foreach($new as &$n){
																			$p = new Person($n['person_id']);
																			$n['person'] = $p->getRecord();
																		}
																		$this->ds->num_new_attachments = count($new);
																		$this->ds->new_attachments = $new;
																	}

																	$this->ds->reviews_type = 'newAttachments';

  																break;
                            }
                        }
                        break;
                    //Show DISTRIBUTION page
                    case 'distribution':
                        $this->ds->type = 'distribution';
                        $this->ds->clear("distType");
                        $this->ds->clear("distribution_books");
                        $this->ds->clear("distribution_list");
                   
                        $dl = new DistributionList();
                        $dl = $dl->find(array(array('active',1),'AND',array(array('expires','>',time()),'OR',array('expires','=',0))));
                        if($dl){
                            $dl = $dl[0];
                        } else {
                            $dl = false;
                        }

                        array_shift($params);
                        if(!isset($params[0])){
                            $logger->log("Going to distribution.", Logger::DEBUG);


                            if($dl){
                                $this->ds->distribution_list  = $dl->getRecord();
                                
                                //Get Books for this List
                                $dlb = new DistributionListBook();
                                $dlbs = $dlb->find(array(array('distribution_list_id',$dl->distribution_list_id)),null, false);
                                $books = array();
                                foreach($dlbs as $dlb){
                                    $b = new Book($dlb['book_id']);
                                    $br = $b->getRecord();
                                    $br['book_or_material'] = $dlb['book_or_material'];
                                    $books[] = $br;
                                }

                                //Get Ranks for this List
                                $dlp = new DistributionListPreference();
                                $ranks = $dlp->getPreferencesByPerson($dl->distribution_list_id, $_SESSION['JR']->user['person_id']);
                                foreach($ranks as &$r){
                                    $bk = new Book($r['book_id']);
                                    $r = array_merge($r,$bk->getRecord());

                                    if($r['book_marketing_info_id']){
                                        $bm = new BookMarketingInfo($r['book_marketing_info_id']);
                                        $r = array_merge($r,$bm->getRecord());
                                    }

                                    foreach($books as &$b){
                                        if($b['book_id'] == $r['book_id']){
                                            $b['rank'] = $r['rank'];
                                        }
                                    }
                                }

                                usort($books,"sortbooks");

                                $this->ds->distribution_books = $books;
                                $this->ds->distribution_ranks = $ranks;
                            }
                        
                        //Handle DISTRIBUTION sub-functions
                        } else {
                            switch($params[0]){
                                case "manageDistributions":
                                    $this->ds->distType = "manage";
                                    
                                    //Set Back Link
                                    $this->ds->back = array(
                                        'name' => 'Back to Make Selections Page',
                                        'url'  => URI.'/distribution'
                                    );

                                    //get Distribution Lists
                                    $distList = new DistributionList();
                                    $distLists = $distList->find(array(array(1,1)),array('ORDER'=>'name'),false);

                                    foreach($distLists as &$dl){
                                        $dl['expires_raw'] = strtotime($dl['expires']);
                                        $dl['created_raw'] = strtotime($dl['created']);
                                    }
                                    $this->ds->distLists = $distLists;
                                    break;


                                case "createDistribution":
                                    $this->ds->distType = "create";
    
                                    //Set Back Link
                                    if(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], "manageDistributions") !== false){
                                        $this->ds->back = array(
                                            'name' => 'Back to Manage Distributions',
                                            'url'  => URI.'/distribution/manageDistributions'
                                        );
                                    } else {
                                        $this->ds->back = array(
                                            'name' => 'Back To Make Selections Page',
                                            'url' => URI.'/distribution'
                                         );
                                    }


                                    $books = array();

                                    if(isset($_GET['dl_id'])){
                                        $dl = new DistributionList($_GET['dl_id']);

                                        //Get Books for this List
                                        $dlb = new DistributionListBook();
                                        $dlbs = $dlb->find(array(array('distribution_list_id',$dl->distribution_list_id)), null,false);
                                        $books = array();
                                        foreach($dlbs as $dlb){
                                            if($dlb['book_or_material'] == 0 ){
                                                $b = new Book($dlb['book_id']);
                                                $b = $b->getRecord();
                                                $b['selected'] = 1;
                                            } else {
                                                $b = new Material($dlb['book_id']);
                                                $b = $b->getRecord();
                                                $b['selected'] = 1;
                                            }
                                            $books[] = $b;
                                        }
                                        
                                        //get Non-Distributed Books
                                        $o_books = Book::getNonDistributed();
                                        $o_materials = Material::getNonDistributed();
                                        foreach($o_books as $o){
                                            $found = 0;
                                            foreach($books as $b) if($b['book_id'] == $o['book_id']) $found = 1;

                                            if(!$found) $books[] = $o;
                                        }
                                        usort($books, "sortbooks");

                                        $this->ds->books = $books;
    
                                        $dl = new DistributionList($_GET['dl_id']);
                                        $this->ds->distribution_list = $dl->getRecord();
                                     } else {
                                    
                                        //get Non-Distributed Books
                                        $books = Book::getNonDistributed();
                                        $this->ds->books = $books;

                                     }
                            
                                    break;
                                case "assignBooks":
                                    $this->ds->distType = "assign";

                                    //Set Back Link
                                    if(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], "manageDistributions") !== false){
                                        $this->ds->back = array(
                                            'name' => 'Back to Manage Distributions',
                                            'url'  => URI.'/distribution/manageDistributions'
                                        );
                                    } else {
                                        $this->ds->back = array(
                                            'name' => 'Back To Make Selections Page',
                                            'url' => URI.'/distribution'
                                         );
                                    }


                                    $this->ds->assign_by = (isset($_GET['assign_by'])) ? $_GET['assign_by'] : 'AE';

                                    if(isset($_GET['dl_id']) && $_GET['dl_id']){
                                        $dl = new DistributionList($_GET['dl_id']);
                                    }
                                    if(isset($_GET['dl_id']) && !$_GET['dl_id']) $dl = false;
                                    
                                    if($dl){
                                        $this->ds->distribution_list = $dl->getRecord();

                                        $person = new Person();
                                        $this->ds->all_aes = $person->find(array(array('role_id',Role::ASSOC_EDITOR)),array('ORDER'=>'last_name'), false);
    
                                        if($this->ds->assign_by == "AE"){
                                            $aes = DistributionListPreference::getAllPreferencesByAE($dl->distribution_list_id);            
                                            $this->ds->aes = $aes;
                                        } else {
                                            $dlbs = DistributionListPreference::getAllPreferencesByBook($dl->distribution_list_id);   
                                            $this->ds->books = $dlbs;
    
                                        }

                                    } else {
                                        $dl = new DistributionList();
                                        $dls = $dl->getAll();

                                        $this->ds->distribution_lists = $dls;
                                    }
                                    break;
                            }

                        }
                        break;
                    case 'mailing':
                        $this->ds->clear("people");
                        $this->ds->type = "mailing";
                        array_shift($params);
                   
                        $this->ds->journals = array(
                            Journal::JASA => 'JASA',
                            Journal::TAS => 'TAS'
                        );

                        $this->ds->review_types = array(
                            ReviewType::TELEGRAPHIC => 'Telegraphic',
                            ReviewType::SHORT       => 'Short',
                            ReviewType::MEDIUM      => 'Medium',
                            ReviewType::LONG        => 'Long'
                        );

                        if(!isset($params[0])){
                            $br = new BookReview();
                            $brs = $br->find(array(array('assoc_editor_id','is not',null),'AND',
                                                   array('date_sent','is',null),'AND',
                                                   array('journal_id','!=',Journal::NO_REVIEW)),null, false);

                                
                            $people = array();
                            if($brs){
                                foreach($brs as $br){
                                    if(!isset($people[$br['assoc_editor_id']])){
                                        $people[$br['assoc_editor_id']] = array('books'=>array());
                                    }
                                    
                                    $people[$br['assoc_editor_id']]['books'][] = array('review'=>$br);
                                }
                            }

                            if($people){
                                //Fill People
                                foreach($people as $id=>&$a){
                                    $a['person'] = new Person($id);
                                    $a['person'] = $a['person']->getRecord();

                                    $add = new Address();
                                    $adds = $add->find(array(array('person_id',$id)),null, false);
                                    if($adds){
                                        $a['person']['address'] = $adds[0];
                                    }

                                    foreach($a['books'] as &$b){
                                        $b['book'] = new Book($b['review']['book_id']);
                                        $b['book'] = $b['book']->getRecord();

                                        if($b['book']['book_marketing_info_id']){
                                            $bmi = new BookMarketingInfo($b['book']['book_marketing_info_id']);
                                            $b['book']['book_marketing_info'] = $bmi->getRecord();
                                        }
                                    }
                                }
                            }

                            $this->ds->people = $people;



                        } else {
                            switch($params[0]){
                                case "markAllSent":
                                    if(isset($_POST['person_id'])) {
                                        $person_id = $_POST['person_id'];
                                            
                                        $br = new BookReview();
                                        $brs = $br->find(array(array('assoc_editor_id', $person_id), 'AND',
                                                               array('date_sent','is',null),'AND',
                                                               array('journal_id','!=',Journal::NO_REVIEW)));

                                        $ret = array('book_reviews'=>array());
                                        foreach($brs as $br){
                                            $time = time();
                                            $br->date_sent = $time; 
                                            $ret['book_reviews'][] = $br->getRecord();
                                        }

                                        print json_encode($ret);

                                    } else {
                                        print "-1";
                                    }
                                        
                                    exit;
                                    break;


                            }


                            //Some conditions that may be added later


                        }
                


                        break;
                    case 'forms':
                        $this->ds->type = 'forms';
                        break;


                    case 'reports':
                        $this->ds->type = 'reports';

															$report = new Report();
															$start = strtotime("Jan 1st Midnight");
															if(!$report->setDateRange($start, time())){
																$ret = array(
																	'js'=>'alert("Could Not Set Dates");'
																);
																print json_encode($ret);
															}

															$reports = array();
															$reports['num_new_books'] = array();
															$reports['num_new_books'][date('Y', $start)] = $report->getNumNewBooks();
															$reports['have_ae'] = $report->getNumNewBooksWithAE();
															$reports['no_ae'] = $report->getNumNewBookWithoutAE();
															$reports['awaiting_assign'] = $report->getNumBooksAwaitingAssignment();
															$reports['by_journal'] = array();
															$reports['by_journal'][date('Y', $start)] = $report->getNumNewBooksPerJournal();
															$reviews_by_issue = $report->getNumBooksPerIssueByJournal();
															
															$months = array('','Jan','Feb','Mar','Apr','May','June','July','Aug','Sept','Oct','Nov','Dec');

															$r_t = array();
															$r_t[1] = array();
															$r_t[2] = array();
															$i = 0;
															foreach($reviews_by_issue[1] as $year=>$arr){
																foreach($arr as $mon=>$val){
																	$r_t[1][$months[$mon]." ".$year] = $val;
																	if(strtotime("{$months[$mon]} $year") < time()) $i++;
																	if($i == 4) break;
																}
																if($i == 4) break;
															}
															$i = 0;
															foreach($reviews_by_issue[2] as $year=>$arr){
																foreach($arr as $mon=>$val){
																	$r_t[2][$months[$mon]." ".$year] = $val;
																	if(strtotime("{$months[$mon]} $year") < time()) $i++;
																	if($i == 4) break;
																}
																if($i == 4) break;
															}
															$reports['reviews_by_issue'] = $r_t;

															$start = strtotime("Jan 1st Midnight -1 year");
															$end = strtotime("Dec 31st Midnight -1 year");
															$report->setDateRange($start,$end);
															$reports['num_new_books'][date('Y',$start)] = $report->getNumNewBooks();
															$reports['by_journal'][date('Y',$start)] = $report->getNumNewBooksPerJournal();
															
															$start = strtotime("Jan 1st Midnight -2 year");
															$end = strtotime("Dec 31st Midnight -2 year");
															$report->setDateRange($start,$end);
															$reports['num_new_books'][date('Y',$start)] = $report->getNumNewBooks();
															$reports['by_journal'][date('Y',$start)] = $report->getNumNewBooksPerJournal();


															$this->ds->current_year = date('Y');
															$this->ds->reports = $reports;
	
										break;
                }
            } else {
               //If nothing is specified
               if($this->user === false){
                   $href = URL.URI."/login";
               } else {
                   $href = URL.URI."/home";
               }
               header("Location: $href");
               exit;
            }


            //Get and Initialize Smarty Object
            $smarty = $this->getSmarty();
            $smarty = $this->cacheToSmarty($smarty); 

            //Show Main Template
            $smarty->display('main.tpl');

            //Set Last Location
        }



        public function getBookByISBN($isbn){
            $key = "ABQIAAAAgxJ3M3nR7Uih6p3Dmr5T_hSzsZ4ElYxNBo_-ioHLHyQmXC0tRBTHFmiiCZDUa8ucQP_qS0ezOBazIw";

            $url = "http://ajax.googleapis.com/ajax/services/search/books?" .
       "v=1.0&q=ISBN:".$isbn."&key=".$key."&userip=".$_SERVER['REMOTE_ADDR'];

            // sendRequest
            // note how referer is set manually
            //$ch = curl_init();
            //curl_setopt($ch, CURLOPT_URL, $url);
            //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($ch, CURLOPT_REFERER, "https://streaming.stat.iastate.edu");
            $body = file_get_contents($url);

            $ret = json_decode($body, false);
            if(isset($ret->responseData->results[0])){
                $url = $ret->responseData->results[0]->unescapedUrl."\n\n";
                $url = str_replace("printsec=frontcover&","",$url);


 //               http://books.google.com/books?id=LAxAdqv1z7kC&dq=ISBN:9781420070576&num=4&client=internal-uds&cd=1&source=uds
   //             "http://books.google.com/books/download/SAS_and_R.ris?id=LAxAdqv1z7kC&output=ris"
            
                $body = file_get_contents($url);
                $start = strpos($body, "<td id=\"bookinfo\">")+18;
                $end = strpos($body, "</td>",$start);
                $bookinfo = substr($body, $start, $end-$start);

                //Get Synopsis
                $start = strpos($bookinfo, "<div id=synopsistext");
                $end = strpos($bookinfo,"</div>",$start)+6;
                $synopsis = substr($bookinfo,$start,$end-$start);
                $synopsis = strip_tags(str_replace("<br><p>","\n<br><p>",$synopsis));
                $ret->responseData->results[0]->synopsis = $synopsis;

                //Get book metadata
                $start = strpos($body, "<table id=\"metadata_content_table\">");
                $end = strpos($body, "</table>",$start)+8;
                $metadata = substr($body,$start,$end-$start);
                $metadata = str_replace("</td>","\n</td>",$metadata);
                $metadata = strip_tags($metadata);
                $metadata = explode("\n",$metadata);
                for($i = 0; $i < count($metadata); $i++){
                    if($metadata[$i] == "Publisher"){
                        if(isset($metadata[$i+1])){
                            $ex = explode(", ",$metadata[$i+1]);
                            $ret->responseData->results[0]->publisher = $ex[0];
                        }
                    }
                }
                
            }

            $ret->responseData->results[0]->ISBN = $isbn;
            
            return json_encode($ret);
        }

				public function getNewAttachmentsURL($last_login){
					return false;
				}	



	/**
         * submitContent
	 *
 	 * Directed from hub() after the submission of
         * the submit content form.
	 *
	 * Processes content submission
	 * 
	 * Prints JSON string
	 *
	 * @param $data array Data to submit
 	 * @return string
	 * 2.25h
	 */
	public function submitContent($data){
		$logger = Logger::getInstance();

		$logger->log("Someone is submitting content.", Logger::DEBUG);

		//Check our honeypot
		if($data['hp_name'] != '' || time() - $data['hp_time'] < 20){
			$d = array('status' => -1);
		} else {
			//Unset field
			unset($data['author_yes']);
			unset($data['hp_name']);
			unset($data['hp_time']);

			//Add
			$data['screen_status'] = Material::UNDECIDED;
	
			$data['author_name'] = ($data['author_name'] == "") ? $data['submitter_name'] : $data['author_name']; 
			$data['author_email'] = ($data['author_email'] == "") ? $data['submitter_email'] : $data['author_email']; 
		

			$ret = $this->svcCreate(array('data'=>$data, 'cls'=>'Material'));

			if($ret == -1){
				$d = array('status'=> -1);
			} else {
				$d = array('status'=> $ret);
			}	
		}

		print json_encode($d);

		exit;
		
		

	}	

        /** 
         * doLogin
         *
         * Directed from hub() after the submission of 
         * the login form.
         *
         * Processes login.
         *
         * Prints JSON string
         *
         * @param $data array Data to add
         * @return string
         */
        public function doLogin($data){
            $logger = Logger::getInstance();
            
            $logger->log("Someone trying to log in.",Logger::DEBUG);
            
            //Create Person Object, create new record
            $person = Person::findPersonByUsernamePassword($data['username'],$data['password']);
            
            if($person !== false){
                $logger->log("Found a user",Logger::DEBUG);
                
                $user =  new Person($person);
                $this->user = $user->getRecord();
								
								$this->user['previous_login'] = $this->user['last_login'];	
								$user->last_login = time();
								$this->user['last_login'] = $user->last_login;


                $logger->log("Created Person Object",Logger::DEBUG);
                
                if(isset($_SESSION['JR_Redirect'])){
                    $href = URI.'/'.$_SESSION['JR_Redirect'];
                    unset($_SESSION['JR_Redirect']);
                } else {
                    $href = URI."/home";
                }
                $ret = array(
                    array(
                        'js' => "window.location = '".$href."'"
                    )
                );
            } else {
                $logger->log("Authorization Failed ({$data['username']}).",Logger::NORMAL);
                $ret = array(
                    array(
                        'js' => "$('#login_error').html('Incorrect Username or Password');
                                 $('#password').val('');
                                 $('#password').focus();"
                    )
                ); 
            }

            //return response from method
            return json_encode($ret);
        }
    
        /**
         * getUser
         *
         * Returns logged in user object.
         *
         * @return object
         */
        public function getUser(){
            return $this->user;
        }

        /**
         * svcCreate
         *
         * Creates a new record
         *
         * Params['data'] : The data to create
         * Params['cls'] : Table Name
         *
         * @param array
         * @return string JSON encoded string
         */
        public function svcCreate($params){
            $logger = Logger::getInstance();

            $logger->log("{$params['cls']}",Logger::HIGH);
            if(!isset($params['cls'])){
                return "-1";
            }

            //Creating a blank object
            if(!isset($params['data'])) $params['data'] = array();

            $logger->log("Creating Dynamic Class",Logger::DEBUG);
            $cls = new $params['cls']();
            $logger->log("Creating Object",Logger::DEBUG);
            $id = $cls->create($params['data']);

            if($id !== false){
                $logger->log("Loading created object",Logger::DEBUG);
                $cls->load($id);
                $rec = $cls->getRecord();

                return json_encode($rec);

            } else {
                return -1;
            }
        }

        /**
         * svcRead
         *
         * Returns the requested record
         *
         * Params['cls'] : Table Name
         * Params['id'] : ID
         *
         * @param array
         * @return string JSON encoded string
         */
        public function svcRead($params){
            $logger = Logger::getInstance();
            
            if(!isset($params['cls']) || (!isset($params['id']) && !isset($params['query'])) 
                    || (isset($params['id']) && !is_numeric($params['id']))){
                return "-1";
            }
       
            if(isset($params['id'])){
                $logger->log("{$params['cls']},{$params['id']}",Logger::HIGH);
                //I love PHP
                $logger->log("Creating Object",Logger::DEBUG);
                $cls = new $params['cls']($params['id']);
                $rec = $cls->getRecord();
            } else {
                $logger->log("{$params['cls']}",Logger::HIGH);
                $logger->log("Creating Object",Logger::DEBUG);
                $cls = new $params['cls']();
                $select = isset($params['select']) ? $params['select'] : "*";
                $options = array();
                if(isset($params['distinct'])) $options['DISTINCT'] = $params['distinct'];
                if(isset($params['limit'])) $options['LIMIT'] = $params['limit'];
                if(isset($params['order'])) $options['ORDER'] = $params['order'];
                $rec = $cls->find($params['query'],$options,false,$select);
            }
           
            if($rec == -1) $logger->log("Read Failed. ".print_r($params,true),Logger::DEBUG);

            $rec = array('results' => $rec, 'params'=>$params);
            $ret = json_encode($rec);
            return $ret;
        }

        /**
         * svcUpdate
         *
         * Updates the requested record
         *
         * Params['id'] : ID
         * Params['data'] : The data to set
         * Params['cls'] : Table Name
         *
         * @param array
         * @return string JSON encoded string
         */
        public function svcUpdate($params){
            $logger = Logger::getInstance();
           
            if(!isset($params['cls']) || !isset($params['id']) || !isset($params['data']) || !is_numeric($params['id'])){
                return "-1";
            }
            $logger->log("{$params['cls']}",Logger::HIGH);

            //I love PHP
            $logger->log("Creating Object({$params['id']})",Logger::DEBUG);    
            $cls = new $params['cls']($params['id']);

            foreach($params['data'] as $k=>$v){
                if($k != "password") $logger->log("Setting $k = $v",Logger::DEBUG);
                else $logger->log("Setting $k = [CONCEALED]",Logger::DEBUG);
                try{
                    $cls->$k = $v;
                } catch (Exception $e){
                    $logger->log($e->getMessage(), Logger::HIGH);
                }
                if($cls->$k != $cls->formatForStore($k,$v) && $k != "password") {
                    $logger->log("Failed to SET! ({$cls->$k} != {$v}),",Logger::HIGH);
                    return json_encode(-1);
                }
            }
            $logger->log("Reloading record.",Logger::DEBUG);
            $cls->load($params['id']);
            $rec = $cls->getRecord();

            return json_encode($rec);
        }

        /**
         * svcDelete
         *
         * Deletes the requested record
         *
         * Params['id'] : ID
         * Params['cls'] : Table Name
         *
         * @param array
         * @return string JSON encoded string
         */
        public function svcDelete($params){
            $logger = Logger::getInstance();
            
            if(!isset($params['cls']) || !isset($params['id']) || !is_numeric($params['id'])){
                return "-1";
            }
            $logger->log("{$params['cls']}, {$params['id']}",Logger::HIGH);
            

            //I love PHP
            $logger->log("Creating Object",Logger::DEBUG);
            $cls = new $params['cls']($params['id']);
            return $cls->delete();
        }

        /**
         * svcCall
         * 
         * Calls a (static) function and returns the result
         *
         * Params['cls']    : Class
         * Params['fn']     : Function name
         * Params['params'] : Params
         *
         * @param array
         * @return mixed
         */
        public function svcCall($params){
            $logger = Logger::getInstance();

            if(!isset($params['cls']) || !isset($params['fn']) || !isset($params['params'])){
                return -1;
            }

            $logger->log("Calling {$params['cls']}::{$params['fn']} from svcCall", Logger::DEBUG);
            $cls = new $params['cls'];
            return call_user_func_array(array($cls,$params['fn']),$params['params']);
        }


        /**
         * getSmarty
         *
         * Creates and returns a Smarty object
         * with some constants loaded.
         *
         * @return object
         */
        function getSmarty(){
            $smarty = new Smarty();
            $smarty->force_compile = true;
            $smarty->template_dir = $_SERVER['DOCUMENT_ROOT'].URI."/resources/templates/";
            $smarty->compile_dir  = $_SERVER['DOCUMENT_ROOT'].URI."/resources/templates_c/";

            //Debug Javascript
            $smarty->assign('debug', 1);
            $smarty->assign('uri', URI);
            $smarty->assign('server_name', $_SERVER['SERVER_NAME']);
            $smarty->assign('user', false);
            if($this->user){
                $smarty->assign('user', $this->user);
            }

            $role = array(
                'ADMINISTRATOR' => Role::ADMINISTRATOR,
                'EDITOR'        => Role::EDITOR,
                'ASSOC_EDITOR'  => Role::ASSOC_EDITOR,
                'REVIEWER'      => Role::REVIEWER
            );
            $smarty->assign('role',$role);


            $params = $this->getParams();
            $paramURL = implode("/",$params);
            $smarty->assign('params',$this->getParams());
            $smarty->assign('paramURL',$paramURL);

            return $smarty;
        }

        /**
         * cacheToSmarty
         * 
         * Fills in the given Smarty Object
         * with the key/value pairs in the 
         * datastore object
         */
        function cacheToSmarty($smarty) {
            $keys = $this->ds->getCacheKeys();
            foreach($keys as $k){
                $smarty->assign($k, $this->ds->$k);
            }


            return $smarty;
        }

        /**
         * getParams
         * 
         * Generates an array of params in the URL
         *
         * @return array
         */
        function getParams() {
            if(isset($_SERVER['HTTP_REFERER'])){
                $params = $_SERVER['HTTP_REFERER'];
                $params = str_replace(array('https://',$_SERVER['SERVER_NAME'],URI),'',$params);

                $params = explode("/",$params);
                array_shift($params);
                array_shift($params);
            
                return $params;
            }

            return array();
        }

    }


    function sortbooks($a, $b){
       if(!isset($a['title']) || !isset($b['title'])) return 0;

       return $a['title'] > $b['title'];
    }



    if ( !function_exists('json_decode') ){
        function json_decode($content, $assoc=false){
            if ( $assoc ){
                $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
            } else {
                $json = new Services_JSON;
            }
            return @$json->decode($content);
        }
    }   

    if ( !function_exists('json_encode') ){
        function json_encode($content){
            $json = new Services_JSON;
              
            return @$json->encode($content);
        }
    }


    function highlightStr($haystack, $needle) {
        // return $haystack if there is no highlight color or strings given, nothing to do.
        if (strlen($haystack) < 1 || strlen($needle) < 1) {
            return $haystack;
        }
        preg_match_all("|$needle+|i", $haystack, $matches);
        if (is_array($matches[0]) && count($matches[0]) >= 1) {
            foreach ($matches[0] as $match) {
                if(strlen($match) >= 3){
                $haystack = str_replace($match, '<span class="result_highlight">'.$match.'</span>', $haystack);
                }
            }
        }
        return $haystack;
    }


?>
