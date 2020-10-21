<?php
    include_once('./dbConn.php');
    
    header("Content-Type: text/html; charset=UTF-8");
    
//    mysql_query("set session character_set_connection=utf8");
//    mysql_query("set session character_set_results=utf8");
//    mysql_query("set session character_set_client=utf8");

    $result = mysql_query("select count(*) as n from g5_member where mb_email = '{$_GET['email']}'");
    $row = mysql_fetch_array($result);
    
    if( $row['n'] == 0 ) {
        echo 0;
    } else {
        echo 1;
    }


?>

