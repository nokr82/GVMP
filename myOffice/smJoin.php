<?php
include_once('./dbConn.php');
include_once('./_common.php');
// CU가 SM으로 가입하는 백엔드 로직



$money = 20000; // SM 회원비. 픽스 값 수정 금지.
$cashback = 16000; // 추천인이 캐시백될 금액. 픽스 값 수정 금지.

//0106수정 홍동운
if ($money > $member['VMP']) {
    alert("보유한 VMP가 부족합니다.");
    exit();
}


$row = mysql_fetch_array(mysql_query("select * from genealogy where mb_id = '{$member['mb_id']}'"));

$myRow = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'"));
if ($myRow['accountType'] == "CU") { // 참이면 SM 첫 가입
    if ($row['recommenderID'] != "") { // SM가입하는 회원이 추천인이 지정 돼 있으면 참
        $row = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$row['recommenderID']}'"));

        if ($row['accountType'] == "VM") { // 추천인이 VM이면 참
            mysql_query("update g5_member set accountType = 'SM', accountRank = 'SM', renewal = CURDATE(), VMP = VMP - {$money} where mb_id = '{$member['mb_id']}'");
            //동우추가 2020-01-21 직급변경이력 남기기
            mysql_query("insert into dayPoint set mb_id = '{$member['mb_id']}', VMC=0, VMR=0, VMP=-{$money}, date=NOW(), way='smJoin'");

            $now_temp = strtotime(date("Y-m-d"));
            $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row['renewal']}', interval +4 month) AS date"));
            $timestamp_temp = strtotime($dateCheck_1["date"]); // 추천인 VM 기간 마지막 일

            if (!($timestamp_temp < $now_temp)) { // 참이면 VM 기간
                mysql_query("update g5_member set VMP = VMP + {$cashback} where mb_id = '{$row['mb_id']}'"); // 추천인에게 VMP 포인트로 캐시백
                mysql_query("insert into dayPoint set mb_id = '{$row['mb_id']}', VMC=0, VMR=0, VMP={$cashback}, date=NOW(), way='smJoinVM;{$member['mb_id']}'");
            }

            echo "<script>alert('SM 가입이 완료되었습니다.'); window.location.href = '/myOffice/index.php';</script>";
            exit();
        } else {
            echo "<script>alert('추천인 계정이 VM이 아닙니다.'); history.back();</script>";
            exit();
        }
    } else {
        echo "<script>alert('추천인이 지정 돼 있지 않습니다.'); history.back();</script>";
        exit();
    }
} else { // 거짓이면 SM 리뉴얼
    $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row['renewal']}', interval +4 month) AS date"));
    $timestamp = $dateCheck_1["date"];
    mysql_query("update g5_member set renewal = '{$timestamp}', VMP = VMP - {$money} where mb_id = '{$member['mb_id']}'");
    mysql_query("insert into dayPoint set mb_id = '{$member['mb_id']}', VMC=0, VMR=0, VMP=-{$money}, date=NOW(), way='smRenewal'");

    $row = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$row['recommenderID']}'"));
    $now_temp = strtotime(date("Y-m-d"));
    $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row['renewal']}', interval +4 month) AS date"));
    $timestamp_temp = strtotime($dateCheck_1["date"]); // 추천인 VM 기간 마지막 일

    if (!($timestamp_temp < $now_temp)) { // 참이면 VM 기간
        mysql_query("update g5_member set VMP = VMP + {$cashback} where mb_id = '{$row['mb_id']}'"); // 추천인에게 VMP 포인트로 캐시백
        mysql_query("insert into dayPoint set mb_id = '{$row['mb_id']}', VMC=0, VMR=0, VMP={$cashback}, date=NOW(), way='smRenewalVM;{$member['mb_id']}'");
    }

    echo "<script>alert('SM 리뉴얼이 완료되었습니다.'); window.location.href = '/myOffice/index.php';</script>";
    exit();
}


?>