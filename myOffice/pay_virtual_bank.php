<link rel="shortcut icon" href="/img/vmp_logo.ico"/>
<link rel="stylesheet" href="./css/index.css"/>
<link rel="stylesheet" href="css/animate.css"/>
<link rel="stylesheet" href="css/myoffice_sub.css"/>

<script src="./js/wow.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;500&display=swap" rel="stylesheet">

<?php
include_once('./inc/title.php');
include_once('./_common.php');
include_once('./dbConn.php');
include_once('./getMemberInfo.php');
include_once('../shop/shop.head.php');
$bank = $_GET['bankName'];
$amt = number_format($_GET['Amt']);
$bank_num = $_GET['bankNum'];

?>

<!--메인 시작-->
<div class="pay_wrap pay_virtualbank">
    <div class="inner_vtlbnk wow fadeInUp">
        <h2>Vendor Marketing Platform</h2>
        <h3>가상계좌 포인트 충전</h3>
        <div class="vtlbnk_info_area wow fadeInUp" data-wow-delay="0.3s">
            <p>가상 계좌</p>
            <div class="wrap_bnk_nb">
                <div class="bank_name info_box"><?=$bank?></div>
                <div class="ac_nb info_box"><?=$bank_num?></div>
            </div>
            <p>예금주</p>
            <div class="ac_holder info_box">엠투오웨이</div>
            <p class="tit_rst_prc">입금하실 금액</p>
            <div class="rst_prc info_box"><?=$amt?>원</div>
        </div>
        <div class="bottom_area">
            <span class="btn_chk" onclick="gogo()">확인</span>
        </div>
    </div>
    <?php
    include_once('./loadingMsg.php');
    ?>
    <?php
    include_once('./alertMsg.php');
    ?>
</div>
<script>

    // 애니메이션 효과
    $(document).ready(function () {
        new WOW().init();
    });


    function gogo() {
        var result = confirm("입금을 하셨습니까? \n 입금후 약 1~3분후 포인트가 충전됩니다.");
        if (result){
            location.href = "/myOffice/index_start.php"
        }else {
            return;
        }

    }


    //콤마찍기
    function comma(str) {
        str = String(str);
        return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
    }

    //콤마풀기
    function uncomma(str) {
        str = String(str);
        return str.replace(/[^\d]+/g, '');
    }
</script>

<?php
include_once('../shop/shop.tail.php');
//
//if ($member["payment2"] == "NO") {
//    ?>
<!--    <script>-->
<!--        $(".alert_btn").attr("href", "/myOffice");-->
<!--        alertMsg('error', "결제 권한이 없습니다.");-->
<!--    </script>-->
<!--    --><?php
//}
//?>



