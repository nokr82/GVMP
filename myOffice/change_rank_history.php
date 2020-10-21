<link rel="shortcut icon" href="/img/vmp_logo.ico"/>

<link rel="stylesheet" href="./css/index.css"/>
<link rel="stylesheet" href="./css/v_ad_center.css"/>
<link rel="stylesheet" href="css/animate.css"/>
<link rel="stylesheet" href="../myOffice/css/loading.css"/>

<link rel="stylesheet" href="css/myoffice_sub.css"/>

<style>
    .loading_wrap.on {
        display: block
    }

    .loading_wrap.off {
        display: none
    }

    .loading_wrap {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 9999;
        background: rgba(255, 255, 255, 0.7);
        text-align: center
    }

    .loading_wrap .loading_content {
        position: absolute;
        top: 50%;
        left: 50%;
        margin-top: -150px;
        margin-left: -200px;
        width: 400px;
        height: 300px;
    }

    .loading_wrap .loading_content .loading_img {
        margin: 0 auto
    }

    .loading_wrap.on .loading_content .loading_txt {
        font-family: 'Noto Sans KR';
        font-size: 1rem;
        color: #333
    }


    @media (min-width: 320px) and (max-width: 480px) {
        .loading_wrap .loading_content {
            margin-left: -100px;
            width: 200px;
        }

        .loading_wrap .loading_content .loading_img {
            width: 80%
        }

        .loading_wrap.on .loading_content .loading_txt {
            font-size: 1.2rem
        }
    }
</style>

<?php
include_once(G5_PLUGIN_PATH . '/jquery-ui/datepicker.php');
include_once('./inc/title.php');
include_once('./_common.php');
include_once('./dbConn.php');
include_once('./getMemberInfo.php');
include_once('./point_pop_back_func.php');
if ($is_guest) // 로그인 안 했을 때 로그인 페이지로 이동
    header('Location: /bbs/login.php');
//페이징처리함수
$num = 0;
$get_num = $_GET['num'];
$page = $num + $get_num;


$today = date("Y-m-d");

$arr_first = $_GET['first'];
$arr_level = $_GET['level'];
$search_type = $_GET['search_type'];
$search = $_GET['search_content'];

//현재페이지 url가져오기
$request_uri = $_SERVER['REQUEST_URI'];


if ($member["mb_id"] == "admin") {
    $member["mb_id"] = '00000001';
}
$date_start = $_GET['date_start'];
$date_end = $_GET['date_end'];

if ($date_start == "") {
    $date_start = date("Y-m-d");
}
if ($date_end == "") {
    $date_end = date("Y-m-d");
}

$e_date = str_replace('-', '', $date_end);
$s_date = str_replace('-', '', $date_start);
$arr_level_str = implode(',', $arr_level);


$sql_search = '';

if ($date_start != "" && $date_end != "") {
    $sql_search .= "datetime BETWEEN '$date_end' AND '$date_start 23:59:59'";
}
if ($search_type == 'id' && $search != '') {
    $search_type = 'r.mb_id';
    $sql_search .= "and {$search_type} = '{$search}'";
} else if ($search_type == 'name' && $search != '') {
    $search_type = 'g.mb_name';
    $sql_search .= "and {$search_type} like '{$search}%'";
}


$v_Self = 0;
$v_first = 'N';

if (in_array('2', $arr_first)) {
    $search_type = 'r.first_check';
    $sql_search .= "and {$search_type} = 'Y'";
}


if (isset($arr_level)) {
    if (count($arr_level) == 1) {
        $sql_level .= " and change_rank='{$arr_level[0]}'";
    } else {
        $sql_level .= " and (change_rank='{$arr_level[0]}'";
        for ($i = 1, $iMax = count($arr_level); $i < $iMax; $i++) {
            $sql_level .= " or change_rank='{$arr_level[$i]}'";
            if ($iMax - 1 == $i) {
                $sql_level .= ')';
            }
        }
    }
}
$count['count(*)'] = 0;
$count2['count(*)'] = 0;

