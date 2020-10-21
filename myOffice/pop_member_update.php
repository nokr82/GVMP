<?php

include_once('./inc/title.php');
include_once('./_common.php');
include_once('./dbConn.php');
include_once("./nameUpdate.php");




if ($is_guest)// 로그인 안 했을 때 로그인 페이지로 이동
    header('Location: /bbs/login.php');

$rank = $member['accountRank'];
if (rank != '5 STAR' || rank != 'AMBASSADOR' || rank != 'Crown AMBASSADOR' || rank != 'Double AMBASSADOR' || rank != 'Royal Crown AMBASSADOR' || rank != 'Triple AMBASSADOR') {
       return alert("수정권한이 없습니다.");
} 

$chk = false;

$modi_row = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$_POST['mb_id']}'"));
$date = date("Y-m-d H:i:s");

$modi_cnt = $modi_row['modi_count'];
if ($modi_cnt >0) {
       return alert("수정권한이 없습니다.");
} 


$mb_zip1 = substr($_POST['mb_zip'], 0, 3);
$mb_zip2 = substr($_POST['mb_zip'], 3, 2);

mysql_query("set session character_set_connection=utf8");
mysql_query("set session character_set_results=utf8");
mysql_query("set session character_set_client=utf8");

$_POST['name'] = trim($_POST['name']);

$txt = "";
if ($_POST['name'] != "") { // 이름 변경을 할 때만 update 쿼리에 쿼리문 넣는 목적으로 조건문 판별. 이름 변경이 어떨때는 값이 넘어오고 어떨때는 안 넘어오기 때문에 넣음.
    $txt = "mb_name = '{$_POST['name']}',";

    nameUpdateFunc($_POST['name'], $member["mb_id"]);
}




if ($_POST['email'] != $modi_row['mb_email'] || $_POST['hp'] != $modi_row['mb_hp'] || $_POST['mb_addr1'] != $modi_row['mb_addr1'] || $_POST['mb_addr2'] != $modi_row['mb_addr2'] || $_POST['birth'] != $modi_row['birth'] || $_POST['bankName'] != $modi_row['bankName'] || $_POST['accountHolder'] != $modi_row['accountHolder'] || $_POST['accountNumber'] != $modi_row['accountNumber']) {
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
                       ,modi_accountHolder,modi_accountNumber,modi_date,modi_id)
values ('{$modi_row['mb_id']}' , '{$modi_row['mb_name']}'"
            . ", '{$modi_row['mb_email']}', '{$modi_row['mb_hp']}', '{$modi_row['mb_zip1']}'"
            . ", '{$modi_row['mb_zip2']}', '{$modi_row['mb_addr1']}', '{$modi_row['mb_addr2']}'"
            . ",'{$modi_row['birth']}', '{$modi_row['bankName']}'"
            . ", '{$modi_row['accountHolder']}', '{$modi_row['accountNumber']}', '{$_POST['name']}'"
            . ",'{$_POST['email']}' ,'{$_POST['hp']}','{$mb_zip1}'"
            . ",'{$mb_zip2}','{$_POST['mb_addr1']}','{$_POST['mb_addr2']}'
       ,'{$_POST['birth']}','{$_POST['bankName']}','{$_POST['accountHolder']}'"
            . ",'{$_POST['accountNumber']}','{$date}','{$member['mb_id']}')";

    $modi_result = mysql_query($sql);
}




$result = mysql_query("UPDATE g5_member 
                        SET 
                            {$txt}
                            mb_hp = '{$_POST['hp']}',
                        	birth='{$_POST['birth']}',
                        	mb_email='{$_POST['email']}',
                        	mb_zip1='{$mb_zip1}',
                            mb_zip2='{$mb_zip2}',
                        	mb_addr1='{$_POST['mb_addr1']}',
                            mb_addr2='{$_POST['mb_addr2']}',
                                bankName = '{$_POST['bankName']}',
                                accountHolder = '{$_POST['accountHolder']}',
                                modi_count = {$modi_row}+1,
                                accountNumber = '{$_POST['accountNumber']}'
                         WHERE mb_id='{$member['mb_id']}'");


mysql_close($connect);

$prevPage = $_SERVER['HTTP_REFERER'];
header('location:' . $prevPage);
?>

