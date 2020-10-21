<?php
    $cssCheck = false; // _common.php에 인클루드 되는 CSS 적용 안 되게 하기 위한 변수
    include_once ('./_common.php');
    include_once ('../dbConn.php');
    
    
    if ($is_guest) // 로그인 안 했을 때 로그인 페이지로 이동
        header('Location: /bbs/login.php');
    

    
    $g5_memberRow = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$member["mb_id"]}'"));
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>간편회원가입</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/animate.css">
      <script src="../js/jquery.min.js"></script>
    <script src="js/wow.min.js"></script>
    <link rel="shortcut icon" href="/img/vmp_logo.ico" />
</head>

<body>
<!-- header -->
<?php
include_once ('../shop/shop.head.php');
?>
<div class="wrap">
    <div class="main_content">
        <div class="info_box clearfix">
            <i><img src="img/vmp_info.png" alt="정보 아이콘"></i>
            <p>간편 회원가입권으로 VM 회원가입 또는 VM 리뉴얼을 할 수 있습니다.</p>
        </div>
        <ul class="join_content clearfix">
            <li class="join_step wow fadeInUp">
                <img src="img/vmp_icon_3.png" alt="회원가입권 아이콘" class="wow flip">
                <h2>보유 간편 회원가입권</h2>
                <strong id="membershipEA"><?php echo number_format($g5_memberRow["membership"]); ?>개</strong>
                <a href="#none" class="btn payment_btn" onclick="payJoin()">
                    <i><img src="img/vmp_card.png" alt="결제 아이콘"></i>결제하기
                </a>
                <a href="#none" class="btn renewal_btn" onclick="renewal()">
                    <i><img src="img/gear.png" alt="결제 아이콘"></i>리뉴얼하기
                </a>
            </li>
            <li class="join_step3 wow fadeInUp">
                <img src="img/vmp_icon_1.png" alt="회원가입권 아이콘" class="wow flip">
                <h2>VM 간편 회원가입 시킨 회원 리스트</h2>
                <a href="/myOffice/simpleJoin/member_list.php" class="btn payment_btn" target="_blank">
                    <i><img src="img/vmp_list.png" alt="리스트 아이콘"></i>리스트 보기
                </a>
                <div class="info_box clearfix">
                    <i><img src="img/vmp_info.png" alt="정보 아이콘"></i>
                    <p>회원가입권으로 VM 간편 회원가입 시킨 리스트를 볼 수 있습니다.</p>
                </div>
            </li>
            <li class="join_step2 wow fadeInUp">
                <img src="img/vmp_icon_2.png" alt="간편회원가입 아이콘" class="wow flip">
                <h2>VM 간편 회원가입(회원가입권 1개 소모)</h2>
                <p>회원가입은 여기서!</p>
            </li>
        </ul>
        <div class="join_form">
            <div class="form_container">
                <form id="form_submit">
                    <input type="hidden" name="mb_id" value="<?=$member["mb_id"]?>">
                <ul>
                    <li class="wow fadeInUp" data-wow-delay="0.2s">
                        <label for="user_name">이름</label>
                        <input type="text" name="user_name" id="user_name" tabindex="1">
                    </li>
                    <li class="wow fadeInUp" data-wow-delay="0.3s">
                        <label for="user_p_number">핸드폰 번호</label>
                        <input type="text" name="user_p_number" id="user_p_number" onkeyup="onlyNumber(this)" tabindex="2">
                    </li>
                    <li class="wow fadeInUp recomm_name_box" data-wow-delay="0.4s"> 
                        <label for="recommender_name">추천인 이름</label>
                        <input type="text" name="recommender_name" id="recommender_name" placeholder="확인 후 자동으로 입력됩니다" readonly>
                    </li>
                    <li class="wow fadeInUp" data-wow-delay="0.5s">
                        <label for="recommender_number">추천인 회원번호</label>
                        <input type="text" name="recommender_number" id="recommender_number" onkeyup="onlyNumber(this)" tabindex="3">
                        <a href="#none" class="number_check_btn" onclick="recommCheck($('#recommender_number').val())" tabindex="4">확인</a>
                    </li>
                    <li class="wow fadeInUp sponsor_name_box" data-wow-delay="0.6s">
                        <label for="sponsor_name">후원인 이름</label>
                        <input type="text" name="sponsor_name" id="sponsor_name" placeholder="확인 후 자동으로 입력됩니다" readonly>
                    </li>
                    <li class="wow fadeInUp" data-wow-delay="0.7s">
                        <label for="sponsor_number">후원인 회원번호</label>
                        <input type="text" name="sponsor_number" id="sponsor_number" onkeyup="onlyNumber(this)" tabindex="5">
                    </li>
                    <li class="wow fadeInUp" data-wow-delay="0.8s">
                        <label for="sponsor_team">후원인 팀배치</label>
                        <input type="radio" name="sponsor_team" id="1team" value="1">
                        <label for="1team">1팀</label>
                        <input type="radio" name="sponsor_team" id="2team" value="2">
                        <label for="2team">2팀</label>
                        <a href="#none" class="number_check_btn" onclick="teamCheckFunc($('#sponsor_number').val())" tabindex="6">확인</a>
                    </li>
                </ul>
                </form>
            </div>
            <div class="info_box clearfix">
                <i><img src="img/vmp_info.png" alt="정보 아이콘"></i>
                <p>모든 정보들을 정확하게 입력해주세요.</p>
                <i><img src="img/vmp_info.png" alt="정보 아이콘"></i>
                <p>가입 시킨 아이디는 한 달 이내에 1회 수정이 가능합니다.</p>
            </div>
            <a href="#none" class="btn submit_btn" onclick="submitCheck()">회원가입 완료</a>
        </div>
        <div class="pay_join">
            
            <from method="POST" action="">
            <div class="pay_container">
                <h3>회원가입권 결제하기</h3>
                <ul>
                    <li>
                        <label for="pay_count">구매 수량</label>
                        <select name="pay_count" id="pay_count" onchange="pay_count_sel_func()">
                            <option value="13">13</option>
                            <option value="12">12</option>
                            <option value="7">7</option>
                            <option value="6">6</option>
                        </select>
                        <!--<input type="number" name="pay_count" id="pay_count" value="12" readonly="true" min="12" max="12" pattern="[0-9]*" onkeyup='payCheck(this)' onchange='payCheck(this)'>--> 
                    </li>
                    <li class="vmc_point">
                        <label for="VMCpoint">VMC</label>
                        <input type='text' name='VMCpoint' id='VMCpoint' onkeyup="payCheck(this)" value="0" onfocus="this.select()"/><label>원</label>
                    </li>
                    <li class="my_vmc_point">
                        <strong>보유 VMC :</strong>
                        <em><b class="my_point" id="my_point_vmc"><?php echo number_format($g5_memberRow["VMC"]); ?></b>원</em>
                    </li>
                    <li class="vmp_point">
                        <label for="VMPpoint">VMP</label>
                        <input type='text' name='VMPpoint' id='VMPpoint' onkeyup="payCheck(this)" value="0" onfocus="this.select()"/><label>원</label>
                    </li>
                    <li class="my_vmp_point">
                        <strong>보유 VMP :</strong>
                        <em><b class="my_point" id="my_point_vmp"><?php echo number_format($g5_memberRow["VMP"]); ?></b>원</em>
                    </li>
                    <li class="money_box">
                        <p>현재 입력금액 : <b id="totalvpoint">0</b>원</p>
                    </li>
                    <li>
                        <p>결제 금액 : <b id="total_money">2,080,000</b>원</p>
                    </li>             
                </ul>
                <div class="pay_load_msg">
                    <img src="img/vmp_loading_icon.gif" alt="결제중입니다."/>
                    <p>결제중 입니다.</br>잠시만 기다려주세요.</p>
                </div>
                <a href="#none" class="pay_btn" onclick="payNow()">결제하기</a>
                <a href="#none" class="cancel_btn" onclick="cancelSubmit(3)">취소</a>
            </div>
            </from>
        </div>
        
        <!--리뉴얼 팝업-->
        <div class="renewal_pop">
            <div class="renewal_wrap">
                <div class="search_box">
                    <label for="renewal_id">리뉴얼 시킬 아이디</label>
                    <input type="text" id="renewal_id" name="renewal_id" value="" placeholder="ex) 00003571 " minlength="8" maxlength="8" onkeyup="onlyIdNumber(this)" >
                    <a href="#none" class="search_btn" onclick="renewalCheckId()">확인</a>
                    <input type="text" id="renewal_name" value="" readonly>
                </div>
                <div class="renewal_btn_box">
                    <a href="#none" class="btn confirm_btn" onclick="">
                        <i><img src="img/gear.png" alt="결제 아이콘"></i>리뉴얼하기
                    </a>
                    <a href="#none" class="close_btn">닫기</a>
                </div>
            </div>
        </div>
        
        
        <!--알림 메시지-->
        <div class="check_msg">
            <div class="msg_box error_msg">
                <span>
                    <i><img src="img/vmp_close.png" alt="아이콘"></i>
                </span>
                <p>에러 메시지입니다.</p>
                <a href="#none" class="msg_btn" onclick="alertMsg('error')">확인</a>
            </div>
            <div class="msg_box error_pay_msg">
                <span>
                    <i><img src="img/vmp_close.png" alt="아이콘"></i>
                </span>
                <p>에러 메시지입니다.</p>
                <a href="#none" class="msg_btn" onclick="alertMsg('error_pay')">확인</a>
            </div>
            <div class="msg_box complete_msg">
                <span>
                    <i><img src="img/vmp_check.png" alt="아이콘"></i>
                </span>
                <p>VM 간편 회원가입이 </br>완료되었습니다.</br></br></br>(정보 수정은 한 달 이내 1회 수정 가능합니다.)</p>
                <a href="#none" class="msg_btn" onclick="alertMsg('complete')">확인</a>
            </div>
            <div class="msg_box complete_pay_msg">
                <span>
                    <i><img src="img/vmp_check.png" alt="아이콘"></i>
                </span>
                <p>구매가 완료되었습니다.</p>
                <a href="#none" class="msg_btn" onclick="alertMsg('complete_pay')">확인</a>
            </div>
            <div class="loading_msg">
                <img src="img/vmp_loading_icon_4.gif" alt="로딩메세지"/>
                <p>데이터를 전송 중입니다 잠시만 기다려 주세요</p>
            </div>
        </div>
        <?php
        include ('../../shop/shop.tail.php'); // gnb 디자인, copyright 마크업 들어있는 소스
        ?>
