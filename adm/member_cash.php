<?php
$sub_menu = "200860";
include_once('./_common.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/myOffice/Classes/PHPExcel.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/myOffice/Classes/PHPExcel/IOFactory.php");
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn2.php');

auth_check($auth[$sub_menu], 'r');

include_once('./admin_menu_check.php');

if ($is_admin != 'super') {
    menu_check($sub_menu, $member['mb_id']);
}

$g5['title'] = '수당정산';
include_once('./admin.head.php');


// 엑셀파일 다운로드 버튼 링크 동적으로 만들기
$downHref = "member_point_excel.php?";
if (isset($_GET['sfl'])) {
    $downHref .= "sfl=" . $_GET['sfl'] . "&";
}
//
if (isset($_GET['fr_date'])) {
    $downHref .= "fr_date=" . $_GET['fr_date'] . "&";
}

if (isset($_GET['la_date'])) {
    $downHref .= "la_date=" . $_GET['la_date'] . "&";
}

if (isset($_GET['stx'])) {
    $downHref .= "stx=" . $_GET['stx'] . "&";
}

?>

<link rel="stylesheet" href="./css/admin_cash.css" type="text/css"/>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.18/themes/base/jquery-ui.css" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="http://code.jquery.com/ui/1.8.18/jquery-ui.min.js"></script>
<script>
    $(function () {
        $("#Datepicker1").datepicker({
            changeMonth: true,
            dayNames: ['월요일', '화요일', '수요일', '목요일', '금요일', '토요일', '일요일'],
            dayNamesMin: ['월', '화', '수', '목', '금', '토', '일'],
            monthNamesShort: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
            monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
            dateFormat: 'yy-mm-dd'
        });
        $("#Datepicker2").datepicker({
            changeMonth: true,
            dayNames: ['월요일', '화요일', '수요일', '목요일', '금요일', '토요일', '일요일'],
            dayNamesMin: ['월', '화', '수', '목', '금', '토', '일'],
            monthNamesShort: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
            monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
            dateFormat: 'yy-mm-dd'
        });
    });

</script>

<style>
    #datePick .calendar1 {
        float: left
    }
</style>

<div id="search_wrap">
    <div id="list_btn" onclick="excelDown()">
        <button>엑셀파일 다운로드</button>
        <br>
    </div>
</div>

