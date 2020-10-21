<?php
    include_once ('./dbConn.php');
    
    // 이미지 광고 만들기를 처리하는 백엔드 로직
    
    
    $_POST["mb_id"] = trim($_POST["mb_id"]);
    $_POST["mb_ad_title"] = trim($_POST["mb_ad_title"]);
    $_POST["mb_company"] = trim($_POST["mb_company"]);
    $_POST["mb_money"] = preg_replace("/[^0-9]/", "",$_POST["mb_money"]) ;
    $_POST["fr_date"] = trim($_POST["fr_date"]);
    $_POST["to_date"] = trim($_POST["to_date"]);
    $_POST["mb_textarea"] = trim($_POST["mb_textarea"]);
    $_POST["mb_url"] = trim($_POST["mb_url"]);
    
    if( $_POST["mb_id"] == "" || $_POST["mb_ad_title"] == "" || $_POST["mb_company"] == "" || $_POST["mb_money"] == "" || $_POST["fr_date"] == "" || $_POST["to_date"] == "" || $_POST["mb_textarea"] == "" ) {
        echo "false"; exit();
    }
    
    if( $_POST["mb_money"] == "" ) {
        $_POST["mb_money"] = 0;
    }
    
    
    
    

    if( $_POST["modi"] == "Y" ) {
        // 업데이트
        mysql_query("update textAdListTBL set mb_id = '{$_POST["mb_id"]}', adName = '{$_POST["mb_ad_title"]}', companyName = '{$_POST["mb_company"]}', budget = {$_POST["mb_money"]}, frDate = '{$_POST["fr_date"]}', toDate = '{$_POST["to_date"]}', text = '{$_POST["mb_textarea"]}', url = '{$_POST["mb_url"]}' where no = {$_POST["no"]}") or die("false");
    } else {
        // 첫 등록
        mysql_query("insert into textAdListTBL set mb_id = '{$_POST["mb_id"]}', adName = '{$_POST["mb_ad_title"]}', companyName = '{$_POST["mb_company"]}', budget = {$_POST["mb_money"]}, frDate = '{$_POST["fr_date"]}', toDate = '{$_POST["to_date"]}', text = '{$_POST["mb_textarea"]}', url = '{$_POST["mb_url"]}'") or die("false");
    }
    
    echo "true";
    
    

?>