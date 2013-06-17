<?php
include("JSON.php");

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


    /* DATABASE CONFIGURATION */
    $host     = "localhost";
    $database = "jasa";
    $user     = "root";
    $password = "mysql 1s g00d";

    $dbh = new PDO("mysql:dbname={$database};host={$host}", $user, $password);
    
    
    $sql = "SELECT Book, MktInfo FROM jasa_export.Books WHERE MktInfo is not null";
    $selectStmt = $dbh->prepare($sql);

    //Execute the prepared statement with the binds array
    if($selectStmt->execute()){
        $rows = $selectStmt->fetchAll(PDO::FETCH_ASSOC);
        $selectStmt = null;
           
        foreach($rows as $r){
            $mktinfo = array();

            //Find ISBN!!
            $isbn = explode(".",$r['MktInfo']); 
            if(strpos($isbn[0], "ISBN") !== false){
                $isbn = explode(" ",$isbn[0]);
                $isbn = str_replace("-","",$isbn[1]);
                if(strlen($isbn) != 10 && strlen($isbn) != 13){
                    $isbn = null;
                }
            } else {
                $isbn = null;
            }

            if($isbn != null){
                $sql = "INSERT INTO book_marketing_info (isbn) VALUES('".$isbn."')";
                $ret = $dbh->exec($sql);
            
                $sql = "SELECT MAX(book_marketing_info_id) as bmi FROM book_marketing_info";
                $ret = $dbh->query($sql)->fetchAll();


                $sql = "UPDATE book SET book_marketing_info_id = ".$ret[0]['bmi']." WHERE old_id = ".$r['Book'];
                $dbh->exec($sql);
            }

        }
    } else {
        print_r($sql);
        $this->error = $selectStmt->errorInfo();
        return false;
    }



