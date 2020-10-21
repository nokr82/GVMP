<link rel="shortcut icon" href="/img/vmp_logo.ico"/>
<link rel="stylesheet" href="./css/index.css"/>
<link rel="stylesheet" href="./css/v_ad_center.css"/>
<link rel="stylesheet" href="css/animate.css"/>
<link rel="stylesheet" href="css/myoffice_sub.css"/>
<link rel="stylesheet" href="css/loading_ver2.css"/>
<link rel="stylesheet" href="../myOffice/css/loading.css"/>

<?php
include_once(G5_PLUGIN_PATH . '/jquery-ui/datepicker.php');
include_once('./inc/title.php');
include_once('./_common.php');
include_once('./dbConn.php');
include_once('./getMemberInfo.php');
include_once('./point_pop_back_func.php');

if ($is_guest) // 로그인 안 했을 때 로그인 페이지로 이동
    header('Location: /bbs/login.php');

/* 1004 VMP 가져오기 */
$row = mysql_fetch_array(mysql_query("SELECT VMP FROM g5_member WHERE mb_id = '00001004'"));
$ANGELVMP = $row['VMP'];
/* 1004 VMP 가져오기 */
/* 추천인, 후원인 정보값 가져오기 */
$result = mysql_query("SELECT * FROM genealogy where mb_id = '{$member['mb_id']}'"); /* 로그인했을때 사용자의 genealogy정보를 불러옴 */
$row = mysql_fetch_array($result);

$dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$member['renewal']}', interval +4 month) AS date"));
$dateCheck_2 = mysql_fetch_array(mysql_query("SELECT date_add('{$member['renewal']}', interval +5 month) AS date"));
$TIMESTAMP = $dateCheck_1["date"];
$timestamp2 = $dateCheck_2["date"];
$now = date("Y-m-d");

if ($member['renewal'] == "") {
    $TIMESTAMP = "0000-00-00";
}

$REMAINTIME = date("Y-m-d", strtotime($TIMESTAMP . "-7 day"));
$LIMITEDAY = 7;
$REMAIN = "END";

$REMAINDATE = strtotime($REMAINTIME . " 00:00:00");
$NOWDATE = strtotime($now . " 00:00:00");
$TIMESTAMPDATE = strtotime($TIMESTAMP . " 00:00:00");
if ($NOWDATE >= $REMAINDATE) {
    $REMAINTIMETMP = date_create($REMAINTIME);
    $NOWDATETMP = date_create($now);
    $INTERVAL = date_diff($REMAINTIMETMP, $NOWDATETMP);
    $REMAIN = $LIMITEDAY - $INTERVAL->days;
    //"지금 시간이 더 크거나 같다
} elseif ($NOWDATE == $TIMESTAMPDATE) {
    //만료
    $REMAIN = "END";
}


$timestamp_temp = strtotime($TIMESTAMP);
$timestamp2_temp = strtotime($timestamp2);
$now_temp = strtotime($now);


if ($member['accountType'] == 'VM' && ($now_temp > $timestamp_temp && $now_temp <= $timestamp2_temp)) {
    echo "<script>alert('현재 VM 기간이 만료된 상태입니다. 리뉴얼을 진행하셔야 VM 권한이 유지됩니다.');</script>";
}

