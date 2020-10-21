<?php
$sub_menu = "200990";
include_once('./_common.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');


$g5['title'] = '차량 유지비 지급 목록';
include_once (G5_ADMIN_PATH.'/admin.head.php');

include_once('./admin_menu_check.php');

if ($is_admin != 'super') {
    menu_check($sub_menu,$member['mb_id']);
}
$colspan = 4;

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

<div class="adm_car">
    <!-- 분류 보기 시작  -->
    <form action="" method="get" name="simple_join_form" id="simple_join_form" class="local_sch01" onsubmit="return simpleJoinForm()">
        <div class="simple_join_box">
            <label for="sch_sort" class="sound_only">목록 조회</label>
            <select name="sfl" id="sch_sort" class="search_sort">
                <option value="" selected>연도 선택</option>
                <option value="">2019년</option>
            </select>
            <select name="sfl" id="sch_sort" class="search_sort">
                <option value="" selected>월 선택</option>
                <option value="">1월</option>
            </select>

            <label for="sch_word" class="sound_only">검색어</label>
            <div class="sch_list">
                <select name="">
                    <option selected>분류 선택</option>
                    <option>아이디</option>
                    <option>이름</option>
                </select>
                <input type="text" class="frm_input"/>
                <input type="submit" value="조회" class="btn_submit">
            </div>
        </div>
    </form>

    <form action="" method="" name="" id="">
        <div class="tbl_head01">
            <table>
                <caption>차량 유지비 지급 리스트</caption>
                <colgroup>
                    <col width="25%">
                    <col width="20%" />
                    <col width="30%" />

                </colgroup>
                <thead>
                <tr>
                    <th>지급일</th>
                    <th>아이디</th>
                    <th>이름</th>
                    <th>지급 금액</th>
                </tr>
                </thead>
                <tbody>

                <?php
                $sql = "select A.*, B.mb_name as constructor_name from (select a.mb_id, b.mb_name, a.constructor_id, a.datetime from membershipListTBL as a left join g5_member as b on a.mb_id = b.mb_id order by a.datetime) as A left join g5_member as B on A.constructor_id = B.mb_id where A.datetime >= '{$fr_date} 00:00:00' and A.datetime <= '{$to_date} 23:59:59'";
                if( $_GET["sfl"] != "" && $_GET["sch_word"] != "" ) {
                    $sql .= " and";
                    if( $_GET["sfl"] == "mb_id" ) {
                        $sql .= " A.mb_id = '{$_GET["sch_word"]}' ";
                    } else if( $_GET["sfl"] == "mb_name" ) {
                        $sql .= " A.mb_name = '{$_GET["sch_word"]}' ";
                    } else if( $_GET["sfl"] == "buyer_mb_id" ) {
                        $sql .= " A.constructor_id = '{$_GET["sch_word"]}' ";
                    } else if( $_GET["sfl"] == "buyer_mb_name" ) {
                        $sql .= " B.mb_name = '{$_GET["sch_word"]}' ";
                    }
                }
                $sql .= " order by A.datetime desc";
                $re = mysql_query($sql);
                while( $row = mysql_fetch_array($re) ) {
                    ?>
                    <tr>
                        <td><?=$row["datetime"]?></td>
                        <td><?=$row["mb_id"]?></td>
                        <td><?=$row["mb_name"]?></td>
                        <td></td>
                    </tr>
                    <?php
                }
                ?>




            </table>
        </div>
    </form>
</div>