<?php
include_once($DOCUMENT_ROOT.'/vexMall/back/dbConn.php'); // DB Server
include_once('./_common.php');

//if (!$is_member)
//    goto_url(G5_BBS_URL."/login.php");
//풀지말것

define("_INDEX_", TRUE);

include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');


$sql = " select a.ct_id,
                a.it_id,
                a.it_name,
                a.ct_price,
                a.ct_point,
                a.ct_qty,
                a.ct_status,
                a.ct_send_cost,
                a.it_sc_type,
                b.ca_id
           from {$g5['g5_shop_cart_table']} a left join {$g5['g5_shop_item_table']} b on ( a.it_id = b.it_id )
          where a.od_id = '$s_cart_id' ";
$sql .= " group by a.it_id ";
$sql .= " order by a.ct_id ";
$result = sql_query($sql);

$cart_count = sql_num_rows($result);
?>
<!--<link rel="stylesheet" href="<?=G5_THEME_CSS_URL?>/jquery.bxslider.css">
<script src="<?=G5_THEME_JS_URL?>/jquery.bxslider.js"></script>
<script src="<?=G5_THEME_JS_URL?>/index.js"></script>-->

<script src="<?php echo G5_JS_URL; ?>/shop.js"></script>

<div id="storeCart">

<form name="frmcartlist" id="sod_bsk_list" class="2017_renewal_itemform" method="post" action="<?php echo $cart_action_url; ?>">
<!--<form action="#" name="cartForm" id="cartForm" method="post">-->
        <!--div class="cart_head">
            <h2>장바구니</h2>
        </div-->
        <div class="cart_content">

<?php if($cart_count) { ?>
            <div class="ck_all">
                <div class="wrap_cart">
                    <input type="checkbox" id="ckAll" name="ckAll">
                    <label for="ckAll">전체 상품선택</label>
                </div>            
            </div>
            <ul>

        <?php
        $tot_point = 0;
        $tot_sell_price = 0;
        $it_send_cost = 0;
        for ($i=0; $row=sql_fetch_array($result); $i++)
        {
            // 합계금액 계산
            $sql = " select SUM(IF(io_type = 1, (io_price * ct_qty), ((ct_price + io_price) * ct_qty))) as price,
                            SUM(ct_point * ct_qty) as point,
                            SUM(ct_qty) as qty
                        from {$g5['g5_shop_cart_table']}
                        where it_id = '{$row['it_id']}'
                          and od_id = '$s_cart_id' ";
            $sum = sql_fetch($sql);

            if ($i==0) { // 계속쇼핑
                $continue_ca_id = $row['ca_id'];
            }

            $a1 = '<a href="./item.php?it_id='.$row['it_id'].'"><strong>';
            $a2 = '</strong></a>';
            $image_width = 128;
            $image_height = 128;
            $image = get_it_image($row['it_id'], $image_width, $image_height);

            $it_name = $a1 . stripslashes($row['it_name']) . $a2;
            $it_options = print_item_options($row['it_id'], $s_cart_id);
            if($it_options) {
                $mod_options = '<button type="button" id="mod_opt_'.$row['it_id'].'" class="mod_btn mod_options">선택사항수정</button>';
               // $it_name .= ;
            }

            // 배송비
            switch($row['ct_send_cost'])
            {
                case 1:
                    $ct_send_cost = '착불';
                    break;
                case 2:
                    $ct_send_cost = '무료';
                    break;
                default:
                    $ct_send_cost = '선불';
                    break;
            }

            // 조건부무료
            if($row['it_sc_type'] == 2) {
                $sendcost = get_item_sendcost($row['it_id'], $sum['price'], $sum['qty'], $s_cart_id);

                if($sendcost == 0)
                    $ct_send_cost = '무료';
            }

            $point      = $sum['point'];
            $sell_price = $sum['price'];

			$send_cost += $ct_send_cost;
			$tot_point += $point;
			$tot_price += $sell_price;
        ?>
                <li class="sod_li">
						
					<input type="hidden" name="it_id[<?php echo $i; ?>]"    value="<?php echo $row['it_id']; ?>">
					<input type="hidden" name="it_name[<?php echo $i; ?>]"  value="<?php echo get_text($row['it_name']); ?>">
                    <div class="wrap_cart">
                        <div class="con_head">
		                    <input type="checkbox" name="ct_chk[<?php echo $i; ?>]" value="1" id="ct_chk_<?php echo $i; ?>" checked class="listcheckbox">
                            <label for="ct_chk_<?php echo $i; ?>"><?=$it_name?></label>
                        </div>
                        <div class="con_item clearfix">
                            <div class="con_img">
                                <?php echo $image; ?>
                            </div>
                            <div class="con_text">
                                <p><?php echo $it_options; ?></p>
								<p><?php echo $mod_options; ?></p>
                            </div>
                        </div>
                        <div class="con_info">
                            <ul class="clearfix">
                                <li class="clearfix">
                                    <h3>판매가</h3>
                                    <p><?php echo number_format($row['ct_price']); ?>원</p>
                                </li>
                                <li class="clearfix">
                                    <h3>수량</h3>
                                    <p><?php echo number_format($sum['qty']); ?></p>
                                </li>
                                <li class="clearfix">
                                    <h3>배송비</h3>
                                    <p><?php echo $ct_send_cost; ?></p>
                                </li>
                                <li class="clearfix">
                                    <h3>적립포인트</h3>
                                    <p><?php echo number_format($point); ?>점</p>
                                </li>
                            </ul>
                        </div>
                        <p class="cart_pay">결제 예정 금액<span><?php echo number_format($sell_price); ?>원</span></p>
                    </div>
                </li>
<?

		}
?>
            </ul>
            <div class="pay_sum">
                <div class="btn_box">
                    <a href="#none" id="ck_del" onclick="return form_check('seldelete');" >선택삭제</a>
                    <a href="#none" id="all_del"onclick="return form_check('alldelete');" >전체삭제</a>
                </div>
                <div class="pay_count">
                    <div class="clearfix">
                        <p class="count_left">배송비</p>
                        <p class="count_right"><span><?php echo number_format($send_cost); ?></span>원</p>
                    </div>
                    <div class="clearfix">
                        <p class="count_left">포인트</p>
                        <p class="count_right"><span><?php echo number_format($tot_point); ?></span>점</p>
                    </div>
                    <div class="clearfix all_sum">
                        <p class="count_left">총계</p>
                        <p class="count_right"><span><?php echo number_format($tot_price); ?></span>원</p>
                    </div>
                </div>                
            </div>
<?}else{?>
    <div class="none_cart">
        <img src="<?=G5_THEME_IMG_URL?>/event/vroad_alert.svg" alt="경고표시" />
        <h3>장바구니에 상품이 없습니다.</h3>
    </div>
<?}?>
            <div class="sum_btn clearfix">

				<input type="hidden" name="url" value="<?php echo G5_SHOP_URL; ?>/orderform.php">
				<input type="hidden" name="act" value="">
				 <input type="hidden" name="records" value="<?php echo $i; ?>">
                <a href="#none" id="go_shop" onClick="location.href='/shop/list.php?ca_id=9010'">쇼핑계속하기</a>
                <a href="#none" id="go_sum" onclick="return form_check('buy');" >구매하기</a>
        <?php if ($naverpay_button_js) { ?>
		        <div class="naverpay-cart"><?php echo $naverpay_request_js.$naverpay_button_js; ?></div>
        <?php } ?>
            </div>
        </div>
