<?php
    include_once('./_common.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');
    
    $_POST['n'];
    $_POST['productName'];
    $_POST['money'];
    $_POST['commission'];
    
    $i = 0;
    while( true ) {
        $i++;
        
        if( isset( $_POST['n' . $i] ) ) {
            if( $_POST['productName' . $i] == "" || $_POST['money' . $i] == "" || $_POST['commission' . $i] == "" ) {
                // 어느 하나의 값이라도 값이 없으면 반복문 넘어가기
                continue;
            }
            
            if( $_POST['productName'.$i] == "삭제" || $_POST['money'.$i] == "삭제" || $_POST['commission'.$i] == "삭제" ) {
                $row = mysql_fetch_array(mysql_query("select * from orderList where n = {$_POST['n' . $i]}"));
                mysql_query("delete from orderList where n = {$_POST['n' . $i]}");
                
                mysql_query("update g5_member set VMP = VMP - {$row['commission']} where mb_id = '{$row['mb_id']}'");
            } else {
                $row = mysql_fetch_array(mysql_query("select * from orderList where n = {$_POST['n' . $i]}"));
                
                $temp = mysql_fetch_array(mysql_query("select * from dayPoint where mb_id = '{$row['mb_id']}' and date like '{$_POST['orderDate' . $i]}%' and VMC=0 and VMR=0 and VMP= {$row['commission']} limit 1"));
                mysql_query("update dayPoint set VMP = ".str_replace(',', '', $_POST['commission'.$i])." where no = {$temp['no']}" );
                
                
                
                
                $commission = preg_replace("/[^\d]/","",$_POST['commission'.$i]);
                $commission_val;
                if( ! ($row['commission'] < 0) ) { // 참이면 양수.
                    $commission_val = preg_replace("/[^\d]/","",$row['commission']) - $commission;
                } else { // 거짓이면 음수.
                    $commission_val = $commission - preg_replace("/[^\d]/","",$row['commission']);
                }
                
                $money = preg_replace("/[^\d]/","",$_POST['money'.$i]);
                $money_val;
                if( ! ($row['money'] < 0) ) { // 참이면 양수.
                    $money_val = preg_replace("/[^\d]/","",$row['money']) - $money;
                } else { // 거짓이면 음수.
                    $money_val = $money - preg_replace("/[^\d]/","",$row['money']);
                }
                mysql_query("update orderList set productName = '{$_POST['productName'.$i]}', commission = commission - {$commission_val}, money = money - {$money_val} where n = {$_POST['n' . $i]}");
                mysql_query("update g5_member set VMP = VMP - {$commission_val} where mb_id = '{$row['mb_id']}'");
            }
        } else {
            break;
        }
    }

echo "<script>
    window.onload = function() {
        alert('수정되었습니다.');
        window.location.href = '{$_SERVER["HTTP_REFERER"]}';
    }
</script>";
?>