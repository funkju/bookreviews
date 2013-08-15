<?php


require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/table.class.php");
require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/DB.class.php");

class Material extends Table {

    protected $table = "material";

    const UNDECIDED = 1;
    const REJECTED = 2;
    const DISTRIBUTE = 3;
    const ASSIGN = 4; 


     function create($data){
            $b = new Book();
            $s = $b->db->exec("SELECT MAX(b.book_num) as m, MAX(m.book_num) as m2 FROM book b, material m");

            if(!empty($s)){
                $new_book_num = ($s[0]['m'] > $s[0]['m2']) ? $s[0]['m'] + 1 : $s[0]['m2']+1;
                $data['book_num'] = $new_book_num;
            }

            return parent::create($data);
        }


    /**
     * getMaterialForm
     * 
     * Returns a form to edit material object
     *
     * @return string  JSON Encoded Return Array
     */
    function getMaterialForm() {
        if(isset($_SESSION['JR'])){

            $smarty = $_SESSION['JR']->getSmarty();


            //Get "BookReview"
            $br = new BookReview();
            $brs = $br->find(array(array('book_or_material','=',1),'AND',array('book_id','=',$this->material_id)));

            if(isset($brs[0])){
               $smarty->assign('book_review', $brs[0]->getRecord());
            } else {
               $smarty->assign('book_review', false);
            }


            //Get all AEs
            $person = new Person();
            $smarty->assign('aes', $person->find(array(array('role_id',Role::ASSOC_EDITOR)),array('ORDER'=>'last_name'), false)); 
            $smarty->assign('material',$this->getRecord());
            $smarty->assign('journals', array(
                    array('journal_id'=>Journal::JASA, 'journal' => 'JASA'),
                    array('journal_id'=>Journal::TAS, 'journal'=>'TAS')
                ));

            $smarty->assign('review_types', array(
                   array('review_type_id'=> ReviewType::TELEGRAPHIC, 'review_type' => 'Telegraphic'),
                    array('review_type_id'=> ReviewType::SHORT , 'review_type'        => 'Short'),
                   array('review_type_id'=>  ReviewType::MEDIUM , 'review_type'       => 'Medium'),
                   array('review_type_id'=>  ReviewType::LONG , 'review_type'       => 'Long')
                ));


            //Assign Roles
            $role = new Role();
            $smarty->assign('roles',$role->getAll());

            //Fetch HTML form
            $html = $smarty->fetch('material_form.tpl');

            //Build, Encode, and Return Array
            return json_encode(array(
                array(
                    'id'   => "submitted_center",
                    'html' => $html
                ),
                array(
                    'js'   => ' $("#material_save_button").bind("click", saveMaterial);
                                $("#material_discard_button").bind("click", function(){
                                    if($("#material__material_id").val()) loadMaterial($("#material__material_id").val());
                                });
                                
                                $("#material__screen_status").change(function(){
                                    if($(this).val() == 4) { $("#material__ae_id, #material_ae_label").show(); }
                                    else { $("#material__ae_id, #material_ae_label").hide(); }
                                });

                                $("#material__author_is_submitter").click(function(){
                                    $("#material_form_line_3").toggle();
                                    if(!$("#material_form_line_3").is(":visible")){
                                        $("#material__submitter_name, #material__submitter_email").addClass("dirty_input");
                                    }  
                                })
                                initInputs("#submitted_center input, #submitted_center textarea, #submitted_center select");'
                )
            ));
        }


    }

    static function getNonDistributed(){

        $br = new Material();

        $brs = $br->find(array(array(array('screen_status', '=', Material::DISTRIBUTE))));

        $dlb = new DistributionListBook();

        $nonDistributed = array();
        foreach($brs as $b){
            $book = new Book($b['book_id']);
            $book = $book->getRecord();
            $nonDistributed[] = $book;
        }

        usort($nonDistributed, array('Book','sortBookList'));


        return $nonDistributed;

    }

    static function getMaterialModal($mat_id){
        $b = new Material($mat_id);
      
        $br = new BookReview();
        $brs = $br->find(array(array('book_id',$mat_id),'AND',array('book_or_material',1)));
        $br = null;
        if($brs){
            $br = $brs[0]->getRecord();
        }

        if(isset($_SESSION['JR'])){
            $smarty = $_SESSION['JR']->getSmarty();
            $smarty->assign('material', $b->getRecord());
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

            $html = $smarty->fetch('material_modal.tpl');


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
