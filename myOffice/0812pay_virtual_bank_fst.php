<link rel="shortcut icon" href="/img/vmp_logo.ico"/>
<link rel="stylesheet" href="./css/index.css"/>
<link rel="stylesheet" href="css/animate.css"/>
<link rel="stylesheet" href="css/myoffice_sub.css"/>

<script src="./js/wow.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;500&display=swap" rel="stylesheet">

<?php
include_once('./inc/title.php');
include_once('./_common.php');
include_once('./dbConn.php');
include_once('./getMemberInfo.php');
include_once('../shop/shop.head.php');
$returnUrl = "http://gvmp.company/myOffice/pay_virtual_bank_result.php";// 결제결과를 수신할 가맹점 returnURL 설정
$retryUrl = "http://gvmp.company/myOffice/0821pay_virtual_inform.php"; // 가맹점 retryURL 설정

$VbankExpDate = date("Ymd", strtotime($day . "1 day"));

//$DEV_PAY_ACTION_URL = "https://tpay.smilepay.co.kr/interfaceURL.jsp";    //개발
$PRD_PAY_ACTION_URL = "https://pay.smilepay.co.kr/interfaceURL.jsp";    //운영

$M_PRD_PAY_ACTION_URL = "https://smpay.smilepay.co.kr/pay/interfaceURL";//모바일운영
$stopUrl = "http://gvmp.company/myOffice/0821pay_virtual_bank_fst.php"; // 결제중지 URL

$Moid = date("YmdHis");

$server_ip = $_SERVER['SERVER_ADDR']; // $_SERVER['SERVER_ADDR'];	// 서버 IP 가져오기
$GoodsName = '대리점 영업권';
$BuyerName = $member['mb_id'].'_'.$_GET['pay_name'];



$BuyerAddr = '';



$ediDate = date("YmdHis"); // 전문생성일시
$MID = "m2oway015m";
$merchantKey = "cixvpJN9KDaqc/6F8yYy1+bk+jiLSJVs2lw3IqAOrFbDIDHSWrWRCWhew0Gt0S3xZDvqvn37dgQvOf+nzh0mgQ==";

$Amt = $_GET['Amt'];
$R_Amt = number_format($Amt);
if ($Amt != '') {
    $EncryptData = base64_encode(md5($ediDate . $MID . $Amt . $merchantKey));

    if (is_mobile()) {
        $ck_m = 'M';
        $actionUrl = $M_PRD_PAY_ACTION_URL; // 개발 서버 URL
    } else {
        $ck_m = 'PC';
        $actionUrl = $PRD_PAY_ACTION_URL; // 개발 서버 URL
    }
}


?>

