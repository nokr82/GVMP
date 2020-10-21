<?php
//원본
include_once ('./_common.php');
include_once ('./dbConn.php');

// 포인트 전송을 처리하는 로직


if( $_POST['VMCpoint3'] == "" ) {
    $_POST['VMCpoint3'] = 0;
}
if( $_POST['VMPpoint3'] == "" ) {
    $_POST['VMPpoint3'] = 0;
}





# 인액티브 상태가 아닌 VM만 VMC 전송할 수 있어야 한다.
$row = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'"));

$now = date("Y-m-d");
$now_temp = strtotime($now);
$dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row['renewal']}', interval +4 month) AS date"));
$timestamp = $dateCheck_1["date"];
$timestamp_temp = strtotime($timestamp);
$VMCApplyCheck = false;
if( $row['accountType'] == "VM" && ( $timestamp_temp <= $now_temp ) ) { // 참이면 VM이면서 VM기간이 아님을 뜻 함. 해동 조건에 충족되면 VMC를 전송할 수 없게 차단할 것임
    $VMCApplyCheck = true;
}

if( $_POST['VMCpoint3'] != "0" && $VMCApplyCheck ) { // VMC 전송금액이 존재하는데 전송할 수 있는 조건이 충족되지 않으 때. 즉 VM기간 중인 VM이 아니라면 참.
    echo "<script>alert('VMC를 전송할 수 있는 조건이 충족되지 않습니다.'); window.location = 'index.php';</script>";
    exit();
}


// 여기다가 돈 있는 지 체크하는 로직을 넣기




mysql_query("update g5_member set VMC = VMC - {$_POST['VMCpoint3']}, VMP = VMP - {$_POST['VMPpoint3']} where mb_id = '{$member['mb_id']}'");
$sum = $_POST['VMCpoint3'] + $_POST['VMPpoint3'];
mysql_query("update g5_member set VMP = VMP + {$sum} where mb_id = '{$_POST['smsid']}'");

$smsidRow = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$_POST['smsid']}'"));

mysql_query("insert into dayPoint set no = null, mb_id = '{$member['mb_id']}', VMC = -{$_POST['VMCpoint3']}, VMR = 0, VMP = -{$_POST['VMPpoint3']}, date = NOW(), way = 'transfer;{$_POST['smsid']}({$smsidRow["mb_name"]})님에게 포인트 전송'");
mysql_query("insert into dayPoint set no = null, mb_id = '{$_POST['smsid']}', VMC = 0, VMR = 0, VMP = +{$sum}, date = NOW(), way = 'transfer;{$member['mb_id']}({$member['mb_name']})님한테 포인트 받음'");

alert("포인트 전송이 완료되었습니다.", "/myOffice/index.php");
?>

