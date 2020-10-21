 <?php
header("Content-Type:text/html; charset=UTF-8;"); 
require("./lib/NicepayLite.php");
/*
*******************************************************
* <결제요청 파라미터>
* 결제시 Form 에 보내는 결제요청 파라미터입니다.
* 샘플페이지에서는 기본(필수) 파라미터만 예시되어 있으며, 
* 추가 가능한 옵션 파라미터는 연동메뉴얼을 참고하세요.
*******************************************************
*/  
$nicepay = new NicepayLite;

$nicepay->m_MerchantKey = "33F49GnCMS1mFYlGXisbUDzVf2ATWCl9k3R++d5hDd3Frmuos/XLx8XhXpe+LDYAbpGKZYSwtlyyLOtS/8aD7A=="; // 상점키
$nicepay->m_MID         = "nictest04m";                         // 상점아이디
$nicepay->m_Price       = "1004";                               // 결제상품금액
$nicepay->m_BuyerEmail  = "happy@day.co.kr";                    // 구매자메일주소
$nicepay->m_GoodsName   = "나이스페이";                         // 결제상품명
$nicepay->m_BuyerName   = "나이스";                             // 구매자명 
$nicepay->m_BuyerTel    = "010-0000-0000";                      // 구매자연락처           
$nicepay->m_Moid        = "mnoid1234567890";                    // 상품주문번호                     
$goodsCnt               = "1";                                  // 결제상품개수
$nicepay->m_EdiDate = date("YmdHis");                           // 거래 날짜
?>
<!DOCTYPE html>
<html>
<head>
<title>NICEPAY FORMPAY REQUEST(UTF-8)</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=yes, target-densitydpi=medium-dpi" />
<link rel="stylesheet" type="text/css" href="./css/import.css"/>
<script type="text/javascript">
function formPay(){
    document.payForm.CardExpire.value = payForm.expYY.value+payForm.expMM.value; 
  	document.payForm.submit();
}
</script>
</head>
<body>
<form name="payForm" method="post" action="formpayResult_utf.php">
    <div class="payfin_area">
      <div class="top">NICEPAY FORMPAY REQUEST(UTF-8)</div>
      <div class="conwrap">
        <div class="con">
          <div class="tabletypea">
            <table>
              <colgroup><col width="30%"/><col width="*"/></colgroup>
              <tr>
                <th><span>결제 상품명</span></th>
                <td><input type="text" name="GoodsName" value="<?php echo($nicepay->m_GoodsName);?>"></td>
              </tr>			  
              <tr>
                <th><span>결제 상품개수</span></th>
                <td><input type="text" name="GoodsCnt" value="<?=$goodsCnt?>"></td>
              </tr>
              <tr>
                <th><span>결제 상품금액</span></th>
                <td><input type="text" name="Amt" value="<?php echo($nicepay->m_Price);?>"></td>
              </tr>	  
              <tr>
                <th><span>구매자명</span></th>
                <td><input type="text" name="BuyerName" value="<?php echo($nicepay->m_BuyerName);?>"></td>
              </tr>	  
              <tr>
                <th><span>구매자 연락처</span></th>
                <td><input type="text" name="BuyerTel" value="<?php echo($nicepay->m_BuyerTel);?>"></td>
              </tr>   
              <tr>
                <th><span>구매자 이메일</span></th>
                <td><input type="text" name="BuyerEmail" value="<?php echo($nicepay->m_BuyerEmail);?>"></td>
              </tr>              
              <tr>
                <th><span>상품 주문번호</span></th>
                <td><input type="text" name="Moid" value="<?php echo($nicepay->m_Moid);?>"></td>
              </tr>	  
              <tr>
                <th><span>상점 아이디</span></th>
                <td><input type="text" name="MID" value="<?php echo($nicepay->m_MID);?>"></td>
              </tr> 
              
              <tr>
                <th><span>카드번호</span></th>
                <td><input type="text" name="CardNo" value=""/></td>
              </tr>
              <tr>
                <th><span>유효기간</span></th>
                <td>
                    <input type="text" name="expMM" maxLength="2" size="3" placeholder="MONTH" value="">/
                    <input type="text" name="expYY" maxLength="2" size="3" placeholder="YEAR" value="">
                    <input type="hidden" name="CardExpire" value=""/>                   
                </td>
              </tr>         
              <tr>
                <th><span>생년월일or사업자번호</span></th>
                <td><input type="password" name="BuyerAuthNum" value=""></td>
              </tr>
              <tr>
                <th><span>카드 비밀번호(앞 2자리)</span></th>
                <td><input type="password" name="CardPwd" maxlength="2" value=""></td>
              </tr>         
              <tr>
                <th><span>할부 기간(mm)</span></th>
                <td><input type="text" name="CardQuota" value="00"></td>
              </tr> 
              <!-- 옵션 -->
              <input type="hidden" name="UserIP" value="<?php echo($nicepay->m_UserIp); ?>">                <!-- USER IP -->
              <input type="hidden" name="MallIP" value="<?php echo($nicepay->m_MerchantServerIp); ?>">      <!-- MALL IP -->
              <input type="hidden" name="CardInterest" value="0"/>                                          <!-- 무이자(사용(1),미사용(0)) -->
              <input type="hidden" name="TransType" value="0">                                              <!-- 거래형태 -->

              <!-- 변경 불가 -->             
              <input type="hidden" name="PayMethod" value="CARD">                                           <!-- 결제수단 -->
              <input type="hidden" name="EdiDate" value="<?php echo($nicepay->m_EdiDate); ?>">              <!-- 전문 생성일시 -->
              <input type="hidden" name="MerchantKey" value="<?php echo($nicepay->m_MerchantKey); ?>">      <!-- 상점키 -->
              <input type="hidden" name="TrKey" value="">
              <input type="hidden" name="AuthFlg" value="2">              
            </table>
          </div>
        </div>
        <div class="btngroup">
          <a href="#" class="btn_blue" onClick="formPay();">요 청</a>
        </div>
      </div>
    </div>
</form>
</body>
</html>