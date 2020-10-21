<?php
include_once('./inc/title.php');
include_once('./dbConn.php');
include_once('./_common.php');
include_once('./getMemberInfo.php');
include_once('./OrganizationChartInfo.php');
?>
<link rel="stylesheet" href="./css/box1122.css">

</head>
<!-- header -->
<?php
include_once('../shop/shop.head.php');

// 최하단 알아내는 함수
function lower($id)
{
    $row = mysql_fetch_array(mysql_query("select * from teamMembers where mb_id = '{$id}'"));
    $re;
    if ($row['1T_ID'] != "")
        $re = lower($row['1T_ID']);
    else
        return $id;

    return $re;
}

$mainID = "";
$teamCountUser = '';
if ($member['mb_id'] == 'admin') {
    $teamCountUser = '00000001';
} else {
    $teamCountUser = $member['mb_id'];
}
if (isset($_GET['mb_id']) && !isset($_GET['boxCheck'])) {
    teamCount($teamCountUser, false);


    $check = 0;
    foreach ($mb_info_1T as $value) {
        if ($value[1] == $_GET['mb_id'])
            $check++;
    }
    foreach ($mb_info_2T as $value) {
        if ($value[1] == $_GET['mb_id'])
            $check++;
    }

    if ($_GET['mb_id'] == $member['mb_id'])
        $check++;


    if ($check != 0) {
        $mainID = $_GET['mb_id'];
    } else {
        if ($_GET['mb_id'] == "00003655") {
            $mainID = $_GET['mb_id'];
        } else {
            alert("검색할 수 없는 회원입니다." . $check);
            $prevPage = $_SERVER['HTTP_REFERER'];
            header('location:' . $prevPage);
        }
    }

    foreach ($mb_info_1T as $value) {
        $key = array_search($mb_info_1T, $value);
        array_splice($mb_info_1T, $key, 1);
    }
    foreach ($mb_info_2T as $value) {
        $key = array_search($mb_info_2T, $value);
        array_splice($mb_info_2T, $key, 1);
    }
} else {
    if ($member['mb_id'] == 'admin') {
        $mainID = '00000001';
    } else {
        $mainID = $member['mb_id'];
    }
    if (isset($_GET["boxCheck"])) {
        $mainID = $_GET["mb_id"];
    }
}

// 1팀, 2팀 최하단이 누구인지 변수에 담아두기
$lowerR = mysql_fetch_array(mysql_query("select * from teamMembers where mb_id = '{$mainID}'"));
$lower1T_ID = lower($lowerR['1T_ID']);
$lower2T_ID = lower($lowerR['2T_ID']);

?>

<?php
$result = mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'");
$row = mysql_fetch_array($result);
if (!($row['accountType'] == 'VM') && !($row['mb_id'] == 'admin')) {
    echo alert('VM회원만 접근 권한이 있습니다.', '/myOffice/index.php');
}
$modi_auth = false;
if ($member['accountRank'] == '5 STAR' || $member['accountRank'] == 'AMBASSADOR' || $member['accountRank'] == 'Crown AMBASSADOR'
    || $member['accountRank'] == 'Double AMBASSADOR' || $member['accountRank'] == 'Royal Crown AMBASSADOR'
    || $member['accountRank'] == 'Triple AMBASSADOR'
    || $member['mb_id'] == "00003656" || $member['mb_id'] == "00000182" || $member['mb_id'] == "00000163"
    || $member['mb_id'] == "00031544" || $member['mb_id'] == "00003696" || $member['mb_id'] == "00003710"
    || $member['mb_id'] == "00003686" || $member['mb_id'] == "00003943" || $member['mb_id'] == "00003571"
    || $member['mb_id'] == "00003697" || $member['mb_id'] == "00003879"  || $member['mb_id'] == "00003660"
    || $member['mb_id'] == "00003880"|| $member['mb_id'] == "00066958"|| $member['mb_id'] == "00447591"
    || $member['mb_id'] == "00447972"|| $member['mb_id'] == "00447976"||$member['mb_id'] == '00003967'||$member['mb_id'] == '00104175'||$member['mb_id'] == '00104204'
    || $member['mb_id']=='00019492') {
    $modi_auth = true;
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
    alert($timestamp2 . '이 되면 CU 계정으로 변경되오니 리뉴얼을 진행해 주시기 바랍니다.', 'index_start.php');
//    echo "<script>alert('{$timestamp2}이 되면 CU 계정으로 변경되오니 리뉴얼을 진행해 주시기 바랍니다.');</script>";
//    $prevPage = $_SERVER['HTTP_REFERER'];
//    header('location:' . $prevPage);
}

$TopIDWhere;

if (isset($_GET['mb_id'])) {
    $TopIDWhere = $_GET['mb_id'];
} else {
    if ($member['mb_id'] != 'admin')
        $TopIDWhere = $member['mb_id'];
    else {
        $TopIDWhere = '00000001';
    }
}

$resultTopID = mysql_query("select * from genealogy where mb_id = '{$TopIDWhere}'");
$rowTopID = mysql_fetch_array($resultTopID);

$TopID;
if ($rowTopID['sponsorID'] == "00000000") {
    $TopID = "#";
} else if ($mainID == $member["mb_id"]) {
    $TopID = "#";
} else {
    $TopID = "/myOffice/box_1.php?mb_id=" . $rowTopID['sponsorID'] . "&boxCheck=true";
}
?>
<body>

<input type="hidden" id="loginID" value="<?= $member['mb_id'] ?>">

