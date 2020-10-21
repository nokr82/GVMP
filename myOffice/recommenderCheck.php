<?php
    include_once ('./dbConn.php');

    //나의 추천인이
    //지정하려는 후원인 상위 상위 상위 중에 있는 지 체크하기(AJAX응답용) CU를 VM으로 가입시킬 때 사용하기 적절한 로직.
    
    $_POST["mb_id"] = trim($_POST["mb_id"]);
    $_POST["spon_id"] = trim($_POST["spon_id"]);
    
    
    // 존재하는 아이디인지 체크하는 로직
    $g5_memberRow = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$_POST["mb_id"]}'"));
    if( $g5_memberRow["mb_id"] == "" ) {
        echo "false";
        exit();
    }
    
    
    // 추천인 지정이 돼 있는 지 체크하는 로직
    $geneRow = mysql_fetch_array(mysql_query("select * from genealogy where mb_id = '{$_POST["mb_id"]}'"));
    if( $geneRow["recommenderID"] == "" ) {
        echo "false";
        exit();
    }
    
    // 지정하려는 후원인이 추천인이면 참
    if( $_POST["spon_id"] == $geneRow["recommenderID"] ) {
        echo "true";
        exit();
    }
    
    $temp = true;
    $tempID = $_POST["spon_id"];
    while( $temp ) {
        $row = mysql_fetch_array(mysql_query("select * from genealogy where mb_id = '{$tempID}'"));
        if( $row["sponsorID"] == $geneRow["recommenderID"] ) {
            echo "true";
            exit();
        }
        
        if( $row["sponsorID"] == "99999999" || $row["sponsorID"] == "00000000" || $row["sponsorID"] == "00003655" || $row["sponsorID"] == "" ) {
            echo "false";
            exit();
        }
        $tempID = $row["sponsorID"];
    }
    
    echo "false";
?>