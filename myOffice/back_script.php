<?php
//프로시저 잘못돌아갔을때
include_once('./dbConn.php');
set_time_limit(0);

//$sql = "SELECT * FROM gyc5.dayPoint where  way = 'vmgMove'  and  '2020-06-30 15:50' < date order by date desc";

$re = mysql_query($sql);
$count = 0;
while ($row = mysql_fetch_array($re)) {
    $sql = "update g5_member set VMC = VMC + {$row['VMG']}, VMG = VMG - {$row['VMG']} where mb_id = '{$row['mb_id']}'";
    $sql2 = "delete from dayPoint where no ={$row['no']}";
    mysql_query($sql);
    mysql_query($sql2);
    echo $row['mb_id'] . "<br>";
}


?>