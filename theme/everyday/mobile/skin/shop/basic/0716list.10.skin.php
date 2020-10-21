<?php
//동우제작0716
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once($_SERVER['DOCUMENT_ROOT'] . "/myOffice/dbConn.php");


// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . G5_SHOP_CSS_URL . '/style.css">', 0);
add_javascript('<script src="' . G5_THEME_JS_URL . '/jquery.shop.list.js"></script>', 10);
?>

<?php
$row = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'"));
if ($_GET['ca_id'] == "10" && ($row['accountType'] == "CU")) { // VM몰이면 참
    echo "<script>alert('VM몰에 접근할 수 없는 계정입니다.');history.back();</script><style>body{display:none;}</style>";
}
?>

<?php if ($config['cf_kakao_js_apikey']) { ?>
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
<div class="sct_wrap">
    <?php
    $li_width = intval(100 / $this->list_mod);
    $li_width_style = ' style="width:' . $li_width . '%;"';

    $where = '';
    if ($_GET['q'] != '') {
        $where .= "and it_name like '%{$_GET['q']}%'";
    }
    if ($_GET['qto'] == '') {
        $_GET['qto'] = 0;
    }
    if ($_GET['qfrom'] == '') {
        $_GET['qfrom'] = 0;
    }

    if ($_GET['qfrom'] != '' && $_GET['qfrom'] != 0) {
        $where .= "and it_price >= {$_GET['qfrom']}";
    }
    if ($_GET['qto'] != '' && $_GET['qto'] != 0) {
        $where .= " and it_price <= {$_GET['qto']}";
    }

    if ($_GET['page'] == '') {
        $_GET['page'] = 0;
    } else {
        $_GET['page'] = ($_GET['page'] - 1) * 24;
    }


    $sql = "select * from `temp_item_shop` where it_1 = '' and it_use = '1' and ( ca_id2 like '{$_GET['ca_id']}%' {$where}) order by it_order, it_id desc limit {$_GET['page']} , 24";

    $result = sql_query($sql);
    for ($i = 0; $row = sql_fetch_array($result); $i++) {
        if ($i == 0) {
            if ($this->css) {
                echo "<ul id=\"sct_wrap\" class=\"{$this->css}\">\n";
            } else {
                echo "<ul id=\"sct_wrap\" class=\"sct sct_10\">\n";
            }
        }

        if ($i % $this->list_mod == 0)
            $li_clear = ' sct_clear';
        else
            $li_clear = '';

        echo "<li class=\"sct_li\"><div class=\"sct_li_wr\">\n";

        echo "<div class=\"img_wr\">";

        if ($this->href) {
            echo "<div class=\"sct_img\"><a href=\"{$this->href}{$row['it_id']}\" class=\"sct_a\">\n";
        }
        if ($this->view_it_img) {
            echo get_it_image($row['it_id'], $this->img_width, $this->img_height, '', '', stripslashes($row['it_name'])) . "\n";
        }

        if ($this->href) {
            echo "</a></div>\n";
        }

        echo "<div class=\"sct_btn\">
            <div class=\"sct_cart_btn\">
                <button type=\"button\" class=\"btn_cart\" data-it_id=\"{$row['it_id']}\"><span class=\"sound_only\">장바구니</span><i class=\"fa fa-shopping-cart\" aria-hidden=\"true\"></i></button>
                <button type=\"button\" class=\"btn_wish\" data-it_id=\"{$row['it_id']}\"><span class=\"sound_only\">위시리스트</span><i class=\"fa fa-heart\" aria-hidden=\"true\"></i></button>
            </div>
        </div>\n";

        echo "</div>";

        echo "<div class=\"sct_cartop\"></div>\n";
        if ($this->href) {
            echo "<div class=\"sct_txt\"><a href=\"{$this->href}{$row['it_id']}\" class=\"sct_a\">\n";
        }

        if ($this->view_it_name) {
            echo stripslashes($row['it_name']) . "\n";
        }

        if ($this->href) {
            echo "</a></div>\n";
        }


        if ($this->view_it_price) {
            echo "<div class=\"sct_cost\">\n";
            echo display_price(get_price($row), $row['it_tel_inq']) . "\n";
            echo "</div>\n";
        }

//    if( $_GET["ca_id"] == "10" ) {
//        echo "<div class=\"sct_cost_point\">
//                <span>
//                    <b>적립금</b>
//                    <strong>" .  number_format( (int)str_replace(',', '', display_price(get_price($row), $row['it_tel_inq'])) * 0.05 ) ." VMP</strong>
//                </span>
//              </div>\n";
//    }


        echo "<div class=\"sct_icon_wr\">" . item_icon2($row) . "</div>\n";

        echo "</div></li>\n";
    }

    if ($i > 0) echo "</ul>\n";

    if ($i == 0) echo "<p class=\"sct_noitem\">등록된 상품이 없습니다.</p>\n";
    ?>

</div>
<script>

    $(".sct-size button").click(function () {
        $(".sct-size button").removeClass("active");
        $(this).addClass("active");
    });
    $("#btn-small").click(function () {
        $(".sct_wrap").removeClass("big").addClass("small");
    });
    $("#btn-big").click(function () {
        $(".sct_wrap").removeClass("small").addClass("big");
    });

</script>
<!-- } 상품진열 10 끝 -->

