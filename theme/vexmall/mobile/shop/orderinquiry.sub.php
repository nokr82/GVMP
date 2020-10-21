<?php

$member = sql_fetch("select * from {$g5['member_table']} where mb_id = '{$mb_id}'");
$row1 = sql_fetch(" select count(*) as cnt from {$g5['g5_shop_order_table']} where mb_id = '{$member['mb_id']}' and od_status = '입금'"); //결제완료
$row2 = sql_fetch(" select count(*) as cnt from {$g5['g5_shop_order_table']} where mb_id = '{$member['mb_id']}' and (od_status = '배송' or od_status = '완료')"); //배송중
$row3 = sql_fetch(" select count(*) as cnt from {$g5['g5_shop_order_table']} where mb_id = '{$member['mb_id']}' and od_status = '취소'"); //취소
//$result = sql_query($sql);
//for ($i=0; $row=sql_fetch_array($result); $i++)
?>
<div id="mypageOrder">
    <ul class="order_state clearfix">
        <li>
            <h2>결제확인/완료</h2>
            <p><?=$row1['cnt']?></p>
        </li>
        <li>
            <h2>배송중/완료</h2>
            <p><?=$row2['cnt']?></p>
        </li>
        <li>
            <h2>취소</h2>
            <p><?=$row3['cnt']?></p>
        </li>
    </ul>
<?
$sql = " select *
		   from {$g5['g5_shop_order_table']}
		  where mb_id = '{$member['mb_id']}'
		  order by od_id desc
		  $limit ";
$result = sql_query($sql);

