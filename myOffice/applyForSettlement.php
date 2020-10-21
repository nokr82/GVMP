<?php
include_once ('./_common.php');
include_once ('./dbConn.php');

// 정산 신청을 처리하는 백엔드 로직.
// 이정현 2018-10-10

$_POST['last_pay_in'] = str_replace(",","",$_POST['last_pay_in']);


if ($is_guest) // 로그인 안 했을 때 로그인 페이지로 이동
    header('Location: /bbs/login.php');

if( $_POST['last_pay_in'] == "0" ) {
    alert("0원을 신청할 수 없습니다.");
}

// 오늘이 며칠인지 알아내고 1~15 사이라면 1분기로 판별하고
// 그게 아니라면 2분기로 판별 시키기
$result;
if( date("d") >= 16 ) { // 참이면 2분기
    alert("신청할 수 없는 기간입니다. 신청을 중단합니다."); exit();
    $result = mysql_query("select * from calculateTBL where mb_id = '{$member['mb_id']}' and settlementDate = '".date( "Y-m-d", mktime(0,0,0,date("m")+1,1,date("Y")) )."'");
} else { // 거짓이면 1분기
    $result = mysql_query("select * from calculateTBL where mb_id = '{$member['mb_id']}' and settlementDate = '".date("Y-m-")."16'");
}

$row = mysql_fetch_array($result);

$result2 = mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'");
$row2 = mysql_fetch_array($result2);


