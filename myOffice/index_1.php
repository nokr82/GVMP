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
//    echo "<script>alert('{$timestamp2}이 되면 CU 계정으로 변경되오니 리뉴얼을 진행해 주시기 바랍니다.');</script>";
    echo "<script>alert('현재 VM 기간이 만료된 상태입니다. 리뉴얼을 진행하셔야 VM 권한이 유지됩니다.');</script>";
}

if ($row2['accountType'] == "CU") {
    echo "<style>#renewalImage {display:none;} #renewalImage2 {display:block;}.pop_vmc{display:none}.pop_vmr{display:none} </style>";
} else if ($row2['accountType'] == "VD") {
    echo "<style>#renewalImage {display:none;}#renewalImage2 {display:none;} #RenewalButton {display:none;} #smBtn {display:none;} #SmSignup {display:none;}</style>";
} else if ($row2['accountType'] == "SM") {
    echo "<style>#renewalImage {display:none;}#renewalImage2 {display:block;} #RenewalButton {display:block;} #smBtn {display:block;} #SmSignup {display:block;}.pop_vmc{display:none}.pop_vmr{display:none}</style>";
} else if ($row2['accountType'] == 'VM') {
    echo "<style>#renewalImage {display:block;}  #renewalImage2 {display:none;}#sponsor_pop{display:none;}#sponsor_pop2{display:none;} #smBtn {display:none;} #SmSignup {display:none;}</style>";
} else if ($row2['accountType'] == "CU" && $row2['renewal'] != null && $timestamp <= $now_temp) {
    echo "<style>#renewalImage {display:block;}  #renewalImage2 {display:none;}.pop_vmc{display:none}.pop_vmr{display:none} #smBtn {display:none;} #SmSignup {display:none;}</style>";
} else {
    echo "<style>#renewalImage {display:none;}#renewalImage2 {display:none;} #RenewalButton {display:none;} #smBtn {display:none;} </style>";
}
?>

