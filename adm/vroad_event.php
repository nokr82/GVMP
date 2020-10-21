<?php
$sub_menu = "200920";
include_once('./_common.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/Classes/PHPExcel.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/Classes/PHPExcel/IOFactory.php");


auth_check($auth[$sub_menu], 'r');

$g5['title'] = '이벤트 관리';
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
            <strong>검색</strong>
            <label for="sfl" class="sound_only">검색대상</label>
            <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
            <input type="text" name="stx" id="stx" class="frm_input">
            <!-- <input type="submit" class="btn_submit" value="검색"> -->

            <input type="submit" value="검색" class="btn_submit">
            <a href="#" class="event_yes btn_event">진행</a>
            <a href="#" class="event_no btn_event">완료</a>
        </div>
        
         <span class="modifiedBt2"> <img src="img/edit.png" alt="editbtn" onclick="modi2();" style="float:right;">
            <ul class="modifiedBtUl2">
                    <li class="closeBt2" onclick="modiclose();" style="float:right;"><span><i class="fa fa-times" id="close-fa" aria-hidden="true"></i></span></li>
                    <li class="confirmBt2" onclick="member_cash_update();" style="display: none; float:right; "><span><i class="fa fa-check" id="check-fa" aria-hidden="true" ></i></span></li>
            </ul>
        </span> 
    </form>

</div>
※ 데이터 삭제를 원할시 삭제 버튼 클릭 후 초록색 체크를 클릭해야 적용됩니다.<br>
※ 동시에 여러 데이터를 수정 및 삭제할 수  있습니다.<br><br>

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
                    <th>날짜</th>
                    <th>수정</th>
                    <th>삭제</th>
                </tr>
            </thead>

            <tbody id="orderTB">
               <tr>
                 <td><input type='text'  readonly></td>
                 <td><input type='text'  readonly></td>
                 <td><input type='text'  readonly></td>
                 <td><input type='text'  readonly></td>
                 <td><input type='text'  readonly></td>
                 <td><a href="./vroad_event_detail.php" class="detail_btn btn_vroad">수정</a></td>    
                 <td><a href="#" class="removebox btn_vroad">삭제</a></td>    
               </tr>
               <tr>
                 <td><input type='text'  readonly></td>
                 <td><input type='text'  readonly></td>
                 <td><input type='text'  readonly></td>
                 <td><input type='text'  readonly></td>
                 <td><input type='text'  readonly></td>
                 <td><a href="./vroad_event_detail.php" class="detail_btn btn_vroad">수정</a></td>
                 <td><a href="#" class="removebox btn_vroad">삭제</a></td>     
               </tr>
               <tr>
                 <td><input type='text'  readonly></td>
                 <td><input type='text'  readonly></td>
                 <td><input type='text'  readonly></td>
                 <td><input type='text'  readonly></td>
                 <td><input type='text'  readonly></td>
                 <td><a href="./vroad_event_detail.php" class="detail_btn btn_vroad">수정</a></td>
                 <td><a href="#" class="removebox btn_vroad">삭제</a></td>     
               </tr>
            </tbody>
            
        </table>
        </form>
    </div>
    </section>
<?php
include_once('./admin.tail.php');
?>

         <!--x버튼눌렀을때 페이지 리로드-->
        
<script type="text/javascript">
    $(document).ready(function() {
        $('.closeBt2').click(function() {
            location.reload();
        });
    });       
</script>   

<!--편집버튼 누르면 v랑 x버튼--> 

<script type="text/javascript">
	$(document).ready(function() {
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
    


</script>

<!--삭제버튼을 눌렀을때 -->
<script type="text/javascript">
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
                 
       

</script>       