</form>
</div>
<script>
$(function() {
    var close_btn_idx;

    // 선택사항수정
    $(".mod_options").click(function() {
        var it_id = $(this).attr("id").replace("mod_opt_", "");
        var $this = $(this);
        close_btn_idx = $(".mod_options").index($(this));

        $.post(
            "./cartoption.php",
            { it_id: it_id },
            function(data) {
                $("#mod_option_frm").remove();
                $this.after("<div id=\"mod_option_frm\"></div>");
                $("#mod_option_frm").html(data);
                price_calculate();
            }
        );
    });

    // 모두선택
    $("input[name=ckAll]").click(function() {
        if($(this).is(":checked")) {
            $("input[name^=ct_chk]").attr("checked", true);
        } else {
            $("input[name^=ct_chk]").attr("checked", false);
		}
    });

	$(".listcheckbox").on("click",function(){
		$.each($(".listcheckbox"),function(e,key){
			if(!$(this).is(":checked")) {
				$("input[name=ckAll]").attr("checked",false);
			}
		});
	});


    // 옵션수정 닫기
    $(document).on("click", "#mod_option_close", function() {
        $("#mod_option_frm").remove();
        $("#win_mask, .window").hide();
        $(".mod_options").eq(close_btn_idx).focus();
    });
    $("#win_mask").click(function () {
        $("#mod_option_frm").remove();
        $("#win_mask").hide();
        $(".mod_options").eq(close_btn_idx).focus();
    });

});

function fsubmit_check(f) {
    if($("input[name^=ct_chk]:checked").size() < 1) {
        alert("구매하실 상품을 하나이상 선택해 주십시오.");
        return false;
    }

    return true;
}

function form_check(act) {
    var f = document.frmcartlist;
    var cnt = f.records.value;

    if (act == "buy")
    {
        f.act.value = act;
        f.submit();
    }
    else if (act == "alldelete")
    {
        f.act.value = act;
        f.submit();
    }
    else if (act == "seldelete")
    {
        if($("input[name^=ct_chk]:checked").size() < 1) {
            alert("삭제하실 상품을 하나이상 선택해 주십시오.");
            return false;
        }

        f.act.value = act;
        f.submit();
    }

    return true;
}
</script>
<?php
include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>