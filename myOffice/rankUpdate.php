<?php
include_once('./dbConn.php');
include_once('./getMemberInfo.php');
//0121동우수정 추가로직만 걸리는시간 1분 30초 동우추가 직급변경함수
include_once('./change_rank_func.php');


set_time_limit(0);

// 본 소스코드는 전체 회원들에 하위 조직도 인원 수에 따라
// 직급을 부여하게 된다.

// MASTER - 직접 추천 3명
// Double MASTER - 1팀 6명    2팀 : 6명
// Triple MASTER - 1팀 20명    2팀 : 20명
// 1 STAR - 1팀 40명    2팀 : 40명
// 2 STAR - 1팀 75명    2팀 : 75명
// 3 STAR - 1팀 150명    2팀 : 150명
// 4 STAR - 1팀 250명    2팀 : 250명
// 5 STAR - 1팀 375명    2팀 : 375명
// AMBASSADOR - 1팀 600명    2팀 : 600명
// Double AMBASSADOR - 1팀 1000명    2팀 : 1000명
// Triple AMBASSADOR - 1팀 2000명    2팀 : 2000명
// Crown AMBASSADOR - 1팀 3000명    2팀 : 3000명


$result = mysql_query("select * from g5_member where mb_id != 'admin' and accountType = 'VM'");

$now_temp = strtotime(date("Y-m-d"));



while ( $row = mysql_fetch_array($result) ) {
    //직급강제 지정은 제외!!! 동우수정0715
    if ($row['rank_yn'] == 'Y'){
        continue;
    }

    $dateCheck_2 = mysql_fetch_array(mysql_query("SELECT date_add('{$row['renewal']}', interval +5 month) AS date"));
    $timestamp_temp = strtotime($dateCheck_2["date"]);

    // 2018.04.13 버그 발견
    // 회원이 리뉴얼 날짜가 2/12일로 설정했을 때 CU로 강등되는 로직이 동작하지 않았고
    // 확인 시 리뉴얼 날짜 2달 후 날짜가 4/13일로 나왔음
    // date 함수는 시간을 기준으로 계산하는 로직으로 동작하여
    // 시간 계신이 정확하지 않는 것으로 판단되며
    // 우선 넘어가기로 함

    // 회원 리뉴얼 유예기간이 지났으면 계정을 CU로 강등시키는 로직
    if( $timestamp_temp < $now_temp ) { // 리뉴얼 유예기간이 지났으면 참
        rank_com($row['mb_id'],'CU',$row['accountRank']);
        // CU로 강등 시키는 로직
        mysql_query("update g5_member set accountType = 'CU', accountRank = 'CU' where mb_id = '".$row['mb_id']."'");
        continue;
    }

    $rank = rankCheck($row['mb_id']);

    if ($rank != $row['accountRank']){
        //랭크체크및 직급변경이력 인서트함수
        rank_com($row['mb_id'],$rank,$row['accountRank']);
        mysql_query("update g5_member set accountRank = '".$rank."' where mb_id = '".$row['mb_id']."'");
    }
//동우변경
//    mysql_query("update g5_member set accountRank = '".$rank."' where mb_id = '".$row['mb_id']."'");

    $result_r = mysql_query("select * from rankCheck where mb_id = '{$row['mb_id']}'");

    // 최고로 달성했던 직급이 무엇인지 저장하는 로직 Table : rankCheck
    $row_r = mysql_fetch_array( $result_r );

    if( mysql_num_rows($result_r) == 0 ) {
        // 기존에 넣은 직급 정보가 없으면 insert 시키기
        mysql_query("insert into rankCheck values( null, '{$row['mb_id']}', '{$rank}', now() )");
    }
    else {
        // 기존에 넣은 직급 정보가 있을 때 직급이 올라갔으면 업데이트 시키기
        $row_1 = mysql_fetch_array(mysql_query("select * from jobAllowance where accountRank = '{$row_r['rankAccount']}'")); // 원래 rankCheck에 저장 되어 있던 직급
        $row_2 = mysql_fetch_array(mysql_query("select * from jobAllowance where accountRank = '{$rank}'")); // 바뀔 직급

        // 마스터 직급이 jobAllowance에 수당이 0원으로 돼 있어 직급 높낮이 비교를 위해 10000이라는 값을 넣는 작업
//        if( $row_1['accountRank'] == 'MASTER' ) {
//            $row_1['Allowance'] = "10000";
//        }
//        if( $row_2['accountRank'] == 'MASTER' ) {
//            $row_2['Allowance'] = "10000";
//        }
        //////////////////////////////////////////////////////////////////////////
        //동우 0619문제 높은직급에서 낮아지면 업데이트안됨 고로 마이오피스 메인 직급반영 안됨
        if( $row_1['Allowance'] < $row_2['Allowance'] ) {
            mysql_query("update rankCheck set rankAccount = '{$rank}', date = now() where mb_id = '{$row['mb_id']}'");
        }
    }
}


echo "완료";
?>