</body>
<script>
    
// 애니메이션 효과 
new WOW().init();
    
// 숫자만 입력가능하게 처리하는 부분 
function onlyNumber(obj){
    $(obj).keyup(function (event) {
        regexp = /[^0-9]/gi;
        value = $(this).val();
        if (regexp.test(value)) {
            $('.check_msg').addClass('error');
            $('.error_msg p').text('숫자만 입력가능 합니다.\n-(하이픈)을 제외한 숫자만 입력하여 주세요.');
            $(this).val(value.replace(regexp, ''));
            $('.msg_btn').focus();
        }
    });
}


// 콤마 만들어주는 식
function comma(str) {
    str = String(str);
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}


// 콤마 제거하기 
function removeComma(str){
	n = parseInt(str.replace(/,/g,""));
        return n;
}


//회원가입권 결제 팝업창 띄우기
function payJoin(){
    $(".pay_join").css("display","block");
}




// 회원가입권 결제하기
function payCheck(obj){

    // 숫자만 입력하게 합니다.
    $("#VMCpoint").val($("#VMCpoint").val().replace(/[^0-9]/g,""));
    $("#VMPpoint").val($("#VMPpoint").val().replace(/[^0-9]/g,""));
    
    
    if( $(obj).val().charAt(0) == 0 ){
        $(obj).select();
        return
    }
    
    // 구매수량을 입력하면 결제금액이 자동계산됩니다.
    if( $('#pay_count') ){
        var totalCalc = $('#pay_count').val() * 2080000;
        var totalMoney = comma(totalCalc);
        $('#total_money').text(totalMoney);
    } 

    if( $("#VMCpoint").val() == '' ){
        $("#VMCpoint").val('0');

    } else if( $("#VMPpoint").val() == '' ){
        $("#VMPpoint").val('0');
    }

    var sumMoney = parseInt( removeComma($('#VMCpoint').val()) ) + parseInt( removeComma($('#VMPpoint').val()) );
    $('#totalvpoint').text( comma(sumMoney) );

    var currentMoney = parseInt( removeComma($('#totalvpoint').text()) );
    var paymentTotal = removeComma( $('#total_money').text() );

    if( currentMoney > paymentTotal ){
        $("#VMCpoint").val("0");
        $("#VMPpoint").val("0");
        $("#totalvpoint").text("0");
        $('.check_msg').addClass('error');
        $('.error_msg p').text('입력 금액이 결제 금액보다 큽니다.');
        $("#VMCpoint").focus();
    }
}

