<?php
include_once('./getMemberInfo_test.php');
//홍동우 2019-12-19
$searchArray = array();

$mainID;
if ($_POST['loginID'] == 'admin') {
    $mainID = '00000001';
} else {
    $mainID = $_POST['loginID'];
}

sel_teamCount($mainID, '', $_POST['searchName']);//문제!!!
//array($row2['mb_name'], $row2['mb_id'], $row2['recommenderID'], $row2['recommenderName'], $row2['renewal'], $row2['accountType'])

foreach ($mb_info_1T as $value) {
    if ($value[0] == $_POST['searchName']) {
        $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$value[4]}', interval +4 month) AS date"));
        $timestamp = $dateCheck_1["date"];
        array_push($searchArray, array($value[0], $value[1], $value[3], $value[5], $timestamp, $value[6]));
    }
}
foreach ($mb_info_2T as $value) {
    if ($value[0] == $_POST['searchName']) {
        $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$value[4]}', interval +4 month) AS date"));
        $timestamp = $dateCheck_1["date"];
        array_push($searchArray, array($value[0], $value[1], $value[3], $value[5], $timestamp, $value[6]));
    }
}

/*
foreach ($mb_info_1T as $value) {
    if ($value[0] == $_POST['searchName']) {
//        $result = mysql_query("select * from genealogy where mb_id = '{$value[1]}'");
//        $result = mysql_query("select recommenderName,sponsorName from genealogy where mb_id = '{$value[1]}'");
//        $row = mysql_fetch_array($result);
//
//        $result2 = mysql_query("select * from g5_member where mb_id = '{$value[1]}'");
//        $result2 = mysql_query("select renewal,accountRank from g5_member where mb_id = '{$value[1]}'");
//        $row2 = mysql_fetch_array($result2);

//        $timestamp = date("Y-m-d", strtotime($row2['renewal'] . "+1 months"));
        array_push($searchArray, array($value[0], $value[1], $value[3], $value[5], $value[4], $value[3]));
    }
}*/

/*foreach ($mb_info_2T as $value) {

    if ($value[0] == $_POST['searchName']) {
//        $result = mysql_query("select * from genealogy where mb_id = '{$value[1]}'");
//        $result = mysql_query("select recommenderName,sponsorName from genealogy where mb_id = '{$value[1]}'");
//        $row = mysql_fetch_array($result);
//
//        $result2 = mysql_query("select * from g5_member where mb_id = '{$value[1]}'");
//        $result2 = mysql_query("select renewal,accountRank from g5_member where mb_id = '{$value[1]}'");
//        $row2 = mysql_fetch_array($result2);
//
//        $timestamp2 = date("Y-m-d", strtotime($row2['renewal'] . "+1 months"));

        array_push($searchArray, array($value[0], $value[1], $value[3], $value[5], $value[4], $value[3]));
    }


}*/

if ($_POST['check'] == "회원가입" && $_POST['searchName'] == "배형무") {
    array_push($searchArray, array("배형무", "00000001", "VMP", "대표사업자", "N/A", "VMP"));
}


echo json_encode($searchArray);
?>
