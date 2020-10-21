<?
include_once('./_common.php');
$jsondata = (isset($_POST['jsonData'])&&$_POST['jsonData'])?str_replace('\"','"',trim($_POST['jsonData'])):"";

$data = json_decode($jsondata,true);
foreach($data as $key => $value) {
	$tmp = $value['node'];
	$sql = "";
	$row = sql_fetch_array(sql_query("select count(*) as cnt from ownerclean_category where own_key = '".$tmp['key']."'"));
	if($row['cnt']){
		$sql = "UPDATE ownerclean_category SET ";
		$WHERE = " WHERE own_key = '".$tmp['key']."'";
	}else{
		$sql = "INSERT INTO ownerclean_category SET ";
		$WHERE = "";
	}
	$sql .= "own_key='".$tmp['key']."',own_name='".$tmp['name']."'".$WHERE;
	sql_query($sql);
}
$msg['msg'] = "true";
echo json_encode($msg);
?>