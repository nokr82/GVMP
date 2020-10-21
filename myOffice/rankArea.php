<?php
include_once('./_common.php');
include_once('./dbConn.php');
include_once('./getMemberInfo.php');


$prev_month = strtotime("-1 month", mktime(0, 0, 0, date("m"), 1, date("Y")));


$memberInfo = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$member["mb_id"]}'"));
$recoCount = mysql_fetch_array(mysql_query("select count(*) as C from genealogy as G left join g5_member as M on G.mb_id = M.mb_id where G.recommenderID = '{$member["mb_id"]}' and DATE_ADD(M.renewal, INTERVAL + 1 month) >= CURDATE()"));


$rank = "";
$nextRank = "";
$min1Team = 0;
$min2Team = 0;

if ($memberInfo['rank_yn'] == 'Y') {
    $rank = $memberInfo['accountRank'];
    $min1Team = $memberInfo["team1"];
    $min2Team = $memberInfo["team2"];
} else {
    if ($memberInfo["team1"] >= 800 && $memberInfo["team2"] >= 800) {

        if (rankCountCheck($memberInfo["mb_id"], 'Crown AMBASSADOR', 1)) {
            $rank = "Royal Crown AMBASSADOR";
            $min1Team = 0;
            $min2Team = 0;
        } else {
            $rank = "Crown AMBASSADOR";
            $min1Team = 0;
            $min2Team = 0;
        }
    } else if ($memberInfo["team1"] >= 500 && $memberInfo["team2"] >= 500) {
        $rank = "Triple AMBASSADOR";
        $min1Team = -800 + $memberInfo["team1"];
        $min2Team = -800 + $memberInfo["team2"];
    } else if ($memberInfo["team1"] >= 300 && $memberInfo["team2"] >= 300) {
        $rank = "Double AMBASSADOR";
        $min1Team = -500 + $memberInfo["team1"];
        $min2Team = -500 + $memberInfo["team2"];
    } else if ($memberInfo["team1"] >= 200 && $memberInfo["team2"] >= 200) {
        $rank = "AMBASSADOR";
        $min1Team = -300 + $memberInfo["team1"];
        $min2Team = -300 + $memberInfo["team2"];
    } else if ($memberInfo["team1"] >= 125 && $memberInfo["team2"] >= 125) {
        $rank = "5 STAR";
        $min1Team = -200 + $memberInfo["team1"];
        $min2Team = -200 + $memberInfo["team2"];
    } else if ($memberInfo["team1"] >= 84 && $memberInfo["team2"] >= 84) {
        $rank = "4 STAR";
        $min1Team = -125 + $memberInfo["team1"];
        $min2Team = -125 + $memberInfo["team2"];
    } else if ($memberInfo["team1"] >= 50 && $memberInfo["team2"] >= 50) {
        $rank = "3 STAR";
        $min1Team = -84 + $memberInfo["team1"];
        $min2Team = -84 + $memberInfo["team2"];
    } else if ($memberInfo["team1"] >= 24 && $memberInfo["team2"] >= 24) {
        $rank = "2 STAR";
        $nextRank = "3 STAR";
        $min1Team = -50 + $memberInfo["team1"];
        $min2Team = -50 + $memberInfo["team2"];
    } else if ($memberInfo["team1"] >= 13 && $memberInfo["team2"] >= 13) {
        $rank = "1 STAR";
        $min1Team = -24 + $memberInfo["team1"];
        $min2Team = -24 + $memberInfo["team2"];
    } else if ($memberInfo["team1"] >= 6 && $memberInfo["team2"] >= 6) {
        $rank = "Triple MASTER";
        $min1Team = -13 + $memberInfo["team1"];
        $min2Team = -13 + $memberInfo["team2"];
    } else if ($memberInfo["team1"] >= 2 && $memberInfo["team2"] >= 2) {
        $rank = "Double MASTER";
        $min1Team = -6 + $memberInfo["team1"];
        $min2Team = -6 + $memberInfo["team2"];
    } else {
        $rank = "MASTER";
        $min1Team = -2 + $memberInfo["team1"];
        $min2Team = -2 + $memberInfo["team2"];
    }
}

switch ($rank) {
    case 'VM':
        $nextRank = "MASTER";
        break;
    case 'MASTER':
        $nextRank = "Double MASTER";
        break;
    case 'Double MASTER':
        $nextRank = "Triple MASTER";
        break;
    case 'Triple MASTER':
        $nextRank = "1 STAR";
        break;
    case '1 STAR':
        $nextRank = "2 STAR";
        break;
    case '2 STAR':
        $nextRank = "3 STAR";
        break;
    case '3 STAR':
        $nextRank = "4 STAR";
        break;
    case '4 STAR':
        $nextRank = "5 STAR";
        break;
    case '5 STAR':
        $nextRank = "AMBASSADOR";
        break;
    case 'AMBASSADOR':
        $nextRank = "Double AMBASSADOR";
        break;
    case 'Double AMBASSADOR':
        $nextRank = "Triple AMBASSADOR";
        break;
    case 'Triple AMBASSADOR':
        $nextRank = "Crown AMBASSADOR";
        break;
    case 'Crown AMBASSADOR':
        $nextRank = "Royal Crown AMBASSADOR";
        break;
    case 'Royal Crown AMBASSADOR':
        $nextRank = "Royal Crown AMBASSADOR";
        break;
}

$jobAllowanceRe = mysql_fetch_array(mysql_query("SELECT * FROM jobAllowance where accountRank = '{$nextRank}'"));
$jobAllowanceRe2 = mysql_fetch_array(mysql_query("SELECT * FROM jobAllowance where accountRank = '{$rank}'"));


