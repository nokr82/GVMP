<link rel="shortcut icon" href="/img/vmp_logo.ico" />
<link rel="stylesheet" href="./css/index.css" />
<?php
include_once ('./inc/title.php');
include_once ('./_common.php');
include_once ('./dbConn.php');

if ($is_guest) // 로그인 안 했을 때 로그인 페이지로 이동
    header('Location: /bbs/login.php');
?>

<?php
mysql_query("set session character_set_connection=utf8");
mysql_query("set session character_set_results=utf8");
mysql_query("set session character_set_client=utf8");
/* 추천인, 후원인 정보값 가져오기 */
$result = mysql_query("SELECT * FROM genealogy where mb_id = '{$member['mb_id']}'"); /* 로그인했을때 사용자의 genealogy정보를 불러옴 */
$row = mysql_fetch_array($result);
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

if ($row2['accountType'] == 'VM' && ($now_temp >= $timestamp_temp && $now_temp <= $timestamp2_temp)) {
    echo "<script>alert('{$timestamp2}이 되면 CU 계정으로 변경되오니 리뉴얼을 진행해 주시기 바랍니다.');</script>";
}

if ($row2['accountType'] == "CU") {
    echo "<style>#renewalImage {display:none;}#RenewalButton {display:block;} #renewalImage2 {display:block;}.pop_vmc{display:none}.pop_vmr{display:none}</style>";
} else if ($row2['accountType'] == "VD") {
    echo "<style>#renewalImage {display:none;}#renewalImage2 {display:none;} #RenewalButton {display:none;}</style>";
} else if ($row2['accountType'] == 'VM' && ($now_temp >= $timestamp_temp && $now_temp <= $timestamp2_temp)) {
    echo "<style>#renewalImage {display:block;} #RenewalButton {display:block;} #renewalImage2 {display:none;}#sponsor_pop{display:none;}#sponsor_pop2{display:none;}</style>";
} else if ($row2['accountType'] == "CU" && $row2['renewal'] != null && $timestamp <= $now_temp) {
    echo "<style>#renewalImage {display:block;} #RenewalButton {display:block;} #renewalImage2 {display:none;}.pop_vmc{display:none}.pop_vmr{display:none}</style>";
} else {
    echo "<style>#renewalImage {display:none;}#renewalImage2 {display:none;} #RenewalButton {display:none;}</style>";
}
?>


<script src="./js/jquery.ui.touch-punch.min.js"></script>
<SCRIPT LANGUAGE="JavaScript">
function dateSelect(docForm,selectIndex) {
   watch = new Date(docForm.year.options[docForm.year.selectedIndex].text, docForm.month.options[docForm.month.selectedIndex].value,1);
   hourDiffer = watch - 86400000;
   calendar = new Date(hourDiffer);

   var daysInMonth = calendar.getDate();
      for (var i = 0; i < docForm.day.length; i++) {
         docForm.day.options[0] = null;
      }
      for (var i = 0; i < daysInMonth; i++) {
         docForm.day.options[i] = new Option(i+1);
   }
   document.form1.day.options[0].selected = true;
}
</script>

<script language="javascript">
        function Today(year,mon,day){
             if(year == "null" && mon == "null" && day == "null"){       
             today = new Date();
             this_year=today.getFullYear();
             this_month=today.getMonth();
             this_month+=1;
             if(this_month <10) this_month="0" + this_month;
         
             this_day=today.getDate();
             if(this_day<10) this_day="0" + this_day;     
         }
         else{  
             var this_year = eval(year);
             var this_month = eval(mon); 
             var this_day = eval(day);
             }
         
          montharray=new Array(31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31); 
          maxdays = montharray[this_month-1]; 
        //아래는 윤달을 구하는 것
          if (this_month==2) { 
              if ((this_year/4)!=parseInt(this_year/4)) maxdays=28; 
              else maxdays=29; 
          } 
         
         document.writeln("<select name='year' size=1 onChange='dateSelect(this.form,this.form.month.selectedIndex);'>");
             for(i=this_year-0;i<this_year+6;i++){//현재 년도에서 과거로 5년까지 미래로 5년까지를 표시함
                 if(i==this_year) document.writeln("<OPTION VALUE="+i+ " selected >" +i); 
                 else document.writeln("<OPTION VALUE="+i+ ">" +i); 
             }    
         document.writeln("</select>년");      
        
         
         document.writeln("<select name='month' size=1 onChange='dateSelect(this.form,this.selectedIndex);'>");
             for(i=1;i<=12;i++){ 
                 if(i<10){
                     if(i==this_month) document.writeln("<OPTION VALUE=0" +i+ " selected >0"+i); 
                     else document.writeln("<OPTION VALUE=0" +i+ ">0"+i);
                 }         
                 else{
                     if(i==this_month) document.writeln("<OPTION VALUE=" +i+ " selected >" +i);  
                     else document.writeln("<OPTION VALUE=" +i+ ">" +i);  
                 }                     
            }         
         document.writeln("</select>월");
         
           
        }
        </script>
