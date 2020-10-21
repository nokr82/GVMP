<script src="./js/jquery.bxslider.min.js"></script>
<link rel="stylesheet" href="./css/jquery.bxslider.min.css">
<style>
.v_ad_content .v_ad_helper .btn_helper {position: fixed; bottom: 185px; right: 11px; z-index: 999; width: 50px; height: 50px; background: url('./images/icon_v_ad_helper.png')no-repeat center; background-size: cover; text-indent: -99999%}
/*.v_ad_content .v_ad_helper .helper_content {position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 100000; width: 100%; height: 100%; background: #3b3c3d;} 200122change*/
.v_ad_content .v_ad_helper .helper_content {position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 100000; width: 100%; height: 100%; background: rgba(59,60,61,1) ;}

.v_ad_content .v_ad_helper .helper_content .close_box {position: absolute; top: 50px; right: 0;  z-index: 10000; text-align: right; font-family: 'Noto Sans KR', sans-serif; font-size: 18px; color: #fff}
.v_ad_content .v_ad_helper .helper_content .close_box #close_cache {margin-right: 5px; padding: 0; width: 22px; height: 22px; border:none; border-radius: 50%; background: url('./images/bg_checkbox.png')no-repeat 0 0; background-size: contain}
.v_ad_content .v_ad_helper .helper_content .close_box #close_cache:checked{background: url('./images/bg_checkbox_checked.png')no-repeat 0 0; background-size: contain }
.v_ad_content .v_ad_helper .helper_content .btn_close {margin: 0 30px 0 26px; display: inline-block; width: 45px; height: 46px; text-indent: -99999%; font-size: 0; background: url('./images/btn_close.png')no-repeat 0 0; background-size: contain; vertical-align: middle; }
.v_ad_content .v_ad_helper .helper_content ul {text-align: center}
.v_ad_content .v_ad_helper .helper_content ul li {margin: 0 auto;}
.v_ad_content .v_ad_helper .helper_content ul li img {margin: 0 auto; object-fit: contain}
.v_ad_content .v_ad_helper .helper_content .helper_mobile_wrap .helper_mobile {display: none}
.v_ad_content .v_ad_helper .helper_content .helper_mobile_wrap .bx-wrapper .bx-pager {display: none}
.v_ad_content .bx-wrapper .bx-controls-direction a {top: 38%; width: 90px; height: 90px}
.v_ad_content .bx-wrapper .bx-prev {background: url('./images/icon_prev_arrow.svg')no-repeat; background-size: cover;}
.v_ad_content .bx-wrapper .bx-next {background: url('./images/icon_next_arrow.svg')no-repeat; background-size: cover;}
.bx-wrapper .bx-controls-auto, .bx-wrapper .bx-pager {position: fixed; bottom: 4%}
.bx-wrapper .bx-pager.bx-default-pager a {width: 8px; height: 8px; background: #fff; transition: all 0.5s ease-in-out}
.bx-wrapper .bx-controls-auto .bx-controls-auto-item, .bx-wrapper .bx-pager-item {vertical-align: middle}
.bx-wrapper .bx-pager.bx-default-pager a.active, .bx-wrapper .bx-pager.bx-default-pager a:focus {width: 17px; height: 17px; border-radius: 50%; background: #fff}
.bx-wrapper .bx-pager.bx-default-pager a:hover {background: #fff}

@media (max-width : 1400px) {
.v_ad_content .v_ad_helper .helper_content .close_box {top: 10px; font-size: 16px}
.v_ad_content .v_ad_helper .helper_content .btn_close {margin: 0 20px 0 13px; width: 32px; height: 35px}
.v_ad_content .bx-wrapper .bx-controls-direction a {top: 38%; width: 60px; height: 60px}
}

@media (max-width : 800px) {
.v_ad_content .v_ad_helper .helper_content .helper_pc_wrap .helper_pc {display: none}  
.v_ad_content .v_ad_helper .helper_content .helper_pc_wrap .helper_pc .bx-wrapper .bx-pager {display: none}  

.v_ad_content .v_ad_helper .helper_content .helper_mobile_wrap .helper_mobile {display: block}  
.v_ad_content .v_ad_helper .helper_content .helper_mobile_wrap .bx-wrapper .bx-pager {display: block}
.v_ad_content .v_ad_helper .helper_content .helper_mobile_wrap .bx-wrapper .bx-controls-direction a {display: none}
}

</style>


<script language='javascript'>
function setCookie(name, value, expiredays){
    var todayDate = new Date();
        todayDate.setDate (todayDate.getDate() + expiredays);
        document.cookie = name + "=" + escape(value) + "; path=/; expires=" + todayDate.toGMTString() + ";";
}

function closePop(){
    if ($('#close_cache').prop('checked')== true){
        setCookie("popname", "done", 1);
    }
    
    $('.helper_content').css('display','none');
}


</script>
       


<!--도움말 시작-->
<div class="v_ad_helper">
    <a href="#none" class="btn_helper">도움말</a>
    <div class="helper_content">
        <div class="close_box">
            <form name="pop_form" id="pop_form">
            <input type="checkbox" id="close_cache" name="close_cache">
            <label for="close_cache">오늘 하루 열지 않기</label>
            <a href="#none" class="btn_close" onclick="closePop()">닫기</a>
            </form>
        </div>
        <div class="helper_pc_wrap">
            <ul class="helper_pc slider">
                <li>
                    <img src="./images/img_v_ad_helper_1.png" alt="도움말"/>
                </li>
                <li>
                    <img src="./images/img_v_ad_helper_2.png" alt="도움말"/>
                </li>
                <li>
                    <img src="./images/img_v_ad_helper_3.png" alt="도움말"/>
                </li>
            </ul>
        </div>
        <div class="helper_mobile_wrap">
            <ul class="helper_mobile slider_2">
                <li>
                    <img src="./images/img_helper_mobile_1.png" alt="도움말"/>
                </li>
                <li>
                    <img src="./images/img_helper_mobile_2.png" alt="도움말"/>
                </li>
                <li>
                    <img src="./images/img_helper_mobile_3.png" alt="도움말"/>
                </li>
                <li>
                    <img src="./images/img_helper_mobile_4.png" alt="도움말"/>
                </li>
            </ul>   
        </div>
    </div>
</div>
<!--도움말 끝-->

<script>
var sliderPc;
var sliderMobile;
var resizeEvnt;
        
$(window).resize(function(){
    clearTimeout(resizeEvnt);
    resizeEvnt = setTimeout(slider,150);
});

slider();
function slider(){
    var width = $(window).innerWidth();
    
    if( width > 800 ){
        sliderPc = $('.slider').bxSlider();
        
        if(sliderMobile){
            sliderMobile.destroySlider();
        }
        
    } else if ( width <= 800 ){
        
        if(sliderPc){
            sliderPc.destroySlider();
        }
        
        var sliderMobile = $('.slider_2').bxSlider();
    }
}


cookiedata = document.cookie;   
if ( cookiedata.indexOf("popname=done") < 0 ){     
    $('.helper_content').css('display','block');
}
else {
    $('.helper_content').css('display','none');
}




//도움말 열기
$('.v_ad_helper .btn_helper').on('click', function(){
    $('.helper_content').css('display','block');
});


</script>
