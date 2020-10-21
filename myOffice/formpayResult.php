<?php
header("Content-Type:text/html; charset=euc-kr;"); 
require_once dirname(__FILE__).'./lib/nicepay/web/NicePayWEB.php';
require_once dirname(__FILE__).'./lib/nicepay/core/Constants.php';
require_once dirname(__FILE__).'./lib/nicepay/web/NicePayHttpServletRequestWrapper.php';
/*
*******************************************************
* <���� ��� ����>
* ����� ��� �ɼ��� ����� ȯ�濡 �µ��� �����ϼ���.
* �α� ���丮�� �� �����ϼ���.
*******************************************************
*/   
$nicepayWEB = new NicePayWEB();
$httpRequestWrapper = new NicePayHttpServletRequestWrapper($_REQUEST);
$_REQUEST = $httpRequestWrapper->getHttpRequestMap();
$payMethod = $_REQUEST['PayMethod'];
$merchantKey = "33F49GnCMS1mFYlGXisbUDzVf2ATWCl9k3R++d5hDd3Frmuos/XLx8XhXpe+LDYAbpGKZYSwtlyyLOtS/8aD7A==";

$nicepayWEB->setParam("NICEPAY_LOG_HOME","C:/log");             // �α� ���丮 ����
$nicepayWEB->setParam("APP_LOG","1");                           // ���ø����̼Ƿα� ��� ����(0: DISABLE, 1: ENABLE)
$nicepayWEB->setParam("EVENT_LOG","1");                         // �̺�Ʈ�α� ��� ����(0: DISABLE, 1: ENABLE)
$nicepayWEB->setParam("EncFlag","S");                           // ��ȣȭ�÷��� ����(N: ��, S:��ȣȭ)
$nicepayWEB->setParam("SERVICE_MODE", "PY0");                   // ���񽺸�� ����(���� ���� : PY0 , ��� ���� : CL0)
$nicepayWEB->setParam("Currency", "KRW");                       // ��ȭ ����(���� KRW(��ȭ) ����)
$nicepayWEB->setParam("PayMethod",$payMethod);                  // �������
$nicepayWEB->setParam("LicenseKey",$merchantKey);               // ����Ű

/*
*******************************************************
* <���� ��� �ʵ�>
* �Ʒ� ���� ������ �ܿ��� ���� Header�� ������ ������ Get ����
*******************************************************
*/
$responseDTO    = $nicepayWEB->doService($_REQUEST);

$resultCode     = $responseDTO->getParameter("ResultCode");     // ����ڵ� (���� ����ڵ�:3001)
$resultMsg      = $responseDTO->getParameter("ResultMsg");      // ����޽���
$authDate       = $responseDTO->getParameter("AuthDate");       // �����Ͻ� (YYMMDDHH24mmss)
$authCode       = $responseDTO->getParameter("AuthCode");       // ���ι�ȣ
$buyerName      = $responseDTO->getParameter("BuyerName");      // �����ڸ�
$mallUserID     = $responseDTO->getParameter("MallUserID");     // ȸ�����ID
$goodsName      = $responseDTO->getParameter("GoodsName");      // ��ǰ��
$mallUserID     = $responseDTO->getParameter("MallUserID");     // ȸ����ID
$mid            = $responseDTO->getParameter("MID");            // ����ID
$tid            = $responseDTO->getParameter("TID");            // �ŷ�ID
$moid           = $responseDTO->getParameter("Moid");           // �ֹ���ȣ
$amt            = $responseDTO->getParameter("Amt");            // �ݾ�
$cardQuota      = $responseDTO->getParameter("CardQuota");      // ī�� �Һΰ��� (00:�Ͻú�,02:2����)
$cardCode       = $responseDTO->getParameter("CardCode");       // ����ī����ڵ�
$cardName       = $responseDTO->getParameter("CardName");       // ����ī����
$bankCode       = $responseDTO->getParameter("BankCode");       // �����ڵ�
$bankName       = $responseDTO->getParameter("BankName");       // �����
$rcptType       = $responseDTO->getParameter("RcptType");       // ���� ������ Ÿ�� (0:�����������,1:�ҵ����,2:��������)
$rcptAuthCode   = $responseDTO->getParameter("RcptAuthCode");   // ���ݿ����� ���ι�ȣ
$carrier        = $responseDTO->getParameter("Carrier");        // ����籸��
$dstAddr        = $responseDTO->getParameter("DstAddr");        // �޴�����ȣ
$vbankBankCode  = $responseDTO->getParameter("VbankBankCode");  // ������������ڵ�
$vbankBankName  = $responseDTO->getParameter("VbankBankName");  // ������������
$vbankNum       = $responseDTO->getParameter("VbankNum");       // ������¹�ȣ
$vbankExpDate   = $responseDTO->getParameter("VbankExpDate");   // ��������Աݿ�����

/*
*******************************************************
* <���� ���� ���� Ȯ��>
*******************************************************
*/
$paySuccess = false;
if($payMethod == "CARD"){
    if($resultCode == "3001") $paySuccess = true;               // �ſ�ī��(���� ����ڵ�:3001)
}else if($payMethod == "BANK"){
    if($resultCode == "4000") $paySuccess = true;               // ������ü(���� ����ڵ�:4000)
}else if($payMethod == "CELLPHONE"){
    if($resultCode == "A000") $paySuccess = true;               // �޴���(���� ����ڵ�:A000)
}else if($payMethod == "VBANK"){
    if($resultCode == "4100") $paySuccess = true;               // �������(���� ����ڵ�:4100)
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
                <th><span>��� ����</th>
                <td>[<?=$resultCode?>]<?=$resultMsg?></td>
              </tr>
              <tr>
                <th><span>���� ����</th>
                <td><?=$payMethod?></td>
              </tr>
              <tr>
                <th><span>��ǰ��</th>
                <td><?=$goodsName?></td>
              </tr>
              <tr>
                <th><span>�ݾ�</th>
                <td><?=$amt?>��</td>
              </tr>
              <tr>
                <th><span>�ŷ����̵�</th>
                <td><?=$tid?></td>
              </tr>               
            <?php if($payMethod=="CARD"){?>
              <tr>
                <th><span>ī����</th>
                <td><?=$cardName?></td>
              </tr>
              <tr>
                <th><span>�Һΰ���</th>
                <td><?=$cardQuota?></td>
              </tr>
            <?php }else if($payMethod=="BANK"){?>
              <tr>
                <th><span>����</th>
                <td><?=$bankName?></td>
              </tr>
              <tr>
                <th><span>���ݿ����� Ÿ��</th>
                <td><?=$rcptType?>(0:�������,1:�ҵ����,2:��������)</td>
              </tr>
            <?php }else if($payMethod=="CELLPHONE"){?>
              <tr>
                <th><span>����� ����</th>
                <td><?=$carrier?></td>
              </tr>
              <tr>
                <th><span>�޴��� ��ȣ</th>
                <td><?=$dstAddr?></td>
              </tr>
            <?php }else if($payMethod=="VBANK"){?>
              <tr>
                <th><span>�Ա� ����</th>
                <td><?=$vbankBankName?></td>
              </tr>
              <tr>
                <th><span>�Ա� ����</th>
                <td><?=$vbankNum?></td>
              </tr>
              <tr>
                <th><span>�Ա� ����</th>
                <td><?=$vbankExpDate?></td>
              </tr>
            <?php }?>
          </table>
        </div>
      </div>
      <p>*�׽�Ʈ ���̵��ΰ�� ���� ���� 11�� 30�п� ��ҵ˴ϴ�.</p>
    </div>
  </div>
</body>
</html>