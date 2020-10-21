<?php
/******************************************************************************

SYSTEM NAME		: 결제완료페이지
PROGRAM NAME		: returnPay.php
MAKER				: sajang
MAKE DATE			: 2017.12.06
PROGRAM CONTENTS	: 결제완료페이지

 ************************* 변 경 이 력 *****************************************
 * 번호	작업자		작업일				변경내용
 *	1	스마트로	2017.12.06		결제완료페이지
 ******************************************************************************/
?>
<?php



// 결제 요청 결과 파라미터[공통]
$PayMethod		= $_REQUEST['PayMethod']==null?"":$_REQUEST['PayMethod']; // 지불수단
$MID			= $_REQUEST['MID']==null?"":$_REQUEST['MID']; // 상점 ID
$Amt			= $_REQUEST['Amt']==null?"":$_REQUEST['Amt']; // 금액
$BuyerName		= $_REQUEST['BuyerName']==null?"":$_REQUEST['BuyerName']; // 결제자명
$GoodsName		= $_REQUEST['GoodsName']==null?"":$_REQUEST['GoodsName']; // 상품명
$mallUserID     = $_REQUEST['mallUserID']==null?"":$_REQUEST['mallUserID']; // 고객사회원ID
$TID            = $_REQUEST['TID']==null?"":$_REQUEST['TID']; // 거래번호
$OID			= $_REQUEST['OID']==null?"":$_REQUEST['OID']; // 주문번호
$AuthDate		= $_REQUEST['AuthDate']==null?"":$_REQUEST['AuthDate']; // 승인일자
$AuthCode		= $_REQUEST['AuthCode']==null?"":$_REQUEST['AuthCode']; // 승인번호
$ResultCode		= $_REQUEST['ResultCode']==null?"":$_REQUEST['ResultCode']; // 결과코드
$ResultMsg		= $_REQUEST['ResultMsg']==null?"":$_REQUEST['ResultMsg']; // 결과메시지
$BuyerTel		= $_REQUEST['BuyerTel']==null?"":$_REQUEST['BuyerTel']; // 구매자 전화번호
$BuyerEmail		= $_REQUEST['BuyerEmail']==null?"":$_REQUEST['BuyerEmail']; // 구매자이메일주소
$SignValue		= $_REQUEST['SignValue']==null?"":$_REQUEST['SignValue']; // 위변조 사인값

$pay_name		= $_REQUEST['pay_name']==null?"":$_REQUEST['pay_name']; // 구매자이메일주소
$pay_tel		= $_REQUEST['pay_tel']==null?"":$_REQUEST['pay_tel']; // 위변조 사인값


// 결제 요청 결과 파라미터[신용카드]
$fn_cd			= $_REQUEST['fn_cd']==null?"":$_REQUEST['fn_cd']; // 결제카드사코드
$fn_name		= $_REQUEST['fn_name']==null?"":$_REQUEST['fn_name']; // 결제카드사명
$CardQuota		= $_REQUEST['CardQuota']==null?"":$_REQUEST['CardQuota']; // 할부개월수
$AcquCardCode	= $_REQUEST['AcquCardCode']==null?"":$_REQUEST['AcquCardCode']; // 매입사코드

$DivideInfo 	= $_REQUEST['DivideInfo']==null?"":$_REQUEST['DivideInfo']; // 서브몰 정보

if (!empty($DivideInfo)) {
    if (strpos($DivideInfo, '%') != false)
        $DivideInfo = urldecode($DivideInfo);
    $byteDivideInfo = base64_decode($DivideInfo); // 가맹점 페이지 인코딩 타입에 따른 처리 필요 (utf-8 로 인코딩 처리 후 전달됨)
    $DivideInfo = $byteDivideInfo;
}

// 결제 요청 결과 파라미터[계좌이체, 가상계좌]
$ReceiptType	= $_REQUEST['ReceiptType']==null?"":$_REQUEST['ReceiptType']; // 현금영수증유형