// 값지우기  
function removeValue(){
    
}





// 회원가입권 결제하기 버튼을 눌렀을 때 전체 값 검증 
function payNow() {
    var currentMoney = parseInt( removeComma($('#totalvpoint').text()) );
    var paymentTotal = removeComma( $('#total_money').text() );
    
    if( removeComma($("#my_point_vmc").text()) < removeComma($("#VMCpoint").val()) ) {
        $('.check_msg').addClass('error');
        $('.error_msg p').text('보유 VMC 이상으로 사용할 수 없습니다');
        return;
    }else if( removeComma($("#my_point_vmp").text()) < removeComma($("#VMPpoint").val()) ) {
        $('.check_msg').addClass('error');
        $('.error_msg p').text('보유 VMP 이상으로 사용할 수 없습니다');
        return;
    }
    
    
        if( currentMoney > paymentTotal ){
            $('.check_msg').addClass('error');
            $('.error_msg p').text('입력 금액이 결제 금액보다 큽니다.다시 입력해주세요');
            
        } else if ( currentMoney < paymentTotal ){
            $('.check_msg').addClass('error');
            $('.error_msg p').text('입력 금액이 결제 금액보다 적습니다.다시 입력해주세요');
            
        } else if( currentMoney == paymentTotal ){
            
            if( currentMoney == 0 && paymentTotal == 0 ){
                $('.check_msg').addClass('error');
                $('.error_msg p').text('수량과 금액을 입력해주세요');

              } else {
                  $('.pay_btn').css('display','none');
                  $('.cancel_btn').css('display','none');
                  $('.pay_load_msg').css('display','block');
                  
                    var data = "pay_count=" + $('#pay_count').val() + "&"
                    + "VMCpoint=" + $('#VMCpoint').val() + "&"
                    + "VMPpoint=" + $('#VMPpoint').val();
            
                    setTimeout(function(){
                        $.ajax({
                            url:'buyMembership.php',
                            type:'POST',
                            data: encodeURI(data),
                            async: false,
                            success:function(result){
                                if( result != "true" ) {
                                    $('.check_msg').addClass('error_pay'); // 디자인된 에러 alert창 띄우기
                                    $('.error_pay_msg p').text(result);
                                    
                                } else {
                                    $('.check_msg').addClass('complete_pay'); // 디자인된 성공 alert창 띄우기
                                }
                           }
                        });
                     }, 100);
                     
              }
        } 
}

       
//결제취소클릭시 팝업창 닫기 
function cancelSubmit(num){
    
    $(".pay_join").css("display","none");
    $("#VMCpoint").val('0');
    $("#VMPpoint").val('0');
    $("#totalvpoint").text('');
}    
  

