<?php
$sub_menu = "200830";
include_once('./_common.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');


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
                <?php $Number = 1; ?>
                <td><?php echo number_format($totalPointRowInfo["VMC"] * $Number); ?> 원</td>
                <td><?php echo number_format($totalPointRowInfo["VMP"] * $Number); ?> 원</td>
                <td><?php echo number_format($totalPointRowInfo["VMM"] * $Number); ?> 원</td>
                <td><?php echo number_format($totalPointRowInfo["VMG"] * $Number); ?> 원</td>
                <td><?php echo number_format($totalPointRowInfo["VCash"] * $Number); ?> 원</td>
                <td><?php echo number_format($totalPointRowInfo["VPay"] * $Number); ?> 원</td>
            </tr>
            </tbody>
        </table>
        토탈
        : <?php echo number_format(($totalPointRowInfo["VMC"] + $totalPointRowInfo["VMP"] + $totalPointRowInfo["VMM"] + $totalPointRowInfo["VMG"] + $totalPointRowInfo["VCash"] + $totalPointRowInfo["VPay"]) * $Number) . " 원"; ?>
        <br/><br/>
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
                <td><?php echo number_format($totalPointRowInfo2["VMC"] * $Number); ?> 원</td>
                <td><?php echo number_format($totalPointRowInfo2["VMP"] * $Number); ?> 원</td>
                <td><?php echo number_format($totalPointRowInfo2["VMM"] * $Number); ?> 원</td>
                <td><?php echo number_format($totalPointRowInfo2["VMG"] * $Number); ?> 원</td>
                <td><?php echo number_format($totalPointRowInfo2["VCash"] * $Number); ?> 원</td>
                <td><?php echo number_format($totalPointRowInfo2["VPay"] * $Number); ?> 원</td>
            </tr>
            </tbody>
        </table>
        토탈
        : <?php echo number_format(($totalPointRowInfo2["VMC"] + $totalPointRowInfo2["VMP"] + $totalPointRowInfo2["VMM"] + $totalPointRowInfo2["VMG"] + $totalPointRowInfo2["VCash"] + $totalPointRowInfo2["VPay"]) * $Number) . " 원"; ?>
    </form>
</div>
<!--VMP 포인트내역 끝-->

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
<br><br>
<div id="tab_container" class="renew">
    <h4 id="tab_title">CU 강제 리뉴얼</h4>
    ※ 조직도에 편성 중인 CU만 리뉴얼 가능합니다.
    <div class="tab on_tab">
        <div>
            <!-- name, action 변경필 //member_modify_update.php 파일 생성 필요 . 18.03.27 by.wook -->
            <form name="" class="visit_del" method="post" action="forceRenewal.php" onsubmit="">
                <div>
                    <ul>
                        <li>
                            <label for="year" class="sound_only">id입력</label>
                            <input type="text" name="before" id="before" maxlength="8" placeholder="변경할 ID를 입력하세요."
                                   autocomplete="off"><a href="#none" class="number_check_btn"
                                                         onclick="nameCheck($(this))">이름 확인</a>
                        </li>
                        <li><b class="before_mb_name"></b><span>님을 강제 리뉴얼</span></li>
                    </ul>
                </div>
                <div class="visit_del_bt">
                    <input type="submit" value="확인" class="btn_submit">
                </div>
            </form>
        </div>

    </div>
</div>


<?php 
    $configR1 = mysql_fetch_array(mysql_query("select * from config where config = 'simpleRenewal'"));
    $configR2 = mysql_fetch_array(mysql_query("select * from config where config = 'simpleJoin'"));
