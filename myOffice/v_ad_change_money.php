<!--V 광고 비즈머니 전환--> 

<link rel="shortcut icon" href="/img/vmp_logo.ico" />
<link rel="stylesheet" href="../font/nanumsquare.css"/>
<link rel="stylesheet" href="./css/v_ad_center.css" />
<link rel="stylesheet" href="css/animate.css"/>
<script src="./js/wow.min.js"/></script>

<link href="https://fonts.googleapis.com/css?family=Noto+Sans+KR&display=swap&subset=korean" rel="stylesheet">
<?php
include_once ('./inc/title.php');
include_once ('./_common.php');
include_once ('./dbConn.php');
include_once('./getMemberInfo.php');
include_once ('../shop/shop.head.php');
?>
<script src="http://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>
<script src="./js/jquery.counterup.min.js"/></script>
<div class="v_ad_wrap">
    <div class="v_ad_content">
        <form onsubmit="return bizFormFunc()">
            <input type="hidden" id="mb_id" value="<?=$member["mb_id"]?>">
        <div class="v_ad_money">
            <h3>비즈머니 전환</h3>
            <?php
            include_once './v_ad_point.php';
            ?>
        </div>
        <div class="point_box wow fadeInUp">
            <div class="point_col_1">
                <p>전환 가능 포인트</p>
                <div>
                    <b class="mb_all_point counter" id="mb_all_point_b"><?php echo number_format($member["VMC"]+$member["VMP"]+$member["VMM"]); ?></b>P
                </div>
            </div>
            <div class="point_col_2">
                <p>전환 신청 포인트</p>
                <ul>
                    <li>
                        <div class="col_1">
                            <label for="mb_vmc_point">VMC</label>
                            포인트 :
                        </div>
                        <div class="col_2">
                            <input type="text" onblur="pointBlur()" name="mb_vmc_point" id="mb_vmc_point" value="" inputmode="numeric" min="0" onkeyup="onlyNumberComma(this)" placeholder="포인트를 입력해주세요.">
                        </div>
                    </li>
                    <li>
                        <div class="col_1">
                            <label for="mb_vmp_point">VMP</label>
                            포인트 :
                        </div>
                        <div class="col_2">
                            <input type="text" onblur="pointBlur()" name="mb_vmp_point" id="mb_vmp_point" value="" inputmode="numeric" min="0" onkeyup="onlyNumberComma(this)"  placeholder="포인트를 입력해주세요.">
                        </div>
                    </li>
                    <li>
                        <div class="col_1">
                            <label for="mb_vmm_point">VMM</label>
                            포인트 :
                        </div>
                        <div class="col_2">
                            <input type="text" onblur="pointBlur()" name="mb_vmm_point" id="mb_vmm_point" value="" inputmode="numeric" min="0"  onkeyup="onlyNumberComma(this)"  placeholder="포인트를 입력해주세요.">
                        </div>
                    </li>
                    <li>
                        <p>총 전환 비즈머니 : <b id="point_Total" class="mb_request_money">0</b>P</p>
                    </li>
                </ul>
            </div>
        </div>
            <div class="btn_box">
                <a href="#none" class="btn_v_ad btn_exchange btn_change_money" >
                <input type="submit" name="request_biz_money" id="request_biz_money" value="비즈머니 전환">
                <i></i>
            </a>
            <a href="./v_ad_center.php" class="btn_v_ad btn_v_ad_go_to">목록</a>
            </div>
    </form>
    </div>
</div>

<script>
// 애니메이션 효과 
new WOW().init();

//전환가능 포인트 카운터 효과
$('.counter').counterUp({
    delay: 10,
    time: 1000
});

//콤마찍기
function comma(str) {
    str = String(str);
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}


// 숫자와 콤마 입력가능하게 처리하는 부분 
function onlyNumberComma(obj){
    $(obj).keyup(function (event) {
        regexp = /[^0-9]/gi;
        value = $(this).val();
        $(this).val(comma(value.replace(regexp, '')));
    });
}




function bizFormFunc() {
    // 비즈머니 FORM 태그 서브밋 함수
    
    if( $("#mb_vmc_point").val() == "" ) {
        $("#mb_vmc_point").val("0");
        return false;
    } 

    if( $("#mb_vmp_point").val() == "" ) {
        $("#mb_vmp_point").val("0");
        return false;
    } 
    
    if( $("#mb_vmm_point").val() == "" ) {
        $("#mb_vmm_point").val("0"); 
        return false;
    } 
    
    if( $("#point_Total").text() == "0" ) {
        alertMsg('error','0원을 전환할 수 없습니다.');
        return false;
    } 
        
    loadMsg('on','데이터를 전송 중입니다.');

    $.ajax({
        url:'v_ad_change_money_back.php',
        type:'POST',
       data : "mb_id="+$("#mb_id").val() + "&mb_vmc_point= " + $("#mb_vmc_point").val() + "&mb_vmp_point=" + $("#mb_vmp_point").val() + "&mb_vmm_point=" + $("#mb_vmm_point").val(),
        async: false,
        success:function(result){
            if( result == "false" ) {
                loadMsg('off');
                alertMsg('error','에러가 발생했습니다.');
                
            } else {
                loadMsg('off');
                $("#success_aTAG").attr("href", "v_ad_change_money.php");
                alertMsg('success','전환이 완료되었습니다.');
            }
       }
    });


    return false;
    
}




function pointBlur() {
    // 사용자가 입력하는 포인트 INPUT 빠져나갈 때 동작할 함수
 
    
    var vmc = $("#mb_vmc_point").val().replace(/,/g, '');
    var vmp = $("#mb_vmp_point").val().replace(/,/g, '');
    var vmm = $("#mb_vmm_point").val().replace(/,/g, '');
    
    if( vmc == "" ) {
        vmc = 0;
    }
    if( vmp == "" ) {
        vmp = 0;
    }
    if( vmm == "" ) {
        vmm = 0;
    }
    
    
    
    if( parseInt(vmc) > parseInt($("#vmc_point_b").text().replace(/,/g, '')) ) {
        alertMsg('error','보유 VMC 금액을 초과하였습니다.');
        $("#mb_vmc_point").val("");
    }
    if( parseInt(vmp) > parseInt($("#vmp_point_b").text().replace(/,/g, '')) ) {
        alertMsg('error','보유 VMP 금액을 초과하였습니다.');
        $("#mb_vmp_point").val("");
    }
    if( parseInt(vmm) > parseInt($("#vmm_point_b").text().replace(/,/g, '')) ) {
        alertMsg('error','보유 VMM 금액을 초과하였습니다.');
        $("#mb_vmm_point").val("");
    }
    
    
    
    
    
    
    
    

    var total = parseInt(vmc)+parseInt(vmp)+parseInt(vmm);

    total = String(total);
    total = total.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
    $("#point_Total").text(total);
    
    
}


</script>

<?php
include_once ('./loadingMsg.php');
?>


<?php
include_once ('../shop/shop.tail.php');
?>

<?php
include_once ('./alertMsg.php');
?>