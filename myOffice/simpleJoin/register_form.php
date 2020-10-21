<?php
    $cssCheck = false; // _common.php에 인클루드 되는 CSS 적용 안 되게 하기 위한 변수
    include_once ('./_common.php');
    include_once ('../dbConn.php');

    if( $_GET["mb_id"] == "" ) {
        echo "<script>alert('필수 파라미터 오류입니다.'); close();</script>";
        exit();
    }

    
    
    
    $trueRow = mysql_fetch_array(mysql_query("select * from membershipListTBL as a inner join g5_member as b on a.mb_id = b.mb_id inner join genealogy as c on b.mb_id = c.mb_id where a.mb_id = '{$_GET["mb_id"]}' and a.constructor_id = '{$member["mb_id"]}' and a.modi_datetime is null and date_add(a.datetime, interval +4 month) >= now()"));
    
    if( $trueRow["mb_id"] == "" ) { // 내가 만든 계정이 아니거나, 수정할 수 있는 기간이 아니거나, 간편회원가입권으로 만들어진 계정이 아니라면 참 뜨게 됨
        echo "<script>alert('수정할 수 없는 계정입니다.'); close();</script>";
        exit();
    }
    
    
?>



<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="/img/vmp_logo.ico" />
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/member_list.css"/>
    <script src="../js/jquery.min.js"></script>  
    <script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
    <script charset="UTF-8" type="text/javascript" src="http://t1.daumcdn.net/postcode/api/core/190611/1560237575423/190611.js"></script>
    <title>회원정보 수정</title>
</head>
<body>

