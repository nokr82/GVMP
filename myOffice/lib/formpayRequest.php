 <?php
header("Content-Type:text/html; charset=euc-kr;"); 
require("./lib/NicepayLite.php");
/*
*******************************************************
* <������û �Ķ����>
* ������ Form �� ������ ������û �Ķ�����Դϴ�.
* ���������������� �⺻(�ʼ�) �Ķ���͸� ���õǾ� ������, 
* �߰� ������ �ɼ� �Ķ���ʹ� �����޴����� �����ϼ���.
*******************************************************
*/  
$nicepay = new NicepayLite;

$nicepay->m_MerchantKey = "33F49GnCMS1mFYlGXisbUDzVf2ATWCl9k3R++d5hDd3Frmuos/XLx8XhXpe+LDYAbpGKZYSwtlyyLOtS/8aD7A=="; // ����Ű
$nicepay->m_MID         = "nictest04m";                         // �������̵�
$nicepay->m_Price       = "1004";                               // ������ǰ�ݾ�
$nicepay->m_BuyerEmail  = "happy@day.co.kr";                    // �����ڸ����ּ�
$nicepay->m_GoodsName   = "���̽�����";                         // ������ǰ��
$nicepay->m_BuyerName   = "���̽�";                             // �����ڸ� 
$nicepay->m_BuyerTel    = "010-0000-0000";                      // �����ڿ���ó           
$nicepay->m_Moid        = "mnoid1234567890";                    // ��ǰ�ֹ���ȣ                     
$goodsCnt               = "1";                                  // ������ǰ����
$nicepay->m_EdiDate = date("YmdHis");                           // �ŷ� ��¥
?>
<!DOCTYPE html>
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
                <th><span>���� ��ǰ��</span></th>
                <td><input type="text" name="GoodsName" value="<?php echo($nicepay->m_GoodsName);?>"></td>
              </tr>			  
              <tr>
                <th><span>���� ��ǰ����</span></th>
                <td><input type="text" name="GoodsCnt" value="<?=$goodsCnt?>"></td>
              </tr>
              <tr>
                <th><span>���� ��ǰ�ݾ�</span></th>
                <td><input type="text" name="Amt" value="<?php echo($nicepay->m_Price);?>"></td>
              </tr>	  
              <tr>
                <th><span>�����ڸ�</span></th>
                <td><input type="text" name="BuyerName" value="<?php echo($nicepay->m_BuyerName);?>"></td>
              </tr>	  
              <tr>
                <th><span>������ ����ó</span></th>
                <td><input type="text" name="BuyerTel" value="<?php echo($nicepay->m_BuyerTel);?>"></td>
              </tr>   
              <tr>
                <th><span>������ �̸���</span></th>
                <td><input type="text" name="BuyerEmail" value="<?php echo($nicepay->m_BuyerEmail);?>"></td>
              </tr>              
              <tr>
                <th><span>��ǰ �ֹ���ȣ</span></th>
                <td><input type="text" name="Moid" value="<?php echo($nicepay->m_Moid);?>"></td>
              </tr>	  
              <tr>
                <th><span>���� ���̵�</span></th>
                <td><input type="text" name="MID" value="<?php echo($nicepay->m_MID);?>"></td>
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
              <input type="hidden" name="UserIP" value="<?php echo($nicepay->m_UserIp); ?>">                <!-- USER IP -->
              <input type="hidden" name="MallIP" value="<?php echo($nicepay->m_MerchantServerIp); ?>">      <!-- MALL IP -->
              <input type="hidden" name="CardInterest" value="0"/>                                          <!-- ������(���(1),�̻��(0)) -->
              <input type="hidden" name="TransType" value="0">                                              <!-- �ŷ����� -->

              <!-- ���� �Ұ� -->             
              <input type="hidden" name="PayMethod" value="CARD">                                           <!-- �������� -->
              <input type="hidden" name="EdiDate" value="<?php echo($nicepay->m_EdiDate); ?>">              <!-- ���� �����Ͻ� -->
              <input type="hidden" name="MerchantKey" value="<?php echo($nicepay->m_MerchantKey); ?>">      <!-- ����Ű -->
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