// 결제 요청 결과 파라미터[가상계좌]
$VbankNum		= $_REQUEST['VbankNum']==null?"":$_REQUEST['VbankNum']; // 가상계좌번호
$VbankName		= $_REQUEST['VbankName']==null?"":$_REQUEST['VbankName']; // 가상계좌은행명

$merchantKey = "0/4GFsSd7ERVRGX9WHOzJ96GyeMTwvIaKSWUCKmN3fDklNRGw3CualCFoMPZaS99YiFGOuwtzTkrLo4bR4V+Ow=="; // SMTPAY001m (MID) 가맹점 키
$VerifySignValue = base64_encode(md5(substr($TID,0,10).$ResultCode.substr($TID,10,5).$merchantKey.substr($TID,15,15)));

// 웹 링크 버전일 경우에 실제 스마트로 서버의 승인 값을 검증 하기 위해서 아래의 값을 비교 합니다.
if ($ResultCode == "3001") {// CARD 결제성공
    // 승인 성공 시 DB 처리 하세요.
    // TID 결제 성공한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT
}
else if ($ResultCode == "4000") {// BANK 결제성공
    // 승인 성공 시 DB 처리 하세요.
    // TID 결제 성공한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT
}
else if ($ResultCode == "4100") {// VBANK 결제성공
    // 승인 성공 시 DB 처리 하세요.
    // TID 결제 성공한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT
}
else if ($ResultCode == "A000") {// cellphone 결제성공
    // 승인 성공 시 DB 처리 하세요.
    // TID 결제 성공한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT
}
else if ($ResultCode == "B000") {// CLGIFT 결제성공
    // 승인 성공 시 DB 처리 하세요.
    // TID 결제 성공한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>스마트로::인터넷결제</title>
    <style type="text/css">
        <!--
        table.type {
            border-collapse: collapse;
            text-align: left;
            line-height: 1.5;
        }
        table.type thead th {
            padding: 8px;
            font-weight: bold;
            vertical-align: top;
            color: #369;
            border-bottom: 3px solid #036;
            text-align:center;
        }
        table.type tbody th {
            width: 150px;
            padding: 8px;
            font-weight: bold;
            vertical-align: top;
            border-bottom: 1px solid #ccc;
            background: #f3f6f7;
            text-align:center;
        }
        table.type td {
            width: 250px;
            padding: 8px;
            vertical-align: top;
            border-bottom: 1px solid #ccc;
            text-align:center;
        }

        .selectbox {
            position: relative;
            width: auto;
            border: 1px solid #999;
            z-index: 1;
            padding: 4px;
            color: #369;
        }

        input {
            width: 100%;
            padding: 6px 10px;
            margin: 2px 0;
            box-sizing: border-box;
        }

        .btnblue {
            background-color: #6DA2D9;
            box-shadow: 0 0 0 1px #6698cb inset,
            0 0 0 2px rgba(255,255,255,0.15) inset,
            0 4px 0 0 rgba(110, 164, 219, .7),
            0 4px 0 1px rgba(0,0,0,.4),
            0 4px 4px 1px rgba(0,0,0,0.5);
        }

        -->
    </style>
</head>
<body ondragstart='' onselectstart='' style="overflow: scroll">
</body>
</html>

<script>
    if ('<?=$ResultCode?>' == '4100'){
        try {
            opener.location.href = "http://gvmp.company/myOffice/pay_virtual_bank.php?bankName="+"<?=$VbankName?>"+"&Amt="+"<?=$Amt?>"+"&bankNum="+"<?=$VbankNum?>";
            self.close();
        }catch (e) {
            location.href = "http://gvmp.company/myOffice/pay_virtual_bank.php?bankName="+"<?=$VbankName?>"+"&Amt="+"<?=$Amt?>"+"&bankNum="+"<?=$VbankNum?>";
        }

    }else {
        alert('<?=$ResultCode?>');
        self.close();
    }
</script>