<div id="datePick">
    <form name="fvisit" id="fvisit" class="local_sch03 local_sch" method="get" action="member_cash.php">
        <div class="sch_last" id="date" style="width: 60%">
            <strong>기간별검색</strong>

            <?php
            $y = date("Y");
            if (isset($_GET['cash_year'])) {
                $y = $_GET['cash_year'];
            }

            $m = date("m");
            if (isset($_GET['cash_month'])) {
                $m = $_GET['cash_month'];
            }

            $d = date("d");
            $b;
            if ($d < 16) {
                $b = "1";
            } else {
                $b = "2";
            }
            if (isset($_GET['cash_b'])) {
                $b = $_GET['cash_b'];
            }
            ?>

            <select name="cash_year" id="cash_year">
                <option value=""></option>
                <option value="2018" <?php if ($y == "2018") {
                    echo 'selected="selected"';
                } ?>>2018
                </option>
                <option value="2019" <?php if ($y == "2019") {
                    echo 'selected="selected"';
                } ?>>2019
                </option>
                <option value="2020" <?php if ($y == "2020") {
                    echo 'selected="selected"';
                } ?>>2020
                </option>
                <option value="2021" <?php if ($y == "2021") {
                    echo 'selected="selected"';
                } ?>>2021
                </option>
                <option value="2022" <?php if ($y == "2022") {
                    echo 'selected="selected"';
                } ?>>2022
                </option>
                <option value="2023" <?php if ($y == "2023") {
                    echo 'selected="selected"';
                } ?>>2023
                </option>
                <option value="2024" <?php if ($y == "2024") {
                    echo 'selected="selected"';
                } ?>>2024
                </option>
                <option value="2025" <?php if ($y == "2025") {
                    echo 'selected="selected"';
                } ?>>2025
                </option>
                <option value="2026" <?php if ($y == "2026") {
                    echo 'selected="selected"';
                } ?>>2026
                </option>
                <option value="2027" <?php if ($y == "2027") {
                    echo 'selected="selected"';
                } ?>>2027
                </option>
                <option value="2028" <?php if ($y == "2028") {
                    echo 'selected="selected"';
                } ?>>2028
                </option>
            </select>
            <label for="" class="">년도</label>

            <select name="cash_month" id="cash_month">
                <option value=""></option>
                <option value="1" <?php if ($m == "1") {
                    echo 'selected="selected"';
                } ?>>1
                </option>
                <option value="2" <?php if ($m == "2") {
                    echo 'selected="selected"';
                } ?>>2
                </option>
                <option value="3" <?php if ($m == "3") {
                    echo 'selected="selected"';
                } ?>>3
                </option>
                <option value="4" <?php if ($m == "4") {
                    echo 'selected="selected"';
                } ?>>4
                </option>
                <option value="5" <?php if ($m == "5") {
                    echo 'selected="selected"';
                } ?>>5
                </option>
                <option value="6" <?php if ($m == "6") {
                    echo 'selected="selected"';
                } ?>>6
                </option>
                <option value="7" <?php if ($m == "7") {
                    echo 'selected="selected"';
                } ?>>7
                </option>
                <option value="8" <?php if ($m == "8") {
                    echo 'selected="selected"';
                } ?>>8
                </option>
                <option value="9" <?php if ($m == "9") {
                    echo 'selected="selected"';
                } ?>>9
                </option>
                <option value="10" <?php if ($m == "10") {
                    echo 'selected="selected"';
                } ?>>10
                </option>
                <option value="11" <?php if ($m == "11") {
                    echo 'selected="selected"';
                } ?>>11
                </option>
                <option value="12" <?php if ($m == "12") {
                    echo 'selected="selected"';
                } ?>>12
                </option>
            </select>
            <label for="" class="">월</label>

            <select name="cash_b" id="cash_b">
                <option value=""></option>
                <option value="1" <?php if ($b == "1") {
                    echo 'selected="selected"';
                } ?>>1분기
                </option>
                <option value="2" <?php if ($b == "2") {
                    echo 'selected="selected"';
                } ?>>2분기
                </option>
            </select>
            <label for="" class="">분기</label>


            <label for="" class="">아이디 : </label>
            <input type="text" id="cash_id" name="cash_id" value="<?=$_GET['cash_id']?>">


            <input type="submit" value="검색" class="btn_submit">

            &nbsp;&nbsp;&nbsp;
            총 정산 신청 VMC : <span id="totalVMC_"></span>원&nbsp;&nbsp;&nbsp;
            총 정산 신청 VMP : <span id="totalVMP_"></span>원


        </div>

        <span class="modifiedBt2"> <img src="img/edit.png" alt="editbtn" onclick="modi2();" style="float:right;">
            <ul class="modifiedBtUl2">
                    <li class="closeBt2" onclick="modiclose();" style="float:right;"><span><i class="fa fa-times"
                                                                                              id="close-fa"
                                                                                              aria-hidden="true"></i></span></li>
                    <li class="confirmBt2" onclick="member_cash_update();" style="display: none; float:right; "><span><i
                                    class="fa fa-check" id="check-fa" aria-hidden="true"></i></span></li>
            </ul>
        </span>

    </form>
    ※ 우리 은행 전산으로 EXCEL 이체시 엑셀파일 다운로드 후 I열(이름), J열(주민번호)은 삭제하고 이체 바랍니다. <br><br>
</div>


