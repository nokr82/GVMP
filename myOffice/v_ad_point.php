<?php
    include_once ('./_common.php');
    include_once ('./dbConn.php');
    
?>
<div class="my_point">
    <h4>보유 포인트<em>카드결제를 통한 포인트 충전은 서포트에 문의바랍니다.</em></h4>
    <?php if( $_SERVER['REQUEST_URI'] !== "/myOffice/v_ad_change_money.php") {?>
        <a href="./v_ad_change_money.php" class="btn_v_ad btn_exchange">비즈머니 전환<i></i></a>
    <?php }?>
        <ul class="wow fadeInUp">
        <li>
            <em>VMC</em>
            <strong><b class="vmc_point" id="vmc_point_b"><?php echo number_format($member["VMC"]); ?></b>원</strong>
        </li>
        <li>
            <em>VMP</em>
            <strong><b class="vmp_point" id="vmp_point_b"><?php echo number_format($member["VMP"]); ?></b>원</strong>
        </li>
        <li>
            <em>VMM</em>
            <strong><b class="vmm_point" id="vmm_point_b"><?php echo number_format($member["VMM"]); ?></b>원</strong>
        </li>
        <li class="biz_money">
            <em>비즈머니</em>
            <strong><b class="bizmoney"><?php echo number_format($member["bizMoney"]); ?></b>원</strong>
        </li>
    </ul>
</div>