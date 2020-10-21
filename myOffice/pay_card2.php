

<link rel="shortcut icon" href="/img/vmp_logo.ico" />
<link rel="stylesheet" href="./css/index.css" />
<link rel="stylesheet" href="css/pay_card.css"/>
<link rel="stylesheet" href="css/animate.css"/>
<script src="./js/wow.min.js"/></script>
<link href="https://fonts.googleapis.com/css?family=Noto+Sans+KR&display=swap&subset=korean" rel="stylesheet">

<?php
include_once ('./inc/title.php');
include_once ('./_common.php');
include_once ('./dbConn.php');
include_once('./getMemberInfo.php');
include_once ('../shop/shop.head.php');
require("./lib/lib/NicepayLite.php");

/*
 * ******************************************************
 * <결제요청 파라미터>
 * 결제시 Form 에 보내는 결제요청 파라미터입니다.
 * 샘플페이지에서는 기본(필수) 파라미터만 예시되어 있으며,
 * 추가 가능한 옵션 파라미터는 연동메뉴얼을 참고하세요.
 * ******************************************************
 */
$nicepay = new NicepayLite;

$nicepay->m_MerchantKey = "9rTdL4IKxaRI9rtsXXOl7jvX2jsexrpPwWfGazYsQ+m/7PSXN3ItkPBpmVnOxdQdLVu05TLVISdUoFdBRFo5ow=="; // 상점키

$nicepay->m_EdiDate = date("YmdHis");                           // 거래 날짜
?>

<!--메인 시작-->
<div class="pay_wrap">
    <div class="inner wow fadeInUp">
        <h2>Vendor Marketing Platform</h2>
        <h3>* 결제시 로그인 중인 계정의 VMP 포인트로 즉시 충전됩니다.</h3>
        <form name="payForm" id="payForm" method="post" onsubmit="return formPay()">
            <input type="hidden" name="mb_id" value="<?= $member["mb_id"] ?>"/>
            <input type="hidden" name="BuyerName" value="<?= trim($member['mb_name']) ?>(<?= trim($member['mb_id']) ?>)"/>
            <input type="hidden" name="Moid" value="<?= date("YmdHis") . $member["mb_id"] ?>"/>
            <input type="hidden" name="MID" value="GV2019613m"/>
            <input type="hidden" name="BuyerEmail" value="<?= trim($member['mb_email']) ?>"/>
            <input type="hidden" name="BuyerTel" value="<?= preg_replace("/[^0-9]*/s", "", $member["mb_hp"]) ?>"/>
            <input type="hidden" name="GoodsName" value="리브몰회원권"/>
            <input type="hidden" name="CardNo" value=""/>
            <input type="hidden" name="GoodsCnt" value="1"/>
            <!-- 변경 불가 -->
            <input type="hidden" name="CardInterest" value="0"/>                                          <!-- 무이자(사용(1),미사용(0)) -->
            <input type="hidden" name="TransType" value="0">                                              <!-- 거래형태 -->
            <input type="hidden" name="PayMethod" value="CARD">                                           <!-- 결제수단 -->
            <input type="hidden" name="EdiDate" value="<?php echo($nicepay->m_EdiDate); ?>">              <!-- 전문 생성일시 -->
            <input type="hidden" name="MerchantKey" value="<?php echo($nicepay->m_MerchantKey); ?>">      <!-- 상점키 -->
            <input type="hidden" name="TrKey" value="">
            <input type="hidden" name="AuthFlg" value="2">
            <div class="card_info">
                <ul>
                    <li class="card_info_box wow fadeInUp" data-wow-delay="0.3s">
                        <label for="card_num">카드번호</label>
                        <input type="text" class="valueOnly" id="card_num" name="card_num1" minlength="4" maxlength="4" placeholder="4자리" onkeyup="onlyNumber(this)" required value="">
                        <i>-</i>
                        <input type="text" class="valueOnly" id="card_num" name="card_num2" minlength="4" maxlength="4" placeholder="4자리" onkeyup="onlyNumber(this)" required value="">
                        <i>-</i>
                        <input type="text" class="valueOnly" id="card_num" name="card_num3" minlength="4" maxlength="4" placeholder="4자리" onkeyup="onlyNumber(this)" required value="">
                        <i>-</i>
                        <input type="text" class="valueOnly" id="card_num" name="card_num4" minlength="3" maxlength="4" placeholder="3~4자리" onkeyup="onlyNumber(this)" required value="">
                    </li>
                    <li class="wow fadeInUp" data-wow-delay="0.4s">
                        <label for="ex_date">유효기간</label>
                        <input type="text" class="valueOnly" id="ex_date" name="ex_date1" minlength="2" maxlength="2" placeholder="월" onkeyup="onlyNumber(this)" required value="">
                        <i>/</i>
                        <input type="text" class="valueOnly" id="ex_date" name="ex_date2" minlength="2" maxlength="2" placeholder="년" onkeyup="onlyNumber(this)" required value="">
                        <input type="hidden" name="CardExpire" value=""/>
                    </li>
                    <li class="select_installmt wow fadeInUp" data-wow-delay="0.5s">
                        <label for="installment">할부기간</label>
                        <select id="installment" name="CardQuota" align="center" required="required">
                            <option value="">- 선택 -</option>
                            <option value="00">일시불</option>
                            <option value="02">2개월</option>
                            <option value="03">3개월</option>
                        </select>
                    </li>
                    <li class="money_box wow fadeInUp" data-wow-delay="0.6s">
                        <label for="total_money">결제금액</label>
                        <div class="total_money_box">
                            <input type="text" class="valueOnly" id="total_money" name="Amt" required autocomplete="off" onkeyup="inputNumberFormat(this)" value="">
                            <b class="won">원</b>
                        </div>
                    </li>
                    <li class="money_box wow fadeInUp" data-wow-delay="0.6s">
                        <label for="total_money">카드 비밀번호(앞에 두자리)</label>
                        <div class="total_money_box">
                            <input type="password" class="valueOnly" id="total_money" name="CardPwd" required autocomplete="off"  value="" maxlength="2">
                        </div>
                    </li>
                    <li class="money_box wow fadeInUp" data-wow-delay="0.6s">
                        <label for="total_money">생년월일(6자리)</label>
                        <div class="total_money_box">
                            <input type="password" class="valueOnly" id="total_money" name="BuyerAuthNum" required autocomplete="off"  value="" maxlength="6">
                        </div>
                    </li>

                </ul>
            </div>
            <div class="card_img_box">
                <img class="wow flip" data-wow-duration="1s" data-wow-delay="0.7s" src="./images/vmp_sms-card_1.png" alt="카드결제 이미지"/>
                <p class="wow flipInX" data-wow-duration="1.5s" data-wow-delay="0.7s">카드 결제</p>
            </div>
            <div class="card_btn">
                <p>* 결제하신 금액은 환불이 불가능하오니 반드시 신중히 결제하시기 바랍니다.</p>
                <input type="submit" class="submit_btn" value="결제하기">
            </div>
        </form>
    </div>
    <?php
    include_once ('./loadingMsg.php');
    ?>
    <?php
    include_once ('./alertMsg.php');
    ?>
