<?
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');

$pIdx = $_GET['vmp_mbid'];
$gvmc = $_GET['vmc'];
$nick = $_GET['nick'];
$type = $_GET['type'];
if($type == '1'){
	$content = "membershipReward;".$nick;
	$vmc = $gvmc;

}else if($type == '2'){
	$content = "sponsReward;".$nick;
	$vmc = $gvmc * 100;

}
$sql = "
	UPDATE  g5_member SET 
		VMC = VMC+".$vmc." 
	WHERE  mb_id='".$pIdx."'
";
		mysql_query($sql);
		mysql_query("INSERT INTO dayPoint (mb_id, VMC, DATE, way) VALUES ('".$pIdx."', ".$vmc.", NOW(), '".$content."')");
		echo "success"
?>