<?php
header("Content-Type:text/html; charset=euc-kr;"); 
require_once dirname(__FILE__).'./lib/nicepay/web/NicePayWEB.php';
require_once dirname(__FILE__).'./lib/nicepay/core/Constants.php';
require_once dirname(__FILE__).'./lib/nicepay/web/NicePayHttpServletRequestWrapper.php';
/*
*******************************************************
* <결제 결과 설정>
* 사용전 결과 옵션을 사용자 환경에 맞도록 변경하세요.
* 로그 디렉토리는 꼭 변경하세요.
*******************************************************
*/   
$nicepayWEB = new NicePayWEB();
$httpRequestWrapper = new NicePayHttpServletRequestWrapper($_REQUEST);
$_REQUEST = $httpRequestWrapper->getHttpRequestMap();
$payMethod = $_REQUEST['PayMethod'];
$merchantKey = "33F49GnCMS1mFYlGXisbUDzVf2ATWCl9k3R++d5hDd3Frmuos/XLx8XhXpe+LDYAbpGKZYSwtlyyLOtS/8aD7A==";

$nicepayWEB->setParam("NICEPAY_LOG_HOME","C:/log");             // 로그 디렉토리 설정
$nicepayWEB->setParam("APP_LOG","1");                           // 어플리케이션로그 모드 설정(0: DISABLE, 1: ENABLE)
$nicepayWEB->setParam("EVENT_LOG","1");                         // 이벤트로그 모드 설정(0: DISABLE, 1: ENABLE)
$nicepayWEB->setParam("EncFlag","S");                           // 암호화플래그 설정(N: 평문, S:암호화)
$nicepayWEB->setParam("SERVICE_MODE", "PY0");                   // 서비스모드 설정(결제 서비스 : PY0 , 취소 서비스 : CL0)
$nicepayWEB->setParam("Currency", "KRW");                       // 통화 설정(현재 KRW(원화) 가능)
$nicepayWEB->setParam("PayMethod",$payMethod);                  // 결제방법
$nicepayWEB->setParam("LicenseKey",$merchantKey);               // 상점키

/*
*******************************************************
* <결제 결과 필드>
* 아래 응답 데이터 외에도 전문 Header와 개별부 데이터 Get 가능
*******************************************************
*/
$responseDTO    = $nicepayWEB->doService($_REQUEST);

$resultCode     = $responseDTO->getParameter("ResultCode");     // 결과코드 (정상 결과코드:3001)
$resultMsg      = $responseDTO->getParameter("ResultMsg");      // 결과메시지
$authDate       = $responseDTO->getParameter("AuthDate");       // 승인일시 (YYMMDDHH24mmss)
$authCode       = $responseDTO->getParameter("AuthCode");       // 승인번호
$buyerName      = $responseDTO->getParameter("BuyerName");      // 구매자명
$mallUserID     = $responseDTO->getParameter("MallUserID");     // 회원사고객ID
$goodsName      = $responseDTO->getParameter("GoodsName");      // 상품명
$mallUserID     = $responseDTO->getParameter("MallUserID");     // 회원사ID
$mid            = $responseDTO->getParameter("MID");            // 상점ID
$tid            = $responseDTO->getParameter("TID");            // 거래ID
$moid           = $responseDTO->getParameter("Moid");           // 주문번호
$amt            = $responseDTO->getParameter("Amt");            // 금액
$cardQuota      = $responseDTO->getParameter("CardQuota");      // 카드 할부개월 (00:일시불,02:2개월)
$cardCode       = $responseDTO->getParameter("CardCode");       // 결제카드사코드
$cardName       = $responseDTO->getParameter("CardName");       // 결제카드사명
$bankCode       = $responseDTO->getParameter("BankCode");       // 은행코드
$bankName       = $responseDTO->getParameter("BankName");       // 은행명
$rcptType       = $responseDTO->getParameter("RcptType");       // 현금 영수증 타입 (0:발행되지않음,1:소득공제,2:지출증빙)
$rcptAuthCode   = $responseDTO->getParameter("RcptAuthCode");   // 현금영수증 승인번호
$carrier        = $responseDTO->getParameter("Carrier");        // 이통사구분
$dstAddr        = $responseDTO->getParameter("DstAddr");        // 휴대폰번호
$vbankBankCode  = $responseDTO->getParameter("VbankBankCode");  // 가상계좌은행코드
$vbankBankName  = $responseDTO->getParameter("VbankBankName");  // 가상계좌은행명
$vbankNum       = $responseDTO->getParameter("VbankNum");       // 가상계좌번호
$vbankExpDate   = $responseDTO->getParameter("VbankExpDate");   // 가상계좌입금예정일

