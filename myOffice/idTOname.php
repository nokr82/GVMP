<?php
include_once ('./dbConn.php');

$result = mysql_query("select * from g5_member where mb_id = '{$_POST['mb_id']}'");
$row = mysql_fetch_array($result);
echo trim($row['mb_name']);
?>