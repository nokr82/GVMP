<?php 
    include_once($_SERVER['DOCUMENT_ROOT'].'/beosyong/dbConn.php');

    // 출금신청 내역 삭제를 처리하는 백엔드 로직

    if( $_POST["datetime"] == "" || $_POST["mb_id"] == "" ) {
        echo "false"; exit();
    }
    
    $row = mysql_fetch_array(mysql_query("select * from withdrawTBL where mb_id = '{$_POST["mb_id"]}' and datetime = '{$_POST["datetime"]}'"));
    
    mysql_query("insert into pointTBL set mb_id = '{$_POST["mb_id"]}', point = {$row["point"]}, way = '출금신청취소', withdrawPoint = 0") or die("false");
    mysql_query("insert into pointTBL set mb_id = '{$_POST["mb_id"]}', point = -{$row["point"]}, way = '출금신청취소', withdrawPoint = -{$row["point"]}") or die("false");
    mysql_query("update withdrawTBL set cancel = 'Y', cancelDatetime = now() where mb_id = '{$_POST["mb_id"]}' and datetime = '{$_POST["datetime"]}'") or die("false");
    mysql_query("update g5_member set point = point + {$row["point"]} where mb_id = '{$_POST["mb_id"]}'") or die("false");

    echo "true";
?>