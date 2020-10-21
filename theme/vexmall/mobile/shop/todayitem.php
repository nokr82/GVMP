<?
include_once('./_common.php');

$g5['title'] = '최근본상품';

include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');

$tv_idx = get_session("ss_tv_idx");
?>
<div id="itemList" class="resultList">
	<ul>
<?
for ($i=1;$i<=$tv_idx;$i++)
{
	$tv_it_idx = $tv_idx - ($i - 1);
	$tv_it_id = get_session("ss_tv[$tv_it_idx]");

	$rowx = sql_fetch(" select * from {$g5['g5_shop_item_table']} where it_id = '$tv_it_id' ");
	if(!$rowx['it_id'])
	continue;

	if ($tv_tot_count % $tv_div['img_length'] == 0) $k++;

	$it_name = get_text($rowx['it_name']);
?>
	<li>
		<a href="/shop/item.php?it_id=<?=$rowx['it_id']?>" class="clearfix">
			<div class="list_img">
				<?=get_app_it_image($rowx['it_id'],340, 308, '', '', stripslashes($rowx['it_name']))?>
			</div>
			<div class="list_text">
				<h3><?=$it_name?></h3>
				<p class="pay"><?=display_price(get_price($rowx), $rowx['it_tel_inq'])?><!--span class="sale">17,330원</span--></p>
				<p class="pay_2">무료배송</p>
			</div>
		</a>
	</li>

<?
	$tv_tot_count++;
}
if(!$tv_tot_count){
?>
<li class="empty_list">
    <img src="<?=G5_THEME_IMG_URL?>/event/vroad_alert.svg" alt="경고표시" />
    <p>최근본 상품이 없습니다.</p>
</li>
<?
}
?>
	</ul>
</div>