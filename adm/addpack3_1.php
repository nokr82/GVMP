<?php
$sub_menu = "200890";
include_once('./_common.php');


auth_check($auth[$sub_menu], 'r');

$g5['title'] = '애드팩 #3 정산';
include_once('./admin.head.php');


?>




<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.18/themes/base/jquery-ui.css" type="text/css" /> 
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>  
<script src="http://code.jquery.com/ui/1.8.18/jquery-ui.min.js"></script>  


<div class="add3pack clearfix">

    <form action="addpack3_back.php" method="POST">
    <div class="add3_id">날짜</div>
    <input type="text"  name="date_add3" id="Datepicker1" class="date_add3" size="11" maxlength="10" >
    <div class="add3_id">아이디</div>
    <input type="text" class="add3_text" id="add3_id" name="mb_id" placeholder="ID를 입력하세요" autofocus>
    
    <input type="submit" value="승인" class="add3_submit">
    </form>
    
</div>
<br>※ 아이디 입력 시 8자리 모두 입력하지 않아도 됩니다. ex. 3571입력시 자동으로 00003571로 입력됩니다.
<br>※ 입력한 아이디 계정 종류가 아래에 해당시 입력한 아이디에게 포인트가 아래 금액 만큼 지급됩니다.
<br>&nbsp;&nbsp;&nbsp; - CU, VD : 200원, SM : 400원, VM : 700원
<br>※ 입력한 아이디 계정 종류가 아래에 해당시 입력한 아이디 직추에게 간접수익이 아래 금액 만큼 지급됩니다.
<br>&nbsp;&nbsp;&nbsp; - CU, VD : 500원, SM : 300원, VM : 없음
<br>※ 1일 최대 적립 가능 금액 : 5,000P
<script>
$('.add3_submit').click(function() { 
   
    var add3id = $("#add3_id").val();
    
    if(!add3id) {
        alert("ID를 입력하세요.");
        return false;
    }
        
    if(confirm('승인하시겠습니까?')) { 
       
        }else {
            return false;
        }
   });
   
$('#Datepicker1').val($.datepicker.formatDate('yy-mm-dd', new Date()));

$(function() {
        $( "#Datepicker1" ).datepicker({
            
          	changeMonth: true, 
            dayNames: ['월요일', '화요일', '수요일', '목요일', '금요일', '토요일', '일요일'],
            dayNamesMin: ['월', '화', '수', '목', '금', '토', '일'], 
            monthNamesShort: ['1','2','3','4','5','6','7','8','9','10','11','12'],
            monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
            dateFormat: 'yy-mm-dd'
        });
    });


</script>

<?php
include_once('./admin.tail.php');
?>