/*
*******************************************************
* <결제 성공 여부 확인>
*******************************************************
*/
$paySuccess = false;
if($payMethod == "CARD"){
    if($resultCode == "3001") $paySuccess = true;               // 신용카드(정상 결과코드:3001)
}else if($payMethod == "BANK"){
    if($resultCode == "4000") $paySuccess = true;               // 계좌이체(정상 결과코드:4000)
}else if($payMethod == "CELLPHONE"){
    if($resultCode == "A000") $paySuccess = true;               // 휴대폰(정상 결과코드:A000)
}else if($payMethod == "VBANK"){
    if($resultCode == "4100") $paySuccess = true;               // 가상계좌(정상 결과코드:4100)
}
?>
<!DOCTYPE html>
<html>
<head>
<title>NICEPAY PAY RESULT(EUC-KR)</title>
<meta charset="euc-kr">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=yes, target-densitydpi=medium-dpi" />
<link rel="stylesheet" type="text/css" href="./css/import.css"/>
</head>
<body> 
  <div class="payfin_area">
    <div class="top">NICEPAY PAY RESULT(EUC-KR)</div>
    <div class="conwrap">
      <div class="con">
        <div class="tabletypea">
          <table>
            <colgroup><col width="30%"/><col width="*"/></colgroup>
              <tr>
                <th><span>결과 내용</th>
                <td>[<?=$resultCode?>]<?=$resultMsg?></td>
              </tr>
              <tr>
                <th><span>결제 수단</th>
                <td><?=$payMethod?></td>
              </tr>
              <tr>
                <th><span>상품명</th>
                <td><?=$goodsName?></td>
              </tr>
              <tr>
                <th><span>금액</th>
                <td><?=$amt?>원</td>
              </tr>
              <tr>
                <th><span>거래아이디</th>
                <td><?=$tid?></td>
              </tr>               
            <?php if($payMethod=="CARD"){?>
              <tr>
                <th><span>카드사명</th>
                <td><?=$cardName?></td>
              </tr>
              <tr>
                <th><span>할부개월</th>
                <td><?=$cardQuota?></td>
              </tr>
            <?php }else if($payMethod=="BANK"){?>
              <tr>
                <th><span>은행</th>
                <td><?=$bankName?></td>
              </tr>
              <tr>
                <th><span>현금영수증 타입</th>
                <td><?=$rcptType?>(0:발행안함,1:소득공제,2:지출증빙)</td>
              </tr>
            <?php }else if($payMethod=="CELLPHONE"){?>
              <tr>
                <th><span>이통사 구분</th>
                <td><?=$carrier?></td>
              </tr>
              <tr>
                <th><span>휴대폰 번호</th>
                <td><?=$dstAddr?></td>
              </tr>
            <?php }else if($payMethod=="VBANK"){?>
              <tr>
                <th><span>입금 은행</th>
                <td><?=$vbankBankName?></td>
              </tr>
              <tr>
                <th><span>입금 계좌</th>
                <td><?=$vbankNum?></td>
              </tr>
              <tr>
                <th><span>입금 기한</th>
                <td><?=$vbankExpDate?></td>
              </tr>
            <?php }?>
          </table>
        </div>
      </div>
      <p>*테스트 아이디인경우 당일 오후 11시 30분에 취소됩니다.</p>
    </div>
  </div>
</body>
</html>