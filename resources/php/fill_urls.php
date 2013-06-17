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


            $key = "ABQIAAAAgxJ3M3nR7Uih6p3Dmr5T_hSzsZ4ElYxNBo_-ioHLHyQmXC0tRBTHFmiiCZDUa8ucQP_qS0ezOBazIw";


    /* DATABASE CONFIGURATION */
    $host     = "localhost";
    $database = "jasa";
    $user     = "root";
    $password = "mysql 1s g00d";

    $dbh = new PDO("mysql:dbname={$database};host={$host}", $user, $password);
    
    
    $sql = "SELECT book_id, bmi.book_marketing_info_id as book_marketing_info_id, isbn FROM book, book_marketing_info bmi WHERE book.book_marketing_info_id = bmi.book_marketing_info_id AND isbn is not null AND pages is null;";
    $selectStmt = $dbh->prepare($sql);

    //Execute the prepared statement with the binds array
    if($selectStmt->execute()){
        $rows = $selectStmt->fetchAll(PDO::FETCH_ASSOC);
        $selectStmt = null;
           
        $i = 0;
        foreach($rows as $r){
            $i++;
            $url = "https://ajax.googleapis.com/ajax/services/search/books?" .
            "v=1.0&q=ISBN:".$r['isbn']."&key=".$key."&userip=".$_SERVER['REMOTE_ADDR'];

            // sendRequest
            // note how referer is set manually
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_REFERER, "https://streaming.stat.iastate.edu");
            $body = curl_exec($ch);

            $ret = json_decode($body, false);
            curl_close($ch);

            if(isset($ret->responseData->results[0])){
                $sql = "UPDATE book_marketing_info SET pages = ? WHERE book_marketing_info_id = ?";
                $upstmt = $dbh->prepare($sql);
                $upstmt->execute(array($ret->responseData->results[0]->pageCount, $r['book_marketing_info_id']));
                echo "($i of ".count($rows).") {$r['isbn']} succeeded.<br>";
            } else {
                echo "<font color='red'>{$r['isbn']} failed.</font><br>";
            }
        }

        //clean up

        return $rows;
    } else {
        print_r($sql);
        $this->error = $selectStmt->errorInfo();
        return false;
    }



