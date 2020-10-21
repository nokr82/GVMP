
<link rel="shortcut icon" href="/img/vmp_logo.ico" />
<link rel="stylesheet" href="./css/index.css" />
<?php
include_once ('./inc/title.php');
include_once ('./_common.php');
include_once ('./dbConn.php');

if ($is_guest) // 로그인 안 했을 때 로그인 페이지로 이동
    header('Location: /bbs/login.php');

/* 1004 VMP 가져오기 */
$row = mysql_fetch_array(mysql_query("SELECT VMP FROM g5_member WHERE mb_id = '00001004'"));
$ANGELVMP = $row['VMP'];
/* 1004 VMP 가져오기 */
/* 추천인, 후원인 정보값 가져오기 */
$result = mysql_query("SELECT * FROM genealogy where mb_id = '{$member['mb_id']}'"); /* 로그인했을때 사용자의 genealogy정보를 불러옴 */
$row = mysql_fetch_array($result);

$dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$member['renewal']}', interval +4 month) AS date"));
$dateCheck_2 = mysql_fetch_array(mysql_query("SELECT date_add('{$member['renewal']}', interval +5 month) AS date"));
$TIMESTAMP = $dateCheck_1["date"];
$timestamp2 = $dateCheck_2["date"];
$now = date("Y-m-d");


//if($member['mb_id']=="00003571"){ //DEbug 용

$REMAINTIME = date("Y-m-d", strtotime($TIMESTAMP . "-7 day"));
$LIMITEDAY = 7;
$REMAIN = "END";

$REMAINDATE = strtotime($REMAINTIME . " 00:00:00");
$NOWDATE = strtotime($now . " 00:00:00");
$TIMESTAMPDATE = strtotime($TIMESTAMP . " 00:00:00");
if($NOWDATE >= $REMAINDATE) {
	$REMAINTIMETMP = date_create($REMAINTIME);
	$NOWDATETMP = date_create($now);
	$INTERVAL = date_diff($REMAINTIMETMP,$NOWDATETMP);
	$REMAIN = $LIMITEDAY - $INTERVAL->days;
	//"지금 시간이 더 크거나 같다
} elseif($NOWDATE == $TIMESTAMPDATE) {
	//만료
	$REMAIN = "END";
}

//}



$timestamp_temp = strtotime($TIMESTAMP);
$timestamp2_temp = strtotime($timestamp2);
$now_temp = strtotime($now);


if ($member['accountType'] == 'VM' && ($now_temp > $timestamp_temp && $now_temp <= $timestamp2_temp)) {
//    echo "<script>alert('{$timestamp2}이 되면 CU 계정으로 변경되오니 리뉴얼을 진행해 주시기 바랍니다.');</script>";
    echo "<script>alert('현재 VM 기간이 만료된 상태입니다. 리뉴얼을 진행하셔야 VM 권한이 유지됩니다.');</script>";
}

if ( $member['accountType'] == "CU" && $member['renewal'] != null ) {
	echo "<style>#renewalImage {display:block;}  #renewalImage2 {display:none;}#sponsor_pop{display:none;}#sponsor_pop2{display:none;} #smBtn {display:none;} #SmSignup {display:none;}</style>";
} else if ($member['accountType'] == "CU") {
    echo "<style>#renewalImage {display:none;} #renewalImage2 {display:block;}.pop_vmc{display:none}.pop_vmr{display:none} </style>";
} else if ($member['accountType'] == "VD") {
    echo "<style>#renewalImage {display:none;}#renewalImage2 {display:none;} #RenewalButton {display:none;} #smBtn {display:none;} #SmSignup {display:none;}</style>";
} else if ($member['accountType'] == "SM") {
    echo "<style>#renewalImage {display:none;}#renewalImage2 {display:block;} #RenewalButton {display:block;} #smBtn {display:block;} #SmSignup {display:block;}.pop_vmc{display:none}.pop_vmr{display:none}</style>";
} else if ($member['accountType'] == 'VM') {
    echo "<style>#renewalImage {display:block;}  #renewalImage2 {display:none;}#sponsor_pop{display:none;}#sponsor_pop2{display:none;} #smBtn {display:none;} #SmSignup {display:none;}</style>";
} else if ($member['accountType'] == "CU" && $member['renewal'] != null && $TIMESTAMP <= $now_temp) {
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

// 리뉴얼 결제 팝업
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

// sm가입 팝업

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

// 비밀번호 수정 팝업


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

//회원탈퇴 팝업
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

//포인트 충전 팝업
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

/*
$( function() {
	$( ".history table" ).draggable({ axis: "x", refreshPositions: true, 
										drag: function( event, ui ) {
											ui.position.left = Math.min( 0, ui.position.left );
										}
									});
} );
*/

//포인트 충전 결제 모듈구현 

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
include_once ('./rankArea.php');   //마이오피스 랭크 주석처리

?>


</body>
</html>
<?php
include_once ('../shop/shop.tail.php');
?>