<!-- submit -->
<script language="javascript">

        function check_submit() {
            
        	var check = confirm("회원정보를 수정하시겠습니까?");
            
             if ( $('#name').val() == "" ) {
                     alert('이름을 입력하세요');
                     $('#name').focus();
                     return;
                     
             } else if ($('#hp').val() == "" || $('#hp').val().length < 13) {
                     alert('핸드폰번호를 입력하세요.(- 포함 13자리.)');
                     $('#hp').focus();
                     return;
                     
             } else if ($('#birth').val() == "" || $('#birth').val().length < 6) {
                     alert('생년월일을 입력하세요(- 제외, 주민번호 앞 6자리)');
                     $('#birth').focus();
                     return;
                     
             }else if (!isNaN('#birth')) {
                     alert('생년월일은 숫자로 입력하세요.(- 제외, 주민번호 앞 6자리)');
                     $('#birth').focus();
                     return;
                 
         	 }else if ($('#emailform').val() == "") {
                     alert('이메일을 입력하세요');
                     $('#emailform').focus();
                     return;
                 
         	 } else if ($('#reg_mb_zip').val() == "") {
                     alert('우편번호를 입력하세요');
                     $('#reg_mb_zip').focus();
                     return;
                     
             }else if ($('#reg_mb_addr1').val() == "") {
                     alert('주소를 입력하세요');
                     $('#reg_mb_addr1').focus();
                     return;
                 
         	  }else if ($('#reg_mb_addr2').val() == "") {
                    alert('상세주소를 입력하세요');
                    $('#reg_mb_addr2').focus();
                    return;
                
        	  }else if ($('#bankName').val() == "") {
                    alert('은행명을 입력하세요');
                    $('#bankName').focus();
                    return;
              
      	  	  }else if ($('#accountHolder').val() == "") {
                    alert('예금주를 입력하세요');
                    $('#accountHolder').focus();
                    return;
          
  	  	      }else if ($('#accountNumber').val() == "") {
                    alert('계좌번호를 입력하세요');
                    $('#accountNumber').focus();
                    return;
      
	  	      }else if (!isNaN('#accountNumber')) {
                   alert('계좌번호는 숫자로 입력하세요.(- 제외)');
                   $('#accountNumber').focus();
                   return;
              
      		  } else if(check){ 
          	  	   alert("회원정보가 수정되었습니다.");
          	  	  document.myForm.submit();
      	      }
        }

</script>
<script>
        function Enter_Check(){ // 회원검색에서 엔터 키 입력하면 검색 진행하게 하는 함수
            if(event.keyCode == 13){
            	change();
            }
        }
    	/* 비밀번호 변경창 */
         function change() {

        	 var check = confirm("비밀번호를 수정하시겠습니까?");
             
             if ($('#pw').val() == "" ) {
                     alert('비밀번호를 입력하세요');
                     $('#pw').focus();
                     return;
                     
             }else if ($('#pw_confirm').val() == "" ) {
                     alert('비밀번호확인을 입력하세요.');
                     $('#pw_confirm').focus();
                     return;
                 
         	 }else if ( $('#pw').val() != $('#pw_confirm').val() ) {
                     alert('비밀번호가 같지 않습니다.');
                     $('#pw_confirm').focus();
                     return;
                     
             } else if ($('#pw').val().length < 3) {
                     alert('비밀번호를 3글자 이상 입력하십시오.');
                     $('#pw').focus();
                     return;
                     
             } else if(check){ 
                 	  alert("비밀번호가 수정되었습니다.");
                 	 document.myForm2.submit(); 	  
          	 }

         }
</script>
<script>
        function Enter_Check2(){ // 회원검색에서 엔터 키 입력하면 검색 진행하게 하는 함수
            if(event.keyCode == 13){
            	change2();
            }
        }
    	/* 비밀번호 변경창 */
         function change2() {

        	 var check = confirm("회원탈퇴를 진행할까요?");
             
             if ($('#pw2').val() == "" ) {
                     alert('비밀번호를 입력하세요');
                     $('#pw2').focus();
                     return;
                     
             }else if ($('#pw_confirm2').val() == "" ) {
                     alert('비밀번호확인을 입력하세요.');
                     $('#pw_confirm2').focus();
                     return;
                 
         	 }else if ( $('#pw2').val() != $('#pw_confirm2').val() ) {
                     alert('비밀번호가 같지 않습니다.');
                     $('#pw_confirm').focus();
                     return;
                     
             } else if ($('#pw2').val().length < 3) {
                     alert('비밀번호를 3글자 이상 입력하십시오.');
                     $('#pw2').focus();
                     return;
                     
             } else if(check){ 
                 	  
                 	 document.myForm3.submit(); 	  
          	 }

         }
