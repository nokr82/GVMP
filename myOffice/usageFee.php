<?php
include_once('./dbConn.php');

set_time_limit(0);

$g5_member_sql = "SELECT a.mb_id, a.mb_name, a.accountRank, a.accountType, a.renewal, a.VMC, a.VMG,j.usageFee, j.Allowance, j.Allowance*0.3 AS uses
                FROM g5_member AS a,
                     jobAllowance AS j
                                WHERE a.accountRank = j.accountRank
                                        AND a.accountType = 'VM'
                                        AND a.accountRank != 'MASTER'
                                        AND a.VMC > 0
                                        AND DATE_ADD(DATE_ADD(a.renewal, INTERVAL +4 month), interval 1 day) >= CURDATE();";
$result = mysql_query($g5_member_sql);


while ($row = mysql_fetch_array($result)) {

    $mb_id=$row['mb_id'];
    $all_rate = $row['uses']; // 직급별 일일수당의 30%
    $usageFee = $row['usageFee']; // 직급별 전산사용료
    $totalVMG = $row['VMG']; // 회원의 VMG금액 확인
    $totalVMC = $row['VMC']; // 회원의 VMC금액 확인
    $renewal = $row['renewal']; //회원의 리뉴얼 날짜

    //회원이 전산 사용료를 더 낼게 있는 지 체크
    if ($totalVMG >= $usageFee) { // 회원VMG >= 전산사용료
        echo "통과";
    } else {
        if ($totalVMC >= $all_rate) { //회원이 가지고있는 vmc >= 일일수당의 30%
            // total_ck = 총VMG + 직급별 일일수당의 30%
            $total_ck = $totalVMG + $all_rate;
            // enogh = 총VMG - 직급별 전산사용료
            $enough = $total_ck-$usageFee;
            // 회원이 가지고 있는 총 VMG > 직급별 전산사용료
            if ($total_ck > $usageFee) {
                // 직급별 일일수당 - 있는만큼만
                $temp = $all_rate - $enough;
                echo $temp . "모자란만큼만";
            }else{
                $temp = $all_rate;
            }
        } else { // 회원이 가지고 있는 vmc가 일일수당의 30%보다 작을때
            // 회원이가지고 있는 vmc만 빼감
            $temp = $totalVMC;
        }
        mysql_query("insert into dayPoint(mb_id,VMC,VMG, `date`, way) values('$mb_id',-$temp, $temp, now(),'vmgMove');");
        mysql_query("update g5_member set VMG = (VMG+$temp), VMC = (VMC-$temp) where mb_id='$mb_id';");
    }
    usageFee_chk($mb_id,$renewal, $totalVMG); // 회원별 1,2,3,4개월의 전산사용료 내는 날짜 체크
}


//전산사용료 내는 날짜 체크
function usageFee_chk($mb_id,$user_re, $totalVMG){ //$user_re: 회원의 리뉴얼 날짜, $totalVMG : 회원의 VMG총액
    $today = date('Y-m-d'); // 오늘날짜

    for ($i = 1; $i < 5; $i++) {
        // 리뉴얼 날짜 1,2,3,4개월을 구하기 위한 코드
        $re_day = "select date_add('$user_re', interval +{$i} month ) as re_day";
        $result = mysql_query($re_day);
        $row = mysql_fetch_array($result);
        $re_day = $row['re_day'];

        //4개월의 경우 +1day를 해주기 위한 코드
        $str_now = strtotime($re_day . '+1 day');
        $re_days = date("Y-m-d", $str_now);


        if ($today <= $re_days) {
            echo $i . "개월차" . "<br>";
            echo $re_days . "하루더하기<br>";
            echo $today . "오늘날짜<br>";
            echo $re_day . "리뉴<br>";
            echo $user_re . "원리뉴<br>";

            $sql = "insert into dayPoint(mb_id,VMG, `date`, way) values('$mb_id', -{$totalVMG}, now(),'usageFee');";
            $sql2 = "update g5_member set VMG = 0 where mb_id='$mb_id';";

            if ($i != 4 && $today == $re_day) { // 4개월이 아니고 && 1,2,3개월의 리뉴얼 날짜와 오늘 날짜가 같으면
                echo $i . " 개월 입니다.";
                mysql_query($sql);
                mysql_query($sql2);
                break;
            } else if ($i == 4 && $today == $re_days) { //4개월차이고 && 4개월+1day의 날짜와 오늘 날짜가 같으면
                echo "<br><br><br><br>";
                echo "마지막달 입니다.";
                mysql_query($sql);
                mysql_query($sql2);
                break;
            } else {
                echo "무쓸모";
                break;
            }
        }
    }
}
?>
