<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.G5_MSHOP_CSS_URL.'/style.css">', 0);
// 이지양, 이정현 CSS 찾기한 곳 2019-05-20

?>
<div id="listNav" class="clearfix">
    <ul class="clearfix shopSubMenu">
        <!--리스트에 클래스 on 이 붙으면 활성화-->
        <li <?if(strlen($_GET['ca_id'])=="4"){?> class="on"<?}?>>
            <a href="?ca_id=<?=substr($_GET['ca_id'],0,4)?>">전체</a>
        </li>
<?
$result = sql_query(" SELECT * FROM {$g5['g5_shop_category_table']} WHERE left(ca_id,4) = '".substr($_GET['ca_id'],0,4)."' AND LENGTH(ca_id)<>'4' AND ca_use = '1' ORDER BY ca_order ASC ");


if($ca_id!="90"){
	while ($row=sql_fetch_array($result)) {
?>
        <li <?if($_GET['ca_id']==$row['ca_id']){?> class="on"<?}?>>
            <a href="?ca_id=<?=$row['ca_id']?>"><?=$row['ca_name']?></a>
        </li>
<?
	}
}
?>
        <!--li class="on">
            <a href="#">테스트트</a>
        </li--> 
    </ul>
    <!--div에 클래스 on 이 붙으면 활성화-->
    <div class="liststyle_list on"></div>
    <div class="liststyle_pic"></div>
</div>
<!--ul id="sct_lst">
    <li><button type="button" class="sct_lst_view sct_lst_list"><i class="fa fa-th-list" aria-hidden="true"></i><span class="sound_only">리스트뷰</span></button></li>
    <li><button type="button" class="sct_lst_view sct_lst_gallery"><i class="fa fa-th-large" aria-hidden="true"></i><span class="sound_only">갤러리뷰</span></button></li>
</ul-->

<?if(strlen($ca_id)>8){?>
<script type="text/javascript">
<!--
window.onload=function(){
	$(".shopSubMenu li").html("");
	$(".shopSubMenu li").removeClass("on");
}
//-->
</script>

<?}?>