$totalcount=sql_num_rows($result);
if($totalcount==0) {
?>
    <div class="none_alert">
        <img src="<?=G5_THEME_IMG_URL?>/event/vroad_alert.svg" alt="경고표시" />
        <p>주문배송 내역이 없습니다.</p>
    </div>
<?
}else{
	for ($i=0; $row=sql_fetch_array($result); $i++)
    {
		// 주문상품
		$sql = " select it_name, ct_option, it_id 
					from {$g5['g5_shop_cart_table']}
					where od_id = '{$row['od_id']}'
					order by io_type, ct_id
					limit 1 ";
		$ct = sql_fetch($sql);
		$ct_name = get_text($ct['it_name']).' '.get_text($ct['ct_option']);

		$sql = " select count(*) as cnt
					from {$g5['g5_shop_cart_table']}
					where od_id = '{$row['od_id']}' ";
		$ct2 = sql_fetch($sql);
		if($ct2['cnt'] > 1)
			$ct_name .= ' 외 '.($ct2['cnt'] - 1).'건';
		
		$uid = md5($row['od_id'].$row['od_time'].$row['od_ip']);



        switch($row['od_status']) {
            case '주문':
                $od_status = '<span class="status_01">입금확인중</span>';
                break;
            case '입금':
                $od_status = '<span class="status_02">입금완료</span>';
                break;
            case '준비':
                $od_status = '<span class="status_03">상품준비중</span>';
                break;
            case '배송':
                $od_status = '<span class="status_04">상품배송</span>';
                break;
            case '완료':
                $od_status = '<span class="status_05">배송완료</span>';
                break;
            default:
                $od_status = '<span class="status_06">주문취소</span>';
                break;
        }

		$od_invoice = '';
		if($row['od_delivery_company'] && $row['od_invoice']) {

			
			if($row['od_delivery_company']=="로젠택배") {
				$od_invoice = '<span class="inv_inv"><i class="fa fa-truck" aria-hidden="true"></i> <strong><a href="https://www.ilogen.com/web/personal/trace/'.get_text($row['od_invoice']).'" target="_blank">'.get_text($row['od_delivery_company']).'</a></strong> '.get_text($row['od_invoice']).'</span>';
			}else if($row['od_delivery_company']=="한진택배") {
				$od_invoice = '<span class="inv_inv"><i class="fa fa-truck" aria-hidden="true"></i> <strong><a href="http://www.hanjin.co.kr/Delivery_html/inquiry/result_waybill.jsp?wbl_num='.get_text($row['od_invoice']).'" target="_blank">'.get_text($row['od_delivery_company']).'</a></strong> '.get_text($row['od_invoice']).'</span>';
			}
		}


?>		
    <div class="order_list">
        <h2 class="order_date"><?=str_replace("-",".",substr($row['od_time'],0,10))?></h2>
        <ul>
            <li class="clearfix">
                <div class="clearfix">
                    <div class="order_text">
						<input type="hidden" name="ct_id[<?php echo $i; ?>]" value="<?php echo $row['ct_id']; ?>">
						<h3><a href="<?php echo G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $row['od_id']; ?>&amp;uid=<?php echo $uid; ?>"><?php echo $row['od_id']; ?></a></h3>
                        <h3><a href="<?php echo G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $row['od_id']; ?>&amp;uid=<?php echo $uid; ?>"><?=$ct_name; ?></a></h3>
                        <p class="order_pay"><a href="<?php echo G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $row['od_id']; ?>&amp;uid=<?php echo $uid; ?>"> <?php echo display_price($row['od_receipt_price']); ?></p></a>
<?if($row['od_receipt_point']){?>
                        <p class="order_pay"> 포인트 결제 : <?php echo display_price($row['od_receipt_point']); ?></p>
<?}?>
                        <p class="order_stat"><?=$od_status?></p>
						<p class="order_number"><?php echo $row['od_delivery_company']; ?> <?php echo get_delivery_inquiry($row['od_delivery_company'], $row['od_invoice'], 'dvr_link'); ?></p>
                    </div>
                </div>     
						    
                <div class="order_btn clearfix">
                    <a href="./itemuseform.php?it_id=<?=$ct['it_id']?>&return=back" class="order_review_s qa_wr itemuse_form " onclick="return false;">구매후기 쓰기</a>
                    <?if($row['od_status']=="주문"){?><a href="#" class="order_cancel">주문 취소</a><?}?>
                </div>
            </li>            
        </ul>
    </div>
<?}?>
    <!--div class="order_list">
        <h2 class="order_date">2019.02</h2>
        <ul>
            <li class="clearfix">
                <div class="clearfix" onclick="test();"> 
                    <div class="order_img">
					 <input type="hidden" name="ct_id[<?php echo $i; ?>]" value="<?php echo $row['ct_id']; ?>">
			            <a href="<?php echo G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $row['od_id']; ?>&amp;uid=<?php echo $uid; ?>"><?php echo $row['od_id']; ?></a>
                       <img src="<?=G5_THEME_IMG_URL?>/store_serch/test_list.jpg" alt="asd" />
                   </div>
                   <div class="order_text">
                       <h3>[브랜드 이름]상품의 이름이 들어갈 텍스트 입니다</h3>
                       <p class="order_option">색상: 스카이 / 사이즈: M / 수량: 1개</p>
                       <p class="order_pay">9,000원</p>
                       <p class="order_stat">배송완료</p>
                   </div>            
                </div>                
                <div class="order_btn clearfix">
                    <a href="#" class="order_review_l">구매후기 쓰기</a>
                </div>
            </li>            
        </ul>
    </div>
    <div class="order_list">
        <h2 class="order_date">2019.01</h2>
        <ul>
            <li class="clearfix">
                <div class="clearfix" onclick="test();">
                    <div class="order_img">
                        <img src="<?=G5_THEME_IMG_URL?>/store_serch/test_list.jpg" alt="asd" />
                    </div>
                    <div class="order_text">
                        <h3>[브랜드 이름]상품의 이름이 들어갈 텍스트 입니다</h3>
                        <p class="order_option">색상: 스카이 / 사이즈: M / 수량: 1개</p>
                        <p class="order_pay">9,000원</p>
                        <p class="order_stat">취소완료</p>
                    </div>
                </div>                
            </li>            
        </ul>
    </div-->
</div>
<?}?>

<script type="text/javascript">
<!--
$(function(){
    $(".itemuse_form").click(function(){
        window.open(this.href, "itemuse_form", "width=810,height=680,scrollbars=1");
        return false;
    });
});
//-->
</script>