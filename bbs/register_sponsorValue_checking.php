<?php 
    header("Content-Type: text/html; charset=UTF-8");
    
    
    $reg_mb_sponsor = $_GET['reg_mb_sponsor']; // 추천인 이름
    $reg_mb_sponsor_no = $_GET['reg_mb_sponsor_no']; // 추천인 ID
    
    if( $reg_mb_sponsor == "VMP" && $reg_mb_sponsor_no == "00000000" ) {
        echo "true";
        exit;
    }
    
    $conn = mysql_connect("192.168.0.30", "root", "Neungsoft1!");
    mysql_select_db("gyc5", $conn);
    
    mysql_query("set session character_set_results=utf8;");
    
    $result = mysql_query("select mb_id, mb_name from g5_member where 
(mb_id = '{$reg_mb_sponsor_no}' and ! (accountType = 'CU' and renewal is null))");

    $row = mysql_fetch_row($result);
    
    if( $row[0] ==  $reg_mb_sponsor_no && $row[1] == $reg_mb_sponsor ) {
        echo "true";
    } else {
        echo "false";
    }
?>