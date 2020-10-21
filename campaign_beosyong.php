<!-- 관리자 버숑 체험단 -->
<?php 
$sub_menu = "010100";
include_once('./_common.php');
$g5['title'] = "버니 센터 관리 > 버숑 체험단";
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

<!-- 버숑 체험단 시작 -->
<div class="campaign_wrap campaign_bs_wrap">
    <div class="campaign_bs_content">
        <div class="campaign_tab">
            <strong>버숑 체험단</strong>
        </div>
        <form name="campaign_bs_form" id="campaign_bs_form" method="get">
            <div class="campaign_bs_select_box">
                <select name="camp_bs_select_list" class="bs_select">
                    <option value="">- 전체 -</option>
                    <option value="신청자" <?php if( $_GET["camp_bs_select_list"] == "신청자" ) {echo "selected='true'";} ?>>신청자 내역</option>
                    <option value="선정자" <?php if( $_GET["camp_bs_select_list"] == "선정자" ) {echo "selected='true'";} ?>>선정자 내역</option>
                    <option value="리뷰 등록 내역" <?php if( $_GET["camp_bs_select_list"] == "리뷰 등록 내역" ) {echo "selected='true'";} ?>>리뷰등록 완료내역</option>
                </select>
            </div>
            <div class="camp_sch_tit_box">
                <label for="camp_bs_tit" class="bs_label">캠페인 제목</label>
                <input type="text" value="<?php if($_GET["camp_bs_tit"] != "") {echo $_GET["camp_bs_tit"];} ?>" name="camp_bs_tit" id="camp_bs_tit"  class="bs_input" placeholder="캠페인명을 입력해주세요">
            </div>
            <div class="campaign_bs_sch_box">
                <select name="camp_bs_select" class="bs_select">
                    <option value="">- ID, 이름 선택 -</option>
                    <option value="mb_id" <?php if($_GET["camp_bs_select"] == "mb_id") {echo "selected='true'";} ?>>ID</option>
                    <option value="mb_name" <?php if($_GET["camp_bs_select"] == "mb_name") {echo "selected='true'";} ?>>이름</option>
                </select>
                <label for="camp_bs_mb_name" class="sound_only">ID 검색</label>
                <input type="text" value="<?php if($_GET["camp_bs_mb_name"] != "") {echo $_GET["camp_bs_mb_name"];} ?>" name="camp_bs_mb_name" id="camp_bs_mb_name"  class="bs_input" placeholder="검색어를 입력해주세요">
            </div>
            <div class="campaign_bs_sch sch_list">
                <input type="text" name="fr_date" id="fr_date" class="frm_input" value="<?php echo $_GET["fr_date"]; ?>" size="11" maxlength="10" autocomplete="off">
                <label for="fr_date" class="sound_only">시작일</label>
                <i>~</i>
                <input type="text" name="to_date" id="to_date" class="frm_input" value="<?php echo $_GET["to_date"]; ?>" size="11" maxlength="10" autocomplete="off">
                <label for="to_date" class="sound_only">종료일</label>
                <input type="submit" value="조회" class="sch_submit_btn">
            </div>
        </form>
        <div class="campaign_bs_table table_wrap">
            <table>
                <caption>버숑 체험단 내역</caption>
                <thead>
                    <tr>
                        <th scope="col" rowspan="2">날짜</th>
                        <th scope="col" rowspan="2" class="th_campaign_tit">제목</th>
                        <th scope="col" rowspan="2">이름</th>
                        <th scope="col" rowspan="2">연락처</th>
                        <th scope="col" rowspan="2">이메일</th>
                        <th scope="col" rowspan="2">주소</th>
                        <th scope="col" class="th_campaign_url">URL</th>
                        <th scope="col" rowspan="2">처리</th>
                    </tr>
                    <tr>
                        <th scope="col">리뷰 URL</th>
                    </tr>
                </thead>
                <tbody>
                    
