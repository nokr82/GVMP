<?php
define('G5_IS_ADMIN', true);
define('G5_IS_SHOP_ADMIN_PAGE', true);
include_once ('../../common.php');
$request_uri = $_SERVER['REQUEST_URI'];
include_once(G5_ADMIN_PATH.'/admin.lib.php');
include_once('./admin.shop.lib.php');

?>