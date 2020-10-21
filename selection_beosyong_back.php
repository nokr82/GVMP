<?php
    include_once ($_SERVER['DOCUMENT_ROOT'].'/beosyong/dbConn.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');

    // 블로그 마케팅 선정처리를 하는 로직
    
    
    $_POST["mb_id"] = trim($_POST["mb_id"]);
    $_POST["wr_id"] = trim($_POST["wr_id"]);
    
    if( $_POST["mb_id"] == "" || $_POST["wr_id"] == "" ) {
        echo "false"; exit();
    }
    
    
    $row = mysql_fetch_array(mysql_query("select * from blogApplyListTBL where mb_id = '{$_POST["mb_id"]}' and wr_id = {$_POST["wr_id"]}"));
    if( $row["wr_id"] == "" ) {
        echo "false"; exit();
    }
    
    mysql_query("update blogApplyListTBL set selection = 'Y' where mb_id = '{$_POST["mb_id"]}' and wr_id = {$_POST["wr_id"]}") or die("false");
    
    echo "true";
?>
