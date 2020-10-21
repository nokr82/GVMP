<!--버니 결제 내역 페이지-->

<?php
$sub_menu = "000300";
include_once('./_common.php');
$g5['title'] = "포인트관리 > 버니 결제 내역";
include_once('./admin.head.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/beosyong/dbConn.php');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');



$colspan = 5;

include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
if (empty($fr_date) || ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = G5_TIME_YMD;
if (empty($to_date) || ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = G5_TIME_YMD;

$qstr = "fr_date=".$fr_date."&amp;to_date=".$to_date;
$query_string = $qstr ? '?'.$qstr : '';

?>
<!-- 버니 결제 내역 시작 -->
<div class="apply_bn_list_wrap">
    <div class="apply_bn_content">
        <div class="apply_bn_view">
            <div class="total_ap mb_bn_box">
                <span class="apply_info_box">
                    <em>총 버니 결제된 회원</em>
                    <strong><b id="bunnyC">130</b>명</strong>
                </span>
                <span class="apply_icon_box">
                    <img src="./img/adm_profile.png" alt="총 결제 회원 수"/>
                </span>
            </div>
            <div class="total_ap mb_payment_box">
                <span class="apply_info_box">
                    <em>총 결제 금액</em>
                    <strong>&#92;<b id="bunnyP">153,400,400</b>원</strong>
                </span>
                <span class="apply_icon_box">
                    <img src="./img/adm_coin.png" alt="총 결제금액"/>
                </span>
            </div>
        </div>
        <form name="applybeony" id="applybeony" method="get" onsubmit="return applyForm()">
        <div class="apply_bn_sch_id">
            <label for="bn_selec_id" class="sound_only">ID 조회</label>
            <select name="bn_selec_id" id="bn_selec_id">
                <option value="">- 선택 -</option>
                <option value="mb_senior" <?php if($_GET["bn_selec_id"] == "mb_senior") { echo "selected='true'"; } ?>>선배 ID</option>
                <option value="mb_id" <?php if($_GET["bn_selec_id"] == "mb_id") { echo "selected='true'"; } ?>>결제자 ID</option>
            </select>
            <label for="bn_sch_id" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
            <input type="text" name="bn_sch_id" value="<?=$_GET["bn_sch_id"]?>" id="bn_sch_id" class="frm_input">
        </div>
        <div class="apply_bn_sch sch_list">
            <input type="text" name="fr_date" id="fr_date" class="frm_input" value="<?php echo $write["wr_1"]; ?>" size="11" maxlength="10" autocomplete="off">
            <label for="fr_date" class="sound_only">시작일</label>
            <i>~</i>
            <input type="text" name="to_date" id="to_date" class="frm_input" value="<?php echo $write["wr_2"]; ?>" size="11" maxlength="10" autocomplete="off">
            <label for="to_date" class="sound_only">종료일</label>
            <input type="submit" value="조회" class="sch_submit_btn apply_bn_form_btn">
        </div>
        </form>
        <div class="apply_bn table_wrap">
            <table>
                <caption>버니 결제 내역</caption>
                <thead>
                    <tr>
                        <th scope="col" class="th_date">날짜</th>
                        <th scope="col">결제수단</th>
                        <th scope="col">결제자ID(이름)</th>
                        <th scope="col" class="th_reg_num">주민등록번호</th>
                        <th scope="col">은행</th>
                        <th scope="col" class="th_account">계좌번호</th>
                        <th scope="col">선배ID(이름)</th>
                        <th scope="col">전자서명</th>
                        <th scope="col">버니승인</th>
                        <th scope="col">처리</th>
                    </tr>
                </thead>
                <tbody>
                    
<?php
    $i = 0; $totalPrice = 0;
    $fr_date; $to_date;
    if( ! isset($_GET["fr_date"]) && ! isset($_GET["to_date"]) ) {
        $fr_date = date("Y-m-d");
        $to_date = date("Y-m-d");
    } else if( isset($_GET["fr_date"]) && isset($_GET["to_date"]) ) {
        $fr_date = $_GET["fr_date"];
        $to_date = $_GET["to_date"];
    }
    
    $sql = "select * from paymentsTBL as a inner join g5_member as b on a.mb_id = b.mb_id where a.requestTime >= '{$fr_date} 00:00:00' and a.requestTime <= '{$to_date} 23:59:59' ";
    
    if( $_GET["bn_selec_id"] == "mb_senior" ) {
        $sql .= "and b.mb_recommend = '{$_GET["bn_sch_id"]}' ";
    } else if( $_GET["bn_selec_id"] == "mb_id" ) {
        $sql .= "and a.mb_id = '{$_GET["bn_sch_id"]}' ";
    }
    
    $sql .= "order by a.requestTime desc";
    
    $paymentsTBLRe = mysql_query($sql);
    
    
    while( $paymentsTBLRow = mysql_fetch_array($paymentsTBLRe) ) {
        if( ($paymentsTBLRow["payType"] == "카드 결제" || $paymentsTBLRow["payType"] == "실시간 계좌 이체") && $paymentsTBLRow["completionTime"] == "" ) {
            continue;
        }
        
        $recoRow = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$paymentsTBLRow["mb_recommend"]}'"));
?>
        <!--tr에 cc_bar 클래스명을 추가하면 취소선이 그어집니다.-->
        <tr class="">
            <td class="td_date"><?=$paymentsTBLRow["requestTime"]?></td>
            <td class="td_pay_type"><?=$paymentsTBLRow["payType"]?></td>
            <td class="td_mb_id"><?=$paymentsTBLRow["mb_id"]?>(<?=$paymentsTBLRow["mb_name"]?>)</td>
            <td class="td_mb_reg_num"><?=$paymentsTBLRow["RRN"]?></td>
            <td class="td_mb_bank"><?=$paymentsTBLRow["bankName"]?></td>
            <td class="td_mb_account"><?=$paymentsTBLRow["accountNumber"]?></td>
            <td class=""><?=$recoRow["mb_id"]?>(<?=$recoRow["mb_name"]?>)</td>
            <td class="td_mb_sign">
                <a href="#none" name="<?=$paymentsTBLRow["no"]?>" class="mb_sign_btn tbl_btn">VIEW</a>
            </td>
            <td class="td_comfirm">
<?php
    if( $paymentsTBLRow["completionTime"] == "" ) {
?>
        <a href="#none" class="tbl_btn bunnyOk" name="<?=$paymentsTBLRow["no"]?>;<?=$paymentsTBLRow["mb_id"]?>">버니승인</a>
<?php
    } else {
        $i++;
        $totalPrice += $paymentsTBLRow["price"];
    }
?>
                
            </td>
            <td>
                <a href="#none" class="adm_btn tbl_btn adm_cancel_btn" onclick="cancelBeony(<?=$paymentsTBLRow["no"]?>)">취소</a>
            </td>
        </tr>       
<?php
    }
    
    
    if( $totalPrice == 0 ) {
        echo "<tr><td colspan=10'>조회 결과가 없습니다.</td></tr>";
    }
?>
        <script>
            $("#bunnyC").text("<?=$i?>");
            $("#bunnyP").text("<?php echo number_format($totalPrice); ?>");
            
            $("#fr_date").val("<?=$fr_date?>");
            $("#to_date").val("<?=$to_date?>");
        </script>
                </tbody>
            </table>
        </div>
    </div>
    
    <!--bn_sign_wrap에 on 클래스명을 추가하면 서명란이 활성화됩니다.-->
    <div class="bn_sign_wrap">
        <div class="bn_sign_content">
            <h4>전자 서명하기</h4>
            <b id="nameInfo"></b>
            <a class="bn_sign_close_btn">닫기</a>
            <div class="sign_terms_box">
                <textarea><?php echo get_text($config['cf_stipulation']) ?></textarea>
            </div>
            <div class="sign_terms_agree">
                <ul>
                    <li>이용약관을 모두 확인했습니다.</li>
                    <li>이용약관에 동의합니다.</li>
                    <li>본인은 제공받을 서비스 내용과 환불 규정을 확인했고 신중한 판단으로 결정합니다.</li>
                    <li>버니 결제 후 수익 활동 권한과 선배의 교육자료가 제공되면 환불이 절대 불가함에 동의합니다.</li>
                    <li>버숑 회원가입 및 멤버십 가입시 등록한 정보가 정확합니다.</li>
                    <li>해당 질문에 대한 동의는 차후 법적인 문제 발생시 법률적 근거자료로 제출될 수 있음을 알고 있습니다.</li>
                </ul>
            </div>
            <div class="signature_wrap">
                <div id="signature">
<!--                <object id="signImg" type="image/svg+xml" data=""></object>-->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    
 
$(function(){
    $('#fr_date,  #to_date').datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });
});


