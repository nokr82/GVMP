
<link rel="shortcut icon" href="/img/vmp_logo.ico" />
<link rel="stylesheet" href="./css/index.css" />
<link rel="stylesheet" href="css/pay_card.css"/>
<link rel="stylesheet" href="css/animate.css"/>
<link  rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Sans+KR&display=swap&subset=korean"/>
<script src="./js/wow.min.js"/></script>
<?php
include_once ('./inc/title.php');
include_once ('./_common.php');
include_once ('./dbConn.php');
include_once('./getMemberInfo.php');
include_once ('../shop/shop.head.php');

if ($member['payment3'] != 'YES' && $member['mb_id'] != 'admin'){
    alert('결제권한이 없습니다.');
}



$url = 'https://www.pay-go.net/oauth/token'; //접속할 url 입력

$post_data["username"] = "vmpcompany";
$post_data["password"] = "11111111";
$post_data["grant_type"] = "password";

//$header_data = array('Authorization: Bearer access_token_value'); //에러 발생

$ch = curl_init(); //curl 사용 전 초기화 필수(curl handle)

curl_setopt($ch, CURLOPT_URL, $url); //URL 지정하기
curl_setopt($ch, CURLOPT_POST, 1); //0이 default 값이며 POST 통신을 위해 1로 설정해야 함
curl_setopt ($ch, CURLOPT_POSTFIELDS, $post_data); //POST로 보낼 데이터 지정하기
curl_setopt ($ch, CURLOPT_POSTFIELDSIZE, 0); //이 값을 0으로 해야 알아서 &post_data 크기를 측정하는듯
curl_setopt($ch, CURLOPT_HEADER, true);//헤더 정보를 보내도록 함(*필수)
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌. 이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능(테스트 시 기본값은 1인듯?)
$res = curl_exec ($ch);

//var_dump($res);//결과값 확인하기

//print_r($res);//마지막 http 전송 정보 출력
//echo curl_errno($ch);//마지막 에러 번호 출력
//echo curl_error($ch);//현재 세션의 마지막 에러 출력
//curl_close($ch);
//print_r($res);

$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$header = substr($res, 0, $header_size);
$body = substr($res, $header_size);
//print_r($body);

$body_json = json_decode($body, true);

?>

<div class="pay_wrap">
    <div class="inner wow fadeInUp">
        <h2>Vendor Marketing Platform</h2>
        <h3>* 결제시 로그인 중인 계정의 VMP 포인트로 즉시 충전됩니다.</h3>
        <form name="pay_form" id="pay_form" method="POST" onsubmit="return payCheck()">
            <input type="hidden" name="mb_id" id="mb_id" value="<?=$member["mb_id"]?>"/>
            <input type="hidden" name="mb_name" value="<?=$member["mb_name"]?>"/>
            <input type="hidden" name="mb_hp" value="<?=$member["mb_hp"]?>"/>
            <input type="hidden" name="mb_email" value="<?=$member["mb_email"]?>"/>
            <input type="hidden" name="token" value="<?=$body_json['access_token']?>"/>
            <input type="hidden" name="pay_type" id="pay_type" value="2">
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
                    </li>
                    <li class="select_installmt wow fadeInUp" data-wow-delay="0.5s">
                        <label for="installment">할부기간</label>
                        <select id="installment" name="installment" align="center" required="required">
                            <option value="">- 선택 -</option>
                            <option value="0">일시불</option>
                            <option value="02">2개월</option>
                            <option value="03">3개월</option>
                            <option value="04">4개월</option>
                            <option value="05">5개월</option>
                            <option value="06">6개월</option>
                        </select>
                    </li>
                    <li class="money_box wow fadeInUp" data-wow-delay="0.6s">
                        <label for="total_money">결제금액</label>
                        <div class="total_money_box">
                            <input type="text" class="valueOnly" id="total_money" name="total_money" required autocomplete="off" onkeyup="inputNumberFormat(this)">
                            <b class="won">원</b>
                        </div>
                    </li>
                    <li class="wrap_inp_name">
                      <p><label for="pay_name">카드주 성명</label></p>
                      <input type="text" class="valueOnly inp_name" id="pay_name" name="pay_name"  value="" placeholder="이름을 입력해주세요." required />
                    </li>
                    <li class="wrap_inp_phone">
                      <p><label for="pay_tel">카드주 연락처</label></p>
                      <input type="text" class="valueOnly inp_phone" id="pay_tel" name="pay_tel"  placeholder=" - 없이 숫자만 입력해 주세요." required  maxlength="15" onkeyup="onlyNumber(this)"/>
                    </li>                                     
                  </ul>
                  
                  <div class="law_notice">해피콜 또는 전자 서명을 위한 것이며<br>실구매자와 연락이 되지 않을 시 수당이 미지급될 수 있으니 정확하게 기입해 주시길 바랍니다.<br>대리로 할 경우 민. 형사상 책임을 져야 합니다.</div>
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
$(document).ready(function(){
    new WOW().init();
 });






