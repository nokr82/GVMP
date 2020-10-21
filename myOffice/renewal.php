<?php
include_once('./_common.php');
include_once('./dbConn.php');

//0121동우수정 직급변경함수
include_once('./change_rank_func.php');

set_time_limit(0);
$VMC = (isset($_POST['VMC']) || $_POST['VMC']) ? $_POST['VMC'] : 0;
$VMR = (isset($_POST['VMR']) || $_POST['VMR']) ? $_POST['VMR'] : 0;
$VMP = (isset($_POST['VMP']) || $_POST['VMP']) ? $_POST['VMP'] : 0;
$VMG = (isset($_POST['VMG']) || $_POST['VMG']) ? $_POST['VMG'] : 0;


if (!$VMC) $VMC = 0;
if (!$VMR) $VMR = 0;
if (!$VMP) $VMP = 0;
if (!$VMG) $VMG = 0;

//0106수정홍동우
if ($VMP > $member['VMP']){
    alert("VMP포인트가 부족합니다.");
    exit();
}
if ($VMG > $member['VMG']){
    alert("금고가 부족합니다.");
    exit();
}
if ($VMC > $member['VMC']){
    alert("VMC가 부족합니다.");
    exit();
}
if ($VMR > $member['VMR']){
    alert("VMR이 부족합니다.");
    exit();
}


if (($VMC + $VMR + $VMP + $VMG) < 2080000) {
    alert("입력하신 포인트가 2,080,000 Point 보다 작습니다.", "/myOffice/index.php");
    exit();
}


$result = mysql_query("select * from g5_member where mb_id = '{$_POST['userid']}'");
$row = mysql_fetch_array($result);

$dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row['renewal']}', interval +4 month) AS date"));
$timestamp = $dateCheck_1["date"];
$timenow = date("Y-m-d");

$str_now = strtotime($timenow);
$timestamp2 = strtotime($timestamp);


$cVMG = $row['VMG'] - $VMG;
$cVMC = $row['VMC'] - $VMC;
$cVMR = $row['VMR'] - $VMR;
$cVMP = $row['VMP'] - $VMP;


if ($cVMC < 0 || $cVMR < 0 || $cVMP < 0 || $cVMG < 0) {
    alert("보유한 금액보다 포인트를 더 많이 사용할 수 없습니다.", "/myOffice/index.php");
}


$renewalDate;
$way;
if ($row['accountType'] == "VM" || ($row['accountType'] == "CU" && $row['renewal'] != null)) {
    // VM 기간인지 익액티브 상태인지를 체크하여 리뉴얼 날짜를 결정하는 로직
    $now_temp = strtotime(date("Y-m-d"));
    $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row['renewal']}', interval +4 month) AS date"));
    $timestamp_temp = strtotime($dateCheck_1["date"]); // VM 기간 마지막 일

    if (!($timestamp_temp < $now_temp)) { // 참이면 VM 기간
        $renewalDate = $timestamp; // 리뉴얼 날짜 + 1달
    } else {
        $renewalDate = $timenow; // 리뉴얼 날짜 오늘

        mysql_query("insert into plusCountListTBL set mb_id = '{$_POST['userid']}'"); // VM 카운팅 리스트(증가)
    }
    $way = 'Renewal';
} else if ($_POST['H_reg_mb_sponsor'] != "" && $_POST['H_reg_mb_sponsorID'] != "" && isset($_POST['H_radiobtn'])) {
    $renewalDate = $timenow; // 리뉴얼 날짜 오늘
    $way = 'newRenewal';


    $teamMembersCheckRow = mysql_fetch_array(mysql_query("select {$_POST['H_radiobtn']}T_ID as C from teamMembers where mb_id = '{$_POST['H_reg_mb_sponsorID']}'"));
    if ($teamMembersCheckRow["C"] != "") {
        alert("지정한 후원인 산하에 계정이 존재하여 배치될 수 없습니다.");
    }
}


