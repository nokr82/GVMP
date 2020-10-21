<?php
include './inc/title.php';
include_once('./_common.php');
include_once('./dbConn.php');

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
?>
<?php
$result = mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'");
$row = mysql_fetch_array($result);
if (!($row['accountType'] == 'VM') && !($row['mb_id'] == 'admin')) {
    echo alert('VM회원만 접근 권한이 있습니다.');
    $prevPage = $_SERVER['HTTP_REFERER'];
    header('location:' . $prevPage);
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

if ($row2['accountType'] == 'VM' && ($now_temp > $timestamp_temp && $now_temp <= $timestamp2_temp)) {
    alert($timestamp2.'이 되면 CU 계정으로 변경되오니 리뉴얼을 진행해 주시기 바랍니다.');
//    echo "<script>alert('{$timestamp2}이 되면 CU 계정으로 변경되오니 리뉴얼을 진행해 주시기 바랍니다.');</script>";
//    $prevPage = $_SERVER['HTTP_REFERER'];
//    header('location:' . $prevPage);
}
?>
<link rel="shortcut icon" href="/img/vmp_logo.ico"/>
<link rel="stylesheet" href="css/tree.css">
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="js/tree.js"></script>

</head>
<body>
<!-- header -->
<?php
include_once('../shop/shop.head.php');
?>

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


<input type="hidden" id="loginID" value="<?= $member['mb_id'] ?>">

<!-- 산하 회원정보 - 트리형 -->
<section class="tree">
    <div class="title clearfix">
        <h2>산하 회원정보 - 리스트형(전체)</h2>
        <div class="boxBtn-group clearfix">

            <form type="hidden" name="exel_down">
                <input type="hidden" name="mb_id" value="<?= $member['mb_id'] ?>"/>
            </form>
            <a href="javascript:void(0)" onclick="excel_down()" class="list-btn list-tree"
               style="background: #34BF8C;display: inline-block"><!-- img src="images/list.png" / --> 엑셀 다운</a>

            <a href="tree_1_load.php" class="list-btn list-part"><i class="fa fa-user" aria-hidden="true"></i> 팀별보기</a>
            <a href="box_1.php" class="list-btn list-tree"><!-- img src="images/tree_btn.png" alt="" -->관리형</a>
            <a href="sales_1.php" class="list-btn list-tree"><!-- img src="images/list.png" / --> 직접추천정보</a>
            <a href="allowance_1.php" class="list-btn list-tree"><!-- img src="images/list.png" / --> 수당체계</a>


        </div>

    </div>
    <div id="line"></div>
    <div class="partner">
        <h3><strong><?= $member['mb_name'] ?></strong>님 파트너 목록</h3>
        <div class="boxSearBox clearfix">
            <form onsubmit="return false;" class="clearfix">

                <div class="search_info">
                    <table id="search_info_tbl">
                        <thead>
                        <tr>
                            <th>이름</th>
                            <th>추천인</th>
                            <th>후원인</th>
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
                        <option value="이름" selected="selected">이름</option>
                        <option value="아이디">회원아이디</option>
                    </select>
                </div>

                <div class="user-search clearfix">
                    <input type="text" placeholder="회원검색" id="searchInput" onkeydown="Enter_Check();"/>
                    <button type="button" onclick="searchClick();" id="searchclick"><i class="fa fa-search"
                                                                                       aria-hidden="true"></i></button>
                </div>

                <div class="clr"></div>
            </form>
            <div class="clr"></div>
        </div>
    </div>
    <div class="treeTableW">
        <div class="list">
            <ul>
                <li>이름</li>
                <li>추천인</li>
                <li>휴대폰</li>
                <li>이메일</li>
                <li style="margin-left:0.7%;">직급</li>
                <li>리뉴얼</li>
            </ul>
        </div>

        <?php

        mysql_query("set session character_set_connection=utf8");
        mysql_query("set session character_set_results=utf8");
        mysql_query("set session character_set_client=utf8");

        $result = mysql_query("select * from teamMembers where mb_id = '{$mainID}'");
        $row = mysql_fetch_array($result);


        if ($row['1T_name'] != null) {
            $result2 = mysql_query("select * from genealogy where mb_id = '{$row['1T_ID']}'");
            $row2 = mysql_fetch_array($result2);

            $result3 = mysql_query("select * from g5_member where mb_id = '{$row['1T_ID']}'");
            $row3 = mysql_fetch_array($result3);

            $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row3['renewal']}', interval +4 month) AS date"));
            $timestamp = $dateCheck_1["date"];

            echo "<div style=\"cursor: pointer;\" onclick=\"treeView(this.id, 2);\" class=\"tree_table\" id=\"{$row['1T_ID']}\">
                      <div class=\"tree_number_circle\">1</div>
                    <ul>
                        <li>{$row['1T_name']}({$row3['mb_id']})</li>
                        <li>{$row2['recommenderName']}</li>
                        <li>{$row3['mb_hp']}</li>
                        <li>{$row3['mb_email']}</li>
                        <li>{$row3['accountRank']}</li>
                        ";
            if ($timestamp < date("Y-m-d")) {
                echo "<li style='color: red'>{$timestamp}</li>";
            } else {
                echo "<li>{$timestamp}</li>";
            }
            echo "
                        <li><img id=\"{$row['1T_ID']}_btn\" src=\"images/more.btn3.png\" alt=\"\"></li>
                        </ul>
                        </div>";
        }
        if ($row['2T_name'] != null) {
            $result2 = mysql_query("select * from genealogy where mb_id = '{$row['2T_ID']}'");
            $row2 = mysql_fetch_array($result2);

            $result3 = mysql_query("select * from g5_member where mb_id = '{$row['2T_ID']}'");
            $row3 = mysql_fetch_array($result3);

            $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row3['renewal']}', interval +4 month) AS date"));
            $timestamp = $dateCheck_1["date"];

            echo "<div onclick=\"treeView(this.id, 2)\" class=\"tree_table\" id=\"{$row['2T_ID']}\">
                      <div class=\"tree_number_circle\">1</div>
                    <ul>
                        <li>{$row['2T_name']}({$row3['mb_id']})</li>
                        <li>{$row2['recommenderName']}</li>
                        <li>{$row3['mb_hp']}</li>
                        <li>{$row3['mb_email']}</li>
                        <li>{$row3['accountRank']}</li>";

            if ($timestamp < date("Y-m-d")) {
                echo "<li style='color: red'>{$timestamp}</li>";
            } else {
                echo "<li>{$timestamp}</li>";
            }
            echo "<li><img id=\"{$row['2T_ID']}_btn\" src=\"images/more.btn3.png\" alt=\"\"></li>
                        </ul>
                        </div>";
        }


        ?>


    </div><!--treeTableW-->
