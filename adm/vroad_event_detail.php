<?php
$sub_menu = "200920";
include_once('./_common.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/Classes/PHPExcel.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/Classes/PHPExcel/IOFactory.php");


auth_check($auth[$sub_menu], 'r');

$g5['title'] = '이벤트 관리 자세히보기';
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




?>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
<link rel="stylesheet" href="./css/vroad_receipt.css" type="text/css" />  
<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.18/themes/base/jquery-ui.css" type="text/css" />  
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>  
<script src="http://code.jquery.com/ui/1.8.18/jquery-ui.min.js"></script>
<script type="text/javascript" src="../plugin/editor/smarteditor2/js/service/HuskyEZCreator.js" charset="utf-8"></script>
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
         $( "#Datepicker3" ).datepicker({
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


<form action="#" method="post" id="receiptForm_detail">
    <div class="form_info clearfix">
        <div class="btn_box">
            <a href="#" class="btn_prev"><i class="fas fa-chevron-left"></i></a>
            <a href="vroad_receipt.php" class="btn_list">목록보기</a>
            <a href="#" class="btn_next"><i class="fas fa-chevron-right"></i></a>
        </div>
        <div class="save_box">
            <a href="#" class="btn_update">수정</a>
            <a href="#" class="btn_del">삭제</a>
        </div>
    </div>
    <h2>이벤트 정보</h2>
    <div class="detail_box clearfix">
        <ul class="clearfix">
            <li class="clearfix">
                <h3>데이터번호</h3>
                <input type="text" name="dataNum">
            </li>
            <li class="clearfix">
                <h3>제목</h3>
                <input type="text" name="eventName">
            </li>
            <li class="clearfix">
                <h3>이벤트 시작일</h3>
                <input id="Datepicker1" type="text" name="eventStart">
            </li>
            <li class="clearfix">
                <h3>이벤트 종료일</h3>
                <input id="Datepicker2" type="text" name="eventEnd">
            </li>
            <li class="clearfix">
                <h3>당첨자 발표일</h3>
                <input id="Datepicker3" type="text" name="eventIssue">
            </li>
            <li class="clearfix ck_box">
                <h3>활성 / 비활성</h3>
                <label for="agree"><input type="radio" id="agree" name="agree" value="agree">활성</label>
                <label for="disagree"><input type="radio" id="disagree" name="agree" value="disagree">비활성</label>
            </li>
        </ul>
    </div>
    <div class="event_text">
        <textarea name="ir1" id="ir1" rows="10" cols="100"></textarea>
    </div>
</form>



<?php
include_once('./admin.tail.php');
?>

    

<script type="text/javascript">

var oEditors = [];

nhn.husky.EZCreator.createInIFrame({

    oAppRef: oEditors,

    elPlaceHolder: "ir1",

    sSkinURI: "../plugin/editor/smarteditor2/SmartEditor2Skin.html",

    fCreator: "createSEditor2"

});

</script>