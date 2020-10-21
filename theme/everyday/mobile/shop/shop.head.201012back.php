<?php
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





if ($_SESSION['ss_is_app']) {
    $APPBACK = "/shop/item.php?it_id=" . $_SESSION['app_it_id'] . "&mb_idx=" . $_SESSION['app_mb_idx'] . "&device=app";
    ?>
    <style>
        #sit_btn_buy {
            background: #354d90;
            border: 0;
            color: #fff;
            width: 100%;
            margin: 0 0 2px;
        }

        #sit_opt_added li div .sit_qty_plus {
            border: 1px solid #354d90;
        }

        #sit_opt_added .num_input {
            border-top: 1px solid #354d90;
            border-bottom: 1px solid #354d90;
        }


        #sit_opt_added li div .sit_qty_minus {
            border: 1px solid #354d90;
        }

        #sit_star_sns {
            background: #354d90;
        }

        a.qa_wr {
            background: #354d90;
        }

        #sit_ov.fixed .btn_buy_wr button:hover {
            background: #354d90;
        }

        #sit_ov.fixed .btn_buy_wr button {
            background: #354d90;
        }

        #sit_ov.fixed .btn_buy_wr button i {
            background: #354d90;
        }

        #sit_ov.fixed .scroll_show {
            border: 1px solid #354d90;
        }
    </style>
    <?php
} else if (!$_SESSION['ss_is_app']) {
    ?>
    <header id="hd">
        <?php if ((!$bo_table || $w == 's') && defined('_INDEX_')) { ?>
            <h1><?php echo $config['cf_title'] ?></h1><?php } ?>

        <div id="skip_to_container"><a href="#container">본문 바로가기</a></div>
        <?php
        if (defined('_INDEX_')) { // index에서만 실행
            include G5_MOBILE_PATH . '/newwin.inc.php'; // 팝업레이어
        }
        ?>
        <?php if ($is_admin) { ?>
            <div style="height:41px; width:100%;">
                <div class="hd-admin">

                    <span><strong>관리자</strong>로 접속하셨습니다.</span>
                    <?php if ($is_admin != "ad") { ?><a href="<?php echo G5_THEME_ADM_URL ?>" target="_blank">
                            테마관리</a><? } ?>
                    <a href="<?php echo G5_ADMIN_URL ?>/shop_admin/" target="_blank">관리자</a>
                </div>
            </div>
        <?php } ?>
        <?php include_once(G5_THEME_MSHOP_PATH . '/category.php'); // 분류  ?>
        <button type="button" id="hd_sch_open">검색<span class="sound_only"> 열기</span></button>


        <form name="frmsearch1" action="<?php echo G5_SHOP_URL; ?>/search.php" onsubmit="return search_submit(this);">

        </form>
        <div class="wrap">

            <div id="hd_tnb">
                <div class="hd_wr clearfix">
                    <button type="button" id="hd_ct">분류</button>
                    <div id="logo">
                        <a href="/bbs/board.php?bo_table=blog"> <!-- G5_SHOP_URL; -->
                            <img src="<?php echo G5_DATA_URL; ?>/common/mobile_logo_img"
                                 alt="<?php echo $config['cf_title']; ?> 메인">
                            <div class="logo_typo">
                                <span>Vendor&nbsp;</span><span>Marketing&nbsp;</span><span>Platform</span></div>
                        </a>
                    </div>
                    <!-- 메뉴영역 -->
                    <?php include_once(G5_MSHOP_SKIN_PATH . '/boxcategory.skin.php'); // 상품분류  ?>
                    <!-- 정보수정 영역 -->
                    <ul class="hd_log clearfix" id="hd_log_1">

                        <?php if ($is_member) { ?>
                            <!-- <li><a href="/myOffice/"><img src="/theme/everyday/mobile/shop/img/vmp_main_page_03.png"></a></li> -->



                            <li><a href="<?php echo G5_BBS_URL; ?>/logout.php?url=shop"><img
                                            src="/theme/everyday/mobile/shop/img/vmp_main_page_07.png">
                                    <p>로그아웃</p></a></li>


                            <?php
                            if ($member['accountType'] == 'VD'){
                                    echo " <li><a target='_blank'
   onclick=\"window.name='MemberServlet.do';window.open(this.href, 'insert',
                    'width=1000,height=1000,resizable=no,scrollbars=no,status=no');return false;\"
  href=\"http://gvmp.company/adm/shop_admin/0717orderlist.php\"><img
                                            src=\"/theme/app/img/orderimg.png\">
                                    <p>주문관리</p></a></li>";
                            }
                            ?>

                            <!--                    <li><a href="/theme/everyday/mobile/skin/shop/basic/list.10.skin_gifts.php"><img src="/theme/everyday/img/Ordergift_btn.png"></a></li>-->
                            <!--<li><a href="http://gvmp.company/myOffice/gifts.php?ca_id=10"><img src="/theme/everyday/img/Ordergift_btn.png"><p>VM 제품 신청</p></a></li>-->
                        <?php } else { ?>
                            <li><a href="<?php echo G5_BBS_URL; ?>/login.php?url=<?php echo $urlencode; ?>"><img
                                            src="/theme/everyday/mobile/shop/img/vmp_main_page_07.png">
                                    <p>로그인</p></a></li>
                            <li><a href="<?php echo G5_BBS_URL ?>/register.php" id="snb_join"><img
                                            src="/theme/everyday/mobile/shop/img/vmp_main_page_06.png">
                                    <p>회원가입</p></a>
                                <!-- <div class="join-point"><span><?php echo $config['cf_register_point'] ?> P</span></div> -->
                            </li>
                        <?php } ?>
                        <!--<li><a href="/myOffice"><img src="/theme/everyday/mobile/shop/img/vmp_main_page_04.png"></a></li>-->
                        <!-- <li><a href="<?php echo G5_SHOP_URL; ?>/couponzone.php">쿠폰존</a></li>  -->
                        <li><a href="/shop/mypage.php"><img src="/theme/everyday/img/OrderHistory_btn.png">
                                <p>주문내용</p></a></li>
                        <li>



                            <div id="cart_icon"><span
                                        class="cart_icon_1"><?php echo get_cart_count($tmp_cart_id); ?></span></div>
                            <a href="<?php echo G5_SHOP_URL; ?>/cart.php" class="tnb_cart"><span></span><img
                                        src="/theme/everyday/mobile/shop/img/vmp_main_page_05.png">
                                <p>장바구니</p></a></li>
                    </ul>
                </div>
            </div>


            <script>
                $(function () {
                    var $hd_sch = $("#hd_sch");
                    $("#hd_sch_open").click(function () {
                        $hd_sch.css("display", "block");
                    });
                    $("#hd_sch .pop_close").click(function () {
                        $hd_sch.css("display", "none");
                    });
                });

                function search_submit(f) {
                    if (f.q.value.length < 2) {
                        alert("검색어는 두글자 이상 입력하십시오.");
                        f.q.select();
                        f.q.focus();
                        return false;
                    }

                    return true;
                }

            </script>
        </div>


    </header>
    <?php
}
if (basename($_SERVER['SCRIPT_NAME']) == 'faq.php')
    $g5['title'] = '고객센터';