</script>
<script>
    /* 인풋창 비활성화 */
        function modi() {
            
        	$("#renewel").attr("disabled",true).attr("readonly",false);
        	$("#recommenderName").attr("disabled",true).attr("readonly",false);
        	$("#recommenderID").attr("disabled",true).attr("readonly",false);
        	$("#sponsorName").attr("disabled",true).attr("readonly",false);
        	$("#sponsorID").attr("disabled",true).attr("readonly",false);
        	$("#name").attr("disabled",true).attr("readonly",false);

        };
        function modiclose() {
        	
        	$("#renewel").attr("readonly",false).attr("disabled",false);
        	$("#recommenderName").attr("readonly",false).attr("disabled",false);
        	$("#recommenderID").attr("readonly",false).attr("disabled",false);
        	$("#sponsorName").attr("readonly",false).attr("disabled",false);
        	$("#sponsorID").attr("readonly",false).attr("disabled",false);
        	$("#name").attr("readonly",false).attr("disabled",false);
        };
 </script>
<!-- 리뉴얼 결제 팝업 -->
<script> 
	function ViewLayer() { 
		
		var now = new Date();
    	var startTime = new Date ( now.getFullYear() + "-" + (now.getMonth()+1) + "-" + now.getDate() + " 00:00:00" );
    	var endTime = new Date ( now.getFullYear() + "-" + (now.getMonth()+1) + "-" + (now.getDate()) + " 01:00:00" );

//    	if( (startTime.getTime() <= now.getTime()) && (endTime.getTime() > now.getTime())) {
//    		alert("금일 00:00 ~ 금일 01:00 점검시간입니다.");
//	        return false;             
//	    } 잠시주석
		
		if(document.all.spot.style.visibility=="hidden") {
		   document.all.spot.style.visibility="visible";
           PointOnchange();
		   return false;
		 }
		 
		if(document.all.spot.style.visibility=="visible") {
		   document.all.spot.style.visibility="hidden";
		   return false;
		 }

	}
</script>
<!-- 비밀번호 수정 팝업 -->
<script>
function showmap() { 
	 if(document.all.spot2.style.visibility=="hidden") {
	   document.all.spot2.style.visibility="visible";
	   return false;
	 }
	 if(document.all.spot2.style.visibility=="visible") {
	  document.all.spot2.style.visibility="hidden";
	  return false;
	 }
	 
	}
function popclose(){
	$("#spot2").hide();
	location.reload();
}
</script>
<!-- 회원탈퇴 팝업 -->
<script>
function showwithdrawal() { 
	 if(document.all.spot3.style.visibility=="hidden") {
	   document.all.spot3.style.visibility="visible";
	   return false;
	 }
	 if(document.all.spot3.style.visibility=="visible") {
	  document.all.spot3.style.visibility="hidden";
	  return false;
	 }
	 
	}
function popclose(){
	$("#spot2").hide();
	location.reload();
}
</script>

<script>
/*
$( function() {
	$( ".history table" ).draggable({ axis: "x", refreshPositions: true, 
										drag: function( event, ui ) {
											ui.position.left = Math.min( 0, ui.position.left );
										}
									});
} );
*/
</script>


</head>
<body onload="">
	<!-- header -->
