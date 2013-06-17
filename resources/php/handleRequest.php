<?php
    ini_set('display_errors',1);

    require_once('StudentRecords.class.php');
 
    session_start();

    $cls = '';
    $fn = '';
    $id = null;
    $params = array();

    if(isset($_POST['cls'])){
        $cls = $_POST['cls'];
    }
    if(isset($_POST['fn'])){
        $fn = $_POST['fn'];
    }
    if(isset($_POST['params'])){
        $params = $_POST['params'];
    }
    if(isset($_POST['id'])){
        $id = $_POST['id'];
    }


 
    if($cls == "") {
        if(isset($_SESSION['SR']) && $fn != "") {
            $ret = call_user_func_array(array($_SESSION['SR'], $fn), $params);
        
            print json_encode($ret);
    
        }
    } else {
        try {
            $ret = call_user_func_array(array(new $cls($id),$fn), $params);
        } catch (Exception $e){
            $ret = array(array('exception'=>$e->getMessage()));
        }
        print json_encode($ret);
    }


?>