<?php
    $sql = "select *, a.mb_id as MBID from blogApplyListTBL as a left join g5_write_blog as b on a.wr_id = b.wr_id";
    if( $_GET["fr_date"] != "" && $_GET["to_date"] != "" ) {
        $sql .= " where (a.datetime >= '{$_GET["fr_date"]} 00:00:00' and a.datetime <= '{$_GET["to_date"]} 23:59:59')";
    }
    
    if( $_GET["camp_bs_select_list"] == "리뷰 등록 내역" ) {
        $sql .= " and (reviewURL != '' or reviewURL is not null)";
    } else if( $_GET["camp_bs_select_list"] == "선정자" ) {
        $sql .= " and selection = 'Y'";
    } else if( $_GET["camp_bs_select_list"] == "신청자" ) {
        $sql .= " and selection = 'N'";
    }
    
    if( $_GET["camp_bs_tit"] != "" ) {
        $sql .= " and wr_subject like '%{$_GET["camp_bs_tit"]}%'";
    }
    
    if( $_GET["camp_bs_select"] == "mb_id" ) {
        $sql .= " and a.mb_id = '{$_GET["camp_bs_mb_name"]}'";
    } else if( $_GET["camp_bs_select"] == "mb_name" ) {
        $sql .= " and a.mb_name = '{$_GET["camp_bs_mb_name"]}'";
    }
    
    $sql .= " order by datetime desc";
    $blogApplyListRe = mysql_query($sql);
    $i = 0;
    while ( $blogApplyListRow = mysql_fetch_array($blogApplyListRe) ) {
        $i++;
        ?>
            <tr class="<?php echo ( $i % 2 == 0 ) ? 'bg0' : 'bg1';?>">
                <td rowspan="2" class="td_campaign_tit"><?=$blogApplyListRow["datetime"]?></td>
                <td rowspan="2" class="td_campaign_tit"><?=$blogApplyListRow["wr_subject"]?></td>
                <td rowspan="2" class="td_mb_name"><a target="_blank" href="/adm/pay_point.php?point_id=<?=$blogApplyListRow["MBID"]?>&fr_date=2019-01-01&to_date=2029-08-13"><?=$blogApplyListRow["mb_name"]?></a></td>
                <td rowspan="2"><?=$blogApplyListRow["mb_hp"]?></td>
                <td rowspan="2"><?=$blogApplyListRow["mb_email"]?></td>
                <td rowspan="2" class="td_mb_addr1"><?=$blogApplyListRow["mb_addr1"]?> (<?=$blogApplyListRow["mb_addr2"]?>)</td>
                <td class="td_campaign_url"><a class="" target="_blank" href="<?=$blogApplyListRow["url"]?>"><?=$blogApplyListRow["url"]?></a></td>
                <td rowspan="2" class="td_campaign_calc">
                    
                    
<?php
    if( $blogApplyListRow["calculateCheck"] == "N" && $blogApplyListRow["selection"] == "N" ) {
        ?> <a href="#none" class="adm_btn" onclick="applyBeosyong('<?=$blogApplyListRow["MBID"]?>', <?=$blogApplyListRow["wr_id"]?>)">선정하기</a> <?php
    } else if( $blogApplyListRow["calculateCheck"] == "N" && $blogApplyListRow["selection"] == "Y" ) {
        ?>
                    <a href="#none" class="adm_btn camp_calc_btn" onclick="calculate('<?=$blogApplyListRow["MBID"]?>', <?=$blogApplyListRow["wr_id"]?>)">정산하기</a>   
        <?php
    } else if ( $blogApplyListRow["calculateCheck"] == "Y" ) {
        ?>

            <a href="#none" class="adm_btn camp_pay_btn">지급완료</a>
        <?php
    }
?>
                    

                </td>
            </tr>   
            <tr class="<?php echo ( $i % 2 == 0 ) ? 'bg0' : 'bg1';?>">
                <td class="td_review_url"><?=$blogApplyListRow["reviewURL"]?></td>
            </tr>
        <?php
    }
    
    if( $i == 0 ) {
        ?>
            <tr>
                <td class="td_campaign_tit" colspan="7">조회 내역이 존재하지 않습니다.</td>
                <td class="td_mb_name"></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="td_campaign_url"></td>
                <td class="td_campaign_calc"></td>
            </tr>   
        <?php
    }
?>
                    
                    

                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- 버숑 체험단 끝 -->


<script>

 
$(function(){
    $('#fr_date,  #to_date').datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });
});


function calculate(mb_id, wr_id) {
    alertMsg('confirm', '정산하시겠습니까?', function() {alertOff(); GO(mb_id, wr_id);}, function() {alertOff();} );
    
    function GO(mb_id, wr_id) {
        $.ajax({
            url:'/beosyong/blogCalculate_back.php',
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




//버숑 체험단 선정
function applyBeosyong(mb_id, wr_id){
    
    alertMsg('confirm', '버숑체험단으로 선정하시겠습니까?', function() {alertOff(); GO(mb_id, wr_id);}, function() {alertOff();} );

    function GO(mb_id, wr_id) {
        $.ajax({
            url:'./selection_beosyong_back.php',
            type:'POST',
            data: "mb_id=" + mb_id + "&wr_id=" + wr_id,
            async: false,
            success:function(result){
                if( result == "false" ) {
                    alertMsg('error', '에러가 발생했습니다.', function() {alertOff();} );
                } else {
                    alertMsg('success', '선정이 완료되었습니다.', function() {alertOff(); window.location.reload();} );
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

