<!-- 언론마케팅 페이지-->

<?php
$sub_menu = "010300";
include_once('./_common.php');
$g5['title'] = "버니 센터관리 > 언론마케팅";
include_once('./admin.head.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/beosyong/dbConn.php');


if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');


include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
if (empty($fr_date) || ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = G5_TIME_YMD;
if (empty($to_date) || ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = G5_TIME_YMD;

$qstr = "fr_date=".$fr_date."&amp;to_date=".$to_date;
$query_string = $qstr ? '?'.$qstr : '';

?>

<!-- 관리자 언론마케팅 시작 -->
<div class="adm_press_wrap">
    <div class="adm_press adm_info_box">
        <span class="txt_box">
            <em>총 계약 금액</em>
            <strong>&#92;<b id="totalMoney"></b>원</strong>
        </span>
        <span class="icon_box">
            <img src="./img/adm_coin.png" alt="총 계약 금액"/>
        </span>
    </div>
    <form name="adm_press" id="adm_press" method="get">
    <div class="aadm_press_sch sch_list">
        <input type="text" name="fr_date" id="fr_date" class="frm_input" value="<?php echo $write["wr_1"]; ?>"  size="11" maxlength="10" autocomplete="off">
        <label for="fr_date" class="sound_only">시작일</label>
        <i>~</i>
        <input type="text" name="to_date" id="to_date" class="frm_input" value="<?php echo $write["wr_2"]; ?>"  size="11" maxlength="10"  autocomplete="off">
        <label for="to_date" class="sound_only">종료일</label>
        <input type="submit" value="조회" class="sch_submit_btn">
    </div>
    </form>
    <div class="adm_press_list table_wrap">
        <table>
            <caption>언론 마케팅 계약 내역</caption>
            <thead>
                <tr>
                    <th scope="col">업체명</th>
                    <th scope="col">담당자 성함</th>
                    <th scope="col">연락처</th>
                    <th scope="col">계약건수</th>
                    <th scope="col">계약 금액</th>
                    <th scope="col">첨부파일</th>
                </tr>
            </thead>
            <tbody>
                
<?php
    $fr_date; $to_date;
    if( ! isset($_GET["fr_date"]) && ! isset($_GET["to_date"]) ) {
        $fr_date = date("Y-m-d");
        $to_date = date("Y-m-d");
    } else if( isset($_GET["fr_date"]) && isset($_GET["to_date"]) ) {
        $fr_date = $_GET["fr_date"];
        $to_date = $_GET["to_date"];
    }


    $pressRe = mysql_query("select * from PressTBL where datetime >= '{$fr_date} 00:00:00' and datetime <= '{$to_date} 23:59:59' order by datetime desc");
    $money = 0;
    while( $pressRow = mysql_fetch_array($pressRe) ) {
        $money += $pressRow["money"];
?>
        <tr>
            <td class="td_press_company"><?=$pressRow["companyName"]?></td>
            <td class="td_press_incharge"><?=$pressRow["name"]?></td>
            <td class="td_press_addr"><?=$pressRow["hp"]?></td>
            <td class="td_press_contract"><?php echo number_format($pressRow["ea"]); ?></td>
            <td class="td_press_contract_price"><?php echo number_format($pressRow["money"]); ?></td>
            <td class="td_press_file">
                <a href="/upload/<?=$pressRow["file"]?>" download class="press_btn tbl_btn">DOWN</a>
            </td>
        </tr>     
<?php
    }
    
    if( $money == 0 ) {
        echo "<tr><td colspan='6'>조회 결과가 없습니다.</td></tr>";
    }
?>
        
        <script>
            $("#totalMoney").text('<?php echo number_format($money); ?>');
            $("#fr_date").val('<?=$fr_date?>');
            $("#to_date").val('<?=$to_date?>');
        </script>
            </tbody>
        </table>
    </div>
</div>
<!-- 관리자 언론마케팅 끝 -->
<script>

$(function(){
    $('#fr_date,  #to_date').datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });
});

</script>



<?php
include_once ('./admin.tail.php');
?>
