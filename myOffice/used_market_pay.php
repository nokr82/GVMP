<!--중고장터 결제페이지-->

<link rel="shortcut icon" href="/img/vmp_logo.ico" />
<?php
include_once ('./inc/title.php');
include_once ('./_common.php');
include_once ('./dbConn.php');
include_once('./getMemberInfo.php');
include_once ('../shop/shop.head.php');

$usedRe = mysql_query("select * from g5_write_used where wr_id = ".$_GET["wr_id"]);
$usedRow = mysql_fetch_array($usedRe);

$imageRow = mysql_fetch_array(mysql_query("select * from g5_board_file where wr_id = " . $_GET["wr_id"]));


include_once(G5_LIB_PATH.'/thumbnail.lib.php');
$filename = $imageRow["bf_file"];
$filepath = G5_DATA_PATH.'/file/used';
$filesrc = G5_DATA_URL.'/file/used/'.$filename;
$thumb = thumbnail($filename, $filepath , $filepath , 600, 600, false, true);
$thumbsrc = G5_DATA_URL.'/file/used/'.$thumb;
?>
<script src="https://use.fontawesome.com/releases/v5.2.0/js/all.js"></script>
<link rel="stylesheet" href="../font/nanumsquare.css">
<link rel="stylesheet" href="/myOffice/css/used_market.css">

<!-- 중고장터 결제페이지 시작 -->
<div class="used_pay_wrap">
    <div class="used_head bo_v_head">
        <h2>중고장터</h2>
    </div>
    <div class="my_point">
        <ul>
            <li>
                <em>VMC</em>
                <strong><b class="vmc_point" id="vmc_point_b"><?php echo number_format($member["VMC"]) ?></b>원</strong>
            </li>
            <li>
                <em>VMP</em>
                <strong><b class="vmp_point" id="vmp_point_b"><?php echo number_format($member["VMP"]) ?></b>원</strong>
            </li>
            <li style="display: none;">
                <em>VMM</em>
                <strong><b class="vmm_point" id="vmm_point_b"><?php echo number_format($member["VMM"]) ?></b>원</strong>
            </li>
        </ul>
    </div>
    <form action="" id="used_form" onsubmit="return formCheck()">
        <input type="hidden" name="mb_id" value="<?=$member["mb_id"]?>"/>
        <input type="hidden" name="wr_id" value="<?=$_GET["wr_id"]?>"/>
    <div class="used_pay_content">
        <div class="pay_info">
            <div class="img_box">
            <?php
                    echo '<img src="'.$thumbsrc.'" alt="'.$filename.'">'; 
             ?>
                <!--<img src="./images/test.jpg" alt="침대사진">-->
            </div>
            <div class="txt_box">
                <dl>
                    <dt>상품명</dt>
                    <dd><?=$usedRow["wr_subject"]?></dd>
                    <dt>상품가격</dt>
                    <dd><b id="item_price"><?php echo number_format($usedRow["price"]) ?></b>원</dd>
                    <dt>판매자</dt>
                    <dd><?=$usedRow["mb_id"]?></dd>
                    <dt><label for="buyer_addr">본인</br>연락처</label></dt>
                    <dd><input id="buyer_addr" name="buyer_addr" type="text" required placeholder="ex) 010-1234-5678"></dd>
                </dl>
            </div>
        </div>
        <div class="pay_point">
            <ul>
                <li>
                    <label for="vmc_point"><em>VMC</em>포인트:</label>
                    <input type="text" name="mb_vmc_point" id="mb_vmc_point" onkeyup="onlyNumberComma(this)" placeholder="포인트를 입력해주세요." autocomplete="off">
                </li>
                <li>
                    <label for="vmp_point"><em>VMP</em>포인트:</label>
                    <input type="text" name="mb_vmp_point" id="mb_vmp_point" onkeyup="onlyNumberComma(this)" placeholder="포인트를 입력해주세요." autocomplete="off">
                </li>
                <li style="display: none;">
                    <label for="vmm_point"><em>VMM</em>포인트:</label>
                    <input type="text" name="mb_vmm_point" id="mb_vmm_point" onkeyup="onlyNumberComma(this)" placeholder="포인트를 입력해주세요." autocomplete="off">
                </li>
            </ul>
        </div>
        <div class="pay_price">
            <div>
                <i class="fas fa-coins"></i>
                <b>총 구매<i></i>결정 포인트</b>
            </div>
            <strong><b id="total_point">0</b>원</strong>
        </div>
    </div>
    <div class="btn_box">
        <input type="submit" class="btn_pay" value="구매">
        <a href="<?=$_SERVER["HTTP_REFERER"]?>" class="btn_cancel">취소</a>
    </div>
    </form>
