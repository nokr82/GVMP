<?php
include_once($DOCUMENT_ROOT.'/vexMall/back/dbConn.php'); //192.168.0.30 DB Server
include_once('./_common.php');

//if (!$is_member)
//    goto_url(G5_BBS_URL."/login.php");
//풀지말것

define("_INDEX_", TRUE);

include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');


$ridx	= (isset($_POST['ridx'])&&trim($_POST['ridx']))		?trim($_POST['ridx']):trim($_GET['ridx']);
$nidx	= (isset($_POST['nidx'])&&trim($_POST['nidx']))		?trim($_POST['nidx']):trim($_GET['nidx']);
$mb_id	= (isset($_POST['mb_id'])&&trim($_POST['mb_id']))	?trim($_POST['mb_id']):trim($_GET['mb_id']);
$SaveFile = (isset($_POST['billFile'])&&trim($_POST['billFile']))?trim($_POST['billFile']):G5_THEME_IMG_URL."/event/vroad_alert.svg";

$sql = " select * from Receipt where idx = '$ridx'";

$result = sql_query($sql);
$total_rows = sql_num_rows($result);
if($total_rows) {
	$row=sql_fetch_array($result);
	$billName = $row["billName"];
	$billDate = $row["billDate"];
	$billNum = $row["billNum"];
	$billSum = $row["billSum"];
	$billTel = $row["billTel"];
	$SaveFile = $row["billFile"];
}
?>
<!--<link rel="stylesheet" href="<?=G5_THEME_CSS_URL?>/jquery.bxslider.css">
<script src="<?=G5_THEME_JS_URL?>/jquery.bxslider.js"></script>
<script src="<?=G5_THEME_JS_URL?>/index.js"></script>-->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.0/css/all.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<form id="billForm" enctype='multipart/form-data' action='bill_update.php' method='post' style="display:none">
<input type="hidden" name="mb_id" id="mb_id" value="<?=$mb_id?>">
<input type="hidden" name="nidx" id="nidx" value="<?=$nidx?>">
<input type="hidden" name="ridx" id="ridx" value="<?=$ridx?>">
<input type="file" name="billfile" id="billfile" style="display:none">
</form>

<form id="billdetailForm" action="bill_detail_update.php" method="POST" enctype='multipart/form-data'>
<input type="hidden" name="ridx" id="ridx" value="<?=$ridx?>">
<input type="hidden" name="nidx" id="nidx" value="<?=$nidx?>">
<input type="hidden" name="mb_id" id="mb_id" value="<?=$mb_id?>">
<input type="hidden" name="billFile" id="billFile" value="<?=$SaveFile?>">
<div id="billDetail">
    <img src="<?=$SaveFile?>" alt="영수증" class="billimg"/>
    <h3>가맹점 정보</h3>
    <ul>
        <li class="clearfix" style="display: none">
            <label for="billName">상호:</label>
            <input type="text" name="billName" id="billName" placeholder="상호를 넣어주세요" value="<?=$billName?>">
        </li>
        <li class="clearfix">
            <label for="billDate">날짜:</label>
            <input type="text" name="billDate" id="billDate" placeholder="사용날짜를 넣어주세요" value="<?=$billDate?>" readonly>
        </li>
        <li class="clearfix">
            <label for="billNum">승인번호:</label>
            <input type="text" name="billNum" id="billNum" placeholder="승인번호를 넣어주세요" value="<?=$billNum?>">
        </li>
        <li class="clearfix">
            <label for="billSum">금액:</label>
            <input type="number" name="billSum" id="billSum" placeholder="금액을 넣어주세요" value="<?=$billSum?>">
        </li>
        <li class="clearfix" style="display: none">
            <label for="billTel">전화번호:</label>
            <input type="text" name="billTel" id="billTel" placeholder="전화번호를 넣어주세요" value="<?=$billTel?>">
        </li>
    </ul>
    <a href="#" id="billBtn" class="on"><?if($total_rows) {?>수정하기<?}else{?>등록하기<?}?></a>
</div>
</form>

<script>
$(function(){
	$("#billSum").keyup(function(){$(this).val( $(this).val().replace(/[^0-9]/g,"") );} );
	$( "#billDate" ).datepicker({
		dateFormat: "yy-mm-dd",
		maxDate: 0
	});
		
	$(".billimg").on("click",function(){
		$("#billfile").click();
	});

	$("#billfile").on("change",function(){
		$("#billForm").submit();
	});

	$('#billBtn').click(function(){        
//		if($("#billName").val() == ""){
//			$('#billName').focus();
//			$('#billName').val('');
//			alert('상호명을 입력해주세요.');
//			return false;
//		}else 

		if($("#billDate").val() == ""){
			$('#billDate').focus();
			$('#billDate').val('');
			alert('날짜를 입력해주세요.');
			return false;
		}else if($("#billNum").val() == ""){
			$('#billNum').focus();
			$('#billNum').val('');
			alert('승인번호를 입력해주세요.');
			return false;
		}else if($("#billSum").val() == ""){
			$('#billSum').focus();
			$('#billSum').val('');
			alert('금액을 입력해주세요.');
			return false;
		} else{
			$("#billdetailForm").submit();
		}
//                else if($("#billTel").val() == ""){
//			$('#billTel').focus();
//			$('#billTel').val('');
//			alert('전화번호를 입력해주세요.');
//			return false;

	});
});
</script>