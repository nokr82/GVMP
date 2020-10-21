<?php
    include_once ('./_common.php');
    include_once ('./dbConn.php');

    // 매일 0시에 보유 포인트를 DB에 저장하는 로직
    
    $result = mysql_query("select * from g5_member where mb_id != 'admin' and accountType != 'CU'");
    
    while( $row = mysql_fetch_array($result) ) {
        mysql_query("insert into retentionPointTBL set mb_id = '{$row['mb_id']}', VMC = {$row['VMC']}, VMR = {$row['VMR']}, VMP = {$row['VMP']}, V = {$row['V']}, VMG = {$row['VMG']}, VMM = {$row["VMM"]}, date = NOW()");
    }

?>