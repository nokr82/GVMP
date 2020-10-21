<?php
include_once($DOCUMENT_ROOT.'/vexMall/back/dbConn.php'); // DB Server
include_once('./_common.php');

//if (!$is_member)
//    goto_url(G5_BBS_URL."/login.php");
//풀지말것

define("_INDEX_", TRUE);

include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');



$sql = " select * from {$g5['g5_shop_category_table']} where ca_id = '$ca_id' and ca_use = '1'  ";
$ca = sql_fetch($sql);
?>
<!--<link rel="stylesheet" href="<?=G5_THEME_CSS_URL?>/jquery.bxslider.css">
<script src="<?=G5_THEME_JS_URL?>/jquery.bxslider.js"></script>
<script src="<?=G5_THEME_JS_URL?>/index.js"></script>-->



<div id="serchResult">
    <ul class="result_state clearfix">        
        <li>
            <a href="#" class="on">최근 검색어</a>
        </li>
        <li>
            <a href="#">인기 검색어</a>
        </li>
    </ul>
    <ul class="recent_result">
        <li class="show_result">
            <a href="#">검색 결과 입니다.</a>
        </li>
        <li class="show_result">
            <a href="#">검색 결과 입니다.</a>
        </li>
        <li class="show_result">
            <a href="#">검색 결과 입니다.</a>
        </li>
        <li class="none_result">
            <img src="<?=G5_THEME_IMG_URL?>/event/vroad_alert.svg" alt="경고표시" />
            <a href="#">최근 검색어가 없습니다.</a>
        </li>
        <li class="del_result">            
            <a href="#">검색 기록 삭제</a>
        </li>
    </ul>
    <ul class="best_result">
        <li>
            <a href="#"><span class="best">1</span>검색 결과 입니다.</a>
        </li>
        <li>
            <a href="#"><span class="best">2</span>검색 결과 입니다.</a>
        </li>
        <li>
            <a href="#"><span class="best">3</span>검색 결과 입니다.</a>
        </li>
         <li>
            <a href="#"><span>4</span>검색 결과 입니다.</a>
        </li>
        <li>
            <a href="#"><span>5</span>검색 결과 입니다.</a>
        </li>
        <li>
            <a href="#"><span>6</span>검색 결과 입니다.</a>
        </li>
         <li>
            <a href="#"><span>7</span>검색 결과 입니다.</a>
        </li>
        <li>
            <a href="#"><span>8</span>검색 결과 입니다.</a>
        </li>
        <li>
            <a href="#"><span>9</span>검색 결과 입니다.</a>
        </li>
        <li>
            <a href="#"><span>10</span>검색 결과 입니다.</a>
        </li>
    </ul>
</div>










<?php
include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>