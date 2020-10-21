<!--로그인 인증-->
<div class="mask on"></div>
<div class="wrap_certi_sms wrap_certi_sms1">
    <i class="btn_close"><span class="blind">닫기</span></i>
    <p class="txt">
        010 - 1234 - **** 로 전송되었습니다.
    </p>
    <div class="box_input">
        <div class="wrap_inp">
            <div class="box_inp">
                <input type="text" name="write_sms" class="write_sms" />
                <span class="rest_time" id="ViewTimer"></span>
            </div>
            <span class="btn_time_extend">시간 연장하기</span>
        </div>
        <span class="btn_sumit">인증하기</span>
    </div>
</div>
<div class="wrap_certi_sms wrap_certi_sms2" style="display:none;">
    <p class="txt alert">
        인증번호가 일치하지 않습니다.
    </p>
    <span class="btn_sumit">확인</span>
</div>
<script>
    var SetTime = 179;      // 최초 설정 시간(기본 : 초)
    function msg_time() {   // 1초씩 카운트
        m = Math.floor(SetTime / 60) + ":" + (SetTime % 60); // 남은 시간 계산
        var msg = m ;
        document.all.ViewTimer.innerHTML = msg;     // div 영역에 보여줌
        SetTime--;                  // 1초씩 감소
        if (SetTime < 0) {          // 시간이 종료 되었으면..
            clearInterval(tid);     // 타이머 해제
            alert("종료");

        }
    }
    function TimerStart(){ tid=setInterval('msg_time()',1000);};
    TimerStart()
    $('.wrap_certi_sms .btn_close').on('click',function(){
        $('.wrap_certi_sms').hide();
        $('.mask').removeClass('on');
    });
    $('.wrap_certi_sms1 .btn_sumit').on('click',function(){
        $('.wrap_certi_sms1').hide();
        $('.wrap_certi_sms2').show();
    })
    $('.wrap_certi_sms2 .btn_sumit').on('click',function(){
        $('.wrap_certi_sms2').hide();
        $('.wrap_certi_sms1').show();
    })

    $('.btn_time_extend').on('click',function(){
        clearInterval(tid);
        TimerStart()
        SetTime = 179;
    });


</script>