<?php
    include_once ('./dbConn.php');
    
    // 광고를 삭제 처리하는 로직
    
    $dbName = "";
    $dbName2 = "";
    if( $_POST["type"] == "image" ) {
        $dbName = "imageAdListTBL";
        $dbName2 = "imageAdViewTBL";
    } else if( $_POST["type"] == "text" ) {
        $dbName = "textAdListTBL";
        $dbName2 = "textAdViewTBL";
    }
    
    
    
    mysql_query("delete from {$dbName} where no = {$_POST["no"]}") or die("false");
    mysql_query("delete from {$dbName2} where adNo = {$_POST["no"]}") or die("false");
    
    echo "true";
?>
