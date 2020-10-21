<?php
include_once('./_common.php');


if(defined('G5_THEME_PATH')) {
	if(file_exists(G5_THEME_PATH.'/mobile/shop/eventdetail.php')){
		require_once(G5_THEME_PATH.'/mobile/shop/eventdetail.php');
		return;
	}
}

?>