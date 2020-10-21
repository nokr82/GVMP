<link rel="stylesheet" href="../theme/basic/css/default_shop.css" /> 


<?php
include_once('./_common.php');




// 기기별 주문폼 include
if($is_mobile_order) {
    $order_action_url = G5_HTTPS_MSHOP_URL.'/orderformupdate.php';
    require_once(G5_MSHOP_PATH.'/orderform.sub2.php');
} else {
    $order_action_url = G5_HTTPS_SHOP_URL.'/orderformupdate.php';
    require_once('./orderform.sub2.php');
}
