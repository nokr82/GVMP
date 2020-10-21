<?php
include_once($DOCUMENT_ROOT.'/vexMall/back/dbConn.php'); // DB Server
include_once('./_common.php');

//if (!$is_member)
//    goto_url(G5_BBS_URL."/login.php");
//풀지말것

define("_INDEX_", TRUE);

include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');


$mb_id = $_GET['mb_id'];

//1검수 2 승인 3 거절
$rowf = sql_fetch_array(sql_query(" SELECT count(*) as cnt FROM Receipt WHERE vdMapidx = (SELECT n FROM vdMapInfoTBL WHERE mb_id='{$mb_id}') and state = '1'"));
$rows = sql_fetch_array(sql_query("SELECT count(*) as cnt FROM Receipt WHERE vdMapidx = (SELECT n FROM vdMapInfoTBL WHERE mb_id='{$mb_id}') and state = '2'"));
$rowt = sql_fetch_array(sql_query(" SELECT count(*) as cnt FROM Receipt WHERE vdMapidx = (SELECT n FROM vdMapInfoTBL WHERE mb_id='{$mb_id}') and state = '3'"));


$rowp = sql_fetch_array(sql_query("SELECT IFNULL(SUM(billSum),0) AS SUM FROM Receipt WHERE vdMapidx = (SELECT n FROM vdMapInfoTBL WHERE mb_id='{$mb_id}') AND state = '2'")); //총매출
$rowu = sql_fetch_array(sql_query("SELECT cashback FROM vdMapInfoTBL WHERE mb_id='{$mb_id}'"));
$cashback = (int)$rowu['cashback'] / 100;
$cashbackPer  = $rowu['cashback'];


$totalPrice = $rowp['SUM'];
$totalSusu = $totalPrice * 0.05;
$totalCashback = $totalPrice * $cashback;
?>
<!--<link rel="stylesheet" href="<?=G5_THEME_CSS_URL?>/jquery.bxslider.css">
<script src="<?=G5_THEME_JS_URL?>/jquery.bxslider.js"></script>
<script src="<?=G5_THEME_JS_URL?>/index.js"></script>-->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.0/css/all.css">

<form id="billStoreDetailForm" enctype='multipart/form-data' action='#.php' method='post'>
<input type="hidden" name="mb_id" id="mb_id" value="<?=$mb_id?>">
<div id="billStoreDetail">
    <ul class="detail_state">
        <li class="clearfix">
            <img src="<?=G5_THEME_IMG_URL?>/bill/vroad-toggle-bill-icon1.svg" alt="정산완료영수증">
            <p>정산완료 영수증: <?=$rows['cnt']?></p>
            <a href="#" class="detailView" alt="2">자세히보기</a>
        </li>
        <li class="clearfix">
            <img src="<?=G5_THEME_IMG_URL?>/bill/vroad-toggle-bill-icon2.svg" alt="거절영수증">
            <p>거절 영수증: <?=$rowt['cnt']?></p>
            <a href="#" class="detailView" alt="3">자세히보기</a>
        </li>
        <li class="clearfix">
            <img src="<?=G5_THEME_IMG_URL?>/bill/vroad-toggle-bill-icon3.svg" alt="검수중 영수증">
            <p>검수중 영수증: <?=$rowf['cnt']?></p>
            <a href="#" class="detailView" alt="1">자세히보기</a>
        </li>
    </ul>
    <ul class="detail_info">
        <li>
            <h3>총매출</h3>
            <p><?=number_format($totalPrice)?>원</p>
        </li>
        <li>
            <h3>총수수료(5%)</h3>
            <p><?=number_format($totalSusu)?>원 </p>
        </li>
        <li>
            <h3>총캐시백(<?=$cashbackPer?>%)</h3>
            <p><?=number_format($totalCashback)?>원</p>
        </li>
    </ul>
</div>
</form>
<script type="text/javascript">
<!--
$(function(){
	$(".detailView").on("click",function(){
		var state=$(this).attr("alt");
		location.href="./bill_store_list.php?mb_id=<?=$mb_id?>&state="+state;
	});
});
//-->
</script>