</div>
<!-- 중고장터 결제페이지 끝 -->

<?php
include_once ('./alert.php');
?>

<?php
include_once ('./loadingMsg.php');
?>

<script>
// 숫자와 콤마 입력가능하게 처리하는 부분 
function onlyNumberComma(obj){
    $(obj).keyup(function (event) {
        regexp = /[^0-9]/gi;
        value = $(this).val();
        $(this).val(comma(value.replace(regexp, '')));

    });
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
 
 
//포인트 계산
$('.pay_point').find('input').each(function(idx, obj){

    var vmc = Number(uncomma($("#vmc_point_b").text()));
    var vmp = Number(uncomma($("#vmp_point_b").text()));
    var vmm = Number(uncomma($("#vmm_point_b").text()));
    var myPoint = $('.my_point').find('b');
    var myPointVal = Number(uncomma( $(myPoint).eq(idx).text() ));
    
    
    $(obj).keyup(function(ev){

        var payPointVal = Number(uncomma($(obj).val()));

        if( myPointVal < payPointVal ){
            msgAlert('error','보유 금액을 초과하였습니다.', function(){alertOff();});
//            $(myPoint).eq(idx).text(comma(vmc));
            $(obj).val('');
            $('#total_point').text( comma( Number(uncomma($("#mb_vmc_point").val())) + Number(uncomma($("#mb_vmp_point").val())) + Number(uncomma($("#mb_vmm_point").val())) ) );
            return;
            
        } else {
//            $(myPoint).eq(idx).text( comma( myPointVal - payPointVal ) );
            $('#total_point').text( comma( Number(uncomma($("#mb_vmc_point").val())) + Number(uncomma($("#mb_vmp_point").val())) + Number(uncomma($("#mb_vmm_point").val())) ) );
 
        }

    });

});







//최종 값검증
function formCheck(){
   
   if( $("#mb_vmc_point").val() == "" ) {
        $("#mb_vmc_point").val("0");
    }
    if( $("#mb_vmp_point").val() == "" ) {
        $("#mb_vmp_point").val("0");
    }
    if( $("#mb_vmm_point").val() == "" ) {
        $("#mb_vmm_point").val("0");
    }
   
   if( $("#mb_vmc_point").val() == "" && $("#mb_vmp_point").val() == "" && $("#mb_vmm_point").val() == ""){
        msgAlert('error','사용하실 포인트를 입력해주세요.', function(){alertOff()});
        return false;

    } else if ( Number(uncomma( $('#total_point').text() )) > Number(uncomma( $('#item_price').text() )) ){    
        msgAlert('error','사용하실 포인트가 <br>상품가격보다 많습니다.<br>다시 입력해주세요.', function(){alertOff();});
        return false;
        
    } else if ( Number(uncomma( $('#total_point').text() )) < Number(uncomma( $('#item_price').text() )) ){    
        msgAlert('error','사용 포인트가 부족합니다.<br>다시 입력해주세요.', function(){alertOff();});
        return false;
        
    } else {
        loadMsg('on','결제 중입니다.');
        
        $.ajax({
            url:'used_buy_back.php',
            type:'POST',
            data: $("#used_form").serialize(),
            async: false,
            success:function(result){
                loadMsg('off');
                if( result == "true" ) {
                    msgAlert('success','구매하였습니다.<br>판매자에게 연락해주세요.', function(){alertOff(); window.location.href = '/bbs/board.php?bo_table=used'; });
                } else if(result == "false") {
                    msgAlert('error','구매 에러', function(){alertOff();});
                }
            }
        });
        
        return false;
    }
}

//결제 취소했을 때
$('.btn_cancel').on('click', function(){
    $('.pay_point').find('input').val('');
});


</script>








<?php
include_once ('../shop/shop.tail.php');
?>