// 에러,확인 메세지 끄기
function alertMsg(msg){
    $('.check_msg').removeClass(msg);
    if( msg == 'joinComplete'){ // 회원가입 성공시
        location.reload();
        
    } else if( msg == 'complete'){ // 기본 성공메세지
        $('.complete_msg p').html('VM 간편 회원가입이 </br>완료되었습니다.</br></br></br>(정보 수정은 한 달 이내 1회 수정 가능합니다.)');

    } else if( msg == 'complete_pay'){ // 회원가입권 구매 성공시
        location.reload();
        
    } else if ( msg == 'error_pay'){ // 회원가입권 구매 실패시
        location.reload();

    } else if ( msg == 'error' ){ // 기본 에러메세지 
        $('.error_msg p').text('에러 메시지입니다.');
    }
}


let recommName = null;
let recommNum = null;

let sponsorName = null;
let sponsorNum = null;
let sponsorTeamNum = null;

// 추천인 회원번호 값검증 
function recommCheck(obj){
  
    if($('#recommender_number').val() == ''){
            $('.check_msg').addClass('error');
            $('.error_msg p').text('추천인 회원번호를 입력해주세요.');
            $('.msg_btn').focus();
    } else {
        $('.check_msg').addClass('load');
        $('.loading_msg p').text('조회중입니다.잠시만 기다려주세요');

        var data = "mb_id=" + $('#recommender_number').val();

        $.ajax({
           url:'/myOffice/memberIDSearch2.php',
           type:'POST',
           data: encodeURI(data),
           async: false,
           success:function(result){
                alertMsg('load');

               if( result == 'false'){
                    $('.check_msg').addClass('error');
                    $('#recommender_name').val('');
                    $('.error_msg p').text('회원정보가 없습니다. 다시 입력해주세요.');
                    $('.msg_btn').focus();
               } else {
                   var data = "reg_mb_recommender=" + result + "&"
                    + "reg_mb_recommender_no=" + $('#recommender_number').val();
                    var temp = result;
                     $.ajax({
                         url:'/bbs/register_value_checking.php',
                         type:'get',
                         data: encodeURI(data),
                         async: false,
                         success:function(result){
                             if( result == "false" ) {
                                $('.check_msg').addClass('error');
                                $('#recommender_name').val('');
                                $('.error_msg p').text('VM이 아닙니다. 다시 입력해주세요.');
                                $('.msg_btn').focus();
                             } else {
                                recommName = temp;
                                recommNum = $('#recommender_number').val(); 
                                $('#recommender_name').val(temp);
                             }
                            }
                    });
                   
                   
               }
           }
       });
   }
}