$sql = "SELECT  count(*) from rank_history as r inner join genealogy_tree as g on r.mb_id = g.mb_id  WHERE g.rootid='{$member['mb_id']}' and {$sql_search} {$sql_level}";
$sql2 = "SELECT  count(*) from rank_history  WHERE mb_id = {$member['mb_id']} and {$sql_search} {$sql_level}";
if ($arr_level_str != '') {
//    $count = mysql_num_rows(mysql_query($sql));
    $count_ = mysql_query($sql);
    $count = mysql_fetch_array($count_);
//자신만뽑기
    $count_2 = mysql_query($sql2);
    $count2 = mysql_fetch_array($count_2);
}
if (in_array('1', $arr_first)) {
    $count['count(*)'] = $count2['count(*)'];
}else{
    $count['count(*)'] = $count['count(*)'] + $count2['count(*)'];
}
//페이징처리
$page = ($_GET['page']) ? $_GET['page'] : 1;
$list = 10;
$block = 5;
$num_pg = $count['count(*)'];
$pageNum = ceil($num_pg / $list); // 총 페이지
$blockNum = ceil($pageNum / $block); // 총 블록
$nowBlock = ceil($page / $block);
$s_page = ($nowBlock * $block) - 4;
if ($s_page <= 1) {
    $s_page = 1;
}

