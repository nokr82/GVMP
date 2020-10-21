<?php
/******************************************************************************
 *
 *    @ SYSTEM NAME        : URL Noti Test 페이지
 *    @ PROGRAM NAME        : inform.php
 *    @ MAKER                : sajang
 *    @ MAKE DATE            : 2017.12.06
 *    @ PROGRAM CONTENTS    : URL Noti Test 페이지
 *
 ************************** 변 경 이 력 *****************************************
 * 번호    작업자        작업일                변경내용
 *    1    스마트로    2017.12.06        URL Noti 결과 페이지
 *******************************************************************************/

//가상계좌결제 처리 url  retryURL
?>
<?php
include_once('./_common.php');
include_once('./dbConn.php');
include_once('./getMemberInfo.php');

//


$BuyerName = $_REQUEST['BuyerName'];
$BuyerTel = $_REQUEST['BuyerTel'];
$PayMethod = $_REQUEST['PayMethod'];
$MID = $_REQUEST['MID'];
$mallUserID = $_REQUEST['mallUserID'];
$Amt = $_REQUEST['Amt'];
$name = $_REQUEST['name'];
$GoodsName = $_REQUEST['GoodsName'];
$TID = $_REQUEST['TID'];
$OTID = $_REQUEST['OTID'];
$OID = $_REQUEST['OID'];
$AuthDate = $_REQUEST['AuthDate'];
$AuthCode = $_REQUEST['AuthCode'];
$ResultCode = $_REQUEST['ResultCode'];
$ResultMsg = iconv('euc-kr', 'utf-8', $_REQUEST['ResultMsg']);
$state_cd = $_REQUEST['state_cd'];
$fn_cd = $_REQUEST['fn_cd'];
$fn_name = $_REQUEST['fn_name'];
$pinNo = $_REQUEST['pinNo'];
$CardQuota = $_REQUEST['CardQuota'];
$BuyerEmail = $_REQUEST['BuyerEmail'];
$BuyerTel = $_REQUEST['BuyerTel'];
$BuyerAuthNum = $_REQUEST['BuyerAuthNum'];
$VbankNum = $_REQUEST['VbankNum'];
$VbankName = $_REQUEST['VbankName'];
$ReceiptType = $_REQUEST['ReceiptType'];
$CardUsePoint = $_REQUEST['CardUsePoint'];
$SignValue = $_REQUEST['SignValue'];

$result = ""; // 가맹점 DB 처리 및 내부처리 로직 성공 여부

if ("0" == $state_cd) {

    if ("4100" == $ResultCode) { //VBANK 체번완료
        // 결제 성공시 DB처리 하세요.
        // TID 결제 취소한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT
    }
    if ("4110" == $ResultCode) { //VBANK 입금완료
        $name_array = explode("_", $name);
        $u_name =  iconv('euc-kr', 'utf-8', $name_array[1]);
        mysql_query("update g5_member set VMP = VMP + {$Amt} where mb_id = '{$name_array[0]}'");
        mysql_query("insert into dayPoint set mb_id = '{$name_array[0]}', VMP = {$Amt}, date=NOW(), way = 'virtual_point'");
        mysql_query("insert into vmp_virtualpay_log set mb_id = '{$name_array[0]}_{$state_cd}', amt = {$Amt}, tid ='{$TID}',result_msg = '{$ResultMsg}',success = 'S_{$ResultCode}',date=NOW()");
        mysql_query("insert into `gyc5`.`pay_list` (`pay_tel`,`pay_name`,`pay_type`,`amt`,`datetime`,`mb_id`)values('{$BuyerTel}','{$u_name}','1','{$Amt} ',now(),'{$name_array[0]}')");
        // 결제 성공시 DB처리 하세요.
        // TID 결제 취소한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT
    }

    // 결제 취소
    if ("2001" == $ResultCode) {
        // 취소 성공시 DB처리 하세요.
        //TID 결제 취소한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT
    }
    if ("2211" == $ResultCode) {
        // 환불
    }
    if ("2013" == $ResultCode) {
        // 이미(기) 취소 거래임
    }

    // if(가맹점 DB 처리 및 내부처리 로직 성공시){
    $result = "OK";
    // }
} else {
    mysql_query("insert into vmp_virtualpay_log set mb_id = '{$name}_{$state_cd}', amt = {$Amt}, tid ='{$TID}',result_msg = '{$ResultMsg}',success = 'F_{$ResultCode}',date=NOW()");
}

?>
<?= $PayMethod . '<br>' ?>
<?= $MID . '<br>' ?>
<?= $mallUserID . '<br>' ?>
<?= $Amt . '<br>' ?>
<?= $name . '<br>' ?>
<?= $GoodsName . '<br>' ?>
<?= $TID . '<br>' ?>
<?= $OTID . '<br>' ?>
<?= $OID . '<br>' ?>
<?= $AuthDate . '<br>' ?>
<?= $AuthCode . '<br>' ?>
<?= $ResultCode . '<br>' ?>
<?= $ResultMsg . '<br>' ?>
<?= $state_cd . '<br>' ?>
<?= $fn_cd . '<br>' ?>
<?= $fn_name . '<br>' ?>
<?= $pinNo . '<br>' ?>
<?= $CardQuota . '<br>' ?>
<?= $BuyerEmail . '<br>' ?>
<?= $BuyerTel . '<br>' ?>
<?= $BuyerAuthNum . '<br>' ?>
<?= $VbankNum . '<br>' ?>
<?= $VbankName . '<br>' ?>
<?= $ReceiptType . '<br>' ?>
<?= $CardUsePoint . '<br>' ?>
<?= $SignValue . '<br>' ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=EUC-KR"/>
</head>
<body>
<?php echo $result ?>
</body>
</html>