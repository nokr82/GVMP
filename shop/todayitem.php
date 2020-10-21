<?php
include_once('./_common.php');

// 테마에 mypage.php 있으면 include
if (G5_IS_MOBILE) {
//	/var/www/html/gvmp/mobile/shop
    include_once(G5_MSHOP_PATH.'/todayitem.php');
    return;
}
?>