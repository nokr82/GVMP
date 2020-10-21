<?php
    include_once('./dbConn.php');
    set_time_limit(0);
    
    // VM이 리뉴얼 시작 날짜가 됐을 때 인원수 카운팅 -1 시키는 로직

    $re = mysql_query("select * from g5_member where renewal = date_add( date_add( curdate(), interval -4 month ), interval -1 day )");
    
    
    while( $row = mysql_fetch_array($re) ) {
        if( date('d', strtotime($row["renewal"])) == '31' && date('d') == '01' ) // renewal 일자가 31일 때 리뉴얼이 안 끝났다로 판별
            continue;

        mysql_query("insert into minusCountListTBL set mb_id = '{$row["mb_id"]}'");
    }
    
    
    
    
    // 이정현 : 예를들어 리뉴얼 값이 2020-05-31일 경우 2020-06-30까지 VM 기간임.
    // 과거에 2020-05-31일 경우 2020-07-01까지 VM 기간이였던 시절에 넣었던 로직 같다.
    // 현재는 VM 한달 기간에 기준이 첫번 째 줄에 적은대로 바껴서 밑에는 주석처리 함.
    // 2020-07-02 작성일
    
    // 아래 로직은 VM가입 또는 리뉴얼 날짜가 31일 일 때 처리하는 로직입니다.
//    if( date('d') == '02' && (date('m') == '02' || date('m') == '03' || date('m') == '05' || date('m') == '07' || date('m') == '09' || date('m') == '10' || date('m') == '12') ) {
//         - 2월 3월 5월 7월 9월 10월 12월 일 때
//         - 오늘 일자가 2일일 때
        
//        $re = mysql_query("select * from g5_member where renewal = '". date("Y-m-31", strtotime("-5 month", time())) ."'");
//        while( $row = mysql_fetch_array($re) ) {
//            mysql_query("insert into minusCountListTBL set mb_id = '{$row["mb_id"]}'");
//        }
//    }
?>