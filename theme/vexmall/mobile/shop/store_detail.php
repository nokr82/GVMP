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



<div id="storeDetail">
    <form action="#" method="post" name="storedetailForm">
        <div class="detail_imgbox">
            <div class="de_head clearfix">
                <div class="back_img">
                    <img src="<?=G5_THEME_IMG_URL?>/payment/vroad_back.svg" alt="뒤로가기버튼" />
                </div>            
                <div class="cart_img">
                    <img src="<?=G5_THEME_IMG_URL?>/store/vroad-main_cart.svg" alt="카트버튼" />
                </div>
                <div class="serch_img">
                    <img src="<?=G5_THEME_IMG_URL?>/store/vroad-main_serch.svg" alt="검색버튼" />
                </div>            
            </div>
            <img src="<?=G5_THEME_IMG_URL?>/store/store-img-4.jpg" alt="검색버튼" />
        </div>

        <div class="detail_info">
            <h3>[브랜드 이름] 상품의 이름이 들어갈 텍스트입니다. 두줄까지 가능합니다.</h3>
            <div class="de_con_1 clearfix">
                <p>9,900원<span>17,330원</span></p>
                <div class="clearfix">
                    <div><i class="fa fa-minus"></i></div>
                    <div>1</div>
                    <div><i class="fa fa-plus"></i></div>                
                </div>                
            </div>
            <div class="de_con_2">
                <h4>선택옵션</h4>
                <div class="op_1 clearfix">
                    <h5>색상</h5>
                    <select class="input_select" >
                        <option value="">옵션 선택</option>
                        <option value="빨강">빨강</option>
                    </select>
                </div>
                <div class="op_2 clearfix">
                    <h5>사이즈</h5>
                    <select class="input_select" >
                        <option value="">옵션 선택</option>
                        <option value="M">M</option>
                    </select>
                </div>
            </div>
        </div>
        <ul class="de_option">
            <li class="clearfix">
                <h4>카드 혜택</h4>
                <p>카드 관련 텍스트입니다</p>
            </li>
            <li class="clearfix">
                <h4>배송방법</h4>
                <p>배송방법 관련 텍스트 입니다</p>
            </li>
            <li class="clearfix">
                <h4>배송비</h4>
                <p>무료배송</p>
            </li>            
        </ul>
        
        <div class="submit_box">
            <a href="#"><i class="fa fa-shopping-cart"></i>구매하기</a>
        </div>
    </form>
</div>










<?php
include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>