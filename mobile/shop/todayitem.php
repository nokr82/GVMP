<?php
include_once('./_common.php');

// 테마에 mypage.php 있으면 include
if(defined('G5_THEME_SHOP_PATH')) {
//	/var/www/html/gvmp/theme/vexmall/mobile/shop/mypage.php
    $theme_mypage_file = G5_THEME_MSHOP_PATH.'/todayitem.php';
    if(is_file($theme_mypage_file)) {
        include_once($theme_mypage_file);
        return;
        unset($theme_mypage_file);
    }
}
?>