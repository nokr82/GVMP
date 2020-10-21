<?php
	$reqdate = date("YmdHis");
	$clntReqNum = $reqdate.rand(100000,999999);
?>
<html>
    <head>
        <title>본인인증서비스  테스트</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <style>
            <!--
            body,p,ol,ul,td
            {
                font-family: 굴림;
                font-size: 12px;
            }

            a:link { size:9px;color:#000000;text-decoration: none; line-height: 12px}
            a:visited { size:9px;color:#555555;text-decoration: none; line-height: 12px}
            a:hover { color:#ff9900;text-decoration: none; line-height: 12px}

            .style1 {
                color: #6b902a;
                font-weight: bold;
            }
            .style2 {
                color: #666666
            }
            .style3 {
                color: #3b5d00;
                font-weight: bold;
            }
            -->
        </style>

    </head>
    <body bgcolor="#FFFFFF" topmargin=0 leftmargin=0 marginheight=0 marginwidth=0>
        <center>
            <br><br><br>
            <span class="style1">본인인증서비스 테스트</span><br>

            <form name="reqForm" method="post" action="./dream_php_test02.php">
           
                <table cellpadding=1 cellspacing=1>
                    <tr>
                        <td align=center>회원사ID</td>
                        <td align=left><input type="text" name="cpid" size='41' maxlength ='10' value = "회원사아이디"></td>
                    </tr>
                    <tr>
                        <td align=center>URL코드</td>
                        <td align=left><input type="text" name="serviceCode" size='41' maxlength ='6' value=""></td>
                    </tr>
                    <tr>
                        <td align=center>요청번호</td>
                        <td align=left><input type="text" name="clntReqNum" size='41' maxlength ='30' value="<?php echo $clntReqNum ?>"></td>
                    </tr>
                    <tr>
                        <td align=center>요청일시</td>
						<!-- 현재시각 세팅(YYYYMMDDHI24MISS) -->
                        <td align=left><input type="text" name="reqdate" size="41" maxlength ='14' value="<?php echo $reqdate ?>"></td>
					</tr>
  
                    
                        <td align=center>결과수신URL</td>
                        <td align=left><input type="text" name="retUrl" size="41" value="https://localhost/dream_php_test03.php"></td>
                    </tr>
                </table>
                <br><br>
                <input type="submit" value="본인인증 테스트" >
            </form>
            <br>
            <br>

            <br>
        </center>
    </body>
</html>