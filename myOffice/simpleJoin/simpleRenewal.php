<?php
$cssCheck = false; // _common.php에 인클루드 되는 CSS 적용 안 되게 하기 위한 변수
include_once('./_common.php');
include_once('../dbConn.php');

// 간편 회원가입 리뉴얼 처리하는 로직

$Array = array();


$row = mysql_fetch_array(mysql_query("select a.*, b.sponsorID from g5_member as a inner join genealogy as b on a.mb_id = b.mb_id where a.mb_id = '{$_POST["mb_id"]}'"));
$row2 = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$member["mb_id"]}'"));

if ($row["accountType"] != "VM") {
    echo json_encode(array("VM이 아닌 계정은 리뉴얼할 수 없습니다.", 0));
    exit();
}

//VMP가설정한 간편 리뉴얼 권한레벨
$configR2 = mysql_fetch_array(mysql_query("select * from config where config = 'simpleRenewal'"));
$rankOrR3 = mysql_fetch_array(mysql_query("select * from rankOrderBy where rankAccount = '{$configR2["value"]}'"));

//자신의 레벨
$rankOrR2 = mysql_fetch_array(mysql_query("select * from rankOrderBy where rankAccount = '{$member["accountRank"]}'"));

if( $member["simpleRenewal"] != "YES" && $rankOrR3["orderNum"] < $rankOrR2["orderNum"] ) {
    echo json_encode(array("간편 리뉴얼 권한이 없습니다.", 0));
    exit();
}


$dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row['renewal']}', interval +4 month) AS date"));
$dateCheck_2 = mysql_fetch_array(mysql_query("SELECT date_add('{$row['renewal']}', interval +5 month) AS date"));
$dateCheck_1_1 = mysql_fetch_array(mysql_query("SELECT date_add(date_add('{$row['renewal']}', interval +4 month), interval +1 day) AS date"));
$timestamp1 = $dateCheck_1["date"];
$timestamp2 = $dateCheck_2["date"];
$timestamp3 = $dateCheck_1_1["date"];
$timenow = date("Y-m-d");

$str_now = strtotime($timenow);
$str_timestamp1 = strtotime($timestamp1);
$str_timestamp2 = strtotime($timestamp2);
$str_timestamp3 = strtotime($timestamp3);

if ($str_now > $str_timestamp2) { // 리뉴얼 유예기간이 지났으면 참
    echo json_encode(array("리뉴얼 유예기간이 지나 리뉴얼할 수 없습니다.", 0));
    exit();
}

if ($row["sponsorID"] == "99999999") { // 후원인이 없으면 참
    echo json_encode(array("후원인이 존재하지 않아 리뉴얼할 수 없습니다.", 0));
    exit();
}

if ((int)$row2["membership"] < 1) { // 간편 회원가입권이 없으면 참
    echo json_encode(array("간편 회원가입권이 부족합니다.", 0));
    exit();
}


// VM 기간인지 익액티브 상태인지를 체크하여 리뉴얼 날짜를 결정하는 로직
$now_temp = strtotime(date("Y-m-d"));
$dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row['renewal']}', interval +4 month) AS date"));
$timestamp_temp = strtotime($dateCheck_1["date"]); // VM 기간 마지막 일
$timestamp = $dateCheck_1["date"];
$renewalDate;
$timenow = date("Y-m-d");

if (!($timestamp_temp < $now_temp)) { // 참이면 VM 기간
    $renewalDate = $timestamp; // 리뉴얼 날짜 + 1달
} else {
    $renewalDate = $timenow; // 리뉴얼 날짜 오늘

    mysql_query("insert into plusCountListTBL set mb_id = '{$_POST['mb_id']}'"); // VM 카운팅 리스트(증가)
}
mysql_query("update g5_member set renewal = '{$renewalDate}' where mb_id = '{$_POST["mb_id"]}'");
mysql_query("update g5_member set membership = membership - 1 where mb_id = '{$member["mb_id"]}'");
mysql_query("insert into dayPoint set mb_id = '{$_POST["mb_id"]}', date = NOW(), way = 'membershipRenewal;{$member["mb_id"]}'");


$row2 = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$member["mb_id"]}'"));
echo json_encode(array("true", $row2["membership"]));
?>