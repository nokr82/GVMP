<html>
<head>
<title>본인인증서비스 Sample 화면</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script language=javascript>
<!--
    var KMCIS_window;
	//window.name = 'sendJsp';
	//document.domain = "mobile-ok.com";
    function openDRMOKWindow(){
    	
    	DRMOK_window = window.open('', 'DRMOKWindow', 'width=425, height=550, resizable=0, scrollbars=no, status=0, titlebar=0, toolbar=0, left=435, top=250' );

        if(DRMOK_window == null){
			alert(" ※ 윈도우 XP SP2 또는 인터넷 익스플로러 7 사용자일 경우에는 \n    화면 상단에 있는 팝업 차단 알림줄을 클릭하여 팝업을 허용해 주시기 바랍니다. \n\n※ MSN,야후,구글 팝업 차단 툴바가 설치된 경우 팝업허용을 해주시기 바랍니다.");
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
	$certPath = "D:/cert/인증서Cert.der";
	$sEncryptedData = $oED->encrypt($reqInfo,$certPath);
	echo "sEncryptedData : ".$sEncryptedData."<br>";
	if($sEncryptedData == false){
		echo $oED->ErrorCode;
	}
?>
<body bgcolor="#FFFFFF" topmargin=0 leftmargin=0 marginheight=0 marginwidth=0>

<center>
<br><br><br><br><br><br>
<span class="style1">본인인증서비스 요청화면 Sample입니다.</span><br>
<br><br>
<table cellpadding=1 cellspacing=1>
    <tr>
        <td align=center>회원사ID</td>
        <?php print(" <td align=left>$postcpid </td>"); ?>
    </tr>
    <tr>
        <td align=center>URL코드</td>
         <?php print(" <td align=left>$posturlCode </td>"); ?>
    </tr>

    <tr>
        <td align=center>요청일시</td>
        <?php print(" <td align=left>$postclntReqNum </td>"); ?>
    </tr>

    <tr>
        <td align=center>거래 일련번호</td>
        <?php print(" <td align=left>$postreqdate </td>"); ?>
    </tr>

    <tr>
        <td align=center>결과수신URL</td>
        <?php print(" <td align=left>$postretUrl </td>"); ?>
    </tr>
</table>
<?php 
echo "<form name=form1 method=post >";
echo "<input type=hidden name=req_info value = $sEncryptedData>";
echo "<input type=hidden name=rtn_url      value = $postretUrl>";
echo "<input type=hidden name=cpid      value =  $postcpid> ";
echo "<input type=submit value=본인인증서비스 요청  onclick= javascript:openDRMOKWindow();> ";
echo "</form>";
?>

<br>
<br>
  이 Sample화면은 본인인증서비스 요청화면 개발시 참고가 되도록 제공하고 있는 화면입니다.<br>
<br>
</center>
</BODY>
</HTML>	 
				
	