<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js" ></script>
<script type="text/javascript" src="https://service.iamport.kr/js/iamport.payment-1.1.5.js"></script>
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
<!--sm가입 팝업 -->
<script> 
	function ViewLayer2() { 
		
		var now = new Date();
    	var startTime = new Date ( now.getFullYear() + "-" + (now.getMonth()+1) + "-" + now.getDate() + " 00:00:00" );
    	var endTime = new Date ( now.getFullYear() + "-" + (now.getMonth()+1) + "-" + (now.getDate()) + " 01:00:00" );

//    	if( (startTime.getTime() <= now.getTime()) && (endTime.getTime() > now.getTime())) {
//    		alert("금일 00:00 ~ 금일 01:00 점검시간입니다.");
//	        return false;             
//	    } 잠시주석
		
		if(document.all.spot4.style.visibility=="hidden") {
		   document.all.spot4.style.visibility="visible";
           PointOnchange2();
		   return false;
		 }
		 
		if(document.all.spot4.style.visibility=="visible") {
		   document.all.spot4.style.visibility="hidden";
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
            <!-- 포인트 충전 팝업 -->
<script>
function conSubmit() { 
	 if(document.all.pop_container.style.visibility=="hidden") {
	   document.all.pop_container.style.visibility="visible";
	   return false;
	 }
	 if(document.all.pop_container.style.visibility=="visible") {
	  document.all.pop_container.style.visibility="hidden";
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

    <!-- 포인트 충전 결제 모듈구현 -->
<script>
    // 포인트 충전 카드

    /** IMP 변수 초기화 **/
    var IMP = window.IMP;
    IMP.init('imp86936594');
    
    // IMP.request_pay 호출
    function requestPay_card(){
        if( ! $("#payCheckBox").prop("checked") ) {
            alert("결제정책 동의 후 결제 진행이 가능합니다.");
            return false;
        }
        
        /** 사용자가 입력한 충전금액 가져오기 **/
        var amount = $("#price").val();
        /** 회원 이름 가져오기 **/
        var name = $(".user_name").val();
        /** 회원 이메일 가져오기 **/
        var email = $("#emailform").val();
        IMP.request_pay({
            pg : 'kcp', // version 1.1.0부터 지원.
            pay_method : 'card',
            merchant_uid : 'merchant_' + new Date().getTime(),
            name : ' 수소수 텀블러',
            amount : amount,
            buyer_email : email,
            buyer_name : name,
            buyer_postcode : $("#mb_info_id").text(),
            m_redirect_url : 'http://gvmp.company/myOffice/payment.php?m_payment=Y'
        }, function(rsp) {
            if ( rsp.success ) {
                location.href='/myOffice/payment.php?'
                + 'pay_method=' + rsp.pay_method
                + '&paid_amount=' + rsp.paid_amount
                + '&pg_tid=' + rsp.pg_tid
                + '&paid_at=' + rsp.paid_at
                + '&vbank_num=' + rsp.vbank_num
                + '&vbank_name=' + rsp.vbank_name
                + '&vbank_holder=' + rsp.vbank_holder
                + '&vbank_date=' + rsp.vbank_date
                + '&imp_uid=' + rsp.imp_uid;
            } else {
                alert("결제에 실패하였습니다. 에러 내용: " +  rsp.error_msg);
            }
            alert(msg);
        });
    }
</script>

<script>
    // 포인트 충전 계좌이체
    
    /** IMP 변수 초기화 **/
    var IMP = window.IMP;
    IMP.init('imp86936594');
    
    // IMP.request_pay 호출
    function requestPay_trans(){
        if( ! $("#payCheckBox").prop("checked") ) {
            alert("결제정책 동의 후 결제 진행이 가능합니다.");
            return false;
        }
        
        /** 사용자가 입력한 충전금액 가져오기 **/
        var amount = $("#price").val();
        /** 회원 이름 가져오기 **/
        var name = $(".user_name").val();
        /** 회원 이메일 가져오기 **/
        var email = $("#emailform").val();
        IMP.request_pay({
            pg : 'kcp', // version 1.1.0부터 지원.
            pay_method : 'trans',
            merchant_uid : 'merchant_' + new Date().getTime(),
            name : ' 수소수 텀블러',
            amount : amount,
            buyer_email : email,
            buyer_name : name,
            buyer_postcode : $("#mb_info_id").text(),
            m_redirect_url : 'http://gvmp.company/myOffice/payment.php?m_payment=Y'
        }, function(rsp) {
            if ( rsp.success ) {
                location.href='/myOffice/payment.php?'
                + 'pay_method=' + rsp.pay_method
                + '&paid_amount=' + rsp.paid_amount
                + '&pg_tid=' + rsp.pg_tid
                + '&paid_at=' + rsp.paid_at
                + '&vbank_num=' + rsp.vbank_num
                + '&vbank_name=' + rsp.vbank_name
                + '&vbank_holder=' + rsp.vbank_holder
                + '&vbank_date=' + rsp.vbank_date
                + '&imp_uid=' + rsp.imp_uid;
            } else {
                alert("결제에 실패하였습니다. 에러 내용: " +  rsp.error_msg);
            }
        });
    }
</script> 

<script>
    // 포인트 충전 가상계좌
    
    /** IMP 변수 초기화 **/
//    var IMP = window.IMP;
//    IMP.init('imp86936594');
//    
//    // IMP.request_pay 호출
//    function requestPay_vbank(){
//        /** 사용자가 입력한 충전금액 가져오기 **/
//        var amount = $("#price").val();
//        /** 회원 이름 가져오기 **/
//        var name = $(".user_name").val();
//        /** 회원 이메일 가져오기 **/
//        var email = $("#emailform").val();
//        IMP.request_pay({
//            pg : 'kcp', // version 1.1.0부터 지원.
//            pay_method : 'vbank',
//            merchant_uid : 'merchant_' + new Date().getTime(),
//            name : ' 수소수 텀블러',
//            amount : amount,
//            buyer_email : email,
//            buyer_name : name,
//            buyer_postcode : $("#mb_info_id").text(),
//            m_redirect_url : 'http://gvmp.company/myOffice/payment.php?m_payment=Y'
//        }, function(rsp) {
//            if ( rsp.success ) {
//                location.href='/myOffice/payment.php?'
//                + 'pay_method=' + rsp.pay_method
//                + '&paid_amount=' + rsp.paid_amount
//                + '&pg_tid=' + rsp.pg_tid
//                + '&paid_at=' + rsp.paid_at
//                + '&vbank_num=' + rsp.vbank_num
//                + '&vbank_name=' + rsp.vbank_name
//                + '&vbank_holder=' + rsp.vbank_holder
//                + '&vbank_date=' + rsp.vbank_date
//                + '&imp_uid=' + rsp.imp_uid;
//            } else {
//                alert("결제에 실패하였습니다. 에러 내용: " +  rsp.error_msg);
//            }
//            alert(msg);
//        });
//    }
</script> 
    <!-- 포인트 결제 모듈 구현 끝-->

</head>
<body onload="">
	<!-- header -->
<?php
include_once ('../shop/shop.head.php');
include_once ('./rankArea.php');
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
<!--	<form name="myForm3" action="withdrawal.php" method="post">
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
	</form>-->

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
                                
                                $total = number_format($row11['VMC'] + $row11['VMP']);
                                $totalall = number_format( ($row11['VMC'] + $row11['VMP']) * 0.967);
                                
                                $row11['VMC'] = number_format($row11['VMC']);
                                $row11['V'] = number_format($row11['V']);
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
<?php
    if( $row11['accountType'] == "CU" ) {
        $SmSignupText = "SM 가입";
    } else if( $row11['accountType'] == "SM" ) { 
        $SmSignupText = "SM 리뉴얼";
    }
?>			
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
                                                        

                                                        <td id="SmSignup" onclick="javascript:ViewLayer2();" >
                                                            <a href="javascript:;" class="SmSignupbtn underBtn-list" id="smBtn"><?=$SmSignupText?></a>
                                                        </td>
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
                                </label>--> 
                                                                <a class="underBtn-list" href="javascript:;" id="renewalImage"><!--<img src="images/reload.png" alt="renewalBtnImg">--> VM 리뉴얼 </a> 
                                                                <a class="underBtn-list" href="javascript:;" id="renewalImage2"><!--<img src="images/renewal2.png" alt="">-->VM 가입 </a>
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
                                                    <td class="clearfix">
                                                        <a href="allowance_1.php" class="underBtn-list underBtn3">
                                                            <i class="fa fa-krw" aria-hidden="true"></i>수당체계
                                                        </a>
                                                    </td>
                                                    <td class="clearfix">
                                                        <a href="sales_1.php" class="underBtn-list underBtn3">
                                                            <i class="fa fa-user-o" aria-hidden="true"></i>직접추천정보
                                                        </a>
                                                    </td>
                                                    <td class="clearfix"></td>
						</tr>
					</table>
					<!-- 4가지로 분류 -->
				</div>
			</div>
		</form>
                
	</section>
        
        
       
        <!-- 보유 코인 포인트현황 및 포인트정산 -->
        
        <div id="Point_Calculation">
            
            <div id="coinhave"><h1 style="font-weight: 800;font-size: 16.38px;float: left;padding: 20px; background-color: white;">보유 코인 현황</h1></div><br/>        
                <div class="charge">
                        <div class="v">
				<div>V coin</div>
				<div>
					<strong id="vvar"><?=$row11['V']?>개</strong>
				</div>
			</div>
                </div>
            
            <div id="chargehave"><h1 style="font-weight: 800;font-size: 16.38px;float: left;padding: 20px; background-color: white;">보유 포인트 현황</h1></div><br/>
        <div class="charge">
            
			<div class="vmc">
				<div>VMC</div>
				<div>
                                    <strong id="vmcvar"><?=$row11['VMC']?></strong>원
				</div>
			</div>
<!--			<div class="vmr">
				<div>VMR</div>
				<div>
					<strong id="vmrvar"><?=$row11['VMR']?></strong>원
				</div>
			</div>-->
			<div class="vmp">
				<div>VMP</div>
				<div>
					<strong id="vmpvar"><?=$row11['VMP']?></strong>원
				</div>
			</div>
<!--                        <div class="v">
				<div>V coin</div>
				<div>
					<strong id="vvar"><?=$row11['V']?>개</strong>
				</div>
			</div>-->
			<div class="total">
				<div>총 수당</div>
				<div ><?=$total?>원</div>
			</div>
                </div>
            <div id="Point_btn">
            <div class="hot-container" onclick="conSubmit();">
                <p>
                    <a href="#" class="btn btn-red">포인트 충전</a>
                </p>
            </div>
            <div class="hot-container" onclick="conSubmit2();">
                <p>
                    <a href="#" class="btn btn-red">비지니스 팩</a>
                </p>
            </div>
                
            <div class="hot-container" onclick="conSubmit3();">
                <p>
                    <a href="#" class="btn btn-red">포인트 전송</a>
                </p>
            </div>
            </div>
            <div id="pop_container" style="position: fixed; visibility: hidden;">
                <div id="con_fix">
                    <div class="cash_input">
                        <label>충전금액</label>
                        <input type="text" id="price" placeholder=" 금액 입력" onkeydown="onlyNumber(this)"><label>원</label>
                        <br/><br/><p style="font-size: 12px; color: gray;">※ 충전하신 금액은 VMP로 충전됩니다.</p>
                    </div>
                    <p>결제 정책</p>
                    충전하신 금액은 환불이 불가합니다.
                    <br>동의 : <input type="checkbox" id="payCheckBox" style="width: 20px">
                    <br><br><br>
                    <p>결제수단</p>
                    <div class="card1 cash"><a href="javascript:void(0)" onclick="requestPay_card()">카드결제</a></div>
                    <div class="card2 cash"><a href="javascript:void(0)" onclick="requestPay_trans()">계좌이체</a></div>
                    <!--<div class="card3 cash"><a href="javascript:void(0)" style="color:#fff" onclick="requestPay_vbank()">가상계좌</a></div>-->

                    <a href="/myOffice/index.php"><div id="renewal_cancel" style="cursor: pointer;">결제취소</div></a>
                </div>
            </div>
            
        <form action="/adm/businessPack.php" method="POST" id="businessForm">
        <input type="hidden" value="<?=$member['mb_id']?>" name="mb_id">
            <div id="pop_container2" style="visibility: hidden;">
                <div id="con_fix2">
                    <div class="cash_input2">
                        <label>비지니스팩 <img src="images/anfdma1.png" alt="물음표" style="width:13px; cursor: pointer;" id="bph"> <div id="BusinessP"> 비즈니스팩을 구매하시면 기존 VM가입비 대비 130,000원 저렴한 가격에 구매하실 수 있습니다.</div></label>
                        <br/><br/>
                        <hr style="display: block"><br>
                        <label>결제할 포인트</label><br><br>
                        VMC<input type='text' name='VMCpoint' id='VMCpoint' onkeydown="" onblur='call2(this.id)' value="0"/><label>원</label><br/><br/>
                        
                        VMP<input type='text' name='VMPpoint' id='VMPpoint' onkeydown="" onblur='call2(this.id)' value="0"/><label>원</label><br/><br/>
                        
                        <p>결제할 금액 : <b id="givemepoint">350,000</b>원</p>  
                        <p>현재 입력금액 : <b id="totalvpoint">0</b>원</p>
                    </div>
                    
                    
                    <?php
                    $busiRow = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'"));
                    echo "<input type='hidden' id='accountType' value='{$busiRow['accountType']}'>";
                    if( $busiRow['accountType'] == "CU" || $busiRow['accountType'] == "SM" ) { ?>
                    <hr style="display: block"><br>
                    <div>
                        <label>후원인 정보</label><br><br>
                        이름 <input type="text" name="sponsorName" id="BsponsorName"><br><br>
                        ID &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" maxlength="8" onkeydown="onlyNumber(this)" name="sponsorID" id="BsponsorID"><br><br>
                        1팀 <input type="radio" name="teamCheck" style="width: 30px; margin-left: 0px;" value="1">&nbsp;&nbsp;&nbsp;&nbsp;2팀 <input type="radio" name="teamCheck" style="width: 30px; margin-left: 0px;" value="2"><br><br>
                    <br></div>
                    <?php } ?>
                    
                    
                    <hr style="display: block"><br>
                    <p>결제 정책</p><br>
                    결제하신 포인트는 환불이 불가합니다.
                    <br>동의 : <input type="checkbox" id="payCheckBox2" style="width: 20px">
                    <br>
                    
                    <div class="card1 cash"><a href="javascript:void(0)" onclick="requestPay_card2()">비지니스팩 신청</a></div>
                    
                    <a href="/myOffice/index.php"><div id="renewal_cancel2" style="cursor: pointer;">결제취소</div></a>
                </div>
            </div>
        </form>
            
            <form action="pointTransfer.php" method="POST" id="pointTransferF">
                <input type="hidden" id="smsNumber" name="smsNumber">
            <div id="pop_container3" style="position: fixed; visibility: hidden;">
                <div id="con_fix3">
                    <div class="cash_input3">
                        <label>포인트 전송</label>
                        <br/><br/>
                        VMC<input type='text' name='VMCpoint3' id='VMCpoint3' onkeydown="onlyNumber(this)" onblur='call3()'  value="0"/><label>원</label><br/><br/>
<!--                        VMR<input type='text' name='VMRpoint3' id='VMRpoint3' onkeydown="onlyNumber(this)" onblur='call3()'  value="0"/><label>원</label><br/><br/>-->
                        VMP<input type='text' name='VMPpoint3' id='VMPpoint3' onkeydown="onlyNumber(this)" onblur='call3()'  value="0"/><label>원</label><br/><br/>
                        
                        <p>현재입력금액 : <b id="totalvpoint3">0</b>원</p>
                        <br/>
                        <p style="margin-bottom:5px;">포인트 전송받을 ID</p><input type='text' name='smsid' id='smsid' onkeydown="onlyNumber(this)" maxlength="8" style="width:92%;" value=""/><br/>
                        
                        
                    </div>
                    <div id="smstext">
                    </div>
                    <div class="card1 cash" id="smsbox"><a href="javascript:void(0)" onclick="">문자인증</a></div>
                    <div class="card1 cash"><a href="javascript:void(0)" onclick="smsmsg3()">포인트 전송</a></div>
                    
                    <a href="/myOffice/index.php"><div id="renewal_cancel3" style="cursor: pointer;">취소</div></a>
                </div>
            </div>
            </form>
            
            
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
        <script>
            
            var SetTime = 180;
                                function msg_time() {	// 1초씩 카운트
                         mm = Math.floor(SetTime / 60) + "분 " + (SetTime % 60) + "초";	// 남은 시간 계산

                         var msg = "남은 시간 <font color='red'>" + mm + "</font>";

                         document.all.ViewTimer.innerHTML = msg;		// div 영역에 보여줌 

                         SetTime--;					// 1초씩 감소

                         if (SetTime < 0) {			// 시간이 종료 되었으면..

                                 clearInterval(tid);		// 타이머 해제
                                 alert("입력 시간을 초과하였습니다.");
                                 window.location.reload();
                         }

                 }
            
            
            $("#smsbox").click(function(){
                // 인증번호 생성 후 서버에 인증번호 전송 요청
                var result = Math.floor(Math.random() * 9999) + 1;
                var length = 4;
                result=result+""
                var str=""

                for(var i=0;i<length-result.length;i++){
                  str=str+"0";
                }
                str=str+result;
                $("#smsNumber").val(str);

                var data = "smsNumber=" + str + "&mb_id=" + $("#mb_info_id").text();
                $.ajax({
                    url:'smsAuthentication.php',
                    type:'POST',
                    data: data
                });
                
                
                
                
               $("#smsbox").hide(function(){
                   $("#smsbox").attr( "id", "smsbox_temp" );
                   $("#smstext").append( "<p>문자인증</p>" );
                   $("#smstext").append('<p id="ViewTimer"></p>');
                   $("#smstext").append('입력');
                   $("#smstext").append('<input type="text" name="smsinnumber" id="smsinnumber" value="" />');
               });
               tid=setInterval('msg_time()',1000);
            });


            
	</script>
        

        
            
            
            <script>
                $("#bph").click(function(){
                    $("#BusinessP").toggle();
                });
            </script>
        
        </div>
        
        <div id="Pointname"> 
            <h1>포인트 정산 <img src="images/anfdma1.png" alt="" style="width:15px;" id="anfdma" onclick="">          
                <div id="Pointnametext"><p>1~15일 정산 신청 한 금액은 16일에 입금되며, 16~말일까지 신청 한 금액은 익월 1일에 입금됩니다.</p><p>VMC + VMP - 원천세3.3% = 총 정산 금액</p></div>
            </h1>
            
            

        <form method="POST" action="applyForSettlement.php" id="applyForSettForm">
            <input type="hidden" name="calcCheck" id="calcCheck" value="">
<?php
    
    
    // 오늘이 며칠인지 알아내고 1~15 사이라면 1분기로 판별하고
    // 그게 아니라면 2분기로 판별 시키기
    $result;
    if( date("d") >= 16 ) { // 참이면 2분기
        $result = mysql_query("select * from calculateTBL where mb_id = '{$member['mb_id']}' and settlementDate = '".date( "Y-m-d", mktime(0,0,0,date("m")+1,1,date("Y")) )."'");
    } else { // 거짓이면 1분기
        $result = mysql_query("select * from calculateTBL where mb_id = '{$member['mb_id']}' and settlementDate = '".date("Y-m-")."16'");
    }

    // 현재 일자에 해당하는 분기에 정산 신청을 한적이 있는 지 판별
    $row = mysql_fetch_array($result);

    if( $row["mb_id"] != "" ) { // 참이면 정산 신청을 한적 있다
        $n = ($row['VMC'] + $row['VMP']) * 0.967;
        $total = floor($n / 100) * 100; // 전에 신청했던 총 수당 계산하기
        $total = number_format($total);
        echo '        <div id="Point_Calculation2">
            <div class="vmc2">
                    <div>VMC</div>
                    <div>
                        <strong><input type=\'text\' name=\'sell1\' id=\'sell1\' style="width:80%;" value="'.$row["VMC"].'"/></strong>원
                    </div>
            </div>
            <p class="pluseem"> + </p>
            <div class="vmp2">
                    <div>VMP</div>
                    <div>
                        <strong><input type=\'text\' name=\'sell2\' id=\'sell2\' style="width:80%;" value="'.$row["VMP"].'"/></strong>원
                    </div>
            </div>
            <p class="pluseem"> = </p>
            <div class="total2">
                    <div>총 수당</div>
                    <div style="font-weight: 700"><b id="sell3">'.$total.'</b>원</div>
            </div>
        </div>';
    } else { // 거짓이면 정산 신청을 한적 없다.
                echo '        <div id="Point_Calculation2">
            <div class="vmc2">
                    <div>VMC</div>
                    <div>
                        <strong><input type=\'text\' name=\'sell1\' id=\'sell1\' style="width:80%;" readonly="" value="0"/></strong>원
                    </div>
            </div>
            <p class="pluseem"> + </p>
            <div class="vmp2">
                    <div>VMP</div>
                    <div>
                        <strong><input type=\'text\' name=\'sell2\' id=\'sell2\' style="width:80%;" readonly="" value="0"/></strong>원
                    </div>
            </div>
            <p class="pluseem"> = </p>
            <div class="total2">
                    <div>총 수당</div>
                    <div style="font-size: 20px; font-weight: 700;"><b id="sell3">0</b>원</div>
            </div>
            </div>
        ';
    }
    
    
?>

                            
<?php
    $result;
    if( date("d") >= 16 ) { // 참이면 2분기
        $result = mysql_query("select * from calculateTBL where mb_id = '{$member['mb_id']}' and settlementDate = '".date( "Y-m-d", mktime(0,0,0,date("m")+1,1,date("Y")) )."'");
    } else { // 거짓이면 1분기
        $result = mysql_query("select * from calculateTBL where mb_id = '{$member['mb_id']}' and settlementDate = '".date("Y-m-")."16'");
    }
    $row = mysql_fetch_array($result);

    
    echo '<div id="Point_btn" style="width: 100%; text-align:center;">';
    if( $row['mb_id'] == "" ) { // 출금 신청을 안 했으면 참
        echo '<div onclick="confirmBt2Func();" class="hot-container" style=" width: 100%;"><p style="margin-left:0px; margin-bottom:10px;"><a href="#" class="btn btn-red">출금 신청</a></p></div>';
    } else { // 출금 신청을 했으면 참
        echo '<div onclick="confirmBt2Func3();" class="hot-container calcButt"><p><a href="#" class="btn btn-red">신청 금액 수정</a></p></div>';
        echo '<div onclick="confirmBt2Func2();" class="hot-container calcButt"><p><a href="#" class="btn btn-red">출금 취소</a></p></div>';
    }
    echo '</div>';

    
    

    
?>    

                
        </div>
       </form>     
           </div> 

            

        <script type="text/javascript">
                                $(document).ready(function() {
                                    $('.closeBt2').click(function() {
                                        location.reload();
                                    });
                                });       
                            </script>
        <script>
            $("#anfdma").click(function(){
                $("#Pointnametext").toggle();
            });
                        
        </script>

        
        

        
<script language='javascript'>
    
    function confirmBt2Func() {
        if( confirm("정산 신청을 진행하시겠습니까?") ) {   
            $("#sell1").val( $("#vmcvar").text().replace(/[^\d]+/g, '') );
            $("#sell2").val( $("#vmpvar").text().replace(/[^\d]+/g, '') );
       
            $('#applyForSettForm').submit();
        }
    }
    
    function confirmBt2Func2() {
        if( confirm("정산 신청을 취소하시겠습니까?") ) {
            $("#calcCheck").val("취소");
            $('#applyForSettForm').submit();
        }
    }
    
    function confirmBt2Func3() {
        if( confirm("정산 신청하신 금액을 수정하시겠습니까?") ) {
            $("#calcCheck").val("수정");
            $('#applyForSettForm').submit();
        }
    }
	

//숫자만 입력받음

function onlyNumber(obj) {
    $(obj).keyup(function(){
         $(this).val($(this).val().replace(/[^0-9]/g,""));
    }); 
}


</script>
        
        
        <input type="hidden" id="calendarType" value="1">
        <input type="hidden" value="test" id="popUpVal">

        
	<!-- 달력 -->
        
	<div class="calendar">
		<div class="calendar_tab">
                    <ul class="c_tab clearfix">
                        <li class="on" onclick="calendarType(1);">입/출금 내역</li>
                        <li onclick="calendarType(2);">입금 내역</li>
                        <li onclick="calendarType(3);">출금 내역</li>

                    </ul>
                </div>
                <script type="text/javascript">
              
                       $('.c_tab > li').click(function(){
                            $(this).addClass('on');
                            $(this).siblings('li').removeClass('on');
                        });
      
                </script>
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
                        // 내꺼는 빨간색
                        if( $row5['mb_id'] == $member['mb_id'] ) {
                            echo "<tr id=\"ordernum\">
                                	<td style='color:red;'>{$row5['productName']}</td>
                                    <td style='color:red;'>{$row5['n']}</td>
                                    <td style='color:red;'>{$row5['orderDate']}</td>
                                    <td style='color:red;'>{$row5['mb_name']}</td>
                                    <td style='color:red;'>￦".number_format($row5['money'])."</td>
                                    <td style='color:red;'>￦".number_format($row5['commission'])."</td>
                                  </tr>";
                        } else {
                            echo "<tr id=\"ordernum\">
                                	<td>{$row5['productName']}</td>
                                    <td>{$row5['n']}</td>
                                    <td>{$row5['orderDate']}</td>
                                    <td>{$row5['mb_name']}</td>
                                    <td>￦".number_format($row5['money'])."</td>
                                    <td>￦".number_format($row5['commission'])."</td>
                                  </tr>";
                        }
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
                                    if( json[i][6] != $("#mb_info_id").text() ) {
                                        $("#orderTB").append("<tr id=\"ordernum\"><td>"+json[i][0]+"</td><td>"+json[i][1]+"</td><td>"+json[i][2]+"</td><td>"+json[i][3]+"</td><td>￦"+comma(json[i][4])+"</td><td>￦"+comma(json[i][5])+"</td></tr>");
                                    } else {
                                        $("#orderTB").append("<tr id=\"ordernum\" style='color:red;'><td style='color:red;'>"+json[i][0]+"</td><td style='color:red;'>"+json[i][1]+"</td><td style='color:red;'>"+json[i][2]+"</td><td style='color:red;'>"+json[i][3]+"</td><td style='color:red;'>￦"+comma(json[i][4])+"</td><td style='color:red;'>￦"+comma(json[i][5])+"</td></tr>");
                                    }
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
<!--	<div class="withdrawal">
		<button type="button" class="withdrawal_btn" id="with_btn"
			onclick="javascript:showwithdrawal();">VMP회원탈퇴하기</button>
	</div>-->


	<form action="renewal.php" method="post" id="orderform">
		<input type="hidden" name="userid" id="userid" value=<?=$member['mb_id']?>> 
                <input type="hidden" name="VMC" value=""> 
                <input type="hidden" name="VMR" value=""> 
                <input type="hidden" name="VMP" value=""> 
                <input type="hidden" name="pay" value=""> 
                <input type="hidden" name="totalpoint" value="">
                <input type="hidden" name="H_reg_mb_sponsor" id="H_reg_mb_sponsor" value="">
                <input type="hidden" name="H_reg_mb_sponsorID" id="H_reg_mb_sponsorID" value="">
                <input type="hidden" name="H_radiobtn" id="H_radiobtn" value="">
	</form>

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
                                                <div class="pop_vmr pop" style="display:none;">
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

<!--sm가입-->

    <div id="spot4" style="visibility: hidden;">		
        <?php
            $renewalTitle = mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'");
            $renewalTitleRow = mysql_fetch_array($renewalTitle);

            if( $renewalTitleRow['accountType'] == 'CU' ) {
                $renewalTitleText = "SM 가입";
            } else if( $renewalTitleRow['accountType'] == 'SM' ) { 
                $renewalTitleText = "SM 리뉴얼";
            }
        ?>
        <form method="POST" action="smJoin.php" id="smJoinForm">
            <div class="pop_tit2"><?=$renewalTitleText?></div>
            <div id="sm_pop">
                <div class="pop_vmp2 pop2">
                    <div>
                        <p>보유 VMP</p>
                        <span id="myVMP"><?=$row11['VMP']?></span>
                    </div>
                </div>
                <div class="summary2">
                    <div><p>결제할 금액 : </p><span id="sum_count2">20,000원</span></div>    
                </div>
                <div id="sm_submit" onclick="submitCheck2();">결제하기</div>
                <a href="/myOffice/"><div id="sm_cancel">취소</div></a>
            </div>
        </form>
    </div>



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
			$('.user_Modified input').addClass('on').attr('readonly',false);
			$(this).css('display', 'none');
			$modifiedBtConfirmBt.css('display', 'block');
			$modifiedBtCloseBt.css('display', 'block');
			$passwordChangeBt.css('display', 'block');
			$adressSearchBt.addClass('on');
		})
		$modifiedBtConfirmBt.on('click',function(){
			$('.user_Modified input').removeClass('on').attr('readonly',true);
			$(this).css('display', 'none');
			$modifiedBtImg.css('display', 'block');
			$modifiedBtCloseBt.css('display', 'none');
			$adressSearchBt.removeClass('on');
		})
		$modifiedBtCloseBt.on('click',function(){
			$('.user_Modified input').removeClass('on').attr('readonly',true);
			$(this).css('display', 'none');
			$modifiedBtImg.css('display', 'block');
			$modifiedBtConfirmBt.css('display', 'none');
			$passwordChangeBt.css('display', 'none');
			$adressSearchBt.removeClass('on');
		})
		
    });
	</script>
        
        <script type="text/javascript">
	$(document).ready(function() {
		
		var $modifiedBtImg2 = $('.modifiedBt2 > img');
		var $modifiedBtConfirmBt2 = $('.confirmBt2');
		var $modifiedBtCloseBt2 = $('.closeBt2');
		
                $modifiedBtImg2.on('click',function(){
                    if( $("#sell1").val() == "" && $("#sell2").val() == "" ) {
                        $('#Point_Calculation2 input').addClass('on').attr('readonly',false);
                    }
			
			$(this).css('display', 'none');
			$modifiedBtCloseBt2.css({"display":"block","float":"right"});
			$modifiedBtConfirmBt2.css({"display":"block","float":"right"});
		})
		$modifiedBtConfirmBt2.on('click',function(){
			$('#Point_Calculation2 input').removeClass('on').attr('readonly',true);
			$(this).css('display', 'none');
			$modifiedBtImg2.css({"display":"block","float":"right"});
			$modifiedBtCloseBt2.css({"display":"none","float":"right"});
		})
		$modifiedBtCloseBt2.on('click',function(){
			$('#Point_Calculation2 input').removeClass('on').attr('readonly',true);
			$(this).css('display', 'none');
			$modifiedBtConfirmBt2.css({"display":"none","float":"right"});
			$modifiedBtImg2.css({"display":"block","float":"right"});
		})
		
    });
	</script>
        
        
        

	<script>
           

        function submitCheck(){
            
                var data = "reg_mb_sponsor=" + $('#reg_mb_sponsor').val() + "&"
                + "reg_mb_sponsor_no=" + $('#reg_mb_sponsorID').val();

                 $.ajax({
                     url:'/bbs/register_sponsorValue_checking.php',
                     type:'get',
                     data: encodeURI(data),
                     async: false,
                     success:function(result){
                         if( result == "false" ) {
                             alert("입력하신 후원인 정보가 잘 못 됐습니다.");
                             $("#reg_mb_sponsorID").val("");
                             $("#reg_mb_sponsor").val("");
                             $("#reg_mb_sponsor").focus();
                             return false;
                         }
                    }
                 });
            
            
                    if( $("#accountType").text() == 'CU' ) {
                        if( ! $('input:radio[name=radiobtn]').is(':checked') ) {
                            alert("※ 팀을 선택하지 않으셨습니다.");
                            return false;
                        }
                        if( $("#reg_mb_sponsor").val() == "" || $("#reg_mb_sponsorID").val() == "" ) {
                            alert("후원인 정보를 입력하지 않으셨습니다.");return false;
                        }
                    }
                    var popsum = $('.vm_sum').find('span');
                    var popsummary = $('.summary').find('span');
                    
                      if(Number(popsummary.text()) < 0){
                            alert("포인트가 결제할 금액보다 큽니다.");
                            return false;                    
                      }else if(Number(popsummary.text()) > 0){
                    	  alert('※ 포인트가 부족합니다.');
                      }else if(Number(popsummary.text()) == 0) {
                          var a = $("#reg_mb_sponsor").val();
                          var b = $("#reg_mb_sponsorID").val();
                          var c = $("input:radio[name=radiobtn]:checked").val();

                          $("#H_reg_mb_sponsor").val( a );
                          $("#H_reg_mb_sponsorID").val( b );
                          $("#H_radiobtn").val( c );
                          
                          if( prompt('VM 가입시 환불이 불가합니다.가입 정책에 동의합니까?"동의합니다"를 입력하세요.') == "동의합니다" )
                               document.getElementById('orderform').submit();
                           else
                               alert("\"동의합니다\"를 입력하지 않으면 결제를 진행할 수 없습니다.");
                      }
                }   
           
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
        
        </script>
        
      	<script>
        //sm가입   

        function submitCheck2(){
            // SM 가입할 때 결제하기 클릭하면 동작하는 함수 
            
            if( Number($("#myVMP").text().replace(/[^\d]+/g, '')) < 20000 ) {
                alert("VMP 포인트가 부족합니다.");
                return;
            }
             
            if( prompt('SM 가입시 환불이 불가합니다.가입 정책에 동의합니까?"동의합니다"를 입력하세요.') == "동의합니다" )
                 document.getElementById('smJoinForm').submit();
             else
                 alert("\"동의합니다\"를 입력하지 않으면 결제를 진행할 수 없습니다.");
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
                 
         calendar += '<td class="' + dateString[j] + '" id="'+dateNum+'" onclick="calenPop(this.id, this.className )" >' + dateNum + '</td>';
      }
      calendar += '</tr>';
   }
    
   calendar += '</tbody>';
   calendar += '</table>';
   
   kCalendar2.innerHTML = calendar;
   
   
   var data = "year=" + currentYear + "&month=" + currentMonth + "&type=" + $("#calendarType").val();
   
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
                   var cont = ""
                   if( json[i][6] != null ) {
                       cont = '<font style="color:red; float:left; padding-left:7px;">PASS</font>';
                   }
                   $('#'+json[i][5]).html(cont + json[i][5]+'<div class=\"box_wrap\"><ul class=\"box\"><li class=\"box1\">VMC</li><li class="">￦'+json[i][0]+'</li></ul><ul class=\"box\"><li class=\"box2\">VMR</li><li>￦'+json[i][1]+'</li></ul><ul class=\"box\"><li class=\"box3\">VMP</li><li>￦'+json[i][2]+'</li></ul></div>');    
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

    $.ajaxSetup({ cache: false });
     $.ajax({
         url:'/bbs/register_sponsorValue_checking.php',
         type:'get',
         data: encodeURI(data),
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
    
    function onlyNumber(obj) {
        $(obj).keyup(function(){
             $(this).val($(this).val().replace(/[^0-9]/g,""));
        }); 
    }

</script>


<!-- 비즈니스팩 팝업 -->
<!--<div class="bz_pop">
    <div class="bz_pop_inner">
        <img src="http://gvmp.company/data/common/mobile_logo_img" class="bz_pop_logo">
        <h6>VMP 비즈니스 팩</h6>
        <p class="txt_sm">(6월 23일 이후 가격 인상 예정)</p>
        <br>
        <p class="txt_md">
            계좌번호 : 1002-254-564367<br>
            우리은행 브이엠피 32만원 (현금결제)
            <br><br>
            적용대상 : 신규(CU) 사업자 & VM 리뉴얼
            
        </p>
        <br>
        <a href="http://pf.kakao.com/_aAbxiC/chat" target="_blank" class="pop_kakaoBtn"><img src="/theme/everyday/mobile/shop/img/kakaochat3.png"> 비지니스팩 구매인증</a>
        <div style="text-align: center; width: 100%; position: relative;">비지니스팩 구매인증 : 오전 10 - 오후 10시</div>
    </div>
    <ul class="bz_pop_closeBtn">
        <li class="bz_pop_closeOne">오늘 하루 팝업닫기</li>
        <li class="bz_pop_close">팝업닫기</li>
    </ul>
</div>-->

<script>
    var pop_dialog = $('.bz_pop');
    var pop_duration = 300;
    
    if (!readCookie('pop_hide')) pop_dialog.fadeIn(pop_duration);
    
    $('.bz_pop_closeOne').on('click', function() {
        pop_dialog.fadeOut(pop_duration);
        createCookie('pop_hide', true, 1)
        
        return false;
    });
    
    $('.bz_pop_close').on('click', function() {
       pop_dialog.fadeOut(pop_duration);
    });
    
    /* jQuery Qookie */
    function createCookie(name, value, days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            var expires = "; expires=" + date.toGMTString();
        }
        else var expires = "";
            document.cookie = name + "=" + value + expires + "; path=/";
        }

    function readCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    }

    function eraseCookie(name) {
        createCookie(name, "", -1);
    }
