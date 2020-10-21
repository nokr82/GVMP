<!--관리자 출금신청 내역-->
<?php 
$sub_menu = "000100";
include_once('./_common.php');
$g5['title'] = "포인트관리 > 출금 신청 내역";
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

if( $_GET["fr_date"] == "" ) {
    $_GET["fr_date"] = date("Y-m-d", strtotime("-1 day"));
    $_GET["fr_time"] = "17";
}
if( $_GET["to_date"] == "" ) {
    $_GET["to_date"] = date("Y-m-d");
    $_GET["to_time"] = "17";
}


?>

<!-- 출금신청 내역 시작 -->
<div class="point_request_wrap">
    <div class="point_request_content">
        <div class="total_reqst_view">
            <div class="total_reqst mb_reqst_box">
                <span class="reqst_info_box">
                    <em>총 출금 내역</em>
                    <strong>&#92;<b id="totalMoney">3,500,000</b>원</strong>
                </span>
                <span class="reqst_icon_box">
                    <img src="./img/adm_coin.png" alt="총 결제금액"/>
                </span>
            </div>
        </div>
        

        <form name="total_request_form" id="total_request_form" method="get" onsubmit="return formCheck(this);">
        <div class="request_bn_sch sch_list">
            <input type="text" name="fr_date" id="fr_date" class="frm_input" value="<?php echo $_GET["fr_date"]; ?>" size="11" maxlength="10" autocomplete="off">
            <label for="fr_date" class="sound_only">시작일</label>
            <select name="fr_time" id="fr_time">
                <option value="">- 선택 -</option>
                <?php for ($i=0; $i< 24; $i++) { ?>
                <option value="<?php echo $i ?>" <?php if($_GET["fr_time"] == $i) {echo "selected='true'";} ?>><?php echo $i ?>시</option>
                <?php }?>
            </select>
            <i>~</i>
            <input type="text" name="to_date" id="to_date" class="frm_input" value="<?php echo $_GET["to_date"]; ?>" size="11" maxlength="10" autocomplete="off">
            <label for="to_date" class="sound_only">종료일</label>
            <select name="to_time" id="to_time">
                <option value="">- 선택 -</option>
                <?php for ($i=0; $i< 24; $i++) { ?>
                <option value="<?php echo $i ?>" <?php if($_GET["to_time"] == $i) {echo "selected='true'";} ?>><?php echo $i ?>시</option>
                <?php }?>
            </select>
            <input type="submit" value="조회" class="sch_submit_btn">
        </div>
        </form>
        <div class="request_bn table_wrap">
            <table>
                <caption>출금 신청 내역</caption>
                <thead>
                    <tr>
                        <th scope="col" class="th_date">날짜</th>
                        <th scope="col">아이디</th>
                        <th scope="col">이름</th>
                        <th scope="col">주민번호</th>
                        <th scope="col">은행</th>
                        <th scope="col" class="th_account">계좌번호</th>
                        <th scope="col">금액</th>
                        <th scope="col" style="width: 10%">내역삭제</th>
                    </tr>
                </thead>
                <tbody>
                    
<?php
    $totalMoney = 0;
    $sql = "select * from withdrawTBL";
    if( $_GET["fr_time"] == "" ) {
        $fr_time = "00:00:00";
    } else {
        $fr_time = $_GET["fr_time"] . ":00:00";
    }
    if( $_GET["to_time"] == "" ) {
        $to_time = "23:59:59";
    } else {
        $to_time = $_GET["to_time"] . ":00:00";
    }
    
    if( $_GET["fr_date"] != "" && $_GET["to_date"] != "" ) {
        $sql .= " where (datetime >= '{$_GET["fr_date"]} {$fr_time}' and datetime <= '{$_GET["to_date"]} {$to_time}')";
    }
    $sql .= " order by datetime desc";
    $withdrawRe = mysql_query($sql);
    $i = 0;
    while( $withdrawRow = mysql_fetch_array($withdrawRe) ) {
        $i++;
        if( $withdrawRow["cancel"] == 'N' ) {
            $totalMoney += $withdrawRow["point"];
        }
        $tempRow = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$withdrawRow["mb_id"]}'"));
        ?>
            <tr>
                <td class="td_date"><?=$withdrawRow["datetime"]?></td>
                <td class="td_mb_id"><?=$withdrawRow["mb_id"]?></td>
                <td class="td_mb_name"><?=$tempRow["mb_name"]?></td>
                <td class="td_mb_name"><?=$tempRow["RRN"]?></td>
                <td class="td_mb_bank"><?=$withdrawRow["bankName"]?></td>
                <td class="td_mb_account"><?=$withdrawRow["accountNumber"]?></td>
                <td class="td_mb_point"><?php echo number_format($withdrawRow["point"]);?>원</td>
                <td class="">
                    <?php 
                        if( $withdrawRow["cancel"] == "N" ) {
                            ?> <a href="#none" class="tbl_btn adm_btn adm_delete_btn" onclick="deleteForm('<?=$withdrawRow["datetime"]?>', '<?=$withdrawRow["mb_id"]?>')">삭제</a> <?php
                        } else {
                            ?> 삭제됨 <?php
                        }
                    ?>
                </td>
            </tr>
        <?php
    }
    if( $i == 0 ) {
        ?>
            <tr>
                <td class="td_date" colspan="6">조회 내역이 존재하지 않습니다.</td>
                <td class="td_mb_id"></td>
                <td class="td_mb_name"></td>
                <td class="td_mb_bank"></td>
                <td class="td_mb_account"></td>
                <td class="td_mb_point"></td>
            </tr>
        <?php
    }
?>
<script>
    $("#totalMoney").text("<?php echo number_format($totalMoney); ?>");
</script>
                    

                </tbody>
            </table>
        </div>
    </div>
</div>

<script>

 
$(function(){
    $('#fr_date,  #to_date').datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });
});


//검색 폼 값검증
function formCheck(){
    
    if( $('#fr_time').val() == '' && $('#to_time').val() !== ''){
        alertMsg('error', '시간을 선택해주세요',function() { $('#fr_time').focus(); alertOff(); });
        return false;
        
    } else if ( $('#fr_time').val() !== '' && $('#to_time').val() == '' ){

        alertMsg('error', '시간을 선택해주세요',function() { $('#to_time').focus(); alertOff();});
        return false;
        
    } else {
        return true;
    }
}

//내역 삭제하기 클릭시 알림창띄우기
function deleteForm( dateTime, mb_id ){
   alertMsg('confirm','해당내역을 삭제하시겠습니까?',function(){Go(dateTime, mb_id);},function(){ alertOff();})
   
   function Go( dateTime, mb_id ) {
       $.ajax({
             url:'point_request_back.php',
             type:'POST',
            data : "datetime="+dateTime + "&mb_id="+mb_id,
             async: false,
             success:function(result){
                 if( result == "false" ) {
                     alertMsg('error', '에러가 발생했습니다.', function() {alertOff();} );
                 } else {
                     location.reload();
                 }
            }
         });
   }
}


</script>
<!-- 출금신청 내역 끝 -->


<?php
include_once ('./admin.tail.php');
include_once ($_SERVER['DOCUMENT_ROOT'].'/beosyong/alert.php');
?>