<!--메인 시작-->
<div class="pay_wrap pay_virtualbank">
    <form name="tranMgr" method="post" accept-charset="<?php if ($ck_m == 'PC') echo 'EUC-KR' ?>">
        <input type="hidden" name="pay_type" id="pay_type" value="1"/>
        <input type="hidden" name="mb_id" id="mb_id" value="<?= $member["mb_id"] ?>">
        <div class="inner_vtlbnk wow fadeInUp">
            <h2>Vendor Marketing Platform</h2>
            <h3>가상계좌 포인트 충전 </h3>
            <div class="vtlbnk_info_area box_tail wow fadeInUp" data-wow-delay="0.3s">
                <p>결제 금액</p>
                <div class="wrap_inp_price">
                    <input type="text" class="inp_prc" id="Amt" name="Amt" autocomplete="off"
                           onkeyup="inputNumberFormat(this)" value="<?php if ($R_Amt != '0') echo $R_Amt ?>"
                           placeholder="0"/>
                    <b>원</b>
                </div>
                <div class="wrap_inp_name">
                    <p><label for="user_name">실 구매자 성명</label></p>
                    <input type="text" class="valueOnly inp_name" id="pay_name" name="pay_name"
                           value="<?= $_GET['pay_name'] ?>"
                           placeholder="이름을 입력해주세요." required/>
                </div>
                <div class="wrap_inp_phone">
                    <p><label for="user_phone">실 구매자 연락처</label></p>
                    <input type="text" class="valueOnly inp_phone" id="pay_tel" name="pay_tel"
                           value="<?= $_GET['pay_tel'] ?>"
                           placeholder=" - 없이 숫자만 입력해 주세요." maxlength="15" onkeyup="onlyNumber(this)"/>
                </div>
                <div class="law_notice">해피콜 또는 전자 서명을 위한 것이며<br>실구매자와 연락이 되지 않을 시 수당이 미지급될 수 있으니 정확하게 기입해 주시길 바랍니다.<br>대리로
                    할 경우 민. 형사상 책임을 져야 합니다.
                </div>
                <input type="hidden" id="ReturnURL" name="ReturnURL" value="<?php echo $returnUrl ?>"/>
                <input type="hidden" name="RetryURL" maxlength="200" value="<?php echo $retryUrl ?>"/>
                <input type="hidden" name="PayMethod" value="VBANK" maxlength="100">
                <input type="hidden" name="GoodsCnt" value="1" maxlength="100">
                <input type="hidden" name="GoodsName" value="대리점 영업권3" maxlength="100">
                <input type="hidden" name="MID" value="m2oway015m" maxlength="100">
                <input type="hidden" name="BuyerName" value="<?= $member['mb_id'] ?>&&<?=$_GET['pay_name']?>" maxlength="100">
                <input type="hidden" name="BuyerEmail" value="<?= $member['mb_email'] ?>" maxlength="100">
                <input type="hidden" name="MallIP" value="<?= $server_ip ?>" maxlength="100">
                <input type="hidden" name="UserIP" value="<?= $_SERVER['REMOTE_ADDR'] ?>" maxlength="100">
                <input type="hidden" name="VbankExpDate" value="<?= $VbankExpDate ?>" maxlength="100">
                <input type="hidden" name="EncryptData" value="" maxlength="100">
                <input type="hidden" name="FORWARD" value="Y" maxlength="100">
                <input type="hidden" name="TransType" value="0" maxlength="100">
                <input type="hidden" name="EncodingType" value="utf8" maxlength="100">
                <input type="hidden" name="OpenType" value="KR" maxlength="100">
                <input type="hidden" name="SocketYN" value="N" maxlength="100">
                <input type="hidden" name="ediDate" value="<?= $ediDate ?>" maxlength="100">
                <input type="hidden" name="Moid" maxlength="64" value="<?php echo $Moid ?>" placeholder="특수문자 포함 불가"/>
                <input type="hidden" name="UrlEncode" value="N" maxlength="100">
                <input type="hidden" name="Productinfo" value="대리점" maxlength="100">
                <input type="hidden" name="BuyerTel" value=""
                       maxlength="100">
                <input type="hidden" name="MallResultFWD" value="" maxlength="100">
                <input type="hidden" name="merchantKey"
                       value="cixvpJN9KDaqc/6F8yYy1+bk+jiLSJVs2lw3IqAOrFbDIDHSWrWRCWhew0Gt0S3xZDvqvn37dgQvOf+nzh0mgQ=="
                       maxlength="100">


                <input type="hidden" name="clientType" value="WEB" maxlength="100">
                <input type="hidden" name="IsBankPayBridge" value="Y" maxlength="100">
                <input type="hidden" name="StopURL" value="<?php echo $stopUrl ?>" maxlength="100">
            </div>
            <div class="bottom_area">
                <div class="acnt_img_box">
                    <img class="wow flip" data-wow-duration="1s" data-wow-delay="0.7s"
                         src="./images/icon_pay_virtual_bank.png"
                         alt="통장 이미지" style="visibility: visible; animation-duration: 1s; animation-delay: 0.7s;"/>
                    <p class="wow flipInX" data-wow-duration="1.5s" data-wow-delay="0.7s"
                       style="visibility: visible; animation-duration: 1.5s; animation-delay: 0.7s; animation-name: flipInX;">
                        가상계좌 결제</p>
                </div>
                <p>* 결제하신 금액은 환불이 불가능하오니 반드시 신중히 결제하시기 바랍니다.</p>
                <span class="btn_chk" onclick="goPay()">확인</span>
            </div>
        </div>
    </form>
    <iframe src="./blank.html" name="payFrame" frameborder="no" width="0" height="0" scrolling="yes"
            align="center"></iframe>
    <?php
    include_once('./loadingMsg.php');
    ?>
    <?php
    include_once('./alertMsg.php');
    ?>
