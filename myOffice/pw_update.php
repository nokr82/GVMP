<?php 
include_once('./_common.php');
include_once('./dbConn.php');

if($is_guest)// 로그인 안 했을 때 로그인 페이지로 이동
    header('Location: /bbs/login.php');

    $pw=get_encrypt_string($_POST['pw_confirm']);
    
    mysql_query("set session character_set_connection=utf8");
    mysql_query("set session character_set_results=utf8");
    mysql_query("set session character_set_client=utf8");
    
    $result = mysql_query("UPDATE g5_member
                        SET mb_password='{$pw}'
                         WHERE mb_id='{$member['mb_id']}'");
    mysql_close($connect); 
    header('Location: /myOffice');
?>