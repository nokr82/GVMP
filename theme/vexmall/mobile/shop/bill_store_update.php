<?php
include_once($DOCUMENT_ROOT.'/vexMall/back/dbConn.php'); // DB Server
include_once('./_common.php');
// 설정

//$ridx		= (isset($_POST["ridx"])&&trim($_POST["ridx"]))?trim($_POST["ridx"]):"";
//$nidx		= (isset($_POST["nidx"])&&trim($_POST["nidx"]))?trim($_POST["nidx"]):"";
$mb_id			= (isset($_POST["mb_id"])		&&trim($_POST["mb_id"]))?trim($_POST["mb_id"]):"";
$billTel		= (isset($_POST["billtel"])		&&trim($_POST["billtel"]))?trim($_POST["billtel"]):"";
$billCash		= (isset($_POST["billCash"])	&&trim($_POST["billCash"]))?trim($_POST["billCash"]):"";
$billAgree_1	= (isset($_POST["billAgree_1"])	&&trim($_POST["billAgree_1"]))?trim($_POST["billAgree_1"]):"";
$billAgree_2	= (isset($_POST["billAgree_2"])	&&trim($_POST["billAgree_2"]))?trim($_POST["billAgree_2"]):"";

$CHECK = billStateCheck($mb_id);
if($CHECK>0) {?>
<script type="text/javascript">
<!--
alert("심사중인 영수증이 있어서 등록 불가능 합니다.");
history.back(-1);
//-->
</script>
<?php
	echo exit;
}else{
	$sql = "UPDATE vdMapInfoTBL SET step='3', tel = '" .$billTel. "',cashback='".$billCash."',susuDate='".date("Y-m-d H:i:s")."',cashDate='".date("Y-m-d H:i:s")."' WHERE mb_id = '".$mb_id."'";
	$result = sql_query($sql);
?>
<script type="text/javascript">
<!--
location.href="/theme/vexmall/mobile/shop/bill_store_detail.php?mb_id=<?=$mb_id?>";
//-->
</script>
<?php
}

function billStateCheck($MID)
{
	$sql = "SELECT COUNT(*) AS cnt FROM billList WHERE state = '1' AND mb_id = '".$MID."'";
	$row = sql_fetch_array(sql_query( $sql ));
	return $row['cnt'];
}
?>