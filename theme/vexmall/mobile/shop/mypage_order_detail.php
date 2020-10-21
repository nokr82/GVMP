<?php
include_once('./_common.php');

$g5['title'] = '마이페이지';

include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');

?>

<div id="mypageOrderDetail">
    <div class="order_num clearfix">
        <div class="num_box">
            <p>20190303VR123745162</p>
            <p>2019.03.03</p>
        </div>
        <a href="#">주문 내역 삭제</a>
    </div>
    <div class="order_detail clearfix">
        <div class="clearfix">
            <div class="order_img">
                <img src="<?=G5_THEME_IMG_URL?>/store_serch/test_list.jpg" alt="asd" />
            </div>
            <div class="order_text">
                <h3>[브랜드 이름]상품의 이름이 들어갈 텍스트 입니다</h3>
                <p class="order_option">색상: 스카이 / 사이즈: M / 수량: 1개</p>
                <p class="order_pay">9,000원</p>
                <p class="order_stat">결제확인</p>
            </div>
        </div>                
        <div class="order_btn clearfix">
            <a href="#" class="order_review_s">구매후기 쓰기</a>
            <a href="#" class="order_cancel">배송 조회</a>
        </div>
    </div>
    <div class="address_box">
        <h3>배송지 정보</h3>
        <p><span>받는 사람</span> 김민규</p>
        <p><span>연락처</span> 010-1234-5678</p>
        <p><span>받는 주소</span> 서울특별시 강남구 봉은사로67길 5 (삼성동)</p>
        <p><span>요청사항</span> 배송전 연락주세요.</p>
    </div>
    <div class="order_pay_info">
        <h3>결제 정보</h3>
        <ul>
            <li>
                <div class="pay_box clearfix">
                    <p class="left_pay">추가배송비</p>
                    <p class="right_pay">0원</p>
                    <p class="add_pay">(지역에 따라 추가되는 도선료 등의 배송비입니다.) </p>
                </div>
                <div class="pay_box clearfix">
                    <p class="left_pay">총 주문 금액</p>
                    <p class="right_pay"><span>40,000</span>원</p>
                </div>
            </li>
             <li>
                <div class="pay_box clearfix">
                    <p class="left_pay">V PAY</p>
                    <p class="right_pay">0원</p>
                </div>
                <div class="pay_box clearfix">
                    <p class="left_pay">V CASH</p>
                    <p class="right_pay">0원</p>
                </div>
            </li>
             <li>
                <div class="pay_box clearfix">
                    <p class="left_pay">최종 결제금액</p>
                    <p class="right_pay"><span>40,000</span>원</p>
                </div>
            </li>
        </ul>
        
    </div>
</div>
<script>

function test() {
    alert('클릭영역 입니다');
}


</script>

<?php
include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>