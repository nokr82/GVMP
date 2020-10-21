<?php
    include_once('./dbConn.php');
    
    // 넘겨받은 ID의 이름이 뭔지 알려주는 코드. 회원가입시 추천인 검색용.
    
    $row = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$_POST['mb_id']}'"));
    
    if( $row['mb_name'] == "" ) {
        echo "false";
    } else {
        echo $row['mb_name'];
    }
    
    


?>
