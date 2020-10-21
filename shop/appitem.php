<?php
$it_id	= (isset($_GET['it_id'])	&&$_GET['it_id'])	?$_GET['it_id']		:'';
$device	= (isset($_GET['device'])	&&$_GET['device'])	?$_GET['device']	:'';
$mb_idx	= (isset($_GET['mb_idx'])	&&$_GET['mb_idx'])	?$_GET['mb_idx']	:'';



if(!$it_id){
	echo "<script>";
	echo "alert('상품이 없습니다');";
	echo "</script>";
	echo exit;
}else if(!$mb_idx){
	echo "<script>";
	echo "alert('로그인을 먼저 해주세요');";
	echo "</script>";
	echo exit;
}else if(!$device){
	echo "<script>";
	echo "alert('잘못된 접근 입니다');";
	echo "</script>";
	echo exit;
}




if($device=="app"){
	$_SESSION['ss_is_app'] = true;
	define('G5_IS_APP', true);
}
?>

<form name="flogin" action="/bbs/login_check.php" method="post" id="flogin">
<input type="hidden" name="url" value="http%3A%2F%2Fvmp.company/shop/item.php?it_id=<?=$it_id?>">
<input type="hidden" name="mb_idx" id="mb_idx" value="<?=$mb_idx?>">
<input type="hidden" name="device" id="device" value="<?=$device?>">
<input type="hidden" name="it_id" id="it_id" value="<?=$it_id?>">
</form>
<script type="text/javascript">
<!--
window.onload=function(){
	document.all.flogin.submit();
}
//-->
</script>