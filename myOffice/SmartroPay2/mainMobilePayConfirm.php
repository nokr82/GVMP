<?php
/******************************************************************************
 *
 *	 SYSTEM NAME		: 결제요청페이지
 *	 PROGRAM NAME		: mainMobilePayConfirm.php
 *	 MAKER				: sajang
 *	 MAKE DATE			: 2017.12.27
 *	 PROGRAM CONTENTS	: 결제요청페이지
 *
 ************************** 변 경 이 력 *****************************************
 * 번호	작업자		작업일				변경내용
 *	1	스마트로	2017.12.27		결제요청페이지
 *******************************************************************************/
?>
<?php
$DEV_PAY_ACTION_URL = "https://tspay.smilepay.co.kr/pay/interfaceURL";	//개발
$PRD_PAY_ACTION_URL = "https://smpay.smilepay.co.kr/pay/interfaceURL";	//운영

$MID = $_REQUEST['MID'];
$Amt = $_REQUEST['Amt'];
$GoodsName = $_REQUEST['GoodsName'];
$BuyerName = $_REQUEST['BuyerName'];
$BuyerAddr = $_REQUEST['BuyerAddr'];
$ediDate = $_REQUEST['ediDate'];
$merchantKey = $_REQUEST['merchantKey'];
$EncryptData = $_REQUEST['EncryptData'];
$DivideInfo = $_REQUEST['DivideInfo'];

if(!empty($DivideInfo)) // Base64 인코딩(utf-8 인코딩)
{
    $temp = str_replace("&#39;", "\"", $DivideInfo);

    $b64Enc = base64_encode($temp);
    $DivideInfo = $b64Enc;
}

$actionUrl = $PRD_PAY_ACTION_URL; // 개발 서버 URL
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
        /**
         * URL 숨기기
         */
        window.addEventListener('load', function(){
            setTimeout(scrollTo, 0, 0, 1);
        }, false);

        function goInterface()
        {
            var form = document.tranMgr;
            form.action = '<?php echo $actionUrl?>';

            form.GoodsName.value = "<?php echo $GoodsName?>";
            form.BuyerName.value = "<?php echo $BuyerName?>";
            form.BuyerAddr.value = "<?php echo $BuyerAddr?>";
            form.EncryptData.value = "<?php echo $EncryptData?>";

            form.submit();
            return false;
        }

    </script>

</head>
<body ondragstart='' onselectstart='' style="overflow: scroll">
<form name="tranMgr" method="post">
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
        <tr>
            <td class="btnblue" colspan="4" onclick="goInterface();">등록하기</td>
        </tr>
        </tbody>
    </table>
</form>
</body>
</html>
