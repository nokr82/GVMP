<?php
    include_once('./inc/title.php');
    include_once('./dbConn.php');
    include_once('./dbConn2.php');
    include_once('./_common.php');
    include_once('./getMemberInfo.php');
    include_once('./OrganizationChartInfo.php');
?>

<link rel="stylesheet" href="./css/mindmap.css">
<link rel="stylesheet" href="./css/allowance.css">


</head>
<!-- header -->
<?php
    include_once('../shop/shop.head.php');
    
    // 최하단 알아내는 함수
    function lower( $id ) {
        $row = mysql_fetch_array( mysql_query("select * from teamMembers where mb_id = '{$id}'") );
        $re;
        if( $row['1T_ID'] != "" )
            $re = lower( $row['1T_ID'] );
        else
            return $id;
        
        return $re;
    }
    
    
    
    
                                $mainID = "";
                                $teamCountUser = '';
                                if( $member['mb_id'] == 'admin' ) {
                                    $teamCountUser = '00000001';
                                } else {
                                    $teamCountUser = $member['mb_id'];
                                }
                        	if( isset( $_GET['mb_id'] ) ) {
                                    teamCount($teamCountUser, false);


                                    $check = 0;
                                    foreach ($mb_info_1T as $value) {
                                        if( $value[1] == $_GET['mb_id'] )
                                            $check++;
                                    }
                                    foreach ($mb_info_2T as $value) {
                                        if( $value[1] == $_GET['mb_id'] )
                                            $check++;
                                    }

                                    if( $_GET['mb_id'] == $member['mb_id'] )
                                        $check++;



                                    if( $check != 0 ) {
                                        $mainID = $_GET['mb_id'];
                                    } else {
                                        alert("검색할 수 없는 회원입니다.");
                                        $prevPage = $_SERVER['HTTP_REFERER'];
                                        header('location:'.$prevPage);
                                    }

                                    foreach ($mb_info_1T as $value) {
                                        $key = array_search( $mb_info_1T, $value );
                                        array_splice( $mb_info_1T, $key, 1 );
                                    }
                                    foreach ($mb_info_2T as $value) {
                                        $key = array_search( $mb_info_2T, $value );
                                        array_splice( $mb_info_2T, $key, 1 );
                                    }

                        	} else {
                                    if( $member['mb_id'] == 'admin' ) {
                                       $mainID = '00000001';
                                    } else {
                                        $mainID = $member['mb_id'];
                                    }
                        	}

                                // 1팀, 2팀 최하단이 누구인지 변수에 담아두기
//                                $lowerR = mysql_fetch_array( mysql_query("select * from teamMembers where mb_id = '{$mainID}'") );
//                                $lower1T_ID = lower( $lowerR['1T_ID'] );
//                                $lower2T_ID = lower( $lowerR['2T_ID'] );

