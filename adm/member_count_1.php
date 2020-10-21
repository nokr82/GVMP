<?php
$sub_menu = "200850";
include_once('./_common.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/myOffice/dbConn.php');


auth_check($auth[$sub_menu], 'r');

$g5['title'] = '판매/구매 전체 내역';
include_once('./admin.head.php');

// 최소년도 구함
$sql = " select min(vi_date) as min_date from {$g5['visit_table']} ";
$row = sql_fetch($sql);

$min_year = (int)substr($row['min_date'], 0, 4);
$now_year = (int)substr(G5_TIME_YMD, 0, 4);



// 엑셀파일 다운로드 버튼 링크 동적으로 만들기
$downHref = "member_count_excel.php?";
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
        <button onclick="window.location.href = '/adm/member_count.php'">전체 회원목록</button>
        <a href="<?=$downHref?>"><button>엑셀파일 다운로드</button></a>
    </div>
</div>

<div id="datePick">
    <form name="fvisit" id="fvisit" class="local_sch03 local_sch" method="get">     
        <div class="sch_last" id="date">
            <strong>기간별검색</strong>
            
            <label for="sfl" class="sound_only">검색대상</label>
            <select name="sfl" id="sfl">
            	<option value="no">선택안함</option>
                <option value="mb_name">이름</option>
                <option value="mb_id">회원아이디</option>
                <option value="productName">상품명</option>
            </select>
            <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
            <input type="text" name="stx" id="stx" class="frm_input">
            <!-- <input type="submit" class="btn_submit" value="검색"> -->
            
            &emsp;<i class="fa fa-calendar" aria-hidden="true"></i>
            <input type="text" placeholder="시작일" name="fr_date" id="Datepicker1" class="frm_input" size="11" maxlength="10" value="<?=$Datepicker1?>">
            <label for="fr_date" class="sound_only">시작일</label>
            ~
            <input type="text" placeholder="종료일"  name="la_date" id="Datepicker2" class="frm_input" size="11" maxlength="10" value="<?=$Datepicker2?>">
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
<section class="total_history">
    <div class="total_history_Table">
        <table>
            <thead>
                <tr>
                    <th>주문서번호</th>
                    <th>상품명</th>
                    <th>주문일시</th>
                    <th>회원이름(ID)</th>
                    <th>주문/판매/광고</th>
                    <th>커미션</th>
                    <th>삭제</th>
                </tr>
            </thead>
            
            <!-- 주문내역 -->
            <form method="POST" action="member_count_update.php" id="member_cash_update">
            <tbody id="orderTB">
            <?php
            
                $sql = "select OL.*, GM.mb_name from orderList as OL inner join g5_member as GM on OL.mb_id = GM.mb_id ";
                
                if( $_GET['sfl'] == 'mb_name' ) {
                    $sql .= "where GM.mb_name = '{$_GET['stx']}'";
                } else if( $_GET['sfl'] == 'mb_id' ) {
                    $sql .= "where GM.mb_id = '{$_GET['stx']}'";
                } else if( $_GET['sfl'] == 'productName' ){
                    $sql .= "where OL.productName = '{$_GET['stx']}'";
                } else if( $_GET['sfl'] == 'no' ){
                    $sql .= "where ";
                }
                
                if( $_GET['sfl'] != 'no' && $_GET['fr_date'] != "" && $_GET['la_date'] != "" ) {
                    $sql .= " and ";
                }
                
                if( $_GET['fr_date'] != "" && $_GET['la_date'] != "" ) {
                    $sql .= " orderDate >= '{$_GET['fr_date']}' and orderDate <= '{$_GET['la_date']}' ";
                }
                
                
                
                $sql .= "order by orderDate desc";
                
            
                $result = mysql_query($sql);
                
                $i = 0;
                while ($row = mysql_fetch_array($result)) {
                       $i++;
                       //<input type='text' value='￦".number_format($row['commission'])."'>
                       if( $i % 2 == 0 ) {
                           echo "<tr id=\"\" style=\"background-color: #e9ebf9;\">
                               <td><input type='text' value='{$row['n']}' readonly name='n{$i}'></td>
                            	<td><input type='text' class=\"sellinput\" value='{$row['productName']}' readonly name='productName{$i}'></td>
                                <td><input type='text' value='{$row['orderDate']}' readonly name='orderDate{$i}'></td>
                                <td>{$row['mb_name']}({$row['mb_id']})</td>
                                <td><input type='text' class=\"sellinput\" value='".number_format($row['money'])."' readonly name='money{$i}'>원</td>
                                <td><input type='text' class=\"sellinput\" value='".number_format($row['commission'])."' readonly name='commission{$i}'>원</td>
                                <td><input type='button' class=\"removebox\" value='삭제'></td>
                              </tr>";
                       } else {
                           echo "<tr id=\"\">
                               <td><input type='text' value='{$row['n']}' readonly name='n{$i}'></td>
                            	<td><input type='text' class=\"sellinput\" value='{$row['productName']}' readonly name='productName{$i}'></td>
                                <td><input type='text' value='{$row['orderDate']}' readonly name='orderDate{$i}'></td>
                                <td>{$row['mb_name']}({$row['mb_id']})</td>
                                <td><input type='text' class=\"sellinput\" value='".number_format($row['money'])."' readonly name='money{$i}'>원</td>
                                <td><input type='text' class=\"sellinput\" value='".number_format($row['commission'])."' readonly name='commission{$i}'>원</td>
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
        $('#member_cash_update').submit();
    }
</script>

<!--삭제버튼을 눌렀을때 -->
<script type="text/javascript">
    $(".removebox").on("click", function(){
        if (confirm('정말 삭제하시겠습니까??') == true){    //확인
          $(this).parents('tr').children('td').eq(1).children('input').attr("value","삭제");
          $(this).parents('tr').children('td').eq(2).children('input').attr("value","삭제");
          $(this).parents('tr').children('td').eq(4).children('input').attr("value","삭제");
          $(this).parents('tr').children('td').eq(5).children('input').attr("value","삭제");

        }else{   //취소
            return;
        }
    });
                 
       

</script>       


        


















<?php


?>




















