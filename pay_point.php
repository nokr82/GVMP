<!-- 관리자 포인트지급 내역  -->
<?php 
$sub_menu = "000200";
include_once('./_common.php');
$g5['title'] = "포인트관리 > 포인트 지급 내역";
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
    $_GET["fr_date"] = date("Y-m-d");
}
if( $_GET["to_date"] == "" ) {
    $_GET["to_date"] = date("Y-m-d");
}


?>

<!-- 포인트지급 내역 시작 -->
<div class="pay_point_wrap">
    <div class="pay_point_content">
        <div class="pay_point_view view_sum_box">
            <div class="total_reqst view_cont">
                <span class="reqst_info_box info_box">
                    <em>총 지급된 포인트 내역</em>
                    <strong>&#92;<b id="totalMoney"></b>원</strong>
                </span>
                <span class="reqst_icon_box icon_box">
                    <img src="./img/adm_coin.png" alt="총 지급된 포인트"/>
                </span>
            </div>
        </div>
        <form name="pay_point_form" id="pay_point_form" method="get">
            <div class="pay_point_sch_box">
                <label for="point_id" class="bs_label">ID 검색</label>
                <input type="text" name="point_id" id="point_id" class="bs_input" value="<?php if( $_GET["point_id"] != "" ) {echo $_GET["point_id"];} ?>" placeholder="검색 ID를 입력하세요">
            </div>
            <div class="pay_point_sch sch_list">
                <input type="text" name="fr_date" id="fr_date" class="frm_input" value="<?php echo $_GET["fr_date"]; ?>" size="11" maxlength="10" autocomplete="off">
                <label for="fr_date" class="sound_only">시작일</label>
                <i>~</i>
                <input type="text" name="to_date" id="to_date" class="frm_input" value="<?php echo $_GET["to_date"]; ?>" size="11" maxlength="10" autocomplete="off">
                <label for="to_date" class="sound_only">종료일</label>
                <input type="submit" value="조회" class="sch_submit_btn">
            </div>
        </form>
        <div class="pay_point_table table_wrap">
            <table>
                <caption>총 지급된 포인트 내역</caption>
                <thead>
                    <tr>
                        <th scope="col" class="th_date">날짜</th>
                        <th scope="col">아이디</th>
                        <th scope="col">이름</th>
                        <th scope="col">내용</th>
                        <th scope="col">포인트</th>
                        <th scope="col">처리</th>
                    </tr>
                </thead>
                <tbody>
                    
<?php
    $sql = "select a.*, b.mb_name from pointTBL as a left join g5_member as b on a.mb_id = b.mb_id";
    if( $_GET["fr_date"] != "" && $_GET["to_date"] != "" ) {
        $sql .= " where (a.datetime >= '{$_GET["fr_date"]} 00:00:00' and a.datetime <= '{$_GET["to_date"]} 23:59:59')";
    }
    $sql .= "and a.way != '누적용' and a.way != '출금신청취소'";
    
    if( $_GET["point_id"] != "" ) {
        $sql .= " and b.mb_id like '%{$_GET["point_id"]}%'";
    }
    
    $sql .= " order by a.datetime desc";
    $pointListRe = mysql_query($sql);
    $i = 0;
    $totalPoint = 0;
    while( $pointListRow = mysql_fetch_array($pointListRe) ) {
        $i++;
        $totalPoint += $pointListRow["point"];
        ?>
            <tr>
                <td class="td_date"><?=$pointListRow["datetime"]?></td>
                <td class="td_mb_id"><?=$pointListRow["mb_id"]?></td>
                <td class="td_mb_name"><?=$pointListRow["mb_name"]?></td>
                <td class="td_mb_bank"><?=$pointListRow["way"]?></td>
                <td class="td_mb_point"><?php echo number_format($pointListRow["point"]);?>P</td>
                <td class=""><a href="#none" class="adm_btn tbl_btn adm_delete_btn" onclick="deletePoint(<?=$pointListRow["no"]?>)">삭제</a></td>
            </tr>
        <?php
    }
    if( $i == 0 ) {
        ?>
            <tr>
                <td class="td_date" colspan="5">조회 내역이 존재하지 않습니다.</td>
                <td class="td_mb_id"></td>
                <td class="td_mb_name"></td>
                <td class="td_mb_bank"></td>
                <td class="td_mb_point"></td>
            </tr>
        <?php
    }
?>

<script>
    $("#totalMoney").text("<?php echo number_format($totalPoint); ?>");
</script>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- 포인트지급 내역 끝 -->

<script>

 
$(function(){
    $('#fr_date,  #to_date').datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });
});




//지급내역 삭제하기
function deletePoint(no){
    alertMsg('confirm', '포인트 지급내역을 삭제하시겠습니까?', function() {alertOff(); GO(no);}, function() {alertOff();} );

    function GO(no) {
        $.ajax({
            url:'/beosyong/pay_point_delete_back.php',
            type:'POST',
            data: "no=" + no,
            async: false,
            success:function(result){
                if( result == "false" ) {
                    alertMsg('error', '에러가 발생했습니다.', function() {alertOff();} );
                } else {
                    alertMsg('success', '삭제가 완료되었습니다.', function() {alertOff(); window.location.reload();} );
                }
           }
        });
    }
}








</script>

<?php
include_once ('./admin.tail.php');
include_once ($_SERVER['DOCUMENT_ROOT'].'/beosyong/alert.php');
?>