</section>
<!--로딩페이지-->
<div class="loading_wrap">
    <div class="loading_content">
        <img class="loading_img" src="/myOffice/images/vmp_loading_icon.gif" alt="로딩메세지"/>
        <p class="loading_txt">엑셀을 다운로드중 입니다.</p>
    </div>
</div>
</body>
</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>
<script src="js/main.js"></script>
<script>

    function getCookie() {
        var parts = document.cookie.split('fileDownloadToken' + "=");
        if (parts.length == 2) return parts.pop().split(";").shift();
    }

    var downloadTimer;
    var deleteCookie = function(name) {
        document.cookie = name + '=; expires=Thu, 01 Jan 1999 00:00:10 GMT;';
    }
    function excel_down() {
        deleteCookie('fileDownloadToken');
        $(".loading_wrap").addClass('on');
        var f = document.exel_down;
        f.action = "./tree_exel1.php";
        f.method = "post";
        f.submit();
        downloadTimer = setInterval(function() {
            var token = getCookie();
            if(token == "TRUE") {
                $(".loading_wrap").removeClass('on');
                clearInterval(downloadTimer);
            }
        }, 1000 );
    };

    function searchClick() {
        if ($("#nameNick option:selected").val() == "아이디") {
            var data = "searchID=" + $('#searchInput').val() + "&loginID=" + $('#loginID').val();

            $.ajax({
                url: './memberIDSearch.php',
                type: 'POST',
                data: data,
                success: function (result) {
                    if (result == "true") {
                        location.href = '/myOffice/tree.php?mb_id=' + $('#searchInput').val();
                    } else {
                        alert("검색할 수 없는 아이디입니다.");
                    }
                }
            });


        } else if ($("#nameNick option:selected").val() == "이름") {

            var data = "searchName=" + $('#searchInput').val() + "&loginID=" + $('#loginID').val();

            $.ajax({
                url: './memberNameSearch.php',
                type: 'POST',
                data: data,
                success: function (result) {

                    var json = JSON.parse(result);

                    var submenu = $(".search_info");
                    if (submenu.is(":visible")) {
                        $('#search_info_tbl > tbody:last > tr').remove();
                    }

                    if (json.length == 0) {
                        alert("검색 결과가 없습니다.");
                        exit();
                    }

                    var submenu = $(".search_info");
                    submenu.show();
//                       var submenu = $(".search_info");
//                       submenu.slideDown();

                    for (var i = 0; i < json.length; i++) {
                        $('#search_info_tbl > tbody:last').append('<tr id=\"' + json[i][1] + '\" onclick=\"searchSelect(this.id);\"><td>' + json[i][0] + '</td><td>' + json[i][2] + '</td><td>' + json[i][5] + '</td><td>' + json[i][3] + '</td><td>' + json[i][4] + '</td></a></tr>');
                    }
                }
            });


//                var submenu = $(".search_info");
//
//                if( submenu.is(":visible") ){
//                    submenu.slideUp();
//                }else{
//                    submenu.slideDown();
//                }
        }
    }

    function Enter_Check() { // 회원검색에서 엔터 키 입력하면 검색 진행하게 하는 함수
        if (event.keyCode == 13) {
            searchClick();
        }
    }

    function searchSelect(id) {
        window.location.href = '/myOffice/tree.php?mb_id=' + id;
    }
