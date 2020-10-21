<?php
include_once('../common.php');
//동우추가 비로그인시 VM몰 볼수없게 01-22일
//수정 추가 검색엔진에 검색안되게
if($is_guest)// 로그인 안 했을 때 로그인 페이지로 이동
    header('Location: /bbs/login.php');
//echo '<meta name="robots" content="noindex, nofollow">';


if (isset($_REQUEST['sort']))  {
    $sort = trim($_REQUEST['sort']);
    $sort = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\s]/", "", $sort);
} else {
    $sort = '';
}

if (isset($_REQUEST['sortodr']))  {
    $sortodr = preg_match("/^(asc|desc)$/i", $sortodr) ? $sortodr : '';
} else {
    $sortodr = '';
}

if (!defined('G5_USE_SHOP') || !G5_USE_SHOP)
    die('<p>쇼핑몰 설치 후 이용해 주십시오.</p>');

define('_SHOP_', true);
?>