<?php
	include './inc/title.php';
	include_once('./_common.php');
	include_once('./dbConn.php');
	include_once('./OrganizationChartInfo.php');
        include_once('./getMemberInfo.php');
	
	if($is_guest) // 로그인 안 했을 때 로그인 페이지로 이동
	    header('Location: /bbs/login.php');
        
        $mainID;
        if( isset( $_GET['mb_id'] ) ) {
            $mainID = $_GET['mb_id'];
        } else {
            if( $member['mb_id'] == 'admin' ) {
                $mainID = '00000001';
            } else {
                $mainID = $member['mb_id'];
            }
        }
        
        $sYear;
        $sMon;
        $eYear;
        $eMon;
        
        if( isset($_GET['sYear']) && isset($_GET['sMon']) && isset($_GET['eYear']) && isset($_GET['eMon']) ) {
            $sYear = $_GET['sYear']; // 시작 년도
            $sMon = $_GET['sMon']; // 시작 월
            $eYear = $_GET['eYear']; // 끝 년도
            $eMon = $_GET['eMon']; // 끝 월
        } else {
            $sYear = date("Y"); // 시작 년도
            $sMon = date("m"); // 시작 월
            $eYear = date("Y"); // 끝 년도
            $eMon = date("m"); // 끝 월
        }
?>
<?php 
$result = mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'");
$row = mysql_fetch_array($result); 
if( !($row['accountType'] == 'VM') && !($row['mb_id'] == 'admin') ) {
    echo alert('VM회원만 접근 권한이 있습니다.');
    $prevPage = $_SERVER['HTTP_REFERER'];
    header('location:'.$prevPage);
}
?>
<?php 
$renewal = mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'");
$row2 = mysql_fetch_array($renewal); 

$dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row2['renewal']}', interval +4 month) AS date"));
$dateCheck_2 = mysql_fetch_array(mysql_query("SELECT date_add('{$row2['renewal']}', interval +5 month) AS date"));
$timestamp = $dateCheck_1["date"];
$timestamp2 = $dateCheck_2["date"];
$now = date("Y-m-d");

$timestamp_temp = strtotime($timestamp);
$timestamp2_temp = strtotime($timestamp2);
$now_temp = strtotime($now);

if( $row2['accountType'] == 'VM' && ( $now_temp >= $timestamp_temp && $now_temp <= $timestamp2_temp ) ) {
    echo "<script>alert('{$timestamp2}이 되면 CU 계정으로 변경되오니 리뉴얼을 진행해 주시기 바랍니다.');</script>";
    $prevPage = $_SERVER['HTTP_REFERER'];
    header('location:'.$prevPage);
}
?>
<link rel="stylesheet" href="./css/sales.css">
</head>
<body>
    <!-- header -->
<?php
    include_once('../shop/shop.head.php');
?>
<input type="hidden" id="loginID" value="<?=$member['mb_id']?>">
<input type="hidden" id="mb_id" value="<?=$_GET['mb_id']?>">
    <!-- 산하 매출정보 -->
    <section class="sales_title">
        <div class="title">
            <h2>직접추천정보</h2>
        </div>
        
        <div id="line"></div>