</div>
<script type="text/javascript">

    var encodingType = "EUC-KR";//EUC-KR

    function setAcceptCharset(form) {
        var browser = getVersionOfIE();
        if (browser != 'N/A')
            document.charset = encodingType;//ie
        else
            form.charset = encodingType;//else
    }

    // 숫자만 입력가능하게 처리하는 부분
    function onlyNumber(obj) {
        $(obj).keyup(function (event) {
            regexp = /[^0-9]/gi;
            value = $(this).val();
            if (regexp.test(value)) {
                $(this).val(value.replace(regexp, ''));
            }
        });
    }

    function getVersionOfIE() {
        var word;
        var version = "N/A";

        var agent = navigator.userAgent.toLowerCase();
        var name = navigator.appName;

        // IE old version ( IE 10 or Lower )
        if (name == "Microsoft Internet Explorer") {
            word = "msie ";
        } else {
            // IE 11
            if (agent.search("trident") > -1) word = "trident/.*rv:";
            // IE 12  ( Microsoft Edge )
            else if (agent.search("edge/") > -1) word = "edge/";
        }

        var reg = new RegExp(word + "([0-9]{1,})(\\.{0,}[0-9]{0,1})");

        if (reg.exec(agent) != null)
            version = RegExp.$1 + RegExp.$2;

        return version;
    }

    $(function () {
        if ('<?=$Amt?>' != '') {
            if ('<?=$ck_m?>' == 'M') {
                goInterface();
            } else {
                goPay();
            }

            if ('<?=$R_Amt?>' != '0') {
                $('#Amt').val('<?=$R_Amt?>')
            }
        }
    });


    function goPay() {

        var form = document.tranMgr;

        var Amt = form.Amt.value;
        var pay_name = form.pay_name.value;
        var pay_tel = form.pay_tel.value;

        console.log(pay_name);

        form.action = '<?php echo $actionUrl ?>';

        if (Amt == '' || Amt == 0) {
            alert("결제금액을 입력해 주세요.");
            Amt.focus();

        }
        if (pay_name == '') {
            alert("실 구매자 성명을 입력해 주세요.");
            pay_name.focus();
            return false;
        }
        if (pay_tel == '') {
            alert("연락처를 입력해 주세요.");
            pay_tel.focus();
            return false;
        }
        pay_tel = pay_tel.replace(/(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/,"$1-$2-$3");
        //alert(pay_tel);
        setAcceptCharset(form);
        form.GoodsName.value = "<?= $GoodsName ?>";
        form.BuyerName.value = "<?= $BuyerName ?>";
        form.BuyerTel.value = pay_tel;
        form.EncryptData.value = "<?=$EncryptData?>";
        form.Amt.value = uncomma($('#Amt').val());

        console.log(form.BuyerName.value);

        if ('<?=$Amt?>' != uncomma($('#Amt').val())) {
            location.href = 'http://gvmp.company/myOffice/0812pay_virtual_bank_fst.php?Amt='
                + uncomma($('#Amt').val()) + '&pay_name=' + $('#pay_name').val() + '&pay_tel=' + $('#pay_tel').val();
            return;
        }

        if (form.FORWARD.value == 'Y') // 화면처리방식 Y(권장):상점페이지 팝업호출
        {
            var popupX = (window.screen.width / 2) - (545 / 2);
            var popupY = (window.screen.height / 2) - (573 / 2);

            var winopts = "width=545,height=573,toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=no,resizable=no,left=" + popupX + ", top=" + popupY + ", screenX=" + popupX + ", screenY= " + popupY;
            var win = window.open("", "payWindow", winopts);

            try {
                if (win == null || win.closed || typeof win.closed == 'undefined' || win.screenLeft == 0) {
                    alert('브라우저 팝업이 차단으로 설정되었습니다.\n 팝업 차단 해제를 설정해 주시기 바랍니다.');
                    return false;
                }
            } catch (e) {
            }

            form.target = "payWindow";//payWindow  고정
            form.submit();

        } else // 화면처리방식 N:결제모듈내부 팝업호출
        {
            form.target = "payFrame";//payFrame  고정
            form.submit();
        }
        return false;
    }

    /**
     * URL 숨기기
     */
    window.addEventListener('load', function () {
        setTimeout(scrollTo, 0, 0, 1);
    }, false);

    function goInterface() {
        var form = document.tranMgr;
        form.action = '<?php echo $actionUrl?>';
        form.Amt.value = uncomma($('#Amt').val());
        form.GoodsName.value = "<?php echo $GoodsName?>";
        form.BuyerName.value = "<?php echo $BuyerName?>";
        form.BuyerTel.value = $('#pay_tel').val();
        form.EncryptData.value = "<?php echo $EncryptData?>";

        form.submit();
        return false;
    }

</script>
<script>
    // 애니메이션 효과
    $(document).ready(function () {
        new WOW().init();
    });


    //값 입력시 콤마찍기
    function inputNumberFormat(obj) {
        obj.value = comma(uncomma(obj.value));

        if (uncomma(obj.value) > 5000000) {
            alertMsg('error', '한도금액 500만원을 초과하였습니다.');
            $('#Amt').val('');
        }
    }


    //콤마찍기
    function comma(str) {
        str = String(str);
        return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
    }

    //콤마풀기
    function uncomma(str) {
        str = String(str);
        return str.replace(/[^\d]+/g, '');
    }
</script>

<?php
include_once('../shop/shop.tail.php');
?>