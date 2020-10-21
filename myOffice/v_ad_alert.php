<!--V광고센터 알림서비스-->

<style>
/*.v_ad_alert_wrap.active {visibility: visible; opacity: 1; bottom: 15px; transition: all 2s ease-in-out}*/
.v_ad_alert_wrap ul li.on {visibility: visible; opacity: 1; transform: translateY(0);}

.v_ad_alert_wrap {position: fixed; bottom: 17px; right: 81px; z-index: 90000;/* transition: all 3s ease-in-out*/}  
.v_ad_alert_wrap ul li {visibility: hidden; opacity: 0; transform: translateY(50%); margin-top: 10px; box-shadow: 0 0 8px 0 rgba(0, 0, 0, 0.2); transition: all 0.5s ease-in-out}
.v_ad_alert_wrap .alert_url {display: block; padding: 15px; width: 250px; border-top: 2px solid #36b65d; background: #fff url('./images/bg_alarm.png')no-repeat right 0; background-size: contain}
.v_ad_alert_wrap .alert_url .tit {display: block; margin-bottom: 5px; font-size: 18px; font-weight: 500; letter-spacing: -0.9px; color: #000}
.v_ad_alert_wrap .alert_url .msg {display: block; font-size: 14px; letter-spacing: -0.7px; color: #979797}
.v_ad_alert_wrap .alert_url .msg_bold {text-decoration: underline; font-size: 14px; font-weight: 500; letter-spacing: -0.7px; color: #000}
.v_ad_alert_wrap .alert_url .msg_2 {font-size: 14px; letter-spacing: -0.7px; color: #979797}


@media (max-width : 800px) {
.v_ad_alert_wrap {bottom: 0; left:0; right: 0;}
.v_ad_alert_wrap .alert_url {padding: 3% 3% 5% ; width: 100%; border-top: 4px solid #36b65d;}
.v_ad_alert_wrap .alert_url .msg {display: inline;}
.v_ad_alert_wrap .alert_url .tit {font-size: 32px; letter-spacing: -1.6px}
.v_ad_alert_wrap .alert_url .msg {font-size: 28px; letter-spacing: -1.4px}
.v_ad_alert_wrap .alert_url .msg_bold {font-size: 28px; letter-spacing: -1.4px}
.v_ad_alert_wrap .alert_url .msg_2 {font-size: 28px; letter-spacing: -1.4px}

}

@media (max-width : 600px) {
.v_ad_alert_wrap .alert_url .tit {font-size: 24px; letter-spacing: -1.6px}
.v_ad_alert_wrap .alert_url .msg {font-size: 21px; letter-spacing: -1.4px}
.v_ad_alert_wrap .alert_url .msg_bold {font-size: 21px; letter-spacing: -1.4px}
.v_ad_alert_wrap .alert_url .msg_2 {font-size: 21px; letter-spacing: -1.4px}
}

@media (max-width : 400px) {
.v_ad_alert_wrap .alert_url {padding: 5%;}
.v_ad_alert_wrap .alert_url .tit {font-size: 16px; letter-spacing: -1.6px}
.v_ad_alert_wrap .alert_url .msg {display: block; font-size: 14px; letter-spacing: -1.4px}
.v_ad_alert_wrap .alert_url .msg_bold {font-size: 14px; letter-spacing: -1.4px}
.v_ad_alert_wrap .alert_url .msg_2 {font-size: 14px; letter-spacing: -1.4px}
}




</style>
<div class="v_ad_alert_wrap">
    <ul class="alert_list">
<!--        <li>
            <a href="#none" class="alert_url alert_box">
                <strong class="tit">ON/OFF 알림서비스</strong>
                <em class="msg">광고대행 서비스가</em>
                <em class="msg_bold">활성화(ON)</em>
                <em class="msg_2">되었습니다.</em>
            </a>
        </li>
        <li>
            <a href="#none" class="alert_url alert_box">
                <strong class="tit">ON/OFF 알림서비스</strong>
                <em class="msg">광고대행 서비스가</em>
                <em class="msg_bold">활성화(ON)</em>
                <em class="msg_2">되었습니다.</em>
            </a>
        </li>
        <li>
            <a href="#none" class="alert_url alert_box">
                <strong class="tit">ON/OFF 알림서비스</strong>
                <em class="msg">광고대행 서비스가</em>
                <em class="msg_bold">활성화(ON)</em>
                <em class="msg_2">되었습니다.</em>
            </a>
        </li>-->
    </ul>
</div>



<script>

// 알림메시지 띄우기 
// str에 'on'을 넘기면 해당 알림창이 활성화 됩니다.
// 
// tit로 메인메시지를 필수로 넘겨주세요.
// 
// 멘트 변경을 원할 경우 msg값을 같이 넘겨주시면 됩니다.
// msg는 기본메시지와 기본메시지+강조메시지를 함께 띄우는 2가지가 있습니다.
// msg는 기본메시지이며 msgBold는 강조메시지입니다.
// 강조메시지가 있을 경우 msg2에 남은 메시지를 같이 넘겨주시면 됩니다.
// 
// 링크이동이 필요한 경우 alerturl에 링크를 넘겨주시면 됩니다.
// 
// 처리함수가 필요한 경우 func로 넘겨주시면 됩니다.
// 
// 
// 
// 아래는 호출 예시입니다.
// alertMsg('on', '도움말 알림서비스', '도움말이 필요하시면', '여기', '를 눌러주세요', '/myOffice/index.php', function(){});
// alertMsg('on', '도움말 알림서비스', '도움말이 필요하시면', '여기', '를 눌러주세요', '', function(){alert('TEST');});


function alertMsg(str,tit,msg,msgBold,msg2,alerturl,func1){
    func1 = typeof func1 !== 'undefined' ? func1 : "" ;
    
    if( str == 'on' ){

        $('.alert_list').append('<li><a href="#none" class="alert_url alert_box"><strong class="tit">'+tit+'</strong><em class="msg">'+msg+'</em><em class="msg_bold">'+msgBold+'</em><em class="msg_2">'+msg2+'</em></a></li>');
        

        
        
        if( alerturl == '' ){
            $('.alert_url').attr('href','#none');
            $('.alert_url').click(func1);
        } else {
            $('.alert_url').attr('href',alerturl);
        }
        
        $('.alert_list li').addClass('on');
        setTimeout(function(){
            $('.alert_list').children().last('li').removeClass('on');

            setTimeout(function(){
                $('.alert_list').children('li').last('li').remove();
            },500);
        }, 5000);
        
    }  
}






</script>