<!--        <div class="sear_box">
            <span>기간</span>
            <img src="images/date.png" alt="">
            <select id="start_year" name="start_year">
                <option value="2018" <?php  echo ($sYear == "2018") ? "selected" : "" ?>>2018</option>
                <option value="2019" <?php  echo ($sYear == "2019") ? "selected" : "" ?>>2019</option>
                <option value="2020" <?php  echo ($sYear == "2020") ? "selected" : "" ?>>2020</option>
                <option value="2021" <?php  echo ($sYear == "2021") ? "selected" : "" ?>>2021</option>
                <option value="2022" <?php  echo ($sYear == "2022") ? "selected" : "" ?>>2022</option>
                <option value="2023" <?php  echo ($sYear == "2023") ? "selected" : "" ?>>2023</option>
                <option value="2024" <?php  echo ($sYear == "2024") ? "selected" : "" ?>>2024</option>
                <option value="2025" <?php  echo ($sYear == "2025") ? "selected" : "" ?>>2025</option>
                <option value="2026" <?php  echo ($sYear == "2026") ? "selected" : "" ?>>2026</option>
                <option value="2027" <?php  echo ($sYear == "2027") ? "selected" : "" ?>>2027</option>
            </select>
            <select id="start_month" name="start_month">
                <option value="1" <?php  echo ($sMon == "1") ? "selected" : "" ?>>1</option>
                <option value="2" <?php  echo ($sMon == "2") ? "selected" : "" ?>>2</option>
                <option value="3" <?php  echo ($sMon == "3") ? "selected" : "" ?>>3</option>
                <option value="4" <?php  echo ($sMon == "4") ? "selected" : "" ?>>4</option>
                <option value="5" <?php  echo ($sMon == "5") ? "selected" : "" ?>>5</option>
                <option value="6" <?php  echo ($sMon == "6") ? "selected" : "" ?>>6</option>
                <option value="7" <?php  echo ($sMon == "7") ? "selected" : "" ?>>7</option>
                <option value="8" <?php  echo ($sMon == "8") ? "selected" : "" ?>>8</option>
                <option value="9" <?php  echo ($sMon == "9") ? "selected" : "" ?>>9</option>
                <option value="10" <?php  echo ($sMon == "10") ? "selected" : "" ?>>10</option>
                <option value="11" <?php  echo ($sMon == "11") ? "selected" : "" ?>>11</option>
                <option value="12" <?php  echo ($sMon == "12") ? "selected" : "" ?>>12</option>
            </select> ~
            <img src="images/date.png" alt="">
            <select id="end_year" name="end_year">
                <option value="2018" <?php  echo ($eYear == "2018") ? "selected" : "" ?>>2018</option>
                <option value="2019" <?php  echo ($eYear == "2019") ? "selected" : "" ?>>2019</option>
                <option value="2020" <?php  echo ($eYear == "2020") ? "selected" : "" ?>>2020</option>
                <option value="2021" <?php  echo ($eYear == "2021") ? "selected" : "" ?>>2021</option>
                <option value="2022" <?php  echo ($eYear == "2022") ? "selected" : "" ?>>2022</option>
                <option value="2023" <?php  echo ($eYear == "2023") ? "selected" : "" ?>>2023</option>
                <option value="2024" <?php  echo ($eYear == "2024") ? "selected" : "" ?>>2024</option>
                <option value="2025" <?php  echo ($eYear == "2025") ? "selected" : "" ?>>2025</option>
                <option value="2026" <?php  echo ($eYear == "2026") ? "selected" : "" ?>>2026</option>
                <option value="2027" <?php  echo ($eYear == "2027") ? "selected" : "" ?>>2027</option>
            </select>
            <select id="end_month" name="end_month">
                <option value="1" <?php  echo ($eMon == "1") ? "selected" : "" ?>>1</option>
                <option value="2" <?php  echo ($eMon == "2") ? "selected" : "" ?>>2</option>
                <option value="3" <?php  echo ($eMon == "3") ? "selected" : "" ?>>3</option>
                <option value="4" <?php  echo ($eMon == "4") ? "selected" : "" ?>>4</option>
                <option value="5" <?php  echo ($eMon == "5") ? "selected" : "" ?>>5</option>
                <option value="6" <?php  echo ($eMon == "6") ? "selected" : "" ?>>6</option>
                <option value="7" <?php  echo ($eMon == "7") ? "selected" : "" ?>>7</option>
                <option value="8" <?php  echo ($eMon == "8") ? "selected" : "" ?>>8</option>
                <option value="9" <?php  echo ($eMon == "9") ? "selected" : "" ?>>9</option>
                <option value="10" <?php  echo ($eMon == "10") ? "selected" : "" ?>>10</option>
                <option value="11" <?php  echo ($eMon == "11") ? "selected" : "" ?>>11</option>
                <option value="12" <?php  echo ($eMon == "12") ? "selected" : "" ?>>12</option>
            </select>
            <span id="date_select"><a href="javascript:;" style="color:#fff" onclick="termSetting();">기간설정</a></span>
            <p id="date_impor">※검색한 회원을 기준으로 기간설정 하실 때는 회원검색 먼저 해주세요.</p>
        </div>-->
        <div class="partner">
            <div class="boxSearBox">
                <form onsubmit="return false;">
                <button type="button" onclick="searchClick();"><i class="fa fa-search" aria-hidden="true"></i></button>
                <input type="text" placeholder="회원검색" id="searchInput" onkeydown="Enter_Check();"/>
                <div class="search_info">
                		<table id="search_info_tbl">
                        <thead>
                            <tr>
                                <th>이름</th>
                                <th>추천인</th>
                                <th>직급</th>
                                <th>리뉴얼</th>
                            </tr>
                        </thead>
                        <tbody>
     
                        </tbody>
                	</table>
                	</div>
                
                
                    <div id="select_box">
                        <label for="nameNick">이름</label>
                        <select id="nameNick">
                            <option value="이름">이름</option>
                            <option value="아이디">회원아이디</option>
                        </select>
                    </div>
                <div class="clr"></div>
           		</form>
                <div class="clr">
                </div>
            </div>
        </div>
        <div id="line2"></div>
        <!--
        <div class="btn">
            <a href="javascript:;"><img src="images/all_member_bt.gif" alt=""></a>
            <a href="javascript:;"><img src="images/print_bt.gif" alt=""></a>
        </div>
        -->
        <div class="salesTableW">
        	<table>
                <thead>
                    <tr>
                        <th>이름</th>
                        <th>회원 ID</th>
                        <th>[직급 1팀-2팀-3팀]</th>
                        <th>리뉴얼</th>
                    </tr>
                </thead>
                <tbody>

