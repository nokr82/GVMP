<?php

include_once('./inc/title.php');
include_once('./_common.php');
include_once('./dbConn.php');
include_once("./nameUpdate.php");


if ($is_guest)// 로그인 안 했을 때 로그인 페이지로 이동
    header('Location: /bbs/login.php');


$mb_zip1 = substr($_POST['mb_zip'], 0, 3);
$mb_zip2 = substr($_POST['mb_zip'], 3, 2);

mysql_query("set session character_set_connection=utf8");
mysql_query("set session character_set_results=utf8");
mysql_query("set session character_set_client=utf8");

$_POST['name'] = trim($_POST['name']);

$admin_ck = $_POST['admin_ck'];


if ($admin_ck != "") {
    alert($admin_ck);
}


$txt = "";
if ($_POST['name'] != "") { // 이름 변경을 할 때만 update 쿼리에 쿼리문 넣는 목적으로 조건문 판별. 이름 변경이 어떨때는 값이 넘어오고 어떨때는 안 넘어오기 때문에 넣음.
    $txt = "mb_name = '{$_POST['name']}',";

    nameUpdateFunc($_POST['name'], $member["mb_id"]);
}


$chk = false;

$modi_row = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$member["mb_id"]}'"));
$date = date("Y-m-d H:i:s");


$email = trim($_POST['email']);
$hp = trim($_POST['hp']);
$birth = trim($_POST['birth']);
$bankname = trim($_POST['bankName']);
$accountHolder = trim($_POST['accountHolder']);
$accountNumber = trim($_POST['accountNumber']);

$modi_email = $modi_row['mb_email'];





if ($email != $modi_email) {
    $email_ck = mysql_fetch_array(mysql_query("select * from g5_member where mb_email = '{$email}'"));
    if ($email_ck) {
        alert("중복된 이메일입니다.");
        exit();
    }
}


if ($email != $modi_row['mb_email'] || $hp != $modi_row['mb_hp'] || $_POST['mb_addr1'] != $modi_row['mb_addr1'] || $_POST['mb_addr2'] != $modi_row['mb_addr2']
    || $birth != $modi_row['birth'] || $bankname != $modi_row['bankName'] || $accountHolder != $modi_row['accountHolder']
    || $accountNumber != $modi_row['accountNumber']) {
    $chk = TRUE;
}

if ($chk) {
    $sql = "insert into member_modify
                       (mb_id, mb_name
                       ,mb_email,mb_hp,mb_zip1
                       ,mb_zip2,mb_addr1,mb_addr2
                      ,mb_birth ,bankName
                       ,accountHolder ,accountNumber
                       ,modi_mb_name, modi_mb_email,modi_mb_hp
                       ,modi_mb_zip1,modi_mb_zip2,modi_mb_addr1
                       ,modi_mb_addr2,modi_birth,modi_bankName
                       ,modi_accountHolder,modi_accountNumber,modi_date)
values ('{$modi_row['mb_id']}' , '{$modi_row['mb_name']}'"
        . ", '{$modi_row['mb_email']}', '{$modi_row['mb_hp']}', '{$modi_row['mb_zip1']}'"
        . ", '{$modi_row['mb_zip2']}', '{$modi_row['mb_addr1']}', '{$modi_row['mb_addr2']}'"
        . ",'{$modi_row['birth']}', '{$modi_row['bankName']}'"
        . ", '{$modi_row['accountHolder']}', '{$modi_row['accountNumber']}', '{$_POST['name']}'"
        . ",'{$email}' ,'{$hp}','{$mb_zip1}'"
        . ",'{$mb_zip2}','{$_POST['mb_addr1']}','{$_POST['mb_addr2']}'
       ,'{$birth}','{$bankname}','{$accountHolder}'"
        . ",'{$accountNumber}','{$date}')";

    $modi_result = mysql_query($sql);
}


$result = mysql_query("UPDATE g5_member 
                        SET 
                            {$txt}
                            mb_hp = '{$hp}',
                        	birth='{$birth}',
                        	mb_email='{$email}',
                        	mb_zip1='{$mb_zip1}',
                            mb_zip2='{$mb_zip2}',
                        	mb_addr1='{$_POST['mb_addr1']}',
                            mb_addr2='{$_POST['mb_addr2']}',
                                bankName = '{$bankname}',
                                accountHolder = '{$accountHolder}',
                                accountNumber = '{$accountNumber}'
                         WHERE mb_id='{$member['mb_id']}'");


mysql_close($connect);


$prevPage = $_SERVER['HTTP_REFERER'];
header('location:' . $prevPage);


/* if(mysql_affected_rows($result)==1){
  $msg="정보수정이 완료되었습니다.";
  } */
?>

