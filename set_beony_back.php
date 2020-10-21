<?php
    include_once ($_SERVER['DOCUMENT_ROOT'].'/beosyong/dbConn.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');

    // 버니 커미션 금액 설정을 처리하는 로직
    
    $_POST["set_bn_point"] = trim($_POST["set_bn_point"]);
    $_POST["set_bn_point"] = preg_replace("/[^0-9]/", "",$_POST["set_bn_point"]);
    
    if( $_POST["set_bn_point"] == "0" || $_POST["set_bn_point"] == "" ) {
        echo "false"; exit();
    }
    
    mysql_query("update bunnyConfTBL set value = '{$_POST["set_bn_point"]}' where name = 'commission'") or die("false");
    
    echo "true";
?>
