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


<div id="storePay">
    <form name="payForm" action="#" method="post" id="payForm">
        <div class="pay_head">
            <img src="<?=G5_THEME_IMG_URL?>/payment/vroad_back.svg" alt="뒤로가기버튼" />
            <h2>구매하기</h2>
        </div>
        <div class="pay_content">            
            <div class="pay_con">
                <h3>주문상품</h3>
                <div class="con_info clearfix">
                    <div class="con_img">
                        <img src="<?=G5_THEME_IMG_URL?>/cart/test_cart.jpg" alt="뒤로가기버튼" />
                    </div>
                    <div class="con_text">
                        <h4>[브랜드 이름] 상품의 이름이 들어가는 텍스트 입니다</h4>
                    </div>
                </div>
                <p class="con_option">색상: 스카이 / 사이즈: M  색상: 스카이 / 사이즈: M</p>
                <p class="con_pay"><span>40,000</span>원(1개)</p>
            </div>            
        </div>        
    </form>    
</div>










<?php
include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>