$e_page = $nowBlock * $block;
if ($pageNum <= $e_page) {
    $e_page = $pageNum;
}
?>
<link rel="stylesheet" href="<?php echo G5_PLUGIN_URL ?>/jquery-ui/jquery-ui.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<script src="./js/wow.min.js"/>
</head>
<body class="myoffice_sub">
<!-- header -->
<?php
include_once('../shop/shop.head.php');
?>
<div class="chg_level_history under_chglv">
    <form name="search_form" method="get" onsubmit="return search_check()">
        <input type="hidden" id="mb_id" value="<?= $member['mb_id'] ?>"/>
        <h3 class="fz0 color_blue1"><span class="disib"></span>직급 변경 이력 검색</h3>
        <div class="search_box wow fadeInDown">
            <div class="flexbox mg02">
                <label class="check_contain ml0">나의 직급 변경 이력만
                    <input type="checkbox" onclick="firstcheck(this)"
                        <?php
                        if (in_array('1', $arr_first)) {
                            ?>
                            checked
                            <?
                        }
                        ?>
                           name="first[]" value="1"/>
                    <span class="checkmark"></span>
                </label>
                <label class="check_contain">최초 달성 이력만
                    <input type="checkbox" onclick="firstcheck(this)"
                        <?php
                        if (in_array('2', $arr_first)) {
                            ?>
                            checked
                            <?php
                        }
                        ?>
                           name="first[]" value="2"/>
                    <span class="checkmark"></span>
                </label>
            </div>

            <div class="selectbox flexbox fl_wrap">
                <div class="select_contain">
                    <label for="search_type"><?php
                        if ($_GET['search_type'] == 'id') {
                            echo '아이디';
                        } else {
                            echo '이름';
                        } ?></label>
                    <select id="search_type" name="search_type" class="select_custom">
                        <option value="id"
                            <?php
                            if ($_GET['search_type'] == 'id') {
                                echo 'selected';
                            }
                            ?>
                        >아이디
                        </option>
                        <option value="name"
                            <?php
                            if ($_GET['search_type'] == 'name') {
                                echo 'selected';
                            }
                            ?>
                        >이름
                        </option>
                    </select>
                </div>

                <div class="search">
                    <input type="text" id="search_content" name="search_content" value="<?php echo $search; ?>"/>
                </div>


                <dl class="date">
                    <dt class="dot">
                        <span class="dot_blue1 disib"></span>날짜
                    </dt>
                    <dd>
                        <input type="text" readonly value="<?php echo $date_end; ?>" id="date_end" name="date_end"/><b>
                            ~ </b> <input type="text" readonly value="<?php echo $date_start; ?>" id="date_start"
                                          name="date_start"/>
                    </dd>
                    <dd class="flo_n">
                        <div class="period flexbox">
                            <input type="hidden" name="period" id="period" value="<?php echo $period; ?>">
                            <button type="button" class="mr0" onclick="change_mth($(this));" value="3">1주일</button>
                            <button type="button" class="mr0" onclick="change_mth($(this));" value="4">1개월</button>
                            <button type="button" class="mr0" onclick="change_mth($(this));" value="5">3개월</button>
                            <button type="button" class="mr0" onclick="change_mth($(this));" value="6">6개월</button>
                            <button type="button" class="mr0" onclick="change_mth($(this));" value="7">12개월</button>
                        </div>
                    </dd>
                </dl>
            </div>

            <dl class="level">
                <dt class="dot mg02"><span class="dot_blue1 disib"></span>직급</dt>
                <dd class="mg02">
                    <label class="check_contain ml0">직급 전체 선택
                        <input type="checkbox" name="checkall2" id="checkall2" value="Y"
                            <?php
                            if ($_GET['checkall2'] == 'Y') {
                                ?>
                                checked
                                <?php
                            }
                            ?>/>
                        <span class="checkmark"></span>
                    </label>
                </dd>
                <dd class="flo_l mg02">
                    <label class="check_contain ml0">CU
                        <input type="checkbox" onclick="levelall(this)" <?php
                        if (in_array('CU', $arr_level)) {
                            ?>
                            checked
                            <?php
                        }
                        ?>
                               name="level[]" value="CU"/>
                        <span class="checkmark"></span>
                    </label>
                    <span class="bd01"></span>
                </dd>
                <dd class="flo_l mg02">
                    <label class="check_contain">VM
                        <input type="checkbox" onclick="levelall(this)" <?php
                        if (in_array('VM', $arr_level)) {
                            ?>
                            checked
                            <?php
                        }
                        ?>
                               name="level[]" value="VM"/>
                        <span class="checkmark"></span>
                    </label>
                    <span class="bd01"></span>
                </dd>
                <dd class="flexbox flo_l mg02">
                    <label class="check_contain">MASTER
                        <input type="checkbox" onclick="levelall(this)"<?php
                        if (in_array('MASTER', $arr_level)) {
                            ?>
                            checked
                            <?php
                        }
                        ?>
                               name="level[]" value="MASTER"/>
                        <span class="checkmark"></span>
                    </label>
                    <label class="check_contain">DOUBLE MASTER
                        <input type="checkbox" onclick="levelall(this)"<?php
                        if (in_array('DOUBLE MASTER', $arr_level)) {
                            ?>
                            checked
                            <?php
                        }
                        ?>
                               name="level[]" value="DOUBLE MASTER"/>
                        <span class="checkmark"></span>
                    </label>
                    <label class="check_contain">TRIPLE MASTER
                        <input type="checkbox" onclick="levelall(this)"<?php
                        if (in_array('TRIPLE MASTER', $arr_level)) {
                            ?>
                            checked
                            <?php
                        }
                        ?>
                               name="level[]" value="TRIPLE MASTER"/>
                        <span class="checkmark"></span>
                    </label>
                    <span class="bd01"></span>
                </dd>
                <dd class="flexbox flo_l mg02">
                    <label class="check_contain ml0">1 STAR
                        <input type="checkbox" onclick="levelall(this)" <?php
                        if (in_array('1 STAR', $arr_level)) {
                            ?>
                            checked
                            <?php
                        }
                        ?>
                               name="level[]" value="1 STAR"/>
                        <span class="checkmark"></span>
                    </label>
                    <label class="check_contain">2 STAR
                        <input type="checkbox" onclick="levelall(this)"<?php
                        if (in_array('2 STAR', $arr_level)) {
                            ?>
                            checked
                            <?php
                        }
                        ?>
                               name="level[]" value="2 STAR"/>
                        <span class="checkmark"></span>
                    </label>
                    <label class="check_contain">3 STAR
                        <input type="checkbox" onclick="levelall(this)"<?php
                        if (in_array('3 STAR', $arr_level)) {
                            ?>
                            checked
                            <?php
                        }
                        ?>
                               name="level[]" value="3 STAR"/>
                        <span class="checkmark"></span>
                    </label>
                    <label class="check_contain">4 STAR
                        <input type="checkbox" onclick="levelall(this)"<?php
                        if (in_array('4 STAR', $arr_level)) {
                            ?>
                            checked
                            <?php
                        }
                        ?>
                               name="level[]" value="4 STAR"/>
                        <span class="checkmark"></span>
                    </label>
                    <label class="check_contain">5 STAR
                        <input type="checkbox" onclick="levelall(this)"<?php
                        if (in_array('5 STAR', $arr_level)) {
                            ?>
                            checked
                            <?php
                        }
                        ?>
                               name="level[]" value="5 STAR"/>
                        <span class="checkmark"></span>
                    </label>
                    <span class="bd01"></span>
                </dd>
                <dd class="flexbox flo_n highlevel">
                    <label class="check_contain ml0">AMBASSADOR
                        <input type="checkbox" onclick="levelall(this)"<?php
                        if (in_array('AMBASSADOR', $arr_level)) {
                            ?>
                            checked
                            <?php
                        }
                        ?>
                               name="level[]" value="AMBASSADOR"
                        />
                        <span class="checkmark"></span>
                    </label>
                    <label class="check_contain">DOUBLE AMBASSADOR
                        <input type="checkbox" onclick="levelall(this)"<?php
                        if (in_array('DOUBLE AMBASSADOR', $arr_level)) {
                            ?>
                            checked
                            <?php
                        }
                        ?>
                               name="level[]" value="DOUBLE AMBASSADOR"
                        />
                        <span class="checkmark"></span>
                    </label>
                    <label class="check_contain">TRIPLE AMBASSADOR
                        <input type="checkbox" onclick="levelall(this)"<?php
                        if (in_array('TRIPLE AMBASSADOR', $arr_level)) {
                            ?>
                            checked
                            <?php
                        }
                        ?>

                               name="level[]" value="TRIPLE AMBASSADOR"
                        />
                        <span class="checkmark"></span>
                    </label>
                    <label class="check_contain">CROWN AMBASSADOR
                        <input type="checkbox" onclick="levelall(this)"<?php
                        if (in_array('CROWN AMBASSADOR', $arr_level)) {
                            ?>
                            checked
                            <?php
                        }
                        ?>
                               name="level[]" value="CROWN AMBASSADOR"
                        />
                        <span class="checkmark"></span>
                    </label>
                    <label class="check_contain">ROYAL CROWN AMBASSADOR
                        <input type="checkbox" onclick="levelall(this)"<?php
                        if (in_array('ROYAL CROWN AMBASSADOR', $arr_level)) {
                            ?>
                            checked
                            <?php
                        }
                        ?>
                               name="level[]" value="ROYAL CROWN AMBASSADOR"
                        />
                        <span class="checkmark"></span>
                    </label>
                </dd>
            </dl>

            <button class="search_start color_white disb" id="search_start">검색하기</button>
        </div>
    </form>


    <form>
        <div class="content slider clearfix">

        </div>

        <div class="ta_c" hidden>
            <img class="disib" src="/myOffice/images/icon_vmp_loading2.gif" alt="로딩 중"/>
        </div>


        <div class="pager_wrap flexbox">
            <ul class="pager"></ul>
        </div>

        <div class="non_result" hidden>검색 결과가 없습니다.</div>
    </form>

