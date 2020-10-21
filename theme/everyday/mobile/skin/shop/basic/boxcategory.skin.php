<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . G5_MSHOP_SKIN_URL . '/style.css">', 0);

$save_file = G5_DATA_PATH . '/cache/theme/everyday/maincategory.php';
if (is_file($save_file))
    include($save_file);

if (!empty($maincategory)) {
    ?>
    <div id="gnb">
        <ul>

            <?php
            /*
            $gnb_zindex = 999; // gnb_1dli z-index 값 설정용
            foreach($maincategory as $key=>$val)
            {
                $sql = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where ca_id = '$key' and ca_use = '1' ";
                $row = sql_fetch($sql);
                if(!$row['ca_id'])
                    continue;

                // 2단계 분류 판매 가능한 것만
                $sql2 = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where LENGTH(ca_id) = '4' and SUBSTRING(ca_id,1,2) = '{$row['ca_id']}' and ca_use = '1' order by ca_order, ca_id ";
                $result2 = sql_query($sql2);
                $count = sql_num_rows($result2);
                */
            ?>

            <?php
            /*
            <li class="gnb_1dli" style="z-index:<?php echo $gnb_zindex; ?>">
                <a href="<?php echo G5_SHOP_URL.'/list.php?ca_id='.$key; ?>" class="gnb_1da<?php if ($count) echo ' gnb_1dam'; ?>"><?php echo $row['ca_name']; ?></a>
            </li>

            $gnb_zindex--; // html 구조에서 앞선 gnb_1dli 에 더 높은 z-index 값 부여

        }
        */
            ?>
            <li class="gnb_1dli">
                <a href="/shop/list.php?ca_id=10">VM몰</a>
            </li>
            <!--    <li class="gnb_1dli">
                    <a href="/shop/list.php?ca_id=90">VENDOR몰</a>
                </li>-->
            <li class="gnb_1dli">
                <a href="/myOffice/">마이오피스</a>
            </li>


            <!--20.08.21 주석 후 게시판 오픈 <li class="gnb_1dli">
                <a href="/bbs/board.php?bo_table=used">중고장터</a>
            </li> -->
            <li class="gnb_1dli"><a href="/bbs/board.php?bo_table=free">게시판</a></li>

            <li class="gnb_1dli">
                <a href="http://libmall365.com" target="_blank">libmall365</a>
            </li>
            <li class="gnb_1dli">
                <a href="/myOffice/v_ad_center.php">V광고센터</a>
            </li>
            <!--    <li class="gnb_1dli" id="gnb_title">
                    <a href="/myOffice/tree.php" id="menu_hover">리스트형</a>
                </li>
                <li class="gnb_1dli">
                    <a href="/myOffice/box_1.php">트리형</a>
                </li>
                <li>
                    <a href="/myOffice/sales.php">직접추천정보</a>
                </li>-->
        </ul>

    </div>

    <?php
}
?>