?>
<?php
$result = mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'");
$row = mysql_fetch_array($result);
if( !($row['accountType'] == 'VM') && !($row['mb_id'] == 'admin') ) {
    echo alert('VM회원만 접근 권한이 있습니다.', '/myOffice/index.php');
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

if ($row2['accountType'] == 'VM' && ($now_temp > $timestamp_temp && $now_temp <= $timestamp2_temp)) {
    alert($timestamp2.'이 되면 CU 계정으로 변경되오니 리뉴얼을 진행해 주시기 바랍니다.');
//    echo "<script>alert('{$timestamp2}이 되면 CU 계정으로 변경되오니 리뉴얼을 진행해 주시기 바랍니다.');</script>";
//    $prevPage = $_SERVER['HTTP_REFERER'];
//    header('location:' . $prevPage);
}

    $TopIDWhere;
    
    if( isset($_GET['mb_id']) ) {
        $TopIDWhere = $_GET['mb_id'];
    } else {
        if( $member['mb_id'] != 'admin' )
            $TopIDWhere = $member['mb_id'];
        else {
            $TopIDWhere = '00000001';
        }
    }

    $resultTopID = mysql_query("select * from genealogy where mb_id = '{$TopIDWhere}'");
    $rowTopID = mysql_fetch_array($resultTopID);

    $TopID;
    if( $rowTopID['sponsorID'] == "00000000" ) {
        $TopID = "#";
    } else {
        $TopID = "/myOffice/box.php?mb_id=" . $rowTopID['sponsorID'];
    }

?>
<body>

<input type="hidden" id="loginID" value="<?=$member['mb_id']?>">

<!-- 컨텐츠 -->
    <section class="boxSection clearfix">
        <h3>수당체계</h3>
        <div class="boxBtn-group clearfix">
            <a href="sales_1.php" class="list-btn list-tree"><!-- img src="images/list.png" / --> 직접추천정보</a>
            <a href="tree.php" class="list-btn list-tree"><!-- img src="images/list.png" / --> 리스트형</a>
            <a href="box_1.php" class="list-btn list-tree"><!-- img src="images/list.png" / --> 관리형</a>
           
        </div> 
        <div class="boxSearBox">
            <a href="/myOffice/allowance_1.php" class="boxBtn boxTree_btn boxTree_btn0-1">나의 조직도</a>
            <a id="boxPrinBt" class="boxBtn boxTree_btn boxTree_btn0-2" href="#" onclick="javascript:content_print();" ><!-- img src="images/print_bt.gif" / --> 조직도 인쇄</a>
            <!-- 버튼 추가 -->
            <a href="#" class="boxTree_btn boxTree_btn1" onclick="mindUp();">위로가기</a>
            <a href="#myName"  class="boxTree_btn boxTree_btn2" >중앙으로</a>
            <a href="#last"  class="boxTree_btn boxTree_btn3" >맨아래</a>
           
           <script>
           $( window ).load(function() {
               location.href = '#myName';
                $('ol li').last().addClass('ooon');
                $('.ooon').attr('id','last');
                
                
            });
//            $('.boxTree_btn').click(function(){
//                var scrollPosition = $($(this).attr("data-target")).offset().top;
//                $(".mindmap").animate({'scrollTop': scrollPosition}, 500);
//            }); data-target="#myName"/
//                var $root = $('body, .mindmap');
//                $('.boxSearBox > a.boxTree_btn').click(function() {
//                    $root.animate({
//                        scrollTop: $( $.attr(this, 'href') ).offset().top}, 2500);
//
//                });
//                $(".boxTree_btn").click(function(event){            
//                       event.preventDefault();
//                       $('html, body').animate({scrollTop:$(this.hash).offset().top}, 500);
//                  });
                
           
         function mindUp(){
             var upMove = $('.children__item').eq(0).offset().top;
             var chHeight = $('.children__item').innerHeight();
             var firstHeight = chHeight*2;
             $('.mindmap').animate({top:0});
             $('#printArea').animate({scrollTop:0});
           }
           
     //      function mindMid(){
     //       var midMove = $('.node_main').innerHeight();
     //       var chAllHeight = $('.children').innerHeight();
      //      var chHeight = $('.children__item').innerHeight(); 
    //        var midHeight = ('-'+chAllHeight/2)-('-'+midMove/2);
      //      var test__1 = $('#myName').offset().top;
//
      //       $('.mindmap').animate({top:midHeight});   
            // $('#printArea').animate({scrollTop:midMove});
     //      }
           
//s


           
           
           </script>
            
            <span class="boxSearBoxHiddenSpan"><br /></span>
            
            <form onsubmit="return false;" class="form-search clearfix">
                <div class="search_info">
                    <table id="search_info_tbl">
                        <thead>
                            <tr>
                                <th>이름</th>
                                <th>추천인</th>
                                <th>후원인</th>
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
                        <option value="이름" selected="selected">이름</option>
                        <option value="아이디" >회원아이디</option>
                    </select>
                </div>   
                <input type="text" placeholder="회원검색" id="searchInput" onkeydown="Enter_Check();"/>
                <button type="button" onclick="searchClick();" id="searchclick"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>
            
            <div class="clr">
            </div>

        </div><!--boxSearBox-->
        
            <div id="printArea" class="boxContentW">
            <div class="mindmap" id="minddrag" style="left:30%;"  >

            <ol class="children children_leftbranch">
<?php
    $mb_info_1T = array(); // 1Team의 하위 회원 정보 담을 배열
    $mb_info_2T = array(); // 2Team의 하위 회원 정보 담을 배열
    $nextID = array(); // mb_info 함수로 넘겨줄 ID 잠시 담는 용도...ㅎㅎ
    
    $result = mysql_query("select * from teamMembers where mb_id = '{$mainID}'");
    $row = mysql_fetch_array($result);






    if( $row['1T_ID'] != null ) {
        $result_1T = mysql_query("select * from genealogy as a inner join g5_member as b on a.mb_id=b.mb_id where a.mb_id='{$row['1T_ID']}'");
        $row_1T = mysql_fetch_array($result_1T);
        array_push( $mb_info_1T, array($row_1T['mb_name'], $row_1T['mb_id'], $row_1T['recommenderID'], $row_1T['recommenderName'], $row_1T['renewal'], $row_1T['accountType'], $row_1T['sponsorName'], $row_1T['mb_open_date'], $row_1T['accountRank'] ) );


        if (mysqli_multi_query($connection, "CALL SP_TREE('{$row['1T_ID']}');SELECT * FROM genealogy_tree WHERE rootid = '{$row['1T_ID']}'")) {
            mysqli_store_result($connection);
            mysqli_next_result($connection);
            do {
                /* store first result set */
                if ($result = mysqli_store_result($connection)) {
                    while ($row2 = mysqli_fetch_array($result)) {
                        array_push( $mb_info_1T, array($row2['mb_name'], $row2['mb_id'], $row2['recommenderID'], $row2['recommenderName'], $row2['renewal'], $row2['accountType'], $row2['sponsorName'], $row2['mb_open_date'], $row2['accountRank']) );
                    }
                    mysqli_free_result($result);
                }
            } while (mysqli_next_result($connection));
        } else {
            echo mysqli_error($connection);
        }
//        튜닝으로 인해 주석 처리
//        $result2 = mysql_query("select 
//                t.*, m.renewal, m.accountType, m.mb_open_date, m.accountRank
//        from
//        (SELECT c.mb_name AS mb_name
//           ,c.mb_id, c.sponsorID, c.sponsorName, c.recommenderID, c.recommenderName, v.level
//        FROM
//        (
//           SELECT f_category() AS mb_id, @level AS LEVEL
//           FROM (SELECT @start_with:='{$row['1T_ID']}', @id:=@start_with, @level:=0) vars
//              INNER JOIN genealogy
//           WHERE @id IS NOT NULL
//        ) v
//           INNER JOIN genealogy c ON v.mb_id = c.mb_id
//        ) t
//                INNER JOIN g5_member m ON t.mb_id = m.mb_id");
//
//        while( $row2 = mysql_fetch_array($result2) ) {
//            array_push( $mb_info_1T, array($row2['mb_name'], $row2['mb_id'], $row2['recommenderID'], $row2['recommenderName'], $row2['renewal'], $row2['accountType'], $row2['sponsorName'], $row2['mb_open_date'], $row2['accountRank']) );
//        }
    }




    if( $row['2T_ID'] ) {
        $result_2T = mysql_query("select * from genealogy as a inner join g5_member as b on a.mb_id=b.mb_id where a.mb_id='{$row['2T_ID']}'");
        $row_2T = mysql_fetch_array($result_2T);
        array_push( $mb_info_2T, array($row_2T['mb_name'], $row_2T['mb_id'], $row_2T['recommenderID'], $row_2T['recommenderName'], $row_2T['renewal'], $row_2T['accountType'], $row_2T['sponsorName'], $row_2T['mb_open_date'], $row_2T['accountRank']) );


        if (mysqli_multi_query($connection, "CALL SP_TREE('{$row['2T_ID']}');SELECT * FROM genealogy_tree WHERE rootid = '{$row['2T_ID']}'")) {
            mysqli_store_result($connection);
            mysqli_next_result($connection);
            do {
                /* store first result set */
                if ($result = mysqli_store_result($connection)) {
                    while ($row2 = mysqli_fetch_array($result)) {
                        array_push( $mb_info_2T, array($row2['mb_name'], $row2['mb_id'], $row2['recommenderID'], $row2['recommenderName'], $row2['renewal'], $row2['accountType'], $row2['sponsorName'], $row2['mb_open_date'], $row2['accountRank']) );
                    }
                    mysqli_free_result($result);
                }
            } while (mysqli_next_result($connection));
        } else {
            echo mysqli_error($connection);
        }
//        튜닝으로 인해 주석 처리
//        $result3 = mysql_query("select 
//                t.*, m.renewal, m.accountType, m.mb_open_date, m.accountRank
//        from
//        (SELECT c.mb_name AS mb_name
//           ,c.mb_id, c.sponsorID, c.sponsorName, c.recommenderID, c.recommenderName, v.level
//        FROM
//        (
//           SELECT f_category() AS mb_id, @level AS LEVEL
//           FROM (SELECT @start_with:='{$row['2T_ID']}', @id:=@start_with, @level:=0) vars
//              INNER JOIN genealogy
//           WHERE @id IS NOT NULL
//        ) v
//           INNER JOIN genealogy c ON v.mb_id = c.mb_id
//        ) t
//                INNER JOIN g5_member m ON t.mb_id = m.mb_id");
//
//        while( $row3 = mysql_fetch_array($result3) ) {
//            array_push( $mb_info_2T, array($row3['mb_name'], $row3['mb_id'], $row3['recommenderID'], $row3['recommenderName'], $row3['renewal'], $row3['accountType'], $row3['sponsorName'], $row3['mb_open_date'], $row3['accountRank']) );
//        }
    }


    // 리뉴얼 유예기간 인원 카운팅 제외를 위한 복제 작업
    $mb_info_1T_copy = $mb_info_1T;
    $mb_info_2T_copy = $mb_info_2T;

    # 1T, 2T 회원의 유예기간을 알아내기 위한 쿼리
    $nowTime = date("Y-m-d"); // 오늘 날짜
    $nowTime_str = strtotime($nowTime); // 오늘 날짜 스트링


    // 리뉴얼 유예기간일 시 복제한 배열에서 삭제하기
    foreach ($mb_info_1T_copy as $value) {
        $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$value[4]}', interval +4 month) AS date"));
        $rowGrace_1T_str_strart = $dateCheck_1["date"]; // 1T 리뉴얼 시작 날짜 스트링
        $rowGrace_1T_str_strart = strtotime($rowGrace_1T_str_strart);

        // 현재날짜가 유예기간 시작날짜 미만이면 거짓
        if( ! ($nowTime_str <= $rowGrace_1T_str_strart) || $value[5] == '회원탈퇴' ) {
            $key = array_search( $mb_info_1T_copy, $value );
            array_splice( $mb_info_1T_copy, $key, 1 );
        }
    }
    foreach ($mb_info_2T_copy as $value) {
        $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$value[4]}', interval +4 month) AS date"));
        $rowGrace_2T_str_strart = $dateCheck_1["date"]; // 2T 리뉴얼 시작 날짜 스트링
        $rowGrace_2T_str_strart = strtotime($rowGrace_2T_str_strart);


        // 현재날짜가 유예기간 시작날짜 미만이면 참
        if( ! ($nowTime_str <= $rowGrace_2T_str_strart) || $value[5] == '회원탈퇴' ) {
            $key = array_search( $mb_info_2T_copy, $value );
            array_splice( $mb_info_2T_copy, $key, 1 );
        }
    }


    foreach ($mb_info_1T_copy as $value) {
        $value[4] = date("Y-m-d", strtotime("+4 month", strtotime($value[4])));
        echo "<li class=\"children__item\">
            <div class=\"node\">
                        <div class=\"node__text team_name\">
                            <p>{$value[0]}({$value[1]})</p>
                            <p>가입 : {$value[7]}</p>
                            <p>리뉴얼 : {$value[4]}</p>
                            <p>후원인 : {$value[6]} / 추천인 : {$value[3]}</p>
                            <p>직급 : ". rankCheck($value[1])."</p>
                        </div>
                </div>
        </li>";
    }
    
    
    
    
    
    
    
    
    
    
