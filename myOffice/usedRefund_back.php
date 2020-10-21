<?php
    include_once ('./dbConn.php');
    
    // 중고장터 물품 결제한 것을 관리자가 환불 요청한 것을 처리하는 로직
    
    if( $_POST["wr_id"] == "" ) {
        echo "false";
        exit();
    }
    
    $gwuRow = mysql_fetch_array(mysql_query("select * from g5_write_used where wr_id = " . $_POST["wr_id"])) or die("false");
    
    mysql_query("update g5_write_used set saleStatus = '환불완료' where wr_id = " . $_POST["wr_id"]) or die("false");
    mysql_query("update g5_member set VMP = VMP + {$gwuRow["price"]} where mb_id = '{$gwuRow["buyer_id"]}'") or die("false");
    mysql_query("insert into dayPoint set mb_id = '{$gwuRow["buyer_id"]}', VMC=0,VMR=0,VMP={$gwuRow["price"]},VMM=0,VMG=0,V=0,VCash=0,VPay=0,bizMoney=0,date=NOW(),way='usedRefund;{$gwuRow["wr_subject"]}'");
    
    echo "true";
?>