<section class="total_history">
    <div class="total_history_Table">
        <form action="member_cash_update.php" method="POST" id="member_cash_update">
            <input type="hidden" id="dateS" name="dateS" value="2015-01-01">
            <?php
            if (isset($_GET['cash_year'])) {
                echo "<input type=\"hidden\" name=\"cash_year\" id=\"cash_year_\" value=\"{$_GET['cash_year']}\">";
            }

            if (isset($_GET['cash_month'])) {
                echo "<input type=\"hidden\" name=\"cash_month\" id=\"cash_month_\" value=\"{$_GET['cash_month']}\">";
            }

            if (isset($_GET['cash_b'])) {
                echo "<input type=\"hidden\" name=\"cash_b\" id=\"cash_b_\" value=\"{$_GET['cash_b']}\">";
            }
            ?>

            <table>
                <thead>
                <tr>
                    <th>아이디</th>
                    <th>직급</th>
                    <th>회원이름(계정종류)</th>
                    <th>신청이름(주민번호)</th>
                    <th>보유 VMC</th>
                    <th>보유 VMP</th>
                    <th>정산 신청 VMC</th>
                    <th>정산 신청 VMP</th>
                    <th>총 정산금액</th>
                </tr>
                </thead>
                <!-- 정산리스트 -->

                <tbody id="orderTB">
                <?php
                $cnt = 0;
                if ($_GET['cash_id'] != '') {
                    $where .= "and (A.mb_id = '{$_GET['cash_id']}'";
                    if (mysqli_multi_query($connection, "CALL SP_TREE('{$_GET['cash_id']}');SELECT * FROM genealogy_tree WHERE rootid = '{$_GET['cash_id']}'")) {
                        mysqli_store_result($connection);
                        mysqli_next_result($connection);
                        do {
                            /* store first result set */
                            if ($result = mysqli_store_result($connection)) {
                                while ($row2 = mysqli_fetch_array($result)) {
                                    $where .= " or A.mb_id = '{$row2['mb_id']}'";
                                    $cnt++;
                                }
                                mysqli_free_result($result);
                            }
                        } while (mysqli_next_result($connection));
                    } else {
                        echo mysqli_error($connection);
                    }
                    $where .= ")";

                }

                //아이디가없을 경우



                $dateS;
                $result;


                if (isset($_GET['cash_year']) && isset($_GET['cash_month']) && isset($_GET['cash_b'])) {
                    if ($_GET['cash_b'] == "1") { // 1분기
                        $result = mysql_query("SELECT 
                            A.*, b.mb_name AS mb_name2, b.RRN_F, b.RRN_B
                        FROM
                            (SELECT 
                                a.*, b.mb_name, b.accountType, b.VMC AS C, b.VMP AS P, b.accountRank
                            FROM
                                calculateTBL AS a
                            INNER JOIN g5_member AS b ON a.mb_id = b.mb_id) AS A
                                LEFT JOIN
                            memberRRNTBL AS b ON A.mb_id = b.mb_id
                        WHERE
                            A.settlementDate = '{$_GET['cash_year']}-{$_GET['cash_month']}-16' {$where} order by (A.VMC+A.VMP) desc");
                        $dateS = "{$_GET['cash_year']}-{$_GET['cash_month']}-16";
                    } else if ($_GET['cash_b'] == "2") { // 2분기
                        $M = intval($_GET['cash_month']) + 1;
                        if ($M == "13") {
                            $M = "01";
                            $_GET['cash_year'] = intval($_GET['cash_year']) + 1;
                        }
                        $result = mysql_query("SELECT 
                            A.*, b.mb_name AS mb_name2, b.RRN_F, b.RRN_B
                        FROM
                            (SELECT 
                                a.*, b.mb_name, b.accountType, b.VMC AS C, b.VMP AS P, b.accountRank
                            FROM
                                calculateTBL AS a
                            INNER JOIN g5_member AS b ON a.mb_id = b.mb_id) AS A
                                LEFT JOIN
                            memberRRNTBL AS b ON A.mb_id = b.mb_id
                        WHERE
                            A.settlementDate = '{$_GET['cash_year']}-{$M}-01' {$where} order by (A.VMC+A.VMP) desc");
                        $dateS = "{$_GET['cash_year']}-{$M}-01";
                    }


                    $totalVMC = 0;
                    $totalVMP = 0;
                    $i = 0;
                    while ($row = mysql_fetch_array($result)) {
                        $cnt++;
                        $totalVMC += $row['VMC'];
                        $totalVMP += $row['VMP'];

                        $i++;
                        $total = ($row['VMC'] + $row['VMP']);
                        $total = $total * 0.967;
                        $total = floor($total / 100) * 100;
                        $total = number_format($total);
                        $row['C'] = number_format($row['C']);
                        $row['P'] = number_format($row['P']);
                        echo "
                        <tr class=\"Point_Calculation2\">
                            <td><input type=\"text\" name=\"cash_id_{$i}\" readonly=\"\" value=\"{$row['mb_id']}\"></td>
                            <td>{$row['accountRank']}</td>
                            <td>{$row['mb_name']}({$row['accountType']})</td>
                            <td>{$row['mb_name2']}({$row['RRN_F']}-{$row['RRN_B']})</td>
                            <td>{$row['C']}</td>
                            <td>{$row['P']}</td>
                            <td class=\"vmc2\">
                                <input type='text' name='sell1_{$i}' class=\"sellinput\" id='sell1' onblur='call()' readonly=\"\" value=\"{$row['VMC']}\" onkeydown=\"return onlyNumber(event)\" onkeyup=\"removeChar(event)\"/>원
                            </td>
                            <td class=\"vmp2\">
                                <input type='text' name='sell2_{$i}' class=\"sellinput\" id='sell2' onblur='call()' readonly=\"\" value=\"{$row['VMP']}\" onkeydown=\"return onlyNumber(event)\" onkeyup=\"removeChar(event)\"/>원
                            </td>
                            <td class=\"total2\">
                                    <div><b id=\"sell3\">{$total}</b>원</div>
                            </td>
                        </tr>
                        ";

                    }
                }

                $totalVMC = number_format($totalVMC);
                $totalVMP = number_format($totalVMP);
                echo "<script>$(document).ready(function() { $('#totalVMC_').text('{$totalVMC}');$('#totalVMP_').text('{$totalVMP}'); $('#dateS').val('{$dateS}'); });</script>";
                ?>
                <?php
                if($cnt == 0){
                    echo "
                        <tr>
                            <td colspan='9'>검색된 정보가 없습니다.</td>
                        </tr>
                        ";
                }
                ?>
                </tbody>


            </table>
        </form>
    </div>
</section>
<?php
include_once('./admin.tail.php');
?>

<!--x버튼눌렀을때 페이지 리로드-->

<script type="text/javascript">
    $(document).ready(function () {
        $('.closeBt2').click(function () {
            location.reload();
        });
    });
</script>


<!--VMC 값 VMP 값 총 금액 계산-->

<script language='javascript'>
    function call() {
        var sell1 = parseInt($("#sell1").val());
        var sell2 = parseInt(document.getElementById('sell2').value);
        var sell3 = document.getElementById("sell3");

        var vmcvar = parseInt(($("#vmcvar").text().replace(/[^\d]+/g, '')));
        var vmpvar = parseInt(($("#vmpvar").text().replace(/[^\d]+/g, '')));


        if (vmcvar < sell1) {
            alert("보유하신 VMC보다 큰 금액을 정산 신청할 수 없습니다.");
            $("#sell1").val("0");
            $("#sell1").focus();
        }

        if (vmpvar < sell2) {
            alert("보유하신 VMP보다 큰 금액을 정산 신청할 수 없습니다.");
            $("#sell2").val("0");
            $("#sell2").focus();
        }

//3자리마다 콤마 만들어주는식        
        function numberWithCommas(n) {
            return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }


        var n = (sell1 + sell2) * 0.967;
        var nn = (Math.floor(n / 100) * 100);

        sell3.innerHTML = numberWithCommas(nn);
    }
</script>

<!--숫자만 입력받기-->
<script>
    function onlyNumber(event) {
        event = event || window.event;
        var keyID = (event.which) ? event.which : event.keyCode;
        if ((keyID >= 48 && keyID <= 57) || (keyID >= 96 && keyID <= 105) || keyID == 8 || keyID == 46 || keyID == 37 || keyID == 39)
            return;
        else
            return false;
    }

    function removeChar(event) {
        event = event || window.event;
        var keyID = (event.which) ? event.which : event.keyCode;
        if (keyID == 8 || keyID == 46 || keyID == 37 || keyID == 39)
            return;
        else
            event.target.value = event.target.value.replace(/[^0-9]/g, "");
    }
</script>


<!--편집버튼 누르면 v랑 x버튼-->

<script type="text/javascript">
    $(document).ready(function () {

        var $modifiedBtImg2 = $('.modifiedBt2 > img');
        var $modifiedBtConfirmBt2 = $('.confirmBt2');
        var $modifiedBtCloseBt2 = $('.closeBt2');


        $modifiedBtImg2.on('click', function () {
            $('.sellinput').addClass('on').attr('readonly', false);
            $(this).css('display', 'none');
            $modifiedBtCloseBt2.css({"display": "block", "float": "right"});
            $modifiedBtConfirmBt2.css({"display": "block", "float": "right"});
        })
        $modifiedBtConfirmBt2.on('click', function () {
            $('.sellinput').removeClass('on').attr('readonly', true);
            $(this).css('display', 'none');
            $modifiedBtImg2.css({"display": "block", "float": "right"});
            $modifiedBtCloseBt2.css({"display": "none", "float": "right"});
        })
        $modifiedBtCloseBt2.on('click', function () {
            $('.sellinput').removeClass('on').attr('readonly', true);
            $(this).css('display', 'none');
            $modifiedBtConfirmBt2.css({"display": "none", "float": "right"});
            $modifiedBtImg2.css({"display": "block", "float": "right"});
        })

    });

    function excelDown() {
        window.location = "member_cash_excel.php?dateS=" + $("#dateS").val() + "&cash_year=" + $("#cash_year_").val() + "&cash_month=" + $("#cash_month_").val() + "&cash_b=" + $("#cash_b_").val()+ "&cash_id=" + "<?=$_GET['cash_id']?>";
    }

    function member_cash_update() {
        $('#member_cash_update').submit();
    }
</script>