?>        
        

                       
	
	
                      
                        
		</ol>
	
                
<?php
    $info = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$mainID}'"));
    $info['renewal'] = date("Y-m-d", strtotime("+4 month", strtotime($info['renewal'])));
?>
                
                
	<div class="node node_root" id="myName">
		<div class="node__text node_main">
			<div class="name_box clearfix">
                            <div class="rank">
<?php
    $rankPath = imagePath( $info['accountRank'] );
    
    if( $mainID == "00000001" ) {
        $rankPath = "/myOffice/images/VMP-DELEGATE.png";
    }
?>
                                <img src="<?=$rankPath?>" alt="rankimage" />
                                
                            </div>
				<div class="name_text">
					
                                        
<?php
    
    echo "<h1 class=\"name\">{$info['mb_name']} <span>{$info['mb_id']}</span></h1>";
    echo "<p>가입 : {$info['mb_open_date']}<br/>리뉴얼 : {$info['renewal']}<br/>";
    echo "직급 : ". rankCheck($info['mb_id'])."</p>";
?>
					
				</div>
				
			</div>
			<div class="team1_count team_count"><p>1팀 : <?=$info["team1"]?>명</p></div>
			<div class="team2_count team_count"><p>2팀 : <?=$info["team2"]?>명</p></div>
		</div>
	</div>
	

	
	<ol class="children children_rightbranch">
            
            
