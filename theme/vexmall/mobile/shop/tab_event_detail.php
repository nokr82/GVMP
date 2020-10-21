
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



<div id="event_detail">
    <div class="detail_head">
        <h3>이벤트 제목이 들어갈 텍스트 입니다.</h3>
        <p>이벤트 기간: 2019-01-07 ~ 2019-02-07</p>
    </div>
    <div class="detail_content">
        <img src="<?=G5_THEME_IMG_URL?>/event/test_event2.jpg" alt="asd" />
    </div>
</div>










<?php
include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>