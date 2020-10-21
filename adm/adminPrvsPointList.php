<?php
$sub_menu = "200970";
include_once('./_common.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');

$g5['title'] = '관리자 P 지급 내역';
include_once (G5_ADMIN_PATH . '/admin.head.php');
include_once ('../myOffice/point_pop_back_func.php');


$colspan = 5;



include_once('./admin_menu_check.php');

if ($is_admin != 'super') {
    menu_check($sub_menu, $member['mb_id']);
}


include_once(G5_PLUGIN_PATH . '/jquery-ui/datepicker.php');
if (empty($fr_date) || !preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date))
    $fr_date = G5_TIME_YMD;
if (empty($to_date) || !preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date))
    $to_date = G5_TIME_YMD;

$qstr = "fr_date=" . $fr_date . "&amp;to_date=" . $to_date;
$query_string = $qstr ? '?' . $qstr : '';


$fr_date = date("Y-m-d");
$to_date = date("Y-m-d");
if ($_GET["fr_date"] != "") {
    $fr_date = $_GET["fr_date"];
}
if ($_GET["to_date"] != "") {
    $to_date = $_GET["to_date"];
}
?>
<style>
    td.point {text-align:left; display:flex; flex-direction:row; justify-content:center; height:inherit; align-items:center;}
    td.point span {display:inline-block; padding:4px 0; font-size:inherit; width:18%; text-align:left;}
    td.point span:before {display:inline-block; color:#fff; padding:2px 3px; margin-right:5px; text-align:center; border-radius: 10%;}
    td.point span.safe:before {background-color:#f2920a; content:'금고';}
    td.point span.vmm:before {background-color:#2c89cc; content:'VMM'}
    td.point span.p_vmc:before {background-color:#1bb934; content:'VMC'}
    td.point span.p_vmp:before {background-color:#fc565e; content:'VMP'}
    td.point span.biz:before {background-color:#384053; content:'BIZ';}



    .v_tbl tbody tr:nth-child(even) {background-color:#dee8f7;}
</style>
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<link rel="stylesheet" href="../myOffice/css/loading.css"/>

<!--관리자 포인트 지급 내역 시작-->
<div class="certify_id_card_wrap ">
    <!-- 분류 보기 시작  -->
    <form action="" method="get" name="sch_form" id="sch_form" class="local_sch01" onsubmit="loading()">
        <div class="sch_box">
            <div class="sch_select">
                <label for="sch_sort_2" class="sound_only">목록 조회</label>
                <select name="sch_sort_2" id="sch_sort_2">
                    <option value="">분류 선택</option>
                    <option value="mb_name" <?php
                    if ($_GET["sch_sort_2"] == "mb_name") {
                        echo "selected='true'";
                    }
                    ?>>이름</option>
                    <option value="mb_id" <?php
                    if ($_GET["sch_sort_2"] == "mb_id") {
                        echo "selected='true'";
                    }
                    ?>>아이디</option>
                </select>
                <label for="sch_word" class="sound_only">검색어</label>
                <input type="text" name="sch_word" id="sch_input" value="<?= $_GET["sch_word"] ?>" placeholder="이름 또는 아이디를 입력해주세요.">
            </div>
            <div class="sch_list">
                <input type="text" name="fr_date" id="fr_date" class="frm_input" value="<?= $fr_date ?>" size="11" maxlength="10" autocomplete="off" placeholder="시작일">
                <label for="fr_date" class="sound_only">시작일</label>
                <i>~</i>
                <input type="text" name="to_date" id="to_date" class="frm_input" value="<?= $to_date ?>" size="11" maxlength="10" autocomplete="off" placeholder="종료일">
                <label for="to_date" class="sound_only">종료일</label>
                <input type="submit" value="조회" class="btn_submit">
            </div>
        </div>
    </form>
    <!-- 분류 보기 끝  -->

    <!-- 내역 시작 -->
    <form action="" method="" name="admin_provision_form" id="admin_provision_form">
        <div class="v_tbl">
            <table>
                <caption>관리자 포인트 지급 내역</caption>
                <colgroup>
                    <col width="5%"/>
                    <col width="10%"/>
                    <col width="10%"/>
                    <col width='5%'/>
                    <col />
                    <col width="17%"/>
                </colgroup>
                <thead>
                    <tr>
                        <th>날짜</th>
                        <th>관리자</th>                    
                        <th>회원</th>
                        <th>입/출금</th>
                        <th>포인트</th>
                        <th>내용</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $sql = "SELECT 
    p.date,
    m.mb_id,
    admin_id,
    mb_name,
    type,
    d.VMM,
    d.VMG,
    d.bizMoney,
    d.VMC,
    d.VMP,
    d.way
FROM
    adminpay AS p
        INNER JOIN
    dayPoint AS d ON p.day_point_no = d.no
        INNER JOIN
    g5_member AS m ON m.mb_id = d.mb_id";
                    $sql .= " where p.date BETWEEN '$fr_date' AND '$to_date 23:59:59'";
                    $sql .= "and   d.VMC+d.VMP+d.VMM+d.VMG+d.bizMoney != 0 ";

                    if ($_GET["sch_sort_2"] == "mb_name" && $_GET["sch_word"] != "") {
                        $sql .= " and mb_name like '%{$_GET["sch_word"]}%'";
                    } else if ($_GET["sch_sort_2"] == "mb_id" && $_GET["sch_word"] != "") {
                        $sql .= " and m.mb_id like '%{$_GET["sch_word"]}%'";
                    }

                    $sql .= " order by date desc";

                    $point_list = mysql_query($sql);
                    $i = 0;
                    while ($row = mysql_fetch_array($point_list)) {
                        $i++;
                        $content = parsing($row['way']);
                        $sql = mysql_query("select mb_name from g5_member where mb_id = '{$row['admin_id']}'");
                        $admin_name = mysql_fetch_array($sql);
                        ?>
                        <tr>
                            <td><?= $row["date"] ?></td>
                            <td><?= $row["admin_id"] ?><br>
                                <?= $admin_name['mb_name'] ?>
                            </td>
                            <td>
                                <?= $row["mb_id"] ?><br>
                                <?= $row['mb_name'] ?>
                            </td>
                            <td><?php
                                if ($row['type'] == 1) {
                                    echo '입금';
                                } elseif (($row['type'] == 2)) {
                                    echo '출금';
                                }
                                ?></td>
                            <td class='point'>
                                <span class='p_vmc'><?= number_format($row["VMC"]) ?></span>
                                <span class='p_vmp'><?= number_format($row["VMP"]) ?></span>
                                <span class='vmm'><?= number_format($row["VMM"]) ?></span>
                                <span class='safe'><?= number_format($row["VMG"]) ?></span>
                                <span class='biz'><?php
                                    if ($row['bizMoney'] == "") {
                                        echo '0';
                                    } else {
                                        echo number_format($row['bizMoney']);
                                    }
                                    ?></span>

                            </td>
                            <td><?= $content ?></td>
                        </tr>
                        <?php
                    }
                    if ($i == 0) {
                        ?>
                        <tr>
                            <td colspan="7">조회된 내역이 없습니다.</td>
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
<!--관리자 포인트 지급 내역 끝-->


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
    $(function () {
        $('#fr_date,  #to_date').datepicker({changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99"});
    });

</script>