<?php
    foreach ($mb_info_2T_copy as $value) {
        $value[4] = date("Y-m-d", strtotime("+4 month", strtotime($value[4])));
        echo "<li class=\"children__item\">
            <div class=\"node\">
                        <div class=\"node__text team_name\">
                            <p>{$value[0]}({$value[1]})</p>
                            <p>가입 : {$value[7]}</p>
                            <p>리뉴얼 : {$value[4]}</p>
                            <p>후원인 : {$value[6]} / 추천인 : {$value[3]}</p>
                            <p>직급 : ". rankCheck($value[1])."</p>
                        </div>
                </div>
        </li>";
    }

?>

                
                
	
	</ol>
</div>

</div>

        


<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>



<script>

$(function(){
	$('.mindmap').mindmap();
});

</script>
     
    </section>

<script src="http://code.jquery.com/jquery-2.1.3.js"></script>
<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script type="text/javascript">
        


    /* 박스리스트 드래그박스 */
	  $(document).ready(function() {
		$( "#minddrag" ).draggable();
	});


    /* 박스리스트 인쇄하기 */
	function content_print(){

		var boxContent_element = document.querySelector('.mindmap')
 
		boxContent_element.style.left='-540px'
		boxContent_element.style.top='-1300px'

		var initBody = document.body.innerHTML;
        window.onbeforeprint = function(){
            document.body.innerHTML = document.getElementById('printArea').innerHTML;
        }
        window.onafterprint = function(){
            document.body.innerHTML = initBody;
        }
        window.print();
        $(".mindmap").draggable();
	}

	$(document).ready(function() {
		var totalTile = 16;
		var bodyW = parseInt($('body').css('width'));
		var boxContentWidth = totalTile * 170;
		var $boxContent = $('.mindmap');
		$boxContent.css('width', boxContentWidth);
		if(bodyW > 800){
			var tt = parseInt($boxContent.css('width')) / 3;
		}else{
			var tt = parseInt($boxContent.css('width')) / 2.3;
		}
		$(".boxContentW").scrollLeft(tt);


		/*셀렉트박스*/
		var select = $("select#nameNick");

		select.change(function(){
			var select_name = $(this).children("option:selected").text();
			$(this).siblings("label").text(select_name);
		});
    });



	 boxContent.position().top;
	// 부모 요소의 상단값을 기준으로 test 엘리먼트 요소가 위치한 상대적 거리값

	boxContent.position().left;
	// 부모 요소의 좌측값을 기준으로 test 엘리먼트 요소가 위치한 상대적 거리값
	
        function selectView( id ) {
		location.href="/myOffice/box.php?mb_id=" + id;
	}

	function boxBack() {
		history.back();
	}



        function searchClick() {
           if( $("#nameNick option:selected").val() == "아이디" ) {
               var data = "searchID=" + $('#searchInput').val() + "&loginID=" + $('#loginID').val();

               $.ajax({
                   url:'./memberIDSearch.php',
                   type:'POST',
                   data: data,
                   success:function(result){
                       if( result == "true" ) {
                           location.href='/myOffice/allowance_1.php?mb_id=' + $('#searchInput').val();
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

                       for( var i=0 ; i<json.length ; i++ ) {
                           $('#search_info_tbl > tbody:last').append('<tr id=\"'+json[i][1]+'\" onclick=\"searchSelect(this.id);\"><td>'+json[i][0]+'</td><td>'+json[i][2]+'</td><td>'+json[i][5]+'</td><td>'+json[i][3]+'</td><td>'+json[i][4]+'</td></a></tr>');
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
            window.location.href = '/myOffice/allowance_1.php?mb_id=' + id;
        }

	</script>



        
        
        
        
        
        
<div id="spot" style="visibility: hidden;">
					
<?php
    $renewalTitle = mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'");
    $row11 = mysql_fetch_array($renewalTitle);
    
    $row11['VMC'] = number_format($row11['VMC']);
    $row11['VMR'] = number_format($row11['VMR']);
    $row11['VMP'] = number_format($row11['VMP']);
    

?>
                    <div class="pop_tit">VM 회원비 결제</div>
                    <div id="renewal_pop">

						
						<div class="pop_vmc pop">
							<div>
								<p>VMC</p>
								<span><?=$row11['VMC']?></span>
							</div>
							<input type="number" name="pop_vmc" id="pop_vmc" placeholder="VMC 차감"
								onkeyup="PointOnchange(this);">
						</div>
						<div class="pop_vmr pop">
							<div>
								<p>VMR</p>
								<span><?=$row11['VMR']?></span>
							</div>
							<input type="number" name="pop_vmr" id="pop_vmr" placeholder="VMR 차감"
								onkeyup="PointOnchange(this);">
						</div>
						<div class="pop_vmp pop">
							<div>
								<p>VMP</p>
								<span><?=$row11['VMP']?></span>
							</div>
							<input type="number" name="pop_vmp" id="pop_vmp" placeholder="VMP 차감"
								onkeyup="PointOnchange(this);">
						</div>
						<div class="vm_sum">
							<div>
								<p>합 계 :</p>
								<span id="value">0</span>
							</div>
						</div>
						<div class="summary">
                        <?php
                        $renewal = mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'");
                        $row2 = mysql_fetch_array($renewal);
                        
                        if ($row2['accountType'] == "CU" && $row2['renewal'] != null && $timestamp <= $now_temp) {
                            
                            echo "<div><p>결제할 금액 : </p><span id=\"sum_count\">{$money}</span></div>";
                        } else {
                            echo "<div><p>결제할 금액 : </p><span id=\"sum_count\">2080000</span></div>";
                        }                     
                        ?>                     
                        
                            
                        </div>
						<div id="renewal_submit" style="cursor: pointer;"
							onclick="submitCheck();">결제하기</div>
                        <a href="javascript:void(0);" onclick="$('#spot').css('visibility', 'hidden'); return false;"><div id="renewal_cancel"
								style="cursor: pointer;">취소</div></a>
					</div>
				</div>
        
        
<script>
function PointOnchange(val){

    var popvmc = $('input[name=pop_vmc]').val();
    var popvmr = $('input[name=pop_vmr]').val();
    var popvmp = $('input[name=pop_vmp]').val();

    var popsum = $('.vm_sum').find('span');
    var popsummary = $('.summary').find('span');

    var selectInput = $(val).attr('name');
    var selectInputVal = $(val).val();


    if(Number(selectInputVal) > Number($('.'+selectInput).find('span').text())){
        alert('잔여포인트 보다 차감액이 큽니다!');
        $(val).val($('.'+selectInput).find('span').text());
        PointOnchange();
        return;
    }




   popsum.text(Number(popvmc)+Number(popvmr)+Number(popvmp));
   popsummary.text(2080000-Number(popsum.text()));

   $('input[name=pay]').val(2080000-Number(popsum.text()));
   $('input[name=VMC]').val(popvmc);
   $('input[name=VMR]').val(popvmr);
   $('input[name=VMP]').val(popvmp);
   $('input[name=totalpoint]').val(Number(popsum.text()));
}




function submitCheck(){
                    var popsum = $('.vm_sum').find('span');
                    var popsummary = $('.summary').find('span');
                    
                      if(Number(popsummary.text()) < 0){
                            alert("포인트가 결제할 금액보다 큽니다.");
                            return false;                    
                      }else if(Number(popsummary.text()) > 0){
                    	  alert('※ 포인트가 부족합니다.');
                      }else if(Number(popsummary.text()) == 0) {
                          
                          if( $("#pop_vmc").val() == "" )
                              $("#pop_vmc").val("0");
                          if( $("#pop_vmr").val() == "" )
                              $("#pop_vmr").val("0");
                          if( $("#pop_vmp").val() == "" )
                              $("#pop_vmp").val("0");

                          
                          window.open( $("#infoTemp").val() + "&vmc=" + $("#pop_vmc").val() + "&vmr=" + $("#pop_vmr").val() + "&vmp=" + $("#pop_vmp").val() + "&loginID=" + $('#loginID').val(), 'small','width=450,height=605,scrollbars=yes,menubar=no,location=no' );
                          
                          $("#pop_vmc").val("");
                          $("#pop_vmr").val("");
                          $("#pop_vmp").val("");
                          
                          $("#spot").css("visibility", "hidden");
                      }
                }   


</script>        



        
        
      
        
        
        
        
        
       



</body>
</html>
<?php
    include_once('../shop/shop.tail.php');
?>
<script src="js/main.js"></script>