?>
<div id="tab_container_permission">
    <div class="permission_manage">
        <h4 id="tab_title">권한 설정</h4>
        ※ 지정한 직급 이상이 해당 기능을 이용할 수 있게 됩니다.
        <ul class="tab_ul">
            <li class="active"><a onclick="tab_change('0','permission_manage')">간편 리뉴얼</a></li>
            <li><a onclick="tab_change('1','permission_manage')">간편 회원가입</a></li>
        </ul>
        <div class="tab on_tab">
            <form method="POST" action="permissionSetting_back.php">
                <input type="hidden" name="type" id="hiddenType" value="simpleRenewal"/>
                <select name="accountRank" id="" class="">
                    <option value="VM" <?php if($configR1["value"] == "VM") {echo "selected='selected'";} ?>>VM</option>
                    <option value="MASTER" <?php if($configR1["value"] == "MASTER") {echo "selected='selected'";} ?>>MASTER</option>
                    <option value="DOUBLE MASTER" <?php if($configR1["value"] == "DOUBLE MASTER") {echo "selected='selected'";} ?>>DOUBLE MASTER</option>
                    <option value="TRIPLE MASTER" <?php if($configR1["value"] == "TRIPLE MASTER") {echo "selected='selected'";} ?>>TRIPLE MASTER</option>
                    <option value="1 STAR" <?php if($configR1["value"] == "1 STAR") {echo "selected='selected'";} ?>>1 STAR</option>
                    <option value="2 STAR" <?php if($configR1["value"] == "2 STAR") {echo "selected='selected'";} ?>>2 STAR</option>
                    <option value="3 STAR" <?php if($configR1["value"] == "3 STAR") {echo "selected='selected'";} ?>>3 STAR</option>
                    <option value="4 STAR" <?php if($configR1["value"] == "4 STAR") {echo "selected='selected'";} ?>>4 STAR</option>
                    <option value="5 STAR" <?php if($configR1["value"] == "5 STAR") {echo "selected='selected'";} ?>>5 STAR</option>
                    <option value="AMBASSADOR" <?php if($configR1["value"] == "AMBASSADOR") {echo "selected='selected'";} ?>>AMBASSADOR</option>
                    <option value="DOUBLE AMBASSADOR" <?php if($configR1["value"] == "DOUBLE AMBASSADOR") {echo "selected='selected'";} ?>>DOUBLE AMBASSADOR</option>
                    <option value="TRIPLE AMBASSADOR" <?php if($configR1["value"] == "TRIPLE AMBASSADOR") {echo "selected='selected'";} ?>>TRIPLE AMBASSADOR</option>
                    <option value="CROWN AMBASSADOR" <?php if($configR1["value"] == "CROWN AMBASSADOR") {echo "selected='selected'";} ?>>CROWN AMBASSADOR</option>
                    <option value="ROYAL CROWN AMBASSADOR" <?php if($configR1["value"] == "ROYAL CROWN AMBASSADOR") {echo "selected='selected'";} ?>>ROYAL CROWN AMBASSADOR</option>
                </select>
            
                <div class="">
                    <input type="submit" value="확인" class="btn_submit">
                </div>
            </form>

        </div>

        <div class="tab">
            <form method="POST" action="permissionSetting_back.php">
                <input type="hidden" name="type" id="type" value="simpleJoin"/>
                <select name="accountRank" id="">
                    <option value="VM" <?php if($configR2["value"] == "VM") {echo "selected='selected'";} ?>>VM</option>
                    <option value="MASTER" <?php if($configR2["value"] == "MASTER") {echo "selected='selected'";} ?>>MASTER</option>
                    <option value="DOUBLE MASTER" <?php if($configR2["value"] == "DOUBLE MASTER") {echo "selected='selected'";} ?>>DOUBLE MASTER</option>
                    <option value="TRIPLE MASTER" <?php if($configR2["value"] == "TRIPLE MASTER") {echo "selected='selected'";} ?>>TRIPLE MASTER</option>
                    <option value="1 STAR" <?php if($configR2["value"] == "1 STAR") {echo "selected='selected'";} ?>>1 STAR</option>
                    <option value="2 STAR" <?php if($configR2["value"] == "2 STAR") {echo "selected='selected'";} ?>>2 STAR</option>
                    <option value="3 STAR" <?php if($configR2["value"] == "3 STAR") {echo "selected='selected'";} ?>>3 STAR</option>
                    <option value="4 STAR" <?php if($configR2["value"] == "4 STAR") {echo "selected='selected'";} ?>>4 STAR</option>
                    <option value="5 STAR" <?php if($configR2["value"] == "5 STAR") {echo "selected='selected'";} ?>>5 STAR</option>
                    <option value="AMBASSADOR" <?php if($configR2["value"] == "AMBASSADOR") {echo "selected='selected'";} ?>>AMBASSADOR</option>
                    <option value="DOUBLE AMBASSADOR" <?php if($configR2["value"] == "DOUBLE AMBASSADOR") {echo "selected='selected'";} ?>>DOUBLE AMBASSADOR</option>
                    <option value="TRIPLE AMBASSADOR" <?php if($configR2["value"] == "TRIPLE AMBASSADOR") {echo "selected='selected'";} ?>>TRIPLE AMBASSADOR</option>
                    <option value="CROWN AMBASSADOR" <?php if($configR2["value"] == "CROWN AMBASSADOR") {echo "selected='selected'";} ?>>CROWN AMBASSADOR</option>
                    <option value="ROYAL CROWN AMBASSADOR" <?php if($configR2["value"] == "ROYAL CROWN AMBASSADOR") {echo "selected='selected'";} ?>>ROYAL CROWN AMBASSADOR</option>
                </select>

                <div class="">
                    <input type="submit" value="확인" class="btn_submit">
                </div>
            </form>
        </div>

    </div>