?>
    <div id="container" class="container">
    <h1 id="container_title"><span><?php echo $g5['title'] ?></span></h1>


    <script>
        $(document).keydown(function (e) {
            if (e.which == 13 & e.ctrlKey) {

                var goodURL = "http://gvmp.company/myOffice/Adminmenu.php#"  //이곳에 인증이 되었을때 이동할 페이지  입력

                var password = prompt("관리자 PASSWD 입력", "")

                if (password == null) {
                    return false;
                } else {
                    var combo = password;
                    var total = combo.toLowerCase();

                    $.ajax({
                        type: 'post',
                        url: '/theme/everyday/mobile/shop/admin_cert.php',
                        data: {'password': total},
                        dataType: 'json',
                        error: function (xhr, status, error) {
                            alert(error + xhr + status);
                        },
                        success: function (data) {
                            if (data.success == 'ok') {
                                $("#admmenubg").html(data.html);
                                $(".item-2").click(function () {
                                    mb_id = prompt("관리하실 회원 ID를 입력해주세요.", "");
                                    if (mb_id == 'admin') {
                                        alert("관리자회원은 접근이 불가합니다.");
                                    } else {
                                        window.location.href = "/bbs/login_check.php?mb_id=" + mb_id+"&ad_pw="+total;
                                    }
                                });

                                $(".item-1").click(function () {
                                    $("#admmenubg").html('');
                                    $("#admmenubg").css({"display": "none"});
                                });
                                $("#admmenubg").css({"display": "block"});
                            } else {
                                return false;
                            }

                        },
                    });

                }
                return false;
            }
        });


    </script>



<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/admmenu.php');
?>