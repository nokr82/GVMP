<?php
require("./lib/lib/NicepayLite.php");


include_once ('./dbConn.php');

// 웰컴페이먼츠 비 인증 결제 처리하는 로직
$row = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$_POST["mb_id"]}'"));
if ($row["payment2"] == "NO") {
    echo "권한 없음";
    exit();
}

/*
 * ******************************************************
 * <결제 결과 설정>
 * 사용전 결과 옵션을 사용자 환경에 맞도록 변경하세요.
 * 로그 디렉토리는 꼭 변경하세요.
 * ******************************************************
 */
$nicepay = new NicepayLite;
$nicepay->m_NicepayHome = "/niceLog";               // 로그 디렉토리 설정
$nicepay->m_ActionType = "PYO";                  // ActionType
$nicepay->m_ssl = "true";                 // 보안접속 여부
$nicepay->m_Price = $Amt;                   // 금액
$nicepay->m_NetCancelAmt = $Amt;                   // 취소금액 설정
$nicepay->m_NetCancelPW = "123456";             // 결제 취소 패스워드 설정
$nicepay->m_charSet = "UTF8";                 // 인코딩

/*
 * ******************************************************
 * <결제 결과 필드>
 * ******************************************************
 */
$nicepay->m_BuyerName = $BuyerName;             // 구매자명
$nicepay->m_BuyerEmail = $BuyerEmail;            // 구매자이메일
$nicepay->m_BuyerTel = $BuyerTel;              // 구매자연락처
$nicepay->m_EncryptedData = $EncryptedData;         // 해쉬값
$nicepay->m_GoodsName = $GoodsName;             // 상품명
$nicepay->m_GoodsCnt = $m_GoodsCnt;            // 상품개수
$nicepay->m_GoodsCl = $GoodsCl;               // 실물 or 컨텐츠
$nicepay->m_PayMethod = $PayMethod;             // 결제수단
$nicepay->m_Moid = $Moid;                  // 주문번호
$nicepay->m_MallUserID = $MallUserID;            // 회원사ID
$nicepay->m_MID = $MID;                   // MID
$nicepay->m_MallIP = $MallIP;                // Mall IP
$nicepay->m_LicenseKey = $MerchantKey;           // 상점키
$nicepay->m_TrKey = $TrKey;                 // 거래키
$nicepay->m_TransType = $TransType;             // 일반 or 에스크로
$nicepay->startAction();

/*
 * ******************************************************
 * <결제 성공 여부 확인>
 * ******************************************************
 */
$resultCode = $nicepay->m_ResultData["ResultCode"];
$msg = $nicepay->m_ResultData["ResultMsg"];

$paySuccess = false;
if ($PayMethod == "CARD") {
    if ($resultCode == "3001"){
        $paySuccess = true;   // 신용카드(정상 결과코드:3001)
        mysql_query("update g5_member set VMP = VMP + {$_POST["Amt"]} where mb_id = '{$_POST["mb_id"]}'");
        mysql_query("insert into dayPoint set mb_id = '{$_POST["mb_id"]}', VMP = {$_POST["Amt"]}, date=NOW(), way = 'mb_point'");
    }
} else if ($PayMethod == "BANK") {
    if ($resultCode == "4000")
        $paySuccess = true;   // 계좌이체(정상 결과코드:4000)
}else if ($PayMethod == "CELLPHONE") {
    if ($resultCode == "A000")
        $paySuccess = true;   // 휴대폰(정상 결과코드:A000)
}else if ($PayMethod == "VBANK") {
    if ($resultCode == "4100")
        $paySuccess = true;   // 가상계좌(정상 결과코드:4100)
}
$data['result_code'] = $resultCode;
$data['msg'] = $msg;
echo json_encode($data);
?>	
