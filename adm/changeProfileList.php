<?php
$sub_menu = "200980";
include_once('./_common.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');

$g5['title'] = '회원 정보 변경 이력 목록';
include_once (G5_ADMIN_PATH . '/admin.head.php');



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
if ($_GET["fr_date"] != "") {
    $fr_date = $_GET["fr_date"];
}
if ($_GET["to_date"] != "") {
    $to_date = $_GET["to_date"];
}
?>
<style>
    .v_tbl .bg1{background:#dee8f7 !important}    
</style>
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<link rel="stylesheet" href="../myOffice/css/loading.css"/>



<!--신분증 인증 시스템 시작-->
<div class="certify_id_card_wrap ">
    <!-- 분류 보기 시작  -->
    <form action="" method="get" name="sch_form" id="sch_form" class="local_sch01" onsubmit="return confirmForm()">
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
                <input type="text" name="sch_word" id="sch_input" value="<?= $_GET["sch_word"] ?>" placeholder="검색어를 입력해주세요.">
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
    <form action="" method="" name="" id="">
        <div class="v_tbl">
            <table>
                <caption>회원 정보 변경 이력 목록</caption>
                <colgroup>
                    <col width="10%"/>
                    <col width="20%"/>
                    <col width="25%"/>
                    <col />
                </colgroup>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>이름</th>
                        <th>핸드폰</th>
                        <th>주소</th>
                    </tr>
                    <tr>
                        <th>변경일</th>
                        <th>생년월일</th>
                        <th>이메일</th>
                        <th>은행정보</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $sql = "select * from member_modify";
                    $sql .= " where modi_date BETWEEN '$fr_date' AND '$to_date 23:59:59'";

                    if ($_GET["sch_sort_2"] == "mb_name" && $_GET["sch_word"] != "") {
                        $sql .= " and (mb_name like '%{$_GET["sch_word"]}%' or modi_mb_name like '%{$_GET["sch_word"]}%')";
                    } else if ($_GET["sch_sort_2"] == "mb_id" && $_GET["sch_word"] != "") {
                        $sql .= " and mb_id like '%{$_GET["sch_word"]}%'";
                    }
                    $sql .= 'order by modi_date desc';
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
                            <td>
                                <?php
                                
                                if ($row['modi_mb_name'] != $row['mb_name'] && $row['modi_mb_name'] != "") {

                                    if ($row['mb_name'] == "") {
                                        echo "<font color=red>" . "값없음" . '->' . $row['modi_mb_name'] . "</font>";
                                    } else {
                                        echo "<font color=red>" . $row['mb_name'] . '->' . $row['modi_mb_name'] . "</font>";
                                    }
                                } else {
                                    echo $row['mb_name'];
                                }
                                ?></td>
                            <td><?php
                                if ($row['modi_mb_hp'] != $row['mb_hp'] && $row['modi_mb_hp'] != "") {
                                    if ($row['mb_hp'] == "") {
                                        echo "<font color=red>값없음" . '->' . $row['modi_mb_hp'] . "</font>";
                                    } else {
                                        echo "<font color=red>" . $row['mb_hp'] . '->' . $row['modi_mb_hp'] . "</font>";
                                    }
                                } else {
                                    echo $row['mb_hp'];
                                }
                                ?></td>
                            <td><?php
                                if ($row['mb_addr1'] != $row['modi_mb_addr1'] || $row['mb_addr2'] != $row['modi_mb_addr2'] || $row['mb_addr3'] != $row['modi_mb_addr3'] || $row['mb_zip1'] != $row['modi_mb_zip1'] || $row['mb_zip2'] != $row['modi_mb_zip2']) {
                                    if ($row['mb_addr1'] == "" && $row['mb_addr2'] == "" && $row['mb_addr3'] == "" && $row['mb_zip1'] == "" && $row['mb_zip2'] == "") {
                                        echo "<font color=red>값없음" . '->' . $row['modi_mb_addr1'] . ' ' . $row['modi_mb_addr2'] . '(' . $row['modi_mb_zip1'] . $row['modi_mb_zip2'] . ')' . "</font>";
                                    } else {
                                        echo "<font color=red>" . $row['mb_addr1'] . ' ' . $row['mb_addr2'] . '(' . $row['mb_zip1'] . $row['mb_zip2'] . ')' . '->' . $row['modi_mb_addr1'] . ' ' . $row['modi_mb_addr2'] . '(' . $row['modi_mb_zip1'] . $row['modi_mb_zip2'] . ')' . "</font>";
                                    }
                                } else {
                                    echo $row['mb_addr1'] . ' ' . $row['mb_addr2'] . '(' . $row['mb_zip1'] . $row['mb_zip2'] . ')';
                                }
                                ?></td>
                        </tr>
                        <tr class="bg<?php
                        if ($i % 2 == 0) { //이 조건이 만족하면 짝수이다.
                            echo '0';
                        } else { //조건에 만족하지않으면 홀수이다.
                            echo '1';
                        }
                        ?>">    
                            <td><?= $row["modi_date"] ?></td>
                            <td><?php
                                if ($row['modi_birth'] != $row['mb_birth'] && $row['modi_birth'] != "") {

                                    if ($row['mb_birth'] == "") {
                                        echo "<font color=red>값없음" . '->' . $row['modi_birth'] . "</font>";
                                    } else {
                                        echo "<font color=red>" . $row['mb_birth'] . '->' . $row['modi_birth'] . "</font>";
                                    }
                                } else {
                                    echo $row['mb_birth'];
                                }
                                ?></td>
                            <td><?php
                                if ($row['modi_mb_email'] != $row['mb_email'] && $row['modi_mb_email'] != "") {
                                    if ($row['mb_email'] == "") {
                                        echo "<font color=red>값없음" . '->' . $row['modi_mb_email'] . "</font>";
                                    } else {
                                        echo "<font color=red>" . $row['mb_email'] . '->' . $row['modi_mb_email'] . "</font>";
                                    }
                                } else {
                                    echo $row['mb_email'];
                                }
                                ?></td>
                            <td><?php
                                if ($row['bankName'] != $row['modi_bankName'] || $row['accountHolder'] != $row['modi_accountHolder'] || $row['accountNumber'] != $row['modi_accountNumber']) {
                                    if ($row['bankName'] == "" && $row['accountHolder'] == "" && $row['accountNumber'] == "") {
                                        echo "<font color=red>값없음" . '->' . $row['modi_bankName'] . ' ' . $row['modi_accountNumber'] . ' ' . $row['modi_accountHolder'] . "</font";
                                    } else {
                                        echo "<font color=red>" . $row['bankName'] . ' ' . $row['accountNumber'] . ' ' . $row['accountHolder'] . '->' . $row['modi_bankName'] . ' ' . $row['modi_accountNumber'] . ' ' . $row['modi_accountHolder'] . "</font";
                                    }
                                } else {
                                    echo $row['bankName'] . ' ' . $row['accountNumber'] . ' ' . $row['accountHolder'];
                                }
                                ?></td>
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
        $('#fr_date,  #to_date').datepicker({changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99"});
    });

</script>