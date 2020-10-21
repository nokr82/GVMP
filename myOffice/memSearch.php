<?
include_once ('./dbConn.php');
include_once('./getMemberInfo.php');

$STXT = (isset($_POST['stxt'])&&$_POST['stxt'])?trim($_POST['stxt']):"";
$STYPE = (isset($_POST['stype'])&&$_POST['stype'])?trim($_POST['stype']):"";




?>