// 숫자만 입력가능하게 처리하는 부분
function onlyNumber(obj){
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

        if($(this).attr('name') == 'card_num4'){
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

    if( uncomma(obj.value) > 5000000 ){
        alertMsg('error','한도금액 500만원을 초과하였습니다.');
        $('#total_money').val('');
    }
}


// input 값이 모두 입력되었을 때 border로 확인시켜주기
function valueCheck(obj){

    if( $(obj).val().length == $(obj).attr("maxLength") ){
        $(obj).addClass('on');
    } else {
        $(obj).removeClass('on');
    }

}

function save_date(){

    var pay_name = document.getElementById('pay_name').value;
    var pay_tel = document.getElementById('pay_tel').value;
    pay_tel = pay_tel.replace(/(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/,"$1-$2-$3");
    var pay_type = document.getElementById('pay_type').value;
    var amt = document.getElementById('total_money').value;
    var mb_id = document.getElementById('mb_id').value;



    var valueSave = {"pay_name":pay_name,"pay_tel":pay_tel,"pay_type":pay_type,"amt":amt,"mb_id":mb_id };
    $.ajax({
        type: 'post',
        url: '0812_pay_virtual.php',
        data: valueSave,
        dataType: 'json',
        error: function (xhr, status, error) {
            alertMsg('error',"에러가 발생했습니다.");
        },
        success: function (data) {
            console.log(data.sql);
            if(data.success == 'ok'){
                console.log(data.sql);
            }else{
                console.log(data.sql);
            }
        },
    });
}

//결제하기 값검증
function payCheck() {

    loadMsg('on','결제 진행중 입니다. 창을 끄지 말아주세요.');

    var formValue = $("#pay_form").serialize();

    $("#installment option:eq(0)").attr("selected", "selected");


        $.ajax({
            type: 'post',
            url: 'payGo.php',
            data: formValue,
            // dataType: 'json',
            error: function (xhr, status, error) {
                loadMsg('off');
                alertMsg('error',"에러가 발생했습니다.");
            },
            success: function (result) {
                loadMsg('off');
                var json = JSON.parse(result);
                console.log(json);
                if( json['success'] == true) {
                    save_date();
                    $("#installment option:eq(0)").attr("selected", "selected");
                    alertMsg('success','결제가 완료되었습니다.');
                    location.reload();
                } else {
                    alertMsg('error',json['message']);
                }
            },
        });
    return false;
}
</script>

<?php
include_once ('../shop/shop.tail.php');

if( $member["payment3"] == "NO" && $member['mb_id'] != 'admin') {
?>
    <script>
        $(".alert_btn").attr("href", "/myOffice");
        alertMsg('error',"결제 권한이 없습니다.");
    </script>
<?php
}
?>