<?php
include_once ('../shop/shop.head.php');
?>
<!-- 비밀번호변경폼 -->
	<form name="myForm2" action="pw_update.php" method="post">
		<div id="spot2" style="position: fixed; visibility: hidden;">
			<div class="pw_confirm">
				<div class="pop_tit2">비밀번호 변경</div>
				<button type="button" onclick="javascript:popclose();"
					class="pop_close">X</button>
				<div>
					<input class="user_pw" id="pw" placeholder="비밀번호" name="password"
						maxlength="20" type="password"> <input class="user_pw_confirm"
						id="pw_confirm" placeholder="비밀번호확인" name="pw_confirm"
						maxlength="20" type="password" onkeydown="Enter_Check();">
					<button type="button" class="final_confirm" onclick='change();'>비밀번호
						변경하기</button>
				</div>
			</div>
		</div>
	</form>

	<!-- 회원탈퇴폼 -->
	<form name="myForm3" action="withdrawal.php" method="post">
		<div id="spot3" style="position: fixed; visibility: hidden;">
			<div class="withdrawal_confirm">
				<div class="pop_tit3">회원탈퇴</div>
				<button type="button" onclick="javascript:popclose();"
					class="pop_close">X</button>
				<div>
					<input class="user_pw2" id="pw2" placeholder="비밀번호"
						name="password2" maxlength="20" type="password"> <input
						class="user_pw_confirm2" id="pw_confirm2" placeholder="비밀번호확인"
						name="pw_confirm2" maxlength="20" type="password"
						onkeydown="Enter_Check2();">
					<button type="button" class="final_confirm" onclick='change2();'>회원탈퇴하기</button>
				</div>
			</div>
		</div>
	</form>

	<!-- 본인정보 -->
	<section>
		<form action="update.php" method="post" name="myForm">
			<div class="user_Modified">
				<div class="first_element">
					<!--프로필 이미지 -->
					<!-- <div>
                        <img src="images/profile.png" alt="">  
                    </div> -->
					<div class="second_element">
						<ul>
							<li><input class="user_name" id="name" placeholder="name"
								name="name" maxlength="4" type="text"
								value="<?=$member['mb_name']?>" readonly>
                                <?php
                                mysql_query("set session character_set_connection=utf8");
                                mysql_query("set session character_set_results=utf8");
                                mysql_query("set session character_set_client=utf8");
                                
                                $result11 = mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'");
                                $row11 = mysql_fetch_array($result11);
                                
                                $total = number_format($row11['VMC'] + $row11['VMR'] + $row11['VMP']);
                                $row11['VMC'] = number_format($row11['VMC']);
                                $row11['VMR'] = number_format($row11['VMR']);
                                $row11['VMP'] = number_format($row11['VMP']);
                                ?>        
                                <span id="accountType"><?=$row11['accountType']?></span>
								| <span><?=$row11['accountRank']?></span> | <span id="mb_info_id"><?=$member['mb_id']?></span>
                                
                                <button type="button" class="pw_change"
									id="pw_btn" onclick="javascript:showmap();">비밀번호 변경</button> <span
								class="modifiedBt"> <img src="images/modified.png" alt=""
									onclick='modi();'>
									<ul class="modifiedBtUl">
										<li class="confirmBt" onclick='check_submit();'><span><i
												class="fa fa-check" id="check-fa" aria-hidden="true"></i></span></li>
										<!--
                                        -->
										<li class="closeBt" onclick='modiclose();'><span><i
												class="fa fa-times" id="close-fa" aria-hidden="true"></i></span></li>
									</ul>
							</span></li>
							<li>
								<ul class="memberInfoUl">
									<li><strong> 핸드폰 </strong>
										<p>
											<input class="infoInputM" id="hp" type="text"
												placeholder="휴대폰번호" name="hp" value="<?=$member['mb_hp']?>"
												readonly>
										</p></li>
									<li><img src="images/dot.png" alt=""></li>
									<li><strong> 생년월일 </strong>
										<p>
											<input class="infoInputM" id="birth" type="text"
												placeholder="생년월일" name="birth"
												value="<?=$member['birth']?>" readonly>
										</p></li>
									<li><img src="images/dot.png" alt=""></li>
									<li><strong>이메일</strong>
										<p>
											<input class="infoInputM" id="emailform" type="text"
												placeholder="email" name="email"
												value="<?=$member['mb_email']?>" readonly>
										</p></li>
									<li><img src="images/dot.png" alt=""></li>
									<li><strong>리뉴얼</strong>
										<p>
											<input class="infoInputM" id="renewel" type="text"
												placeholder="리뉴얼" name="renewel" value="<?=$timestamp?>"
												readonly>
										</p></li>

								</ul>
							</li>
							<li id="post_element"><strong>주소</strong>
                                <?php if ($config['cf_use_addr']) { ?>
                                 <label for="reg_mb_zip"
								class="sound_only">우편번호<?php echo $config['cf_req_addr']?'<strong class="sound_only"> 필수</strong>':''; ?></label>
								<input type="text" name="mb_zip"
								value="<?php echo $member['mb_zip1'].$member['mb_zip2']; ?>"
								id="reg_mb_zip"
								<?php echo $config['cf_req_addr']?"required":""; ?>
								class="frm_input<?php echo $config['cf_req_addr']?"required":""; ?>"
								size="5" maxlength="6" placeholder="우편번호">
								<button type="button" class="adressSearchBt" id="postbtn"
									onclick="win_zip('fregisterform', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소
									검색</button>
								<br> <label for="reg_mb_addr1" class="sound_only">주소<?php echo $config['cf_req_addr']?'<strong class="sound_only"> 필수</strong>':''; ?></label>
								<input type="text" name="mb_addr1"
								value="<?php echo get_text($member['mb_addr1']) ?>"
								id="reg_mb_addr1"
								<?php echo $config['cf_req_addr']?"required":""; ?>
								class="frm_input frm_address<?php echo $config['cf_req_addr']?"required":""; ?>"
								size="50" placeholder="주소"> <label for="reg_mb_addr2"
								class="sound_only">상세주소</label> <input type="text"
								name="mb_addr2"
								value="<?php echo get_text($member['mb_addr2']) ?>"
								id="reg_mb_addr2" class="frm_input frm_address" size="50"
								placeholder="상세주소"> <br>

                                <?php } ?>
                            </li>
						</ul>
						<!-- 3가지로 분류 -->
					</div>
					<!--second_element-->
					<div class="clr"></div>
				</div>
				<div id="spot" style="visibility: hidden;">
					
<?php
    $renewalTitle = mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'");
    $renewalTitleRow = mysql_fetch_array($renewalTitle);
    
    if( $renewalTitleRow['accountType'] == 'CU' ) {
        echo '<div class="pop_tit">VM 가입</div>';
    } else if( $renewalTitleRow['accountType'] == 'VM' ) {
        echo '<div class="pop_tit">리뉴얼 결제</div>';
    }
