<?php
include_once($DOCUMENT_ROOT.'/vexMall/back/dbConn.php'); // DB Server
include_once('./_common.php');
// 설정
$uploads_dir = $_SERVER["DOCUMENT_ROOT"] . '/up/receipt';
$allowed_ext = array('jpg','jpeg','png','gif');
$FILEDIR = '/up/receipt';
 
// 변수 정리
$error = $_FILES['billfile']['error'];
$name = $_FILES['billfile']['name'];
$ext = array_pop(explode('.', $name));

// 오류 확인
if( $error != UPLOAD_ERR_OK ) {
	switch( $error ) {
		case UPLOAD_ERR_INI_SIZE:
		case UPLOAD_ERR_FORM_SIZE:
			echo "파일이 너무 큽니다. ($error)";
			break;
		case UPLOAD_ERR_NO_FILE:
			echo "파일이 첨부되지 않았습니다. ($error)";
			break;
		default:
			echo "파일이 제대로 업로드되지 않았습니다. ($error)";
	}
	exit;
}

// 확장자 확인
if( !in_array($ext, $allowed_ext) ) {
	echo "허용되지 않는 확장자입니다.";
	exit;
}


$FileName = GetUniqFileName($name, $uploads_dir);
$SaveFile = $uploads_dir."/".$FileName;
$FormSaveFile = $FILEDIR."/".$FileName;

move_uploaded_file($_FILES["billfile"][tmp_name],$SaveFile);

imageFileRotationCheck("/".$FormSaveFile);

function GetUniqFileName($FN, $PN)
{
	$FileExt = substr(strrchr($FN, "."), 1);
	$FileName = substr($FN, 0, strlen($FN) - strlen($FileExt) - 1);
	$ret = $FileName.".".$FileExt;
	while(file_exists($PN."/".$ret))
	{
		$FileCnt++;
		$ret = $FileName."_".$FileCnt.".".$FileExt;
	}

	return($ret);
}

if($ridx){ //수정을 타고 온 이미지이다
	$sql = "UPDATE Receipt SET mb_id = '" .$mb_id. "',billFile='".$FormSaveFile."' WHERE idx = '".$ridx."'";
	$result = sql_query($sql);
?>
<script type="text/javascript">
<!--
window.onload=function(){
	location.href='/theme/vexmall/mobile/shop/bill_detail.php?ridx=<?=$ridx?>&mb_id=<?=$mb_id?>&nidx=<?=$nidx?>';
}
//-->
</script>
<?
	echo "<script>location.href='/theme/vexmall/mobile/shop/bill_detail.php?ridx=".$ridx."&mb_id=".$mb_id."&nidx=".$nidx."';</script>";
	echo exit;
}


?>
<script type="text/javascript">
<!--
window.onload=function(){
	document.moveForm.submit();
}
//-->
</script>
<form name="moveForm" action="/theme/vexmall/mobile/shop/bill_detail.php" method="POST">
<input type="hidden" name="mb_id" id="mb_id" value="<?=$mb_id?>">
<input type="hidden" name="nidx" id="nidx" value="<?=$nidx?>">
<input type="hidden" name="billFile" id="billFile" value="<?=$FormSaveFile?>">
</form>