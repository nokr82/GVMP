<!-- 관리자 인스타그램 체험단 -->
<?php 
$sub_menu = "010200";
include_once('./_common.php');
$g5['title'] = "버니 센터 관리 > 인스타그램 마케팅";
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

<!-- 인스타그램 체험단 시작 -->
<div class="campaign_wrap campaign_insta_wrap">
    <div class="campaign_insta_content">
        <div class="campaign_tab">
            <strong>인스타그램 마케팅</strong>
        </div>
        <form name="campaign_insta_form" id="campaign_insta_form" method="get">
            <div class="camp_sch_tit_box">
                <label for="camp_insta_tit" class="bs_label">캠페인 제목</label>
                <input type="text" value="<?php if($_GET["camp_insta_tit"] != "") {echo $_GET["camp_insta_tit"];} ?>" name="camp_insta_tit" id="camp_insta_tit"  class="bs_input" placeholder="캠페인명을 입력해주세요">
            </div>
            <div class="campaign_bs_sch_box">
                <select name="camp_bs_select" class="bs_select">
                    <option value="">- ID, 이름 선택 -</option>
                    <option value="mb_id" <?php if($_GET["camp_bs_select"] == "mb_id") {echo "selected='true'";} ?>>ID</option>
                    <option value="mb_name" <?php if($_GET["camp_bs_select"] == "mb_name") {echo "selected='true'";} ?>>이름</option>
                </select>
                <label for="camp_insta_mb_name" class="sound_only">ID 검색</label>
                <input type="text" value="<?php if($_GET["camp_insta_mb_name"] != "") {echo $_GET["camp_insta_mb_name"];} ?>" name="camp_insta_mb_name" id="camp_insta_mb_name"  class="bs_input" placeholder="검색어를 입력해주세요">
            </div>
            <div class="campaign_insta_sch sch_list">
                <input type="text" name="fr_date" id="fr_date" class="frm_input" value="<?php echo $_GET["fr_date"]; ?>" size="11" maxlength="10" autocomplete="off">
                <label for="fr_date" class="sound_only">시작일</label>
                <i>~</i>
                <input type="text" name="to_date" id="to_date" class="frm_input" value="<?php echo $_GET["to_date"]; ?>" size="11" maxlength="10" autocomplete="off">
                <label for="to_date" class="sound_only">종료일</label>
                <input type="submit" value="조회" class="sch_submit_btn">
            </div>
        </form>
        <div class="campaign_insta_table table_wrap">
            <table>
                <caption>인스타그램 마케팅 내역</caption>
                <thead>
                    <tr>
                        <th scope="col">날짜</th>
                        <th scope="col" class="th_campaign_tit">제목</th>
                        <th scope="col">아이디</th>
                        <th scope="col">이름</th>
                        <th scope="col" class="th_campaign_url">URL</th>
                        <th scope="col">정산</th>
                    </tr>
                </thead>
                <tbody>
                    
<?php
    $sql = "select *, a.mb_id as MBID from instagramApplyListTBL as a left join g5_write_instagram as b on a.wr_id = b.wr_id";
    if( $_GET["fr_date"] != "" && $_GET["to_date"] != "" ) {
        $sql .= " where (a.datetime >= '{$_GET["fr_date"]} 00:00:00' and a.datetime <= '{$_GET["to_date"]} 23:59:59')";
    }

    
    if( $_GET["camp_insta_tit"] != "" ) {
        $sql .= " and wr_subject like '%{$_GET["camp_insta_tit"]}%'";
    }
    
    if( $_GET["camp_bs_select"] == "mb_id" ) {
        $sql .= " and a.mb_id = '{$_GET["camp_insta_mb_name"]}'";
    }
    
    $sql .= " order by datetime desc";
    $instagramApplyListRe = mysql_query($sql);
    $i = 0;
    while( $instagramApplyListRow = mysql_fetch_array($instagramApplyListRe) ) {
        $i++;
        $row = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$instagramApplyListRow["MBID"]}'"));
        
        if( $_GET["camp_bs_select"] == "mb_name" && $row["mb_name"] != $_GET["camp_insta_mb_name"] ) {
            continue;
        }
        ?>
            <tr>
                <td class="td_campaign_tit"><?=$instagramApplyListRow["datetime"]?></td>
                <td class="td_campaign_tit"><?=$instagramApplyListRow["wr_subject"]?></td>
                <td class="td_mb_id"><a target="_blank" href="/adm/member_form.php?sst=&sod=&sfl=&stx=&page=&w=u&mb_id=<?=$row["mb_id"]?>"><?=$instagramApplyListRow["MBID"]?></a></td>
                <td class="td_mb_name"><a target="_blank" href="/adm/pay_point.php?point_id=<?=$instagramApplyListRow["MBID"]?>&fr_date=2019-01-01&to_date=2029-08-13"><?=$row["mb_name"]?></a></td>
                <td class="td_campaign_url"><a class="" target="_blank" href="<?=$instagramApplyListRow["url"]?>"><?=$instagramApplyListRow["url"]?></a></td>
                <td class="td_campaign_calc">
<?php
    if( $instagramApplyListRow["calculateCheck"] == "N" ) {
        ?>
                    <a href="#none" class="adm_btn camp_calc_btn" onclick="calculate('<?=$instagramApplyListRow["MBID"]?>', <?=$instagramApplyListRow["wr_id"]?>);">정산하기</a>  
        <?php
    } else if ( $instagramApplyListRow["calculateCheck"] == "Y" ) {
        ?>
            <a href="#none" class="adm_btn camp_pay_btn">지급완료</a>
        <?php
    }
?>
                    
                    
                </td>
            </tr> 
        <?php
    }
    
    if( $i == 0 ) {
        ?>
            <tr>
                <td class="td_campaign_tit" colspan="5">조회 내역이 존재하지 않습니다.</td>
                <td class="td_mb_id"></td>
                <td class="td_mb_name"></td>
                <td class="td_campaign_url"></td>
                <td class="td_campaign_calc"> </td>
            </tr> 
        <?php
    }
?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- 인스타그램 체험단 끝 -->


<script>

 
$(function(){
    $('#fr_date,  #to_date').datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });
});



function calculate(mb_id, wr_id) {
    alertMsg('confirm', '정산하시겠습니까?', function() {alertOff(); GO(mb_id, wr_id);}, function() {alertOff();} );
    
    function GO(mb_id, wr_id) {
        $.ajax({
            url:'/beosyong/instagramCalculate_back.php',
            type:'POST',
            data: "mb_id=" + mb_id + "&wr_id=" + wr_id,
            async: false,
            success:function(result){
                if( result == "false" ) {
                    alertMsg('error', '에러가 발생했습니다.', function() {alertOff();} );
                } else {
                    alertMsg('success', '정산이 완료되었습니다.', function() {alertOff(); window.location.reload();} );
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

