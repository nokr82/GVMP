<?php
    include_once ('./dbConn.php');

    // 광고를 on off 시키는 것을 처리하는 백엔드 로직
    
    
    $dbName = "";
    if( $_POST["type"] == "text" ) {
        $dbName = "textAdListTBL";
    } else if( $_POST["type"] == "image" ) {
        $dbName = "imageAdListTBL";
    }
    
    $state = "";
    if( $_POST["check"] == "on" ) {
        $state = "Y";
    } else if( $_POST["check"] == "off" ) {
        $state = "N";
    }
    
    mysql_query("update {$dbName} set state = '{$state}' where no = {$_POST["no"]}") or die("false");
    
    echo "true";
    
?>

