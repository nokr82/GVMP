<?php
include_once('../dbConn.php');
$searchArray = array();

$mainID;
if ($_POST['loginID'] == 'admin') {
    $mainID = '00000001';
} else {
    $mainID = $_POST['loginID'];
}

$sql = "SELECT * FROM genealogy_tree where rootid = '{$mainID}' and mb_name = '{$_POST['searchName']}'";
$result = mysql_query($sql);

while ($row=mysql_fetch_array($result)) {
    array_push($searchArray, array($row['mb_name'], $row['recommenderID'], $row['sponsorID'], $row['accountRank'], $row['renewal'],$row['mb_id']));
}

if ($_POST['check'] == "회원가입" && $_POST['searchName'] == "배형무") {
    array_push($searchArray, array("배형무", "00000001", "VMP", "대표사업자", "N/A", "VMP"));
}
echo json_encode($searchArray);
?>
