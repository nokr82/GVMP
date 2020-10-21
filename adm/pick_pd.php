<?php
$sub_menu = "200870";
include_once('./_common.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/Classes/PHPExcel.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/Classes/PHPExcel/IOFactory.php");


auth_check($auth[$sub_menu], 'r');

$g5['title'] = '신청제품관리';
include_once('./admin.head.php');
include_once('./admin_menu_check.php');

if ($is_admin != 'super') {
     menu_check($sub_menu,$member['mb_id']);
}



?>

<link rel="stylesheet" href="./css/admin_pick_pd.css" type="text/css" />  
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

<div id="search_wrap">
    <div id="list_btn">
        <button>엑셀파일 다운로드</button>
    </div>
</div>

<form action="pick_pd.php" method="GET" id="pickForm">
<div class="pick_serch clearfix">
    <p class="pick_serch_head"><strong>기간검색</strong></p>
    <div>
        <input type="text" id="Datepicker1" class="date_input" name="Datepicker1" autocomplete=off value="<?=$_GET['Datepicker1']?>">
        <span>~</span>
        <input type="text" id="Datepicker2" class="date_input" name="Datepicker2" autocomplete=off value="<?=$_GET['Datepicker2']?>">
    </div>
    
    <a class="btn_pick" onclick="pickForm()">검색</a>
    

    </a>
</div>
</form>







<section class="total_history">
    <div class="total_history_Table">
        <form action="" method="" id="pick_pd">
            



        <table>
            <thead>
                <tr>
                    <th class="tab_3">아이디</th>
                    <th class="tab_6">VM가입일</th>
                    <th class="tab_1">상품명</th>
                    <th class="tab_2">이름</th>
                    <th class="tab_3">전화번호</th>
                    <th class="tab_5">주소</th>
                    <th class="tab_5">상세주소</th>
                </tr>
            </thead>
         
            <tbody id="orderTB">
<?php
    if( $_GET['Datepicker1'] != "" && $_GET['Datepicker2'] != "" ) {
        $result = mysql_query("SELECT 
    b.mb_id,
    a.date,
    d.it_name,
    c.name,
    c.hp,
    c.address1,
    c.address2
FROM
    dayPoint AS a
        INNER JOIN
    g5_member AS b ON a.mb_id = b.mb_id
        INNER JOIN
    selectedProducts AS c ON b.mb_id = c.mb_id
        INNER JOIN
    g5_shop_item AS d ON c.productNumber = d.it_id
WHERE
    a.date >= '{$_GET['Datepicker1']}'
        AND a.date <= '{$_GET['Datepicker2']}'
        AND (a.way = 'renewal' OR a.way = 'autoRenewal' OR a.way = 'newRenewal' OR a.way = 'autoPass')
        AND a.VMR != 20000 order by a.date");
        
        while( $row = mysql_fetch_array( $result ) ) {
            echo "<tr><td>{$row['mb_id']}</td><td>{$row['date']}</td><td>{$row['it_name']}</td><td>{$row['name']}</td><td>{$row['hp']}</td><td>{$row['address1']}</td><td>{$row['address2']}</td></tr>";
        }
        
    }
?>
               
                
            </tbody>
        </table>
        </form>
    </div>
    </section>
<?php
include_once('./admin.tail.php');
?>


        


        
        
<!--편집버튼 누르면 v랑 x버튼--> 

<script type="text/javascript">
    $("#list_btn").on("click", function(){
        window.location.href = "http://gvmp.company/adm/pick_pd_excel.php?Datepicker1=" + $("#Datepicker1").val() + "&Datepicker2=" + $("#Datepicker2").val();
    });
    
//    function excelDown() {
//        window.location.href = "http://gvmp.company/adm/pick_pd_excel.php?Datepicker1=" + $("#Datepicker1").val() + "&Datepicker2=" + $("#Datepicker2").val();
//    }
    
    
    function pickForm() {
        if( $("#Datepicker1").val() == "" || $("#Datepicker2").val() == "" ) {
            alert("검색하실 시작 날짜와 종료 날짜를 모두 입력 바랍니다.");
            return false;
        }
        
        document.getElementById('pickForm').submit();
    }
	</script>