</div>


<!--로딩페이지-->
<div class="loading_wrap">
    <div class="loading_content">
        <img class="loading_img" src="/myOffice/images/vmp_loading_icon.gif" alt="로딩메세지"/>
        <p class="loading_txt">데이터를 조회중입니다. 잠시만 기다려주세요</p>
    </div>
</div>


<?php
include_once('../shop/shop.tail.php'); // gnb 디자인, copyright 마크업 들어있는 소스
?>

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<script src="js/jquery.bxslider.min.js"></script>

<script>

    var totalCount = <?=$count['count(*)']?>; //전체 건수
    var totalPage = Math.ceil(totalCount / 10);//페이지 수
    var PageNum;
    var page;


    //페이징처리 페이지그리는 스크립트
    function drawPage(goTo) {

        page = goTo;

        var pageGroup = Math.ceil(page / 4);    //페이지 그룹 넘버링

        var next = pageGroup * 4;

        var prev = next - 3;


        var goNext = next + 1;

        if (prev - 1 <= 0) {

            var goPrev = 1;

        } else {

            var goPrev = prev - 1;

        }


        if (next >= totalPage) {

            goNext = totalPage;

            next = totalPage;

        } else {

            goNext = next + 1;

        }


        var e_prevStep = '<button type="button" onclick="scrollpage(10,1)" class="blind btns btn_ll">제일 처음으로 가기 </button>';

        var prevStep = '<button type="button" onclick="scrollpage(10,' + goPrev + ')"class="blind btns btn_l">5개 이전 페이지로 가기 </button>';


        if (totalCount != 0) {
            $(".pager_wrap").empty();
            $(".pager_wrap").append(e_prevStep);
            $(".pager_wrap").append(prevStep);
            $(".pager_wrap").append('<ul class="pager" id=\'pager\'> </ul>');
            $("#pager").empty();
            for (var i = prev; i <= next; i++) {
                PageNum = '<li><button type="button" class="pg_cl"onclick="scrollpage(10,' + i + ')">' + i + ' </button></li>';
                $("#pager").append(PageNum);
            }
            var nextStep = '<button type="button" onclick="scrollpage(10,' + goNext + ')" class="blind btns btn_r">5개 이후 페이지로 가기</button>';

            var e_nextStep = '<button type="button" onclick="scrollpage(10,' + totalPage + ')" class="blind btns btn_rr">제일 마지막으로 가기 </button>';

            $(".pager_wrap").append(nextStep);
            $(".pager_wrap").append(e_nextStep);
        }


    }

    function search_check() {
        var arr_level = [];
        $('input[name="level[]"]:checked').each(function (i) {//체크된 리스트 저장
            arr_level.push($(this).val());
        });

        if (arr_level.length == 0) {
            alert('직급을 선택해주세요.');
            return false;
        }
        var uri = '<?=$request_uri?>';
        if (uri == '/myOffice/change_rank_history.php') {
            if (result != 1) {
                loading();

                //result가 1이될때까지 로딩을보이게끔
                setTimeout("search_check()", 1000);
                return false;
            }
        }

        if (result == 1 && uri == '/myOffice/change_rank_history.php') {
            $('#search_start').click();
        }
    }


    // 셀렉트 박스 커스텀에 따른 js
    var select = $('select.select_custom');
    select.change(function () {
        var select_name = $(this).children('option:selected').text();
        $(this).siblings('label').text(select_name);
    });


    $.datepicker.regional["ko"] = {
        prevText: "이전달",
        nextText: "다음달",
        currentText: "오늘",
        monthNames: ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"],
        monthNamesShort: ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"],
        dayNames: ["일", "월", "화", "수", "목", "금", "토"],
        dayNamesShort: ["일", "월", "화", "수", "목", "금", "토"],
        dayNamesMin: ["일", "월", "화", "수", "목", "금", "토"],
        weekHeader: "Wk",
        dateFormat: "yy-mm-dd",
        firstDay: 0, // Sunday is 0, Monday is 1
        isRTL: false,
        showMonthAfterYear: true,
        yearSuffix: "-",
        closeText: '닫기'
    };

    $.datepicker.setDefaults($.datepicker.regional["ko"]);

    $(function () {

        $('#date_start').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            showButtonPanel: true,
            yearRange: "-50:+0",
            onClose: function (selectedDate) {
                $("#date_end").datepicker("option", "maxDate", selectedDate);
            }
        });

        $('#date_end').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            showButtonPanel: true,
            yearRange: "-50:+0",
            onClose: function (selectedDate) {
                $("#date_start").datepicker("option", "minDate", selectedDate);
            }
        });

        $(document).on('click', "button.ui-datepicker-current", function () {
            $("#date_end").datepicker('setDate', new Date())
        });