<?php 
    mysql_query("set session character_set_connection=utf8");
    mysql_query("set session character_set_results=utf8");
    mysql_query("set session character_set_client=utf8");
    
   $new_result = mysql_query("select b.renewal, a.mb_id, b.mb_name, b.accountRank from genealogy as a inner join g5_member as b on a.mb_id = b.mb_id where a.recommenderID = '{$mainID}'"); 



    while($new_row = mysql_fetch_array($new_result))
    {	
        $reT = teamCount($new_row['mb_id'], true);
        $re = memberColor($new_row['accountRank']);
        
        $new_result2 = mysql_query("SELECT 
         mb_id , SUM(VMC+VMR+VMP) AS total
        FROM
            dayPoint
        WHERE
            date >= '{$sYear}-{$sMon}-01'
            AND date < '{$eYear}-{$eMon}-31'
            AND mb_id = '{$new_row['mb_id']}'");      
        $total = 0;
         while( $new_row2 = mysql_fetch_array($new_result2) ) {
            if($new_row2['mb_id'] == $new_row['mb_id']){
             $total = $new_row2['total'];
            }
        }
        $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$new_row['renewal']}', interval +4 month) AS date"));
        $timestamp = $dateCheck_1["date"];
       
        if( $member['mb_id'] == 'admin' ) {
            echo ("
        <tr>
                <td class='name1'><span onclick=\"memberlogin(this);\" style= \"cursor:pointer; background-color: {$re};\">{$new_row['mb_name']}</span></td>
                <td class='id1'>{$new_row['mb_id']}</td>
                <td>{$new_row['accountRank']} {$reT[0]}-{$reT[1]}-{$reT[2]}</td>
                <td>{$total} ￦</td>
        </tr>");
        } else {
            echo ("
        <tr>
                <td class='name1'><span style= \"background-color: {$re};\">{$new_row['mb_name']}</span></td>
                <td class='id1'>{$new_row['mb_id']}</td>
                <td>{$new_row['accountRank']} {$reT[0]}-{$reT[1]}-{$reT[2]}</td>
                <td>{$timestamp}</td>
        </tr>");
        }
        

    }

?>



<!--
                    <tr>
                        <td class="name1"><span></span></td>
                        <td>001000000987</td>
                        <td>팀장 3-5-2</td>
                        <td>￦135.000.500</td>
                    </tr>
                    <tr>
                        <td class="name2"><span>홍길동</span></td>
                        <td>001000000987</td>
                        <td>팀장 3-5-2</td>
                        <td>￦135.000.500</td>
                    </tr>
                    <tr>
                        <td class="name3"><span>홍길동</span></td>
                        <td>001000000987</td>
                        <td>팀장 3-5-2</td>
                        <td>￦135.000.500</td>
                    </tr>

-->
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>
<?php
    include_once('../shop/shop.tail.php');
?>
<script src="js/main.js"></script>
<script>
    function searchClick() {
   if( $("#nameNick option:selected").val() == "아이디" ) {
       var data = "searchID=" + $('#searchInput').val() + "&loginID=" + $('#loginID').val();

       $.ajax({
           url:'./memberIDSearch.php',
           type:'POST',
           data: data,
           success:function(result){
               if( result == "true" ) {
                   location.href='/myOffice/sales.php?mb_id=' + $('#searchInput').val();
               } else {
                   alert("검색할 수 없는 아이디입니다.");
               }
           }
       });


   } else if( $("#nameNick option:selected").val() == "이름" ) {

       var data = "searchName=" + $('#searchInput').val() + "&loginID=" + $('#loginID').val();

       $.ajax({
           url:'./memberNameSearch.php',
           type:'POST',
           data: data,
           success:function(result){

               var json = JSON.parse(result);  

                var submenu = $(".search_info");
                if( submenu.is(":visible") ){
                    $('#search_info_tbl > tbody:last > tr').remove();
                }

               if( json.length == 0 ) {
                   alert("검색 결과가 없습니다."); exit();
               }

               var submenu = $(".search_info");
               submenu.show();
//                       var submenu = $(".search_info");
//                       submenu.slideDown();
               for( var i=0 ; i<json.length ; i++ ) {
                   $('#search_info_tbl > tbody:last').append('<tr id=\"'+json[i][1]+'\" onclick=\"searchSelect(this.id);\"><td>'+json[i][0]+'</td><td>'+json[i][2]+'</td><td>'+json[i][3]+'</td><td>9999-99-99</td></a></tr>');
               }
           }
       });
   }
}


    function Enter_Check(){ // 회원검색에서 엔터 키 입력하면 검색 진행하게 하는 함수
        if(event.keyCode == 13){
             searchClick();
        }
    }
    
    function searchSelect(id) {
        window.location.href = '/myOffice/sales.php?mb_id=' + id;
    }
    
    function termSetting() {
        var url = '/myOffice/sales.php?sYear='+$("#start_year option:selected").val()+
                '&sMon='+$("#start_month option:selected").val()+
                '&eYear='+$("#end_year option:selected").val()+
                '&eMon='+$("#end_month option:selected").val();
        
        if( $("#mb_id").val() != "" ) {
            url = url + "&mb_id=" + $("#mb_id").val();
        }
        
        window.location.href=url;
    }
    
    function memberlogin(val){
       var mb_id = $(val).closest('tr').find(".id1").text();
       
         window.location.href="../bbs/login_check.php?mb_id="+mb_id;
      
    }
    
</script>