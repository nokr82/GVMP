<?php
include_once($DOCUMENT_ROOT.'/vexMall/back/dbConn.php'); // DB Server
include_once('./_common.php');
session_start();


if($mb_id && $member['mb_id']!=$mb_id) {
	$mb = sql_fetch("select * from {$g5['member_table']} where mb_id = '{$mb_id}'");
	set_session('ss_mb_password', $mb['mb_password']);
	set_session('ss_mb_id', $mb['mb_id']);
	set_session('ss_mb_key', md5($mb['mb_datetime'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']));
}

if (!$is_member) {
	$mb = sql_fetch("select * from {$g5['member_table']} where mb_id = '{$mb_id}'");
	set_session('ss_mb_password', $mb['mb_password']);
	set_session('ss_mb_id', $mb['mb_id']);
	set_session('ss_mb_key', md5($mb['mb_datetime'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']));
}




define("_INDEX_", TRUE);

include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');
?>
<link rel="stylesheet" href="<?=G5_THEME_CSS_URL?>/jquery.bxslider.css">
<script src="<?=G5_THEME_JS_URL?>/jquery.bxslider.js"></script>
<script src="<?=G5_THEME_JS_URL?>/index.js"></script>



<div id="mainBanner">
    <ul class="bxslider">
        <li><img src="<?=G5_THEME_IMG_URL?>/index/main_banner_0620_1.png" alt="메인광고이미지" /></li>
        <li><img src="<?=G5_THEME_IMG_URL?>/index/main_banner_0620_2.png" alt="메인광고이미지" /></li>
        <li><img src="<?=G5_THEME_IMG_URL?>/index/main_banner_0620_3.png" alt="메인광고이미지" /></li>
    </ul>
</div>

<div id="venderMall">
    <h2>vendor mall</h2>
    <ul class="clearfix">
<?
    $url = "http://gvmp.company/vexMall/images/category/";
//	echo "select * from g5_shop_category where ca_id like '90__' and vexMallCheck = 'Y' order by ca_order" . "<br>";
    $result = mysql_query("select * from g5_shop_category where ca_id like '90__' and vexMallCheck = 'Y' order by ca_order");
	$bcnt = 0;
    while( $row = mysql_fetch_array($result) ) {
?>
        <li>
            <a href="/shop/list.php?ca_id=<?=$row['ca_id']?>">
                <img src="<?=G5_THEME_IMG_URL?>/index/vroad-main-icon-<?=str_replace(".png",".svg",$row['imagePath']);?>" alt="<?=$row["ca_id"]?>" />
                <p><?=$row['ca_name']?></p>
            </a>
        </li>
<?
		$bcnt++;
    }
	if ($bcnt<10) {
		for ($i=$bcnt;$i<10;$i++) {
?>
        <li>
            <img src="<?=G5_THEME_IMG_URL?>/index/vroad-main-logo.svg" alt="asd" />
        </li>
<?
		}
	}
?>
    </ul>
</div>

<div id="btmBanner">
    <img src="<?=G5_THEME_IMG_URL?>/index/vroad-main-mini-banner.png" alt="메인광고이미지" />
</div>











<?php
include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>