if ($member['accountType'] == "CU" && $member['renewal'] != null) {
    echo "<style>#renewalImage {display:block;}  #renewalImage2 {display:none;}#sponsor_pop{display:none;}#sponsor_pop2{display:none;} #smBtn {display:none;} #SmSignup {display:none;}</style>";
} else if ($member['accountType'] == "CU") {
    echo "<style>#renewalImage {display:none;} #renewalImage2 {display:block;}.pop_vmc{display:none}.pop_vmr{display:none} </style>";
} else if ($member['accountType'] == "VD") {
    echo "<style>#renewalImage {display:none;}#renewalImage2 {display:none;} #RenewalButton {display:none;} #smBtn {display:none;} #SmSignup {display:none;}</style>";
} else if ($member['accountType'] == "SM") {
    echo "<style>#renewalImage {display:none;}#renewalImage2 {display:block;} #RenewalButton {display:block;} #smBtn {display:block;} #SmSignup {display:block;}.pop_vmc{display:none}.pop_vmr{display:none}</style>";
} else if ($member['accountType'] == 'VM') {
    echo "<style>#renewalImage {display:block;}  #renewalImage2 {display:none;}#sponsor_pop{display:none;}#sponsor_pop2{display:none;} #smBtn {display:none;} #SmSignup {display:none;}</style>";
} else if ($member['accountType'] == "CU" && $member['renewal'] != null && $TIMESTAMP <= $now_temp) {
    echo "<style>#renewalImage {display:block;}  #renewalImage2 {display:none;}.pop_vmc{display:none}.pop_vmr{display:none} #smBtn {display:none;} #SmSignup {display:none;}</style>";
} else {
    echo "<style>#renewalImage {display:none;}#renewalImage2 {display:none;} #RenewalButton {display:none;} #smBtn {display:none;} </style>";
}

$sql_search = " where mb_id= '{$member['mb_id']}' and VMC+VMP+VMR+VMM+VMG+V+VCash+VPay+bizMoney != 0 ";

if ($date_start == "") {
    $date_start = date("Y-m-d");
}
if ($date_end == "") {
    $date_end = date("Y-m-d");
}

if ($period == "") {
    $period = "1";
}

if ($type == "") {
    $type = $type2;
} elseif ($type2 == "") {
    $type2 = $type;
} elseif ($type2 != $type) {
    $type = $type2;
}


if ($type == "2") {
    $sql_type = "AND  (VMC < 0 OR VMR < 0 OR VMP < 0 OR VMM < 0 OR bizMoney < 0 OR VCash < 0 OR VPay < 0)";

    if ($price_start != 0) {
        $sql_search .= "and VMC+VMP+VMR+VMM+VMG+V+VCash+VPay+bizMoney <='-$price_start'";
    }
    if ($price_end != 0) {
        $sql_search .= "and VMC+VMP+VMR+VMM+VMG+V+VCash+VPay+bizMoney >='-$price_end'";
    }
} else if ($type == "3") {
    $sql_type = "";
    if ($price_start != 0 && $price_end != 0) {
        $sql_search .= "   AND (VMC + VMP + VMR + VMM + VMG + V + VCash + VPay + bizMoney >= '$price_start'
         AND VMC + VMP + VMR + VMM + VMG + V + VCash + VPay + bizMoney <= '$price_end'
          or (VMC + VMP + VMR + VMM + VMG + V + VCash + VPay + bizMoney <= '-$price_start'
         AND VMC + VMP + VMR + VMM + VMG + V + VCash + VPay + bizMoney >= '-$price_end'))";
    } else {
        if ($price_start != 0) {
            $sql_search .= "and (VMC+VMP+VMR+VMM+VMG+V+VCash+VPay+bizMoney >='$price_start')";
            $sql_search .= "or( VMC+VMP+VMR+VMM+VMG+V+VCash+VPay+bizMoney <='-$price_start')";
        }
        if ($price_end != 0) {
            $sql_search .= "and (VMC+VMP+VMR+VMM+VMG+V+VCash+VPay+bizMoney <='$price_end')";
            $sql_search .= "or( VMC+VMP+VMR+VMM+VMG+V+VCash+VPay+bizMoney >='-$price_end')";
        }
    }
} else {
    $sql_type = "AND ! (VMC < 0 OR VMR < 0 OR VMP < 0 OR VMM < 0 OR bizMoney < 0 OR VCash < 0 OR VPay < 0)";

    if ($price_start != 0) {
        $sql_search .= "and VMC+VMP+VMR+VMM+VMG+V+VCash+VPay+bizMoney >='$price_start'";
    }
    if ($price_end != 0) {

        $sql_search .= "and VMC+VMP+VMR+VMM+VMG+V+VCash+VPay+bizMoney <='$price_end'";
    }
}


