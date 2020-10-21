<?php
//    include_once('./_common.php');
include_once('./dbConn.php');

//0210동우수정 직급변경함수
include_once('./change_rank_func.php');

set_time_limit(0);

$result = mysql_query("select * from g5_member");

while ($row = mysql_fetch_array($result)) {
    $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row['renewal']}', interval +4 month) AS date"));
    $dateCheck_2 = mysql_fetch_array(mysql_query("SELECT date_add('{$row['renewal']}', interval +5 month) AS date"));
    $dateCheck_1_1 = mysql_fetch_array(mysql_query("SELECT date_add(date_add('{$row['renewal']}', interval +4 month), interval +1 day) AS date"));
    $timestamp1 = $dateCheck_1["date"];
    $timestamp2 = $dateCheck_2["date"];
    $timestamp3 = $dateCheck_1_1["date"];
    $timenow = date("Y-m-d");

    $str_now = strtotime($timenow);
    $str_timestamp1 = strtotime($timestamp1);
    $str_timestamp2 = strtotime($timestamp2);
    $str_timestamp3 = strtotime($timestamp3);

    $var_date; // 리뉴얼 날짜 또는 데일리 수당 지급 날짜로 사용할 변수
    $preDay = date("Y-m-d", strtotime($timenow . "-1 day")); // 어제 날짜

    
    
    $money = 2080000; // VM 리뉴얼 금액
    $money2 = 20000; // SM 리뉴얼 금액
    $cashback = 16000; // SM 리뉴얼시 추천인에게 캐시백될 금액

    
    if ($row['accountType'] == "VM" && $str_now >= $str_timestamp1 && $str_now <= $str_timestamp2) { // VM회원의 리뉴얼 유예기간에 해당되면 참
        $var_date = $timenow;
        if (($row['accountRank'] == "MASTER" || $row['accountRank'] == "Double MASTER" || $row['accountRank'] == "Triple MASTER") && $str_now == $str_timestamp1) {
            // 마스터 또는 더불 마스터일 경우 돈 차감 없이 리뉴얼 시키기
            // 이 조건이 충족했다는 것은 아직 인액티브 상태가 된게 아니기 때문에 인원수 증가하는 로직은 돌리지 않고 리뉴얼 날짜만 조정
            mysql_query("update g5_member set renewal = '{$var_date}' where mb_id = '{$row['mb_id']}'");
            mysql_query("insert into dayPoint set mb_id = '{$row['mb_id']}', VMC = 0, VMR = 0, VMP = 0, date = NOW(), way = 'autoPass'");
        } else {
            $POINTSUM = (int)$row['VMG'] + (int)$row['VMC'] + (int)$row['VMP'] + (int)$row['VMR']; //현재 각 포인트의 합계를 구함
            if ($POINTSUM >= $money) {
                $cVMC = $row['VMC'];
                //금고 처리 로직
                if ($row['VMG'] > 0) {
                    if ($row['VMG'] < $money) {
                        $cVMG = 0;
                        $TVMG = $row['VMG'];
                    } else {
                        $cVMG = $row['VMG'] - $money;
                        $TVMG = $money;
                    }
                    mysql_query("update g5_member set VMG = '{$cVMG}', renewal = '{$var_date}' where mb_id = '{$row['mb_id']}'");
                    mysql_query("insert into dayPoint set mb_id = '{$row['mb_id']}', VMG = -'{$TVMG}', VMR = 0, VMP = 0, date = NOW(), way = 'autoRenewal'");
                    $money = $money - $TVMG;
                }
                //금고 처리 로직

                if ($row['VMC'] > $money && $money) {
                    $cVMC = $row['VMC'] - $money;
                    mysql_query("update g5_member set VMC = '{$cVMC}', renewal = '{$var_date}' where mb_id = '{$row['mb_id']}'");
                    mysql_query("insert into dayPoint set mb_id = '{$row['mb_id']}', VMC = -{$money}, VMR = 0, VMP = 0, date = NOW(), way = 'autoRenewal'");

                } else if ($row['VMP'] >= $money - $row['VMC'] && $money) {
                    $cVMC = 0;
                    $cVMP = $row['VMP'] - ($money - $row['VMC']);
                    mysql_query("update g5_member set VMC = '{$cVMC}', VMP = '{$cVMP}', renewal = '{$var_date}' where mb_id = '{$row['mb_id']}'");
                    mysql_query("insert into dayPoint set mb_id = '{$row['mb_id']}', VMC = -{$row['VMC']}, VMR = 0, VMP = -" . ($money - $row['VMC']) . ", date = NOW(), way = 'autoRenewal'");

                } else if ($row['VMR'] >= ($money - $row['VMC'] - $row['VMP']) && $money) {
                    $cVMC = 0;
                    $cVMP = 0;
                    $cVMR = $row['VMR'] - ($money - $row['VMC'] - $row['VMP']);
                    mysql_query("update g5_member set VMC = '{$cVMC}', VMP = '{$cVMP}', VMR = '{$cVMR}', renewal = '{$var_date}' where mb_id = '{$row['mb_id']}'");
                    mysql_query("insert into dayPoint set mb_id = '{$row['mb_id']}', VMC = -{$row['VMC']}, VMR = -" . ($money - $row['VMC'] - $row['VMP']) . ", VMP = -{$row['VMP']}, date = NOW(), way = 'autoRenewal'");

                }
                if ($str_now > $str_timestamp1) { // 유예기간이 시작된 후 리뉴얼이면 참
                    mysql_query("insert into plusCountListTBL set mb_id = '{$row['mb_id']}'"); // VM 카운팅 리스트(증가)
                }
            }
        }


    } else if ($row['accountType'] == "VM" && $str_now > $str_timestamp2) { // VM회원의 리뉴얼 유예기간이 끝났다면 참
        mysql_query("update g5_member set accountType = 'CU', accountRank = 'CU' where mb_id = '{$row['mb_id']}'");
        //동우추가 0210 직급변경함수
        rank_com($row['userid'],'CU',$row['accountRank']);
    } else if ($row['accountType'] == "SM" && $str_now >= $str_timestamp1 && $str_now <= $str_timestamp2) { // SM회원의 리뉴얼 유예기간에 해당되면 참
        $var_date = $timenow;


        if ($row['VMC'] > $money2) {
            $cVMC = $row['VMC'] - $money2;

            mysql_query("update g5_member set VMC = '{$cVMC}', renewal = '{$var_date}' where mb_id = '{$row['mb_id']}'");
            mysql_query("insert into dayPoint set mb_id = '{$row['mb_id']}', VMC = -{$money2}, VMR = 0, VMP = 0, date = NOW(), way = 'smAutoRenewal'");

            $row2 = mysql_fetch_array(mysql_query("select * from genealogy where mb_id = '{$row['mb_id']}'"));
            $row3 = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$row2['recommenderID']}'"));
            $now_temp = strtotime(date("Y-m-d"));
            $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row3['renewal']}', interval +4 month) AS date"));
            $timestamp_temp = strtotime($dateCheck_1["date"]); // 추천인 VM 기간 마지막 일

            if (!($timestamp_temp < $now_temp)) { // 참이면 VM 기간
                mysql_query("update g5_member set VMP = VMP + {$cashback} where mb_id = '{$row3['mb_id']}'"); // 추천인에게 VMP 포인트로 캐시백
                mysql_query("insert into dayPoint set mb_id = '{$row3['mb_id']}', VMC=0, VMR=0, VMP={$cashback}, date=NOW(), way='smAutoRenewalVM;{$row['mb_id']}'");
            }
        } else if ($row['VMP'] >= $money2 - $row['VMC']) {
            $cVMC = 0;

            $cVMP = $row['VMP'] - ($money2 - $row['VMC']);
            mysql_query("update g5_member set VMC = '{$cVMC}', VMP = '{$cVMP}', renewal = '{$var_date}' where mb_id = '{$row['mb_id']}'");
            mysql_query("insert into dayPoint set mb_id = '{$row['mb_id']}', VMC = -{$row['VMC']}, VMR = 0, VMP = -" . ($money2 - $row['VMC']) . ", date = NOW(), way = 'smAutoRenewal'");

            $row2 = mysql_fetch_array(mysql_query("select * from genealogy where mb_id = '{$row['mb_id']}'"));
            $row3 = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$row2['recommenderID']}'"));
            $now_temp = strtotime(date("Y-m-d"));
            $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row3['renewal']}', interval +4 month) AS date"));
            $timestamp_temp = strtotime($dateCheck_1["date"]); // 추천인 VM 기간 마지막 일

            if (!($timestamp_temp < $now_temp)) { // 참이면 VM 기간
                mysql_query("update g5_member set VMP = VMP + {$cashback} where mb_id = '{$row3['mb_id']}'"); // 추천인에게 VMP 포인트로 캐시백
                mysql_query("insert into dayPoint set mb_id = '{$row3['mb_id']}', VMC=0, VMR=0, VMP={$cashback}, date=NOW(), way='smAutoRenewalVM;{$row['mb_id']}'");
            }
        } else if ($row['VMR'] >= ($money2 - $row['VMC'] - $row['VMP'])) {
            $cVMC = 0;
            $cVMP = 0;
            $cVMR = $row['VMR'] - ($money2 - $row['VMC'] - $row['VMP']);

            mysql_query("update g5_member set VMC = '{$cVMC}', VMP = '{$cVMP}', VMR = '{$cVMR}', renewal = '{$var_date}' where mb_id = '{$row['mb_id']}'");
            mysql_query("insert into dayPoint set mb_id = '{$row['mb_id']}', VMC = -{$row['VMC']}, VMR = -" . ($money2 - $row['VMC'] - $row['VMP']) . ", VMP = -{$row['VMP']}, date = NOW(), way = 'smAutoRenewal'");

            $row2 = mysql_fetch_array(mysql_query("select * from genealogy where mb_id = '{$row['mb_id']}'"));
            $row3 = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$row2['recommenderID']}'"));
            $now_temp = strtotime(date("Y-m-d"));
            $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row3['renewal']}', interval +4 month) AS date"));
            $timestamp_temp = strtotime($dateCheck_1["date"]); // 추천인 VM 기간 마지막 일

            if (!($timestamp_temp < $now_temp)) { // 참이면 VM 기간
                mysql_query("update g5_member set VMP = VMP + {$cashback} where mb_id = '{$row3['mb_id']}'"); // 추천인에게 VMP 포인트로 캐시백
                mysql_query("insert into dayPoint set mb_id = '{$row3['mb_id']}', VMC=0, VMR=0, VMP={$cashback}, date=NOW(), way='smAutoRenewalVM;{$row['mb_id']}'");
            }
        }
    } else if ($row['accountType'] == "SM" && $str_now >= $str_timestamp2) { // SM회원의 리뉴얼 유예기간이 끝났다면 참
        mysql_query("update g5_member set accountType = 'CU', accountRank = 'CU' where mb_id = '{$row['mb_id']}'");
    }
}

mysql_close($connect);

echo "완료";
?>