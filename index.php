<?php

    include("resources/php/BookReviews.class.php");
    session_start();
    
    if(isset($_SERVER['REQUEST_URI'])){

        $req = str_replace(URI,"",$_SERVER['REQUEST_URI']);
        $req = explode("?", $req);
        $req = $req[0];
        $data = explode("/", $req);
        array_shift($data);

        if(isset($data[1]) && $data[1] == "clear"){
            session_unregister('JR');
            $sub = $data[0];
            array_shift($data);
            header("Location: ".URI."/$sub/".implode("/",$data));
            exit;
        } else {
            if(isset($data[0]) && $data[0] == ""){
                $data = array();
            }
        }

    }else {
        $data = array();
    }

    //I like not having a persistent session better
    if(!isset($_SESSION['JR'])){
        $_SESSION['JR'] = new BookReviews();
    }

    
    $_SESSION['JR']->hub($data); 


    //session_write_close();
   

?>
