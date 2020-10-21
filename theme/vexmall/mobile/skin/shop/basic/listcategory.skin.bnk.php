<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
$str = '';
$exists = false;

$ca_id2 = substr($ca_id, 0, 2); //팝업스토어 서브페이지에 공통목록(서브메뉴목록, 타이틀이미지) 출력 


if(!$ca_id2)$ca_id2="90";

if(strlen($ca_id)>8){
	$ca_id2 = "90";
}	

$ca_id_len = strlen($ca_id2);
$len2 = $ca_id_len + 2;
$len4 = $ca_id_len + 4;
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_CSS_URL.'/style.css">', 0);
$allstate="";
if($_GET['ca_id']!="90")$allstate="not-";
?>
<!-- 상품분류 1 시작 { -->
<div id="storeTab">
    <div class="tab_list">
        <ul class="clearfix topiconmenu">
            <!--li class="list_all">
                <a href="/shop/list.php?ca_id=90">
                    <img src="http://gvmp.company/theme/vexmall/img/store_serch/vroad-store_<?=$allstate?>hover6.svg" alt="asd" title="">
                </a>
            </li-->
<?
$etcimgstate = "not-";
$sql = " select ca_id, ca_name, imagePath from {$g5['g5_shop_category_table']} where ca_id like '$ca_id2%' and length(ca_id) = $len2 and ca_use = '1' order by ca_order, ca_id ";

//echo $sql . "<br>";
//echo exit;
$result = sql_query($sql);

$url = "http://gvmp.company/vexMall/images/category/";
$i=1;
while ($row=sql_fetch_array($result)) {

    $row2 = sql_fetch(" select count(*) as cnt from {$g5['g5_shop_item_table']} where (ca_id like '{$row['ca_id']}%' or ca_id2 like '{$row['ca_id']}%' or ca_id3 like '{$row['ca_id']}%') and it_use = '1'  ");

    $str .= '<li><a href="./list.php?ca_id='.$row['ca_id'].'" class="btn_hover box">'.$row['ca_name'].' <span>'.$row2['cnt'].'</span></a></li>';
    $exists = true;
	$imgstate="";
	if($ca_id!=$row['ca_id'])$imgstate="not-";
?>
            <li>
                <a href="/shop/list.php?ca_id=<?=$row['ca_id']?>&sort=<?=$_GET['sort']?>&sortodr=<?=$_GET['sortodr']?>">
                    <img src="http://gvmp.company/theme/vexmall/img/store_serch/vroad-store_<?=$imgstate?>hover<?=$i?>.svg" alt="<?=$row['ca_name']?>" title="" class="topIconMenu">
                    <p><?=$row['ca_name']?></p>
                </a>
            </li>
<?
	$i++;
}
if(strlen($_GET['ca_id'])>8){
	$etcimgstate="";
}
?> 

            <li>
                <a href="#none" class="onwner">
                    <img src="/theme/vexmall/img/store_serch/vroad-store_<?=$etcimgstate?>hover7.svg" alt="" title="">
                </a>
            </li>
        </ul>
<?
$sql = "SELECT own_key AS own_key, own_name FROM `ownerclean_category` WHERE own_key LIKE '%00000000' ORDER BY own_key ASC";
$result = sql_query($sql);
while ($row=sql_fetch_array($result)) {
//    $ownstr .= '<li><a href="./list.php?ca_id='.$row['own_key'].'" class="btn_hover box">'.$row['own_name'].'</a></li>';
    $ownstr .= '<option ';
	if($_GET['ca_id']==$row['own_key']){
		$ownstr .= ' selected ';
	}
	$ownstr .= ' value="'.$row['own_key'].'">'.$row['own_name'].'</option>';
}
?>
		<ul class="clearfix onwnercate" style="margin:0 auto;<?if(strlen($_GET['ca_id'])<8){?>display:none;<?}?>">
			<select name="onwnerCateName" id="onwnerCateName" style="width:100%;height:40px">
				<option>:::선택하세요:::</option>
			<?php echo $ownstr; ?>
			</select>
		</ul>
    </div>
</div>
<!-- } 상품분류 1 끝 -->
<script type="text/javascript">
<!--
$(".onwner").on("click",function(){
	$.each($(".topIconMenu"),function(obj,key){
		var NUM = obj+1;
		$(this).attr("src","/theme/vexmall/img/store_serch/vroad-store_not-hover"+NUM+".svg");
	});
	$(".shopSubMenu li").html("");
	$(".shopSubMenu li").removeClass("on");

	$(".onwnercate").toggle();
	var IMGSRC = "/theme/vexmall/img/store_serch/vroad-store_";
	if($(".onwnercate").is(":visible")) {
		IMGSRC += "hover7.svg";
	}else{
		IMGSRC += "not-hover7.svg";
	}
	$(".onwner > img").attr("src",IMGSRC);
});

$("#onwnerCateName").on("change",function(){
	location.href="./list.php?ca_id="+$("#onwnerCateName option:selected").val();
});
//-->
</script>