<!--action="http://gvmp.company/bbs/register_form_update.php"-->
<form name="fregisterform" id="fregisterform" action="register_formBack.php" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" id="mb_id" name="mb_id" value="<?=$_GET["mb_id"]?>">
    <div class="form_01">
        <h2 class="title_txt">회원정보 수정</h2>
        <h2 style="background:#2399f1;">개인정보</h2>
        <li class="rgs_name_li">
            <label for="reg_mb_name" class="sound_only">이름<strong>필수</strong></label>
            <input type="text" id="reg_mb_name" name="mb_name" value="<?=$trueRow["mb_name"]?>" class="frm_input full_input  readonly" placeholder="이름은 필수 입력 사항입니다" required>
        </li>
        <li class="reg_hp_box">
            <input type="hidden" id="reg_hp_smsnumber" name="reg_hp_smsnumber" value=""/>
            <input type="hidden" id="confirmedNum" name="confirmedNum" value=""/>
            <p class="reg_hp_info">인증문자 발송 버튼을 누르세요. 4자리 인증번호 문자를 보내드리겠습니다. </p>
            <label for="reg_mb_hp" class="sound_only">휴대폰번호<strong>필수</strong></label>
            <input onkeydown="onlyNumber(this)" type="text" name="mb_hp" value="<?php echo preg_replace("/[^0-9]*/s", "", $trueRow["mb_hp"]); ?>" id="reg_mb_hp" class="frm_input full_input required " minlength="11" maxlength="11" title="휴대전화 번호" placeholder="휴대전화 번호를 숫자만 입력" required>
            <a href="#none" class="reg_hp_btn reg_hp_reconf_btn" onclick='reconfirmHp()'>번호 변경하기</a>
            <a href="#none" class="reg_hp_btn reg_hp_confirm_btn">인증문자 발송</a>
            <em class="reg_hp_confirm_msg">필수! 문자인증을 해주시기 바랍니다.</em>
            <div class="reg_hp_confirm_box"></div>
            <input type="hidden" name="old_mb_hp" value="">
        </li>
        <li>
            <label for="reg_mb_email" class="sound_only">E-mail<strong>필수</strong></label>
            <span class="frm_info">
            </span>
            <input type="hidden" name="old_email" value="">
            <input type="email" name="mb_email" value="" id="reg_mb_email" required class="frm_input email required" size="50" maxlength="100" placeholder="E-mail"  required>
        </li>
        <li>
            <label for="reg_mb_tel" class="sound_only">생년월일<strong>필수</strong></label>
            <input onkeydown="onlyNumber(this)" maxlength="6" type="text" name="mb_birth" value="" id="reg_mb_tel" class="frm_input full_input required" maxlength="20" placeholder="생년월일 (ex. 890803)" required>
        </li>
        <li>
            <span class="frm_label" style="background:#2399f1;">주소<strong class="sound_only">필수</strong></span>
            <label for="reg_mb_zip" class="sound_only">우편번호<strong class="sound_only"> 필수</strong>':''; </label>
            <input type="text" name="mb_zip" value=""" id="reg_mb_zip" class="frm_input required" size="5" maxlength="6" placeholder="우편번호" required>
            <button type="button" class="btn_frmline btn" onclick="win_zip('fregisterform', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소 검색</button><br>
            <div id="wrap" style="display:none;border:1px solid;width:500px;height:300px;margin:5px 0;position:relative">
                <img src="//t1.daumcdn.net/postcode/resource/images/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" onclick="foldDaumPostcode()" alt="접기 버튼">
            </div>
            <label for="reg_mb_addr1" class="sound_only">주소<strong class="sound_only"> 필수</strong>':'';</label>
            <input type="text" name="mb_addr1" value="" id="reg_mb_addr1" class="frm_input frm_address required" required size="50" placeholder="주소" required><br>
            <label for="reg_mb_addr2" class="sound_only">상세주소</label>
            <input type="text" name="mb_addr2" value="" id="reg_mb_addr2" class="frm_input frm_address required" size="50" placeholder="상세주소" required>
            <br>
            <input type="hidden" name="mb_addr3" value="" id="reg_mb_addr3" class="frm_input frm_address" size="50" readonly="readonly" placeholder="참고항목">
            <input type="hidden" name="mb_addr_jibeon" value="">
<script>
// 우편번호 찾기 찾기 화면을 넣을 element
var element_wrap = document.getElementById('wrap');

function foldDaumPostcode() {
// iframe을 넣은 element를 안보이게 한다.
element_wrap.style.display = 'none';
}

function win_zip() {
// 현재 scroll 위치를 저장해놓는다.
var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
new daum.Postcode({
    oncomplete: function(data) {
        // 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

        // 각 주소의 노출 규칙에 따라 주소를 조합한다.
        // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
        var addr = ''; // 주소 변수
        var extraAddr = ''; // 참고항목 변수

        //사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
        if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
            addr = data.roadAddress;
        } else { // 사용자가 지번 주소를 선택했을 경우(J)
            addr = data.jibunAddress;
        }

        // 사용자가 선택한 주소가 도로명 타입일때 참고항목을 조합한다.
        if(data.userSelectedType === 'R'){
            // 법정동명이 있을 경우 추가한다. (법정리는 제외)
            // 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
            if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
                extraAddr += data.bname;
            }
            // 건물명이 있고, 공동주택일 경우 추가한다.
            if(data.buildingName !== '' && data.apartment === 'Y'){
                extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
            }
            // 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
            if(extraAddr !== ''){
                extraAddr = ' (' + extraAddr + ')';
            }
            // 조합된 참고항목을 해당 필드에 넣는다.
            document.getElementById("reg_mb_addr3").value = extraAddr;
            
            } else {
                document.getElementById("reg_mb_addr3").value = '';
            }

            // 우편번호와 주소 정보를 해당 필드에 넣는다.
            document.getElementById('reg_mb_zip').value = data.zonecode;
            document.getElementById("reg_mb_addr1").value = addr;
            // 커서를 상세주소 필드로 이동한다.
            document.getElementById("reg_mb_addr2").focus();

            // iframe을 넣은 element를 안보이게 한다.
            // (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
            element_wrap.style.display = 'none';

            // 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
            document.body.scrollTop = currentScroll;
        },
        // 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분. iframe을 넣은 element의 높이값을 조정한다.
        onresize : function(size) {
            element_wrap.style.height = size.height+'px';
        },
        width : '100%',
        height : '100%'
    }).embed(element_wrap);

    // iframe을 넣은 element를 보이게 한다.
    element_wrap.style.display = 'block';
}
</script>
        </li>
    </div>
    <div class="form_01">
        <h2 style="background:#1cb934;">사용자 계좌정보</h2>
        <li>
            <label for="bankName" class="sound_only">은행명<strong>필수</strong></label>
            <select name="bankName" id="bankName" class="frm_input full_input" reg_mb_addr3>
                <option value="00" selected="">입금할 은행을 선택하세요.</option>
                <option value="IBK기업은행">IBK기업은행</option>
                <option value="KB국민은행" >KB국민은행</option>
                <option value="수협중앙회">수협중앙회</option>
                <option value="NH농협은행">NH농협은행</option>
                <option value="우리은행">우리은행</option>
                <option value="KEB하나은행">KEB하나은행</option>
                <option value="신한은행">신한은행</option>
                <option value="KDB산업은행">KDB산업은행</option>
                <option value="SC제일은행">SC제일은행</option>
                <option value="대구은행">대구은행</option>
                <option value="광주은행">광주은행</option>
                <option value="부산은행">부산은행</option>
                <option value="제주은행">제주은행</option>
                <option value="전북은행">전북은행</option>
                <option value="경남은행">경남은행</option>
            </select>
        </li>
        <li>
            <label for="reg_mb_payment" class="sound_only">예금주<strong>필수</strong></label>
            <input type="text" name="mb_accountHolder" id="reg_mb_payment" class="frm_input full_input" minlength="2" maxlength="20" placeholder="예금주" required>
        </li>
        <li>
            <label for="reg_mb_account" class="sound_only">계좌번호<strong>필수</strong></label>
            <input type="text" name="mb_accountNumber" id="reg_mb_account" class="frm_input full_inpu" minlength="3" maxlength="20" placeholder="계좌번호 (숫자만 입력)" onkeydown="onlyNumber(this)" required>
        </li>
    </div>
    <div class="btn_top top">
        <input type="submit" value="수정하기" id="btn_submit" class="btn_submit" accesskey="s">
    </div>
    <div class="load_msg">
        <img src="img/vmp_loading_icon.gif" alt="수정중입니다.">
        <p>수정중 입니다.<br>잠시만 기다려주세요.</p>
    </div>
</form>
<script>
//숫자만 입력받기
function onlyNumber(obj) {
    $(obj).keyup(function(){
         $(this).val($(this).val().replace(/[^0-9]/g,""));
    }); 
}

//이메일 중복여부 확인하기
function emailConf() {
    check = "";
    var data = "email=" + $('#reg_mb_email').val();
    $.ajax({
         url:'../emailConf.php',
         type:'get',
         data: data,
         async : false,
         success:function(result){
             if( result == 1 ) {
                 alert("입력하신 이메일이 이미 사용 중입니다..");
                 $("#reg_mb_email").val("");
                 $("#reg_mb_email").focus();
                 check = "false";
                 return false;
             }
            return true;
        }
    });
}

// 휴대폰번호 문자인증을 안할경우 멘트띄우기
$('#reg_mb_hp').focusout(function(){
if( ! ($('#reg_mb_hp').val().length == 11)){
    return
} else {
    if( $('.reg_hp_confirm_btn').css('visibility') == 'visible' ){
            setTimeout(function(){
                $('.reg_hp_confirm_msg').css('visibility','visible');
            },300);
            
    } else {
        return
    }
}
});

// 인증번호 제한시간 타이머
var SetTime = 180;
function msg_time() {	// 1초씩 카운트
    mm = Math.floor(SetTime / 60) + "분 " + (SetTime % 60) + "초";// 남은 시간 계산

    var msg = "남은 시간 <font color='#f75e61'>" + mm + "</font>";

    document.all.ViewTimer.innerHTML = msg;// div 영역에 보여줌 

    SetTime--;	// 1초씩 감소

    if (SetTime < 0) {	// 시간이 종료 되었으면..

        clearInterval(tid); // 타이머 해제
        alert("입력 시간을 초과하였습니다.");
        window.location.reload();
    }
}



// 문자인증번호 눌렀을 때 실행하기
$(".reg_hp_confirm_btn").click(function(){    
    console.log('asdsad');
    if( ! ($('#reg_mb_hp').val().length == 11) ){
        alert('휴대폰 번호를 정확히 입력해 주세요.');
        return
    }

    // 인증번호 생성 후 서버에 인증번호 전송 요청
    var result = Math.floor(Math.random() * 9999) + 1;
    var length = 4;
    result=result+""
    var str=""

    for(var i=0;i<length-result.length;i++){
      str=str+"0";
    }
    str=str+result;
    $("#reg_hp_smsnumber").val(str);

    var data = "smsNumber=" + str + "&mb_id=" + "&mb_hp=" + $("#reg_mb_hp").val();
    $('#confirmedNum').val( $("#reg_mb_hp").val() );

    $.ajax({
        url:'../smsAuthentication.php',
        type:'POST',
        data: data
    });

    $(".reg_hp_confirm_btn").hide(function(){
        $('#reg_mb_hp').prop('readonly',true);
        $('.reg_hp_reconf_btn').css('display','inline');
        $(".reg_hp_confirm_box").append( "<p>휴대폰으로 전송된 인증번호를 입력하여 주세요</p>" );
        $(".reg_hp_confirm_box").append('<p id="ViewTimer"></p>');
        $(".reg_hp_confirm_box").append('인증번호 입력');
        $(".reg_hp_confirm_box").append('<input type="text" name="reg_hp_innumber" id="reg_hp_innumber" minlength="4" maxlength="4" autocomplete="off" title="인증번호 입력" value=""/>');
        $(".reg_hp_confirm_box").append('<p class="reg_hp_text" style="display:none">인증번호가 틀립니다.(4자리)</br>다시 입력해주세요</p>');
        checkHpinnumber();
    });
    tid=setInterval('msg_time()',1000);
});



// 문자인증번호 값검증 메세지 띄우기
function regConfirmMsg(str){
if( str == "success" ){
    $('.reg_hp_confirm_msg').text('문자인증 완료');
    $('.reg_hp_confirm_msg').css('color','#4285F4');
    $('#reg_hp_innumber').css('border','3px solid #4285F4');
} else if ( str == "fail" ){
    $('.reg_hp_confirm_msg').text('정확하게 입력하여 주세요.(4자리)');
    $('.reg_hp_confirm_msg').css('color','#e8104c');
} else if ( str == 'origin' ){
    $('.reg_hp_confirm_msg').text('필수! 문자인증을 해주시기 바랍니다.');
    $('.reg_hp_confirm_msg').css('color','#e8104c');
}
}



// 문자인증번호 값검증
function checkHpinnumber(){
$('#reg_hp_innumber').keyup(function(ev){
    var keyValue = this.value.trim();
    var onlyNumber = keyValue.replace(/[^0-9]/g, '');
    var tmp = '';
    
    if( onlyNumber.length < 4 ){
       this.value = onlyNumber;
       
       if( $('#reg_hp_innumber').val() !== $("#reg_hp_smsnumber").val() ){ // 인증번호가 틀렸을 때
            $(".reg_hp_text").css('display','block');
            regConfirmMsg('fail');
            return;
            
        } else {
            $(".reg_hp_text").css('display','none');
            $('#reg_mb_hp').prop('readonly',true);
            $('.reg_hp_reconf_btn').css('display','inline');
            regConfirmMsg('success');
        }
        
    } else if( onlyNumber.length == 4 ){
        this.value = onlyNumber.substr(0,4);
        
        if( $('#reg_hp_innumber').val() !== $("#reg_hp_smsnumber").val() ){ // 인증번호가 틀렸을 때
            $(".reg_hp_text").css('display','block');
            regConfirmMsg('fail');
            return;
            
        } else {
            $(".reg_hp_text").css('display','none');
            $('#reg_mb_hp').prop('readonly',true);
            $('.reg_hp_reconf_btn').css('display','inline');
            regConfirmMsg('success');
            $('.reg_hp_confirm_box > p').text('문자인증 완료');
            $('.reg_hp_confirm_box').html('');

            SetTime = 180;
            clearInterval(tid);
            
        }
    }
});
}

// 휴대전화번호가 readonly일경우 백스페이스 불가처리
$('#reg_mb_hp').keydown(function(ev){
if( ev.keyCode === 8 &&  $('#reg_mb_hp').prop('readonly') == true ){
    console.log($('#reg_mb_hp').prop('readonly'))
    console.log('백스페이스누름 ');
    return false;
}
});





// 휴대전화번호 문자 재인증하기
function reconfirmHp(){

$('.reg_hp_reconf_btn').css('display','none');
$('#reg_mb_hp').prop('readonly',false);

//문자인증 초기화
$('.reg_hp_confirm_btn').css('display','inline');
$('#reg_mb_hp').val('');
$('.reg_hp_confirm_box').html('');
regConfirmMsg('origin');

SetTime = 180;
clearInterval(tid);
return false;
}


// submit 최종 폼체크
function fregisterform_submit(obj){

    // 이름 검사
    if ($('#reg_mb_name').val() =='') {
        if ($('#reg_mb_name').val().length < 1) {
            alert('이름을 입력하십시오.');
            $('#reg_mb_name').focus();
            return false;
        }
    }




    if( $("#bankName option:selected").val() == "00" || $("#reg_mb_payment").val() == "" || $("#reg_mb_account").val() == "" ) {
        alert("은행명, 예금주명, 계좌번호를 입력 후 진행할 수 있습니다.");
        return false;
    }   

    if( !( $.trim($("#reg_mb_name").val()) ==  $.trim($("#reg_mb_payment").val()) )) {
        alert("이름과 예금주가 동일하지 않습니다.");
        $("#reg_mb_name").focus();
        $("#reg_mb_payment").focus();
        return false;
    }


    // 문자인증 여부체크 
    if( $('.reg_hp_confirm_btn').css('display') !== "none" ){        
        alert('문자인증을 해주시기 바랍니다.');
        $('#reg_mb_hp').focus();
        return false;

    } else if ( $('.reg_hp_confirm_btn').css('display') == "none" && $('#reg_mb_hp').val() !== $('#confirmedNum').val() ){
        alert('문자인증을 진행해 주세요');
        $('#reg_mb_hp').focus();

        //문자인증 초기화
        $('.reg_hp_confirm_btn').css('display','inline');
        $('.reg_hp_confirm_box').html('');
        regConfirmMsg('origin');

        SetTime = 180;
        clearInterval(tid);
        return false;

    } else if ( $('.reg_hp_confirm_msg').text() !== '문자인증 완료'){
        alert('인증번호가 틀렸습니다.');
        $('#reg_hp_innumber').focus();
        return false;
    }
    
    var data = "type=mb_hp&mb_id=" + $("#mb_id").val() + "&value=" + $("#reg_mb_hp").val();
    var falseCheck = true;
    $.ajax({
       url:'checkInfo.php',
       type:'POST',
       data: encodeURI(data),
       async: false,
       success:function(result){
           if( result == 'false'){
               alert("다른 회원과 연락처가 중복됩니다.");
               $('#reg_mb_hp').focus();
               falseCheck = false;
           } else {
               falseCheck = true;
           }
       }
    });

    if( ! falseCheck ) {
        return false;
    }
    
    var data = "type=mb_email&mb_id=" + $("#mb_id").val() + "&value=" + $("#reg_mb_email").val();
    var falseCheck = true;
    $.ajax({
       url:'checkInfo.php',
       type:'POST',
       data: encodeURI(data),
       async: false,
       success:function(result){
           if( result == 'false'){
               alert("다른 회원과 이메일이 중복됩니다.");
               $('#reg_mb_email').focus();
               falseCheck = false;
           } else {
               falseCheck = true;
           }
       }
    });

    if( ! falseCheck ) {
        return false;
    }


    var data = "type=mb_bank&mb_id=" + $("#mb_id").val() + "&value=" + $("#reg_mb_account").val();
    var falseCheck = true;
    $.ajax({
       url:'checkInfo.php',
       type:'POST',
       data: encodeURI(data),
       async: false,
       success:function(result){
           if( result == 'false'){
               alert("다른 회원과 계좌정보가 중복됩니다.");
               $('#reg_mb_account').focus();
               falseCheck = false;
           } else {
               falseCheck = true;
           }
       }
    });

    if( ! falseCheck ) {
        return false;
    }
    
    document.getElementById("btn_submit").disabled = "disabled";
    // 로딩메세지 띄우기
    $('#btn_submit').css('display','none');
    $('.load_msg').css('display','block');

    return true;
}
</script>
</body>
</html>