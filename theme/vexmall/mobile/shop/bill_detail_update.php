<?php
include_once($DOCUMENT_ROOT.'/vexMall/back/dbConn.php'); //192.168.0.30 DB Server
include_once('./_common.php');
// 설정



$ridx		= (isset($_POST["ridx"])&&trim($_POST["ridx"]))?trim($_POST["ridx"]):"";
$nidx		= (isset($_POST["nidx"])&&trim($_POST["nidx"]))?trim($_POST["nidx"]):"";
$mb_id		= (isset($_POST["mb_id"])&&trim($_POST["mb_id"]))?trim($_POST["mb_id"]):"";
$billFile	= (isset($_POST["billFile"])&&trim($_POST["billFile"]))?trim($_POST["billFile"]):"";
$billName	= (isset($_POST["billName"])&&trim($_POST["billName"]))?trim($_POST["billName"]):"";
$billDate	= (isset($_POST["billDate"])&&trim($_POST["billDate"]))?trim($_POST["billDate"]):"";
$billNum	= (isset($_POST["billNum"])&&trim($_POST["billNum"]))?trim($_POST["billNum"]):"";
$billSum	= (isset($_POST["billSum"])&&trim($_POST["billSum"]))?trim($_POST["billSum"]):"";
$billTel	= (isset($_POST["billTel"])&&trim($_POST["billTel"]))?trim($_POST["billTel"]):"";


if(!$ridx) {
	$sql = "SELECT count(*) as cnt FROM Receipt WHERE billDate = '".$billDate."' AND billNum = '".$billNum."' AND billSum = '".$billSum."'";
	$row = sql_fetch($sql);
	if($row['cnt']>0){
?>
<script type="text/javascript">
<!--
	alert("이미 등록되어 있는 영수증 입니다.");
	location.href="/theme/vexmall/mobile/shop/bill.php?mb_id=<?=$mb_id?>&nidx=<?=$nidx?>";
//-->
</script>
<?
	echo exit;
	}
}

if($ridx) {
	$sql = "UPDATE Receipt SET mb_id = '" .$mb_id. "',billFile='".$billFile."',billName='".$billName."',billDate='".$billDate."',billNum='".$billNum."',billSum='".$billSum."',billTel='".$billTel."',state = '1' WHERE idx = '".$ridx."'";
}else{
	$sql = "INSERT INTO Receipt SET vdMapidx = '".$nidx."', mb_id = '" .$mb_id. "',billFile='".$billFile."',billName='".$billName."',billDate='".$billDate."',billNum='".$billNum."',billSum='".$billSum."',billTel='".$billTel."',state = '1',date = '".date("Y-m-d H:i:s")."'";
}
$result = sql_query($sql);

if($nidx){
	$backURL = "/theme/vexmall/mobile/shop/bill.php?mb_id=".$mb_id."&nidx=".$nidx;
}else{
	$backURL = "/theme/vexmall/mobile/shop/bill_list.php?mb_id=".$mb_id."&state=1#";
}

//push처리
$row = sql_fetch("SELECT token, mb_id FROM usersPush WHERE mb_id =(SELECT mb_id FROM vdMapInfoTBL WHERE n = '".$nidx."')");
$push_token = $row['token'];
$tomb_id = $row['mb_id'];
if($push_token){ //TOKEN이 있을때만 동작
	if($ridx) { //수정 부분
		$kind = "영수증등록";
		$message = "소비자가 등록한 영수증이 등록되었습니다.";
	}else{	// 입력부분
		$kind = "영수증수정";
		$message = "소비자가 등록한 영수증을 수정하였습니다.";
	}
	$result = Android_Chat_Push($push_token, "영수증알림", $message,"alram", "", "");
	if($result) {
		savePushContent("영수증알림",$kind,$message,$tomb_id,$result);
	}
}
//push처리

?>
<script type="text/javascript">
<!--
location.href="<?=$backURL?>";
//-->
</script>
