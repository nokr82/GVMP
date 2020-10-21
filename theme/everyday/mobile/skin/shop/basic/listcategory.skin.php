<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$str = '';
$ownstr = '';
$exists = false;

if(strlen($ca_id)>8){
	$ca_id = "90";
}	
$ca_id2 = substr($ca_id, 0, 2); //팝업스토어 서브페이지에 공통목록(서브메뉴목록, 타이틀이미지) 출력 
$ca_id_len = strlen($ca_id2);
$len2 = $ca_id_len + 2;
$len4 = $ca_id_len + 4;


$sql = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where ca_id like '$ca_id2%' and length(ca_id) = $len2 and ca_use = '1' order by ca_order, ca_id ";
$result = sql_query($sql);
while ($row=sql_fetch_array($result)) {
    $row2 = sql_fetch(" select count(*) as cnt from {$g5['g5_shop_item_table']} where (ca_id like '{$row['ca_id']}%' or ca_id2 like '{$row['ca_id']}%' or ca_id3 like '{$row['ca_id']}%') and it_use = '1'  ");
    $str .= '<li><a href="./list.php?ca_id='.$row['ca_id'].'" class="btn_hover box">'.$row['ca_name'].' <span>'.$row2['cnt'].'</span></a></li>';
    $exists = true;
}
if($ca_id=="90"||strlen($ca_id)>10||substr($ca_id,0,2)=="90"){
	$str .= '<li><a href="#none" class="btn_hover box onwner">쇼핑몰</a></li>';
}



$sql = "SELECT own_key AS own_key, own_name FROM `ownerclean_category` WHERE own_key LIKE '%00000000' ORDER BY own_key ASC";
$result = sql_query($sql);
while ($row=sql_fetch_array($result)) {
    $ownstr .= '<li><a href="./list.php?ca_id='.$row['own_key'].'" class="btn_hover box">'.$row['own_name'].'</a></li>';
}

if ($exists) {
    // add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
    add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_CSS_URL.'/style.css">', 0);
?>
<style>
.vmmall_banner {width:100%;}
@media only screen and (min-width:801px) {
    .vmmall_banner.desktop {display:block;}
    .vmmall_banner.mobile {display:none;}
}
@media only screen and (max-width:800px) {
.vmmall_banner.mobile {display:block;}
    .vmmall_banner.desktop {display:none;}
}


</style>
<!-- 상품분류 1 시작 { -->
<!--<img src="/theme/everyday/img/vmp_pop_store1212_02.png" style="width:100%;">-->
    <img class="vmmall_banner desktop" src="/theme/everyday/mobile/skin/shop/basic/img/vmmall_200225.jpg" alt="VMP vm몰 (벤더사 입점 문의:010-2956-2109)">
    <img class="vmmall_banner mobile" src="/theme/everyday/mobile/skin/shop/basic/img/vmmall_200225_m.jpg" alt="VMP vm몰 (벤더사 입점 문의:010-2956-2109)">

<aside id="sct_ct_1" class="sct_ct">
    <h2>현재 상품 분류와 관련된 분류</h2>
    <ul>
        <?php echo $str; ?>
    </ul>
	<ul class="onwnercate" style="width:700px;margin:0 auto;<?if(strlen($_GET['ca_id'])<8){?>display:none;<?}?>">
        <?php echo $ownstr; ?>
	</ul>
</aside>
<!-- } 상품분류 1 끝 -->

<?php } ?>

<script type="text/javascript">
<!--
$(".onwner").on("click",function(){
	$(".onwnercate").toggle();
});
//-->
</script>