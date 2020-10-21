<?=$tabswitch?><?php
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
<div class="loading_msg" style="display: none">
    <img src="/theme/vexmall/mobile/skin/shop/basic/img/loading_img.svg" alt="로딩중입니다">
    <p>로딩중입니다.</p>
</div>
<div id="storeTab">
    <div class="tab_list">
        <ul class="clearfix topiconmenu">
<?php
$etcimgstate = "not-";
$metcimgstate = "not-";
$tabswitch = "class='tab_bar on'";
$sql = " select ca_id, ca_name, imagePath from {$g5['g5_shop_category_table']} where ca_id like '$ca_id2%' and length(ca_id) = $len2 and ca_use = '1' order by ca_order, ca_id ";
$result = sql_query($sql);
$url = "http://gvmp.company/vexMall/images/category/";
$i=1;
while ($row=sql_fetch_array($result)) {

    $row2 = sql_fetch(" select count(*) as cnt from {$g5['g5_shop_item_table']} where (ca_id like '{$row['ca_id']}%' or ca_id2 like '{$row['ca_id']}%' or ca_id3 like '{$row['ca_id']}%') and it_use = '1'  ");

    $str .= '<li><a href="./list.php?ca_id='.$row['ca_id'].'" class="btn_hover box">'.$row['ca_name'].' <span>'.$row2['cnt'].'</span></a></li>';
    $exists = true;
    $imgstate="";
	
    $mownstr .= '<option ';
	if($ca_id!=$row['ca_id'])$imgstate="not-";

	if($_GET['ca_id']==$row['ca_id']){
		$mownstr .= ' selected ';
	}
	$mownstr .= ' value="'.$row['ca_id'].'">'.$row['ca_name'].'</option>';
	$i++;
}
if(strlen($_GET['ca_id'])>8){
	$etcimgstate="";
	$listKind = "1";
}else{
        $tabswitch = "class='tab_bar'";  
	$metcimgstate="";
	$listKind = "2";
}
?> 
            <li>
                <a href="#none" class="onwner" alt="8" onclick="tabSwitch(8)">
                    <img id="icon8" src="/theme/vexmall/img/store_serch/vroad-store_<?=$metcimgstate?>hover8.svg" alt="" title="">
                </a>
            </li>
            <li <?=$tabswitch?>>
                <img src="/theme/vexmall/img/store_serch/vroad-store_swich_on.svg" alt="아이콘">
            </li>
            <li>
                <a href="#none" class="onwner" alt="9" onclick="tabSwitch(9)">
                    <img id="icon9" src="/theme/vexmall/img/store_serch/vroad-store_<?=$etcimgstate?>hover9.svg" alt="" title="">
                </a>
            </li>
        </ul>
<?php
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
		<ul class="clearfix onwnercate icon8sel" style="margin:0 auto;<?if(strlen($_GET['ca_id'])>8){?>display:none;<?}?>">
                    <select class="onwnerCateName catelist1" name="onwnerCateName" id="onwnerCateName" style="width:100%;height:40px">
				<option>:::선택하세요:::</option>
			<?php echo $mownstr; ?>
			</select>
		</ul>
		<ul class="clearfix onwnercate icon9sel" style="margin:0 auto;<?if(strlen($_GET['ca_id'])<8){?>display:none;<?}?>">
                    <select class="onwnerCateName catelist2" name="onwnerCateName" id="onwnerCateName" style="width:100%;height:40px">
                            <option>:::선택하세요:::</option>
                    <?php echo $ownstr; ?>
                    </select>
		</ul>
    </div>
</div>
<script>
 //  스토어 탭, 카테고리 스위치 효과

function tabSwitch(obj){
    if(obj == 9){
          $('.tab_bar').addClass('on');
    } else {
          $('.tab_bar').removeClass("on");
    }
}
</script>
<!-- } 상품분류 1 끝 -->
<script type="text/javascript">
<!--
$(document).ready(function(){
	var kindvalue = "<?=$listKind?>";
	$(".catelist"+kindvalue).trigger("focus");
	setTimeout(function(){
		$(".catelist"+kindvalue).trigger("click");
	},800);
});
$(".onwner").on("click",function(){
	var NUM = $(this).attr("alt");
	$("#icon8").attr("src","/theme/vexmall/img/store_serch/vroad-store_not-hover8.svg");
	$("#icon9").attr("src","/theme/vexmall/img/store_serch/vroad-store_not-hover9.svg");

	$("#icon"+NUM).attr("src","/theme/vexmall/img/store_serch/vroad-store_hover"+NUM+".svg");
	$(".onwnercate").hide();
	$(".icon"+NUM+"sel").show();
});

$(".onwnerCateName").on("change",function(){
//	location.href="./list.php?ca_id="+$("#onwnerCateName option:selected").val();
	location.href="./list.php?ca_id="+$("option:selected", this).val();
        $('.loading_msg').css('display', 'block'); // 스토어 카테고리 클릭시 로딩메세지 띄우기 - 이지양 19-05-20
});
//-->

</script>