// Seeting up default language, Korean
//         $.datepicker.setDefaults($.datepicker.regional['ko']);
        // $("#date_start").datepicker({dateFormat: 'yy-mm-dd'});
        // $('#date_end').datepicker({dateFormat: 'yy-mm-dd'});
        new WOW().init();
    });
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


    });

    function scroll_loading() {
        //스크롤막기
        $('body').on('scroll touchmove mousewheel', function (e) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        });
        $('.ta_c').show();
    }


    function change_mth(t) {
        var priod = t.attr("value");
        var today = "<?php echo $today ?>";
        var end_day = "<?php echo $today ?>";

        $(".mr0").removeClass('on');
        t.addClass('on');
        if (priod == "3") {
            end_day = "<?php echo date("Y-m-d", strtotime("-6 days")) ?>";
            $('#date_start').attr("value", today);
            $('#date_end').attr("value", end_day);
            $('#period').attr("value", "3");
        } else if (priod == "4") {
            end_day = "<?php echo date("Y-m-d", strtotime("-1 month +1 days")) ?>";
            $('#date_start').attr("value", today);
            $('#date_end').attr("value", end_day);
            $('#period').attr("value", "4");
        } else if (priod == "5") {
            end_day = "<?php echo date("Y-m-d", strtotime("-3 month +1 days")) ?>";
            $('#date_start').attr("value", today);
            $('#date_end').attr("value", end_day);
            $('#period').attr("value", "5");
        } else if (priod == "6") {
            end_day = "<?php echo date("Y-m-d", strtotime("-6 month +1 days")) ?>";
            $('#date_start').attr("value", today);
            $('#date_end').attr("value", end_day);
            $('#period').attr("value", "6");
        } else if (priod == "7") {
            end_day = "<?php echo date("Y-m-d", strtotime("-1 years +1 days")) ?>";
            $('#date_start').attr("value", today);
            $('#date_end').attr("value", end_day);
            $('#period').attr("value", "7");
        }
    }


    //전체동의 체크
    $("input[checked]").each(function (index, item) {
        $(item).parent('label').css('color', '#79cdcd');
    });

    function firstcheck(obj) {
        var check = $(obj).is(":checked");
        if (!check) {
            $(obj).parent('label').css('color', 'black');
        } else {
            $(obj).parent('label').css('color', '#79cdcd');
        }
    }


    function levelall(obj) {
        var check = $(obj).is(":checked");
        if (!check) {
            $('#checkall2').prop('checked', false).parent('label').css('color', 'black');
            $(obj).parent('label').css('color', 'black');
        } else {
            $(obj).parent('label').css('color', '#79cdcd');
        }
    }

    $('#checkall2').click(function () {
        var checkall2 = $('#checkall2').prop('checked');
        if (checkall2) {
            $("#checkall2").parent('label').css('color', '#79cdcd');
            $("input[name='level[]']").prop('checked', true).parent('label').css('color', '#79cdcd');
        } else {
            $("#checkall2").parent('label').css('color', 'black');
            $("input[name='level[]']").prop('checked', false).parent('label').css('color', 'black');
        }
    })


    /* 모바일에서만 카드 방식, pc및 태블릿에서는 스크롤링 */

    var num = 0;  //페이징과 같은 방식이라고 생각하면 된다.
    var last = 0;
    // 윈도우 사이즈 책정 후 가동 방식 분기
    var $windowWidth = $(window).width();
    var pc_mobile = "";

    function check_device_width() {
        $windowWidth = $(window).width();

        if ($windowWidth <= 800) {
            pc_mobile = 'mobile';
        } else {
            pc_mobile = 'pc';
        }
        if (pc_mobile == 'mobile') {
            scrollpage(num);
            num = num + 10;
        } else {
            scrollpage(num);
            num = num + 50;
        }


    }

    $(function () {
        //현재페이지가 초기페이지와같으면
        var uri = '<?=$request_uri?>';
        if (result != 1 && uri == '/myOffice/change_rank_history.php') {
            start_load();
        }
        check_device_width();
    });

    $(window).scroll(function () {
        if ($(window).scrollTop() >= $(document).height() - $(window).height() && pc_mobile == 'pc') {
            if (last != 1) {
                scrollpage(num);
                num = num + 50;
            }
        }
    });


    function loading() {
        //스크롤막기
        $('body').on('scroll touchmove mousewheel', function (e) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        });
        $('.loading_wrap').addClass('on');
    }

    function scrollpage(num, page) {
        var mb_id = $('#mb_id').val();

        var arr_level = [];
        $('input[name="level[]"]:checked').each(function (i) {//체크된 리스트 저장
            arr_level.push($(this).val());
        });

        var arr_first = [];
        $('input[name="first[]"]:checked').each(function (i) {//체크된 리스트 저장
            arr_first.push($(this).val());
        });

        if (pc_mobile == 'mobile') {
            $('.content.slider').empty();
            last = 2;
        }
        var search_type = $('#search_type').val();
        var search_content = $('#search_content').val();
        // console.log(mb_id);
        var date_start = $('#date_start').val();
        var date_end = $('#date_end').val();
        // console.log(num);
        var page = page;
        if (arr_level != '') {
            $.ajax({
                url: 'get_change_rank.php',
                type: 'get',
                data: {
                    num: num, mb_id: mb_id,
                    "arr_level": arr_level,
                    "arr_first": arr_first,
                    "search_type": search_type,
                    "search_content": search_content,
                    "date_start": date_start,
                    "date_end": date_end,
                    "pc_mobile": pc_mobile,
                    "page": page
                },
                beforeSend: function () {
                    if (pc_mobile != 'pc') {
                        $('.content.result *').remove();
                        loading();
                        $('.pager_wrap').css('display', 'flex')
                    } else {
                        scroll_loading();
                    }
                },
                complete: function () {
                    if (pc_mobile != 'pc') {
                        if (typeof page == 'undefined') {
                            page = 1;
                            drawPage(1);
                        } else {
                            drawPage(page);
                        }

                        $('.pg_cl').each(function (index, item) {
                            var txt = $(item).text();
                            if (txt == page) {
                                $(item).parent('li').addClass('active');
                            } else {
                                $(item).parent('li').removeClass('active');
                            }
                        });
                        $('.loading_wrap').removeClass('on');
                        $('.pager_wrap').css('display', 'flex');
                        if (ttCarousel) {
                            ttCarousel.reloadSlider();
                        } else {
                            ttCarousel = $('.result').bxSlider(ttCarousel_Config);
                        }
                    }
                },
                success: function (data) {
                    if (data == 'empty' && last == 0) {
                        $('.non_result').show();
                        // $('.content.slider').append(' <div class="non_list">조회된 내역이 없습니다.</div>');
                        last++;
                    } else if (last == 2 && data == 'empty') {
                        last++;
                    } else if (last != 1 || last == 2) {
                        $('.content.slider').append(data);
                    }
                    $('.ta_c').hide();
                    //스크롤풀기
                    $('body').off('scroll touchmove mousewheel');
                },
                error: function () {
                }
            });
        }

    }

    //슬라이더 생성문
    var carouselWidth = 800;
    var ttCarousel;
    var ttCarousel_Config = {
        minSlides: 1,
        maxSlides: 5,
        slideWidth: 285,
        slideMargin: 25,
        controls: false,
        pager: false,
        infiniteLoop: false
    }


    if ($(window).width() < carouselWidth) {
        if (ttCarousel == null)
            ttCarousel = $('.slider').bxSlider(ttCarousel_Config);
    }
    ;

    $(window).resize(function () {
        if ($(window).width() < carouselWidth) {
            //슬라이더 재생성
            if (ttCarousel) {
                ttCarousel.reloadSlider();
            } else {
                ttCarousel = $('.slider').bxSlider(ttCarousel_Config);
            }
        } else {
            //슬라이더가 생성되었다면 슬라이더 종료
            if (ttCarousel) {
                ttCarousel.destroySlider();
            }
            $('.pager_wrap').css('display', 'none');
        }
    });


    var result = 0;

    function start_load() {
        var mb_id = $('#mb_id').val();
        $.ajax({
            url: 'get_proc.php',
            type: 'post',
            data: {
                mb_id: mb_id
            },
            datatype: 'json',
            success: function (data) {
                result = data;
            },
            error: function () {
            }
        })
    }

</script>
</body>
</html>