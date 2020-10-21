<?php
    include_once('./dbConn.php');
    
    function hyphen_hp_number($hp)
    {
        $hp = preg_replace("/[^0-9]/", "", $hp);
        return preg_replace("/([0-9]{3})([0-9]{3,4})([0-9]{4})$/", "\\1-\\2-\\3", $hp);
    }
    
    $_POST["mb_hp"] = trim($_POST["mb_hp"]);
    $_POST["accountHolder"] = trim($_POST["accountHolder"]);
    $_POST["accountNumber"] = trim($_POST["accountNumber"]);
    $_POST["mb_hp"] = hyphen_hp_number($_POST["mb_hp"]);
    
    
    if( $_POST["mb_hp"] != "" ) {
        $hpCheck = mysql_fetch_array(mysql_query("select * from g5_member where mb_hp = '{$_POST["mb_hp"]}'"));
        if( $hpCheck["mb_id"] != "" ) {
            echo "입력하신 연락처는 이미 사용 중입니다."; exit();
        } else {
            echo ""; exit();
        }
    }
    
    if( $_POST["accountHolder"] != "" && $_POST["accountNumber"] ) {
        $accCheck = mysql_fetch_array(mysql_query("select * from g5_member where accountHolder = '{$_POST["accountHolder"]}' and accountNumber = '{$_POST["accountNumber"]}'"));
        if( $accCheck["mb_id"] != "" ) {
            echo "입력하신 계좌정보는 이미 사용 중입니다."; exit();
        } else {
            echo ""; exit();
        }
    }


?>