</script>


<script type="text/javascript">




function call2(id){
        var VMCP = parseInt( $("#VMCpoint").val() );
        var VMPP = parseInt( $("#VMPpoint").val() );
        
        
        
        var totalvpoint = $("#totalvpoint");

        var vmcvar = parseInt(($("#vmcvar").text().replace(/[^\d]+/g, '')));
        var vmpvar = parseInt(($("#vmpvar").text().replace(/[^\d]+/g, '')));


        if( vmcvar < VMCP ) {
            alert("보유하신 VMC보다 큰 금액을 정산 신청할 수 없습니다.");
            $("#VMCpoint").val("0");
            $("#VMCpoint").focus();
        }


        if( vmpvar < VMPP ) {
            alert("보유하신 VMP보다 큰 금액을 정산 신청할 수 없습니다.");
            $("#VMPpoint").val("0");
            $("#VMPpoint").focus();
        }
        
        var allVpoint = VMCP+VMPP;
        if( allVpoint > 350000 ) {
            alert("결제 금액인 350,000원을 초과해서 입력할 수 없습니다.");
            $("#"+id).val("0");
            $("#"+id).focus();
        }

        
        $("#totalvpoint").text(allVpoint.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));

        
}




    function requestPay_card2(){
        if( ! $("#payCheckBox2").prop("checked") ) {
            alert("결제정책 동의 후 결제 진행이 가능합니다.");
            return false;
        }

        if( (parseInt($("#VMCpoint").val()) + parseInt($("#VMPpoint").val()) ) < 350000 ) {
            alert("입력하신 금액이 350,000원 미만입니다.");
            return false;
        }
        
        if( $("#accountType").val() == "CU" ) {
            if( $("#BsponsorName").val() == "" ) {
                alert("후원인 이름을 입력하세요."); return false;
            } else if ( $("#BsponsorID").val() == "" ) {
                alert("후원인 ID를 입력하세요."); return false;
            } else if( ! $('input:radio[name=teamCheck]').is(':checked') ) {
                alert("소속될 팀을 선택하세요."); return false;
            }
        }
        
        if( prompt('비즈니스팩 결제는 환불이 불가합니다.가입 정책에 동의합니까?"동의합니다"를 입력하세요.') == "동의합니다" )
             document.getElementById('businessForm').submit();
         else
             alert("\"동의합니다\"를 입력하지 않으면 결제를 진행할 수 없습니다.");
        
    }


