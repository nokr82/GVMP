<?php 
include_once ('./_common.php');
include_once ('./dbConn.php');

$OWNERTOKEN = (isset($_POST['owntoken'])&&$_POST['owntoken'])?trim($_POST['owntoken']):"";


//echo "UPDATE g5_config SET cf_10 = '".$OWNERTOKEN."'" . "<br>";
$result = sql_query("UPDATE g5_config SET cf_10 = '".$OWNERTOKEN."'");
if($result) {
	$data['code'] = "00";
}else{
	$data['code'] = "01";
}
echo json_encode($data);
?>