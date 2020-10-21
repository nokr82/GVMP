<!-- Channel Plugin Scripts -->
<!--<script>
//고객센터 플러그인
  window.channelPluginSettings = {
    "pluginKey": "276194da-83a6-4e2f-93e2-c37dba0dc6c4"
  };
  (function() {
    var node = document.createElement('div');
    node.id = 'ch-plugin';
    document.body.appendChild(node);
    var async_load = function() {
      var s = document.createElement('script');
      s.type = 'text/javascript';
      s.async = true;
      s.src = '//cdn.channel.io/plugin/ch-plugin-web.js';
      s.charset = 'UTF-8';
      var x = document.getElementsByTagName('script')[0];
      x.parentNode.insertBefore(s, x);
    };
    if (window.attachEvent) {
      window.attachEvent('onload', async_load);
    } else {
      window.addEventListener('DOMContentLoaded', async_load, false);
    }
  })();
</script>-->

<!-- End Channel Plugin -->

<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

function get_mshop_category($ca_id, $len)
{
    global $g5;

    $sql = " select ca_id, ca_name from {$g5['g5_shop_category_table']}
                where ca_use = '1' ";
    if ($ca_id)
        $sql .= " and ca_id like '$ca_id%' ";
    $sql .= " and length(ca_id) = '$len' order by ca_order, ca_id ";

    return $sql;
}

// 쿠폰
$cp_count = 0;
$sql = " select cp_id
            from {$g5['g5_shop_coupon_table']}
            where mb_id IN ( '{$member['mb_id']}', '전체회원' )
              and cp_start <= '" . G5_TIME_YMD . "'
              and cp_end >= '" . G5_TIME_YMD . "' ";
$res = sql_query($sql);

for ($k = 0; $cp = sql_fetch_array($res); $k++) {
    if (!is_used_coupon($member['mb_id'], $cp['cp_id']))
        $cp_count++;
}
?>

<meta property="og:type" content="website">
<meta property="og:title" content="VMP 홈페이지">
<meta property="og:description" content="Vendor Marketing Platform">
<meta property="og:image" content="http://gvmp.company/image/common/vmp_og_logo.png">
<meta property="og:url" content="http://gvmp.company/">


<!-- 장바구니 백엔드 -->
<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

include_once(G5_THEME_PATH . '/head.sub.php');
include_once(G5_LIB_PATH . '/outlogin.lib.php');
include_once(G5_LIB_PATH . '/visit.lib.php');
include_once(G5_LIB_PATH . '/connect.lib.php');
include_once(G5_LIB_PATH . '/popular.lib.php');
include_once(G5_LIB_PATH . '/latest.lib.php');

set_cart_id(0);
$tmp_cart_id = get_session('ss_cart_id');

add_javascript('<script src="' . G5_THEME_JS_URL . '/owl.carousel.min.js"></script>', 10);
add_stylesheet('<link rel="stylesheet" href="' . G5_THEME_JS_URL . '/owl.carousel.css">', 0);

?>