</script>

<script>
function conSubmit2() { 
	 if(document.all.pop_container2.style.visibility=="hidden") {
	   document.all.pop_container2.style.visibility="visible";
	   return false;
	 }
	 if(document.all.pop_container2.style.visibility=="visible") {
	  document.all.pop_container2.style.visibility="hidden";
	  return false;
	 }
	 
	}
function popclose2(){
	$("#spot2").hide();
	location.reload();
}
</script>


<script type="text/javascript">

function call3(){
        var VMCP3 = parseInt( $("#VMCpoint3").val() );
//        var VMRP3 = parseInt( $("#VMRpoint3").val() );
        var VMPP3 = parseInt( $("#VMPpoint3").val() );http://ksohee1207.dothome.co.kr/img/me_intro.png
        
        
        
        var totalvpoint3 = $("#totalvpoint3");

        var vmcvar3 = parseInt(($("#vmcvar").text().replace(/[^\d]+/g, '')));
//        var vmrvar3 = parseInt(($("#vmrvar3").text().replace(/[^\d]+/g, '')));
        var vmpvar3 = parseInt(($("#vmpvar").text().replace(/[^\d]+/g, '')));


        if( vmcvar3 < VMCP3 ) {
            alert("보유하신 VMC보다 큰 금액을 정산 신청할 수 없습니다.");
            $("#VMCpoint3").val("0");
            $("#VMCpoint3").focus();
        }

//        if( vmrvar3 < VMRP3 ) {
//            alert("보유하신 VMR보다 큰 금액을 정산 신청할 수 없습니다.");
//            $("#VMRpoint3").val("0");
//            $("#VMRpoint3").focus();
//        }

        if( vmpvar3 < VMPP3 ) {
            alert("보유하신 VMP보다 큰 금액을 정산 신청할 수 없습니다.");
            $("#VMPpoint3").val("0");
            $("#VMPpoint3").focus();
        }


//3자리마다 콤마 만들어주는식        
        function numberWithCommas() {
            return allVpoint3.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }


        var allVpoint3 = VMCP3+VMPP3;
        $("#totalvpoint3").text(numberWithCommas(allVpoint3));


        
}





