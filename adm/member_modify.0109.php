<?php
$sub_menu = "200830";
include_once('./_common.php');


auth_check($auth[$sub_menu], 'r');


include_once('./admin_menu_check.php');

if ($is_admin != 'super') {
    menu_check($sub_menu, $member['mb_id']);
}
$g5['title'] = 'VMP';
include_once('./admin.head.php');

// 최소년도 구함
$sql = " select min(vi_date) as min_date from {$g5['visit_table']} ";
$row = sql_fetch($sql);

$min_year = (int)substr($row['min_date'], 0, 4);
$now_year = (int)substr(G5_TIME_YMD, 0, 4);
?>

<div class="local_ov01 local_ov">

</div>
<!--VMP 포인트내역 시작-->
<div id="tab_vmp_point" class="tbl_head01 tbl_wrap">
    <h4 id="tab_title">VMP 포인트 내역(전체 회원)</h4>
    <form action="" name="vmp_point_table" id="vmp_point_table">
        <?php
        $totalPointRowInfo = sql_fetch_array(sql_query("select sum(VMC) as VMC, sum(VMP) as VMP, sum(VMM) as VMM, sum(VMG) as VMG, sum(VCash) as VCash, sum(VPay) as VPay from g5_member"));
        $totalPointRowInfo2 = sql_fetch_array(sql_query("select sum(VMC) as VMC, sum(VMP) as VMP, sum(VMM) as VMM, sum(VMG) as VMG, sum(VCash) as VCash, sum(VPay) as VPay from g5_member where accountType = 'VM'"));
        ?>
        <table>
            <caption>VMP 포인트 내역(전체 회원)</caption>
            <thead>
            <tr>
                <th scope="col">VMC</th>
                <th scope="col">VMP</th>
                <th scope="col">VMM</th>
                <th scope="col">금고</th>
                <th scope="col">V CASH</th>
                <th scope="col">V PAY</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?php echo number_format($totalPointRowInfo["VMC"]); ?> 원</td>
                <td><?php echo number_format($totalPointRowInfo["VMP"]); ?> 원</td>
                <td><?php echo number_format($totalPointRowInfo["VMM"]); ?> 원</td>
                <td><?php echo number_format($totalPointRowInfo["VMG"]); ?> 원</td>
                <td><?php echo number_format($totalPointRowInfo["VCash"]); ?> 원</td>
                <td><?php echo number_format($totalPointRowInfo["VPay"]); ?> 원</td>
            </tr>
            </tbody>
        </table>
        <h4 id="tab_title">VMP 포인트 내역(VM)</h4>
        <table>
            <caption>VMP 포인트 내역(VM)</caption>
            <thead>
            <tr>
                <th scope="col">VMC</th>
                <th scope="col">VMP</th>
                <th scope="col">VMM</th>
                <th scope="col">금고</th>
                <th scope="col">V CASH</th>
                <th scope="col">V PAY</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?php echo number_format($totalPointRowInfo2["VMC"]); ?> 원</td>
                <td><?php echo number_format($totalPointRowInfo2["VMP"]); ?> 원</td>
                <td><?php echo number_format($totalPointRowInfo2["VMM"]); ?> 원</td>
                <td><?php echo number_format($totalPointRowInfo2["VMG"]); ?> 원</td>
                <td><?php echo number_format($totalPointRowInfo2["VCash"]); ?> 원</td>
                <td><?php echo number_format($totalPointRowInfo2["VPay"]); ?> 원</td>
            </tr>
            </tbody>
        </table>
    </form>
</div>
<!--VMP 포인트내역 끝-->


