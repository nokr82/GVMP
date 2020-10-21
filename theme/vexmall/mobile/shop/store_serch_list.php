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



<div id="storeTab">
    <div class="tab_list">
        <ul class="clearfix">
            <li class="list_all">
                <a href="#">
                    <img src="<?=G5_THEME_IMG_URL?>/store_serch/vroad-store_not-hover6.svg" alt="asd" />
                    <!--<img src="<?=G5_THEME_IMG_URL?>/store_serch/vroad-store_hover6.svg" alt="asd" />-->
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="<?=G5_THEME_IMG_URL?>/store_serch/vroad-store_not-hover1.svg" alt="asd" />
                    <!--<img src="<?=G5_THEME_IMG_URL?>/store_serch/vroad-store_hover1.svg" alt="asd" />-->
                    <p>패션<span class="na">·</span>잡화<span class="na">·</span>뷰티</p>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="<?=G5_THEME_IMG_URL?>/store_serch/vroad-store_not-hover2.svg" alt="asd" />
                    <!--<img src="<?=G5_THEME_IMG_URL?>/store_serch/vroad-store_hover2.svg" alt="asd" />-->
                    <p>식품<span class="na">·</span>출산<span class="na">·</span>유아</p>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="<?=G5_THEME_IMG_URL?>/store_serch/vroad-store_not-hover3.svg" alt="asd" />
                    <!--<img src="<?=G5_THEME_IMG_URL?>/store_serch/vroad-store_hover3.svg" alt="asd" />-->
                    <p>생활<span class="na">·</span>건강<span class="na">·</span>리빙</p>
                 </a>
            </li>
            <li>
                <a href="#">
                    <img src="<?=G5_THEME_IMG_URL?>/store_serch/vroad-store_not-hover4.svg" alt="asd" />
                    <!--<img src="<?=G5_THEME_IMG_URL?>/store_serch/vroad-store_hover4.svg" alt="asd" />-->
                    <p>디지털<span class="na">·</span>가전<span class="na">·</span>핸드폰</p>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="<?=G5_THEME_IMG_URL?>/store_serch/vroad-store_not-hover5.svg" alt="asd" />
                    <!--<img src="<?=G5_THEME_IMG_URL?>/store_serch/vroad-store_hover5.svg" alt="asd" />-->
                    <p>스포츠<span class="na">·</span>취미<span class="na">·</span>자동차</p>
                </a>
            </li>              
        </ul>
    </div>
</div>

<div id="listNav" class="clearfix">
    <ul class="clearfix">
        <!--리스트에 클래스 on 이 붙으면 활성화-->
        <li>
            <a href="#">전체</a>
        </li>
        <li>
            <a href="#">테스트</a>
        </li>
        <li class="on">
            <a href="#">테스트트</a>
        </li>        
    </ul>
    <!--div에 클래스 on 이 붙으면 활성화-->
    <div class="liststyle_list on"></div>
    <div class="liststyle_pic"></div>
</div>

<div class="resultList">
    <ul>
        <li>
            <a href="#" class="clearfix">
                <div class="list_img">
                    <img src="<?=G5_THEME_IMG_URL?>/store_serch/test_list.jpg" alt="asd" />
                </div>
                <div class="list_text">
                    <h3>[브랜드 이름]상품의 이름이 들어갈 텍스트 입니다</h3>
                    <p class="pay">9,000원 <span class="sale">17,330원</span></p>
                    <p class="pay_2">무료배송</p>
                </div>
            </a>
        </li>
        <li>
            <a href="#" class="clearfix">
                <div class="list_img">
                    <img src="<?=G5_THEME_IMG_URL?>/store_serch/test_list.jpg" alt="asd" />
                </div>
                <div class="list_text">
                    <h3>[브랜드 이름]상품의 이름이 들어갈 텍스트 입니다</h3>
                    <p class="pay">9,000원 <span class="sale">17,330원</span></p>
                    <p class="pay_2">무료배송</p>
                </div>
            </a>
        </li>
    </ul>
</div>










<?php
include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>