if ($sel_month != "") {
    $sql_search .= "and month(date) = '$sel_month'";
}

if ($sel_day != "") {
    $sql_search .= "and dayofmonth(date) = '$sel_day'";
}


if ($date_start != "" && $date_end != "") {
    $sql_search .= "and date BETWEEN '$date_end' AND '$date_start 23:59:59'";
    //2020-02-07    2020-02-14
}

$daily_arr = explode(',', $daily);
if ($daily != '') {
    $sql_search .= "and(";
    for ($i = 0; $i < count($daily_arr); $i++) {
        if ($i == 0) {
            $sql_search .= "SUBSTR(_UTF8'일월화수목금토', DAYOFWEEK(date), 1) = '$daily_arr[$i]'";
        } else {
            $sql_search .= "or SUBSTR(_UTF8'일월화수목금토', DAYOFWEEK(date), 1) = '$daily_arr[$i]'";
        }
    }
    $sql_search .= ")";
}


$today = date("Y-m-d");


$sql_search .= $sql_type;


$sql_common = " from dayPoint ";
$sql_common .= $sql_search;

// 테이블의 전체 레코드수만 얻음
//$sql = " select count(*) as cnt " . $sql_common;
//$row = sql_fetch($sql);
//$total_count = $row['cnt'];
//
//$rows = 10;
//$total_page = ceil($total_count / $rows);  // 전체 페이지 계산
//if ($page < 1) {
//    $page = 1;
//} // 페이지가 없으면 첫 페이지 (1 페이지)
//$from_record = ($page - 1) * $rows; // 시작 열을 구함
//
//if (!$sst) {
//    $sst = "date";
//    $sod = "desc";
//}
//$sql_order = "order by $sst $sod";
// 출력할 레코드를 얻음
$sql = " select date,mb_id,VMC,VMR,VMP,VMM,VMG,V,VCash,VPay,bizMoney,VMC+VMP+VMR+VMM+VMG+V+VCash+VPay+bizMoney as total,
    way, SUBSTR(_UTF8'일월화수목금토', DAYOFWEEK(date), 1 ) as weekday, dayofmonth(date) as days ,month(date) as month
             $sql_common
             order by date desc
             ";
$result = sql_query($sql);

$total_p = 0; //포인트 총 합계금액
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="./js/wow.min.js"></script>

<script>
    $(function () {
        $.datepicker.regional['ko'] = {
            closeText: '닫기', // 닫기 버튼 텍스트 변경
            currentText: '오늘', // 오늘 텍스트 변경
            monthNames: ['1 월', '2 월', '3 월', '4 월', '5 월', '6 월', '7 월', '8 월', '9 월', '10 월', '11 월', '12 월'], // 개월 텍스트 설정
            monthNamesShort: ['1 월', '2 월', '3 월', '4 월', '5 월', '6 월', '7 월', '8 월', '9 월', '10 월', '11 월', '12 월'], // 개월 텍스트 설정
            dayNames: ['일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일'], // 요일 텍스트 설정
            dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'], // 요일 텍스트 축약 설정&nbsp
            dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'], // 요일 최소 축약 텍스트 설정
        };

// Seeting up default language, Korean
        $.datepicker.setDefaults($.datepicker.regional['ko']);
        $("#date_start").datepicker({dateFormat: 'yy-mm-dd'});
        $('#date_end').datepicker({dateFormat: 'yy-mm-dd'});
        new WOW().init();
    });
