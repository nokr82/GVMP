<?php
    include_once('./dbConn.php');
    include_once('./_common.php');
    
    // 회원의 추천인을 변경하는 로직


    $result1 = mysql_query("select count(mb_id) as a, mb_name from g5_member where mb_id = '{$_POST['before2']}'");
    $row1 = mysql_fetch_array($result1);
    if( $row1['a'] == 0 ) {
        alert( $_POST['before2'] . '는 존재하지 않는 아이디입니다.' , "/adm/member_modify.php");
    }
    
    $result2 = mysql_query("select count(mb_id) as a, mb_name from g5_member where mb_id = '{$_POST['after2']}'");
    $row2 = mysql_fetch_array($result2);
    if( $row2['a'] == 0 ) {
        alert( $_POST['after2'] . '는 존재하지 않는 아이디입니다.' , "/adm/member_modify.php");
    }
    
    $result1 = mysql_query("update genealogy set recommenderID = '{$_POST['after2']}', recommenderName = '{$row2['mb_name']}' where mb_id = '{$_POST['before2']}'");
    
    alert( $row1['mb_name'] . '님의 추천인을 ' . $row2['mb_name'] . '로 변경했습니다.', "/adm/member_modify.php" );

    
    



?>