<div id="category" style="z-index: 9999;">
    <div class="ct_wr">
        <?php if ($is_member) { ?>
            <div class="ct_login ct_logout">
                <h2><?php echo $member['mb_id'] ? $member['mb_name'] : '비회원'; ?></strong>님, 환영합니다.</h2>
                <ul class="ct-info2">

                    <li>
                        <a href=" <?php echo G5_SHOP_URL; ?>/coupon.php" target="_blank" class="win_coupon">
                            <img src="<?php echo G5_THEME_URL; ?>/mobile/shop/img/icon-cp.png">
                            <strong>쿠폰</strong>
                            <span class="num"><?php echo number_format($cp_count); ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo G5_BBS_URL; ?>/point.php" target="_blank" class="win_point">
                            <img src="<?php echo G5_THEME_URL; ?>/mobile/shop/img/icon-po.png">
                            <strong>포인트</strong>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo G5_SHOP_URL; ?>/cart.php">
                            <img src="<?php echo G5_THEME_URL; ?>/mobile/shop/img/icon-cart.png">
                            <strong>장바구니</strong>
                            <span class="num"><?php echo get_cart_count($tmp_cart_id); ?></span>
                        </a>
                    </li>
                    <li>
                        <button type="button" class="today_btn">
                            <img src="<?php echo G5_THEME_URL; ?>/mobile/shop/img/icon-today.png">
                            <strong>오늘본상품</strong>
                        </button>
                        <?php include(G5_MSHOP_SKIN_PATH . '/boxtodayview.skin.php'); // 오늘 본 상품 ?>
                        <script>
                            $(".today_btn").click(function () {
                                $("#today-view").toggle();
                            });
                        </script>
                    </li>
                </ul>
                <!-- 수직메뉴바 -->
                <ul class="ct-info ">
                    <?php if ($is_member) { ?>
                        <!--                <li><a href="/myOffice/"><img src="/theme/everyday/mobile/shop/img/office.png"></a></li>
                <li><a href="/myOffice/tree.php"><img src="/theme/everyday/mobile/shop/img/list.png"></a></li>
                <li><a href="/myOffice/box_1.php"><img src="/theme/everyday/mobile/shop/img/tree.png"></a></li>
                <li><a href="/myOffice/sales.php"><img src="/theme/everyday/mobile/shop/img/sales.png"></a></li>
                <li><a href="<?php echo G5_BBS_URL; ?>/logout.php?url=shop"><img src="/theme/everyday/mobile/shop/img/logout.png"></a></li>-->
                    <?php } else { ?>
                        <li><a href="<?php echo G5_BBS_URL; ?>/login.php?url=<?php echo $urlencode; ?>"><img
                                        src="/theme/everyday/mobile/shop/img/vmp_main_page_02.png"></a></li>
                        <li><a href="<?php echo G5_BBS_URL ?>/register.php" id="snb_join"><img
                                        src="/theme/everyday/mobile/shop/img/vmp_main_page_06.png"></a>
                            <!-- <div class="join-point"><span><?php echo $config['cf_register_point'] ?> P</span></div> -->
                        </li>
                    <?php } ?>

                    <!-- <li><a href="/myOffice"><img src="/theme/everyday/mobile/shop/img/vmp_main_page_04.png"></a></li> -->
                    <!-- <li><a href="<?php echo G5_SHOP_URL; ?>/couponzone.php">쿠폰존</a></li>  -->
                    <!-- <li><a href="<?php echo G5_SHOP_URL; ?>/cart.php" class="tnb_cart"><span></span><img src="/theme/everyday/mobile/shop/img/vmp_main_page_05.png"></a><div class="cart-num"><span><?php echo get_cart_count($tmp_cart_id); ?></span></div></li>  -->
                </ul>


                <ul class="ct-btn ct-info2">
                    <li><a href="<?php echo G5_BBS_URL; ?>/member_confirm.php?url=register_form.php"
                           class="btn_1">정보수정</a></li>
                    <li><a href="<?php echo G5_BBS_URL; ?>/logout.php?url=shop">로그아웃</a></li>
                </ul>
            </div>

        <?php } else { ?>
            <div class="ct_login">
                <h2>Welcome</h2>
                <p>로그인을 하시면 더 많은 혜택을 <br>받으실 수 있습니다</p>
                <ul class="ct-btn">
                    <li><a href="<?php echo G5_BBS_URL ?>/register.php" class="btn_1">회원가입</a></li>
                    <li><a href="<?php echo G5_BBS_URL; ?>/login.php?url=<?php echo $urlencode; ?>">로그인</a></li>
                </ul>
            </div>
        <?php } ?>


        <!-- 팝업 메뉴 -->
        <div class="ct_popup">
            <div class="ct_shop clearfix">
                <div><a href="http://gvmp.company/shop/list.php?ca_id=10"><img
                                src="/theme/everyday/mobile/shop/img/shop1.png">
                        <p>VM몰</p></a></div>
                <!--<div><a href="http://gvmp.company/shop/list.php?ca_id=90"><img src="/theme/everyday/mobile/shop/img/shop2.png"><p>VENDOR몰</p></a></div>-->
            </div>
            <ul>
                <li><a href="/myOffice">마이오피스<span>></span></a></li>
                <li><a href="/bbs/board.php?bo_table=free">게시판<span>></span></a></li>
                <li><a href="http://libmall365.com" target="_blank">libmall365<span>></span></a></li>
                <li><a href="/myOffice/v_ad_center.php">V광고센터<span>></span></a></li>
                <!-- 20.08.21 주석 <li><a href="/bbs/board.php?bo_table=used">중고장터<span>></span></a></li>-->
                <li><a href="/shop/cart.php">장바구니<span>></span></a></li>
                <li><a href="/shop/mypage.php">주문내역<span>></span></a></li>
                <!--<li><a href="http://gvmp.company/myOffice/gifts.php?ca_id=10">VM 제품 신청<span>></span></a></li>-->
                <li><a href="/bbs/logout.php?url=shop">로그아웃<span>></span></a></li>

                <!--        		<li><a href="http://gvmp.company/bbs/board.php?bo_table=gallery">광고<span>></span></a></li>
                                <li><a href="http://gvmp.company/bbs/board.php?bo_table=signul">시그널<span>></span></a></li>
                                <li><a href="http://gvmp.company/bbs/board.php?bo_table=notice">커뮤니티<span>></span></a></li>-->
            </ul>
        </div>

        <!-- 팝업메뉴 하단부분 -->

        <div class="ch_icon">
            <ul style="text-align: center; padding-top: 10%;">
                <li style="display: inline-block;">
                    <a href="http://pf.kakao.com/_aAbxiC/chat" style="position:relative; top:0px; right:0px;">
                        <img src="/theme/everyday/mobile/shop/img/kakaochat.png" style="width:80px">
                    </a>
                </li>
                <li style="display: inline-block; padding-left: 10%;"><a href="https://pf.kakao.com/_xnUxblC"
                                                                         id="custom-button-2"><img
                                src="/theme/everyday/mobile/shop/img/kakaoplus2_03.png" style="width:80px;"></a></li>
                <li>
                    <div id="kakaostory-follow-button2" data-id="appinfou" data-type="vertical"
                         data-show-follower-count="true"></div>
                </li>
            </ul>
        </div>


        <div class="company">
            <div id="line"></div>
            <p>Copyright 2018 VMP.COMPANY. All Right Reserved. </p>
        </div>
        <button type="button" class="pop_close"><span class="sound_only">카테고리 </span>닫기</button>

    </div>