?>
                             
                    
                    <div id="renewal_pop">
                    	<div id="sponsor_pop2">
							<p>후원인 정보입력</p>
							<input class="team_infor" type="text" name="reg_mb_sponsor" id="reg_mb_sponsor"
								placeholder="후원인 이름" minlength="3" maxlength="20"/> 
							<input onkeydown="onlyNumber(this)" class="team_infor" type="text"
								name="reg_mb_sponsorID" id="reg_mb_sponsorID" placeholder="후원인 회원번호" minlength="3" maxlength="8"/>
						</div>
						<div id="sponsor_pop">
							<p>후원인 팀배치</p>
                                                        <input type="radio" name="radiobtn" value="1" onclick="teamCheckFunc()"/> 1팀 
                                                        <input type="radio" name="radiobtn" value="2" class="team_sel" onclick="teamCheckFunc()"/> 2팀
						</div>
						
						<div class="pop_vmc pop">
							<div>
								<p>VMC</p>
								<span><?=$row11['VMC']?></span>
							</div>
							<input type="number" name="pop_vmc" placeholder="VMC 차감"
								onkeyup="PointOnchange(this);">
						</div>
						<div class="pop_vmr pop">
							<div>
								<p>VMR</p>
								<span><?=$row11['VMR']?></span>
							</div>
							<input type="number" name="pop_vmr" placeholder="VMR 차감"
								onkeyup="PointOnchange(this);">
						</div>
						<div class="pop_vmp pop">
							<div>
								<p>VMP</p>
								<span><?=$row11['VMP']?></span>
							</div>
							<input type="number" name="pop_vmp" placeholder="VMP 차감"
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
						<a href="/myOffice/"><div id="renewal_cancel"
								style="cursor: pointer;">취소</div></a>
					</div>
				</div>
				<div class="table_wrap">
					<table>
						<tr>
							<th>추천인</th>
							<td><strong>이름</strong> <input class="infoInputSS"
								id="recommenderName" type="text" placeholder="recommenderName"
								name="recommenderName" value="<?=$row['recommenderName']?>"
								readonly></td>
							<td><strong>회원번호</strong> <input class="infoInputSM"
								id="recommenderID" type="text" placeholder="recommenderID"
								name="recommenderID" value="<?=$row['recommenderID']?>" readonly>
							</td>
							<td></td>
						</tr>
						<tr>
							<th>후원인</th>
							<td><strong>이름</strong> <input class="infoInputSS"
								id="sponsorName" type="text" placeholder="sponsorName"
								name="sponsorName" value="<?=$row['sponsorName']?>" readonly></td>
							<td><strong>회원번호</strong> <input class="infoInputSM"
								id="sponsorID" type="text" placeholder="sponsorID"
								name="sponsorID" value="<?=$row['sponsorID']?>" readonly></td>
							<td id="RenewalButton" onclick="javascript:ViewLayer();">
								<!--<label>
                                    <input type="radio" name="asd">1팀
                                </label>
                                <label>
                                    <input type="radio" name="asd">2팀
                                </label>--> <a href="javascript:;" id="renewalImage"><img src="images/renewal.png" alt=""></a> <a
								href="javascript:;" id="renewalImage2"><img
									src="images/renewal2.png" alt=""></a>
							</td>
						</tr>

						<tr>
							<th>은행정보</th>
							<td><strong>은행명</strong> <input class="infoInputSS" id="bankName"
								type="text" placeholder="bankName" name="bankName"
								value="<?=$member['bankName']?>" readonly></td>
							<td><strong>예금주</strong> <input class="infoInputSS"
								id="accountHolder" type="text" placeholder="accountHolder"
								name="accountHolder" value="<?=$member['accountHolder']?>"
								readonly></td>
							<td><strong>계좌번호</strong> <input class="infoInputSB"
								id="accountNumber" type="text" placeholder="accountNumber"
								name="accountNumber" value="<?=$member['accountNumber']?>"
								readonly></td>
						</tr>
						<tr>
							<th>산하 회원정보</th>
							<td><a href="tree.php"><img src="images/list.png" alt="리스트형"></a>
							</td>
							<td><a href="box_1.php"><img src="images/box_btn.png" alt="트리형"></a>
							</td>
							<td><a href="sales.php"><img src="images/revenue_btn.png"
									alt="직접추천정보"></a></td>
						</tr>
					</table>
					<!-- 4가지로 분류 -->
				</div>
			</div>
		</form>

		<div class="charge">
			<div class="vmc">
				<div>VMC</div>
				<div>
					<strong><?=$row11['VMC']?></strong>원
				</div>
			</div>
			<div class="vmr">
				<div>VMR</div>
				<div>
					<strong><?=$row11['VMR']?></strong>원
				</div>
			</div>
			<div class="vmp">
				<div>VMP</div>
				<div>
					<strong><?=$row11['VMP']?></strong>원
				</div>
			</div>
			<div class="total">
				<div>총 수당</div>
				<div style="font-size: 20px; font-weight: 700"><?=$total?>원</div>
			</div>
		</div>
	</section>
	<!-- 달력 -->
	<div class="calendar">
		<h2>수당 달력</h2>
		<form name="form1">
			<script language="javascript"> Today('null','null','null'); </script>
			<button class="test_btn" type="button">조회</button>
		</form>
	</div>
	<div id="kCalendar"></div>
	<script>
        window.onload = function () {
            kCalendar2('kCalendar');
        };
    </script>

	<section class="history">
		<div>
			<h3>최근주문/판매 내역</h3>
            <?php echo $CRow['DD']; ?>
            <!-- <a href="javascript:;"><img src="images/history_more.png" alt="주문내역 더보기"></a> -->
		</div>

		<input type="hidden" id="orderNum" value="10">

		<div class="historyTableW">
			<table>
				<thead>
					<tr>
						<th>상품명</th>
						<th>주문서번호</th>
						<th>주문일시</th>
						<th>회원이름</th>
						<th>주문/판매/광고</th>
						<th>커미션</th>
					</tr>
				</thead>
				<!-- 주문내역 -->
				<tbody id="orderTB">
                <?php

                
                
                $result = mysql_query("select a.mb_id from genealogy as a
inner join g5_member as b
on a.mb_id = b.mb_id
where recommenderID = '{$member['mb_id']}' and b.accountType != 'VM'");
                
                $list = array();
                
                while( $row = mysql_fetch_array($result) ) {
                    array_push( $list, $row['mb_id'] );
                }
                
                array_push( $list, $member['mb_id'] );
                
                $Qu = "select AL.*, GM.mb_name from orderList as AL inner join g5_member as GM
on AL.mb_id = GM.mb_id where ";
                
                for( $i=0 ; $i<count($list) ; $i++ ) {
                    $Qu .= "GM.mb_id = '{$list[$i]}'";
                    if( $i+1 != count($list) )
                        $Qu .= " or ";
                        
                }
                
                $Qu .= "order by orderDate desc limit 0, 10";
                
                $result5 = mysql_query($Qu);

                    
                    while ($row5 = mysql_fetch_array($result5)) {
                        echo "<tr id=\"ordernum\">
                                	<td>{$row5['productName']}</td>
                                    <td>{$row5['n']}</td>
                                    <td>{$row5['orderDate']}</td>
                                    <td>{$row5['mb_name']}</td>
                                    <td>￦".number_format($row5['money'])."</td>
                                    <td>￦".number_format($row5['commission'])."</td>
                                  </tr>";
                                }
                ?>
				</tbody>
			</table>
			<script>
                function comma(str) { //스크립트 콤마찍기
                    str = String(str);
                    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
                }
                
                function orderMeth() {
                	var orderNum = $("#orderNum").val() ;
                	var ornum = "orderNum=" + orderNum + "&mb_id=" + $("#mb_info_id").text();
                	$.ajax({
                    	url:'recipt.php',
                        type:'POST',
                        data: ornum,
                        success:function(result5){
                			var json = JSON.parse( result5 );
                
                			
                            for( var i=0 ; i<json.length ; i++ ) {
                            	$("#orderTB").append("<tr id=\"ordernum\"><td>"+json[i][0]+"</td><td>"+json[i][1]+"</td><td>"+json[i][2]+"</td><td>"+json[i][3]+"</td><td>￦"+comma(json[i][4])+"</td><td>￦"+comma(json[i][5])+"</td></tr>");
                			}
                        	
                
                        	$("#orderNum").val( parseInt(orderNum) + 10);
                		}
                    });
                }
			  </script>

			<div class="list_more">
				<button type="button" id="more" onclick="orderMeth()">
					<i class="fa fa-ellipsis-h" aria-hidden="true"></i>
				</button>
			</div>
		</div>


	</section>
        
	<div class="withdrawal">
		<button type="button" class="withdrawal_btn" id="with_btn"
			onclick="javascript:showwithdrawal();">VMP회원탈퇴하기</button>
	</div>


	<form action="renewal.php" method="post" id="orderform">
		<input type="hidden" name="userid" value=<?=$member['mb_id']?>> 
                <input ype="hidden" name="VMC" value=""> 
                <input type="hidden" name="VMR" value=""> 
                <input type="hidden" name="VMP" value=""> 
                <input type="hidden" name="pay" value=""> 
                <input type="hidden" name="totalpoint" value="">
                <input type="hidden" name="H_reg_mb_sponsor" id="H_reg_mb_sponsor" value="">
                <input type="hidden" name="H_reg_mb_sponsorID" id="H_reg_mb_sponsorID" value="">
                <input type="hidden" name="H_radiobtn" id="H_radiobtn" value="">
	</form>





	<script type="text/javascript">
	$(document).ready(function() {
		
                             
                var pointvmc = $('.vmc').find('strong').text();
                var pointvmr = $('.vmr').find('strong').text();
                var pointvmp = $('.vmp').find('strong').text();
                
                var pointTotal = Number(pointvmc)+Number(pointvmr)+Number(pointvmp);
                
                $('.total').children('div').children('strong').text(pointTotal);
                
		var $modifiedBtImg = $('.modifiedBt > img');
		var $modifiedBtConfirmBt = $('.confirmBt');
		var $modifiedBtCloseBt = $('.closeBt');
		var $adressSearchBt = $('.adressSearchBt');
		var $passwordChangeBt = $('.pw_change');
		
       $modifiedBtImg.on('click',function(){
			$('body input').addClass('on').attr('readonly',false);
			$(this).css('display', 'none');
			$modifiedBtConfirmBt.css('display', 'block');
			$modifiedBtCloseBt.css('display', 'block');
			$passwordChangeBt.css('display', 'block');
			$adressSearchBt.addClass('on');
		})
		$modifiedBtConfirmBt.on('click',function(){
			$('body input').removeClass('on').attr('readonly',true);
			$(this).css('display', 'none');
			$modifiedBtImg.css('display', 'block');
			$modifiedBtCloseBt.css('display', 'none');
			$adressSearchBt.removeClass('on');
		})
		$modifiedBtCloseBt.on('click',function(){
			$('body input').removeClass('on').attr('readonly',true);
			$(this).css('display', 'none');
			$modifiedBtImg.css('display', 'block');
			$modifiedBtConfirmBt.css('display', 'none');
			$passwordChangeBt.css('display', 'none');
			$adressSearchBt.removeClass('on');
		})
		
    });
	</script>

	<script>
           

        function submitCheck(){
                    if( $("#accountType").text() == 'CU' ) {
                        if( ! $('input:radio[name=radiobtn]').is(':checked') ) {
                            alert("※ 팀을 선택하지 않으셨습니다.");
                            return false;
                        }
                    }
                    var popsum = $('.vm_sum').find('span');
                    var popsummary = $('.summary').find('span');
                    
                      if(Number(popsummary.text()) < 0){
                            alert("포인트가 결제할 금액보다 큽니다.");
                            return false;                    
                      }else if(Number(popsummary.text()) > 0){
                    	  alert('※ 커미션이 부족할 경우 리뉴얼을 위해 해당 계정의 이름으로 입금해주시기 바랍니다.\n 우리은행 VMP 1002254564367');
                      }else if(Number(popsummary.text()) == 0) {
                          var a = $("#reg_mb_sponsor").val();
                          var b = $("#reg_mb_sponsorID").val();
                          var c = $("input:radio[name=radiobtn]:checked").val();

                          $("#H_reg_mb_sponsor").val( a );
                          $("#H_reg_mb_sponsorID").val( b );
                          $("#H_radiobtn").val( c );
                          
                          
                    	  document.getElementById('orderform').submit();
                      }
                }   
           
        function PointOnchange(val){
                var popvmc = $('input[name=pop_vmc]').val(); //차감
                var popvmr = $('input[name=pop_vmr]').val();
                var popvmp = $('input[name=pop_vmp]').val(); //차감

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
        
        </script>

</body>
</html>
<?php
include_once ('../shop/shop.tail.php');
?>
<script src="js/main.js"></script>
<script src="js/script.js"></script>
<!-- 주소찾기 -->
<script src="<?php echo G5_PLUGIN_URL ?>/postcodify/zip.js"></script>
<script>


/* Kurien / Kurien's Blog / http://blog.kurien.co.kr */
/* 주석만 제거하지 않는다면, 어떤 용도로 사용하셔도 좋습니다. */

function kCalendar2(id, date) {
   var kCalendar2 = document.getElementById(id);
   
   if( typeof( date ) !== 'undefined' ) {
      date = date.split('-');
      date[1] = date[1] - 1;
      date = new Date(date[0], date[1], date[2]);
   } else {
      var date = new Date();
   }
   var currentYear = date.getFullYear();
   //년도를 구함
   
   var currentMonth = date.getMonth() + 1;
   //연을 구함. 월은 0부터 시작하므로 +1, 12월은 11을 출력
   
   var currentDate = date.getDate();
   //오늘 일자.
   
   date.setDate(1); 
   var currentDay = date.getDay();
   //이번달 1일의 요일은 출력. 0은 일요일 6은 토요일
   
   var dateString = new Array('sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat');
   var lastDate = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
   if( (currentYear % 4 === 0 && currentYear % 100 !== 0) || currentYear % 400 === 0 )
      lastDate[1] = 29;
   //각 달의 마지막 일을 계산, 윤년의 경우 년도가 4의 배수이고 100의 배수가 아닐 때 혹은 400의 배수일 때 2월달이 29일 임.
   //console.log('월 구하기??흠..'+currentMonth);
   var currentLastDate = lastDate[currentMonth-1];
   var week = Math.ceil( ( currentDay + currentLastDate ) / 7 );
   //총 몇 주인지 구함.
   
  
   
   
   if(currentMonth != 1)
      var prevDate = currentYear + '-' + ( currentMonth - 1 ) + '-' + currentDate;
   else
      var prevDate = ( currentYear - 1 ) + '-' + 12 + '-' + currentDate;
   //만약 이번달이 1월이라면 1년 전 12월로 출력.
   
   if(currentMonth != 12) 
      var nextDate = currentYear + '-' + ( currentMonth + 1 ) + '-' + currentDate;
   else
      var nextDate = ( currentYear + 1 ) + '-' + 1 + '-' + currentDate;
   //만약 이번달이 12월이라면 1년 후 1월로 출력.
   
   var calendar = '';
   
   calendar += '<div id="header">';
   calendar += '         <span><a href="javascript:;" class="button left" onclick="kCalendar2(\'' +  id + '\', \'' + prevDate + '\')"><</a></span>';
   calendar += '         <span id="date">' + currentYear + '년 ' + currentMonth + '월</span>';
   calendar += '         <span><a href="javascript:;" class="button right" onclick="kCalendar2(\'' + id + '\', \'' + nextDate + '\')">></a></span>';
   calendar += '      </div>';
   calendar += '      <table>';
   calendar += '         <caption>' + currentYear + '년 ' + currentMonth + '월 달력</caption>';
   calendar += '         <thead>';
   calendar += '            <tr>';
   calendar += '              <th class="sun" scope="row">일요일</th>';
   calendar += '              <th class="mon" scope="row">월요일</th>';
   calendar += '              <th class="tue" scope="row">화요일</th>';
   calendar += '              <th class="wed" scope="row">수요일</th>';
   calendar += '              <th class="thu" scope="row">목요일</th>';
   calendar += '              <th class="fri" scope="row">금요일</th>';
   calendar += '              <th class="sat" scope="row">토요일</th>';
   calendar += '            </tr>';
   calendar += '         </thead>';
   calendar += '         <tbody class="calenBody">';
   
   var dateNum = 1 - currentDay;
    
 
   for(var i = 0; i < week; i++) {
      calendar += '         <tr>';
      for(var j = 0; j < 7; j++, dateNum++) {
         if( dateNum < 1 || dateNum > currentLastDate ) {
            calendar += '<td class="' + dateString[j] + '"> </td>';
            continue;
         }     
                 
         calendar += '<td class="' + dateString[j] + '" id="'+dateNum+'">' + dateNum + '</td>';
      }
      calendar += '</tr>';
   }
    
   calendar += '</tbody>';
   calendar += '</table>';
   
   kCalendar2.innerHTML = calendar;
   
   
   var data = "year=" + currentYear + "&month=" + currentMonth;
   
    $.ajax({
           url:'index2.php',
           type:'POST',
           data: data,
           success:function(result){

               var json = JSON.parse(result);  
            
               if( json.length == 0 ) {
                   console.log("결과가 없습니다.");
               }

               for( var i=0 ; i<json.length ; i++ ) {
                   $('#'+json[i][5]).html(json[i][5]+'<div class=\"box_wrap\"><ul class=\"box\"><li class=\"box1\">VMC</li><li class="">￦'+json[i][0]+'</li></ul><ul class=\"box\"><li class=\"box2\">VMR</li><li>￦'+json[i][1]+'</li></ul><ul class=\"box\"><li class=\"box3\">VMP</li><li>￦'+json[i][2]+'</li></ul></div>');    
               }
           }
       });
   
}
$('.test_btn').click(function(){
    var year = $('select[name=year]').val()
    var mon = $('select[name=month]').val()
    var total = year+'-'+ mon +'-01';
    kCalendar2('kCalendar',total)
})    

function onlyNumber(obj) {
    $(obj).keyup(function(){
         $(this).val($(this).val().replace(/[^0-9]/g,""));
    }); 
}



$('#reg_mb_sponsorID').blur(function() {
    var data = "reg_mb_sponsor=" + $('#reg_mb_sponsor').val() + "&"
    + "reg_mb_sponsor_no=" + $('#reg_mb_sponsorID').val();

     $.ajax({
         url:'/bbs/register_sponsorValue_checking.php',
         type:'get',
         data: data,
         success:function(result){
             if( result == "false" ) {
                 alert("입력하신 후원인 정보가 잘 못 됐습니다.");
                 $("#reg_mb_sponsorID").val("");
                 $("#reg_mb_sponsor").val("");
                 $("#reg_mb_sponsor").focus();
             }
                            }
     });
    });  
    
    function teamCheckFunc() {
        $(document).ready(function(){
            var data = "reg_mb_sponsor=" + $('#reg_mb_sponsor').val() + "&"
            + "reg_mb_sponsor_no=" + $('#reg_mb_sponsorID').val() + "&"
            + "reg_mb_sponser_team=" + $("input:radio[name=radiobtn]:checked").val();
            $.ajax({
                url:'/bbs/register_sponsorTeam_checking.php',
                type:'get',
                data: data,
                success:function(result){
                    if( result == "false" ) {
                        alert("선택하신 후원인 팀으로 배치될 수 없습니다.\n팀 선택을 다시 해 주시기 바랍니다.");
                        $("input:radio[name='radiobtn']:radio[value='1']").prop("checked", false);
                        $("input:radio[name='radiobtn']:radio[value='2']").prop("checked", false);
                    }
                           }
            });
        });
    }

</script>

