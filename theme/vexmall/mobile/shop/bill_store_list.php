<?php
include_once($DOCUMENT_ROOT.'/vexMall/back/dbConn.php'); // DB Server
include_once('./_common.php');

//if (!$is_member)
//    goto_url(G5_BBS_URL."/login.php");
//풀지말것

define("_INDEX_", TRUE);

include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');


$mb_id = $_GET['mb_id'];
$state = $_GET['state'];

$sql = " SELECT * FROM Receipt WHERE vdMapidx = (SELECT n FROM vdMapInfoTBL WHERE mb_id='{$mb_id}') and state = '{$state}'";
$result = sql_query($sql);
$total_rows = sql_num_rows($result);
?>
<!--<link rel="stylesheet" href="<?=G5_THEME_CSS_URL?>/jquery.bxslider.css">
<script src="<?=G5_THEME_JS_URL?>/jquery.bxslider.js"></script>
<script src="<?=G5_THEME_JS_URL?>/index.js"></script>-->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.0/css/all.css">

<div id="billStoreList">
    <ul class="detail_list">
<?if($total_rows>0){?>
<?	while($row=sql_fetch_array($result)){?>
        <li class="detailView" alt="<?=$row['idx']?>">
            <p class="date clearfix">날짜<span><?=substr($row['date'],0,10)?></span></p>
            <p class="pay clearfix">금액<span><?=number_format($row['billSum'])?>원</span></p>
        </li>
<?	}?>
<?}else{?>
        <li>
            <p class="date clearfix">등록된 영수증이 없습니다.</p>
        </li>
<?}?>
    </ul>
</div>

<script type="text/javascript">
<!--
$(function(){
	$(".detailView").on("click",function(){
		var state=$(this).attr("alt");
		location.href="./bill_store_info.php?mb_id=<?=$mb_id?>&idx="+state;
	});
});
//-->
</script>

