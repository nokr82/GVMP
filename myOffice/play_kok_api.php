<?
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');

$pIdx = $_GET['vmp_mbid'];

$sql = "
	SELECT * FROM g5_member WHERE  mb_id='".$pIdx."' AND accountType = 'VM'  AND renewal + INTERVAL 4 MONTH >= DATE(NOW())
";
 $res = mysql_query($sql);
 while($row = mysql_fetch_array($res)){
	$rowArr = $row; 
 }
 if(count($rowArr) == 0){
	echo "erro";
 }else{
	echo "success";
 }

?>