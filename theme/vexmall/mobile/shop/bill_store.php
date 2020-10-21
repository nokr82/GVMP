<?php
include_once($DOCUMENT_ROOT.'/vexMall/back/dbConn.php'); // DB Server
include_once('./_common.php');

//if (!$is_member)
//    goto_url(G5_BBS_URL."/login.php");
//풀지말것

define("_INDEX_", TRUE);

include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');

$mb_id = $_GET['mb_id'];

$row = sql_fetch_array(sql_query("select * from vdMapInfoTBL where mb_id = '{$mb_id}' AND delState='1' and step >= 2"));
if($row['susuDate']&&$row['cashDate']&&$row['cashback']!=""){?>
<script type="text/javascript">
<!--
location.href="./bill_store_detail.php?mb_id=<?=$mb_id?>";
//-->
</script>
<?
echo exit;
}
?>
<!--<link rel="stylesheet" href="<?=G5_THEME_CSS_URL?>/jquery.bxslider.css">
<script src="<?=G5_THEME_JS_URL?>/jquery.bxslider.js"></script>
<script src="<?=G5_THEME_JS_URL?>/index.js"></script>-->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.0/css/all.css">

<form id="billStoreForm" enctype='multipart/form-data' action='bill_store_update.php' method='post'>
<input type="hidden" name="mb_id" id="mb_id" value="<?=$mb_id?>">
<div id="billStore">
    <ul>
        <li class="clearfix">
            <label for="billName">상호명:</label>
            <input type="text" name="billName" id="billName" value="<?=$row['companyName']?>" readonly>
        </li>
        <li class="clearfix">
            <label for="billCeo">업종:</label>
            <input type="text" name="billCeo" id="billCeo" value="<?=$row['industry']?>" readonly>
        </li>
        <li class="bill_add clearfix">
            <label for="billAdd_1">주소:</label>
            <input type="text" name="billAdd_1" id="billAdd_1" value="<?=$row['postCode']?>" readonly>
            <!--<button id="serch_add"><i class="fas fa-map-marked-alt"></i>주소검색</button>-->
            <label class="none_text" for="billAdd_2">기본주소:</label>
            <input type="text" name="billAdd_2" id="billAdd_2" value="<?=$row['addr1']?>" readonly>
            <label class="none_text" for="billAdd_3">상세주소:</label>
            <input type="text" name="billAdd_3" id="billAdd_3" value="<?=$row['addr2']?>" readonly>
        </li>
        <li class="clearfix">
            <label for="billtel">연락처:</label>
            <input type="number" name="billtel" id="billtel" value="<?=$row['tel']?>">
        </li>
        <li class="clearfix">
            <label for="billCash">캐시백:</label>
            <input type="text" name="billCash" id="billCash" value="<?=$row['cashback']?>">
            <span>%</span>
        </li>
    </ul>
    <div class="agree_box">
        <div class="clearfix">
            <input type="checkbox" name="billAgree_1" id="billAgree_1">
            <label for="billAgree_1">수수료 5%에 동의합니다.</label>
            
        </div>
        <div class="clearfix">
            <input type="checkbox" name="billAgree_2" id="billAgree_2">
            <label for="billAgree_2">캐시백에 동의합니다.</label>            
        </div>
    </div>
    
    <a href="#" id="billBtn" class="on">등록하기</a>
</div>
</form>

<script>
$(function(){
	$("#billtel").keyup(function(){$(this).val( $(this).val().replace(/[^0-9]/g,"") );} );
});
$('#billBtn').click(function(){
    if($("#billtel").val() == ""){
        $('#billtel').focus();
        $('#billtel').val('');
        alert('연락처를 입력해주세요.');
        return false;
    }
    if($("#billCash").val() == ""){
        $('#billCash').focus();
        $('#billCash').val('');
        alert('캐시백을 입력해주세요.');
        return false;
    }
    var ck1 = $('#billAgree_1').is(':checked');
    var ck2 = $('#billAgree_2').is(':checked');
    
    if( !ck1 ) {
        $('#billAgree_1').focus();
        $('#billAgree_1').val('');
        alert('수수료에 동의해주세요.');
        return false;
    }
    
    if( !ck2 ) {
        $('#billAgree_2').focus();
        $('#billAgree_2').val('');
        alert('캐시백에 동의해주세요.');
        return false;
    }
    $("#billStoreForm").submit();
});


</script>