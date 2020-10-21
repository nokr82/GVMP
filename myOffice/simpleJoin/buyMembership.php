<?php
$cssCheck = false; // _common.php에 인클루드 되는 CSS 적용 안 되게 하기 위한 변수
include_once('./_common.php');
include_once('../dbConn.php');

if ($is_guest) // 로그인 안 했을 때 로그인 페이지로 이동
    header('Location: /bbs/login.php');

// 간편 회원가입권 구매를 처리하는 로직입니다.
// 성공시 true를 전달하고 실패시 에러 메시지를 전달함.

$_POST["pay_count"] = trim($_POST["pay_count"]);
$_POST["VMCpoint"] = trim($_POST["VMCpoint"]);
$_POST["VMPpoint"] = trim($_POST["VMPpoint"]);

if (!$_POST["VMCpoint"] > 0)
    $_POST["VMCpoint"] = 0;
if (!$_POST["VMPpoint"] > 0)
    $_POST["VMPpoint"] = 0;


// VMP가설정한 간편 회원가입 권한레벨
$configR1 = mysql_fetch_array(mysql_query("select * from config where config = 'simpleJoin'"));
$rankOrR1 = mysql_fetch_array(mysql_query("select * from rankOrderBy where rankAccount = '{$configR1["value"]}'"));

//자신의 레벨
$rankOrR2 = mysql_fetch_array(mysql_query("select * from rankOrderBy where rankAccount = '{$member["accountRank"]}'"));

if ($member["simpleJoin"] != "YES" && $rankOrR1["orderNum"] < $rankOrR2["orderNum"]) {
    echo "간편회원가입 권한이 없습니다.";
    exit();
}

if ($_POST["pay_count"] === "" || $_POST["VMCpoint"] === "" || $_POST["VMPpoint"] === "") {
    echo $_POST["VMPpoint"];
    echo "파라미터 에러입니다. 다시 진행 바랍니다.";
    exit();
}

$total = 2080000 * (int)$_POST["pay_count"]; // 총 결제 금액

if ($total != ((int)$_POST["VMCpoint"] + $_POST["VMPpoint"])) {
    echo "사용 포인트 금액이 잘 못 되었습니다.";
    exit();
}

$g5_memberRow = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$member["mb_id"]}'"));

if ($g5_memberRow["VMC"] < $_POST["VMCpoint"] || $g5_memberRow["VMP"] < $_POST["VMPpoint"]) {
    echo "보유 포인트 금액이 부족합니다.";
    exit();
}

mysql_query("update g5_member set membership = membership+{$_POST["pay_count"]}, VMC = VMC - {$_POST["VMCpoint"]}, VMP = VMP - {$_POST["VMPpoint"]} where mb_id = '{$member["mb_id"]}'");
mysql_query("insert into dayPoint set mb_id='{$member["mb_id"]}', VMC=-{$_POST["VMCpoint"]}, VMP=-{$_POST["VMPpoint"]}, date=NOW(),way='membershipBuy'");

echo "true";
?>