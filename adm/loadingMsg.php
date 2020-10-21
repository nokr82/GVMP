<!--로딩메세지 띄우는 페이지--> 

<style>
.loading_wrap.on {display: block}
.loading_wrap.off {display: none}
.loading_wrap {display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;z-index: 9999; background: rgba(255,255,255,0.9); text-align: center}
.loading_wrap .loading_content {position: absolute; top: 50%; left: 50%; margin-top: -150px; margin-left: -200px; width: 400px; height: 300px;}
.loading_wrap .loading_content .loading_img {margin: 0 auto}
.loading_wrap.on .loading_content .loading_txt {font-family: 'Noto Sans KR'; font-size: 1rem; color: #333}



@media (min-width: 320px) and (max-width: 480px){   
.loading_wrap .loading_content {margin-left: -100px; width: 200px;}    
.loading_wrap .loading_content .loading_img {width: 80%}
.loading_wrap.on .loading_content .loading_txt {font-size: 1.2rem}
}
</style>

<div class="loading_wrap">
    <div class="loading_content">
        <img class="loading_img" src="/myOffice/images/vmp_loading_icon.gif" alt="로딩메세지"/>
        <p class="loading_txt">데이터를 전송 중입니다.</p>
    </div>
</div>
<script>


// 로딩메세지 띄우기 
// act가 on일때 메세지가 띄워지며 off일때는 메세지가 꺼집니다. 
function loadMsg(act,msg){
    if(act == 'on'){
        
        if($('.loading_wrap').hasClass('off')){
            $('.loading_wrap').removeClass('off')
        }
        
        $('.loading_wrap').addClass('on');
        $('.loading_txt').text(msg);
        
    } else if( act == 'off' ){
        
        if($('.loading_wrap').hasClass('on')){
            $('.loading_wrap').removeClass('on');
        }
        $('.loading_wrap').removeClass('on');
        $('.loading_txt').text('데이터를 전송 중입니다.');
    }
}
</script>