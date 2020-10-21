<?php
include './inc/title.php';
include_once('./_common.php');
include_once('./dbConn.php');
include_once('./OrganizationChartInfo.php');
include_once('./getMemberInfo.php');

if ($is_guest) // 로그인 안 했을 때 로그인 페이지로 이동
    header('Location: /bbs/login.php');

$mainID;
if (isset($_GET['mb_id'])) {
    $mainID = $_GET['mb_id'];
} else {
    if ($member['mb_id'] == 'admin') {
        $mainID = '00000001';
    } else {
        $mainID = $member['mb_id'];
    }
}

//현재페이지 url가져오기
$request_uri = $_SERVER['REQUEST_URI'];

?>
<?php
$result = mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'");
$row = mysql_fetch_array($result);
if (!($row['accountType'] == 'VM') && !($row['mb_id'] == 'admin')) {
    echo "<script> 
    alert('VM회원만 접근 권한이 있습니다.');
    document.location.href='/myOffice/index.php'; 
    </script>";
    exit();
}
?>
<?php
$renewal = mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'");
$row2 = mysql_fetch_array($renewal);

$dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row2['renewal']}', interval +4 month) AS date"));
$dateCheck_2 = mysql_fetch_array(mysql_query("SELECT date_add('{$row2['renewal']}', interval +5 month) AS date"));
$timestamp = $dateCheck_1["date"];
$timestamp2 = $dateCheck_2["date"];
$now = date("Y-m-d");

$timestamp_temp = strtotime($timestamp);
$timestamp2_temp = strtotime($timestamp2);
$now_temp = strtotime($now);

//if( $row2['accountType'] == 'VM' && ( $now_temp >= $timestamp_temp && $now_temp <= $timestamp2_temp ) ) {
//    echo "<script>alert('{$timestamp2}이 되면 CU 계정으로 변경되오니 리뉴얼을 진행해 주시기 바랍니다.');</script>";
//    $prevPage = '/myOffice/index_start.php';
//    header('location:'.$prevPage);
//}
?>
<link rel="shortcut icon" href="http://gvmp.company/img/vmp_logo.ico"/>
<link rel="stylesheet" href="./css/sales.css">

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

