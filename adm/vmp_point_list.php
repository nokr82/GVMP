<?php
$sub_menu = "200999";
include_once('./_common.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');

$g5['title'] = '포인트충전내역';
include_once(G5_ADMIN_PATH . '/admin.head.php');

include_once('./admin_menu_check.php');

if ($is_admin != 'super') {
    menu_check($sub_menu, $member['mb_id']);
}
$colspan = 5;

include_once(G5_PLUGIN_PATH . '/jquery-ui/datepicker.php');
if (empty($fr_date) || !preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date))
    $fr_date = G5_TIME_YMD;
if (empty($to_date) || !preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date))
    $to_date = G5_TIME_YMD;

$qstr = "fr_date=" . $fr_date . "&amp;to_date=" . $to_date;
$query_string = $qstr ? '?' . $qstr : '';


$fr_date = date("Y-m-d");
$to_date = date("Y-m-d");
$sch_sort_1 = $_GET['sch_sort_1']; //타입
$sch_sort_2 = $_GET['sch_sort_2']; //분류선택
$sch_word = $_GET["sch_word"]; //검색어

if ($_GET["fr_date"] != "") {
    $fr_date = $_GET["fr_date"];
}
if ($_GET["to_date"] != "") {
    $to_date = $_GET["to_date"];
}
?>
<style>
    .v_tbl .bg1 {
        background: #dee8f7 !important
    }
</style>
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css"
      rel="stylesheet"/>
<link rel="stylesheet" href="../myOffice/css/loading.css"/>


<!--신분증 인증 시스템 시작-->
<div class="certify_id_card_wrap ">
    <!-- 분류 보기 시작  -->
    <form action="" method="get" name="sch_form" id="sch_form" class="local_sch01" onsubmit="return confirmForm()">
        <div class="sch_box">
            <div class="sch_select">
                <label for="sch_sort_2" class="sound_only">목록 조회</label>
                <select name="sch_sort_1" id="sch_sort_1">
                    <option value="">전체</option>
                    <option value="1" <?php if ($sch_sort_1 == "1") echo "selected='true'";  ?>>가상결제</option>
                    <option value="2" <?php if ($sch_sort_1 == "2") echo "selected='true'"; ?>>카드결제3</option>
                </select>
                <select name="sch_sort_2" id="sch_sort_2">
                    <option value="">분류 선택</option>
                    <option value="mb_id" <?php if ($sch_sort_2 == "mb_id") echo "selected='true'"; ?>>결재자ID</option>
                    <option value="pay_name" <?php if ($sch_sort_2 == "pay_name") echo "selected='true'"; ?>>이름</option>
                    <option value="pay_tel" <?php if ($sch_sort_2 == "pay_tel") echo "selected='true'"; ?>>연락처</option>
                </select>
                <label for="sch_word" class="sound_only">검색어</label>
                <input type="text" name="sch_word" id="sch_input" value="<?= $sch_word ?>"
                       placeholder="검색어를 입력해주세요.">
            </div>
            <div class="sch_list">
                <input type="text" name="fr_date" id="fr_date" class="frm_input" value="<?= $fr_date ?>" size="11"
                       maxlength="10" autocomplete="off" placeholder="시작일">
                <label for="fr_date" class="sound_only">시작일</label>
                <i>~</i>
                <input type="text" name="to_date" id="to_date" class="frm_input" value="<?= $to_date ?>" size="11"
                       maxlength="10" autocomplete="off" placeholder="종료일">
                <label for="to_date" class="sound_only">종료일</label>
                <input type="submit" value="조회" class="btn_submit">
            </div>
        </div>
    </form>
    <!-- 분류 보기 끝  -->

    <!-- 내역 시작 -->
    <form action="" method="" name="" id="">
        <div class="v_tbl">
            <table>
                <caption>회원 정보 변경 이력 목록</caption>
                <colgroup>
                    <col width="15%"/>
                    <col width="20%"/>
                    <col width="25%"/>
                    <col/>
                </colgroup>
                <thead>
                <tr>
                    <th>결재자ID</th>
                    <th>이름</th>
                    <th>연락처</th>
                </tr>
                <tr>
                    <th>타입</th>
                    <th>충전금액</th>
                    <th>결제시간</th>
                </tr>
                </thead>
                <tbody>

                <?php


                $where = "where datetime BETWEEN '$fr_date 00:00:00' AND '$to_date 23:59:59'";

                $sql = "select * from pay_list ";

                if ($sch_sort_2 == "pay_name" && $sch_word != "") {
                    $where .= " and (pay_name like '%$sch_word%')";
                } else if ($sch_sort_2 == "mb_id" && $sch_word != "") {
                    $where .= " and (mb_id like '%$sch_word%')";
                }else if($sch_sort_2 == "pay_tel" && $sch_word){
                    $where .= " and (pay_tel = '$sch_word')";
                }

                if ($sch_sort_1 == "1") { // 가상결제
                    $where_and = " and pay_type = 1";
                } else if ($sch_sort_1 == "2") { // 카드결제3
                    $where_and = " and pay_type = 2";
                }
                $sql .= "{$where} {$where_and} order by no desc";
                $modi_list = mysql_query($sql);
                $i = 0;
                while ($row = mysql_fetch_array($modi_list)) {
                    $i++;
                    ?>
                    <tr class="bg<?php
                    if ($i % 2 == 0) { //이 조건이 만족하면 짝수이다.
                        echo '0';
                    } else { //조건에 만족하지않으면 홀수이다.
                        echo '1';
                    }
                    ?>">
                        <td><?= $row["mb_id"] ?></td>
                        <td><?= $row["pay_name"] ?></td>
                        <td><?= $row["pay_tel"] ?></td>
                    </tr>
                    <tr class="bg<?php
                    if ($i % 2 == 0) { //이 조건이 만족하면 짝수이다.
                        echo '0';
                    } else { //조건에 만족하지않으면 홀수이다.
                        echo '1';
                    }
                    ?>">
                        <?php
                        if ($row["pay_type"] == '1') {
                            $row["pay_type"] = "가상결제";
                        } else if ($row["pay_type"] == '2') {
                            $row["pay_type"] = "카드결제3";
                        }
                        ?>
                        <td><?= $row["pay_type"] ?></td>
                        <td><?= number_format($row["amt"]) ?></td>
                        <td><?= $row["datetime"] ?></td>
                    </tr>

                    <?php
                }
                if ($i == 0) {
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


<div class="check_msg">
    <div class="loading_msg">
        <img src="../myOffice/simpleJoin/img/vmp_loading_icon_4.gif" alt="로딩메세지"/>
        <p>데이터를 조회중입니다. 잠시만 기다려주세요</p>
    </div>
</div>


<script>
    function loading() {
        $('.check_msg').addClass('load');
        $('.loading_msg p').text('데이터를 조회중입니다. 잠시만 기다려주세요');
    }


    function confirmForm() {
        loading()
        return true;
    }


    $(function () {
        $('#fr_date,  #to_date').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            showButtonPanel: true,
            yearRange: "c-99:c+99"
        });
    });

</script>