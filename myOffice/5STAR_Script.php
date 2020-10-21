<?php
    include_once('./dbConn.php');
    
    // 5 STAR 달성 보너스를 지급하는 로직
    // 매일 하루에 1번 새벽에 가동 됨
    
    define(VMC, 1000000);
    
    $re = mysql_query("SELECT 
                            *
                        FROM
                            rank_history
                        WHERE
                            change_rank = '5 STAR'
                                AND first_check = 'Y'
                                AND LEFT(datetime, 10) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL - 1 DAY), '%Y-%m-%d') order by mb_id");
    
    while( $row = mysql_fetch_array($re) ) {
        $dayCheckRow = mysql_fetch_array(mysql_query("select * from dayPoint where mb_id = '{$row["mb_id"]}' and way = '5STARBonus'"));
        
        if( $dayCheckRow["mb_id"] != "" ) {
            continue; // 조건 참이면 5 STAR 보너스를 한번이라도 받은 적이 있는 회원이다.
        }
        
        
        mysql_query("insert into dayPoint set mb_id = '{$row["mb_id"]}', VMC=".VMC.", date=now(), way='5STARBonus'");       
        mysql_query("update g5_member set VMC = VMC + ".VMC." where mb_id = '{$row["mb_id"]}'");
    }
    


?>