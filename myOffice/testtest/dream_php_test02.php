<html>
<head>
<title>������������ Sample ȭ��</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script language=javascript>
<!--
    var KMCIS_window;
	//window.name = 'sendJsp';
	//document.domain = "mobile-ok.com";
    function openDRMOKWindow(){
    	
    	DRMOK_window = window.open('', 'DRMOKWindow', 'width=425, height=550, resizable=0, scrollbars=no, status=0, titlebar=0, toolbar=0, left=435, top=250' );

        if(DRMOK_window == null){
			alert(" �� ������ XP SP2 �Ǵ� ���ͳ� �ͽ��÷η� 7 ������� ��쿡�� \n    ȭ�� ��ܿ� �ִ� �˾� ���� �˸����� Ŭ���Ͽ� �˾��� ����� �ֽñ� �ٶ��ϴ�. \n\n�� MSN,����,���� �˾� ���� ���ٰ� ��ġ�� ��� �˾������ ���ֽñ� �ٶ��ϴ�.");
        }
				
        document.form1.action = 'https://www.mobile-ok.com/popup/common/hscert.jsp';
        document.form1.target = 'DRMOKWindow';
    }

//-->
</script>

</head>
<body>

<?php 
	$postcpid = $_REQUEST["cpid"];
	$posturlCode = $_REQUEST["serviceCode"];
	$postclntReqNum = $_REQUEST["clntReqNum"];
	$postreqdate = $_REQUEST["reqdate"];
	$postretUrl = $_REQUEST["retUrl"];
	$reqInfo =  $posturlCode."/" .$postclntReqNum. "/" .$postreqdate;

	$oED = new Crypt();
	$certPath = "D:/cert/������Cert.der";
	$sEncryptedData = $oED->encrypt($reqInfo,$certPath);
	echo "sEncryptedData : ".$sEncryptedData."<br>";
	if($sEncryptedData == false){
		echo $oED->ErrorCode;
	}
?>
<body bgcolor="#FFFFFF" topmargin=0 leftmargin=0 marginheight=0 marginwidth=0>

<center>
<br><br><br><br><br><br>
<span class="style1">������������ ��ûȭ�� Sample�Դϴ�.</span><br>
<br><br>
<table cellpadding=1 cellspacing=1>
    <tr>
        <td align=center>ȸ����ID</td>
        <?php print(" <td align=left>$postcpid </td>"); ?>
    </tr>
    <tr>
        <td align=center>URL�ڵ�</td>
         <?php print(" <td align=left>$posturlCode </td>"); ?>
    </tr>

    <tr>
        <td align=center>��û�Ͻ�</td>
        <?php print(" <td align=left>$postclntReqNum </td>"); ?>
    </tr>

    <tr>
        <td align=center>�ŷ� �Ϸù�ȣ</td>
        <?php print(" <td align=left>$postreqdate </td>"); ?>
    </tr>

    <tr>
        <td align=center>�������URL</td>
        <?php print(" <td align=left>$postretUrl </td>"); ?>
    </tr>
</table>
<?php 
echo "<form name=form1 method=post >";
echo "<input type=hidden name=req_info value = $sEncryptedData>";
echo "<input type=hidden name=rtn_url      value = $postretUrl>";
echo "<input type=hidden name=cpid      value =  $postcpid> ";
echo "<input type=submit value=������������ ��û  onclick= javascript:openDRMOKWindow();> ";
echo "</form>";
?>

<br>
<br>
  �� Sampleȭ���� ������������ ��ûȭ�� ���߽� ���� �ǵ��� �����ϰ� �ִ� ȭ���Դϴ�.<br>
<br>
</center>
</BODY>
</HTML>	 
				
	
