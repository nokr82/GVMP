<?php
include_once('../common.php');

// 커뮤니티 사용여부
if(G5_COMMUNITY_USE === false) {
    if (!defined('G5_USE_SHOP') || !G5_USE_SHOP)
        die('<p>쇼핑몰 설치 후 이용해 주십시오.</p>');

    define('_SHOP_', true);
}
//asdasdsadsaasd
if($is_guest)// 로그인 안 했을 때 로그인 페이지로 이동
    header('Location: /bbs/login.php');


    function imagePath( $rank ) {
        switch( $rank ) {
            case "VM" :
                return "./images/VM.png"; break;
            case "MASTER" :
                return "./images/M_1.png"; break;
            case "Double MASTER" :
                return "./images/M_2.png"; break;
            case "Triple MASTER" :
                return "./images/M_3.png"; break;
            case "1 STAR" :
                return "./images/S_1.png"; break;
            case "2 STAR" :
                return "./images/S_2.png"; break;
            case "3 STAR" :
                return "./images/S_3.png"; break;
            case "4 STAR" :
                return "./images/S_4.png"; break;
            case "5 STAR" :
                return "./images/S_5.png"; break;
            case "AMBASSADOR" :
                return "./images/A_1.png"; break;
            case "Double AMBASSADOR" :
                return "./images/A_2.png"; break;
            case "Triple AMBASSADOR" :
                return "./images/A_3.png"; break;
            case "Crown AMBASSADOR" :
                return "./images/A_4.png"; break;
            case "Royal Crown AMBASSADOR" :
                return "./images/A_5.png"; break;
        }
    }
$base_filename = basename($_SERVER['PHP_SELF']);

if(trim($base_filename)!="index2.php"&&trim($base_filename)!="point_pop_back.php" && trim($base_filename)!="recipt.php" && ! isset($cssCheck)){
?>
<link rel="stylesheet" href="http://gvmp.company/theme/everyday/css/mobile_shop2.css">
<?php
}
?>