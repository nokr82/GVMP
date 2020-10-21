<?php
    include_once('./dbConn.php');
    
    // 중고장터에서 좋아요 클릭을 처리하는 백엔드 로직
    
    if( $_POST["mb_id"] == "" || $_POST["wr_id"] == "" || $_POST["type"] == "" ) {
        echo "false"; exit();
    }
    
    if( $_POST["type"] == "on" ) {
        mysql_query("update g5_write_used set heartNum = heartNum + 1 where wr_id = {$_POST["wr_id"]}") or die("false");
        
        mysql_query("insert into used_heart_list set wr_id = {$_POST["wr_id"]}, mb_id = '{$_POST["mb_id"]}'") or die("false");
    } else if( $_POST["type"] == "off" ) {
        mysql_query("update g5_write_used set heartNum = heartNum - 1 where wr_id = {$_POST["wr_id"]}") or die("false");
        
        mysql_query("delete from used_heart_list where wr_id = {$_POST["wr_id"]} and mb_id = '{$_POST["mb_id"]}'") or die("false");
    }
    

    echo "true";
?>