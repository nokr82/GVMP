<?php
include_once('./_common.php');



if (isset($_SESSION['ss_mb_reg']))
    $mb = get_member($_SESSION['ss_mb_reg']);

    $conn = mysql_connect("192.168.0.30", "root", "Neungsoft1!");
    mysql_select_db("gyc5", $conn);
    
    mysql_query("set session character_set_connection=utf8;");
    
    mysql_query("set session character_set_results=utf8;");
    
    mysql_query("set session character_set_client=utf8;");
    
    $result = mysql_query("select mb_hp from g5_member where mb_id = '{$mb['mb_id']}'");
    
    $row = mysql_fetch_row($result);
    
    $loginID = $mb['mb_id'];
    $mobile = $row[0];


// 회원정보가 없다면 초기 페이지로 이동
if (!$mb['mb_id'])
    goto_url(G5_URL);




$g5['title'] = '회원가입 완료';
include_once('./_head.php');
include_once($member_skin_path.'/register_result.skin.php');
include_once('./_tail.php');
?>

<input type="hidden" id="test1" value="<?=$loginID?>">
<input type="hidden" id="test2" value="<?=$mobile?>">   
<script  src="http://code.jquery.com/jquery-latest.min.js"></script>
<!--<script>
    	var data = "strData=[VMP] 회원가입을 축하드립니다. 아이디는 " + $('#test1').val() + "입니다.&strTelList=" + $('#test2').val() + ";&strCallBack=07074515121";

         $.ajax({
             url:'/sms/sms.php',
             type:'POST',
             data: data
         });
</script>-->

