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
        <?php //include_once(G5_THEME_MSHOP_PATH . '/category.php'); // 분류  ?>
        <!-- <button type="button" id="hd_sch_open">검색<span class="sound_only"> 열기</span></button> -->


        <form name="frmsearch1" action="<?php echo G5_SHOP_URL; ?>/search.php" onsubmit="return search_submit(this);">

        </form>
        <div class="wrap_gheader">

            <div id="hd_tnb">

                <div id="logo">
                    <a href="/bbs/board.php?bo_table=blog" class="logo_area"> <!-- G5_SHOP_URL; -->
                        <img src="/theme/everyday/mobile/shop/img/vmp_logo.png"
                                alt="메인으로 이동">
                        <div class="logo_typo">
                            <span>Vendor&nbsp;</span><span>Marketing&nbsp;</span><span>Platform</span></div>
                    </a>
                    <div class="mobile_menu"><i class="btn_menu"><span class="blind">모바일 메뉴</span></i></div>
                </div>
                <!-- 메뉴영역 (gnb) -->
                <div class="mobile_menu_box">

                    <!-- 모바일 전용 // -->
                    <div class="mobile_sign_area">
                    <?php if ($is_member) { ?>
                        <p class="welcome_txt">VMP님<br/>환영합니다.</p>
                        <ul class="mobile_log">

                            <li>
                                <a href="<?php echo G5_BBS_URL; ?>/logout.php?url=shop">
                                    <img src="/theme/everyday/mobile/shop/img/icon_logout.svg" width="32" height="32" />
                                    <p>로그아웃</p>
                                </a>
                            </li>
                        </ul>
                    <?php } else { ?>
                        <p class="welcome_txt">Welcome</p>
                        <div class="comeon_vmp">로그인을 하시면<br/>더 많은 혜택을<br/>받을 수 있습니다.</div>
                        <ul class="mobile_log">
                            <li>
                                <a href="<?php echo G5_BBS_URL; ?>/login.php?url=<?php echo $urlencode; ?>">
                                    <img src="/theme/everyday/mobile/shop/img/icon_login.png" width="32" height="32" />
                                    <p>로그인</p>
                                </a>
                            </li>
                            <li class="btn_join_mobile">
                                <a href="<?php echo G5_BBS_URL ?>/register.php" id="snb_join">
                                    <img src="/theme/everyday/mobile/shop/img/icon_register.png" width="32" height="32" />
                                    <p>회원가입</p>
                                </a>
                            </li>
                        </ul>
                    <?php } ?>
                    </div>
                    <!-- //모바일 전용 -->


                    <?php include_once(G5_MSHOP_SKIN_PATH . '/boxcategory.skin.php'); // 상품분류
                    //theme/everyday/mobile/skin/shop/basic
                    ?>
                 <!-- 정보수정 영역 -->
                 <span class="gvmp_header_bg"></span>
                    <ul class="hd_log" id="hd_log_1">

                        <?php if ($is_member) { ?>
                            <li class="onlypc"><a href="<?php echo G5_BBS_URL; ?>/logout.php?url=shop"><img
                                            src="/theme/everyday/mobile/shop/img/icon_logout.svg" width="32" height="32">
                                    <p>로그아웃</p></a></li>
                            <?php
                            if ($member['accountType'] == 'VD'){                            
                              //  if($_SERVER['REMOTE_ADDR'] == '211.44.185.70'){
                            ?>
                            <li class="manageorder_li onlypc">
                                <a target='_blank' onclick="window.name='MemberServlet.do';window.open(this.href, 'insert',
                    'width=1000,height=1000,resizable=no,scrollbars=no,status=no');return false;" href="http://gvmp.company/adm/shop_admin/0717orderlist.php">
                                    <img src="/theme/everyday/mobile/shop/img/icon_ordermanage.svg" />
                                    <p>주문관리</p>
                                </a>
                            </li>
                            <?php
                            }
                            ?>
                        <?php } else { ?>
                            <li class="onlypc"><a href="<?php echo G5_BBS_URL; ?>/login.php?url=<?php echo $urlencode; ?>"><img
                                            src="/theme/everyday/mobile/shop/img/icon_login.png" width="32" height="32">
                                    <p>로그인</p></a></li>
                            <li class="onlypc"><a href="<?php echo G5_BBS_URL ?>/register.php" id="snb_join"><img
                                            src="/theme/everyday/mobile/shop/img/icon_register.png" width="32" height="32">
                                    <p>회원가입</p></a>
                            </li>
                        <?php } ?>

                        <li><a href="/shop/mypage.php"><img src="/theme/everyday/mobile/shop/img/icon_orderlist.svg" width="33" height="31">
                                <p>주문내역</p></a></li>
                        <li>
                            <div id="cart_icon">
                                <span class="cart_icon_1"><?php echo get_cart_count($tmp_cart_id); ?></span>
                            </div>
                            <a href="<?php echo G5_SHOP_URL; ?>/cart.php" class="tnb_cart">
                                <span></span>
                                <img src="/theme/everyday/mobile/shop/img/icon_cart.png" width="32" height="32" />
                                <p>장바구니</p>
                            </a>
                        </li>
                    </ul>

                    <copyright class="vmp_copyright">Copyright 2018 VMP .COMPANY. All Right Reserved.</copyright>
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



$(document).on('click', '.mobile_menu', function(){
    $('.mobile_menu_box, .mobile_menu').toggleClass('on');
});
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