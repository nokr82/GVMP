<?php 
    include_once ('./_common.php');
    include_once ('./dbConn.php');
    include_once('./getMemberInfo.php');
    // 직급을 매개변수로 넘기면 이미지 경로를 리턴 해 주는 함수

    $memberInfo = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$member["mb_id"]}'"));
    $recoCount = mysql_fetch_array(mysql_query("select count(*) as C from genealogy as G left join g5_member as M on G.mb_id = M.mb_id where G.recommenderID = '{$member["mb_id"]}' and DATE_ADD(M.renewal, INTERVAL + 1 MONTH) > CURDATE()"));



    $rank = "";
    $nextRank = "";
    $min1Team = 0;
    $min2Team = 0;
    if( $recoCount["C"] >= 3 && $memberInfo["team1_temp"] >= 3000 && $memberInfo["team2_temp"] >= 3000 ) {
        $rank = "Crown AMBASSADOR";
        $nextRank = "Crown AMBASSADOR";
        $min1Team = 0;
        $min2Team = 0;
    } else if( $recoCount["C"] >= 3 && $memberInfo["team1_temp"] >= 2000 && $memberInfo["team2_temp"] >= 2000 ) {
        $rank = "Triple AMBASSADOR";
        $nextRank = "Crown AMBASSADOR";
        $min1Team = -3000 + $teamCountArray[0];
        $min2Team = -3000 + $teamCountArray[1];
    } else if( $recoCount["C"] >= 3 && $memberInfo["team1_temp"] >= 1000 && $memberInfo["team2_temp"] >= 1000 ) {
        $rank = "Double AMBASSADOR";
        $nextRank = "Triple AMBASSADOR";
        $min1Team = -2000 + $teamCountArray[0];
        $min2Team = -2000 + $teamCountArray[1];
    } else if( $recoCount["C"] >= 3 && $memberInfo["team1_temp"] >= 600 && $memberInfo["team2_temp"] >= 600 ) {
        $rank = "AMBASSADOR";
        $nextRank = "Double AMBASSADOR";
        $min1Team = -1000 + $teamCountArray[0];
        $min2Team = -1000 + $teamCountArray[1];
    } else if( $recoCount["C"] >= 3 && $memberInfo["team1_temp"] >= 375 && $memberInfo["team2_temp"] >= 375 ) {
        $rank = "5 STAR";
        $nextRank = "AMBASSADOR";
        $min1Team = -600 + $teamCountArray[0];
        $min2Team = -600 + $teamCountArray[1];
    } else if( $recoCount["C"] >= 3 && $memberInfo["team1_temp"] >= 250 && $memberInfo["team2_temp"] >= 250 ) {
        $rank = "4 STAR";
        $nextRank = "5 STAR";
        $min1Team = -375 + $teamCountArray[0];
        $min2Team = -375 + $teamCountArray[1];
    } else if( $recoCount["C"] >= 3 && $memberInfo["team1_temp"] >= 150 && $memberInfo["team2_temp"] >= 150 ) {
        $rank = "3 STAR";
        $nextRank = "4 STAR";
        $min1Team = -250 + $teamCountArray[0];
        $min2Team = -250 + $teamCountArray[1];
    } else if( $recoCount["C"] >= 3 && $memberInfo["team1_temp"] >= 75 && $memberInfo["team2_temp"] >= 75 ) {
        $rank = "2 STAR";
        $nextRank = "3 STAR";
        $min1Team = -150 + $teamCountArray[0];
        $min2Team = -150 + $teamCountArray[1];
    } else if( $recoCount["C"] >= 3 && $memberInfo["team1_temp"] >= 40 && $memberInfo["team2_temp"] >= 40 ) {
        $rank = "1 STAR";
        $nextRank = "2 STAR";
        $min1Team = -75 + $teamCountArray[0];
        $min2Team = -75 + $teamCountArray[1];
    } else if( $recoCount["C"] >= 3 && $memberInfo["team1_temp"] >= 20 && $memberInfo["team2_temp"] >= 20 ) {
        $rank = "Triple MASTER";
        $nextRank = "1 STAR";
        $min1Team = -40 + $teamCountArray[0];
        $min2Team = -40 + $teamCountArray[1];
    } else if( $recoCount["C"] >= 3 && $memberInfo["team1_temp"] >= 6 && $memberInfo["team2_temp"] >= 6 ) {
        $rank = "Double MASTER";
        $nextRank = "Triple MASTER";
        $min1Team = -20 + $teamCountArray[0];
        $min2Team = -20 + $teamCountArray[1];
    } else if( $recoCount["C"] >= 3 ) {
        $rank = "MASTER";
        $nextRank = "Double MASTER";
        $min1Team = -6 + $teamCountArray[0];
        $min2Team = -6 + $teamCountArray[1];
    } else {
        $rank = "VM";
        $nextRank = "MASTER";
        $min1Team = 0;
        $min2Team = 0;
    }
    
    $jobAllowanceRe = mysql_fetch_array(mysql_query("SELECT * FROM jobAllowance where accountRank = '{$nextRank}'"));
    $jobAllowanceRe2 = mysql_fetch_array(mysql_query("SELECT * FROM jobAllowance where accountRank = '{$rank}'"));
    
    $dayMoney = number_format($jobAllowanceRe2["Allowance"]); // 일일 수당
    $textTemp = "";
    if( $rank == "MASTER" ) {
        $dayMoney = 0;
        $textTemp = "(회비면제)";
    } else if( $rank == "Double MASTER" ) {
        $textTemp = "(회비면제)";
    }
    
    
    // 롤링 배너에 필요한 직급자들 정보 뽑아오기
    $result1 = mysql_query( "SELECT 
    a.*, b.Allowance
FROM
    (SELECT 
        rankCheck.*, g5_member.mb_name
    FROM
        rankCheck
    INNER JOIN g5_member ON rankCheck.mb_id = g5_member.mb_id
    WHERE
       g5_member.mb_id = '{$member["mb_id"]}'
        ) AS a
        INNER JOIN
    jobAllowance AS b ON a.rankAccount = b.accountRank
ORDER BY b.Allowance DESC , a.date" );
    
    
//    $result2 = mysql_query( "SELECT 
//        rankCheck.*, g5_member.mb_name
//    FROM
//        rankCheck
//            INNER JOIN
//        g5_member ON rankCheck.mb_id = g5_member.mb_id
//    WHERE
//        rankAccount LIKE '%MASTER'
//    ORDER BY date DESC
//    LIMIT 20" );
    
    
//    $result3 = mysql_query( "SELECT 
//        rankCheck.*, g5_member.mb_name
//    FROM
//        rankCheck
//            INNER JOIN
//        g5_member ON rankCheck.mb_id = g5_member.mb_id
//    WHERE
//        rankAccount LIKE 'VM'
//    ORDER BY date DESC
//    LIMIT 30" );
    /////////////////////////////////////////////////////////////////////
    
$sharePointRe = mysql_fetch_array(mysql_query("select sum(VMC) as sum from dayPoint where way like '%sharePoint%' and mb_id = '{$member["mb_id"]}'"));




$sharePointRe2 = mysql_fetch_array(mysql_query("select count(*) as c from
(SELECT c.mb_name AS mb_name
   ,c.mb_id, c.sponsorID, c.sponsorName, c.recommenderID, c.recommenderName, v.level
FROM
(
   SELECT f_category() AS mb_id, @level AS LEVEL
   FROM (SELECT @start_with:='{$member["mb_id"]}', @id:=@start_with, @level:=0) vars
          INNER JOIN genealogy
   WHERE @id IS NOT NULL
) v
   INNER JOIN genealogy c ON v.mb_id = c.mb_id
) t
                INNER JOIN g5_member m ON t.mb_id = m.mb_id
        where accountType = 'VM' and level <= 20 and renewal >= LEFT(DATE_ADD(NOW(), INTERVAL -1 MONTH),10)"));






$totalMNYRow = mysql_fetch_array(mysql_query("select SUM(VMC+VMP) as total from calculateTBL where mb_id = '{$member["mb_id"]}'"));
if( $totalMNYRow["total"] == "" ) {
    $totalMNYRow["total"] = 0;
}

$transferRow = mysql_fetch_array(mysql_query("select sum(VMC) as total from dayPoint where mb_id = '{$member["mb_id"]}' and (way like 'transfer%' or way like 'autoRenewal%' or way like 'Renewal%' or way like 'newRenewal%' or way like 'smJoin%' or way like 'smRenewal%' or way like 'smAutoRenewal%' or way like 'vmJoin%') and (VMC < 0 or VMR <0 or VMP < 0)"));


$totalMNYRow["total"] += ($member["VMC"]+$member["VMP"]) + abs($transferRow["total"]);
    

    
?>
<link rel="stylesheet" href="./css/simpleBanner.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="./js/simpleBanner.js"></script>
    <!-- ㅇㅇㅇㅇㅇㅇㅇ여기부터ㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇ-->
<body>
    
<div id="banner02box" class="clearfix">
<!--테두리 주석처리-->     
<!--    <div id="border_all">
        <div id="M_box_border"></div>
        <div id="S_box_border">
            <div class="loader">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div id="VM_box_border"></div>

    </div>-->

    
    <!-- 마스터 롤링 배너1 -->
    <div class="simple_banner_wrap banner02 bn1" data-type="vslide" data-interval="3000">
        <ul>
            <li>
                    <div id="VM_box" class="M_box_all">                        
                        <div>누적 공유 보너스</div>
                        <div class="color_gold"><?php echo number_format($sharePointRe["sum"])?><span>vmc</span></div>
                        <div class="sim_bar"></div>
                        <div>월 예상 공유 보너스</div>
                        <div class="color_gold"><?php echo number_format($sharePointRe2*500); ?><span>vmc</span></div>
                        <div class="sim_bar"></div>
                        <div>현재 누적 금액</div>
                        <div class="color_gold"><?php echo number_format($totalMNYRow["total"]);?><span>원</span></div>
                    </div>
                </li>
            
        <?php
//            while( $row2 = mysql_fetch_array($result2) ) {
//                $date = date('Y-m-d', strtotime($row2['date']));
//                $imagePath = imagePath( $row2['rankAccount'] );
//                echo "<li>
//                    <div id=\"M_box\" class=\"M_box_all\">
//                        <div>현재 일일 수당</div>
//                        <div>30,000<span>vmc</span></div>
//                        <div>다음 직급 정보</div>
//                        <div>직급: TRIPLE MASTER</div>
//                        <div>팀별 인원수:  -66 / -33</div>
//                        <div>일일 수당: 50,000VMC</div>
//                    </div>
//                </li>";
//            }
        ?>
        </ul>
    </div>
    
    <!-- 마스터 롤링 배너2 -->
    <div class="simple_banner_wrap banner02 bn2" data-type="vslide" data-interval="3000">
        <ul>
<!--            <li>
                <div id="S_box">
                    <div>
                        <p><img src="images/zik_up.png" width="100%;"></p>
                        <p>직급</p><p><?=$row1['rankAccount']?></p>
                        <p><img src="images/zik_down.png" width="100%;"></p>
                    </div>
                    <div>
                        <p><img src="{$imagePath}" width="100%;"></p>
                    </div>
                    <div>
                        <p>이름 <b style="text-align: left">임시이름</b></p>
                        <p>달성시기 <b style="text-align: left">2018-12-12</b></p>
                        <p class='btm_text' >현재 1팀/2팀/무료 추천 3명</p>
                    </div>
                </div>
            </li>-->
            <?php                

                

                $now_temp = strtotime(date("Y-m-d"));

                
                while( $row1 = mysql_fetch_array($result1) ) {

                    $date = date('Y-m-d', strtotime($row1['date']));
                    $imagePath = imagePath( $row1['rankAccount'] );
                    
                    $rankAllowanceRe = mysql_fetch_array(mysql_query("SELECT * FROM jobAllowance where accountRank = '{$row1['rankAccount']}'"));

                    
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
                                    <p class=\"day_pay color_gold\">일일 수당 : ".number_format($rankAllowanceRe["Allowance"])." VMC</p>
                                    <p class=\"day_pay color_gold\">월 수당 : " . number_format($rankAllowanceRe["Allowance"]*30) . " VMC</p>
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
                    <div id="M_box" class="M_box_all">
                        <div>현재 일일 수당 <?=$textTemp?></div>
                        <div><?php echo $dayMoney;?><span>vmc</span></div>
                        <div>현재 인원수</div>
                        <div><?php echo "{$memberInfo["team1_temp"]}/{$memberInfo["team2_temp"]}/{$memberInfo["team3_temp"]}";?> 추천 <?php echo "{$recoCount["C"]}";?>명</div>
                        <div class="sim_bar"></div>
                        <div class="mbox_right">다음 직급 정보</div>
                        <div class="mbox_right">직급: <?=$nextRank?></div>
                        <div class="mbox_right">인원수:  <?=$min1Team?> / <?=$min2Team?></div>
                        <div class="mbox_right">일일 수당: <?php echo number_format($jobAllowanceRe["Allowance"]);?>VMC</div>
                        
                    </div>
                </li>
        <?php
//            while( $row3 = mysql_fetch_array($result3) ) {
//                $date = date('Y-m-d', strtotime($row3['date']));
//                $imagePath = imagePath( $row3['rankAccount'] );
//
//
//                echo "<li>
//                        <div id=\"VM_box\">
//                            <div>직급</div>
//                            <div>{$row3['rankAccount']}</div>
//                            <div><img src=\"{$imagePath}\" width=\"90%;\"></div>
//                            <div>이름</div>
//                            <div>{$row3['mb_name']}</div>
//                            <div>달성시기</div>
//                            <div>{$date}</div>
//                        </div>
//                    </li>";
//            }
        ?>
        </ul>
    </div>
</div> 
    
    
    </div>
</body>
    <!-- ㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇ여기까지ㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇ-->