// 후원인 팀배치 체크
function teamCheckFunc(){
    if($('#sponsor_number').val() == ''){
        $('.check_msg').addClass('error');
        $('.error_msg p').text('후원인 회원번호를 입력해주세요');
        $('.msg_btn').focus();
        
    } else if ($('input:radio[name="sponsor_team"]:checked').val() == undefined){
        $('.check_msg').addClass('error');
        $('.error_msg p').text('팀을 선택해주세요');
        $('.msg_btn').focus();
        
    } else {
        $('.check_msg').addClass('load');
        $('.loading_msg p').text('조회중입니다.잠시만 기다려주세요');

//        var data = "reg_mb_sponsor_no=" + $('#sponsor_number').val() + "&"
//             + "reg_mb_sponser_team=" + $("input:radio[name=sponsor_team]:checked").val();
//    
//        $.ajax({
//                url:'/bbs/register_sponsorTeam_checking.php',
//                type:'get',
//                data: data,
//                success:function(result){
//                    $('.check_msg').removeClass('load');
//
//                    if( result == "false" ) {
//                         $('.check_msg').addClass('error');
//                         $('.error_msg p').text('선택하신 후원인 팀으로 배치될 수 없습니다.팀 선택을 다시 해 주시기 바랍니다.');
//                         $('.msg_btn').focus();
//                         $('#sponsor_name').val('');
//                         $('#sponsor_number').val('');
//                         $("input:radio[name='sponsor_team']:radio[value='1']").prop("checked", false);
//                         $("input:radio[name='sponsor_team']:radio[value='2']").prop("checked", false);
//
//                    } else {
//                            sponsorTeamNum = $("input:radio[name='sponsor_team']:checked").val();
//                            sponsorCheck();
//                    }
//                }
//        });
        
        sponsorTeamNum = $("input:radio[name='sponsor_team']:checked").val();
        sponsorCheck();
    }
    
    // 후원인 회원번호 값검증
    function sponsorCheck(obj){
        $('.check_msg').addClass('load');
        $('.loading_msg p').text('조회중입니다.잠시만 기다려주세요');

        var data = "reg_mb_sponsor_no=" + $('#sponsor_number').val() + "&"
                 + "reg_mb_sponser_team=" + $("input:radio[name=sponsor_team]:checked").val()
                 + "&nameCheck=true";

        $.ajax({
                url:'/bbs/register_sponsorTeam_checking.php',
                type:'get',
                data: data,
                success:function(result){
                    $('#sponsor_name').val(result);
                    
                    if( $('#sponsor_name').val() == 'false' ){
                         alertMsg('load');
                         $('.check_msg').addClass('error');
                         $('.error_msg p').text('회원정보가 없습니다. 다시 입력해주세요.');
                         $('.msg_btn').focus();
                         $('#sponsor_name').val('');
                         $('#sponsor_number').val('');
                         $("input:radio[name='sponsor_team']:radio[value='1']").prop("checked", false);
                         $("input:radio[name='sponsor_team']:radio[value='2']").prop("checked", false);
                         
                    } else {
                        alertMsg('load');
                        $('.check_msg').addClass('complete');
                        $('.complete_msg p').text('선택하신 후원인은 ' + result +' 님 입니다.');
                        $('.msg_btn').focus();
                        
                        $('#sponsor_name').val(result);      
                        sponsorName = result;
                        sponsorNum = $('#sponsor_number').val();
                    }
                }
            });
    }
    
}


