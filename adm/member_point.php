<?php
$sub_menu = "200840";
include_once('./_common.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/Classes/PHPExcel.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/Classes/PHPExcel/IOFactory.php");
include_once ($_SERVER['DOCUMENT_ROOT'].'/myOffice/point_pop_back_func.php');


auth_check($auth[$sub_menu], 'r');

include_once('./admin_menu_check.php');

if ($is_admin != 'super') {
     menu_check($sub_menu,$member['mb_id']);
}


$g5['title'] = '포인트 내역';
include_once('./admin.head.php');

if( $_GET['fr_date'] == "" && $_GET['la_date'] == "" ) {
    $_GET['fr_date'] = date("Y-m-d");
    $_GET['la_date'] = date("Y-m-d");
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




?>
<link rel="stylesheet" href="./css/admin_cash.css" type="text/css" />  
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
        <button onclick="window.location.href = '/adm/member_point.php'">전체 회원목록</button>
        <a href="<?=$downHref?>"><button>엑셀파일 다운로드</button></a><br>
        <!-- <span>※ 전체 회원목록은 오늘을 기준으로<br>한달치에 대한 기간으로 전체 회원의 조회가 진행됩니다.</span> -->
    </div>
</div>

<div id="datePick">
    <form name="fvisit" id="fvisit" class="local_sch03 local_sch" method="get">     
        <div class="sch_last" id="date">
            <strong>기간별검색</strong>
            <label for="sfl" class="sound_only">검색대상</label>
            <select name="sfl" id="sfl">
            	<option value="no">선택안함</option>
                <option value="mb_name" <?php if($_GET["sfl"]=="mb_name") {echo 'selected="true"';} ?>>이름</option>
                <option value="mb_id" <?php if($_GET["sfl"]=="mb_id") {echo 'selected="true"';} ?>>회원아이디</option>
            </select>
            <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
            <input type="text" name="stx" id="stx" class="frm_input" value="<?=$_GET["stx"]?>">
            <!-- <input type="submit" class="btn_submit" value="검색"> -->
            
            &emsp;<i class="fa fa-calendar" aria-hidden="true"></i>
            <input type="text" placeholder="시작일" name="fr_date" id="Datepicker1" class="frm_input" size="11" maxlength="10" value="<?=$_GET["fr_date"]?>">
            <label for="fr_date" class="sound_only">시작일</label>
            ~
            <input type="text" placeholder="종료일"  name="la_date" id="Datepicker2" class="frm_input" size="11" maxlength="10" value="<?=$_GET["la_date"]?>">
            <label for="fr_date" class="sound_only">종료일</label>
            <input type="submit" value="검색" class="btn_submit">
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
※ 동시에 여러 데이터를 수정 및 삭제할 수  있습니다.<br>
※ 수정 진행시 실제 회원의 보유 포인트가 대량 변경이 진행되기에 <font style="color:red; font-size:15px;">실수가 발생되지 않도록 각별히 주의하시기 바랍니다.</font><br>
※ 수정 진행시 가능하면 계정 또는 기간을 설정하여 최대한 수정되는 데이터 양을 줄이시는게 처리 속도와 안전성도 높일 수 있습니다.<br><br>
※ 200,000원을 100,000원으로 변경하면 보유 포인트가 100,000원 <font style="color:blue;">차감</font>됩니다.<br>
※ 100,000원을 200,000원으로 변경하면 보유 포인트가 100,000원 <font style="color:blue;">증가</font>됩니다.<br><br>

<section class="total_history">
    <div class="total_history_Table">
        <table>
            <thead>
                <tr>
                    <th>데이터번호</th>
                    <th>회원이름(ID)</th>
                    <th>VMC</th>
                    <th>VMP</th>
                    <th>VMM</th>
                    <th>금고</th>
                    <th>bizMoney</th>
                    <th>내용</th>
                    <th>날짜</th>
                    <th>삭제</th>
                </tr>
            </thead>
            
            
            
            <!-- 주문내역 -->
            <form action="member_point_update.php" method="POST" id="pointForm">
            <tbody id="orderTB">
                
            <?php
            
                $sql = "select DP.*, MB.mb_name from dayPoint as DP inner join g5_member as MB on DP.mb_id = MB.mb_id ";
                
                if( $_GET['sfl'] == 'mb_name' ) {
                    $sql .= "where MB.mb_name = '{$_GET['stx']}'";
                } else if( $_GET['sfl'] == 'mb_id' ) {
                    $sql .= "where MB.mb_id = '{$_GET['stx']}'";
                } else if( $_GET['sfl'] == 'no' ){
                    $sql .= "where ";
                }
                
                if( $_GET['sfl'] != 'no' && $_GET['fr_date'] != "" && $_GET['la_date'] != "" ) {
                    $sql .= " and ";
                }
                
                
                
                if( $_GET['fr_date'] != "" && $_GET['la_date'] != "" ) {
                    $sql .= " date >= '{$_GET['fr_date']} 00:00:00' and date <= '{$_GET['la_date']} 23:59:59' ";
                }
 
                $sql .= "order by date desc";
           
                $result = mysql_query($sql);
                

                
                $i = 0;
                while ($row = mysql_fetch_array($result)) {
                     if( $row['VMC']+$row['VMP']+$row['VMM']+$row['VMG']+$row['bizMoney'] == 0 ) {
                         continue;
                     }
                     
                     $i++;
                       
                       if( $i % 2 == 0 ) {
                           echo "<tr id=\"\" style=\"background-color: #e9ebf9;\">
                               <td><input type='text'  value='{$row['no']}' name='dataN{$i}' readonly></td>
                            	<td>{$row['mb_name']}({$row['mb_id']})</td>
                                <td><input type='text' class=\"sellinput\" value='"; echo number_format($row['VMC']) . "' name='VMC{$i}' readonly></td>
                                <td><input type='text' class=\"sellinput\" value='"; echo number_format($row['VMP']) . "' name='VMP{$i}' readonly></td>
                                <td><input type='text' class=\"sellinput\" value='"; echo number_format($row['VMM']) . "' name='VMM{$i}' readonly></td>
                                <td><input type='text' class=\"sellinput\" value='"; echo number_format($row['VMG']) . "' name='VMG{$i}' readonly></td>
                                <td><input type='text' class=\"sellinput\" value='"; echo number_format($row['bizMoney']) . "' name='bizMoney{$i}' readonly></td>
                                <td>".parsing($row['way'])."</td>
                                <td><input type='text' value='{$row['date']}' name='date{$i}' readonly></td>
                                <td><input type='button' class=\"removebox\" value='삭제'></td>    
                              </tr>";
                       } else {
                           echo "<tr id=\"\">
                               <td><input type='text' value='{$row['no']}' name='dataN{$i}' readonly></td>
                            	<td>{$row['mb_name']}({$row['mb_id']})</td>
                                <td><input type='text' class=\"sellinput\" value='"; echo number_format($row['VMC']) . "' name='VMC{$i}' readonly></td>
                                <td><input type='text' class=\"sellinput\" value='"; echo number_format($row['VMP']) . "' name='VMP{$i}' readonly></td>
                                <td><input type='text' class=\"sellinput\" value='"; echo number_format($row['VMM']) . "' name='VMM{$i}' readonly></td>
                                <td><input type='text' class=\"sellinput\" value='"; echo number_format($row['VMG']) . "' name='VMG{$i}' readonly></td>
                                <td><input type='text' class=\"sellinput\" value='"; echo number_format($row['bizMoney']) . "' name='bizMoney{$i}' readonly></td>
                                <td>".parsing($row['way'])."</td>
                                <td><input type='text' value='{$row['date']}' name='date{$i}' readonly></td>
                                <td><input type='button' class=\"removebox\" value='삭제'></td>    
                              </tr>";
                       }
                       
                    } 

                    
                    
                    
                    
                    
          
                    
                    
                    
                    
                    
                    
                    
                    
            ?>    
           
                
                
                
                
                
            </tbody>
            </form>
        </table>
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
                    $removeBox1.css({"display":"block"});
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
    
    function excelDown() {
        window.location = "member_cash_excel.php?dateS=" + $("#dateS").val() + "&cash_year=" + $("#cash_year_").val() + "&cash_month=" + $("#cash_month_").val() + "&cash_b=" + $("#cash_b_").val();
    }
    
    function member_cash_update() {
        if( confirm("수정 사항을 적용 하시겠습니까?") )
            $('#pointForm').submit();
    }
</script>

<!--삭제버튼을 눌렀을때 -->
<script type="text/javascript">
    $(".removebox").on("click", function(){
        if (confirm('정말 삭제하시겠습니까??') == true){    //확인
          $(this).parents('tr').children('td').eq(2).children('input').attr("value","삭제");
          $(this).parents('tr').children('td').eq(3).children('input').attr("value","삭제");
          $(this).parents('tr').children('td').eq(4).children('input').attr("value","삭제");
          $(this).parents('tr').children('td').eq(5).children('input').attr("value","삭제");
          $(this).parents('tr').children('td').eq(6).children('input').attr("value","삭제");
          $(this).parents('tr').children('td').eq(8).children('input').attr("value","삭제");

        }else{   //취소
            return;
        }
    });
                 
       

</script>       

