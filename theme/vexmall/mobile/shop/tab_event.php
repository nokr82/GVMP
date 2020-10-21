
<?php
include_once('./_common.php');

//if (!$is_member)
//    goto_url(G5_BBS_URL."/login.php");
//풀지말것

define("_INDEX_", TRUE);

include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');
?>
<!--<link rel="stylesheet" href="<?=G5_THEME_CSS_URL?>/jquery.bxslider.css">
<script src="<?=G5_THEME_JS_URL?>/jquery.bxslider.js"></script>
<script src="<?=G5_THEME_JS_URL?>/index.js"></script>-->



<div id="tabEvent">
    <ul class="event_state clearfix">        
        <li>
            <a href="#" class="on">진행중인 이벤트</a>
        </li>
        <li>
            <a href="#">종료된 이벤트</a>
        </li>
    </ul>
    <!--display none 해제시 이벤트 내역 보임 style="display: none;"-->
    <ul class="event_list">
        <!--li class end 추가시 종료된 이벤트 모양-->
        <li>
            <a href="#">
                <div>
                    <img src="<?=G5_THEME_IMG_URL?>/event/test_event.jpg" alt="asd" />
                </div>                
                <h3>이벤트 제목이 들어갈 텍스트 입니다.</h3>
                <p>2019-01-07 ~ 2019-02-07</p>
            </a>
        </li>
        <li class="end">
            <a href="#">
               <div>
                    <img src="<?=G5_THEME_IMG_URL?>/event/test_event.jpg" alt="asd" />
                </div>
                <h3>이벤트 제목이 들어갈 텍스트 입니다.</h3>
                <p>2019-01-07 ~ 2019-02-07</p>
            </a>
        </li>
    </ul>
    <div class="none_event">
        <img src="<?=G5_THEME_IMG_URL?>/event/vroad_alert.svg" alt="경고표시" />
        <h3>이벤트가 없습니다.</h3>
    </div>
</div>










<?php
include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>