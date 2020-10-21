<?php
include_once($DOCUMENT_ROOT.'/vexMall/back/dbConn.php'); // DB Server
include_once('./_common.php');

//if (!$is_member)
//    goto_url(G5_BBS_URL."/login.php");
//풀지말것

define("_INDEX_", TRUE);

include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');


$ridx	= (isset($_POST['ridx'])&&trim($_POST['ridx']))		?trim($_POST['ridx']):trim($_GET['ridx']);
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
<div id="billDetail">
    <img src="<?=$SaveFile?>" alt="영수증" />
    <h3>가맹점 정보</h3>
    <ul>
        <!--li class="clearfix">
            <label for="billName">상호:</label>
            <input type="text" name="billName" id="billName" placeholder="상호를 넣어주세요" value="<?=$billName?>" class="read_only" readonly>
        </li-->
        <li class="clearfix">
            <label for="billDate">날짜:</label>
            <input type="text" name="billDate" id="billDate" placeholder="사용날짜를 넣어주세요" value="<?=$billDate?>" class="read_only" readonly>
        </li>
        <li class="clearfix">
            <label for="billNum">승인번호:</label>
            <input type="text" name="billNum" id="billNum" placeholder="승인번호를 넣어주세요" value="<?=$billNum?>" class="read_only" readonly>
        </li>
        <li class="clearfix">
            <label for="billSum">금액:</label>
            <input type="text" name="billSum" id="billSum" placeholder="금액을 넣어주세요" value="<?=number_format($billSum)?>" class="read_only" readonly>
        </li>
        <!--li class="clearfix">
            <label for="billTel">전화번호:</label>
            <input type="text" name="billTel" id="billTel" placeholder="전화번호를 넣어주세요" value="<?=$billTel?>" class="read_only" readonly>
        </li-->
    </ul>
    <a href="#" id="billBtn" class="on" onClick="history.back(-1)">확인</a>
</div>

<script>
</script>