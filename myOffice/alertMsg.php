
<!--알림 메시지--> 

<style>   

.alert_box.active {display: block}
.alert_box {display: none; padding-top: 20%; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); text-align: center}

.alert_box .alert_msg {display: none; margin: 0 auto; width: 530px; background: #72ba71}
.alert_box .alert_msg > img {margin: 9.5% 0 9%; width: 19.8%;}
.alert_box .alert_msg .confirm_box {padding-bottom: 5.4%; background: #fff}
.alert_box .alert_msg .confirm_box p {padding: 8.5% 0 7%; font-size: 0.9rem; font-weight: bold; color: #4c4861}
.alert_box .alert_msg .confirm_box .alert_btn {display: block; margin: 0 auto; width: 108px; height: 39px; border-radius: 19.5px; background: #72ba71; font-size: 0.9rem; color: #fff; line-height: 39px}
.alert_box .alert_msg.success_msg.on {display: block;} 

.alert_box .alert_msg.error_msg.on {display: block;}
.alert_box .alert_msg.error_msg {background: #e57284} 
.alert_box .alert_msg.error_msg .alert_btn {padding-left: 2%; background: #e57284 url('./images/cancel_img.png')no-repeat 24% center; background-size: 9%}

.alert_box .alert_msg.confirm_msg.on {display: block;}
.alert_box .alert_msg.confirm_msg {background: #4bb149} 
.alert_box .alert_msg.confirm_msg .alert_btn.alert_confirm_btn {display: inline-block; padding-left: 2%; background: #4bb149}
.alert_box .alert_msg.confirm_msg .alert_btn.alert_confirm_btn + alert_confirm_btn {background: #fff; color: #4bb149}


@media (max-width: 780px){
.alert_box {padding-top: 35%;}   
.alert_box .alert_msg {width: 335px;}
}
@media (max-width: 480px){
.alert_box {padding-top: 39%;}   
.alert_box .alert_msg {width: 80%;}  
.alert_box .alert_msg.error_msg .alert_btn {width: 30%}
.alert_box .alert_msg.confirm_msg .alert_btn {width: 30%}

}



</style>

<!--알림 메시지 시작 -->
<div class="alert_box">
    <div class="alert_msg error_msg">
        <img src="./images/delete-button.png" alt="오류 아이콘"/>
        <div class="confirm_box">
            <p>오류메시지입니다.</p>
            <a href="#none" class="alert_btn">닫기</a>
        </div>
    </div>
    <div class="alert_msg success_msg">
        <img src="./images/checked.png" alt="성공 아이콘"/>
        <div class="confirm_box">
            <p>결제가 완료 되었습니다.</p>
            <a href="#none" id="success_aTAG" class="alert_btn">확인</a>
        </div>
    </div>
    <!-- 컨펌 메시지  -->
    <div class="alert_msg confirm_msg">
        <img src="./images/img_alert_confirm.png" alt="확인 아이콘"/>
        <div class="confirm_box">
            <p>확읺</p>
            <a href="#none" class="alert_btn alert_confirm_btn">확인</a>
            <a href="#none" class="alert_btn alert_confirm_btn">취소</a>
        </div>
    </div>
    
</div>
<!--알림 메시지 끝-->

<script>

// 알림메시지 띄우기 
// 'success'는 성공메시지 
// 'error'는 에러메시지
// 멘트 변경을 원할경우 msg값을 같이 넘겨주시면 됩니다.

function alertMsg(str,msg){
    $('.alert_box').addClass('active');
    
    if( str == 'success'){
        $('.success_msg').addClass('on');
        $('.alert_btn').focus();
        
        if(msg == ''){ //멘트 변경하기 
            $('.success_msg p').text('결제가 완료 되었습니다.');
        } else {
            $('.success_msg p').text(msg);
        }
        
    } else if(str == 'error'){
        $('.error_msg').addClass('on');
        $('.alert_btn').focus();
        
        if(msg == ''){ //메세지 변경하기 
            $('.error_msg p').text('오류메시지입니다');
        } else {
            $('.error_msg p').text(msg);
        }
    }

}

// 알림 메시지 끄기
$('.alert_btn').on('click', function(){
    $('.alert_box').removeClass('active');
    
    if( $('.alert_msg').hasClass('on') ){
        $('.alert_msg').removeClass('on');
    } 
    
    $('.error_msg p').text('오류메시지입니다');
    $('.success_msg p').text('결제가 완료 되었습니다.');
});

 
</script>