</div>
<!--<script type="text/javascript">
category(function(){
    category("#hd_ct").toggle(
    function(){
        category("#category").animate({left:0}, 500);
        category("#hd_ct").css({"background":"url()"});
       },
       function(){
        category("#category").animate({left:'-100%'}, 500);
        category("#hd_ct").css({"background":"url()"});
           
       }
    );
});

</script>-->
<script>

    $(function () {
        var $category = $("#category");

        $("#hd_ct").on("click", function () {
            $category.css({'display': 'block', 'left': '0%'});
        });

//    $("#hd_ct").on("click", function(){
//        $("#hd_ct").css({'background':'url(img/btnclose.png) no-repeat 50% 50%'});
//    });

        $("#category .pop_close").on("click", function () {
            $category.css({'display': 'block', 'left': '-100%'});
        });


        $("button.sub_ct_toggle").on("click", function () {
            var $this = $(this);
            $sub_ul = $(this).closest("li").children("ul.sub_cate");

            if ($sub_ul.size() > 0) {
                var txt = $this.text();

                if ($sub_ul.is(":visible")) {
                    txt = txt.replace(/닫기$/, "열기");
                    $this
                        .removeClass("ct_cl")
                        .text(txt);
                } else {
                    txt = txt.replace(/열기$/, "닫기");
                    $this
                        .addClass("ct_cl")
                        .text(txt);
                }

                $sub_ul.toggle();
            }
        });
    });


</script>