</script>
<script>
function conSubmit3() { 
	 if(document.all.pop_container3.style.visibility=="hidden") {
	   document.all.pop_container3.style.visibility="visible";
	   return false;
	 }
	 if(document.all.pop_container3.style.visibility=="visible") {
	  document.all.pop_container3.style.visibility="hidden";
	  return false;
	 }
	 
	}
function popclose2(){
	$("#spot2").hide();
	location.reload();
}

function onlyNumber(obj) {
    $(obj).keyup(function(){
         $(this).val($(this).val().replace(/[^0-9]/g,""));
    }); 
}

</script>


<script>

function smsmsg3(){
    
    
    
        var point3 = $("#totalvpoint3").text();
    
        if( point3 == 0 ) {
            alert ("0원을 전송하실 수 없습니다.");
            $("#VMCpoint3").focus();
            return;
        }
        
        var num = $("#smsid").val();
        num = num.toString();
        numDigit = num.length;
        console.log(numDigit);

        if (numDigit < 8){
            alert("ID는 8자리 숫자 형태로만 가능합니다.");
            $("#smsid").focus();
            return;
        }


        
        if ( $("#smsbox").attr( "id" ) == "smsbox" ){
            alert("문자인증 후 진행할 수 있습니다.");
            $("#smsinnumber").focus();
            return;
        }
        
        if ($("#smsinnumber").val() != $("#smsNumber").val() ){
            alert("인증번호가 틀렸습니다.");
            $("#smsinnumber").val("");
            $("#smsinnumber").focus();
            return;
        }

        
        if ( point3 != 0 && numDigit == 8 ){
            var data = "mb_id=" + $("#smsid").val();
            $.ajax({
                url:'idTOname.php',
                type:'POST',
                data: data,
                async: false,
                success:function(result){
                    if( result == "" ) {
                        alert("존재하지 않는 ID입니다.");
                        return;
                    }
                    if( confirm(result + "님에게 "+ $("#totalvpoint3").text() +"원을 전송하시겠습니까?") ) {
                        $("#pointTransferF").submit();
                    }
                }
            });
        }
        
}


function calendarType(v) {
    $("#calendarType").val(v);
    kCalendar2('kCalendar');
}

//달력의 날짜를 눌렀을 때 팝업창에 해당날짜의 포인트정보가 나온다

function calenPop( a, b ) {
        var pToday = $("#date").text() + " " + a ;     
        var pToday = pToday.split(" ");
        var ptYear = pToday[0].slice(0,-1);
        var ptMonth = pToday[1].slice(0,-1);
        var mtLength = ptMonth.length;
        
        if(1==mtLength){
          var ptMonth = '0'+ ptMonth;
        }
        var ptDate = pToday[2];
        var dtLength = ptDate.length;
       
        if(1==dtLength){
          var ptDate = '0'+ ptDate;
        }
        
        var pointToday = ptYear + "-" + ptMonth + "-" + ptDate;  
          
       $("#popUpVal").val( pointToday );
       
      
	var popOption = "width=610, height=810, top=50, left=50, toolbar=no,  menubar=no, resizeable=no";
        var windowObj = window.open("/myOffice/point_pop.php","vmp_point",popOption);
      

}





</script>

