<?php
    include_once('./dbConn.php');
    include_once('./dbConn2.php');
    include_once('./getMemberInfo.php');
    
    
    mysql_query("update g5_member set team1=0, team2=0");
    mysqli_multi_query($connection, "call sp_tree_team('00000001')") or die("ERROR1");
    
    
    
    
    
    
    
    // 튜닝으로 인해 기존 사용 로직 주석. 위에 로직은 프로시저로 처리함
//    
//    $start = get_time();
//    set_time_limit(0);
//    
//    // 모든 VM의 인원수를 새어 team1, team2 컬럼 값으로 다시 세팅하는 로직
//
//
////    mysql_query("update g5_member set team1=0, team2=0");
//    $result = mysql_query("select * from g5_member where mb_id != 'admin' and accountType = 'VM' order by mb_no desc");
//
//
//    $now_temp = strtotime(date("Y-m-d"));
//    
//    while ( $row = mysql_fetch_array($result) ) {
//        $timestamp_temp = strtotime(date("Y-m-d", strtotime($row['renewal'] . "+2 months")));
//        
//        // 회원 리뉴얼 유예기간이 지났으면 계정을 CU로 강등시키는 로직
//        if( $timestamp_temp < $now_temp ) { // 리뉴얼 유예기간이 지났으면 참
//            // CU로 강등 시키는 로직
////            mysql_query("update g5_member set accountType = 'CU', accountRank = 'CU' where mb_id = '".$row['mb_id']."'");
//            continue;
//        }
//
//        $count_1T = 0;
//        $count_2T = 0;
//        $teamCountArray = teamCount($row['mb_id'], true);
//        unset($mb_info_1T);
//        unset($mb_info_2T);
//        $mb_info_1T = array(); // 1Team의 하위 회원 정보 담을 배열
//        $mb_info_2T = array(); // 2Team의 하위 회원 정보 담을 배열
//        
//
////        mysql_query("update g5_member set team1 = {$teamCountArray[0]}, team2 = {$teamCountArray[1]} where mb_id = '{$row['mb_id']}'");
//        
//    }
//    
//    $end = get_time();
//    $time = $end - $start;
//    echo $time . 's<br>';
//
//
//    function get_time()
//    {
//        list($usec, $sec) = explode(" ", microtime());
//        return ((float)$usec + (float)$sec);
//    }
    
    echo "끝";
?>