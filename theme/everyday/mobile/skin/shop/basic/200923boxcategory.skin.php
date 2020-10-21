<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="' . G5_MSHOP_SKIN_URL . '/200923style.css">', 0);//삭제하기

$save_file = G5_DATA_PATH . '/cache/theme/everyday/maincategory.php';
if (is_file($save_file))
    include($save_file);

if (!empty($maincategory)) {
    ?>
    <div id="gnb">
        <ul>
            <li class="gnb_1dli">
                <a href="/shop/list.php?ca_id=10">VM몰</a>
            </li>
            <li class="gnb_1dli">
                <a href="/myOffice/">마이오피스</a>
            </li>

            <li class="gnb_1dli"><a href="/bbs/board.php?bo_table=free">게시판</a></li>

            <li class="gnb_1dli">
                <a href="http://libmall365.com" target="_blank">libmall365</a>
            </li>
            <li class="gnb_1dli">
                <a href="/myOffice/v_ad_center.php">V광고센터</a>
            </li>         
        </ul>

    </div>

<?php
}
?>