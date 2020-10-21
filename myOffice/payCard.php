
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
?>

<!--메인 시작-->
<div class="pay_wrap">
    <div class="inner wow fadeInUp">
        <h2>Vendor Marketing Platform</h2>
        <h3>* 결제시 로그인 중인 계정의 VMP 포인트로 즉시 충전됩니다.</h3>
        <form name="pay_form" id="pay_form" onsubmit="return payCheck()">
            <input type="hidden" name="mb_id" value="<?=$member["mb_id"]?>"/>
            <input type="hidden" name="mb_name" value="<?=$member["mb_name"]?>"/>
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
                            <option value="00">일시불</option>
                            <option value="02">2개월</option>
                            <option value="03">3개월</option>
                        </select>
                    </li>
                    <li class="money_box wow fadeInUp" data-wow-delay="0.6s">
                        <label for="total_money">결제금액</label>
                        <div class="total_money_box">
                            <input type="text" id="total_money" name="total_money" required autocomplete="off" onkeyup="inputNumberFormat(this)" value="">
                            <b class="won">원</b>
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



//결제하기 값검증
function payCheck(){
    if ('<?=$member['mb_id']?>' != '00003571'){
        alert("점검 중 입니다. 죄송합니다.");
        return;
    }

    loadMsg('on','결제 진행중 입니다. 창을 끄지 말아주세요.');

    setTimeout(function(){
   $.ajax({
         url:'welcomePayments.php',
         type:'POST',
         data: encodeURI($("#pay_form").serialize()),
         async: false,
         error:function(request,status,error){
            loadMsg('off');
            alertMsg('error',"에러가 발생했습니다.");
        },
         success:function(result){
             loadMsg('off');

             var json = JSON.parse(result);
             console.log(json);
             
             if( json["result_code"] === "0000" ) {
                 $(".valueOnly").val("");
                 $("#installment option:eq(0)").attr("selected", "selected");
                 alertMsg('success','결제가 완료되었습니다.');
             } else {
                 alertMsg('error',json["result_message"]);
             }
        }
     });
    }, 1);

     

    
    
    
    
    return false;
}







</script>

<?php
include_once ('../shop/shop.tail.php');

if( $member["payment"] == "NO" ) {
?>
    <script>
        $(".alert_btn").attr("href", "/myOffice");
        alertMsg('error',"결제 권한이 없습니다.");
    </script>
<?php
}
?>

