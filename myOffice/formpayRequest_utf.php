<?php
header("Content-Type:text/html; charset=UTF-8;");	
$ip        = $_SERVER['REMOTE_ADDR']; // 클라이언트 ip
$server_ip = $_SERVER['SERVER_ADDR']; // 서버 ip
$ediDate   = date("YmdHis");          // 전문생성일시
?>
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
                <th><span>상품명</span></th>
                <td><input type="text" name="GoodsName" value="나이스페이"></td>
              </tr>	
              <tr>
                <th><span>상품가격</span></th>
                <td><input type="text" name="Amt" value="1004"></td>
              </tr>	 
              <tr>
                <th><span>상품주문번호</span></th>
                <td><input type="text" name="Moid" value="mnoid1234567890"></td>
              </tr>	
              <tr>
                <th><span>구매자명</span></th>
                <td><input type="text" name="BuyerName" value="나이스"></td>
              </tr>	         
              <tr>
                <th><span>구매자 이메일</span></th>
                <td><input type="text" name="BuyerEmail" value="happy@day.co.kr"></td>
              </tr>	 
              <tr>
                <th><span>구매자 전화번호</span></th>
                <td><input type="text" name="BuyerTel" value="01012341234"></td>
              </tr>	
              <tr>
                <th><span>상점아이디</span></th>
                <td><input type="text" name="MID" value="nictest04m"></td>
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
              <input type="hidden" name="UserIP" value="<?=$ip?>">        <!-- USER IP -->
              <input type="hidden" name="MallIP" value="11.11.11.11">     <!-- MALL IP -->
              <input type="hidden" name="CardInterest" value="0"/>        <!-- 무이자(사용(1),미사용(0)) -->
              <input type="hidden" name="GoodsCnt" value="1">             <!-- 상품 갯수 -->
              <input type="hidden" name="TransType" value="0">            <!-- 거래형태 -->

              <!-- 변경 불가 -->             
              <input type="hidden" name="PayMethod" value="CARD">         <!-- 결제수단 -->
              <input type="hidden" name="EdiDate" value="<?=$ediDate?>">  <!-- 전문 생성일시 -->
              <input type="hidden" name="SocketYN" value="Y">             <!-- 소켓사용여부 -->
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