<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

include_once('./_common.php');
unset($list);
//해당아이템에 판매자로 지정한아이디 주문내역 따로저장

$sql = "select b.it_sell_email,
b.mb_id ,
                a.it_id,
                a.it_name,
                ct_qty,
                (ct_price * ct_qty) as price
           from g5_shop_cart a left join temp_item_shop b on ( a.it_id = b.it_id )
          where a.od_id = '{$od_id}'
            and a.ct_select = '1'
             and b.mb_id != ''
            group by a.it_id";
$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++)
{
    if ($row['mb_id'] != ''){
        $sql = " insert into sell_order_list
                    set od_id = '{$od_id}',
                        it_id       = '{$row['it_id']}',
                        mb_id       = '{$member['mb_id']}',
                        sale_mb_id  = '{$row['mb_id']}',
                        od_cart_count  = '{$row['ct_qty']}',
                        od_cart_price     = '{$row['price']}',
                        od_buyTime = current_timestamp(); 
                       ";
        sql_query($sql);
    }
}
?>