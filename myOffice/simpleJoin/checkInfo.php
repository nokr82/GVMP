<?php
    $cssCheck = false; // _common.php에 인클루드 되는 CSS 적용 안 되게 하기 위한 변수
    include_once ('./_common.php');
    include_once ('../dbConn.php');
    
    function add_hyphen($tel) {
        $tel = preg_replace("/[^0-9]/", "", $tel);    // 숫자 이외 제거
        if (substr($tel,0,2)=='02')
            return preg_replace("/([0-9]{2})([0-9]{3,4})([0-9]{4})$/", "\\1-\\2-\\3", $tel);
        else if (strlen($tel)=='8' && (substr($tel,0,2)=='15' || substr($tel,0,2)=='16' || substr($tel,0,2)=='18'))
            // 지능망 번호이면
            return preg_replace("/([0-9]{4})([0-9]{4})$/", "\\1-\\2", $tel);
        else
            return preg_replace("/([0-9]{3})([0-9]{3,4})([0-9]{4})$/", "\\1-\\2-\\3", $tel);
    }

    

    if( $_POST["type"] == "mb_hp" ) {
        $row = mysql_fetch_array(mysql_query("select * from g5_member where mb_hp = '". add_hyphen(trim($_POST["value"]))."'"));
        if( $row["mb_hp"] != "" ) {
            echo "false"; exit();
        } else {
            echo "true"; exit();
        }
    } else if( $_POST["type"] == "mb_email" ) {
        $row = mysql_fetch_array(mysql_query("select * from g5_member where mb_email = '".trim($_POST["value"])."'"));
        if( $row["mb_email"] != "" ) {
            echo "false"; exit();
        } else {
            echo "true"; exit();
        }
    } else if( $_POST["type"] == "mb_bank" ) {
        $row = mysql_fetch_array(mysql_query("select * from g5_member where accountNumber = '".trim($_POST["value"])."'"));
        if( $row["accountNumber"] != "" ) {
            echo "false"; exit();
        } else {
            echo "true"; exit();
        }
    }
    
?>