</script>
<script>
    // 트리에서 클릭한 회원 하위 1~3팀 직원들 정보 긁어와서 뿌려주는 함수

    function treeView(id, n) {
        var data = "id=" + id;

        // 몇 촌인지에 따른 백그라운드 색상 지정
        var color = "";
        switch (n) {
            case 1:
                color = "#FF0000";
                break;
            case 2:
                color = "#FF5E00";
                break;
            case 3:
                color = "#FFBB00";
                break;
            case 4:
                color = "#FFE400";
                break;
            case 5:
                color = "#ABF200";
                break;
            case 6:
                color = "#1DDB16";
                break;
            case 7:
                color = "#00D8FF";
                break;
            case 8:
                color = "#0054FF";
                break;
            case 9:
                color = "#5F00FF";
                break;
            case 10:
                color = "#FF00DD";
                break;
            case 11:
                color = "#FF007F";
                break;
            case 12:
                color = "#980000";
                break;
            case 13:
                color = "#993800";
                break;
            case 14:
                color = "#997000";
                break;
            case 15:
                color = "#998A00";
                break;
            case 16:
                color = "#6B9900";
                break;
            case 17:
                color = "#2F9D27";
                break;
            case 18:
                color = "#008299";
                break;
            case 19:
                color = "#003399";
                break;
            case 20:
                color = "#990085";
                break;
            case 21:
                color = "#4C4C4C";
                break;
            case 22:
                color = "#660033";
                break;
            case 23:
                color = "#660058";
                break;
            case 24:
                color = "#2A0066";
                break;
            case 25:
                color = "#005766";
                break;
            case 26:
                color = "#22741C";
                break;
            case 27:
                color = "#476600";
                break;
            case 28:
                color = "#665C00";
                break;
            case 29:
                color = "#664B00";
                break;
            case 30:
                color = "#662500";
                break;

        }

        if ($('#' + id + '_btn').attr('src') == "images/more.btn3.png") {
            $.ajax({
                url: './treeInfo.php',
                type: 'POST',
                data: data,
                success: function (result) {
                    var json = JSON.parse(result);

                    // 1팀 정보
                    // json.team1_mb_id <-- 회원 ID
                    // json.team1_mb_name <-- 회원 이름
                    // json.team1_recommenderName <-- 추천인 이름
                    // json.team1_mb_hp <-- 회원 연락처
                    // json.team1_mb_email <-- 회원 이메일

                    // 2팀 정보
                    // json.team2_mb_id <-- 회원 ID
                    // json.team2_mb_name <-- 회원 이름
                    // json.team2_recommenderName <-- 추천인 이름
                    // json.team2_mb_hp <-- 회원 연락처
                    // json.team2_mb_email <-- 회원 이메일

                    // 3팀 정보
                    // json.team3_mb_id <-- 회원 ID
                    // json.team3_mb_name <-- 회원 이름
                    // json.team3_recommenderName <-- 추천인 이름
                    // json.team3_mb_hp <-- 회원 연락처
                    // json.team3_mb_email <-- 회원 이메일

                    var today = new Date();
                    var real_day =
                        leadingZeros(today.getFullYear(), 4) + '-' +
                        leadingZeros(today.getMonth() + 1, 2) + '-' +
                        leadingZeros(today.getDate(), 2);

                    $('#' + id).after("<div id=\"" + id + "_area\" style=\"display:block\">");
                    if (json.team1_mb_id != null) {
                        var timestamp = "";
                        if (json.team1_renewal < real_day) {
                            timestamp = "<li style='color: red'>" + json.team1_renewal + "</li>"
                        } else {
                            timestamp = "<li>" + json.team1_renewal + "</li>"
                        }
                        $('#' + id + '_area').append("<div onclick=\"treeView(this.id, " + (n + 1) + ")\" class=\"tree_table\" id=\"" + json.team1_mb_id + "\"><div style=\"background-color:" + color + ";\" class=\"tree_number_circle\">" + n + "</div><ul><li>" + json.team1_mb_name + "(" + json.team1_mb_id + ")" + "</li><li>" + json.team1_recommenderName + "</li><li>" + json.team1_mb_hp + "</li><li>" + json.team1_mb_email + "</li><li>" + json.team1_accountRank + "</li>" +
                            timestamp +
                            "<li><img id=\"" + json.team1_mb_id + "_btn\" src=\"images/more.btn3.png\" alt=\"\"></li></ul></div>");
                    }

                    if (json.team2_mb_id != null) {
                        var timestamp2 = "";
                        if (json.team2_renewal < real_day) {
                            timestamp2 = "<li style='color: red'>" + json.team2_renewal + "</li>"
                        } else {
                            timestamp2 = "<li>" + json.team2_renewal + "</li>"
                        }
                        $('#' + id + '_area').append("<div onclick=\"treeView(this.id, " + (n + 1) + ")\" class=\"tree_table\" id=\"" + json.team2_mb_id + "\"><div style=\"background-color:" + color + ";\" class=\"tree_number_circle\">" + n + "</div><ul><li>" + json.team2_mb_name + "(" + json.team2_mb_id + ")" + "</li><li>" + json.team2_recommenderName + "</li><li>" + json.team2_mb_hp + "</li><li>" + json.team2_mb_email + "</li><li>" + json.team2_accountRank + "</li>" +
                            timestamp2 +
                            "<li><img id=\"" + json.team2_mb_id + "_btn\" src=\"images/more.btn3.png\" alt=\"\"></li></ul></div>");
                    }

//							if( json.team3_mb_id != null ) {
//								$('#' + id + '_area').append("<div onclick=\"treeView(this.id, "+(n+1)+")\" class=\"tree_table\" id=\""+json.team3_mb_id+"\"><div style=\"background-color:"+color+";\" class=\"tree_number_circle\">"+n+"</div><ul><li>"+json.team3_mb_name+"</li><li>"+json.team3_recommenderName+"</li><li>"+json.team3_mb_hp+"</li><li>"+json.team3_mb_email+"</li><li>"+json.team3_accountRank+"</li><li>2018.02.22</li><li><img id=\""+json.team3_mb_id+"_btn\" src=\"images/more.btn3.png\" alt=\"\"></li></ul></div>");
//							}


                    $('#' + id + "_btn").attr("src", "images/more.btn4.png");


                }
            });
        } else {
            $('#' + id + '_area').remove();
            $('#' + id + "_btn").attr("src", "images/more.btn3.png");
        }


    }

    function leadingZeros(n, digits) {
        var zero = '';
        n = n.toString();

        if (n.length < digits) {
            for (i = 0; i < digits - n.length; i++)
                zero += '0';
        }
        return zero + n;
    }


</script>


<?php
include_once('../shop/shop.tail.php');
?>

<?php
mysql_close($connect);
?>
