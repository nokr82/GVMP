<?php
/******************************************************************************
 *
 *	 SYSTEM NAME		: 결제요청페이지
 *	 PROGRAM NAME		: mainPayConfirm.php
 *	 MAKER				: sajang
 *	 MAKE DATE			: 2017.12.06
 *	 PROGRAM CONTENTS	: 결제요청페이지
 *
 ************************** 변 경 이 력 *****************************************
 * 번호	작업자		작업일				변경내용
 *	1	스마트로	2017.12.06		결제요청페이지
 *******************************************************************************/
?>
<?php
$DEV_PAY_ACTION_URL = "https://tpay.smilepay.co.kr/interfaceURL.jsp";	//개발
$PRD_PAY_ACTION_URL = "https://pay.smilepay.co.kr/interfaceURL.jsp";	//운영

$MID = $_REQUEST['MID'];
$Amt = $_REQUEST['Amt'];
$GoodsName = $_REQUEST['GoodsName'];
$BuyerName = $_REQUEST['BuyerName'];
$BuyerAddr = $_REQUEST['BuyerAddr'];
$ediDate = $_REQUEST['ediDate'];
$UrlEncode = $_REQUEST['UrlEncode'];
$merchantKey = $_REQUEST['merchantKey'];
$DivideInfo = $_REQUEST['DivideInfo'];


if (!empty($DivideInfo)) // Base64 인코딩(utf-8 인코딩)
{
    $temp = str_replace("&#39;", "\"", $DivideInfo);
    $b64Enc = base64_encode($temp);
    $DivideInfo = $b64Enc;
}

$EncryptData = base64_encode(md5($ediDate . $MID . $Amt . $merchantKey));
$actionUrl = $DEV_PAY_ACTION_URL; // 개발 서버 URL
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
    <style type="text/css">
        <!--
        table.type {
            border-collapse: collapse;
            text-align: left;
            line-height: 1.5;
        }
        table.type thead th {
            padding: 8px;
            font-weight: bold;
            vertical-align: top;
            color: #369;
            border-bottom: 3px solid #036;
            text-align:center;
        }
        table.type tbody th {
            width: 150px;
            padding: 8px;
            font-weight: bold;
            vertical-align: top;
            border-bottom: 1px solid #ccc;
            background: #f3f6f7;
            text-align:center;
        }
        table.type td {
            width: 250px;
            padding: 8px;
            vertical-align: top;
            border-bottom: 1px solid #ccc;
            text-align:center;
        }

        input {
            width: 100%;
            padding: 6px 10px;
            margin: 2px 0;
            box-sizing: border-box;
        }

        .btnblue {
            background-color: #6DA2D9;
            box-shadow: 0 0 0 1px #6698cb inset,
            0 0 0 2px rgba(255,255,255,0.15) inset,
            0 4px 0 0 rgba(110, 164, 219, .7),
            0 4px 0 1px rgba(0,0,0,.4),
            0 4px 4px 1px rgba(0,0,0,0.5);
        }

        -->
    </style>

    <title>스마트로::인터넷결제</title>

    <script type="text/javascript">

        var encodingType = "EUC-KR";//EUC-KR

        function setAcceptCharset(form)
        {
            var browser = getVersionOfIE();
            if(browser != 'N/A')
                document.charset = encodingType;//ie
            else
                form.charset = encodingType;//else
        }

        function getVersionOfIE()
        {
            var word;
            var version = "N/A";

            var agent = navigator.userAgent.toLowerCase();
            var name = navigator.appName;

            // IE old version ( IE 10 or Lower )
            if ( name == "Microsoft Internet Explorer" )
            {
                word = "msie ";
            }
            else
            {
                // IE 11
                if ( agent.search("trident") > -1 ) word = "trident/.*rv:";

                // IE 12  ( Microsoft Edge )
                else if ( agent.search("edge/") > -1 ) word = "edge/";
            }

            var reg = new RegExp( word + "([0-9]{1,})(\\.{0,}[0-9]{0,1})" );

            if ( reg.exec( agent ) != null  )
                version = RegExp.$1 + RegExp.$2;

            return version;
        }

        function goPay()
        {
            var form = document.tranMgr;
            form.action = '<?php echo $actionUrl ?>';

            setAcceptCharset(form);



            form.GoodsName.value = "<?php echo $GoodsName ?>";
            form.BuyerName.value = "<?php echo $BuyerName ?>";
            form.BuyerAddr.value = "<?php echo $BuyerAddr ?>";
            form.EncryptData.value = "<?php echo $EncryptData ?>";
            form.DivideInfo.value = "<?php echo $DivideInfo ?>";

            if(form.FORWARD.value == 'Y') // 화면처리방식 Y(권장):상점페이지 팝업호출
            {
                var popupX = (window.screen.width / 2) - (545 / 2);
                var popupY = (window.screen.height /2) - (573 / 2);

                var winopts= "width=545,height=573,toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=no,resizable=no,left="+ popupX + ", top="+ popupY + ", screenX="+ popupX + ", screenY= "+ popupY;
                var win =  window.open("", "payWindow", winopts);

                try{
                    if(win == null || win.closed || typeof win.closed == 'undefined' || win.screenLeft == 0) {
                        alert('브라우저 팝업이 차단으로 설정되었습니다.\n 팝업 차단 해제를 설정해 주시기 바랍니다.');
                        return false;
                    }
                }catch(e){}

                form.target = "payWindow";//payWindow  고정
                form.submit();
            }
            else // 화면처리방식 N:결제모듈내부 팝업호출
            {
                form.target = "payFrame";//payFrame  고정
                form.submit();
            }

            return false;
        }


    </script>

</head>
<body ondragstart='' onselectstart='' style="overflow: scroll">
<form name="tranMgr" method="post" accept-charset="EUC-KR">
    <table class="type">
        <thead>
        <tr>
            <th scope="cols">Parameter</th>
            <th scope="cols">Value</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($_REQUEST as $key=>$value) {
            ?>
            <tr>
                <th scope="row"><?php echo $key ?></th>
                <td><?php echo $value ?></td>
            </tr>
            <input type="hidden" name="<?php echo $key ?>" value="<?php echo $value ?>" maxlength="100"/>
            <?php
        }
        ?>
        <input type="hidden" name="pay_name" value="홍도우" maxlength="100"/>
        <input type="hidden" name="pay_tel" value="010545454554" maxlength="100"/>
        <tr>
            <td class="btnblue" colspan="4" onclick="goPay();">등록하기</td>
        </tr>
        </tbody>
    </table>
</form>

<%-- iframe --%>
<iframe src="./blank.html" name="payFrame" frameborder="no" width="0" height="0" scrolling="yes" align="center"></iframe>

</body>
</html>
