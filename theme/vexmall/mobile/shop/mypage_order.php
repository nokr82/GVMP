<?php
include_once('./_common.php');

$g5['title'] = '마이페이지';

include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');

define("_ORDERINQUIRY_", true);
// /var/www/html/gvmp/mobile/shop

include G5_MSHOP_PATH.'/orderinquiry.sub.php';
?>

<?php
include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>