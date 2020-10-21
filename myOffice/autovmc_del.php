<?php
include_once ('./_common.php');
include_once ('./dbConn.php');

$sql = "select count(*) as cnt from dayPoint where (1) and  way = 'autoVMC' and date like '2020-10-15%'";
$query = mysql_query($sql);

$row = mysql_fetch_array($query);
echo "@@@@@>>>>".$row['cnt'];


exit;

echo "ddd22";