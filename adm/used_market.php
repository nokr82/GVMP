<?php
$sub_menu = "200960";
include_once('./_common.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');

$g5['title'] = '중고장터';
include_once (G5_ADMIN_PATH.'/admin.head.php');


$colspan = 5;

include_once('./admin_menu_check.php');

if ($is_admin != 'super') {
     menu_check($sub_menu,$member['mb_id']);
}


include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
if (empty($fr_date) || ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = G5_TIME_YMD;
if (empty($to_date) || ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = G5_TIME_YMD;

$sql = "";
if( $_GET["sch_sort"] == "await_pay" ) { // 결제 대기
    $sql .= "and saleStatus = '결제대기'";
} else if( $_GET["sch_sort"] == "compl_pay" ) { // 결제 완료
    $sql .= "and saleStatus = '결제완료'";
} else if( $_GET["sch_sort"] == "trade" ) { // 거래 완료
    $sql .= "and saleStatus = '거래완료'";
} else if( $_GET["sch_sort"] == "refund" ) { // 환불 완료
    $sql .= "and saleStatus = '환불완료'";
}


if( $_GET["sch_sort_2"] == "purchaser_mb_id" && $_GET["sch_sort_2"] == "" ) { // 구매자 아이디
    $sql .= " and buyer_id = '{$_GET["sch_word"]}'";
} else if( $_GET["sch_sort_2"] == "seller_mb_id" && $_GET["sch_sort_2"] == "" ) { // 판매자 아이디
    $sql .= " and mb_id = '{$_GET["sch_word"]}'";
}



$qstr = "fr_date=".$fr_date."&amp;to_date=".$to_date;
$query_string = $qstr ? '?'.$qstr : '';


$fr_date = date("Y-m-d");
$to_date = date("Y-m-d");
if( $_GET["fr_date"] != "" ) {
    $fr_date = $_GET["fr_date"];
}
if( $_GET["to_date"] != "" ) {
    $to_date = $_GET["to_date"];
}


$gwuRe = mysql_query("select * from g5_write_used where wr_datetime >= '{$fr_date} 00:00:00' and wr_datetime <= '{$to_date} 23:59:59' and wr_is_comment = 0 {$sql} order by wr_datetime desc");


?>
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script src="https://use.fontawesome.com/releases/v5.2.0/js/all.js"></script>

<!--중고장터 시작-->
<div class="used_market_wrap ">
    <div class="nav_tab"></div>
    <!-- 분류 보기 시작  -->
    <form action="" method="get" name="sch_form" id="sch_form" class="local_sch01" onsubmit="return confirmForm()">
    <div class="sch_box">
        <div class="sch_select">
            <label for="sch_sort" class="sound_only">상태 조회</label>
            <select name="sch_sort" id="sch_sort">
                <option value="all">승인상태</option>
                <option value="await_pay" <?php if($_GET["sch_sort"]=="await_pay") {echo "selected='true'";} ?>>결제대기</option>
                <option value="compl_pay" <?php if($_GET["sch_sort"]=="compl_pay") {echo "selected='true'";} ?>>결제완료</option>
                <option value="trade" <?php if($_GET["sch_sort"]=="trade") {echo "selected='true'";} ?>>거래완료</option>
                <option value="refund" <?php if($_GET["sch_sort"]=="refund") {echo "selected='true'";} ?>>환불완료</option>
            </select>
            <label for="sch_sort_2" class="sound_only">목록 조회</label>
            <select name="sch_sort_2" id="sch_sort_2">
                <option value="">분류 선택</option>
                <option value="purchaser_mb_id" <?php if($_GET["sch_sort_2"]=="purchaser_mb_id") {echo "selected='true'";} ?>>구매자 아이디</option>
                <option value="seller_mb_id" <?php if($_GET["sch_sort_2"]=="seller_mb_id") {echo "selected='true'";} ?>>판매자 아이디</option>
            </select>
            <label for="sch_word" class="sound_only">검색어</label>
            <input type="text" name="sch_word" id="sch_input" value="<?=$_GET["sch_word"]?>" placeholder="검색어를 입력해주세요.">
        </div>
        <div class="sch_list">
            <input type="text" name="fr_date" id="fr_date" class="frm_input" value="<?=$fr_date?>" size="11" maxlength="10" autocomplete="off" placeholder="시작일">
            <label for="fr_date" class="sound_only">시작일</label>
            <i>~</i>
            <input type="text" name="to_date" id="to_date" class="frm_input" value="<?=$to_date?>" size="11" maxlength="10" autocomplete="off" placeholder="종료일">
            <label for="to_date" class="sound_only">종료일</label>
            <input type="submit" value="조회" class="btn_submit">
        </div>
    </div>
    </form>
    <!-- 분류 보기 끝  -->
    
    
    <!-- 중고장터 거래내역 시작 -->
    <form action="" method="" name="list_form" id="list_form">
    <div class="v_tbl">
        <table>
            <caption>중고장터 거래내역</caption>
            <thead>
                <tr>
                    <th>등록일</th>
                    <th>결제일</th>
                    <th>상품명</th>
                    <th>가격</th>
                    <th>구매자</th>
                    <th>구매자 연락처</th>
                    <th>판매자</th>
                    <th>판매자 연락처</th>
                    <th>상태</th>
                    <th>환불</th>
                </tr>
            </thead>
            <tbody>
                
<?php
    $i = 0;
    while( $gwuRow = mysql_fetch_array($gwuRe) ) {
        $i++;
        $buyerRow = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$gwuRow["buyer_id"]}'"));
        $sellerRow = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$gwuRow["mb_id"]}'"));
        ?>
            <tr>
            <td><?=$gwuRow["wr_datetime"]?></td>
            <td><?=$gwuRow["buy_datetime"]?></td>
            <td><?=$gwuRow["wr_subject"]?></td>
            <td><?php echo number_format($gwuRow["price"])?>원</td>
            <td>
                <?php
                    if( $buyerRow["mb_name"] != "" ) {
                        ?> <?=$buyerRow["mb_name"]?>(<?=$gwuRow["buyer_id"]?>) <?php
                    }
                ?>
            </td>
            <td><?=$gwuRow["buyer_hp"]?></td>
            <td>
                <?php
                    if( $sellerRow["mb_name"] != "" ) {
                        ?> <?=$sellerRow["mb_name"]?>(<?=$gwuRow["mb_id"]?>) <?php
                    }
                ?>
            </td>
            <td><?=$gwuRow["seller_hp"]?></td>
            <td>
                <?php
                    if( $gwuRow["saleStatus"] == "결제대기" ) {
                        ?> <a href="#none" class="adm_btn btn_await">결제대기</a> <?php
                    } else if( $gwuRow["saleStatus"] == "결제완료" ) {
                        ?> <a href="#none" class="adm_btn btn_pay">결제완료</a> <?php
                    } else if( $gwuRow["saleStatus"] == "거래완료" ) {
                        ?> <a href="#none" class="adm_btn btn_trade">거래완료</a> <?php
                    } else if( $gwuRow["saleStatus"] == "환불완료" ) {
                        ?> <a href="#none" class="adm_btn btn_compl_refund">환불완료</a> <?php
                    }
                ?>
            </td>
            <td>
                <?php
                    if( $gwuRow["saleStatus"] == "결제완료" ) {
                        ?> <a href="#none" onclick="refundFunc('<?=$gwuRow["wr_id"]?>')" class="adm_btn btn_refund"><i class="fas fa-sync-alt"></i>환불</a> <?php
                    }
                ?>
                
            </td>
            </tr>  
        <?php
    }
