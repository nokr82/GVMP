<?php
    include_once ('./dbConn.php');
    // 넘겨 받은 계좌 정보가 인증 계좌 목록 DB에 있는 지 체크 여부 알려주는 코드
    // 계좌 인증을 했는 지 안 했는 지 여부 체크하기 위함
    
    
    
    // 계좌 인증 여부 체크 하기
    $row = mysql_fetch_array(mysql_query("select * from accountCheck where time >= date_sub(now(), interval 3 minute) and bankName = '{$_POST['bankName']}' and accountNumber = '{$_POST['accountNumber']}'"));
    if( $row['bankName'] == "" ) {
        echo "false";
    } else {
        echo "true";
    }


?>