if( $_POST['point_type'] == "C포인트" ) {
    // 현재 일자에 해당하는 분기에 정산 신청을 한적이 있는 지 판별
    if( $row['VMC'] > 0 ) {
        alert("이미 정산 신청이 되어있는 상태입니다. 정산 신청을 중단합니다.");
    } else {
        # 인액티브 상태가 아닌 VM만 VMC 신청이 가능해야 한다.
        $now = date("Y-m-d");
        $now_temp = strtotime($now);
        $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row2['renewal']}', interval +4 month) AS date"));
        $timestamp = $dateCheck_1["date"];
        $timestamp_temp = strtotime($timestamp);
        if( $row2['accountType'] == "VM" && ( $timestamp_temp <= $now_temp ) ) { // 참이면 VM이면서 VM기간이 아님을 뜻 함. 해동 조건에 충족되면 VMC를 신청할 수 없게 차단할 것임
            alert("C포인트 정산 신청을 위해서는 VM 리뉴얼이 필요합니다.");
        } else if($row2['accountType'] != "VM") {
            alert("VM 회원만 C포인트 정산 신청이 가능합니다.");
        } else if( $row2['VMC'] < $_POST['last_pay_in'] ) {
            alert("보유하고 있는 C포인트 보다 신청 금액이 큽니다. 정산 신청을 중단합니다.");
        }

        if( $row['mb_id'] == "" ) {
            if( date("d") >= 16 ) { // 참이면 2분기
                mysql_query("insert into calculateTBL set settlementDate = '".date( "Y-m-d", mktime(0,0,0,date("m")+1,1,date("Y")) )."', mb_id = '{$member['mb_id']}', VMC = {$_POST['last_pay_in']}, applicationDate = now()");
            } else { // 거짓이면 1분기
                mysql_query("insert into calculateTBL set settlementDate = '".date("Y-m-")."16', mb_id = '{$member['mb_id']}', VMC = {$_POST['last_pay_in']}, applicationDate = now()");
            }
        } else {
            if( date("d") >= 16 ) { // 참이면 2분기
                mysql_query("update calculateTBL set VMC = {$_POST['last_pay_in']}, applicationDate = NOW() where mb_id = '{$member['mb_id']}' and settlementDate = '".date( "Y-m-d", mktime(0,0,0,date("m")+1,1,date("Y")) )."'");
            } else { // 거짓이면 1분기
                mysql_query("update calculateTBL set VMC = {$_POST['last_pay_in']}, applicationDate = NOW() where mb_id = '{$member['mb_id']}' and settlementDate = '".date("Y-m-")."16'");
            }
        }
        mysql_query("update g5_member set VMC = VMC - {$_POST['last_pay_in']} where mb_id = '{$member['mb_id']}'");
        mysql_query("insert into dayPoint set mb_id = '{$member['mb_id']}', VMC = -{$_POST['last_pay_in']}, VMR = 0, VMP = 0, date = NOW(), way = 'calculate'");
    }
} else if( $_POST['point_type'] == "P포인트" ) {
    if( date("d") >= 16 )
        alert("P포인트는 1일~15일까지 신청할 수 있습니다. 신청을 중단합니다.");


    $retenRow = mysql_fetch_array(mysql_query("select * from retentionPointTBL where date >= '".date("Y-m-")."01' and date < '".date("Y-m-")."02' and mb_id = '{$member['mb_id']}'"));

    if( $retenRow['VMP'] == 0 || $retenRow['mb_id'] == "" ) {
        alert("정산 신청할 수 있는 P포인트가 없습니다.");
    } else if( $retenRow['VMP'] < $_POST['last_pay_in'] ) {
        alert("신청 금액이 신청 가능 금액을 초과하여 신청을 중단합니다.");
    } else if( $row['VMP'] > 0 ) {
        alert("이미 정산 신청이 되어있는 상태입니다. 정산 신청을 중단합니다.");
    }

    if( $row['mb_id'] == "" ) {
        mysql_query("insert into calculateTBL set settlementDate = '".date("Y-m-")."16', mb_id = '{$member['mb_id']}', VMP = {$_POST['last_pay_in']}, applicationDate = now()");
    } else {
        mysql_query("update calculateTBL set VMP = {$_POST['last_pay_in']}, applicationDate = NOW() where mb_id = '{$member['mb_id']}' and settlementDate = '".date("Y-m-")."16'");
    }
    mysql_query("update g5_member set VMP = VMP - {$_POST['last_pay_in']} where mb_id = '{$member['mb_id']}'");
    mysql_query("insert into dayPoint set mb_id = '{$member['mb_id']}', VMP = -{$_POST['last_pay_in']}, VMR = 0, VMC = 0, date = NOW(), way = 'calculate'");
} else if( $_POST['point_type'] == "C포인트 신청 취소" ) {
    if( ! $row['VMC'] > 0 ) {
        alert("정산 신청이 안 되어 있는 상태입니다. 신청 취소를 중단합니다.");
    }

    if( $row['VMP'] == 0 ) {
        mysql_query("delete from calculateTBL where n = {$row['n']}");
    } else {
        mysql_query("update calculateTBL set VMC = 0 where n = {$row['n']}");
    }
    mysql_query("update g5_member set VMC = VMC + {$row['VMC']} where mb_id = '{$member['mb_id']}'");
    mysql_query("insert into dayPoint set mb_id = '{$member['mb_id']}', VMC = {$row['VMC']}, date = NOW(), way = 'calculateCan'");


    alert("정산 신청 취소가 완료되었습니다.");
} else if( $_POST['point_type'] == "P포인트 신청 취소" ) {
    if( ! $row['VMP'] > 0 ) {
        alert("정산 신청이 안 되어 있는 상태입니다. 신청 취소를 중단합니다.");
    }

    if( $row['VMC'] == 0 ) {
        mysql_query("delete from calculateTBL where n = {$row['n']}");
    } else {
        mysql_query("update calculateTBL set VMP = 0 where n = {$row['n']}");
    }
    mysql_query("update g5_member set VMP = VMP + {$row['VMP']} where mb_id = '{$member['mb_id']}'");
    mysql_query("insert into dayPoint set mb_id = '{$member['mb_id']}', VMP = {$row['VMP']}, date = NOW(), way = 'calculateCan'");

    alert("정산 신청 취소가 완료되었습니다.");
}


// C, P 포인트 공통 처리 부분
// 주민번호 저장 돼 있는 체크해서 저장하거나 업데이트 시키는 로직
$rowRRN = mysql_fetch_array(mysql_query("select * from memberRRNTBL where mb_id = '{$member['mb_id']}'"));
if( $rowRRN['mb_id'] != "" ) {
    mysql_query("update memberRRNTBL set mb_name = '{$_POST['name']}', RRN_F = '{$_POST['unum_f']}', RRN_B = '{$_POST['unum_b']}' where mb_id = '{$member['mb_id']}'");
} else {
    mysql_query("insert into memberRRNTBL set mb_id = '{$member['mb_id']}', mb_name = '{$_POST['name']}', RRN_F = '{$_POST['unum_f']}', RRN_B = '{$_POST['unum_b']}'");
}


alert("정산 신청이 완료되었습니다.");


?>