// 리뉴얼 팝업 활성화
function renewal(){
   $('.renewal_pop').addClass('active');
}

// 리뉴얼 ID 숫자만 입력가능하게 처리하는 부분 
function onlyIdNumber(obj){

    $(obj).keyup(function (event) {
        regexp = /[^0-9]/gi;
        value = $(this).val();
        if (regexp.test(value)) {
            $(this).val(value.replace(regexp, ''));
        }
    });  
}




var renewalId = null;

// 리뉴얼 시킬 아이디 조회
function renewalCheckId(){
    
    renewalId = $('#renewal_id').val();
    
    var data = "mb_id=" + $('#renewal_id').val();

        $.ajax({
           url:'/myOffice/memberIDSearch2.php',
           type:'POST',
           data: encodeURI(data),
           async: false,
           success:function(result){
                alertMsg('load');

                if( result == 'false'){
                    $('.check_msg').addClass('error');
                    $('#renewal_name').val('');
                    $('#renewal_id').val('');
                    $('.error_msg p').text('회원정보가 없습니다. 다시 입력해주세요.');
                    $('.msg_btn').focus();

                } else {
                    var data = "reg_mb_recommender=" + result + "&" + "reg_mb_recommender_no=" + $('#renewal_id').val();
                    var temp = result;
                     $.ajax({
                         url:'/bbs/register_value_checking.php',
                         type:'get',
                         data: encodeURI(data),
                         async: false,
                         success:function(result){
                             if( result == "false" ) {
                                $('.check_msg').addClass('error');
                                $('#renewal_id').val('');
                                $('#renewal_name').val('');
                                $('.error_msg p').text('VM이 아닙니다. 다시 입력해주세요.');
                                $('.msg_btn').focus();

                             } else {
                                renewalId = $('#renewal_id').val();
                                $('#renewal_name').addClass('on');
                                $('#renewal_name').val(temp);
                            }
                        }
                    }); 
                }
            }
        });
}

//리뉴얼 enter key 눌렀을 때 검색되게하기
$('#renewal_id').keypress(function(event){
     if ( event.which == 13 ) {
         $('.search_btn').click();
         return false;
     }
});



// 리뉴얼 값검증 
$('.renewal_btn_box a').on('click', function(){

    //리뉴얼하기 버튼을 눌렀을 때 
    if( $(this).hasClass('confirm_btn') ){
        
        if($('#renewal_id').val() == '' || $('#renewal_name').val() == '' ){
            if ( $('#renewal_name').val() == '' || !($('#renewal_name').hasClass('on')) ){
                $('.check_msg').addClass('error');
                $('.error_msg p').text('리뉴얼 시킬 아이디를 입력 후 확인 버튼을 눌러주세요.');
                $('.msg_btn').focus();

            } else {
                $('.check_msg').addClass('error');
                $('.error_msg p').text('정보를 정확히 입력해주세요.');
                $('.msg_btn').focus();
            }
            
        } else if( renewalId !== $('#renewal_id').val()){
            $('.check_msg').addClass('error');
            $('.error_msg p').text('리뉴얼 시킬 아이디와 해당이름이 다릅니다. 다시 입력해주세요');
            $('.msg_btn').focus();
                 
        } else {
            $('.check_msg').addClass('load');
            $('.loading_msg p').text('처리중입니다.잠시만 기다려주세요');
            
            
            
            
            var data = "mb_id=" + $('#renewal_id').val();

            $.ajax({
                url:'simpleRenewal.php',
                type:'POST',
                data: encodeURI(data),
                async: false,
                success:function(result){
                    var json = JSON.parse(result);  
                    
                    if( json[0] == "true" ) {
                        $('.check_msg').removeClass('load');
                        $('.check_msg').addClass('complete');
                        $('.complete_msg p').text('리뉴얼이 완료되었습니다.');
                        $('#renewal_id').val('') ;
                        $('#renewal_name').val('');
                        $('#renewal_name').removeClass('on');
                        $('.renewal_pop').removeClass('active');
                        $("#membershipEA").text(json[1] + "개");
                    } else {
                        $('.check_msg').removeClass('load');
                        $('.check_msg').addClass('error');
                        $('.error_msg p').text(json[0]);
                    }
               }
            });

            
        }
       
    //닫기 버튼을 눌렀을 때   
    } else {
        $('#renewal_id').val('') ;
        $('#renewal_name').val('');
        $('#renewal_name').removeClass('on');
        $('.renewal_pop').removeClass('active');
    }
});




