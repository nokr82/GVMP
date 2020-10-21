<?php
include_once ('./_common.php');
include_once ('./dbConn.php');

if ($is_guest) // 로그인 안 했을 때 로그인 페이지로 이동
    header('Location: /bbs/login.php');


$row1 = mysql_fetch_array(mysql_query("select * from selectedProducts where mb_id = '{$member['mb_id']}'"));


if( $row1['n'] == "" ) {
    mysql_query("insert into selectedProducts set productNumber = '{$_POST['product_ck']}', mb_id = '{$member[mb_id]}', name = '{$_POST['name']}', hp = '{$_POST['hp']}', address1 = '{$_POST['addr1']}', address2 = '{$_POST['addr2']}'");
} else if( $row1['n'] != "" ) {
    mysql_query("update selectedProducts set productNumber = '{$_POST['product_ck']}',  name = '{$_POST['name']}', hp = '{$_POST['hp']}', address1 = '{$_POST['addr1']}', address2 = '{$_POST['addr2']}' where mb_id = '{$member['mb_id']}'");
}

echo "<script>alert('저장이 완료되었습니다.'); window.location.href = '/myOffice/gifts.php?ca_id=10';</script>";

?>