if (($row['accountType'] == "CU" && $row['renewal'] != null)) { // VM이였다가 죽은 회원일 때
    mysql_query("update g5_member set accountType = 'VM', accountRank = 'MASTER' where mb_id = '{$_POST['userid']}'"); // 계정을 VM으로 바꾸기
    mysql_query("update g5_member set mb_1 = '1번' where mb_id = '{$_POST['userid']}'");
    //동우추가 2020-01-21 직급변경이력 남기기
    rank_com($_POST['userid'],'VM','CU');
} else if ($row['accountType'] == "VM") { // VM이 리뉴얼할 때
    mysql_query("update g5_member set mb_1 = '2번' where mb_id = '{$_POST['userid']}'");
} else { // 그외. CU로 첫 가입할 때
    if ($_POST['H_reg_mb_sponsor'] == "" || $_POST['H_reg_mb_sponsorID'] == "" || $_POST['H_radiobtn'] == "") {
        alert("후원인 정보가 잘 못 되었습니다. 다시 진행 바랍니다.");
        exit();
    }

    mysql_query("update g5_member set accountType = 'VM', accountRank = 'MASTER' where mb_id = '{$_POST['userid']}'"); // 계정을 VM으로 바꾸기
    mysql_query("update genealogy set sponsorName = '{$_POST['H_reg_mb_sponsor']}', sponsorID = '{$_POST['H_reg_mb_sponsorID']}', sponsorTeam = {$_POST['H_radiobtn']} where mb_id = '{$_POST['userid']}'"); // 자신의 후원인 정보 바꾸기
    mysql_query("delete from team3_list where 3T_id = '{$_POST['userid']}'");
    mysql_query("update teamMembers set {$_POST['H_radiobtn']}T_name = '{$row['mb_name']}', {$_POST['H_radiobtn']}T_ID = '{$_POST['userid']}' where mb_id = '{$_POST['H_reg_mb_sponsorID']}'");

    mysql_query("update g5_member set mb_1 = '3번' where mb_id = '{$_POST['userid']} / {$_POST['H_reg_mb_sponsor']} / {$_POST['H_reg_mb_sponsorID']} / {$_POST['H_radiobtn']} / {$row['mb_name']}'");


    mysql_query("insert into plusCountListTBL set mb_id = '{$_POST['userid']}'"); // VM 카운팅 리스트(증가)

    //동우추가 2020-01-21 직급변경이력 남기기
    rank_com($_POST['userid'],'VM','CU');
}


mysql_query("update g5_member set VMC = '{$cVMC}', VMR = '{$cVMR}', VMP = '{$cVMP}', VMG = '{$cVMG}', renewal = '{$renewalDate}' where mb_id = '{$_POST['userid']}'");
mysql_query("insert into dayPoint set mb_id = '{$_POST['userid']}', VMC = -{$VMC}, VMR = -{$VMR}, VMP = -{$VMP}, VMG = -{$VMG} , date = NOW(), way = '{$way}'");


/*금고에 16만원없을때 체우는 로직*/
if (get_VMG_check($_POST['userid'])) { //트리플마스터 이상만
    if ($cVMG < 2080000) { //금고에 16만원 미만이 있을때
        if ($cVMC > 0) {
            $sendVMC = $cVMC - $VMG;
            $TVMG = 2080000;
            if ($sendVMC < 0) {
                $TVMG = $cVMC;
                $cVMC = 0;
            } else {
                $cVMC = $cVMC - $VMG;
            }
            mysql_query("update g5_member set VMC = '{$cVMC}', VMG = '$TVMG', renewal = '{$renewalDate}' where mb_id = '{$_POST['userid']}'");
            mysql_query("insert into dayPoint set mb_id = '{$_POST['userid']}', VMC = -{$TVMG}, VMG = +{$TVMG} , date = NOW(), way = 'vmgMove'");
        }
    }
}
/*금고에 16만원없을때 체우는 로직*/


alert("VM 가입이 완료되었습니다.");
exit();


?>