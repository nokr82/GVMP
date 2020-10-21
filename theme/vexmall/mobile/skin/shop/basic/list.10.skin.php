<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once($_SERVER['DOCUMENT_ROOT'] . "/myOffice/dbConn.php");


// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_CSS_URL.'/style.css">', 0);
add_javascript('<script src="'.G5_THEME_JS_URL.'/jquery.shop.list.js"></script>', 10);
?>

<?php
    $row = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'"));
    if( $_GET['ca_id'] == "10" && ($row['accountType'] == "CU") ) { // VM몰이면 참
        echo "<script>alert('VM몰에 접근할 수 없는 계정입니다.');history.back();</script><style>body{display:none;}</style>";
    }
?>

<?php if($config['cf_kakao_js_apikey']) { ?>
<script src="https://developers.kakao.com/sdk/js/kakao.min.js"></script>
<script src="<?php echo G5_JS_URL; ?>/kakaolink.js"></script>
<script>
    // 사용할 앱의 Javascript 키를 설정해 주세요.
    Kakao.init("<?php echo $config['cf_kakao_js_apikey']; ?>");
</script>
<?php } ?>
<!-- <div class="sct-size"> 
    <button type="button" class="btn-size" id="btn-big">이미지크게보기</button>
    <button type="button" class="btn-size active" id="btn-small">이미지작게보기</button>
</div> -->
<!-- 상품진열 10 시작 { -->
<div id="itemList" class="resultList ">
	<ul id="sct_wrap">
<?php
$li_width = intval(100 / $this->list_mod);
$li_width_style = ' style="width:'.$li_width.'%;"';

for ($i=0; $row=sql_fetch_array($result); $i++) {
//    if ($i == 0) {
//        if ($this->css) {
//            echo "<ul id=\"sct_wrap\" class=\"{$this->css}\">\n";
//        } else {
//            echo "<ul id=\"sct_wrap\" class=\"sct sct_10\">\n";
//        }
//    }
?>
        <li>
            <a href="<?echo $this->href?><?=$row['it_id']?>" class="clearfix">
                <div class="list_img">
                    <?=get_app_it_image($row['it_id'],340, 308, '', '', stripslashes($row['it_name']))?>
                </div>
                <div class="list_text">
                    <h3><?=$row['it_name']?></h3>
                    <p class="pay"><?=display_price(get_price($row), $row['it_tel_inq'])?><!--span class="sale">17,330원</span--></p>
                    <p class="pay_2">무료배송</p>
                </div>
            </a>
        </li>
<?
}
?>
	</ul>
</div>
<script>
$(".sct-size button").click(function () {
	$(".sct-size button").removeClass("active");
	$(this).addClass("active");
});

$(".liststyle_list").click(function () {
	if(!$(this).hasClass("on")){
		$("#listNav div").removeClass("on");
		$(this).addClass("on");
		$("#itemList").toggleClass('resultList resultPic');
	}
});

$(".liststyle_pic").click(function () {
	if(!$(this).hasClass("on")){
		$("#listNav div").removeClass("on");
		$(this).addClass("on");
		$("#itemList").toggleClass('resultPic resultList');
	}
});


$(window).on("scroll",function(){
	if($(this).scrollTop() > 100){
		$("#shopTopBtn").show("slow");
	}else{
		$("#shopTopBtn").hide();
	}
});
var scrollTop = function() {
	var body = $("html, body");
	body.stop().animate({scrollTop:0}, 500, 'swing');
}
</script>
<!-- } 상품진열 10 끝 -->

<div id="shopTopBtn" style="position:fixed;bottom:10px;right:10px;z-index:9999997;height:40px;display:none;">
<a href="javascript:scrollTop();"><img src="/img/itemShopTopBtn.svg" style="width:40px;height:40px"></a>
</div>