<?php 

    include_once('./dbConn.php');
    
    mysql_query("set session character_set_connection=utf8");
    mysql_query("set session character_set_results=utf8");
    mysql_query("set session character_set_client=utf8");
    
    $result = mysql_query("select * from teamMembers where mb_id = '{$_POST['id']}'");
    $row = mysql_fetch_array($result);
    
    $result2 = mysql_query("select * from g5_member AS A INNER JOIN genealogy AS B ON A.mb_id = B.mb_id where A.mb_id = '{$row['1T_ID']}'");
    $row2 = mysql_fetch_array($result2);
    
    $result3 = mysql_query("select * from g5_member AS A INNER JOIN genealogy AS B ON A.mb_id = B.mb_id where A.mb_id = '{$row['2T_ID']}'");
    $row3 = mysql_fetch_array($result3);
    
    $result4 = mysql_query("select * from g5_member AS A INNER JOIN genealogy AS B ON A.mb_id = B.mb_id where A.mb_id = '{$row['3T_ID']}'");
    $row4 = mysql_fetch_array($result4);
    
    $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row2['renewal']}', interval +4 month) AS date"));
    $dateCheck_11 = mysql_fetch_array(mysql_query("SELECT date_add('{$row3['renewal']}', interval +4 month) AS date"));
    $timestamp1 = $dateCheck_1["date"];
    $timestamp2 = $dateCheck_11["date"];
    
    
    // 하위 1~3개 팀 정보 제이슨 값으로 만들기
    $myObj->team1_mb_id = $row2['mb_id']; // 회원ID
    $myObj->team1_mb_name = $row2['mb_name']; // 회원이름
    $myObj->team1_recommenderName = $row2['recommenderName']; // 추천인 이름
    $myObj->team1_mb_hp = $row2['mb_hp']; // 회원 연락처
    $myObj->team1_mb_email = $row2['mb_email']; // 회원 이메일
    $myObj->team1_accountRank = $row2['accountRank']; // 회원 직급
    $myObj->team1_renewal = $timestamp1; // 회원 리뉴얼 날짜
    
    $myObj->team2_mb_id = $row3['mb_id']; // 회원ID
    $myObj->team2_mb_name = $row3['mb_name']; // 회원이름
    $myObj->team2_recommenderName = $row3['recommenderName']; // 추천인 이름
    $myObj->team2_mb_hp = $row3['mb_hp']; // 회원 연락처
    $myObj->team2_mb_email = $row3['mb_email']; // 회원 이메일
    $myObj->team2_accountRank = $row3['accountRank']; // 회원 직급
    $myObj->team2_renewal = $timestamp2; // 회원 리뉴얼 날짜
    
//    $myObj->team3_mb_id = $row4['mb_id']; // 회원ID
//    $myObj->team3_mb_name = $row4['mb_name']; // 회원이름
//    $myObj->team3_recommenderName = $row4['recommenderName']; // 추천인 이름
//    $myObj->team3_mb_hp = $row4['mb_hp']; // 회원 연락처
//    $myObj->team3_mb_email = $row4['mb_email']; // 회원 이메일
//    $myObj->team3_accountRank = $row3['accountRank']; // 회원 직급
    
    $myJSON = json_encode($myObj);

    
    echo $myJSON;
?>