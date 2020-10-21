<?php
include_once($DOCUMENT_ROOT.'/vexMall/back/dbConn.php'); // DB Server
include_once('./_common.php');

//if (!$is_member)
//    goto_url(G5_BBS_URL."/login.php");
//풀지말것

define("_INDEX_", TRUE);

include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');

$mb_id = $_GET['mb_id'];
$idx = $_GET['idx'];

$row = sql_fetch_array(sql_query(" SELECT * FROM Receipt WHERE idx='{$idx}'"));
$crow = sql_fetch_array(sql_query(" SELECT cashback FROM vdMapInfoTBL WHERE mb_id='{$mb_id}'"));
$cashback = (int)$crow['cashback'] / 100;

$totalPrice = $row['billSum'];
$totalSusu = $totalPrice * 0.05;
$totalCashback = $totalPrice * $cashback;
?>
<link rel="stylesheet" href="<?=G5_THEME_CSS_URL?>/jquery.bxslider.css">
<script src="<?=G5_THEME_JS_URL?>/jquery.bxslider.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.0/css/all.css">

<div id="billStoreInfo">
    <div class="slider_box">
        <ul class="bxslider">
            <li>
                <img src="<?=$row['billFile']?>" alt="영수증사진">
            </li>
        </ul>
    </div>    
    <ul class="detail_info">
        <li>
            <h3>날짜</h3>
            <p><?=substr($row['date'],0,10)?></p>
        </li>
        <li>
            <h3>금액</h3>
            <p><?=number_format($totalPrice)?>원</p>
        </li>
        <li>
            <h3>수수료</h3>
            <p><?=number_format($totalSusu)?>원</p>
        </li>
        <li>
            <h3>캐시백</h3>
            <p><?=number_format($totalCashback)?>원</p>
        </li>
    </ul>
</div>


<script>
//    $(document).ready(function(){
//      $('.bxslider').bxSlider({
//          pager: true,
//          pagerType: 'short'
//      });
//    });
</script>