<!-- 컨텐츠 -->
<section class="boxSection clearfix">
    <h3>관리형 </h3>

    <div class="boxBtn-group clearfix">
        <a href="sales_1.php" class="list-btn list-tree"><!-- img src="images/list.png" / --> 직접추천정보</a>
        <a href="tree.php" class="list-btn list-tree"><!-- img src="images/list.png" / --> 리스트형</a>
        <a href="allowance_1.php" class="list-btn list-tree"><!-- img src="images/list.png" / --> 수당체계</a>
    </div>
    <div class="boxSearBox">
        <a href="/myOffice/box_1.php" class="boxBtn boxTree_btn boxTree_btn0-1">
            <!-- img src="images/my_member_bt.gif"/ -->나의 조직도</a>
        <a id="boxPrinBt" class="boxBtn boxTree_btn boxTree_btn0-2" href="#" onclick="javascript:content_print();">
            <!-- img src="images/print_bt.gif" / --> 조직도 인쇄</a>
        <!-- 버튼 추가 -->
        <a href="<?= $TopID ?>" class="boxTree_btn boxTree_btn1">위로가기</a>

        <?php
        if ($lower1T_ID != "") {
            echo "<a href=\"/myOffice/box_1.php?mb_id={$lower1T_ID}&boxCheck=true#\" class=\"boxTree_btn boxTree_btn2\">1팀 최하단</a>";
        } else {
            echo "<a href=\"#\" class=\"boxTree_btn boxTree_btn2\">1팀 최하단</a>";
        }

        if ($lower2T_ID != "") {
            echo "<a href=\"/myOffice/box_1.php?mb_id={$lower2T_ID}&boxCheck=true#\" class=\"boxTree_btn boxTree_btn3\">2팀 최하단</a>";
        } else {
            echo "<a href=\"#\" class=\"boxTree_btn boxTree_btn2\">2팀 최하단</a>";
        }
        ?>

        <span class="boxSearBoxHiddenSpan"><br/></span>

        <form onsubmit="return false;" class="form-search clearfix">
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
            <input type="text" placeholder="회원검색" id="searchInput" onkeydown="Enter_Check();"/>
            <button type="button" onclick="searchClick();" id="searchclick"><i class="fa fa-search"
                                                                               aria-hidden="true"></i></button>
        </form>

        <div class="clr">
        </div>

    </div><!--boxSearBox-->

    <div id="printArea" class="boxContentW">


        <div class="boxContent" style="left:-13%;" id="draggable">
            <!--        <ul class="preview">
                                <li><a href="#"><img src="images/previous.png" alt="" onclick="boxBack();"></a></li>
                                <li><a href="#"><img src="images/replay.png" alt="" onclick="selectView('<?= $member['mb_id'] ?>')";></a></li>
                        </ul>-->

            <?php
            $result = mysql_query("SELECT A.mb_id, A.renewal, A.accountRank, A.mb_name,A.modi_count, A.mb_open_date, B.recommenderName, A.team1, A.team2 FROM g5_member AS A INNER JOIN genealogy AS B ON A.mb_id = B.mb_id where A.mb_id = '{$mainID}'");
            $row = mysql_fetch_array($result);

            $result2 = mysql_query("select count(*) as team3 from team3_list where mb_id = '{$mainID}'");
            $row2 = mysql_fetch_array($result2);


            $infoRe = selectFunc($mainID, 1,$member['mb_id']);

            $infoRe2 = selectFunc($infoRe[0], 2,$member['mb_id']);
            $infoRe3 = selectFunc($infoRe[1], 3,$member['mb_id']);

            $infoRe4 = selectFunc($infoRe2[0], 4,$member['mb_id']);
            $infoRe5 = selectFunc($infoRe2[1], 5,$member['mb_id']);
            $infoRe6 = selectFunc($infoRe3[0], 6,$member['mb_id']);
            $infoRe7 = selectFunc($infoRe3[1], 7,$member['mb_id']);

            $infoRe8 = selectFunc($infoRe4[0], 8,$member['mb_id']);
            $infoRe9 = selectFunc($infoRe4[1], 9,$member['mb_id']);
            $infoRe10 = selectFunc($infoRe5[0], 10,$member['mb_id']);
            $infoRe11 = selectFunc($infoRe5[1], 11,$member['mb_id']);

            $infoRe12 = selectFunc($infoRe6[0], 12,$member['mb_id']);
            $infoRe13 = selectFunc($infoRe6[1], 13,$member['mb_id']);
            $infoRe14 = selectFunc($infoRe7[0], 14,$member['mb_id']);
            $infoRe15 = selectFunc($infoRe7[1], 15,$member['mb_id']);


            $mainColor = memberColor($row['accountRank']);
            $fontColor = "white";
            if ($mainID == "00000001") {
                $row['accountRank'] = "대표사업자";
                $mainColor = "black";
                $fontColor = "goldenrod";
            }
            $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row['renewal']}', interval +4 month) AS date"));
            $timestamp = $dateCheck_1["date"];

            $timenow = date("Y-m-d");
            $str_now = strtotime($timenow);
            $dateCheck_1_1 = mysql_fetch_array(mysql_query("SELECT date_add(date_add('{$row['renewal']}', interval +4 month), interval +1 day) AS date"));
            $timestamp3 = $dateCheck_1_1["date"];
            $str_timestamp3 = strtotime($timestamp3);

            if ($row['accountRank'] == "CU")
                $mainColor = "#f76e5e";

            echo '<div class="boxDepth0"><div class="boxLevel0 boxNone"><a style="color: ' . $fontColor . ';background-color:' . $mainColor . ';" href="" onclick="return false;" style="text-decoration:none; cursor: default;"><div class="img_element">';

            $renewalColor = "";
            if ($str_now >= $str_timestamp3) {
                $renewalColor = "red";
            }

            $imagePath = imagePath(rankCheck($row["mb_id"]));

            if ($mainID == '00000001') {
                $imagePath = "./images/VMP DELEGATE.png";
            }
            echo "<img src=\"{$imagePath}\" onerror=\"this.style.display='none'\" ></div><p>이름 : {$row['mb_name']}<br>ID : {$row['mb_id']}<br />가입 : {$row['mb_open_date']}<br /><font style=\"color: {$renewalColor};\">리뉴얼 : {$timestamp}</font><br />인원 : {$row["team1"]}/{$row["team2"]}/{$row2['team3']}<br />추천 : {$row['recommenderName']}<br /></p>"
                . "</a><div class=\"boxLine\"></div></div></div>";
            ?>


            <div class="boxDepth1">

                <?php
                if ($memberInfo['1촌']['1번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['1촌']['1번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }

                    echo "<div class=\"boxLevel1 boxRight\" >"
                        . "<a style=\"background-color: {$memberInfo['1촌']['1번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['1촌']['1번']['아이디']}\">"
                        . "<div class=\"img_element\"><img src=\"{$memberInfo['1촌']['1번']['이미지경로']}\" ></div><p>이름 : {$memberInfo['1촌']['1번']['이름']}<br>ID : {$memberInfo['1촌']['1번']['아이디']}<br>가입 : {$memberInfo['1촌']['1번']['회원가입날짜']}<br />"
                        . "<font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['1촌']['1번']['리뉴얼날짜']}</font><br />인원 : {$memberInfo['1촌']['1번']['1팀인원수']}/{$memberInfo['1촌']['1번']['2팀인원수']}/{$memberInfo['1촌']['1번']['3팀인원수']}<br />추천 : {$memberInfo['1촌']['1번']['추천인']}"
                        . "<br /></p>";
                    if ($memberInfo['1촌']['1번']['수정권한'] == true){
                        if ($modi_auth) {
                            echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['1촌']['1번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['1촌']['1번']['아이디']}')\" >";
                        }
                    }
                    echo "</a><div class=\"boxLine\"></div></div>";
                }
                else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$mainID}&mb_name={$row['mb_name']}&team=1','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                       <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\" alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>

                <div class="boxEmpty hidden1">
                    <span>&nbsp;</span>
                </div>
                <div class="boxEmpty hidden1">
                    <span>&nbsp;</span>
                </div>
                <div class="boxEmpty hidden1">
                    <span>&nbsp;</span>
                </div>
                <div class="boxEmpty hidden1">
                    <span>&nbsp;</span>
                </div>
                <div class="boxEmpty hidden1">
                    <span>&nbsp;</span>
                </div>
                <div class="boxEmpty hidden1">
                    <span>&nbsp;</span>
                </div>
                <div class="boxEmpty hidden1">
                    <span>&nbsp;</span>
                </div>

                <?php
                if ($memberInfo['1촌']['2번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['1촌']['2번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel2 boxLeft\" >
                    <a style=\"background-color: {$memberInfo['1촌']['2번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['1촌']['2번']['아이디']}\">
                    	<div class=\"img_element\">
                            <img src=\"{$memberInfo['1촌']['2번']['이미지경로']}\"onerror=\"this.style.display='none'\" >
                        </div>
                        <p>
                            이름 : {$memberInfo['1촌']['2번']['이름']}<br>
                            ID : {$memberInfo['1촌']['2번']['아이디']}<br>
                            가입 : {$memberInfo['1촌']['2번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['1촌']['2번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['1촌']['2번']['1팀인원수']}/{$memberInfo['1촌']['2번']['2팀인원수']}/{$memberInfo['1촌']['2번']['3팀인원수']}<br />
                            추천 : {$memberInfo['1촌']['2번']['추천인']}<br />
                        </p>";
                if ($memberInfo['1촌']['2번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['1촌']['2번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['1촌']['2번']['아이디']}')\" >";
                    }
                }
                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$mainID}&mb_name={$row['mb_name']}&team=2','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\"onerror=\"this.style.display='none'\" alt=\"\">
</a>
                   
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>


            </div><!--boxDepth1-->


            <div class="boxDepth2">

                <?php
                if ($memberInfo['2촌']['1번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['2촌']['1번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel1 boxRight\">
                    <a style=\"background-color: {$memberInfo['2촌']['1번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['2촌']['1번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['2촌']['1번']['이미지경로']}\" onerror=\"this.style.display='none'\">
                        </div>
                        <p>
                            이름 : {$memberInfo['2촌']['1번']['이름']}<br>
                            ID : {$memberInfo['2촌']['1번']['아이디']}<br>
                            가입 : {$memberInfo['2촌']['1번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['2촌']['1번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['2촌']['1번']['1팀인원수']}/{$memberInfo['2촌']['1번']['2팀인원수']}/{$memberInfo['2촌']['1번']['3팀인원수']}<br />
                            추천 : {$memberInfo['2촌']['1번']['추천인']}<br />
                       </p>";
                if ($memberInfo['2촌']['1번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['2촌']['1번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['2촌']['1번']['아이디']}')\" >";
                    }
                }
                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['1촌']['1번']['아이디']}&mb_name={$memberInfo['1촌']['1번']['이름']}&team=1','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\" alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>


                <div class="boxEmpty hidden1">
                        <span>
                            <div class="img"><img src="" alt="" onerror="this.style.display='none'"/></div>
                            <p>&nbsp;</p>
                        </span>
                </div><!--
                    -->
                <div class="boxEmpty">
                        <span>
                            <div class="img"><img src="" alt="" onerror="this.style.display='none'"/></div>
                            <p>&nbsp;</p>
                        </span>
                </div><!--
                    -->
                <div class="boxEmpty hidden1">
                        <span>
                            <div class="img"><img src="" alt="" onerror="this.style.display='none'"/></div>
                            <p>&nbsp;</p>
                        </span>
                </div>


                <?php
                if ($memberInfo['2촌']['2번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['2촌']['2번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a style=\"background-color: {$memberInfo['2촌']['2번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['2촌']['2번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['2촌']['2번']['이미지경로']}\" onerror=\"this.style.display='none'\">
                        </div>
                        <p>
                            이름 : {$memberInfo['2촌']['2번']['이름']}<br>
                            ID : {$memberInfo['2촌']['2번']['아이디']}<br>
                            가입 : {$memberInfo['2촌']['2번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['2촌']['2번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['2촌']['2번']['1팀인원수']}/{$memberInfo['2촌']['2번']['2팀인원수']}/{$memberInfo['2촌']['2번']['3팀인원수']}<br />
                            추천 : {$memberInfo['2촌']['2번']['추천인']}<br />
                       </p>";
                if ($memberInfo['2촌']['2번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['2촌']['2번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['2촌']['2번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['1촌']['1번']['아이디']}&mb_name={$memberInfo['1촌']['1번']['이름']}&team=2','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\" alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>


                <div class="boxEmpty hidden2">
                        <span>
                            <div class="img"><img src="" alt="" onerror="this.style.display='none'"/></div>
                            <p>&nbsp;</p>
                        </span>
                </div><!--
                    -->
                <div class="boxEmpty hidden2">
                        <span>
                            <div class="img"><img src="" alt="" onerror="this.style.display='none'"/></div>
                            <p>&nbsp;</p>
                        </span>
                </div><!--
                    -->
                <div class="boxEmpty hidden2">
                        <span>
                            <div class="img"><img src="" alt="" onerror="this.style.display='none'"/></div>
                            <p>&nbsp;</p>
                        </span>
                </div>

                <?php
                if ($memberInfo['2촌']['3번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['2촌']['3번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a style=\"background-color: {$memberInfo['2촌']['3번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['2촌']['3번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['2촌']['3번']['이미지경로']}\" onerror=\"this.style.display='none'\">
                        </div>
                        <p>
                            이름 : {$memberInfo['2촌']['3번']['이름']}<br>
                            ID : {$memberInfo['2촌']['3번']['아이디']}<br>
                            가입 : {$memberInfo['2촌']['3번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['2촌']['3번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['2촌']['3번']['1팀인원수']}/{$memberInfo['2촌']['3번']['2팀인원수']}/{$memberInfo['2촌']['3번']['3팀인원수']}<br />
                            추천 : {$memberInfo['2촌']['3번']['추천인']}<br />
                       </p>";
                if ($memberInfo['2촌']['3번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['2촌']['3번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['2촌']['3번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['1촌']['2번']['아이디']}&mb_name={$memberInfo['1촌']['2번']['이름']}&team=1','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\" alt=\"\">
                    </a>
                   
                    <div class=\"boxLine\" onerror=\"this.style.display='none'\"></div>
                </div>";
                }
                ?>

                <div class="boxEmpty hidden1">
                        <span>
                            <div class="img"><img src="" alt="" onerror="this.style.display='none'"/></div>
                            <p>&nbsp;</p>
                        </span>
                </div><!--
                    -->
                <div class="boxEmpty">
                        <span>
                            <div class="img"><img src="" alt="" onerror="this.style.display='none'"/></div>
                            <p>&nbsp;</p>
                        </span>
                </div><!--
                    -->
                <div class="boxEmpty hidden1">
                        <span>
                            <div class="img"><img src="" alt="" onerror="this.style.display='none'"/></div>
                            <p>&nbsp;</p>
                        </span>
                </div>

                <?php
                if ($memberInfo['2촌']['4번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['2촌']['4번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a style=\"background-color: {$memberInfo['2촌']['4번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['2촌']['4번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['2촌']['4번']['이미지경로']}\" onerror=\"this.style.display='none'\">
                        </div>
                        <p>
                            이름 : {$memberInfo['2촌']['4번']['이름']}<br>
                            ID : {$memberInfo['2촌']['4번']['아이디']}<br>
                            가입 : {$memberInfo['2촌']['4번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['2촌']['4번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['2촌']['4번']['1팀인원수']}/{$memberInfo['2촌']['4번']['2팀인원수']}/{$memberInfo['2촌']['4번']['3팀인원수']}<br />
                            추천 : {$memberInfo['2촌']['4번']['추천인']}<br />
                               
                      </p>";
                if ($memberInfo['2촌']['4번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['2촌']['4번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['2촌']['4번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['1촌']['2번']['아이디']}&mb_name={$memberInfo['1촌']['2번']['이름']}&team=1','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\" alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>


            </div><!--boxDepth2-->


            <div class="boxDepth3">


                <?php
                if ($memberInfo['3촌']['1번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['3촌']['1번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a style=\"background-color: {$memberInfo['3촌']['1번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['3촌']['1번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['3촌']['1번']['이미지경로']}\" onerror=\"this.style.display='none'\">
                        </div>
                        <p>
                            이름 : {$memberInfo['3촌']['1번']['이름']}<br>
                            ID : {$memberInfo['3촌']['1번']['아이디']}<br>
                            가입 : {$memberInfo['3촌']['1번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['3촌']['1번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['3촌']['1번']['1팀인원수']}/{$memberInfo['3촌']['1번']['2팀인원수']}/{$memberInfo['3촌']['1번']['3팀인원수']}<br />
                            추천 : {$memberInfo['3촌']['1번']['추천인']}<br />
                     </p>";
                if ($memberInfo['3촌']['1번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['3촌']['1번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['3촌']['1번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['2촌']['1번']['아이디']}&mb_name={$memberInfo['2촌']['1번']['이름']}&team=1','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\"onerror=\"this.style.display='none'\" alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>


                <div class="boxEmpty">
                        <span>
                            <div class="img"><img src="" alt="" onerror="this.style.display='none'"/></div>
                            <p>&nbsp;</p>
                        </span>
                </div>


                <?php
                if ($memberInfo['3촌']['2번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['3촌']['2번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a style=\"background-color: {$memberInfo['3촌']['2번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['3촌']['2번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['3촌']['2번']['이미지경로']}\" onerror=\"this.style.display='none'\" >
                        </div>
                        <p>
                            이름 : {$memberInfo['3촌']['2번']['이름']}<br>
                            ID : {$memberInfo['3촌']['2번']['아이디']}<br>
                            가입 : {$memberInfo['3촌']['2번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['3촌']['2번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['3촌']['2번']['1팀인원수']}/{$memberInfo['3촌']['2번']['2팀인원수']}/{$memberInfo['3촌']['2번']['3팀인원수']}<br />
                            추천 : {$memberInfo['3촌']['2번']['추천인']}<br />
                        </p>";
                if ($memberInfo['3촌']['2번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['3촌']['2번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['3촌']['2번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['2촌']['1번']['아이디']}&mb_name={$memberInfo['2촌']['1번']['이름']}&team=2','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\"alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>


                <div class="boxEmpty hidden2">
                        <span>
                            <div class="img"><img src="" alt="" onerror="this.style.display='none'"/></div>
                            <p>&nbsp;</p>
                        </span>
                </div>


                <?php
                if ($memberInfo['3촌']['3번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['3촌']['3번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a style=\"background-color: {$memberInfo['3촌']['3번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['3촌']['3번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['3촌']['3번']['이미지경로']}\" onerror=\"this.style.display='none'\" >
                        </div>
                        <p>
                            이름 : {$memberInfo['3촌']['3번']['이름']}<br>
                            ID : {$memberInfo['3촌']['3번']['아이디']}<br>
                            가입 : {$memberInfo['3촌']['3번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['3촌']['3번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['3촌']['3번']['1팀인원수']}/{$memberInfo['3촌']['3번']['2팀인원수']}/{$memberInfo['3촌']['3번']['3팀인원수']}<br />
                            추천 : {$memberInfo['3촌']['3번']['추천인']}<br />
                       </p>";
                if ($memberInfo['3촌']['3번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['3촌']['3번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['3촌']['3번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['2촌']['2번']['아이디']}&mb_name={$memberInfo['2촌']['2번']['이름']}&team=1','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\"onerror=\"this.style.display='none'\" alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>


                <div class="boxEmpty">
                        <span>
                            <div class="img"><img src="" alt="" onerror="this.style.display='none'"/></div>
                            <p>&nbsp;</p>
                        </span>
                </div>

                <?php
                if ($memberInfo['3촌']['4번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['3촌']['4번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a style=\"background-color: {$memberInfo['3촌']['4번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['3촌']['4번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['3촌']['4번']['이미지경로']}\" onerror=\"this.style.display='none'\" >
                        </div>
                        <p>
                            이름 : {$memberInfo['3촌']['4번']['이름']}<br>
                            ID : {$memberInfo['3촌']['4번']['아이디']}<br>
                            가입 : {$memberInfo['3촌']['4번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['3촌']['4번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['3촌']['4번']['1팀인원수']}/{$memberInfo['3촌']['4번']['2팀인원수']}/{$memberInfo['3촌']['4번']['3팀인원수']}<br />
                            추천 : {$memberInfo['3촌']['4번']['추천인']}<br />
                      </p>";
                if ($memberInfo['3촌']['4번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['3촌']['4번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['3촌']['4번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['2촌']['2번']['아이디']}&mb_name={$memberInfo['2촌']['2번']['이름']}&team=2','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\" alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>


                <div class="boxEmpty hidden2">
                        <span>
                            <div class="img"><img src="" alt="" onerror="this.style.display='none'"/></div>
                            <p>&nbsp;</p>
                        </span>
                </div>

                <?php
                if ($memberInfo['3촌']['5번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['3촌']['5번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a style=\"background-color: {$memberInfo['3촌']['5번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['3촌']['5번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['3촌']['5번']['이미지경로']}\" onerror=\"this.style.display='none'\" >
                        </div>
                        <p>
                            이름 : {$memberInfo['3촌']['5번']['이름']}<br>
                            ID : {$memberInfo['3촌']['5번']['아이디']}<br>
                            가입 : {$memberInfo['3촌']['5번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['3촌']['5번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['3촌']['5번']['1팀인원수']}/{$memberInfo['3촌']['5번']['2팀인원수']}/{$memberInfo['3촌']['5번']['3팀인원수']}<br />
                            추천 : {$memberInfo['3촌']['5번']['추천인']}<br />
              </p>";
                if ($memberInfo['3촌']['5번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['3촌']['5번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['3촌']['5번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['2촌']['3번']['아이디']}&mb_name={$memberInfo['2촌']['3번']['이름']}&team=1','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\"alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>


                <div class="boxEmpty">
                        <span>
                            <div class="img"><img src="" alt="" onerror="this.style.display='none'"/></div>
                            <p>&nbsp;</p>
                        </span>
                </div>

                <?php
                if ($memberInfo['3촌']['6번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['3촌']['6번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a style=\"background-color: {$memberInfo['3촌']['6번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['3촌']['6번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['3촌']['6번']['이미지경로']}\" onerror=\"this.style.display='none'\" >
                        </div>
                        <p>
                            이름 : {$memberInfo['3촌']['6번']['이름']}<br>
                            ID : {$memberInfo['3촌']['6번']['아이디']}<br>
                            가입 : {$memberInfo['3촌']['6번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['3촌']['6번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['3촌']['6번']['1팀인원수']}/{$memberInfo['3촌']['6번']['2팀인원수']}/{$memberInfo['3촌']['6번']['3팀인원수']}<br />
                            추천 : {$memberInfo['3촌']['6번']['추천인']}<br />
                   </p>";
                if ($memberInfo['3촌']['6번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['3촌']['6번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['3촌']['6번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['2촌']['3번']['아이디']}&mb_name={$memberInfo['2촌']['3번']['이름']}&team=2','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\" alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>


                <div class="boxEmpty hidden2">
                        <span>
                            <div class="img"><img src="" alt="" onerror="this.style.display='none'"/></div>
                            <p>&nbsp;</p>
                        </span>
                </div>

                <?php
                if ($memberInfo['3촌']['7번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['3촌']['7번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a style=\"background-color: {$memberInfo['3촌']['7번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['3촌']['7번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['3촌']['7번']['이미지경로']}\" onerror=\"this.style.display='none'\">
                        </div>
                        <p>
                            이름 : {$memberInfo['3촌']['7번']['이름']}<br>
                            ID : {$memberInfo['3촌']['7번']['아이디']}<br>
                            가입 : {$memberInfo['3촌']['7번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['3촌']['7번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['3촌']['7번']['1팀인원수']}/{$memberInfo['3촌']['7번']['2팀인원수']}/{$memberInfo['3촌']['7번']['3팀인원수']}<br />
                            추천 : {$memberInfo['3촌']['7번']['추천인']}<br />
                       </p>";
                if ($memberInfo['3촌']['7번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['3촌']['7번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['3촌']['7번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['2촌']['4번']['아이디']}&mb_name={$memberInfo['2촌']['4번']['이름']}&team=1','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\" alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>


                <div class="boxEmpty">
                        <span>
                            <div class="img"><img src="" alt="" onerror="this.style.display='none'"/></div>
                            <p>&nbsp;</p>
                        </span>
                </div>

                <?php
                if ($memberInfo['3촌']['8번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['3촌']['8번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a style=\"background-color: {$memberInfo['3촌']['8번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['3촌']['8번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['3촌']['8번']['이미지경로']}\" onerror=\"this.style.display='none'\">
                        </div>
                        <p>
                            이름 : {$memberInfo['3촌']['8번']['이름']}<br>
                            ID : {$memberInfo['3촌']['8번']['아이디']}<br>
                            가입 : {$memberInfo['3촌']['8번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['3촌']['8번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['3촌']['8번']['1팀인원수']}/{$memberInfo['3촌']['8번']['2팀인원수']}/{$memberInfo['3촌']['8번']['3팀인원수']}<br />
                            추천 : {$memberInfo['3촌']['8번']['추천인']}<br />
                    </p>";
                if ($memberInfo['3촌']['8번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['3촌']['8번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['3촌']['8번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['2촌']['4번']['아이디']}&mb_name={$memberInfo['2촌']['4번']['이름']}&team=2','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\" alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>


            </div><!--boxDepth3-->
            <div class="boxDepth4">


                <?php
                if ($memberInfo['4촌']['1번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['4촌']['1번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a style=\"background-color: {$memberInfo['4촌']['1번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['1번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['4촌']['1번']['이미지경로']}\" onerror=\"this.style.display='none'\" >
                        </div>
                        <p>
                            이름 : {$memberInfo['4촌']['1번']['이름']}<br>
                            ID : {$memberInfo['4촌']['1번']['아이디']}<br>
                            가입 : {$memberInfo['4촌']['1번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['4촌']['1번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['4촌']['1번']['1팀인원수']}/{$memberInfo['4촌']['1번']['2팀인원수']}/{$memberInfo['4촌']['1번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['1번']['추천인']}<br />
                     </p>";
                if ($memberInfo['4촌']['1번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['4촌']['1번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['4촌']['1번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['3촌']['1번']['아이디']}&mb_name={$memberInfo['3촌']['1번']['이름']}&team=1','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\" alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>


                <?php
                if ($memberInfo['4촌']['2번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['4촌']['2번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a style=\"background-color: {$memberInfo['4촌']['2번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['2번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['4촌']['2번']['이미지경로']}\" onerror=\"this.style.display='none'\">
                        </div>
                        <p>
                            이름 : {$memberInfo['4촌']['2번']['이름']}<br>
                            ID : {$memberInfo['4촌']['2번']['아이디']}<br>
                            가입 : {$memberInfo['4촌']['2번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['4촌']['2번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['4촌']['2번']['1팀인원수']}/{$memberInfo['4촌']['2번']['2팀인원수']}/{$memberInfo['4촌']['2번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['2번']['추천인']}<br />
                          </p>";
                if ($memberInfo['4촌']['2번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['4촌']['2번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['4촌']['2번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['3촌']['1번']['아이디']}&mb_name={$memberInfo['3촌']['1번']['이름']}&team=2','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\" alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>


                <?php
                if ($memberInfo['4촌']['3번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['4촌']['3번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a style=\"background-color: {$memberInfo['4촌']['3번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['3번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['4촌']['3번']['이미지경로']}\" onerror=\"this.style.display='none'\">
                        </div>
                        <p>
                            이름 : {$memberInfo['4촌']['3번']['이름']}<br>
                            ID : {$memberInfo['4촌']['3번']['아이디']}<br>
                            가입 : {$memberInfo['4촌']['3번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['4촌']['3번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['4촌']['3번']['1팀인원수']}/{$memberInfo['4촌']['3번']['2팀인원수']}/{$memberInfo['4촌']['3번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['3번']['추천인']}<br />
                      </p>";
                if ($memberInfo['4촌']['3번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['4촌']['3번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['4촌']['3번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['3촌']['2번']['아이디']}&mb_name={$memberInfo['3촌']['2번']['이름']}&team=1','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\" alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>


                <?php
                if ($memberInfo['4촌']['4번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['4촌']['4번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a style=\"background-color: {$memberInfo['4촌']['4번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['4번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['4촌']['4번']['이미지경로']}\" onerror=\"this.style.display='none'\">
                        </div>
                        <p>
                            이름 : {$memberInfo['4촌']['4번']['이름']}<br>
                            ID : {$memberInfo['4촌']['4번']['아이디']}<br>
                            가입 : {$memberInfo['4촌']['4번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['4촌']['4번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['4촌']['4번']['1팀인원수']}/{$memberInfo['4촌']['4번']['2팀인원수']}/{$memberInfo['4촌']['4번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['4번']['추천인']}<br />
                    </p>";
                if ($memberInfo['4촌']['4번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['4촌']['4번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['4촌']['4번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['3촌']['2번']['아이디']}&mb_name={$memberInfo['3촌']['2번']['이름']}&team=2','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\" alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>


                <?php
                if ($memberInfo['4촌']['5번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['4촌']['5번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a style=\"background-color: {$memberInfo['4촌']['5번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['5번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['4촌']['5번']['이미지경로']}\" onerror=\"this.style.display='none'\">
                        </div>
                        <p>
                            이름 : {$memberInfo['4촌']['5번']['이름']}<br>
                            ID : {$memberInfo['4촌']['5번']['아이디']}<br>
                            가입 : {$memberInfo['4촌']['5번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['4촌']['5번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['4촌']['5번']['1팀인원수']}/{$memberInfo['4촌']['5번']['2팀인원수']}/{$memberInfo['4촌']['5번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['5번']['추천인']}<br />
                        </p>";
                if ($memberInfo['4촌']['5번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['4촌']['5번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['4촌']['5번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel1 boxRight\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['3촌']['3번']['아이디']}&mb_name={$memberInfo['3촌']['3번']['이름']}&team=2','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\" alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>



                <?php
                if ($memberInfo['4촌']['6번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['4촌']['6번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a style=\"background-color: {$memberInfo['4촌']['6번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['6번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['4촌']['6번']['이미지경로']}\" onerror=\"this.style.display='none'\" >
                        </div>
                        <p>
                            이름 : {$memberInfo['4촌']['6번']['이름']}<br>
                            ID : {$memberInfo['4촌']['6번']['아이디']}<br>
                            가입 : {$memberInfo['4촌']['6번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['4촌']['6번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['4촌']['6번']['1팀인원수']}/{$memberInfo['4촌']['6번']['2팀인원수']}/{$memberInfo['4촌']['6번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['6번']['추천인']}<br />
                             </p>";
                if ($memberInfo['4촌']['6번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['4촌']['6번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['4촌']['6번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['3촌']['3번']['아이디']}&mb_name={$memberInfo['3촌']['3번']['이름']}&team=2','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\" alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>



                <?php
                if ($memberInfo['4촌']['7번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['4촌']['7번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a style=\"background-color: {$memberInfo['4촌']['7번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['7번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['4촌']['7번']['이미지경로']}\" onerror=\"this.style.display='none'\" >
                        </div>
                        <p>
                            이름 : {$memberInfo['4촌']['7번']['이름']}<br>
                            ID : {$memberInfo['4촌']['7번']['아이디']}<br>
                            가입 : {$memberInfo['4촌']['7번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['4촌']['7번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['4촌']['7번']['1팀인원수']}/{$memberInfo['4촌']['7번']['2팀인원수']}/{$memberInfo['4촌']['7번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['7번']['추천인']}<br />
                              </p>";
                if ($memberInfo['4촌']['7번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['4촌']['7번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['4촌']['7번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['3촌']['4번']['아이디']}&mb_name={$memberInfo['3촌']['4번']['이름']}&team=1','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\" alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>



                <?php
                if ($memberInfo['4촌']['8번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['4촌']['8번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a style=\"background-color: {$memberInfo['4촌']['8번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['8번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['4촌']['8번']['이미지경로']}\" onerror=\"this.style.display='none'\">
                        </div>
                        <p>
                            이름 : {$memberInfo['4촌']['8번']['이름']}<br>
                            ID : {$memberInfo['4촌']['8번']['아이디']}<br>
                            가입 : {$memberInfo['4촌']['8번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['4촌']['8번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['4촌']['8번']['1팀인원수']}/{$memberInfo['4촌']['8번']['2팀인원수']}/{$memberInfo['4촌']['8번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['8번']['추천인']}<br />
                             </p>";
                if ($memberInfo['4촌']['8번']['수정권한'] == true) {

                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['4촌']['8번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['4촌']['8번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['3촌']['4번']['아이디']}&mb_name={$memberInfo['3촌']['4번']['이름']}&team=2','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\" alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>



                <?php
                if ($memberInfo['4촌']['9번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['4촌']['9번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a style=\"background-color: {$memberInfo['4촌']['9번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['9번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['4촌']['9번']['이미지경로']}\" onerror=\"this.style.display='none'\">
                        </div>
                        <p>
                            이름 : {$memberInfo['4촌']['9번']['이름']}<br>
                            ID : {$memberInfo['4촌']['9번']['아이디']}<br>
                            가입 : {$memberInfo['4촌']['9번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['4촌']['9번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['4촌']['9번']['1팀인원수']}/{$memberInfo['4촌']['9번']['2팀인원수']}/{$memberInfo['4촌']['9번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['9번']['추천인']}<br />
                        </p>";
                if ($memberInfo['4촌']['9번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['4촌']['9번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['4촌']['9번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['3촌']['5번']['아이디']}&mb_name={$memberInfo['3촌']['5번']['이름']}&team=1','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\" alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>




                <?php
                if ($memberInfo['4촌']['10번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['4촌']['10번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a style=\"background-color: {$memberInfo['4촌']['10번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['10번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['4촌']['10번']['이미지경로']}\" onerror=\"this.style.display='none'\">
                        </div>
                        <p>
                            이름 : {$memberInfo['4촌']['10번']['이름']}<br>
                            ID : {$memberInfo['4촌']['10번']['아이디']}<br>
                            가입 : {$memberInfo['4촌']['10번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['4촌']['10번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['4촌']['10번']['1팀인원수']}/{$memberInfo['4촌']['10번']['2팀인원수']}/{$memberInfo['4촌']['10번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['10번']['추천인']}<br />
                        </p>";
                if ($memberInfo['4촌']['10번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['4촌']['10번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['4촌']['10번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['3촌']['5번']['아이디']}&mb_name={$memberInfo['3촌']['5번']['이름']}&team=2','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\" alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>



                <?php
                if ($memberInfo['4촌']['11번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['4촌']['11번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a style=\"background-color: {$memberInfo['4촌']['11번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['11번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['4촌']['11번']['이미지경로']}\" onerror=\"this.style.display='none'\">
                        </div>
                        <p>
                            이름 : {$memberInfo['4촌']['11번']['이름']}<br>
                            ID : {$memberInfo['4촌']['11번']['아이디']}<br>
                            가입 : {$memberInfo['4촌']['11번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['4촌']['11번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['4촌']['11번']['1팀인원수']}/{$memberInfo['4촌']['11번']['2팀인원수']}/{$memberInfo['4촌']['11번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['11번']['추천인']}<br />
                            </p>";
                if ($memberInfo['4촌']['11번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['4촌']['11번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['4촌']['11번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['3촌']['6번']['아이디']}&mb_name={$memberInfo['3촌']['6번']['이름']}&team=1','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\" alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>



                <?php
                if ($memberInfo['4촌']['12번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['4촌']['12번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a style=\"background-color: {$memberInfo['4촌']['12번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['12번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['4촌']['12번']['이미지경로']}\" onerror=\"this.style.display='none'\">
                        </div>
                        <p>
                            이름 : {$memberInfo['4촌']['12번']['이름']}<br>
                            ID : {$memberInfo['4촌']['12번']['아이디']}<br>
                            가입 : {$memberInfo['4촌']['12번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['4촌']['12번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['4촌']['12번']['1팀인원수']}/{$memberInfo['4촌']['12번']['2팀인원수']}/{$memberInfo['4촌']['12번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['12번']['추천인']}<br />
                          </p>";
                if ($memberInfo['4촌']['12번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['4촌']['12번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['4촌']['12번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['3촌']['6번']['아이디']}&mb_name={$memberInfo['3촌']['6번']['이름']}&team=2','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\" alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>



                <?php
                if ($memberInfo['4촌']['13번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['4촌']['13번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a style=\"background-color: {$memberInfo['4촌']['13번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['13번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['4촌']['13번']['이미지경로']}\" onerror=\"this.style.display='none'\">
                        </div>
                        <p>
                            이름 : {$memberInfo['4촌']['13번']['이름']}<br>
                            ID : {$memberInfo['4촌']['13번']['아이디']}<br>
                            가입 : {$memberInfo['4촌']['13번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['4촌']['13번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['4촌']['13번']['1팀인원수']}/{$memberInfo['4촌']['13번']['2팀인원수']}/{$memberInfo['4촌']['13번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['13번']['추천인']}<br />
                       </p>";
                if ($memberInfo['4촌']['13번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['4촌']['13번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['4촌']['13번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['3촌']['7번']['아이디']}&mb_name={$memberInfo['3촌']['7번']['이름']}&team=1','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\" alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>



                <?php
                if ($memberInfo['4촌']['14번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['4촌']['14번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a style=\"background-color: {$memberInfo['4촌']['14번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['14번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['4촌']['14번']['이미지경로']}\" onerror=\"this.style.display='none'\">
                        </div>
                        <p>
                            이름 : {$memberInfo['4촌']['14번']['이름']}<br>
                            ID : {$memberInfo['4촌']['14번']['아이디']}<br>
                            가입 : {$memberInfo['4촌']['14번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['4촌']['14번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['4촌']['14번']['1팀인원수']}/{$memberInfo['4촌']['14번']['2팀인원수']}/{$memberInfo['4촌']['14번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['14번']['추천인']}<br />
                       </p>";
                if ($memberInfo['4촌']['14번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['4촌']['14번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['4촌']['14번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['3촌']['7번']['아이디']}&mb_name={$memberInfo['3촌']['7번']['이름']}&team=2','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\" alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>



                <?php
                if ($memberInfo['4촌']['15번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['4촌']['15번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a style=\"background-color: {$memberInfo['4촌']['15번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['15번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['4촌']['15번']['이미지경로']}\" onerror=\"this.style.display='none'\" >
                        </div>
                        <p>
                            이름 : {$memberInfo['4촌']['15번']['이름']}<br>
                            ID : {$memberInfo['4촌']['15번']['아이디']}<br>
                            가입 : {$memberInfo['4촌']['15번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['4촌']['15번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['4촌']['15번']['1팀인원수']}/{$memberInfo['4촌']['15번']['2팀인원수']}/{$memberInfo['4촌']['15번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['15번']['추천인']}<br />
                          </p>";
                if ($memberInfo['4촌']['15번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['4촌']['15번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['4촌']['15번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['3촌']['8번']['아이디']}&mb_name={$memberInfo['3촌']['8번']['이름']}&team=1','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\" alt=\"\">
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>



                <?php
                if ($memberInfo['4촌']['16번']['존재유무'] == true) {
                    $timenow = date("Y-m-d");
                    $str_now = strtotime($timenow);
                    $timestamp3 = date("Y-m-d", strtotime($memberInfo['4촌']['16번']['리뉴얼날짜'] . "+1 day"));
                    $str_timestamp3 = strtotime($timestamp3);
                    $renewalColor = "";
                    if ($str_now >= $str_timestamp3) {
                        $renewalColor = "red";
                    }
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a style=\"background-color: {$memberInfo['4촌']['16번']['색상코드']};\" onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['16번']['아이디']}\">
                    	<div class=\"img_element\">
                    	<img src=\"{$memberInfo['4촌']['16번']['이미지경로']}\" onerror=\"this.style.display='none'\">
                        </div>
                        <p>
                            이름 : {$memberInfo['4촌']['16번']['이름']}<br>
                            ID : {$memberInfo['4촌']['16번']['아이디']}<br>
                            가입 : {$memberInfo['4촌']['16번']['회원가입날짜']}<br />
                            <font style=\"color: {$renewalColor};\">리뉴얼 : {$memberInfo['4촌']['16번']['리뉴얼날짜']}</font><br />
                            인원 : {$memberInfo['4촌']['16번']['1팀인원수']}/{$memberInfo['4촌']['16번']['2팀인원수']}/{$memberInfo['4촌']['16번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['16번']['추천인']}<br />
                         </p>";
                if ($memberInfo['4촌']['16번']['수정권한'] == true) {
                    if ($modi_auth) {
                        echo " <img class=\"modi_img;\" style=\"background-color: {$memberInfo['4촌']['16번']['색상코드']};\" src =\"./images/icon_pencil.png\" style = \"width:19px; height:18px;\" onclick=\"modify_check('{$memberInfo['4촌']['16번']['아이디']}')\" >";
                    }
                }

                    echo "</a><div class=\"boxLine\"></div></div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">

                    <a href=\"#\" style=\"background:none\" id=\"insert\" onclick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id={$memberInfo['3촌']['8번']['아이디']}&mb_name={$memberInfo['3촌']['8번']['이름']}&team=2','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\">
                        <img src=\"images/box_insert.png\" onerror=\"this.style.display='none'\" alt=\"\">
                    </a>

                    <div class=\"boxLine\"></div>

                </div>";
                }
                ?>


                <input type="hidden" id="infoTemp">

            </div><!--boxDepth4-->


        </div><!--boxContent-->
    </div><!--boxContentW-->


</section>


<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<script type="text/javascript">


    /* 박스리스트 드래그박스 */
    $(document).ready(function () {
        $("#draggable").draggable();
//                        $(".modi_img").style.display = "block";

    });

    function modify_check(mb_id) {
        event.stopPropagation() //이벤트 하나만되게
        var rank = '<?= $member['accountRank'] ?>';
        var admin_mb_id = '<?= $member['mb_id'] ?>';

        if (rank == '5 STAR' || rank == 'AMBASSADOR' || rank == 'Crown AMBASSADOR' || rank == 'Double AMBASSADOR' || rank == 'Royal Crown AMBASSADOR' || rank == 'Triple AMBASSADOR'
            || admin_mb_id == "00003656" || admin_mb_id == "00003696" || admin_mb_id == "00000182" || admin_mb_id == "00003710" || admin_mb_id == "00031544" || admin_mb_id == "00003686" || admin_mb_id == "00003943" || admin_mb_id == "00003571" || admin_mb_id == "00003697"|| admin_mb_id == "00003879"   || admin_mb_id == "00003660"|| admin_mb_id == "00003880"|| admin_mb_id == "00066958"|| admin_mb_id == "00447591"|| admin_mb_id == "00447972"|| admin_mb_id == "00447976"||admin_mb_id == '00003967'|| admin_mb_id == '00019492'|| admin_mb_id == '00104175'|| admin_mb_id == '00104204') {
            window.open('/myOffice/pop_member_form.php?mb_id=' + mb_id, '수정화면', 'width=1100, height=900,, "toolbar=yes,scrollbars=yes,resizable=yes, scrollbars = 1');
        } else {
            alert("수정권한이 없습니다.");
        }

    }

    /* 박스리스트 인쇄하기 */
    function content_print() {

        var boxContent_element = document.querySelector('.boxContent')
        boxContent_element.style.left = '0'
        boxContent_element.style.top = '0'
        var initBody = document.body.innerHTML;
        window.onbeforeprint = function () {
            document.body.innerHTML = document.getElementById('printArea').innerHTML;
        }
        window.onafterprint = function () {
            document.body.innerHTML = initBody;
        }
        window.print();
        $(".boxContent").draggable();
    }

    $(document).ready(function () {
        var totalTile = 16;
        var bodyW = parseInt($('body').css('width'));
        var boxContentWidth = totalTile * 170;
        var $boxContent = $('.boxContent');

        $boxContent.css('width', boxContentWidth);
        if (bodyW > 800) {
            var tt = parseInt($boxContent.css('width')) / 3;
        } else {
            var tt = parseInt($boxContent.css('width')) / 2.3;
        }
        $(".boxContentW").scrollLeft(tt);

        /*셀렉트박스*/
        var select = $("select#nameNick");


        select.change(function () {
            var select_name = $(this).children("option:selected").text();
            $(this).siblings("label").text(select_name);
        });
    });


    //                    boxContent.position().top;
    // 부모 요소의 상단값을 기준으로 test 엘리먼트 요소가 위치한 상대적 거리값

    //                    boxContent.position().left;
    // 부모 요소의 좌측값을 기준으로 test 엘리먼트 요소가 위치한 상대적 거리값
    function selectView(id) {
        location.href = "/myOffice/box_1.php?mb_id=" + id + "&boxCheck=true";
    }

    function boxBack() {
        history.back();
    }


    function searchClick() {
        $('.loading_wrap').addClass('on')
        if ($("#nameNick option:selected").val() == "아이디") {
            var data = "searchID=" + $('#searchInput').val() + "&loginID=" + $('#loginID').val();

            $.ajax({
                url: './memberIDSearch.php',
                type: 'POST',
                data: data,
                success: function (result) {
                    $('.loading_wrap').removeClass('on');
                    if (result == "true") {
                        location.href = '/myOffice/box_1.php?mb_id=' + $('#searchInput').val();
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
                    $('.loading_wrap').removeClass('on');
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

                    for (var i = 0; i < json.length; i++) {
                        $('#search_info_tbl > tbody:last').append('<tr id=\"' + json[i][1] + '\" onclick=\"searchSelect(this.id);\"><td>' + json[i][0] + '</td><td>' + json[i][2] + '</td><td>' + json[i][5] + '</td><td>' + json[i][3] + '</td><td>' + json[i][4] + '</td></a></tr>');
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
        window.location.href = '/myOffice/box_1.php?mb_id=' + id;
    }

</script>


<div id="spot" style="visibility: hidden;">

    <?php
    $renewalTitle = mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'");
    $row11 = mysql_fetch_array($renewalTitle);

    $row11['VMC'] = number_format($row11['VMC']);
    $row11['VMR'] = number_format($row11['VMR']);
    $row11['VMP'] = number_format($row11['VMP']);
    ?>
    <div class="pop_tit">VM 회원비 결제</div>
    <div id="renewal_pop">


        <div class="pop_vmc pop">
            <div>
                <p>VMC</p>
                <span><?= $row11['VMC'] ?></span>
            </div>
            <input type="number" name="pop_vmc" id="pop_vmc" placeholder="VMC 차감" onkeyup="PointOnchange(this);" disabled="disabled">
        </div>
        <div class="pop_vmr pop">
            <div>
                <p>VMR</p>
                <span><?= $row11['VMR'] ?></span>
            </div>
            <input type="number" name="pop_vmr" id="pop_vmr" placeholder="VMR 차감" onkeyup="PointOnchange(this);">
        </div>
        <div class="pop_vmp pop">
            <div>
                <p>VMP</p>
                <span><?= $row11['VMP'] ?></span>
            </div>
            <input type="number" name="pop_vmp" id="pop_vmp" placeholder="VMP 차감"
                   onkeyup="PointOnchange(this);" disabled="disabled">
        </div>
        <div class="vm_sum">
            <div>
                <p>합 계 :</p>
                <span id="value">0</span>
            </div>
        </div>
        <div class="summary">
            <?php
            $renewal = mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'");
            $row2 = mysql_fetch_array($renewal);

            if ($row2['accountType'] == "CU" && $row2['renewal'] != null && $timestamp <= $now_temp) {

                echo "<div><p>결제할 금액 : </p><span id=\"sum_count\">{$money}</span></div>";
            } else {
                echo "<div><p>결제할 금액 : </p><span id=\"sum_count\">2080000</span></div>";
            }
            ?>


        </div>
        <div id="renewal_submit" style="cursor: pointer;"
             onclick="return false;submitCheck();">결제하기
        </div>
        <a href="javascript:void(0);" onclick="$('#spot').css('visibility', 'hidden');
                    return false;">
            <div id="renewal_cancel"
                 style="cursor: pointer;">취소
            </div>
        </a>
    </div>
</div>


<script>
    function PointOnchange(val) {

        var popvmc = $('input[name=pop_vmc]').val();
        var popvmr = $('input[name=pop_vmr]').val();
        var popvmp = $('input[name=pop_vmp]').val();

        var popsum = $('.vm_sum').find('span');
        var popsummary = $('.summary').find('span');

        var selectInput = $(val).attr('name');
        var selectInputVal = $(val).val();


        if (Number(selectInputVal) > Number($('.' + selectInput).find('span').text())) {
            alert('잔여포인트 보다 차감액이 큽니다!');
            $(val).val($('.' + selectInput).find('span').text());
            PointOnchange();
            return;
        }


        popsum.text(Number(popvmc) + Number(popvmr) + Number(popvmp));
        popsummary.text(2080000 - Number(popsum.text()));

        $('input[name=pay]').val(2080000 - Number(popsum.text()));
        $('input[name=VMC]').val(popvmc);
        $('input[name=VMR]').val(popvmr);
        $('input[name=VMP]').val(popvmp);
        $('input[name=totalpoint]').val(Number(popsum.text()));
    }


    function submitCheck() {
        var popsum = $('.vm_sum').find('span');
        var popsummary = $('.summary').find('span');

        if (Number(popsummary.text()) < 0) {
            alert("포인트가 결제할 금액보다 큽니다.");
            return false;
        } else if (Number(popsummary.text()) > 0) {
            alert('※ 포인트가 부족합니다.');
        } else if (Number(popsummary.text()) == 0) {

            if ($("#pop_vmc").val() == "")
                $("#pop_vmc").val("0");
            if ($("#pop_vmr").val() == "")
                $("#pop_vmr").val("0");
            if ($("#pop_vmp").val() == "")
                $("#pop_vmp").val("0");


            window.open($("#infoTemp").val() + "&vmc=" + $("#pop_vmc").val() + "&vmr=" + $("#pop_vmr").val() + "&vmp=" + $("#pop_vmp").val() + "&loginID=" + $('#loginID').val(), 'small', 'width=450,height=605,scrollbars=yes,menubar=no,location=no');

            $("#pop_vmc").val("");
            $("#pop_vmr").val("");
            $("#pop_vmp").val("");

            $("#spot").css("visibility", "hidden");
        }
    }
</script>


<!--로딩페이지-->
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

<div class="loading_wrap">
    <div class="loading_content">
        <img class="loading_img" src="/myOffice/images/vmp_loading_icon.gif" alt="로딩메세지"/>
        <p class="loading_txt">회원을 검색중 입니다.</p>
    </div>
</div>


</body>
</html>
<?php
include_once('../shop/shop.tail.php');
?>
<script src="js/main.js"></script>
