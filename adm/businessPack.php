<meta charset="utf-8">

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/myOffice/dbConn.php");
//동우추가 2020-01-21
include_once ($_SERVER["DOCUMENT_ROOT"].'/myOffice/change_rank_func.php');


// 비즈니스 팩 3개월을 적용 시키는 로직
$mb_id;
if (isset($_GET['mb_id'])) {
    $mb_id = $_GET['mb_id'];
} else if (isset($_POST['mb_id'])) {
    $mb_id = $_POST['mb_id'];
}


//0106수정 홍동우
if (isset($_POST['mb_id'])) {
    $result = mysql_query("select * from g5_member where mb_id = '{$mb_id}'");
    $row = mysql_fetch_array($result);
    if (isset($_POST['VMCpoint']) && isset($_POST['VMPpoint'])) {
        if ($_POST['VMCpoint'] > $row['VMC']) {
            echo "<script>
            alert('VMC가 부족합니다.');
            history.back();
             </script>";
            exit();

        }
        if ($_POST['VMPpoint'] > $row['VMP']) {
            echo "<script>
            alert('VMP가 부족합니다.');
            history.back();
             </script>";
            exit();
        }
    }
}


// CU, SM 계정이 비즈니스팩을 신청할 때 입력한 후원인 정보가 올바른지 검증하는 로직
if (isset($_POST['sponsorName']) && isset($_POST['sponsorID']) && isset($_POST['teamCheck'])) {
    $row = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$_POST['sponsorID']}' and mb_name = '{$_POST['sponsorName']}'"));
    $row2 = mysql_fetch_array(mysql_query("select * from teamMembers where mb_id = '{$_POST['sponsorID']}'"));

    if ($row['mb_name'] == "") {
        echo "<script>
            alert('후원인 이름 또는 ID가 일치하지 않습니다.');
            history.back();
             </script>";
        exit();
    } else if ($row2[$_POST['teamCheck'] . "T_ID"] != "") {
        echo "<script>
            alert('지정한 후원인의 팀으로 배치될 수 없습니다.');
            history.back();
             </script>";
        exit();
    }
}


$result = mysql_query("select * from g5_member where mb_id = '{$mb_id}'");
$row = mysql_fetch_array($result);
if ($row['accountType'] == "VD") {
    echo "<script>
        alert('VD 계정은 비지니스 팩을 구매할 수 없습니다.');
        history.back();
         </script>";
    exit();
}

if ($row['accountType'] == "CU" || $row['accountType'] == "SM") {
    // 계정이 CU 또는 SM이면 오늘을 기준으로 2개월 후 날짜를 DB에 리뉴얼 날짜로 넣고 계정을 VM으로 변경
    // 3팀에서 배제시키는 로직
    mysql_query("update g5_member set renewal = date_add(CURDATE(), interval +5 month), accountType = 'VM', accountRank = 'VM' where mb_id = '{$mb_id}'");
    mysql_query("delete from team3_list where 3T_id = '{$mb_id}'");
    mysql_query("insert into plusCountListTBL set mb_id = '{$mb_id}'"); // VM 카운팅 리스트(증가)

    //동우추가 2020-01-21 직급변경이력 저장
    rank_com($mb_id,'VM',$row['accountType']);

} else if ($row['accountType'] == "VM") {
    $now = date("Y-m-d");
    $now_temp = strtotime($now);
    $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row['renewal']}', interval +4 month) AS date"));
    $timestamp = $dateCheck_1["date"];
    $timestamp_temp = strtotime($timestamp);

    if ($now_temp > $timestamp_temp) { // VM이 인액티브 상태이면 참
        mysql_query("update g5_member set renewal = date_add(CURDATE(), interval +5 month) where mb_id = '{$row['mb_id']}'");
        mysql_query("insert into plusCountListTBL set mb_id = '{$mb_id}'"); // VM 카운팅 리스트(증가)
    } else { // VM이 인액티브 상태가 아니라면 참
        mysql_query("update g5_member set renewal = date_add('{$timestamp}', interval +5 month) where mb_id = '{$row['mb_id']}'");
    }
}


if (isset($_POST['VMCpoint']) && isset($_POST['VMPpoint'])) {
    // 조건 참 뜨면 마이오피스에서 비즈니스팩을 신청한 거기에
    // 보유 포인트를 차감 시키게 되고
    // 조건이 거짓이라면 관리자가 비즈니스팩을 넣어주는 거기 때문에
    // 보유 포인트를 차감하지 않고 dayPoint에만 이력을 남긴다.

    mysql_query("update g5_member set VMC = VMC -{$_POST['VMCpoint']}, VMP = VMP -{$_POST['VMPpoint']} where mb_id = {$mb_id}");
    mysql_query("insert into dayPoint set mb_id = '{$mb_id}', VMC = -{$_POST['VMCpoint']}, VMR = 0, VMP = -{$_POST['VMPpoint']}, date = NOW(), way = 'businessPack'");

    if ($row['accountType'] == "CU" || $row['accountType'] == "SM") {
        // 계정이 CU 또는 SM이라면 후원인 정보를 지정시키는 로직
        mysql_query("update genealogy set sponsorName = '{$_POST['sponsorName']}', sponsorID = '{$_POST['sponsorID']}', sponsorTeam = {$_POST['teamCheck']} where mb_id = '{$mb_id}'");
        mysql_query("update teamMembers set {$_POST['teamCheck']}T_ID = '{$mb_id}', {$_POST['teamCheck']}T_name = '{$row['mb_name']}' where mb_id = '{$_POST['sponsorID']}'");
    }
    echo "<script>
        alert('비즈니스 팩이 적용되었습니다.');
        window.location.href = '/myOffice/';
         </script>";
    exit();
} else {
    mysql_query("insert into dayPoint set mb_id = '{$mb_id}', VMC=0, VMR=0, VMP=420000, date=NOW(), way='admin3'");
    mysql_query("insert into dayPoint set mb_id = '{$mb_id}', VMC=0, VMR=0, VMP=-420000, date=NOW(), way='adminBusinessPack'");
}


echo "<script>
        alert('{$row['mb_name']}({$row['mb_id']})님이 비즈니스 팩이 적용되었습니다.');
        window.location.href = '/adm/member_list.php';
         </script>";
?>