</script>
<script type="text/javascript">


    $(document).ready(function () {
        var period = "<?php echo $period; ?>";
        var daily = "<?php echo $daily; ?>";

        var daily_arr = daily.split(",");

        $(".mr0").removeClass('on');
        $('.mr0').each(function () {
            var value = $(this).attr('value');
            if (period == value) {
                $(this).attr("class", 'mr0 on');
            }
        });

        $(".mr1").removeClass('on');

        $('.mr1').each(function () {
            for (var i = 0; i < daily_arr.length; i++) {
                var value = $(this).attr('value');
                if (daily_arr[i] == value) {
                    $(this).attr("class", 'mr1 on');
                    console.log(daily_arr[i]);
                }
            }
        });
    });

    function loading() {
        $('.check_msg').addClass('load');
        $('.loading_msg p').text('데이터를 조회중입니다. 잠시만 기다려주세요');
    }

    function change_month() {
        var itemidSelect = document.getElementById("sel_month");

        // select element에서 선택된 option의 value
        var itemID = itemidSelect.options[itemidSelect.selectedIndex].value;
        console.log('itemID: ' + itemID);
        var itemName = itemidSelect.options[itemidSelect.selectedIndex].text;
        console.log('itemName: ' + itemName);
        $('#month').text(itemName);
    }

    function change_day() {
        var itemidSelect = document.getElementById("sel_day");

        // select element에서 선택된 option의 value
        var itemID = itemidSelect.options[itemidSelect.selectedIndex].value;
        console.log('itemID: ' + itemID);
        var itemName = itemidSelect.options[itemidSelect.selectedIndex].text;
        console.log('itemName: ' + itemName);
        $('#day').text(itemName);
    }

    function change_mth(t) {
        var priod = t.attr("value");
        var today = "<?php echo $today ?>";
        var end_day = "<?php echo $today ?>";

        $(".mr0").removeClass('on');
        t.addClass('on');
        $('#date_start').val("<? echo $today;?>");
        if (priod == "1") {
            $('#date_start').attr("value", today);
            $('#date_end').attr("value", end_day);
            $('#period').attr("value", "1");
        } else if (priod == "2") {
            end_day = "<?php echo date("Y-m-d", strtotime("-3 days")) ?>";
            $('#date_start').attr("value", today);
            $('#date_end').attr("value", end_day);
            $('#period').attr("value", "2");
        } else if (priod == "3") {
            end_day = "<?php echo date("Y-m-d", strtotime("-7 days")) ?>";
            $('#date_start').attr("value", today);
            $('#date_end').attr("value", end_day);
            $('#period').attr("value", "3");
        } else if (priod == "4") {
            end_day = "<?php echo date("Y-m-d", strtotime("-1 month")) ?>";
            $('#date_start').attr("value", today);
            $('#date_end').attr("value", end_day);
            $('#period').attr("value", "4");
        } else if (priod == "5") {
            end_day = "<?php echo date("Y-m-d", strtotime("-3 month")) ?>";
            $('#date_start').attr("value", today);
            $('#date_end').attr("value", end_day);
            $('#period').attr("value", "5");
        } else if (priod == "6") {
            end_day = "<?php echo date("Y-m-d", strtotime("-6 month")) ?>";
            $('#date_start').attr("value", today);
            $('#date_end').attr("value", end_day);
            $('#period').attr("value", "6");
        } else if (priod == "7") {
            end_day = "<?php echo date("Y-m-d", strtotime("-1 years")) ?>";
            $('#date_start').attr("value", today);
            $('#date_end').attr("value", end_day);
            $('#period').attr("value", "7");
        }
    }

    function change_daily(t) {
        var class_ck = t.attr("class");

        if (class_ck == 'mr1 on') {
            t.removeClass('on');
            var value = $(this).attr('value');
        } else {
            t.addClass('on');
            var value = $(this).attr('value');
        }
        var daily = new Array();

        $('.mr1').each(function () {
            var ck = $(this).attr('class');
            if (ck == 'mr1 on') {
                var value = $(this).attr('value');
                daily.push(value);
            }
        });

        console.log(daily);
        $('#daily').attr("value", daily);
    }
