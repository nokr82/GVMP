<?php
include_once('./dbConn.php');
include_once('./dbConn2.php');

set_time_limit(0);

$week = array("일요일" , "월요일"  , "화요일" , "수요일" , "목요일" , "금요일" ,"토요일") ;

$weekday = $week[ date('w'  , strtotime("Now")  ) ] ;
if ($weekday=="일요일"){
    exit();
}



mysqli_multi_query($connection, "call SP_POINT()") or die(mysqli_error($connection)); // 수당 지급 프로시저




// 아래 수당 지급 로직 성능 저하로 인해 프로시저로 개발하여 주석처리 했습니다. 이정현
// 리뉴얼 날짜가 오늘이라면 수당 지급 안 되게 where 조건으로 막음. 이유 : 수당 지급은 어제꺼를 금일 지급하기 때문.
//$result = mysql_query("SELECT
//                            RE.*, CK.way
//                        FROM
//                            (SELECT
//                                A.*, B.Allowance
//                            FROM
//                                (SELECT
//                                MB.*
//                            FROM
//                                (SELECT
//                                *
//                            FROM
//                                g5_member AS a
//                            WHERE
//                                (a.accountType = 'VM'
//                                    AND a.accountRank != 'VM'
//                                    AND DATE_ADD(a.renewal, INTERVAL + 1 month) >= CURDATE())) AS MB
//                            LEFT JOIN (SELECT
//                                *
//                            FROM
//                                dayPoint
//                            WHERE
//                                (way = 'newRenewal' OR way = 'vmJoin2')
//                                    AND date like '".date("Y-m-d")."%') AS DP ON MB.mb_id = DP.mb_id
//                            WHERE
//                                (DP.way = '' OR DP.way IS NULL)) AS A
//                            INNER JOIN jobAllowance AS B ON A.accountRank = B.accountRank) AS RE
//                                LEFT JOIN
//                            (SELECT
//                                *
//                            FROM
//                                dayPoint AS CK
//                            WHERE
//                                CK.date like '".date("Y-m-d")."%'
//                                    AND (CK.way = 'dayPass' OR CK.way = 'autoVMC')
//                            GROUP BY CK.mb_id) AS CK ON RE.mb_id = CK.mb_id
//                        WHERE
//                            CK.way IS NULL");
//
//
//
//
//while($row = mysql_fetch_array($result)) {
//    if( $row['accountRank'] == "MASTER" || $row['accountRank'] == "Double MASTER" || $row['accountRank'] == "Triple MASTER" ) {
//            // 마스터 또는 더불 마스터 또는 트리플 마스터일 때 PASS TBL에 정보 기록하는 코드
//            mysql_query("insert into passTBL set mb_id = '{$row['mb_id']}', date = CURDATE(), accountRANK = '{$row['accountRank']}'");
//            mysql_query("insert into dayPoint set mb_id = '{$row['mb_id']}', VMC=0, VMR=0, VMP=0, date=NOW(), way = 'dayPass'");
//    }
//
//
//    if( $row['Allowance'] >= 1 ) {
//		$cVMC = $row['VMC'] + $row['Allowance'];
//		$cVMG = $row['VMG'];
//
//		mysql_query("update g5_member set VMC = VMC + {$row['Allowance']} where mb_id = '{$row['mb_id']}'");
//		mysql_query("insert into dayPoint(mb_id,VMC,date,way) value ('{$row['mb_id']}','{$row['Allowance']}',NOW(),'autoVMC')");
//                echo "{$row['mb_id']}에게 {$row['Allowance']}지급<br>";
//
//		/*금고에 16만원없을때 체우는 로직*/
//		if(get_VMG_check($row['mb_id'])) { //트리플마스터 이상만
//			if ($cVMG < 2080000) { //금고에 16만원 미만이 있을때
//				if($cVMC > 0){ //VMC가 현재 있다
//					$TVMG = 2080000 - $cVMG; //차액
//					$VMCCHK = $cVMC - $TVMG;
//					if ($VMCCHK < 0) { //모자른다
//						$TVMG = $cVMC; //전부다
//						$cVMC = 0;
//						$cVMG = $cVMG + $TVMG;
//					} else {
//						$cVMC = $cVMC - $TVMG;
//						$cVMG = $cVMG + $TVMG;
//					}
//					mysql_query("update g5_member set VMC = '{$cVMC}', VMG = '$cVMG' where mb_id = '{$row['mb_id']}'");
//					mysql_query("insert into dayPoint set mb_id = '{$row['mb_id']}', VMC = -{$TVMG}, VMG = +{$TVMG} , date = NOW(), way = 'vmgMove'");
//                                        echo "{$row['mb_id']}에게 {$TVMG} 금고 적립<br>";
//				}
//			}
//		}
//		/*금고에 16만원없을때 체우는 로직*/
//    }
//}







    // 아래 로직은 VMM을 적립시키는 로직입니다. 수당 지급이 전부 다 돌아가고 바로 VMM 적립을 수행.

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



