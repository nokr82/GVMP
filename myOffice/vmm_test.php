<?php

include_once('./dbConn.php');
include_once('./dbConn2.php');

set_time_limit(0);

$week = array("일요일" , "월요일"  , "화요일" , "수요일" , "목요일" , "금요일" ,"토요일") ;

$weekday = $week[ date('w'  , strtotime("Now")  ) ] ;
if ($weekday=="일요일"){
    exit();
}



$g5_memberRe = mysql_query("SELECT
                                    *
                                FROM
                                    g5_member AS a
                                WHERE
                                    a.accountType = 'VM'
                                        AND a.accountRank != 'VM'
                                        AND a.VMC > 0
                                        AND DATE_ADD(DATE_ADD(a.renewal, INTERVAL +4 month), interval 1 day) >= CURDATE()");


while( $g5_memberRow = mysql_fetch_array($g5_memberRe) ) {
    $monthVMMCheck = monthVMMCheck($g5_memberRow["accountRank"]);

    // totalVMM = 이번달에 VMM으로 적립된 토탈 금액
    $totalVMMRow = mysql_fetch_array(mysql_query("select sum(VMM) as VMM from dayPoint where mb_id = '{$g5_memberRow["mb_id"]}' and way = 'autoVMM' and date like '".date("Y-m-")."%' and VMM > 0"));
    $totalVMM = ($totalVMMRow["VMM"] == "") ? 0 : $totalVMMRow["VMM"];

    $monthMaxVMM = monthVMMCheck( $g5_memberRow["accountRank"] ); // 직급별 월 최대 적립금이 얼만지 알아내기

    if( $totalVMM >= $monthMaxVMM ) { // 이번달 적립시킬 VMM을 모두 적립 시켰다면 continue
        continue;
    }

    $jobAllowanceRow = mysql_fetch_array(mysql_query("SELECT * FROM jobAllowance where accountRank = '{$g5_memberRow["accountRank"]}'"));
    //0.3이 아닌 0.15를 한 이유
    //테이블값으로 메인화면도 움직이기에 최소한의 작업을 위해 식을 변경하였습니다.
    $dayMaxVMM = $jobAllowanceRow["Allowance"] * 0.15; // 하루에 최대로 뺄 수 있는 VMM

    $dayMaxVMM = ($dayMaxVMM > $monthVMMCheck) ? $monthVMMCheck : $dayMaxVMM; // 최대치 직급 최대치로 VMM으로 제한 시키기

    // curdateVMM = 오늘 VMM으로 적립된 금액
    $curdateVMMRow = mysql_fetch_array(mysql_query("select sum(VMM) as VMM from dayPoint where mb_id = '{$g5_memberRow["mb_id"]}' and way like 'autoVMM%' and date like '".date("Y-m-d")."%' and VMM > 0"));
    $curdateVMM = ($curdateVMMRow["VMM"] == "") ? 0 : $curdateVMMRow["VMM"];

    if( $curdateVMM != 0 ) { // 하루 최대 VMM 적립시킬 수 있는 금액을 이미 적립 했다면 continue. 하루 일일 수당의 30%까지가 한도임.
        continue;
    }

    $monthPoint = $monthVMMCheck - $totalVMM; // 이번달 적립시킬 금액
    if( $monthPoint < $dayMaxVMM)
        $dayMaxVMM = $monthPoint;

    $VMM = nowVMM( $g5_memberRow["VMC"], $dayMaxVMM );

    if( (int)$g5_memberRow["VMC"] < (int)$VMM ) { // 보유 VMC보다 뺄 VMM이 크다면 안 빼고 넘어가기
        continue;
    }


    //직급별 확인하기 이번달 daypoint 조회후 부족한 금액만 뽑기

    echo "----------------------------------------------------------------------------<br>";
echo $g5_memberRow['mb_id']."님의 직급은 ".$g5_memberRow['accountRank']."입니다. 오늘 적립금은 -->".$dayMaxVMM."원 입니다. 나갈 적립금 --> ".number_format($monthPoint)."<br>";
    echo $curdateVMM."<br>";
    echo $dayMaxVMM."<br>";
    echo $VMM."<br>";
    echo $monthPoint."<br>";
    echo $totalVMM."<br>";
    echo "----------------------------------------------------------------------------<br>";
    mysql_query("update g5_member set VMC = VMC - {$VMM}, VMM = VMM + {$VMM} where mb_id = '{$g5_memberRow["mb_id"]}'");
    mysql_query("insert into dayPoint set mb_id = '{$g5_memberRow["mb_id"]}', VMM = {$VMM}, date = NOW(), way = 'autoVMM'");
    mysql_query("insert into dayPoint set mb_id = '{$g5_memberRow["mb_id"]}', VMC = -{$VMM}, date = NOW(), way = 'autoVMM'");
}


function monthVMMCheck( $rank ) {
    // 직급을 넘겨 받아 해당 직급이 월 최대 적립금이 얼만지 리턴하는 함수

    //$re;
    switch( $rank ) {
        case "MASTER":
            $re = 4000;
            break;
        case "Double MASTER" :
            $re = 7500; break;
        case "Triple MASTER" :
            $re = 12500; break;
        case "1 STAR" :
            $re = 25000; break;
        case "2 STAR" :
            $re = 50000; break;
        case "3 STAR" :
            $re = 75000; break;
        case "4 STAR" :
            $re = 100000; break;
        case "5 STAR" :
            $re = 150000; break;
        case "AMBASSADOR" :
            $re = 250000; break;
        case "Double AMBASSADOR" :
            $re = 400000; break;
        case "Triple AMBASSADOR" :
            $re = 550000; break;
        case "Crown AMBASSADOR" :
            $re = 825000; break;
        case "Royal Crown AMBASSADOR" :
            $re = 1100000; break;
    }

    return $re;
}

function nowVMM($a, $b) { // 실제로 오늘 적립시킬 VMM 계산시키기.
    // $a = 보유 VMC,    $b = 최대 일일 수당의 30%
    if( $a < $b ) {
        return $a;
    } else if( $a > $b ) {
        return $b;
    } else if( $a == $b ) {
        return $a;
    }
}
