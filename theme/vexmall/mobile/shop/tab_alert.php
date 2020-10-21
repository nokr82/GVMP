<?php
include_once($DOCUMENT_ROOT.'/vexMall/back/dbConn.php'); // DB Server
include_once('./_common.php');

//if (!$is_member)
//    goto_url(G5_BBS_URL."/login.php");
//풀지말것

define("_INDEX_", TRUE);

include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');



$sql = " select * from {$g5['g5_shop_category_table']} where ca_id = '$ca_id' and ca_use = '1'  ";
$ca = sql_fetch($sql);
?>
<!--<link rel="stylesheet" href="<?=G5_THEME_CSS_URL?>/jquery.bxslider.css">
<script src="<?=G5_THEME_JS_URL?>/jquery.bxslider.js"></script>
<script src="<?=G5_THEME_JS_URL?>/index.js"></script>-->
    <style>
		#tabAlert li{ list-style:none;}
		#tabAlert li >h2{ cursor:pointer;}
		li > ul{ display:none;}
		li > ul >li{ color:#00F;}

    </style>

<div id="tabAlert">
    <ul>
        <li>            
            <p>이벤트 알림 <span>2019-01-07~2019-02-07</span></p>
            <h2>이벤트 제목이 들어갈 텍스트 입니다.<i class="fa fa-chevron-down"></i></h2>
            <ul>
                <li><p>텍스트가 들어갈수도 있습니다.</p></li>
            </ul>
        </li>
        <li>            
            <p>이벤트 알림 <span>2019-01-07~2019-02-07</span></p>
            <h2>이벤트 제목이 들어갈 텍스트 입니다.<i class="fa fa-chevron-down"></i></h2>
            <ul>
                <li><img src="<?=G5_THEME_IMG_URL?>/event/vroad_alert.svg" alt="경고표시" /></li>
            </ul>
        </li>
    </ul>
    <div class="none_alert">
        <img src="<?=G5_THEME_IMG_URL?>/event/vroad_alert.svg" alt="경고표시" />
        <p>알림이 없습니다.</p>
    </div>
</div>


<script>
              
$(function(){
	$("#tabAlert h2").click(function(){
		$("#tabAlert ul ul").slideUp();
                $('i').css('transform','none');
		if(!$(this).next().is(":visible"))
		{
                    $(this).next().slideDown();
                    $(this).find('i').css('transform','rotate(180deg)');
		}
	})
})


</script>


<?php
include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>