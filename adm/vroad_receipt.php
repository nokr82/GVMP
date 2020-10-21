<?php
$sub_menu = "200910";
include_once('./_common.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/Classes/PHPExcel.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/Classes/PHPExcel/IOFactory.php");


auth_check($auth[$sub_menu], 'r');

$g5['title'] = '영수증 관리';
include_once('./admin.head.php');
include_once('./admin_menu_check.php');

if ($is_admin != 'super') {
     menu_check($sub_menu,$member['mb_id']);
}

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

$state = "";
if($_GET['state']) {
	$state = " state='".$_GET['state']."'";	
}


$where = array();


if($_GET['sfl']&&$_GET['stx']) {
	$where[] = " " . $_GET['sfl'] . "='".$_GET['stx']."'";
}



if($_GET['fr_date']) {
	$where[] = " billDate >= '".$_GET['fr_date']."'";
}
if($_GET['la_date']) {
	$where[] = " billDate <= '".$_GET['la_date']."'";
}


if ($where) {
    $sql_search = ' where '.implode(' and ', $where);
	$tsql_search = $sql_search;
	if ($state) {
		$tsql_search .= " AND {$state}";
	}
}else{
	if ($state) {
		$tsql_search = " where {$state}";
	}
}


$sql = " SELECT sum(billSum) as total FROM billList {$tsql_search} Order by billDate DESC";
$row = sql_fetch($sql);
$totalprice = $row['total'];
$getPrice = $totalprice * 0.06; 

$sql = " SELECT * FROM billList {$tsql_search} Order by billDate DESC";

//echo $sql . "<br>";
$result = sql_query($sql);
//for ($i=0; $row1=sql_fetch_array($result1); $i++)
$totalcount= sql_num_rows($result);
?>
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

<!--<div id="search_wrap">
    <div id="list_btn">
        <button onclick="window.location.href = '/adm/member_point.php'">전체 회원목록</button>
        <a href="<?=$downHref?>"><button>엑셀파일 다운로드</button></a><br>
         <span>※ 전체 회원목록은 오늘을 기준으로<br>한달치에 대한 기간으로 전체 회원의 조회가 진행됩니다.</span> 
    </div>
</div>-->

<div id="datePick">
    <form name="fvisit" id="fvisit" class="local_sch03 local_sch" method="get">     
        <div class="sch_last" id="date">
            <strong>기간별검색</strong>
            <label for="sfl" class="sound_only">검색대상</label>
            <select name="sfl" id="sfl">
            	<option value="no">선택안함</option>
                <option value="billName" <?if($sfl=="billName"){?>selected<?}?>>이름</option>
                <option value="mb_id" <?if($sfl=="mb_id"){?>selected<?}?>>회원아이디</option>
                <option value="companyName" <?if($sfl=="companyName"){?>selected<?}?>>상점이름</option>
            </select>
            <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
            <input type="text" name="stx" id="stx" class="frm_input" value="<?=$stx?>">
            <!-- <input type="submit" class="btn_submit" value="검색"> -->
            
            &emsp;<i class="fa fa-calendar" aria-hidden="true"></i>
            <input type="text" placeholder="시작일" name="fr_date" id="Datepicker1" class="frm_input" size="11" maxlength="10" value="<?=$fr_date?>" readonly>
            <label for="fr_date" class="sound_only">시작일</label>
            ~
            <input type="text" placeholder="종료일"  name="la_date" id="Datepicker2" class="frm_input" size="11" maxlength="10" value="<?=$la_date?>" readonly>
            <label for="fr_date" class="sound_only">종료일</label>
            <input type="submit" value="검색" class="btn_submit">
			<!--ul class="modifiedBtUl" style="padding-top:10px;font-size:1.0rem;">
				<li style="float:left;">총금액 : <?=number_format($totalprice)?></li>
				<li style="float:left;padding-left:15px;">받을금액(6%) : <?=number_format($getPrice)?></li>
			</ul-->

			<label><input type="radio" name="state" id="state1" value="" <?if($_GET['state']==""){?>checked<?}?>>전체</label>
			<label><input type="radio" name="state" id="state1" value="1" <?if($_GET['state']=="1"){?>checked<?}?>>검토</label>
			<label><input type="radio" name="state" id="state1" value="2" <?if($_GET['state']=="2"){?>checked<?}?>>승인</label>
			<label><input type="radio" name="state" id="state1" value="3" <?if($_GET['state']=="3"){?>checked<?}?>>거절</label>
        </div>
        
		<span class="modifiedBt2">
			<img src="img/edit.png" alt="editbtn" onclick="modi2();" style="float:right;">
			<ul class="modifiedBtUl2">
				<li class="closeBt2" style="float:right;"><span><i class="fa fa-times" id="close-fa" aria-hidden="true"></i></span></li>
				<li class="confirmBt2" style="display: none; float:right; "><span><i class="fa fa-check" id="check-fa" aria-hidden="true" ></i></span></li>
			</ul>
		</span> 
    </form>

</div>
<!--※ 데이터 삭제를 원할시 삭제 버튼 클릭 후 초록색 체크를 클릭해야 적용됩니다.<br>
※ 동시에 여러 데이터를 수정 및 삭제할 수  있습니다.<br><br>-->

<section class="total_history">
    <div class="total_history_Table">
        <form action="#" method="POST" id="receiptForm">
        <table>
            <thead>
                <tr>
                    <th>데이터번호</th>
                    <th>회원이름(ID)</th>
                    <th>상점이름</th>
                    <th>금액</th>
                    <th>수수료 + 캐시백</th>
                    <th>받을금액</th>
                    <th>날짜</th>
                    <th>상태</th>
                    <th>수정</th>
                    <th class="removebox">삭제</th>
                </tr>
            </thead>

            <tbody id="orderTB">
<?if($totalcount==0){?>
               <tr>
                 <td colspan="7">등록된 영수증이 없습니다.</td>    
               </tr>
<?}else{?>
<?
$totalPay = 0;
$totalPrice = 0;
	while($row=sql_fetch_array($result)){
		switch($row['state']){
			case "2":
				$stateValue = "<span style='color:blue'>승인</span>";
				$cashback = $row['cashback'] + 5;
				$cashback = $cashback . "%";
				$sellpay = round($row['billSum'] * ($cashback/100));
				$totalPay = $totalPay + $sellpay;
				$totalPrice = $totalPrice + $row['billSum'];
				$sellpay = number_format($sellpay);
				break;
			case "3":
				$stateValue = "<span style='color:red'>거절</span>";
				$cashback = "-";
				$sellpay = "-";
				break;
			default:
				$stateValue = "검토중";
				$cashback = "-";
				$sellpay = "-";
				break;
		}
?>

               <tr>
                 <td><?=$row['billNum']?></td>
                 <td><?=$row['mb_name']?> (<?=$row['mb_id']?>)</td>
                 <td><?=$row['companyName']?></td>

                 <td><?=number_format($row['billSum'])?></td>
                 <td><?=$cashback?></td>
                 <td><?=$sellpay?></td>

                 <td><?=$row['billDate']?></td>
                 <td><?=$stateValue?></td>
                 <td><a href="javascript:moveDetail('<?=$row['idx']?>')" class="detail_btn btn_vroad">수정</a></td>    
                 <td class="removebox"><a href="#" class=" btn_vroad">삭제</a></td>    
               </tr>
<?	}?>
				<tr>
					<td colspan="9" style="text-align:right;padding-right:40px;">
						총금액 : <?=number_format($totalPrice)?>
						받을금액 : <?=number_format($totalPay)?>
					</td>
				</tr>
<?}?>
            </tbody>
            
        </table>
        </form>
    </div>
    </section>
<?php
include_once('./admin.tail.php');
?>

<script type="text/javascript">
$(function(){
	var $modifiedBtImg2 = $('.modifiedBt2 > img');
	var $modifiedBtConfirmBt2 = $('.confirmBt2');
	var $modifiedBtCloseBt2 = $('.closeBt2');
	var $removeBox1 = $('.removebox');

	$modifiedBtImg2.on('click',function(){
		$('.sellinput').addClass('on').attr('readonly',false);
		$(this).css('display', 'none');
		$removeBox1.css({"display":"inline-block"});
		$modifiedBtCloseBt2.css({"display":"block","float":"right"});
		$modifiedBtConfirmBt2.css({"display":"block","float":"right"});
	})
	$modifiedBtConfirmBt2.on('click',function(){
		$('.sellinput').removeClass('on').attr('readonly',true);
		$(this).css('display', 'none');
		$removeBox1.css({"display":"none"});
		$modifiedBtImg2.css({"display":"block","float":"right"});
		$modifiedBtCloseBt2.css({"display":"none","float":"right"});
	})
	$modifiedBtCloseBt2.on('click',function(){
		$('.sellinput').removeClass('on').attr('readonly',true);
		$(this).css('display', 'none');
		$removeBox1.css({"display":"none"});
		$modifiedBtConfirmBt2.css({"display":"none","float":"right"});
		$modifiedBtImg2.css({"display":"block","float":"right"});
	})
});

$(".removebox").on("click", function(){
	if (confirm('정말 삭제하시겠습니까??') == true){    //확인
		$(this).parents('tr').children('td').eq(2).children('input').attr("value","삭제");
		$(this).parents('tr').children('td').eq(3).children('input').attr("value","삭제");
		$(this).parents('tr').children('td').eq(4).children('input').attr("value","삭제");
		$(this).parents('tr').children('td').eq(5).children('input').attr("value","삭제");
	}else{   //취소
		return;
	}
});
function moveDetail(num)
{
	$("#detailForm").attr("action","./vroad_receipt_detail.php?idx="+num);
	$("#detailForm").submit();
}
</script>
<form name="detailForm" id="detailForm" action="" method="post">
<input type="hidden" name="tsql_search" id="tsql_search" value="<?=$tsql_search?>">
</form>