// 전산 사용료 지불을 위한 VMC -> VMG 작업

//$g5_member_sql = "SELECT a.mb_id, a.mb_name, a.accountRank, a.accountType, a.renewal, a.VMC, a.VMG,j.usageFee, j.Allowance
//                FROM g5_member AS a,
//                     jobAllowance AS j
//                                WHERE a.accountRank = j.accountRank
//                                        AND a.accountType = 'VM'
//                                        AND a.accountRank != 'MASTER'
//                                        AND a.VMC > 0
//                                        AND DATE_ADD(a.renewal, INTERVAL +4 month) >= CURDATE()";
//$result = mysql_query($g5_member_sql);
//
//
//while ($row = mysql_fetch_array($result)) {
//
//
//    $mb_id = $row['mb_id'];
//    //$renewal = $row['renewal'];
//
//    $rate = 0.3; // 일일수당의 30%를 전산사용료로 지급
//    $usageFee = $row['usageFee']; // 직급별 전산사용료
//
//
//    // 회원의 일일수당 받은 이력이 있는지 확인 후 30%를 $totalVMC에 저장
//    $totalVMC = $row["Allowance"] * $rate;
//
//
//    // 금고총액 = 원래금고 총액+ 전산사용료
//    $vmg_total = $row['VMG'] + $totalVMC;
//    $renewal2 = $row['renewal'];
//    $myVMC= $row['VMC'];
//
//    echo "ddd";
//    // 전산사용료가 모자를 경우
//    if($myVMC < $totalVMC){
//        $sql = "insert into dayPoint(mb_id,VMC,VMG, `date`, way) values('$mb_id',-$myVMC,$myVMC, now(),'vmgMove');";
//        mysql_query($sql);
//
//        $sql = "update g5_member set VMC = (VMC-$myVMC), VMG = (VMG+$myVMC) where mb_id='$mb_id';";
//        mysql_query($sql);
//        continue;
//    }else if ($vmg_total > $usageFee ) {
//        // 회원이 전산사용료를 모두 채웠다면 통과
//        if ($usageFee == $row['VMG']) {
//            echo '통과';
//            continue;
//        }
//        // 금고에 전산사용료보다 적게 남았다면
//
//
//        echo '<br>' . $myVMC. '가진값';
//        echo '<br>' . $totalVMC . '30%';
//        $no_vmg = $vmg_total - $usageFee;
//        echo '<br>' . $no_vmg . '안내는값';
//        $yes_vmg = $totalVMC - $no_vmg;
//        echo '<br>' . $yes_vmg . '내는값';
//
//        $sql = "insert into dayPoint(mb_id,VMC,VMG, `date`, way) values('$mb_id',-$yes_vmg,$yes_vmg, now(),'vmgMove');";
//        mysql_query($sql);
////        echo '<br>' . $sql;
//
//        $sql = "update g5_member set VMC = (VMC-$yes_vmg), VMG = (VMG+$yes_vmg) where mb_id='$mb_id';";
//        mysql_query($sql);
//
////        echo '<br>' . $sql;
//
//        continue;
//    }
//
//
//    $sql = "insert into dayPoint(mb_id,VMC,VMG, `date`, way) values('$mb_id',-$totalVMC,$totalVMC, now(),'vmgMove');";
//    mysql_query($sql);
//    echo '<br>' . $sql;
//
//
//
//    $sql = "update g5_member set VMC = (VMC-$totalVMC), VMG = (VMG+$totalVMC) where mb_id='$mb_id';";
//    mysql_query($sql);
//
//    echo '<br>' . $sql;
//}


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




$g5_member_sql = "SELECT a.mb_id, a.mb_name, a.accountRank, a.accountType, a.renewal, a.VMC, a.VMG,j.usageFee, j.Allowance, j.Allowance*0.3 AS uses
                FROM g5_member AS a,
                     jobAllowance AS j
                                WHERE a.accountRank = j.accountRank
                                        AND a.accountType = 'VM'
                                        AND a.accountRank != 'MASTER'
                                        AND a.VMC > 0
                                        AND DATE_ADD(DATE_ADD(a.renewal, INTERVAL +4 month), interval 1 day) >= CURDATE();";
$result = mysql_query($g5_member_sql) or die(mysql_error($connect));


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
완료
