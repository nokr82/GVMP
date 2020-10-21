<?php
include_once('./dbConn.php');

mysql_query("set session character_set_connection=utf8");
mysql_query("set session character_set_results=utf8");
mysql_query("set session character_set_client=utf8");

$result = mysql_query("SELECT * FROM g5_shop_order");

while($row = mysql_fetch_array($result)) {
    
    if( $row['od_buyCheck'] != Y ){     
        $od_id = $row['od_id'];
        $buyCheckTime = $row['od_completionTime'];                   
        $d_5 = date("Y-m-d",strtotime ("+5 days")); // 5일 전 날짜
        if ($buyCheckTime > $d_5){                     
            mysql_query("update g5_shop_order set od_buyCheck = 'Y' where od_id = '{$od_id}'");
            mysql_query("update g5_shop_order set od_buyTime = now() where od_id = '{$od_id}'");
        }        
    }
       
}

?>