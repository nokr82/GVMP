<?php
$sub_menu = "200910";
include_once('./_common.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/Classes/PHPExcel.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/Classes/PHPExcel/IOFactory.php");


auth_check($auth[$sub_menu], 'r');

$g5['title'] = '영수증 관리 자세히보기';
include_once('./admin.head.php');


// 엑셀파일 다운로드 버튼 링크 동적으로 만들기
$downHref = "member_point_excel.php?";
if( isset( $_GET['sfl'] ) ) {
    $downHref .= "sfl=" . $_GET['sfl'] . "&";
}

if( isset( $_GET['fr_date'] ) ) {
    $downHref .= "fr_date=" . $_GET['fr_date'] . "&";
}

if( isset( $_GET['la_date'] ) ) {
    $downHref .= "la_date=" . $_GET['la_date'] . "&";
}

if( isset( $_GET['stx'] ) ) {
    $downHref .= "stx=" . $_GET['stx'] . "&";
}


$sql = " SELECT * FROM billList WHERE idx = '".$idx."'";
$row = sql_fetch($sql);
imageFileRotationCheck($row['billFile']);


$prevsql = " SELECT idx FROM billList WHERE idx < '".$idx."' ORDER BY idx DESC LIMIT 0 ,1";
$prevrow = sql_fetch($prevsql);
$perv = $prevrow['idx'];

$nextsql = " SELECT idx FROM billList WHERE idx > '".$idx."' ORDER BY idx ASC LIMIT 0 ,1";
$nextrow = sql_fetch($nextsql);
$next = $nextrow['idx'];


?>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
<link rel="stylesheet" href="./css/vroad_receipt.css" type="text/css" />  
<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.18/themes/base/jquery-ui.css" type="text/css" />  
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>  
<script src="http://code.jquery.com/ui/1.8.18/jquery-ui.min.js"></script>  
<script>
$(function() {
	$( "#Datepicker1" ).datepicker({
		changeMonth: true, 
		dayNames: ['월요일', '화요일', '수요일', '목요일', '금요일', '토요일', '일요일'],
		dayNamesMin: ['월', '화', '수', '목', '금', '토', '일'], 
		monthNamesShort: ['1','2','3','4','5','6','7','8','9','10','11','12'],
		monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		dateFormat: 'yy-mm-dd'
	});
	$( "#Datepicker2" ).datepicker({
		changeMonth: true, 
		dayNames: ['월요일', '화요일', '수요일', '목요일', '금요일', '토요일', '일요일'],
		dayNamesMin: ['월', '화', '수', '목', '금', '토', '일'], 
		monthNamesShort: ['1','2','3','4','5','6','7','8','9','10','11','12'],
		monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		dateFormat: 'yy-mm-dd'
	});
});
</script>
<style>
#datePick .calendar1{float:left}
</style>
<script type="text/javascript">
<!--
var preidx = "<?=$perv?>";
var nextidx = "<?=$next?>";
function movePage(state) {
	if(state=="prev") {
		if(!preidx) {
			alert("이전 영수증 데이터가 없습니다.");
		}else{
			$("#moveForm").attr("action","vroad_receipt_detail.php?idx="+preidx);
			$("#moveForm").submit();
		}
	}else{
		if(!nextidx) {
			alert("다음 영수증 데이터가 없습니다.");
		}else{
			$("#moveForm").attr("action","vroad_receipt_detail.php?idx="+nextidx);
			$("#moveForm").submit();
		}
	}
}
function movelist()
{
	location.href=$("#httpreferer").val();
}
//-->
</script>
<form name="moveForm" id="moveForm" method="POST" action="">
<input type="hidden" name="httpreferer" id="httpreferer" value="<?=($httpreferer)?trim($httpreferer):$_SERVER['HTTP_REFERER'];?>">
</form>
<form action="./vroad_receipt_update.php" method="post" id="receiptForm_detail">
<input type="hidden" name="idx" id="idx" value="<?=$row['idx']?>">
<input type="hidden" name="state" id="state" value="">
    <div class="form_info clearfix">
        <div class="btn_box">
            <a href="javascript:movePage('prev')" class="btn_prev"><i class="fas fa-chevron-left"></i></a>
            <a href="javascript:movelist()" class="btn_list">목록보기</a>
            <a href="javascript:movePage('next')" class="btn_next"><i class="fas fa-chevron-right"></i></a>
        </div>
        <div class="save_box">
            <?if($row['state']!="2"){?><a href="#" class="btn_update">수정</a><?}?>
            <!--a href="#" class="btn_del">삭제</a-->
        </div>
    </div>
    <h2>영수증 정보</h2>
    <div class="detail_box clearfix">
        <ul class="clearfix">
            <li class="clearfix">
                <h3>승인번호</h3>
                <?=$row['billNum']?>
            </li>
            <li class="clearfix">
                <h3>회원이름(ID)</h3>
                <?=$row['mb_name']?> (<?=$row['mb_id']?>)
            </li>
            <li class="clearfix">
                <h3>상점이름</h3>
                <?=$row['companyName']?>
            </li>
            <li class="clearfix">
                <h3>금액</h3>
                <?=number_format($row['billSum'])?>
            </li>
            <li class="clearfix">
                <h3>날짜</h3>
                <?=$row['billDate']?>
            </li>
            <li class="clearfix">
                <h3>캐쉬백</h3>
                <?=(!$row['cashback'])?"0":$row['cashback'];?> %
            </li>
<!--            <li class="clearfix">
                <h3>리뉴얼상태</h3>
                <?echo (getRenewalDate($row['mb_id']))?"기간임":"<font color='red'>아님</font>";?>
            </li>-->

			
            <li class="clearfix ck_box">
                <h3>승인 / 거절</h3>
				<?if($row['state']=="2"){?>
				승인 완료
				<?}else{?>
                <label for="agree"><input type="radio" id="agree" name="agree" value="2">승인</label>
                <label for="disagree"><input type="radio" id="disagree" name="agree" value="3">거절</label>
				<?}?>
<!--                 <label for="beforeagree"><input type="radio" id="beforeagree" name="agree" value="1" <?if($row['state']=="1"){?>checked<?}?>>검토중</label> -->
<!--                 <label for="agree"><input type="radio" id="agree" name="agree" value="2" <?if($row['state']=="2"){?>checked<?}?>>승인</label> -->
<!--                 <label for="disagree"><input type="radio" id="disagree" name="agree" value="3" <?if($row['state']=="3"){?>checked<?}?>>거절</label> -->
            </li>
        </ul>
        <div class="img_box">
            <a href="<?=$row['billFile']?>" target="_blank"><img src="<?=$row['billFile']?>" alt="영수증"/></a>
        </div>
    </div>
</form>

<script type="text/javascript">
<!--
$(function(){
	$(".btn_update").on("click",function(){
		var check = $("input[name='agree']:checked").val();
		if(check==undefined){
			alert("승인/거절을 선택해 주세요");
		}else{
			var TXT = (check=="2")?"승인":"거절";
			if(confirm("현재 영수증을 `" + TXT + "`처리 하시겠습니까?\n확인시 수정은 불가능합니다.")){
				$("#state").val("modify");
				$("#receiptForm_detail").submit();
			}
		}
	});
});
//-->
</script>

<?php
include_once('./admin.tail.php');
?>

    

