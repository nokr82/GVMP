<?php
header("Content-Type:text/html; charset=euc-kr;");	
$ip        = $_SERVER['REMOTE_ADDR']; // Ŭ���̾�Ʈ ip
$server_ip = $_SERVER['SERVER_ADDR']; // ���� ip
$ediDate   = date("YmdHis");          // ���������Ͻ�
?>
<html>
<head>
<title>NICEPAY FORMPAY REQUEST(EUC-KR)</title>
<meta charset="euc-kr">
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
<form name="payForm" method="post" action="formpayResult.php">
    <div class="payfin_area">
      <div class="top">NICEPAY FORMPAY REQUEST(EUC-KR)</div>
      <div class="conwrap">
        <div class="con">
          <div class="tabletypea">
            <table>
              <colgroup><col width="30%"/><col width="*"/></colgroup>
              <tr>
                <th><span>��ǰ��</span></th>
                <td><input type="text" name="GoodsName" value="���̽�����"></td>
              </tr>	
              <tr>
                <th><span>��ǰ����</span></th>
                <td><input type="text" name="Amt" value="1004"></td>
              </tr>	 
              <tr>
                <th><span>��ǰ�ֹ���ȣ</span></th>
                <td><input type="text" name="Moid" value="mnoid1234567890"></td>
              </tr>	
              <tr>
                <th><span>�����ڸ�</span></th>
                <td><input type="text" name="BuyerName" value="���̽�"></td>
              </tr>	         
              <tr>
                <th><span>������ �̸���</span></th>
                <td><input type="text" name="BuyerEmail" value="happy@day.co.kr"></td>
              </tr>	 
              <tr>
                <th><span>������ ��ȭ��ȣ</span></th>
                <td><input type="text" name="BuyerTel" value="01012341234"></td>
              </tr>	
              <tr>
                <th><span>�������̵�</span></th>
                <td><input type="text" name="MID" value="nictest04m"></td>
              </tr>	          
              <tr>
                <th><span>ī���ȣ</span></th>
                <td><input type="text" name="CardNo" value=""/></td>
              </tr>
              <tr>
                <th><span>��ȿ�Ⱓ</span></th>
                <td>
                    <input type="text" name="expMM" maxLength="2" size="3" placeholder="MONTH" value="">/
                    <input type="text" name="expYY" maxLength="2" size="3" placeholder="YEAR" value="">
                    <input type="hidden" name="CardExpire" value=""/>                   
                </td>
              </tr>         
              <tr>
                <th><span>�������or����ڹ�ȣ</span></th>
                <td><input type="password" name="BuyerAuthNum" value=""></td>
              </tr>
              <tr>
                <th><span>ī�� ��й�ȣ(�� 2�ڸ�)</span></th>
                <td><input type="password" name="CardPwd" maxlength="2" value=""></td>
              </tr>         
              <tr>
                <th><span>�Һ� �Ⱓ(mm)</span></th>
                <td><input type="text" name="CardQuota" value="00"></td>
              </tr> 
              <!-- �ɼ� -->
              <input type="hidden" name="UserIP" value="<?=$ip?>">        <!-- USER IP -->
              <input type="hidden" name="MallIP" value="11.11.11.11">     <!-- MALL IP -->
              <input type="hidden" name="CardInterest" value="0"/>        <!-- ������(���(1),�̻��(0)) -->
              <input type="hidden" name="GoodsCnt" value="1">             <!-- ��ǰ ���� -->
              <input type="hidden" name="TransType" value="0">            <!-- �ŷ����� -->

              <!-- ���� �Ұ� -->             
              <input type="hidden" name="PayMethod" value="CARD">         <!-- �������� -->
              <input type="hidden" name="EdiDate" value="<?=$ediDate?>">  <!-- ���� �����Ͻ� -->
              <input type="hidden" name="SocketYN" value="Y">             <!-- ���ϻ�뿩�� -->
              <input type="hidden" name="TrKey" value="">
              <input type="hidden" name="AuthFlg" value="2">
            </table>
          </div>
        </div>
        <div class="btngroup">
          <a href="#" class="btn_blue" onClick="formPay();">�� û</a>
        </div>
      </div>
    </div>
</form>
</body>
</html>