</div>
<script>
// 애니메이션 효과
    $(document).ready(function () {
        new WOW().init();
    });


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

// 카드번호 입력시 다음 입력칸으로 이동하게합니다.
    $('.card_info input').keyup(function () {
        var inputLimit = $(this).attr("maxlength");

        if (this.value.length == inputLimit) {
            $(this).next().next('input').focus();

            if ($(this).attr('name') == 'card_num4') {
                $('#ex_date').focus();
            }
        }
    });





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


//값 입력시 콤마찍기
    function inputNumberFormat(obj) {
        obj.value = comma(uncomma(obj.value));

        if (uncomma(obj.value) > 5000000) {
            alertMsg('error', '한도금액 500만원을 초과하였습니다.');
            $('#total_money').val('');
        }
    }


// input 값이 모두 입력되었을 때 border로 확인시켜주기
    function valueCheck(obj) {

        if ($(obj).val().length == $(obj).attr("maxLength")) {
            $(obj).addClass('on');
        } else {
            $(obj).removeClass('on');
        }

    }

    function formPay() {

        document.payForm.Amt.value = uncomma(payForm.Amt.value);
        document.payForm.CardExpire.value = payForm.ex_date2.value + payForm.ex_date1.value;
        document.payForm.CardNo.value = payForm.card_num1.value + payForm.card_num2.value + payForm.card_num3.value + payForm.card_num4.value;
        payCheck();
        return false
    }

    function loading() {
        loadMsg('on', '결제 진행중 입니다. 창을 끄지 말아주세요.');
    }

//결제하기 값검증
    function payCheck() {
        loadMsg('on', '결제 진행중 입니다. 창을 끄지 말아주세요.');

        $.ajax({
            url: 'nice_pay.php',
            type: 'POST',
            data: $("#payForm").serialize(),
            dataType: "JSON",
            error: function (data, status, error) {
                loadMsg('off');
                alertMsg('error', "에러가 발생했습니다.");
            },
            success: function (data) {
                loadMsg('off');
                if (data.result_code == "3001") {
                    $(".valueOnly").val("");
                    $("#installment option:eq(0)").attr("selected", "selected");
                    alertMsg('success', '결제가 완료되었습니다.');
                    // window.location.reload();
                } else {
                    if (data.msg == "null") {
                        alertMsg('error', "제데로 입력해주세요.");
                    } else {
                        alertMsg('error', data.msg);
                    }
                }


            }
        });



        return false;
    }








</script>

<?php
include_once ('../shop/shop.tail.php');

if( $member["payment2"] == "NO" ) {
?>
<!--    <script>-->
<!--        $(".alert_btn").attr("href", "/myOffice");-->
<!--        alertMsg('error',"결제 권한이 없습니다.");-->
<!--    </script>-->
<?php
}
?>



