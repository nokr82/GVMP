<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');
    
    // 관리자가 광고를 승인 또는 거절하는 것을 백엔드 처리하는 로직
    
    $dbName = "";
    if( $_POST["type"] == "image" ) {
        $dbName= "imageAdListTBL";
    } else if( $_POST["type"] == "text" ) {
        $dbName= "textAdListTBL";
    }
    
    $sql = "update {$dbName} set ok = '{$_POST["ok"]}', comment = '{$_POST["comment"]}' where no = {$_POST["no"]}";
    mysql_query($sql) or die("false");
    
    echo "true";
?>