//검색 값 검증 
function applyForm(){
    var selectVal = $('select option:selected').val();
   
    if( selectVal !== '' ){
        
        if ( $('#bn_sch_id').val() == '' ){
        
            $('#bn_sch_id').attr('placeholder','검색ID를 입력해주세요');
            
            alertMsg('error','검색ID를 입력해주세요',function() {alertOff(); $('#bn_sch_id').focus();});
            
            return false;
        }

    } else {
        
        return true;
    }
}









$('.bunnyOk').on('click', function(){
    var no_temp = $(this).attr('name').split(";");
    var mb_id_temp = $(this).attr('name').split(";");
    var no = no_temp[0];
    var mb_id = mb_id_temp[1];
    
    alertMsg('confirm', '승인 하시겠습니까?', function() {alertOff(); bunnyOk2(no, mb_id);}, function() {alertOff();} );
    

    function bunnyOk2(no, mb_id) {
        $.ajax({
            url:'/beosyong/bunnyOk.php',
            type:'POST',
            data: "no=" + no + "&mb_id=" + mb_id,
            async: false,
            success:function(result){
                if( result == "false" ) {
                    alertMsg('error', '처리 실패', function() {alertOff(); location.reload();} );
                } else {
                    alertMsg('success', '처리 완료', function() {alertOff(); location.reload();} );
                }
           }
        });
    }
});