</div>



<div class="vmp7">
    <h4 id="tab_title">임시</h4>
    ※ 조직도에 편성 중인 CU만 리뉴얼 가능합니다.
    <div class="vmp7_cont">
      <form name="" id="" class="" method="post" action=".php" onsubmit="">                
          <div class="vmp7_inp_area">
              <label for="year" class="sound_only">id입력</label>
              <input type="text" name="before" id="before" maxlength="8" placeholder="이름을 입력해주세요" class="inp_vmp7"
                                autocomplete="off">
              <a href="#none" class="number_check_btn" onclick="nameCheck($(this))">이름 확인</a>
              <b class="before_mb_name"></b><span class="help_txt">님을 강제 리뉴얼</span>       
          </div>
 
        <input type="hidden" name="type" id="hiddenType" value="simpleRenewal"/>
        <select name="accountRank" id="" class="">
          <option value="VM" <?php if($configR1["value"] == "VM") {echo "selected='selected'";} ?>>VM</option>
          <option value="MASTER" <?php if($configR1["value"] == "MASTER") {echo "selected='selected'";} ?>>MASTER</option>
          <option value="DOUBLE MASTER" <?php if($configR1["value"] == "DOUBLE MASTER") {echo "selected='selected'";} ?>>DOUBLE MASTER</option>
          <option value="TRIPLE MASTER" <?php if($configR1["value"] == "TRIPLE MASTER") {echo "selected='selected'";} ?>>TRIPLE MASTER</option>
          <option value="1 STAR" <?php if($configR1["value"] == "1 STAR") {echo "selected='selected'";} ?>>1 STAR</option>
          <option value="2 STAR" <?php if($configR1["value"] == "2 STAR") {echo "selected='selected'";} ?>>2 STAR</option>
          <option value="3 STAR" <?php if($configR1["value"] == "3 STAR") {echo "selected='selected'";} ?>>3 STAR</option>
          <option value="4 STAR" <?php if($configR1["value"] == "4 STAR") {echo "selected='selected'";} ?>>4 STAR</option>
          <option value="5 STAR" <?php if($configR1["value"] == "5 STAR") {echo "selected='selected'";} ?>>5 STAR</option>
          <option value="AMBASSADOR" <?php if($configR1["value"] == "AMBASSADOR") {echo "selected='selected'";} ?>>AMBASSADOR</option>
          <option value="DOUBLE AMBASSADOR" <?php if($configR1["value"] == "DOUBLE AMBASSADOR") {echo "selected='selected'";} ?>>DOUBLE AMBASSADOR</option>
          <option value="TRIPLE AMBASSADOR" <?php if($configR1["value"] == "TRIPLE AMBASSADOR") {echo "selected='selected'";} ?>>TRIPLE AMBASSADOR</option>
          <option value="CROWN AMBASSADOR" <?php if($configR1["value"] == "CROWN AMBASSADOR") {echo "selected='selected'";} ?>>CROWN AMBASSADOR</option>
          <option value="ROYAL CROWN AMBASSADOR" <?php if($configR1["value"] == "ROYAL CROWN AMBASSADOR") {echo "selected='selected'";} ?>>ROYAL CROWN AMBASSADOR</option>
      </select>
    </form>
  </div>
  <input type="submit" value="확인" class="btn_submit" />
</div>


<script>

    // 후원인,추천인 이름보여주기
    function nameCheck(obj) {

        var data = "mb_id=" + $(obj).prev('input').val();

        $.ajax({
            url    : '/myOffice/memberIDSearch2.php',
            type   : 'POST',
            data   : encodeURI(data),
            async  : false,
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
            url    : '/myOffice/memberIDSearch2.php',
            type   : 'POST',
            data   : encodeURI(data),
            async  : false,
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


    //200427추가, 탭 변경 number = index, wrapbox = 컨테이너 클래스
    function tab_change(number,wrapbox) {
        tab_index = number;
        $wrap_box = $('.'+wrapbox);
       $wrap_box.children('.tab_ul').children('li').eq(number).addClass('active').siblings('li').removeClass('active');
        $wrap_box.children('.tab').eq(number).addClass('on_tab').siblings('.tab').removeClass('on_tab');
    }
</script>

<?php
include_once('./admin.tail.php');

?>
