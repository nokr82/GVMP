
<!-- 알림 메시지 -->
<style>
a {text-decoration: none}
/* 체크 메세지  */
.alert_wrap {display: none; position: fixed; top: 0; right: 0; bottom: 0; left: 0; z-index: 1000; background-color: rgba(0,0,0,0.6)}
.alert_wrap.active {display: block; }

/* load 일때는 로딩메세지가, error 일때는 에러메세지가 보이고 complete일때는 확인메세지가 보입니다. */
.alert_msg.error {display: block}
.alert_msg.error .error_msg {display: block}

.alert_msg.success {display: block}
.alert_msg.success .success_msg {display: block}

.alert_msg.confirm {display: block}
.alert_msg.confirm .confirm_msg {display: block}

.alert_msg .msg_box {display: none; position: absolute; top: 50%; left: 50%; margin-top: -125px; margin-left: -150px; width: 300px; background-color: #fff; border-radius: 5px; text-align: center; font-family: 'NanumGothic'; font-size: 1rem; font-weight: 500;}
.alert_msg .msg_box span {position: absolute; top: -50px; left: 50%; margin-left: -50px; width: 100px; height: 100px}
.alert_msg .msg_box span i {display: block; margin: 0 auto; width: 80px; height: 80px; background-color: #f75e61; border: 4px solid #fff; border-radius: 50%; text-align: center;}
.alert_msg .msg_box span i img {margin-top: 19px; width: 33px; height: 33px}
.alert_msg .msg_box p {margin: 0 auto; padding: 23% 0 19% 0; width: 93%; line-height: 2; word-break: keep-all}
.alert_msg .msg_box a {display: block; margin: 0 auto 7px; width: 94%; border-radius: 5px; background-color: #f75e61; color: #fff; font-size: 1rem; line-height: 2rem;}

.alert_msg .success_msg span i {background-color: #49b1a9}
.alert_msg .success_msg a {background-color: #49b1a9}

.alert_msg .confirm_msg span i {background-color: #4bb149}
.alert_msg .confirm_msg span i img {margin-top: 15px; width: 40px; height: 40px;}
.alert_msg .confirm_msg a {background-color: #4bb149}
.alert_msg .confirm_msg a.msg_btn {display: inline-block; width: 44%}
.alert_msg .confirm_msg a.msg_btn.cancel_btn {border: 1px solid #4bb149; background: #fff; color: #4bb149}

@media (max-width: 480px){
.alert_msg .msg_box {top: 55%; margin-left: -125px; width: 250px;}
.alert_msg .msg_box span {top: -36px; margin-left: -40px; width: 80px; height: 80px;}
.alert_msg .msg_box span i {width: 70px; height: 70px;}
.alert_msg .msg_box span i img {margin-top: 15px}

.alert_msg .confirm_msg span i img {margin-top: 10px; width: 35px; height: 36px}

}



</style>

<!-- 알림 메시지 시작 -->
<div class="alert_wrap">
    <div class="alert_msg">
        <!-- 완료 메시지 -->
        <div class="msg_box success_msg">
            <span>
                <i><img src="/myOffice/images/img_check.png" alt="완료 메시지"></i>
            </span>
            <p>완료 되었습니다.</p>
            <a href="#none" id="successButton" class="msg_btn">확인</a>
        </div>
        <!-- 에러 메시지 -->
        <div class="msg_box error_msg">
            <span>
                <i><img src="/myOffice/images/img_close.png" alt="에러 메시지"/></i>
            </span>
            <p>에러 메시지입니다.</p>
            <a href="#none" id="errorButton" class="msg_btn">확인</a>
        </div>
        <!-- 컨펌 메시지  -->
        <div class="msg_box confirm_msg">
            <span>
                <i><img src="/myOffice/images/img_exclamation.png" alt="컨펌 메시지"></i>
            </span>
            <p>정보를 변경하시겠습니까?</p>
            <a href="#none" id="confirmButton1" class="msg_btn">확인</a>
            <a href="#none" id="confirmButton2" class="msg_btn cancel_btn">취소</a>
        </div>
    </div>
</div>
<!-- 알림 메시지 끝 -->

<script>

// 알림메시지 띄우기 
// 'success'는 성공메시지 
// 'error'는 에러메시지
// 'confirm'은 컨펌메시지
// str에 'success','error','confirm'으로 넘기면 해당 알림창이 활성화 됩니다.
// 멘트 변경을 원할경우 msg값을 같이 넘겨주시면 됩니다.
// 아래는 호출 예시입니다. 크...
//   msgAlert('success', '성공 야호 야호', function() {alertOff(); location.reload();} );
//   msgAlert('error', '실패 야호 야호', function() {alertOff();} );
//   msgAlert('confirm', '확인 야호 야호', function() {alertOff(); location.reload();}, function() {alertOff();} );

function msgAlert(str,msg,func1,func2){

    func2 = typeof func2 !== 'undefined' ? func2 : "" ;
    
    $('.alert_wrap').addClass('active');
    
    if(str == 'success'){
        $('.alert_msg').addClass('success');
        $('.alert_btn').focus();
        
        if(msg == ''){ //멘트 변경하기 
            $('.success_msg p').text('완료 되었습니다.');
        } else {
            $('.success_msg p').html(msg);
        }
        
        $("#successButton").off().on('click', func1);
        
    } else if (str == 'error'){
        $('.alert_msg').addClass('error');
        $('.alert_btn').focus();
        
        if(msg == ''){ //멘트 변경하기 
            $('.error_msg p').text('에러 메시지입니다.');
        } else {
            $('.error_msg p').html(msg);
        }
        
        $("#errorButton").off().on('click', func1);

        
    } else if (str == 'confirm'){
        $('.alert_msg').addClass('confirm');
        $('.alert_btn').focus();
        
        if(msg == ''){ //멘트 변경하기 
            $('.confirm_msg p').text('정보를 변경하시겠습니까?');
        } else {
            $('.confirm_msg p').html(msg);
        }
       
        $("#confirmButton1").off().on('click', func1);
        $("#confirmButton2").off().on('click', func2);

    }
}



// 모든 종류의 알림 메시지 끄기
function alertOff(){
    $('.alert_wrap').removeClass('active');
    $('.alert_msg').removeClass('confirm');
    $('.alert_msg').removeClass('success');
    $('.alert_msg').removeClass('error');


    $('.success_msg p').text('완료 되었습니다.');
    $('.error_msg p').text('오류메시지입니다');
    $('.confirm_msg p').text('정보를 변경하시겠습니까?');
}

 

</script>