//전자서명 활성화
$('.mb_sign_btn').on('click', function(){
   $('.bn_sign_wrap').addClass('on');

    var tr = $(this).parent().parent();
    var td = tr.children();

    $.ajax({
         url:'/beosyong/signImageDown.php',
         type:'POST',
         data: "no=" + $(this).attr('name'),
         async: false,
         success:function(result){
             $("#nameInfo").text("결제자ID(성명) : "+td.eq(2).text());
             $("#signature").html('<object id="signImg" type="image/svg+xml" data="'+result+'</object>');
        }
     });
   
   
  // 전자서명 비활성화
    $('.bn_sign_close_btn').on('click', function(){
        $('.bn_sign_wrap').removeClass('on');
    });
   
});



//버니 취소처리 
function cancelBeony(no){
    alertMsg('confirm', '삭제 / 취소 하시겠습니까?', function() {alertOff(); GO2(no);}, function() {alertOff();} );

    function GO2(no) {
        $.ajax({
            url:'/beosyong/apply_bn_delete_back.php',
            type:'POST',
            data: "no=" + no,
            async: false,
            success:function(result){
                if( result == "false" ) {
                    alertMsg('error', '에러가 발생했습니다.', function() {alertOff();} );
                } else {
                    alertMsg('success', '완료되었습니다.', function() {alertOff(); window.location.reload();} );
                }
           }
        });
    }

}




</script>
<!-- 버니 결제 내역 끝 -->






<?php
include_once ('./admin.tail.php');
include_once ($_SERVER['DOCUMENT_ROOT'].'/beosyong/alert.php');
?>