</head>
<body>
<!-- header -->
<?php
include_once('../shop/shop.head.php');
?>
<input type="hidden" id="loginID" value="<?= $member['mb_id'] ?>">
<input type="hidden" id="mb_id" value="<?= $_GET['mb_id'] ?>">
<!-- 산하 매출정보 -->
<section class="sales_title">
    <div class="title clearfix">
        <h3>직접추천정보</h3>
        <div class="boxBtn-group clearfix">
            <a href="tree.php" class="list-btn list-tree"><!-- img src="images/list.png" / --> 리스트형</a>
            <a href="allowance_1.php" class="list-btn list-tree"><!-- img src="images/list.png" / --> 수당체계</a>
            <a href="box_1.php" class="list-btn list-tree"><!-- img src="images/tree_btn.png" alt="" -->관리형</a>
        </div>
    </div>

    <div id="line"></div>

    <div class="partner">
        <div class="boxSearBox">
            <form onsubmit="return false;">
                <button type="button" onclick="searchClick();"><i class="fa fa-search" aria-hidden="true"></i></button>
                <input type="text" placeholder="회원검색" id="searchInput" onkeydown="Enter_Check();"/>
                <div class="search_info">
                    <table id="search_info_tbl">
                        <thead>
                        <tr>
                            <th>이름</th>
                            <th>추천인</th>
                            <th>
                                후원인
                            </th>
                            <th>직급</th>
                            <th>리뉴얼</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>


                <div id="select_box">
                    <label for="nameNick">이름</label>
                    <select id="nameNick">
                        <option value="이름">이름</option>
                        <option value="아이디">회원아이디</option>
                    </select>
                </div>
                <div class="clr"></div>
            </form>
            <div class="clr">
            </div>
        </div>
    </div>
    <div id="line2"></div>
    <!--
    <div class="btn">
        <a href="javascript:;"><img src="images/all_member_bt.gif" alt=""></a>
        <a href="javascript:;"><img src="images/print_bt.gif" alt=""></a>
    </div>
    -->
    <div class="salesTableW">
        <table>
            <thead>
            <tr>
                <th>이름</th>
                <th>회원 ID</th>
                <th>[직급 1팀-2팀-3팀]</th>
                <th>리뉴얼</th>
            </tr>
            </thead>
            <tbody>

            <?php
            $sql = "select b.renewal, a.mb_id, b.mb_name, b.accountRank, b.team1, b.team2 from genealogy as a inner join g5_member as b on a.mb_id = b.mb_id where a.recommenderID = '{$mainID}' and ( b.renewal >= date_add(now(), interval -3 month) or b.renewal is null)";

            $new_result = mysql_query($sql);


            while ($new_row = mysql_fetch_array($new_result)) {
                $re = memberColor(rankCheck($new_row["mb_id"]));

                $new_result2 = mysql_query("SELECT
         mb_id , SUM(VMC+VMR+VMP) AS total
        FROM
            dayPoint
        WHERE
            date >= '{$sYear}-{$sMon}-01'
            AND date < '{$eYear}-{$eMon}-31'
            AND mb_id = '{$new_row['mb_id']}'");
                $total = 0;
                while ($new_row2 = mysql_fetch_array($new_result2)) {
                    if ($new_row2['mb_id'] == $new_row['mb_id']) {
                        $total = $new_row2['total'];
                    }
                }

                $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$new_row['renewal']}', interval +4 month) AS date"));
                $timestamp = $dateCheck_1["date"];
                $timenow = date("Y-m-d");
                $str_now = strtotime($timenow);
                $str_target = strtotime($timestamp);

                if ($str_now > $str_target && $new_row['accountRank'] != "CU" && $new_row['accountRank'] != "VD") {
                    echo "<script>alert('{$new_row['mb_name']}님이 리뉴얼 기간이 지났습니다.');</script>";
                }
                $team3_row = mysql_fetch_row(mysql_query("select count(*) as n from team3_list where mb_id = '{$new_row["mb_id"]}'"));
                if ($member['mb_id'] == 'admin') {
                    echo("
        <tr>
                <td class='name1'><span onclick=\"memberlogin(this);\" style= \"cursor:pointer; background-color: {$re};\">{$new_row['mb_name']}</span></td>
                <td class='id1'>{$new_row['mb_id']}</td>
                <td>" . rankCheck($new_row['mb_id']) . " {$new_row["team1"]}-{$new_row["team2"]}-{$team3_row[0]}</td>
                <td>{$total} ￦</td>
        </tr>");
                } else {
                    echo("
        <tr>
                <td class='name1'><span style= \"background-color: {$re};\">{$new_row['mb_name']}</span></td>
                <td class='id1'>{$new_row['mb_id']}</td>
                <td>" . rankCheck($new_row['mb_id']) . " {$new_row["team1"]}-{$new_row["team2"]}-{$team3_row[0]}</td>
                <td>{$timestamp}</td>
        </tr>");
                }


            }

            ?>


            </tbody>
        </table>
    </div>
</section>
</body>

<!--로딩페이지-->
<div class="loading_wrap">
    <div class="loading_content">
        <img class="loading_img" src="/myOffice/images/vmp_loading_icon.gif" alt="로딩메세지"/>
        <p class="loading_txt">데이터를 조회중입니다. 잠시만 기다려주세요</p>
    </div>
</div>

</html>
<?php
include_once('../shop/shop.tail.php');
?>

<script src="js/main.js"></script>
<script>

    var result = 0;


    $(function () {
        //현재페이지가 초기페이지와같으면
        var uri = '<?=$request_uri?>';
        if (result != 1 && uri == '/myOffice/sales_t.php') {
            start_load();
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

    function start_load() {
        var mb_id = '<?=$member['mb_id']?>';
        $.ajax({
            url: 'get_proc.php',
            type: 'post',
            data: {
                mb_id: mb_id
            },
            datatype: 'json',
            success: function (data) {
                result = data;
                console.log(mb_id);
                console.log(result);

            },
            error: function () {
            }
        })
    }

    function searchClick() {
        if (result != 1 &&  '<?=$request_uri?>' == '/myOffice/sales_t.php') {
            loading();
            //result가 1이될때까지 로딩을보이게끔
            setTimeout("searchClick()", 1000);
            return false;
        }
        if ($("#nameNick option:selected").val() == "아이디") {


            var data = "searchID=" + $('#searchInput').val() + "&loginID=" + $('#loginID').val();

            $.ajax({
                url: './ajax/memberIDSearch.php',
                type: 'POST',
                data: data,
                success: function (result) {
                    if (result == "true") {
                        location.href = '/myOffice/sales_t.php?mb_id=' + $('#searchInput').val();
                    } else {
                        alert("검색할 수 없는 아이디입니다.");
                    }
                }
            });


        }
        else if ($("#nameNick option:selected").val() == "이름") {

            var data = "searchName=" + $('#searchInput').val() + "&loginID=" + $('#loginID').val();

            $.ajax({
                url: './ajax/memberNameSearch.php',
                type: 'POST',
                data: data,
                beforeSend: function () {
                    loading();
                },
                success: function (result) {
                    $('.loading_wrap').removeClass('on');

                    var json = JSON.parse(result);

                    console.log(json);
                    var submenu = $(".search_info");
                    if (submenu.is(":visible")) {
                        $('#search_info_tbl > tbody:last > tr').remove();
                    }

                    if (json.length == 0) {
                        alert("검색 결과가 없습니다.");
                    }

                    var submenu = $(".search_info");
                    submenu.show();
//                       var submenu = $(".search_info");
//                       submenu.slideDown();
                    for (var i = 0; i < json.length; i++) {
                        $('#search_info_tbl > tbody:last').append('<tr id=\"' + json[i][5] + '\" onclick=\"searchSelect(this.id);\"><td>' + json[i][0] + '</td><td>' + json[i][1] + '</td><td>' + json[i][2] + '</td><td>' + json[i][3] + '</td><td>'+ json[i][4] +'</td></a></tr>');
                    }
                }
            });
        }
    }


    function Enter_Check() { // 회원검색에서 엔터 키 입력하면 검색 진행하게 하는 함수
        if (event.keyCode == 13) {
            searchClick();
        }
    }

    function searchSelect(id) {
        window.location.href = '/myOffice/sales_t.php?mb_id=' + id;
    }


    function memberlogin(val) {
        var mb_id = $(val).closest('tr').find(".id1").text();

        window.location.href = "../bbs/login_check.php?mb_id=" + mb_id;

    }

</script>
