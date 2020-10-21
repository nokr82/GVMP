<?php
$sub_menu = "200950";
include_once('./_common.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');

$g5['title'] = '신분증 인증 시스템';
include_once (G5_ADMIN_PATH.'/admin.head.php');

include_once('./admin_menu_check.php');

if ($is_admin != 'super') {
    menu_check($sub_menu,$member['mb_id']);
}
$colspan = 5;

include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
if (empty($fr_date) || ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = G5_TIME_YMD;
if (empty($to_date) || ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = G5_TIME_YMD;

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


?>
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<!--신분증 인증 시스템 시작-->
<div class="certify_id_card_wrap ">
    <!-- 분류 보기 시작  -->
    <form action="" method="get" name="sch_form" id="sch_form" class="local_sch01" onsubmit="return confirmForm()">
    <div class="sch_box">
        <div class="sch_select">
            <label for="sch_sort" class="sound_only">상태 조회</label>
            <select name="sch_sort" id="sch_sort">
                <option value="all">승인상태</option>
                <option value="await_approval" <?php if($_GET["sch_sort"]=="await_approval") {echo "selected='true'";} ?>>승인대기</option>
                <option value="seal_approval" <?php if($_GET["sch_sort"]=="seal_approval") {echo "selected='true'";} ?>>승인완료</option>
                <option value="reject_approval" <?php if($_GET["sch_sort"]=="reject_approval") {echo "selected='true'";} ?>>승인거절</option>
            </select>
            <label for="sch_sort_2" class="sound_only">목록 조회</label>
            <select name="sch_sort_2" id="sch_sort_2">
                <option value="">분류 선택</option>
                <option value="mb_name" <?php if($_GET["sch_sort_2"]=="mb_name") {echo "selected='true'";} ?>>이름</option>
                <option value="mb_id" <?php if($_GET["sch_sort_2"]=="mb_id") {echo "selected='true'";} ?>>아이디</option>
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
    
    <!-- 내역 시작 -->
    <form action="" method="" name="list_form" id="list_form">
    <div class="v_tbl">
        <table>
            <caption>신분증 인증 내역</caption>
            <thead>
                <tr>
                    <th>등록일</th>
                    <th>이름</th>
                    <th>아이디</th>
                    <th>주민등록번호</th>
                    <th>상태</th>
                </tr>
            </thead>
            <tbody>
                
<?php
    

    $sql = "select * from idCertificateTBL";
    $sql .= " where datetime >= '{$fr_date} 00:00:00' and datetime <= '{$to_date} 23:59:59'";

    if( $_GET["sch_sort"] == "await_approval" ) { // 승인 대기
        $sql .= " and ok = 'W'";
    } else if( $_GET["sch_sort"] == "seal_approval" ) { // 승인 완료
        $sql .= " and ok = 'Y'";
    } else if( $_GET["sch_sort"] == "reject_approval" ) { // 승인 거절
        $sql .= " and ok = 'N'";
    }
    
    if( $_GET["sch_sort_2"] == "mb_name" && $_GET["sch_word"] != "" ) {
        $sql .= " and name like '%{$_GET["sch_word"]}%'";
    } else if( $_GET["sch_sort_2"] == "mb_id" && $_GET["sch_word"] != "" ) {
        $sql .= " and mb_id like '%{$_GET["sch_word"]}%'";
    }
    $sql .= " order by datetime desc";
    $idCertificateRe = mysql_query($sql);
    $i = 0;
    while( $idCertificateRow = mysql_fetch_array($idCertificateRe) ) {
        $i++;
        ?>
            <tr>
                <td><?=$idCertificateRow["datetime"]?></td>
                <td><?=$idCertificateRow["name"]?></td>
                <td><?=$idCertificateRow["mb_id"]?></td>
                <td><?=$idCertificateRow["RRN1"]?>-<?=$idCertificateRow["RRN2"]?></td>
                <td>
<?php
    if( $idCertificateRow["ok"] == "W" ) {
        ?> <a href="./certify_id_card_detail.php?no=<?=$idCertificateRow["no"]?>" class="adm_btn btn_await">승인대기</a> <?php
    } else if( $idCertificateRow["ok"] == "Y" ) {
        ?> <a href="./certify_id_card_detail.php?no=<?=$idCertificateRow["no"]?>" class="adm_btn btn_seal">승인완료</a> <?php
    } else if( $idCertificateRow["ok"] == "N" ) {
        ?> <a href="./certify_id_card_detail.php?no=<?=$idCertificateRow["no"]?>" class="adm_btn btn_reject">승인거절</a> <?php
    }
?>
                </td>
            </tr>
        <?php
    }
    if( $i == 0 ) {
        ?>
            <tr>
                <td colspan="5">조회된 내역이 없습니다.</td>
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
<!--신분증 인증 시스템 끝-->




<script>


function confirmForm(){
    return true;
}


$(function(){
    $('#fr_date,  #to_date').datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });
});

</script>