<?php
$sub_menu = "200930";
include_once('./_common.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');


$g5['title'] = '광고관리';
include_once (G5_ADMIN_PATH.'/admin.head.php');


$colspan = 5;



include_once('./admin_menu_check.php');

if ($is_admin != 'super') {
     menu_check($sub_menu,$member['mb_id']);
}


include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
if (empty($fr_date) || ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = G5_TIME_YMD;
if (empty($to_date) || ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = G5_TIME_YMD;

$qstr = "fr_date=".$fr_date."&amp;to_date=".$to_date;
$query_string = $qstr ? '?'.$qstr : '';


$whereSQL = "";
if( ($_GET["fr_date"] != "" && $_GET["to_date"] != "") || ($_GET["sch_sort"] != "all" && $_GET["sch_sort"] != "") || $_GET["sch_sort_2"] != "" ) {
    $whereSQL .= " where ";
}

if( $_GET["fr_date"] != "" && $_GET["to_date"] != "" ) {
    $whereSQL .= "datetime >= '{$_GET["fr_date"]} 00:00:00' and datetime <= '{$_GET["to_date"]} 23:59:59' ";
}

if( $_GET["sch_sort"] != "all" ) {
    if( $_GET["fr_date"] != "" && $_GET["to_date"] != "" ) {
        $whereSQL .= " and ";
    }
    if( $_GET["sch_sort"] == "seal_approval" ) {
        $whereSQL .= "ok = 'Y'";
    } else if( $_GET["sch_sort"] == "reject_approval" ) {
        $whereSQL .= "ok = 'N'";
    } else if( $_GET["sch_sort"] == "await_approval" ) {
        $whereSQL .= "ok = 'W'";
    }
}

if( $_GET["sch_sort_2"] != "" ) {
    if( ($_GET["fr_date"] != "" && $_GET["to_date"] != "") || $_GET["sch_sort"] != "all" ) {
        $whereSQL .= " and ";
    }
    if( $_GET["sch_sort_2"] == "mb_name" ) {
        $whereSQL .= "mb_name like '%{$_GET["sch_word"]}%'";
    } else if( $_GET["sch_sort_2"] == "mb_id" ) {
        $whereSQL .= "a.mb_id like '%{$_GET["sch_word"]}%'";
    } else if( $_GET["sch_sort_2"] == "mb_company" ) {
        $whereSQL .= "companyName like '%{$_GET["sch_word"]}%'";
    } else if( $_GET["sch_sort_2"] == "mb_ad_tit" ) {
        $whereSQL .= "adName like '%{$_GET["sch_word"]}%'";
    }
}





?>
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />

<div class="adm_v_ad_center">
    <div class="nav_tab"></div>
    <!-- 분류 보기 시작  -->
    <form action="" method="get" name="sch_form" id="sch_form" class="local_sch01" onsubmit="return confirmForm()">
    <div class="sch_box">
        <div class="sch_select">
            <label for="sch_sort" class="sound_only">상태 조회</label>
            <select name="sch_sort" id="sch_sort">
                <option value="all">승인상태</option>
                <option value="await_approval" <?php if($_GET["sch_sort"] == "await_approval") {echo 'selected="true"';} ?>>승인대기</option>
                <option value="seal_approval" <?php if($_GET["sch_sort"] == "seal_approval") {echo 'selected="true"';} ?>>승인완료</option>
                <option value="reject_approval" <?php if($_GET["sch_sort"] == "reject_approval") {echo 'selected="true"';} ?>>승인거절</option>
            </select>
            <label for="sch_sort_2" class="sound_only">목록 조회</label>
            <select name="sch_sort_2" id="sch_sort_2">
                <option value="">분류 선택</option>
                <option value="mb_name" <?php if($_GET["sch_sort_2"] == "mb_name") {echo 'selected="true"';} ?>>이름</option>
                <option value="mb_id" <?php if($_GET["sch_sort_2"] == "mb_id") {echo 'selected="true"';} ?>>아이디</option>
                <option value="mb_company" <?php if($_GET["sch_sort_2"] == "mb_company") {echo 'selected="true"';} ?>>회사명</option>
                <option value="mb_ad_tit" <?php if($_GET["sch_sort_2"] == "mb_ad_tit") {echo 'selected="true"';} ?>>광고명</option>
            </select>
            <label for="sch_word" class="sound_only">검색어</label>
            <input type="text" name="sch_word" id="v_ad_sch_txt" value="<?=$_GET["sch_word"]?>" placeholder="검색어를 입력해주세요.">
        </div>
        <div class="v_ad_sch sch_list">
            <input type="text" name="fr_date" id="fr_date" class="frm_input" value="<?=$_GET["fr_date"]?>" size="11" maxlength="10" autocomplete="off" placeholder="시작일">
            <label for="fr_date" class="sound_only">시작일</label>
            <i>~</i>
            <input type="text" name="to_date" id="to_date" class="frm_input" value="<?=$_GET["to_date"]?>" size="11" maxlength="10" autocomplete="off" placeholder="종료일">
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
            <caption>광고 신청 내역</caption>
            <thead>
                <tr>
                    <th>등록일</th>
                    <th>광고유형</th>
                    <th>광고명</th>
                    <th>이름(아이디)</th>
                    <th>회사명</th>
                    <th>하루예산</th>
                    <th>ON/OFF</th>
                    <th>상태</th>
                </tr>
            </thead>
            <tbody>
                
<?php
    $i = 0;
    $imageAdListRe = mysql_query("select a.*, b.mb_name from imageAdListTBL as a left join g5_member as b on a.mb_id = b.mb_id {$whereSQL}");
    while( $imageAdListRow = mysql_fetch_array($imageAdListRe) ) {
        $i++;
        ?>
            <tr>
                <td><?=$imageAdListRow["datetime"]?></td>
                <td>이미지</td>
                <td class="v_ad_tit"><?=$imageAdListRow["adName"]?></td>
                <td><?=$imageAdListRow["mb_name"]?>(<?=$imageAdListRow["mb_id"]?>)</td>
                <td><?=$imageAdListRow["companyName"]?></td>
                <td><?php echo number_format($imageAdListRow["budget"]) ?>원</td>
                <td class="ad_toggle <?php if($imageAdListRow["state"]=="Y") {echo "on";} ?>" onclick="onOffFunc($(this), 'image', <?=$imageAdListRow["no"]?>);">
                    <a href="#none" class="btn_toggle">광고 상태 변경</a>
                </td>
                <td>
                    <?php
                        if( $imageAdListRow["ok"] == "Y" ) {
                            ?> <a href="./v_ad_adm_center_detail.php?type=image&no=<?=$imageAdListRow["no"]?>" class="adm_btn btn_seal">승인완료</a> <?php
                        } else if( $imageAdListRow["ok"] == "W" ) {
                            ?> <a href="./v_ad_adm_center_detail.php?type=image&no=<?=$imageAdListRow["no"]?>" class="adm_btn btn_await">승인대기</a> <?php
                        } else if( $imageAdListRow["ok"] == "N" ) {
                            ?> <a href="./v_ad_adm_center_detail.php?type=image&no=<?=$imageAdListRow["no"]?>" class="adm_btn btn_reject">승인거절</a> <?php
                        }
                    ?>
                </td>
            </tr>
        <?php
    }
?>
            
<?php
    $textAdListRe = mysql_query("select a.*, b.mb_name from textAdListTBL as a left join g5_member as b on a.mb_id = b.mb_id {$whereSQL}");
    while( $textAdListRow = mysql_fetch_array($textAdListRe) ) {
        $i++;
        ?>
            <tr>
                <td><?=$textAdListRow["datetime"]?></td>
                <td>텍스트</td>
                <td class="v_ad_tit"><?=$textAdListRow["adName"]?></td>
                <td><?=$textAdListRow["mb_name"]?>(<?=$textAdListRow["mb_id"]?>)</td>
                <td><?=$textAdListRow["companyName"]?></td>
                <td><?php echo number_format($textAdListRow["budget"]) ?>원</td>
                <td class="ad_toggle <?php if($textAdListRow["state"]=="Y") {echo "on";} ?>" onclick="onOffFunc($(this), 'text', <?=$textAdListRow["no"]?>);">
                    <a href="#none" class="btn_toggle">광고 상태 변경</a>
                </td>
                <td>
                    <?php
                        if( $textAdListRow["ok"] == "Y" ) {
                            ?> <a href="./v_ad_adm_center_detail.php?type=text&no=<?=$textAdListRow["no"]?>" class="adm_btn btn_seal">승인완료</a> <?php
                        } else if( $textAdListRow["ok"] == "W" ) {
                            ?> <a href="./v_ad_adm_center_detail.php?type=text&no=<?=$textAdListRow["no"]?>" class="adm_btn btn_await">승인대기</a> <?php
                        } else if( $textAdListRow["ok"] == "N" ) {
                            ?> <a href="./v_ad_adm_center_detail.php?type=text&no=<?=$textAdListRow["no"]?>" class="adm_btn btn_reject">승인거절</a> <?php
                        }
                    ?>
                </td>
            </tr>
        <?php
    }
    if( $i == 0 ) {
        ?>
            <tr>
                <td colspan="8">내역이 존재하지 않습니다.</td>
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


<?php
include_once ('../myOffice/v_ad_alert.php');
?>
<?php
include_once ('../myOffice/alert.php');
?>





<script>

$(function(){
    $('#fr_date,  #to_date').datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });
});


//ON/OFF 스위치 
function onOffFunc(obj, type, no) {
    var check = "";
    if( obj.hasClass('on') ){
        obj.removeClass('on');
        alertMsg('on', 'ON/OFF 알림서비스', '광고서비스가 ', '비활성화(OFF)', ' 되었습니다.', '', function(){});
        check = "off";
    } else {
        obj.addClass('on')
        alertMsg('on', 'ON/OFF 알림서비스', '광고서비스가 ', '활성화(ON)', ' 되었습니다.', '', function(){});
        check = "on";
    }
    
     $.ajax({
        url:'/myOffice/v_ad_onOff_back.php',
        type:'POST',
        data: "type="+type+"&no="+no+"&check="+check,
        async: false,
        success:function(result){
            if( result == "false" ) {
                alertMsg('error','에러가 발생했습니다.');
            } else {
                
            }
       }
    });
}

</script>