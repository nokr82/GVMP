<?php
include_once($DOCUMENT_ROOT.'/vexMall/back/dbConn.php'); // DB Server
include_once('./_common.php');

//if (!$is_member)
//    goto_url(G5_BBS_URL."/login.php");
//풀지말것

define("_INDEX_", TRUE);

include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');
//1검수 2 승인 3 거절
function stateval($state) {
	$DATA;
	switch ($state) {
		case "1":
			$DATA = "검토중 입니다.";
			break;
		case "2":
			$DATA = "승인되었습니다.";
			break;
		case "3":
			$DATA = "거절되었습니다.<i class='icon_right far fa-edit'></i>";
			break;
	}
	return $DATA;
}

function statevalClass($state) {
	$DATA;
	switch ($state) {
		case "1":
			$DATA = "bill_ing";
			break;
		case "2":
			$DATA = "bill_ok";
			break;
		case "3":
			$DATA = "bill_no";
			break;
	}
	return $DATA;
}

$mb_id = $_GET['mb_id'];
$nidx = $_GET['nidx'];

$sql = "SELECT cashback FROM vdMapInfoTBL WHERE n = '".$nidx."'";
$row = sql_fetch($sql);
$billCheck = ($row['cashback'])?$row['cashback']:"";

//$mb_id = "00003571";
$sql = " select * from billList where mb_id = '".$mb_id."' and vdMapidx = '".$nidx."' order by billdate DESC";
$result = sql_query($sql);
$total_rows = sql_num_rows($result);
?>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.0/css/all.css">
<script type="text/javascript">
<!--
$(function(){
	$(".billBtn").on("click",function(){
		if($(this).text()=="뒤로"){
			alert("back");
		}else{
			$("#billfile").click();
		}
	});

	$("#billfile").on("change",function(){
		$("#billForm").submit();
	});

	$(".fa-edit").on("click",function(){
		if(confirm("수정 하시겠습니까?")){
			var idx = $(this).parent().next().val();
			$("#ridx").val(idx);
			$("#modifyForm").attr("action","bill_detail.php");
			$("#modifyForm").submit();
		}
		return false;
	});


	$(".rview").on("click",function(){
		var idx = $(this).find("#idx").val();
		$("#ridx").val(idx);
		$("#modifyForm").attr("action","bill_view.php");
		$("#modifyForm").submit();
	});
});
//-->
</script>
<form id="modifyForm" name="modifyForm" action="./bill_detail.php">
<input type="hidden" name="ridx" id="ridx" value="">
<input type="hidden" name="mb_id" id="mb_id" value="<?=$mb_id?>">
<input type="hidden" name="nidx" id="nidx" value="<?=$nidx?>">
</form>
<form id="billForm" enctype='multipart/form-data' action='bill_update.php' method='post'>
<input type="hidden" name="mb_id" id="mb_id" value="<?=$mb_id?>">
<input type="hidden" name="nidx" id="nidx" value="<?=$nidx?>">
<div id="bill">
<?if($total_rows>0){?>
    <ul>
<?	while($row=sql_fetch_array($result)){?>
        <li>
            <a href="#" class="rview">
                <div class="clearfix">
                    <div class="img_box">
                        <img src="<?=$row['billFile']?>" alt="영수증"/>
                    </div>
                    <div class="text_box">
                        <p class="bill_name"><span>상호:</span><?=$row['companyName']?></p>
                        <p class="bill_date"><span>날짜:</span><?=$row['billDate']?></p>
                        <p class="bill_sum"><span>금액:</span><?=number_format($row['billSum'])?>원</p>
                        <p class="bill_state <?=statevalClass($row['state'])?>"><i class="fa fa-sync-alt"></i><?=stateval($row['state'])?></p>
						<input type="hidden" name="idx" id="idx" value="<?=$row['idx']?>">
                    </div>
                </div>                    
            </a>
        </li>
<?	}?>
    </ul>
<?}else{?>
    <div class="bill">
        <img src="<?=G5_THEME_IMG_URL?>/event/vroad_alert.svg" alt="경고표시" />
        <h3>등록된 영수증이 없습니다.</h3>
    </div>
<?}?>
<?if($billCheck>=0){?>
    <button type="button" class="btn billBtn">영수증 등록하기</button>
	<input type="file" name="billfile" id="billfile" style="display:none">
<?}else{?>
    <button type="button" class="btn billBtn">뒤로</button>
<?}?>
</div>
</form>

