<?
include_once('./_common.php');

$jsondata = (isset($_POST['jsonData'])&&$_POST['jsonData'])?str_replace('\"','"',trim($_POST['jsonData'])):"";
$jsondata = preg_replace('/\r\n|\r|\n/','',$jsondata);
$sql = "INSERT INTO ownertmp SET jsoncontent='".$jsondata."'";

sql_query($sql);

$msg['msg'] = "true";
echo json_encode($msg);
?>