$dayMoney = number_format($jobAllowanceRe2["Allowance"]); // 일일 수당
$textTemp = "";
//if ($rank == "MASTER") {
//    $dayMoney = 0;
//    $textTemp = "(회비면제)";
//} else if ($rank == "Double MASTER") {
//    $textTemp = "(회비면제)";
//}


$sql = "SELECT 
    a.*, b.Allowance
FROM
    (SELECT 
        rankCheck.*, g5_member.mb_name
    FROM
        rankCheck
    INNER JOIN g5_member ON rankCheck.mb_id = g5_member.mb_id
    WHERE
       g5_member.mb_id = '{$member['mb_id']}') AS a
        INNER JOIN
    jobAllowance AS b ON a.rankAccount = b.accountRank
ORDER BY b.Allowance DESC , a.date";


// 롤링 배너에 필요한 직급자들 정보 뽑아오기
$result1 = mysql_query($sql);


//$sharePointRe = mysql_fetch_array(mysql_query("select sum(VMC) as sum from dayPoint where way like '%sharePoint%' and mb_id = '{$member["mb_id"]}'"));


$totalMNYRow = mysql_fetch_array(mysql_query("select SUM(VMC+VMP) as total from calculateTBL where mb_id = '{$member["mb_id"]}'"));
if ($totalMNYRow["total"] == "") {
    $totalMNYRow["total"] = 0;
}

$transferRow = mysql_fetch_array(mysql_query("select sum(VMC) as total from dayPoint where mb_id = '{$member["mb_id"]}' and (way like 'transfer%' or way like 'autoRenewal%' or way like 'Renewal%' or way like 'newRenewal%' or way like 'smJoin%' or way like 'smRenewal%' or way like 'smAutoRenewal%' or way like 'vmJoin%') and (VMC < 0 or VMR <0 or VMP < 0)"));


$totalMNYRow["total"] += ($member["VMC"] + $member["VMP"] + $member["VMM"]) + abs($transferRow["total"]);


?>
<link rel="stylesheet" href="./css/simpleBanner.css"/>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="./js/simpleBanner.js"></script>
<!-- ㅇㅇㅇㅇㅇㅇㅇ여기부터ㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇ-->
<body>

<div id="banner02box" class="clearfix">


    <!-- 마스터 롤링 배너1 -->
    <div class="simple_banner_wrap banner02 bn1" data-type="vslide" data-interval="3000">
        <ul>
            <li>
                <div id="VM_box" class="M_box_all">
                    <div>누적 공유 보너스</div>
                    <div class="color_gold"><?php // echo number_format($sharePointRe["sum"])?>개편 예정<span>vmc</span>
                    </div>
                    <!--                        <div class="sim_bar"></div>-->
                    <!--                        <div>월 예상 공유 보너스</div>
                        <div class="color_gold"><?php // echo number_format($memberInfo["sharePoint"]); ?><span>vmc</span></div>-->
                    <div class="sim_bar"></div>
                    <div>현재 누적 금액</div>
                    <div class="color_gold"><?php echo number_format($totalMNYRow["total"]); ?><span>원</span></div>
                </div>
            </li>
        </ul>
    </div>

    <!-- 마스터 롤링 배너2 -->
    <div class="simple_banner_wrap banner02 bn2" data-type="vslide" data-interval="3000">
        <ul>
            <?php
            while ($row1 = mysql_fetch_array($result1)) {

                $date = date('Y-m-d', strtotime($row1['date']));
                $imagePath = imagePath($row1['rankAccount']);

                echo "<li>
                            <div id=\"S_box\">
                                <div>
                                    <p><img src=\"images/zik_up.png\" width=\"100%;\"></p>                                    
                                    <p class=\"box_text\">{$row1['rankAccount']}</p>
                                    <p><img src=\"images/zik_down.png\" width=\"100%;\"></p>
                                </div>
                                <div>
                                    <p><img src=\"{$imagePath}\" width=\"100%;\"></p>
                                </div>
                                <div>
                                    <p>이름 <b style=\"text-align: left\">{$row1['mb_name']}</b></p>
                                    <p>달성시기 <b style=\"text-align: left\">{$date}</b></p>
                                    <p class=\"day_pay color_gold\">일일 수당 : " . number_format($row1["Allowance"]) . "</p>
                                    <p class=\"day_pay color_gold\">30일 수당 : " . number_format($row1["Allowance"] * 30) . "</p>
                                </div>
                            </div>
                        </li>";
            }


            ?>
        </ul>
    </div>

    <!-- 마스터 롤링 배너3 -->
    <div class="simple_banner_wrap banner02 bn3" data-type="vslide" data-interval="3000">
        <ul>
            <li>

                <?php
                $team3_row = mysql_fetch_row(mysql_query("select count(*) from team3_list where mb_id = '{$member["mb_id"]}'"));
                ?>
                <div id="M_box" class="M_box_all">
                    <div>현재 일일 수당 <?= $textTemp ?></div>
                    <div><?php echo $dayMoney; ?><span>vmc</span></div>
                    <div>현재 인원수</div>
                    <div><?php echo "{$memberInfo["team1"]}/{$memberInfo["team2"]}/{$team3_row[0]}"; ?>
                        추천 <?php echo "{$recoCount["C"]}"; ?>명
                    </div>
                    <div class="sim_bar"></div>
                    <div class="mbox_right">다음 직급 정보</div>
                    <div class="mbox_right">직급: <?= $nextRank ?></div>
                    <div class="mbox_right">인원수: <?= $min1Team ?> / <?= $min2Team ?></div>
                    <div class="mbox_right">일일 수당: <?php echo number_format($jobAllowanceRe["Allowance"]); ?>VMC</div>

                </div>
            </li>
        </ul>
    </div>
</div>


</div>
</body>