</script>
</head>
<body>
<!-- header -->
<?php
include_once('../shop/shop.head.php');
?>
<div class="detail_DPS_WTH">
    <form name="search_form" method="get" onsubmit="loading()">

            <span class="btn_list">
                <input type="hidden" id="list_op" value="10">
                <button class="list_dps" style="display: none;">쉿<span class="button_image"></span></button>
                <button class="list_dps
                <?php
                if ($type == "1" || $type == "") {
                    echo 'on';
                }
                ?>"
                        name="type2" value="1">입금 내역<span class="button_image"></span></button>
                <button class="list_wth
                <?php
                if ($type == "2") {
                    echo 'on';
                }
                ?>" name="type2" value="2">출금 내역<span class="button_image"></span></button>
                <button class="list_dpsWth
                <?php
                if ($type == "3") {
                    echo 'on';
                }
                ?>" name="type2" value="3">입출금 내역<span class="button_image"></span></button>


            </span>

        <div class="search_box wow fadeInDown clearfix">
            <input type="hidden" id="type" name="type" value="<?php echo $type; ?>">

            <div class="date">
                <label for="date" class="nametag">날짜</label>
                <input type="text" value="<?php echo $date_end; ?>" id="date_end" name="date_end"/><b> - </b> <input
                        type="text" value="<?php echo $date_start; ?>" id="date_start" name="date_start"/>
            </div>
            <div class="prices">
                <label for="price_start" class="nametag">금액별</label>
                <input type="number" value="<?php echo $price_start; ?>" name="price_start" id="price_start"/><b> - </b><input
                        type="number" name="price_end" value="<?php echo $price_end; ?>" id="price_end"/>
            </div>

            <div class="monthly">
                <label for="sel_month" class="nametag">월별</label>
                <div class="selectcustom">
                    <label for="monthly" class="nametag" name="month" id="month"><?php
                        if ($sel_month != "") {
                            echo $sel_month . "월";
                        } else {
                            echo "월별";
                        }
                        ?></label>
                    <select id='sel_month' name="sel_month" onchange="change_month()">
                        <option value=""<?php echo get_selected($_GET['sel_month'], ""); ?>>월별</option>
                        <option value="1"<?php echo get_selected($_GET['sel_month'], "1"); ?>>1월</option>
                        <option value="2"<?php echo get_selected($_GET['sel_month'], "2"); ?>>2월</option>
                        <option value="3"<?php echo get_selected($_GET['sel_month'], "3"); ?>>3월</option>
                        <option value="4"<?php echo get_selected($_GET['sel_month'], "4"); ?>>4월</option>
                        <option value="5"<?php echo get_selected($_GET['sel_month'], "5"); ?>>5월</option>
                        <option value="6"<?php echo get_selected($_GET['sel_month'], "6"); ?>>6월</option>
                        <option value="7"<?php echo get_selected($_GET['sel_month'], "7"); ?>>7월</option>
                        <option value="8"<?php echo get_selected($_GET['sel_month'], "8"); ?>>8월</option>
                        <option value="9"<?php echo get_selected($_GET['sel_month'], "9"); ?>>9월</option>
                        <option value="10"<?php echo get_selected($_GET['sel_month'], "10"); ?>>10월</option>
                        <option value="11"<?php echo get_selected($_GET['sel_month'], "11"); ?>>11월</option>
                        <option value="12"<?php echo get_selected($_GET['sel_month'], "12"); ?>>12월</option>
                    </select>
                </div>
            </div>

            <div class="daily">
                <label for="daily" class="nametag">일별</label>
                <div class="selectcustom">
                    <label for="daily" class="nametag" name="day" id="day"><?php
                        if ($sel_day != "") {
                            echo $sel_day . "일";
                        } else {
                            echo "일별";
                        }
                        ?></label>
                    <select id='sel_day' name="sel_day" onchange="change_day()">
                        <option value=""<?php echo get_selected($_GET['sel_day'], ""); ?>>일별</option>
                        <?php
                        for ($i = 1; $i < 32; $i++) {
                            ?>
                            <option value="<?php echo $i; ?>"<?php echo get_selected($_GET['sel_day'], $i); ?>><?php echo $i . '일'; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="weekly">
                <input type="hidden" id="daily" name="daily" value="<?php echo $daily; ?>">
                <label for="weekly" class="nametag">요일별</label>
                <a href='javascript:void(0);' onclick="change_daily($(this))"
                   class="mr1" value="월">월</a>
                <a href='javascript:void(0);' onclick="change_daily($(this))"
                   class="mr1" value="화">화</a>
                <a href='javascript:void(0);' onclick="change_daily($(this))"
                   class="mr1" value="수">수</a>
                <a href='javascript:void(0);' onclick="change_daily($(this))"
                   class="mr1" value="목">목</a>
                <a href='javascript:void(0);' onclick="change_daily($(this))"
                   class="mr1" value="금">금</a>
                <a href='javascript:void(0);' onclick="change_daily($(this))"
                   class="mr1" value="토">토</a>
                <a href='javascript:void(0);' onclick="change_daily($(this))"
                   class="mr1" value="일">일</a>
            </div>

            <div class="period clearfix">
                <input type="hidden" name="period" id="period" value="<?php echo $period; ?>">
                <label for="period" class="nametag">기간별</label>
                <a href='javascript:void(0);' onclick="change_mth($(this));"
                   class="mr0 on" value="1">당일
                </a>
                <a href='javascript:void(0);' onclick="change_mth($(this));"
                   class="mr0" value="2">3일
                </a>
                <a href='javascript:void(0);' onclick="change_mth($(this));"
                   class="mr0" value="3">1주일
                </a>
                <a href='javascript:void(0);' onclick="change_mth($(this));"
                   class="mr0" value="4">1개월
                </a>
                <a href='javascript:void(0);' onclick="change_mth($(this));"
                   class="mr0" value="5">3개월
                </a>
                <a href='javascript:void(0);' onclick="change_mth($(this));"
                   class="mr0" value="6">6개월
                </a>
                <a href='javascript:void(0);' onclick="change_mth($(this));"
                   class="mr0" value="7">12개월
                </a>
            </div>
            <div class="search">
                <label for="search" class="nametag">내용</label>
                <input type="text" id="search" name="search" value="<?php echo $search; ?>"/>
            </div>
            <button class="search_start" id="search_start">검색하기</button>
    </form>
</div>
<div class="total_wrap_tbl active wow fadeInDown">
    <p class="total_tit">검색 결과 총합</p>
    <dl class="vbonus_ttl">
        <dt>VBonus</dt>
        <dd><b class="single_total" id="vbonus_ttl">0</b>원</dd>
    </dl>
    <dl class="safe_ttl">
        <dt>금고</dt>
        <dd><b class="single_total" id="safe_ttl">0</b>원</dd>
    </dl>
    <dl class="vmm_ttl">
        <dt>VMM</dt>
        <dd><b class="single_total" id="vmm_ttl">0</b>원</dd>
    </dl>
    <dl class="vmc_ttl">
        <dt>VMC</dt>
        <dd><b class="single_total" id="vmc_ttl">0</b>원</dd>
    </dl>
    <dl class="vmp_ttl">
        <dt>VMP</dt>
        <dd><b class="single_total" id="vmp_ttl">0</b>원</dd>
    </dl>
    <dl class="vcash_ttl">
        <dt>VCash</dt>
        <dd><b class="single_total" id="vcash_ttl">0</b>원</dd>
    </dl>
    <dl class="vpay_ttl">
        <dt>VPay</dt>
        <dd><b class="single_total" id="vpay_ttl">0</b>원</dd>
    </dl>
    <dl class="biz_ttl">
        <dt>Biz</dt>
        <dd><b class="single_total" id="biz_ttl">0</b>원</dd>
    </dl>
</div>


<table class="result_search wow fadeInUp">
    <caption class="mobile_th">검색결과</caption>
    <thead class="desktop_th">
    <tr>
        <th>기간</th>
        <th>입/출금</th>
        <th>포인트</th>
        <th>합계</th>
        <th>내용</th>
    </tr>
    </thead>
    <tbody>

    <?php
    $indexchk = false;
    $V = 0;
    $VMG = 0;
    $VMM = 0;
    $VMC = 0;
    $VMP = 0;
    $VCash = 0;
    $VPay = 0;
    $bizMoney = 0;

    for ($i = 0; $row = sql_fetch_array($result); $i++) {
        ?>
        <?php
//                $total_point = $row['V'] + $row['VMG'] + $row['VMM'] + $row['VMC'] + $row['VMP'] + $row['VCash'] + $row['VPay'];
        ?>
        <?php
        if ($search != "") {
            $content = parsing($row['way']);
            if (strpos($content, $search) !== false) {
                $total_p = $total_p + $row['total'];
                $indexchk = true;
                $V = $V + $row['V'];
                $VMG = $VMG + $row['VMG'];
                $VMM = $VMM + $row['VMM'];
                $VMC = $VMC + $row['VMC'];
                $VMP = $VMP + $row['VMP'];
                $VCash = $VCash + $row['VCash'];
                $VPay = $VPay + $row['VPay'];
                $bizMoney = $bizMoney + $row['bizMoney'];
                ?>
                <tr>
                    <td>
                        <b class="mobile_tit">기간</b><?php echo $row['date']; ?> <?php echo '(' . $row['weekday'] . ')'; ?>
                        <br class="enter"/></td>
                    <td><b class="mobile_tit">입/출금</b><?php
                        if ($row['total'] > 0) {
                            echo '입금';
                        } else {
                            echo '출금';
                        }
                        ?></td>
                    <td class="point"><b class="mobile_tit">포인트</b>
                        <?php if ($row['V'] != 0) { ?>
                            <span class="vcoin"><?php echo number_format($row['V']); ?>원</span>
                        <?php } ?>
                        <?php if ($row['VMG'] != 0) { ?>
                            <span class="safe"><?php echo number_format($row['VMG']); ?>원</span>
                        <?php } ?>
                        <?php if ($row['VMM'] != 0) { ?>
                            <span class="vmm"><?php echo number_format($row['VMM']); ?>원</span>
                        <?php } ?>
                        <?php if ($row['VMC'] != 0) { ?>
                            <span class="p_vmc"><?php echo number_format($row['VMC']); ?>원</span>
                        <?php } ?>
                        <?php if ($row['VMP'] != 0) { ?>
                            <span class="p_vmp"><?php echo number_format($row['VMP']); ?>원</span>
                        <?php } ?>
                        <?php if ($row['VCash'] != 0) { ?>
                            <span class="vcash"><?php echo number_format($row['VCash']); ?>원</span>
                        <?php } ?>
                        <?php if ($row['VPay'] != 0) { ?>
                            <span class="vpay"><?php echo number_format($row['VPay']); ?>원</span>
                        <?php } ?>
                        <?php if ($row['bizMoney'] != 0) { ?>
                            <span class="biz"><?php echo number_format($row['bizMoney']); ?>원</span>
                        <?php } ?>
                    </td>
                    <td><b class="mobile_tit">합계</b><span
                                class="m_font_inhrt"><?php echo number_format($row['total']); ?></span>원
                    </td>
                    <td><b class="mobile_tit">내용</b><?php echo parsing($row['way']); ?></td>
                </tr>
                <?php
            }
        } else {

            $indexchk = true;
            $total_p = $total_p + $row['total'];

            $V = $V + $row['V'];
            $VMG = $VMG + $row['VMG'];
            $VMM = $VMM + $row['VMM'];
            $VMC = $VMC + $row['VMC'];
            $VMP = $VMP + $row['VMP'];
            $VCash = $VCash + $row['VCash'];
            $VPay = $VPay + $row['VPay'];
            $bizMoney = $bizMoney + $row['bizMoney'];
            ?>
            <tr>
                <td><b class="mobile_tit">기간</b><?php echo $row['date']; ?> <?php echo '(' . $row['weekday'] . ')'; ?>
                    <br class="enter"/></td>
                <td><b class="mobile_tit">입/출금</b><?php
                    if ($row['total'] > 0) {
                        echo '입금';
                    } else {
                        echo '출금';
                    }
                    ?></td>
                <td class="point"><b class="mobile_tit">포인트</b>
                    <?php if ($row['V'] != 0) { ?>
                        <span class="vcoin"><?php echo number_format($row['V']); ?>원</span>
                    <?php } ?>
                    <?php if ($row['VMG'] != 0) { ?>
                        <span class="safe"><?php echo number_format($row['VMG']); ?>원</span>
                    <?php } ?>
                    <?php if ($row['VMM'] != 0) { ?>
                        <span class="vmm"><?php echo number_format($row['VMM']); ?>원</span>
                    <?php } ?>
                    <?php if ($row['VMC'] != 0) { ?>
                        <span class="p_vmc"><?php echo number_format($row['VMC']); ?>원</span>
                    <?php } ?>
                    <?php if ($row['VMP'] != 0) { ?>
                        <span class="p_vmp"><?php echo number_format($row['VMP']); ?>원</span>
                    <?php } ?>
                    <?php if ($row['VCash'] != 0) { ?>
                        <span class="vcash"><?php echo number_format($row['VCash']); ?>원</span>
                    <?php } ?>
                    <?php if ($row['VPay'] != 0) { ?>
                        <span class="vpay"><?php echo number_format($row['VPay']); ?>원</span>
                    <?php } ?>
                    <?php if ($row['bizMoney'] != 0) { ?>
                        <span class="biz"><?php echo number_format($row['bizMoney']); ?>원</span>
                    <?php } ?>
                </td>
                <td><b class="mobile_tit">합계</b><span
                            class="m_font_inhrt"><?php echo number_format($row['total']); ?></span>원
                </td>
                <td><b class="mobile_tit">내용</b><?php echo parsing($row['way']); ?></td>
            </tr>
            <?php
        }
        ?>

        <?php
    }
    //            echo    $total_p; 총포인트 금액
    ?>
    <?php
    if (!$indexchk) {
        ?>
        <tr>
            <td colspan="6">조회된 내역이 없습니다.</td>
        </tr>
        <?php
    }
    ?>


    </tbody>
</table>



<?php
//    echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&page=');
?>


</div>

<div class="check_msg">
    <div class="loading_msg">
        <img src="./simpleJoin/img/vmp_loading_icon_4.gif" alt="로딩메세지"/>
        <p>데이터를 조회중입니다. 잠시만 기다려주세요</p>
    </div>
</div>
<?php
include_once('../shop/shop.tail.php'); // gnb 디자인, copyright 마크업 들어있는 소스
?>


</body>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>

    point_sum();
    function point_sum() {
        $('#vbonus_ttl').text(number_format('<?=$V?>'));
        $('#safe_ttl').text(number_format('<?=$VMG?>'));
        $('#vmm_ttl').text(number_format('<?=$VMM?>'));
        $('#vmc_ttl').text(number_format('<?=$VMC?>'));
        $('#vmp_ttl').text(number_format('<?=$VMP?>'));
        $('#vcash_ttl').text(number_format('<?=$VCash?>'));
        $('#vpay_ttl').text(number_format('<?=$VPay?>'));
        $('#biz_ttl').text(number_format('<?=$bizMoney?>'));

    }


</script>
</html>