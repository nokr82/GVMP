<?php
    include_once('./getMemberInfo.php');

    $searchArray = array();

    $mainID;
    if( $_POST['loginID'] == 'admin' ) {
        $mainID = '00000001';
    } else {
        $mainID = $_POST['loginID'];
    }

    teamCount($mainID, false);

    foreach ($mb_info_1T as $value) {
        if( $value[0] == $_POST['searchName'] ) {
//            $result = mysql_query("select * from genealogy where mb_id = '{$value[1]}'");
            $result = mysql_query("select recommenderName,sponsorName from genealogy where mb_id = '{$value[1]}'");
            $row = mysql_fetch_array($result);

//            $result2 = mysql_query("select * from g5_member where mb_id = '{$value[1]}'");
            $result2 = mysql_query("select renewal,accountRank from g5_member where mb_id = '{$value[1]}'");
            $row2 = mysql_fetch_array($result2);

            $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row2['renewal']}', interval +4 month) AS date"));
            $timestamp = $dateCheck_1["date"];

            array_push( $searchArray, array($value[0], $value[1], $row['recommenderName'], $row2['accountRank'], $timestamp, $row['sponsorName'] ) );
        }
    }

    foreach ($mb_info_2T as $value) {
        if( $value[0] == $_POST['searchName'] ) {
//            $result = mysql_query("select * from genealogy where mb_id = '{$value[1]}'");
            $result = mysql_query("select recommenderName,sponsorName from genealogy where mb_id = '{$value[1]}'");
            $row = mysql_fetch_array($result);

//            $result2 = mysql_query("select * from g5_member where mb_id = '{$value[1]}'");
            $result2 = mysql_query("select renewal,accountRank from g5_member where mb_id = '{$value[1]}'");
            $row2 = mysql_fetch_array($result2);

            $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row2['renewal']}', interval +4 month) AS date"));
            $timestamp2 = $dateCheck_1["date"];

            array_push( $searchArray, array($value[0], $value[1], $row['recommenderName'], $row2['accountRank'], $timestamp2, $row['sponsorName']) );
        }
    }
    
    if( $_POST['check'] == "회원가입" && $_POST['searchName'] == "배형무" ) {
        array_push( $searchArray, array("배형무", "00000001", "VMP", "대표사업자", "N/A", "VMP") );
    }

    echo json_encode($searchArray);
?>