?>
                
                

                
                
                
        <?php
            if( $i == 0 ) {
                ?>
                    <tr>
                        <td colspan="10">조회된 내역이 없습니다.</td>
                    </tr>
                <?php
            }
        ?>
            </tbody>
        </table>
    </div>
    </form>
    <!-- 내역 끝 -->
</div>
<!--중고장터 거래내역 끝-->
            



<script>


function confirmForm(){
    return true;
}


$(function(){
    $('#fr_date,  #to_date').datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });
});

function refundFunc(wr_id) {
    msgAlert('confirm', '환불 처리하시겠습니까?', function() {alertOff(); tempFunc(wr_id);}, function() {alertOff();} );
    
    function tempFunc(wr_id) {
        var data = "wr_id=" + wr_id;

        $.ajax({
            url:'/myOffice/usedRefund_back.php',
            type:'POST',
            data: data,
            async: false,
            success:function(result){
                if( result == "true" ) {
                    msgAlert('success','환불 처리하였습니다.', function(){alertOff(); window.location.reload(); });
                } else if(result == "false") {
                    msgAlert('error','환불 처리 에러', function(){alertOff();});
                }
            }
        });
    }
}

</script>


<?php
include_once ($_SERVER['DOCUMENT_ROOT'].'/myOffice/alert.php');
?>