<!-- 2020-01-09 추가 시작 / 매출액 설정, 확인 -->
<div id="tab_container" class="added_option">
    <h4 id="tab_title">매출액 조회/설정</h4>

    <div class="tab on_tab">
        <h4>매출액 조회 </h4>
        <div>

            <form name="" class="" method="post" action="" onsubmit="return form_submit(this);">
                <div class="select_contain mg1">
                    <label for="year_1"><?=date('Y')?></label>
                    <select id="year_1" class="chg_select">
                        <option value="년">년</option>
                        <?php
                        $year = date('Y');
                        for ($x = 2018; $x <= $year; $x++) {
                            if ($x==$year){
                                echo '<option value="' . $x . '" selected>' . $x . '</option>';
                            }else{
                                echo '<option value="' . $x . '">' . $x . '</option>';
                            }
                        }
                        ?>
                    </select>

                </div>
                <div class="select_contain">
                    <label for="month_1"><?=date('m')?></label>
                    <select id="month_1" class="chg_select">
                        <option value="월">월</option>
                        <?php
                        $month = date('m');
                        for ($x = 1; $x <= 12; $x++) {
                            if ($x==$month){
                                echo '<option value="' . $x . '" selected>' . $x . '</option>';
                            }else{
                                echo '<option value="' . $x . '">' . $x . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <p><font style="font-size:15px;">매출액 : </font>
                    <font id="search_sale" style="color: red;font-family: bold; font-size:15px;"></font>
                </p>
                <div class="visit_del_bt">
                    <input type="button" value="조회" class="btn_submit" onclick="search_sale()">
                </div>
            </form>
        </div>
    </div>

    <div class="tab">
        <h4>매출액 설정</h4>
        <div>
            <div class="select_contain mg1">
                <label for="year_2">년</label>
                <select id="year_2" class="chg_select">
                    <option value="년">년</option>
                    <?php
                    $year = date('Y');
                    for ($x = 2018; $x <= $year + 1; $x++) {
                        echo '<option value="' . $x . '">' . $x . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="select_contain">
                <label for="month_2">월</label>
                <select id="month_2" class="chg_select">
                    <option value="월">월</option>
                    ;
                    <?php
                    for ($x = 1; $x <= 12; $x++) {
                        echo '<option value="' . $x . '">' . $x . '</option>';
                    }
                    ?>
                </select>
            </div>
            <input type="text" placeholer="금액을설정해주세요" id="price" onkeyup="onlyNumberComma(this)"/>
            <input type="hidden" id="mem_id" value="<?= $member['mb_id'] ?>"/>
            <div class="visit_del_bt">
                <input type="button" value="설정" class="btn_submit" onclick="set_sale()">
            </div>
        </div>
    </div>
</div>
<!-- 2020-01-09 추가 끝 / 매출액 설정, 확인 -->

<div id="tab_container">
    <h4 id="tab_title">후원인/추천인 변경</h4>
    <div class="tab on_tab">
        <h4>후원인 변경</h4>
        <div>
            <!-- name, action 변경필 //member_modify_update.php 파일 생성 필요 . 18.03.27 by.wook -->
            <form name="member_modify" class="visit_del" method="post" action="/myOffice/member_modify_update.php"
                  onsubmit="return form_submit(this);">
                <div>
                    <ul>
                        <li>
                            <label for="year" class="sound_only">id입력</label>
                            <input type="text" name="before" id="before" maxlength="8" placeholder="변경할 ID를 입력하세요."
                                   autocomplete="off"><a href="#none" class="number_check_btn"
                                                         onclick="nameCheck($(this))">이름 확인</a>
                        </li>
                        <li><b class="before_mb_name"></b><span>님의 후원인을</span></li>
                        <li>
                            <input type="text" name="after" id="after" maxlength="8" placeholder="변경후 ID를 입력하세요."
                                   autocomplete="off"><a href="#none" class="number_check_btn"
                                                         onclick="nameCheck($(this))">이름 확인</a>
                        </li>
                        <li>
                            <b class="after_mb_name"></b><span>님의&nbsp;</span><input class="radio" type="radio"
                                                                                     name="team" class="go" value="1">&nbsp;1
                            팀&nbsp;&nbsp;&nbsp;&nbsp;<input class="radio go" type="radio" name="team" value="2">&nbsp;2
                            팀&nbsp;
                            <span>으로 변경.</span>
                        </li>
                    </ul>
                </div>
                <div class="visit_del_bt">
                    <input type="submit" value="확인" class="btn_submit">
                </div>
            </form>
        </div>
    </div>

    <div class="tab">
        <h4>추천인 변경</h4>
        <div>
            <!-- name, action 변경필 //member_modify_update2.php 파일 생성 필요 . 18.03.27 by.wook -->
            <form name="member_modify" class="visit_del" method="post" action="/myOffice/member_modify_update2.php"
                  onsubmit="return form_submit2(this);">
                <div>
                    <ul>
                        <li>
                            <label for="year" class="sound_only">id입력</label>
                            <input type="text" name="before2" id="before2" maxlength="8" placeholder="변경할 ID를 입력하세요."
                                   autocomplete="off"><a href="#none" class="number_check_btn"
                                                         onclick="nameCheck($(this))">이름 확인</a>
                        </li>
                        <li><b class="before2_mb_name"></b><span>님의 추천인을</span></li>
                        <li>
                            <input type="text" name="after2" id="after2" maxlength="8" placeholder="변경후 ID를 입력하세요."
                                   autocomplete="off"><a href="#none" class="number_check_btn"
                                                         onclick="nameCheck($(this))">이름 확인</a>
                        </li>
                        <li>
                            <b class="after2_mb_name"></b><span>님으로 변경</span>
                        </li>
                    </ul>
                </div>
                <div class="visit_del_bt">
                    <input type="submit" value="확인" class="btn_submit">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 회원 포인트 정산  -->
<div id="point_st">
    <div class="point">
        <h4>회원 포인트 정산</h4>
        <div class="point_count">
            <form name="member_modify" class="visit_del" method="post" action="./member_modify_update.php"
                  onsubmit="return form_submit(this);">
                <div class="button">
                    <a href="/myOffice/memberInfoExcel.php">
                        <button type="button">회원정보 다운받기</button>
                    </a>
                    <span> *회원 포인트가 자동 다운로드 됩니다.</span>
                </div>
            </form>
        </div>
    </div>

    <div class="update">
        <form action="/myOffice/upload_point.php" method="post" onsubmit="return pointUp(this);"
              enctype="multipart/form-data">
            <input type="file" name="myfile" id="file_update">
            <div class="button">
                <!--<button type="button" id="filecheck" onclick="javascript:ViewLayer();">회원정보 업로드</button>-->
                <button type="submit" id="filecheck">회원정보 업로드</button>
                <span> *선택된 파일이 업로드 됩니다.</span>
            </div>
        </form>
    </div>
</div>
<!-- 최근 주문/판매 내역 정산  -->
<div id="order_form">
    <div class="order">
        <h4>최근 주문/판매 내역 정산</h4>
        <div class="order_count">
            <form name="member_modify" class="visit_del" method="post" action="" onsubmit="return form_submit(this);">
                <div class="button">
                    <a href="/up/주문판매내역.xls">
                        <button type="button">양식 다운받기</button>
                    </a>
                    <span> *최근 주문/판매 내역이 자동 다운로드 됩니다.</span>
                </div>
            </form>
        </div>
    </div>

    <div class="order_update">
        <form action="/myOffice/upload_orderList.php" method="post" enctype="multipart/form-data"
              onsubmit="return orderUp(this);">
            <input type="file" name="myfile" id="file_update2">
            <div class="button">
                <button type="submit" id="filecheck2">최근 주문/판매 내역 업로드</button>
                <span> *선택된 파일이 업로드 됩니다.</span>
            </div>
        </form>
    </div>
</div>

<script>
    // 매출액조회
    function search_sale() {
        var year = $('#year_1').val();
        var month = $('#month_1').val();
        if (year == '년') {
            alert("년도를 선택해주세요.");
            return;
        }
        if (month == '월') {
            alert("월을 입력해주세요.");
            return;
        }
        console.log(year+month)
        $.ajax({
            url: '/myOffice/set_sale.php',
            type: 'POST',
            data: {
                year: year
                , month: month
                , up: 'search'
            },
            dataType: "json",
            success: function (data) {
                if (data['result'] == 'success') {
                    $('#search_sale').text(number_format(data['set_price'])+'원');
                } else {
                    alert('검색결과가 없습니다.');
                    $('#search_sale').text('');
                }
            }
        });


    }


    // 매출액설정
    function set_sale(up) {
        var up = up;
        var year = $('#year_2').val();
        var month = $('#month_2').val();
        var price = $('#price').val();
        var mem_id = $('#mem_id').val();
        console.log(up);
        if (year == '년') {
            alert("년도를 선택해주세요.");
            return;
        }
        if (month == '월') {
            alert("월을 입력해주세요.");
            return;
        }
        if (price == '') {
            alert("매출액을 입력해주세요.");
            return;
        }
        console.log(mem_id);
        console.log(year + 'asdas' + month + "asdas" + price);
        $.ajax({
            url: '/myOffice/set_sale.php',
            type: 'POST',
            data: {
                year: year
                , month: month
                , price: price
                , mem_id: mem_id
                , up: up
            },
            dataType: "json",
            success: function (data) {
                if (data['result'] == 'success') {
                    alert("성공적으로 저장되었습니다.")
                } else if (data['result'] == 'already') {
                    if (confirm('이미 설정된 매출액이 있습니다. 재설정 하시겠습니까?')) {
                        set_sale("up");
                    }
                } else {
                    alert('저장에 실패하였습니다.')
                }
            }
        });


    }


    // 후원인,추천인 이름보여주기
    function nameCheck(obj) {

        var data = "mb_id=" + $(obj).prev('input').val();

        $.ajax({
            url: '/myOffice/memberIDSearch2.php',
            type: 'POST',
            data: encodeURI(data),
            async: false,
            success: function (result) {
                if (result == 'false') {
                    $(obj).parent().next().find('b').text('회원정보가 없습니다.');

                } else {
                    $(obj).parent().next().find('b').text(result);

                }
            }
        });

    }

    //회원ID와 회원이름 체크
    function checkFunc(obj) {

        var data = "mb_id=" + obj;
        var resultValue;

        $.ajax({
            url: '/myOffice/memberIDSearch2.php',
            type: 'POST',
            data: encodeURI(data),
            async: false,
            success: function (result) {
                if (result == 'false') {
                    resultValue = '회원정보가 없습니다.';

                } else {
                    resultValue = result;

                }
            }
        });

        return resultValue;

    }

    function form_submit(f) {
        var before = $("#before").val();
        var after = $("#after").val();
        var radio = $("input:radio[name=team]").is(":checked");
        var pass = $("#pass").val();
        var radioVal = $(".go").val();
        var beforeName;
        var afterName;

        if (!before) {
            alert("변경 전 후원인의 ID를 입력해 주세요.");
            return false;
        }

        if (!after) {
            alert("변경 될 후원인의 ID를 입력해 주세요.");
            return false;
        }
        if (!radio) {
            alert("변경될 팀을 선택해 주세요.");
            return false;
        }
//    if(!pass) {
//        alert("관리자 비밀번호를 입력해 주십시오.");
//        return false;
//    }
        beforeName = checkFunc($('#before').val());
        afterName = checkFunc($('#after').val());

        var msg = beforeName + "(" + before + ")" + "님의 후원인을 \n" + afterName + "(" + after + ")" + "님으로 변경하시겠습니까?";

        return confirm(msg);
    }


    function form_submit2(f) {
        var before = $("#before2").val();
        var after = $("#after2").val();
        //var method = $("#method").val();
        var pass = $("#pass2").val();
        var beforeName;
        var afterName;

        if (!before) {
            alert("변경 전 추천인의 ID를 입력해 주세요.");
            return false;
        }

        if (!after) {
            alert("변경 될 추천인의 ID를 입력해 주세요.");
            return false;
        }

//    if(!pass) {
//        alert("관리자 비밀번호를 입력해 주십시오.");
//        return false;
//    }

        beforeName = checkFunc($('#before2').val());
        afterName = checkFunc($('#after2').val());

        var msg = beforeName + "(" + before + ")" + "님의 추천인을 \n" + afterName + "(" + after + ")" + "님으로 변경하시겠습니까?";

        return confirm(msg);
    }

    $(".tab").click(function () {
        $(this).addClass("on_tab");
        $(this).siblings(".tab").removeClass("on_tab");
    });


</script>

<script>

    function pointUp(f) {
        var now = new Date();
        var startTime = new Date(now.getFullYear() + "-" + (now.getMonth() + 1) + "-" + now.getDate() + " 00:00:00");
        var endTime = new Date(now.getFullYear() + "-" + (now.getMonth() + 1) + "-" + (now.getDate()) + " 01:00:00");

        if ((startTime.getTime() <= now.getTime()) && (endTime.getTime() > now.getTime())) {
            // 참이 뜨면...
            alert("금일 00:00 ~ 금일 01:00 점검시간입니다.");
            return false;

        } else {

            var check = confirm("회원정보를 업로드하시겠습니까?");

            if ($('#file_update').val() == 0) {
                alert("파일을 선택해주세요.");
                return false;
            } else if (!check) {
                return false;
            } else {
                return true;
            }
        }
    }

    function orderUp(f) {
        var now = new Date();
        var startTime = new Date(now.getFullYear() + "-" + (now.getMonth() + 1) + "-" + now.getDate() + " 00:00:00");
        var endTime = new Date(now.getFullYear() + "-" + (now.getMonth() + 1) + "-" + (now.getDate()) + " 01:00:00");

        if ((startTime.getTime() <= now.getTime()) && (endTime.getTime() > now.getTime())) {
            alert("금일 00:00 ~ 금일 01:00 점검시간입니다.");
            return false;
        } else {
            var check = confirm("회원정보를 업로드하시겠습니까?");
            if ($('#file_update2').val() == 0) {
                alert("파일을 선택해주세요.");
                return false;
            } else if (!check) {
                return false;
            } else {
                return true;
            }
        }

    }


    //    	매출액 조회, 설정 200109
    $(document).ready(function () {
        var select = $(".chg_select");
        select.change(function () {
            var select_name = $(this).children("option:selected").text();
            $(this).prev("label").text(select_name);
        });
    });

    function onlyNumberComma(obj) {
        var num01;
        var num02;
        num01 = obj.value;
        num02 = num01.replace(/\D/g, ""); //숫자가 아닌것을 제거,
        //즉 [0-9]를 제외한 문자 제거; /[^0-9]/g 와 같은 표현
        num01 = setComma(num02); //콤마 찍기
        obj.value = num01;

        function setComma(n) {
            var reg = /(^[+-]?\d+)(\d{3})/;   // 정규식
            n += '';                          // 숫자를 문자열로 변환
            while (reg.test(n)) {
                n = n.replace(reg, '$1' + ',' + '$2');
            }
            return n;
        }
    }


</script>

<?php
include_once('./admin.tail.php');
?>
