<?php
	$reqdate = date("YmdHis");
	$clntReqNum = $reqdate.rand(100000,999999);
?>
<html>
    <head>
        <title>������������  �׽�Ʈ</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <style>
            <!--
            body,p,ol,ul,td
            {
                font-family: ����;
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
            <span class="style1">������������ �׽�Ʈ</span><br>

            <form name="reqForm" method="post" action="./dream_php_test02.php">
           
                <table cellpadding=1 cellspacing=1>
                    <tr>
                        <td align=center>ȸ����ID</td>
                        <td align=left><input type="text" name="cpid" size='41' maxlength ='10' value = "ȸ������̵�"></td>
                    </tr>
                    <tr>
                        <td align=center>URL�ڵ�</td>
                        <td align=left><input type="text" name="serviceCode" size='41' maxlength ='6' value=""></td>
                    </tr>
                    <tr>
                        <td align=center>��û��ȣ</td>
                        <td align=left><input type="text" name="clntReqNum" size='41' maxlength ='30' value="<?php echo $clntReqNum ?>"></td>
                    </tr>
                    <tr>
                        <td align=center>��û�Ͻ�</td>
						<!-- ����ð� ����(YYYYMMDDHI24MISS) -->
                        <td align=left><input type="text" name="reqdate" size="41" maxlength ='14' value="<?php echo $reqdate ?>"></td>
					</tr>
  
                    
                        <td align=center>�������URL</td>
                        <td align=left><input type="text" name="retUrl" size="41" value="https://localhost/dream_php_test03.php"></td>
                    </tr>
                </table>
                <br><br>
                <input type="submit" value="�������� �׽�Ʈ" >
            </form>
            <br>
            <br>

            <br>
        </center>
    </body>
</html>