// 회원가입 완료버튼을 눌렀을 때 값검증
function submitCheck(){
    
    if($('#user_name').val() == ''){
        $('#user_name').focus();
        $("#user_name").attr('placeholder', "필수 값입니다.");
        $("#user_name").css('outline-color', "red");
    } else if($('#user_p_number').val() == ''){
        $('#user_p_number').focus();
        $("#user_p_number").attr('placeholder', "필수 값입니다.");
        $("#user_p_number").css('outline-color', "red");
    } else if($('#recommender_number').val() == ''){ 
        $('#recommender_number').focus();
        $("#recommender_number").attr('placeholder', "필수 값입니다.");
        $("#recommender_number").css('outline-color', "red");
    } else if($('#recommender_name').val() == ''){
        $('.check_msg').addClass('error');
        $('.error_msg p').text('추천인 회원번호 옆 확인버튼을 눌러주세요.');
        $('.msg_btn').focus();
        
    } else if($('#sponsor_number').val() == ''){
        $('#sponsor_number').focus();
        $("#sponsor_number").attr('placeholder', "필수 값입니다.");
        $("#sponsor_number").css('outline-color', "red");
    } else if($('#sponsor_name').val() == ''){
        $('.check_msg').addClass('error');
        $('.error_msg p').text('후원인 팀배치 옆 확인버튼을 눌러주세요.');
        $('.msg_btn').focus();
        
    } else if($("input:radio[name=sponsor_team]:checked") == false){
        $('input:radio[name=sponsor_team]').focus();
        
    } else if( ! recommName == $('#recommender_name').val() || ! recommNum == $('#recommender_number').val() ) {
        $("#recommender_name").val("");
        $("#recommender_number").val("");
        $('.check_msg').addClass('error');
        $('.error_msg p').text('추천인 정보를 다시 확인해주세요');
    } else if( !(sponsorName == $('#sponsor_name').val() && sponsorNum == $('#sponsor_number').val() && sponsorTeamNum == $("input:radio[name=sponsor_team]:checked").val()) ){
        $("#sponsor_name").val("");
        $("#sponsor_number").val("");
        $("input:radio[name='sponsor_team']:radio[value='1']").prop("checked", false);
        $("input:radio[name='sponsor_team']:radio[value='2']").prop("checked", false);
        $('.check_msg').addClass('error');
        $('.error_msg p').text('후원인 정보를 다시 확인해주세요');
    } else {
        var data = "recommender_number=" + $('#recommender_number').val() + "&"
        + "sponsor_number=" + $('#sponsor_number').val();
        var returnTemp = true;
         $.ajax({
             url:'/myOffice/recommenderCheck2.php',
             type:'POST',
             data: encodeURI(data),
             async: false,
             success:function(result){
                 if( result == "false" ) {
                     $('.check_msg').addClass('error');
                     $('.error_msg p').text('본인의 추천인 산하로만 배치될 수 있습니다.');
                     returnTemp = false;
                 }
            }
         });
         if(!returnTemp)
            return false;
        
        
        
        
        
        
        $('.check_msg').addClass('load');
        $('.loading_msg p').text('처리 중입니다.잠시만 기다려주세요');
        
        var formValue = $("#form_submit").serialize();
        
        $.ajax({
            url:'memberJoin.php',
            type:'POST',
            data: encodeURI(formValue),
            async: false, // 동기 통신
            success:function(result){
                $('.check_msg').removeClass('load');
                if( result == "true" ) {
                    $('.complete_msg p').html('VM 간편 회원가입이 </br>완료되었습니다.</br></br></br>(정보 수정은 한 달 이내 1회 수정 가능합니다.)');
                    $('.check_msg').addClass('complete');
                    $(".msg_btn").removeAttr("onclick");
                    $(".msg_btn").attr("onclick", "alertMsg('joinComplete')")
                } else {
                    $('.check_msg').addClass('error');
                    $('.error_msg p').text(result);
                }
           }
        });
        
        
    }

     
     
     
}


function pay_count_sel_func() {
    $("#total_money").text( comma(parseInt($("#pay_count option:selected").val())*2080000) );
}
</script>