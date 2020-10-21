<?php
include_once('./dbConn.php');

//$result = mysql_query("SELECT * FROM (SELECT mb_id,SUM(VMG) AS vmg FROM dayPoint WHERE VMG > 0 AND way LIKE 'vmg%' AND (DATE >= '2019-03-15 00:00:00' AND DATE <= '2019-03-18 23:59:59') 
//GROUP BY mb_id) AS a WHERE vmg <=2080000");

$result = mysql_query("SELECT * FROM (SELECT mb_id,SUM(VMG) AS vmg FROM dayPoint WHERE VMG > 0 AND way LIKE 'vmg%' AND (DATE >= '2019-03-15 00:00:00' AND DATE <= '2019-03-18 23:59:59') 
GROUP BY mb_id) AS a WHERE vmg > 2080000");


while($row = mysql_fetch_array($result)) {

	$ssql = "select * from g5_member where mb_id = '".$row['mb_id']."' and VMG <> '" . $row['vmg'] . "'";
	$srow = mysql_fetch_array(mysql_query($ssql));
	echo $srow['mb_id'] . ":::>".$row['vmg'].":::" .$srow['VMG'] . " <br>";
}

?>