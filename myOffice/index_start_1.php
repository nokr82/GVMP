

<link rel="shortcut icon" href="/img/vmp_logo.ico" />
<link rel="stylesheet" href="./css/index.css" />
<link rel="stylesheet" href="./css/v_ad_center.css" />
<link rel="stylesheet" href="css/animate.css"/>
<script src="./js/wow.min.js"/></script>
<?php
include_once ('./inc/title.php');
include_once ('./_common.php');
include_once ('./dbConn.php');
include_once('./getMemberInfo.php');

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

if( $member['renewal'] == "" ) {
    $TIMESTAMP = "0000-00-00";
}

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




if ($member['accountType'] == 'VM' && ($now_temp >= $timestamp_temp && $now_temp <= $timestamp2_temp)) {
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
<script>
   // 포인트전송 display:none 처리
$(function(){
    var infoId = $("#mb_info_id").text();
    if(infoId == "00003834" || infoId == "00005857" || infoId == "00003687"){
        $(".point_transfer").css("display","none")
    }
    
    
    //팝업 헤더 크기에 따른 위치 수정
    var hd_height = $('#hd').height() + 10;
    $('.popup_position').css("top",hd_height+"px");
});



$(function(){
    new WOW().init();
});
</script>
<SCRIPT LANGUAGE="JavaScript">
function dateSelect(docForm,selectIndex) {
   watch = new Date(docForm.year.options[docForm.year.selectedIndex].text, docForm.month.options[docForm.month.selectedIndex].value,1);
   hourDiffer = watch - 86400000;
   calendar = new Date(hourDiffer);
   var daysInMonth = calendar.getDate();
//      for (var i = 0; i < docForm.day.length; i++) { // 날짜별 조회 주석처리
//         docForm.day.options[0] = null;
//      }
//      for (var i = 0; i < daysInMonth; i++) {
//         docForm.day.options[i] = new Option(i+1);
//      }
//   document.form1.day.options[0].selected = true;
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
        
//기존 회원정보  
let originHp;
let originBirth;
let originMail;

$(document).ready(function(){

    //기존 회원정보 
    originHp = $('#hp').val();
    originBirth = $('#birth').val(); 
    originMail = $('#emailform').val();
});



// 회원정보수정 완료버튼 눌렀을 때 수행하는 함수
function check_submit() {
    var hpNumber = $('#hp').val();
    var check = confirm("회원정보를 수정하시겠습니까?");
    
    
    if ( $('#name').val() == "" ) {
        alert('이름을 입력하세요');
        $('#name').focus();
        return;

    } else if ($('#hp').val() == "" || $('#hp').val().length < 13) {
             alert('핸드폰번호를 입력하세요.(- 포함 13자리.)');
             $('#hp').focus();
             $('.second_element').removeClass('active');
             return;

    } else if ($('#birth').val() == "" || $('#birth').val().length < 6) {
             alert('생년월일을 입력하세요(- 제외, 주민번호 앞 6자리)');
             $('#birth').focus();
             $('.second_element').removeClass('active');
             return;

    }else if (!isNaN('#birth')) {
             alert('생년월일은 숫자로 입력하세요.(- 제외, 주민번호 앞 6자리)');
             $('#birth').focus();
             $('.second_element').removeClass('active');
             return;
                 
    }else if ($('#emailform').val() == "") {
             alert('이메일을 입력하세요');
             $('#emailform').focus();
             $('.second_element').removeClass('active');
             return;

    } else if ($('#reg_mb_zip').val() == "") {
             alert('우편번호를 입력하세요');
             $('#reg_mb_zip').focus();
             $('.second_element').removeClass('active');
             return;

    }else if ($('#reg_mb_addr1').val() == "") {
             alert('주소를 입력하세요');
             $('#reg_mb_addr1').focus();
             $('.second_element').removeClass('active');
             return;

    }else if ($('#reg_mb_addr2').val() == "") {
            alert('상세주소를 입력하세요');
            $('#reg_mb_addr2').focus();
            $('.second_element').removeClass('active');
            return;

    }else if ($('#bankName').val() == "") {
            alert('은행명을 입력하세요');
            $('#bankName').focus();
            $('.second_element').removeClass('active');
            return;
              
    }else if ($('#accountHolder').val() == "") {
            alert('예금주를 입력하세요');
            $('#accountHolder').focus();
            $('.second_element').removeClass('active');
            return;

//    }else if ( $.trim($('#accountHolder').val()) !== $.trim($('#name').val()) ){
//            alert('이름과 예금주가 동일하지 않습니다.');
//            $('#accountHolder').focus();
//            $('.second_element').removeClass('active');
//            return;
        
    } else if ($('#accountNumber').val() == "") {
            alert('계좌번호를 입력하세요');
            $('#accountNumber').focus();
            $('.second_element').removeClass('active');
            return;

    }else if (!isNaN('#accountNumber')) {
           alert('계좌번호는 숫자로 입력하세요.(- 제외)');
           $('#accountNumber').focus();
            $('.second_element').removeClass('active');
           return;
       
              
//    } else if ( originHp != $('#hp').val() && $(".hp_confirm_btn").css("display") == "block" ){ // 연락처를 변경했으면 참
//            alert("변경한 번호로 문자인증 후 진행할 수 있습니다.");
//            $('.second_element').removeClass('active');
//            $('#hp').val( originHp );
//            $("#hp").focus();
//             return;
//         
//    } else if ( $('.hp_confirm_box').children().is('#hpinnumber') && $("#hpinnumber").val() !== $("#hp_smsnumber").val() ){ 
//
//        alert("인증번호가 틀렸습니다.");
//        modiclose();
//        if( $('.second_element').hasClass('active') ){
//        
//            if( $(".hp_confirm_box").hasClass('active') ){ //인증번호 나타나는 box와 제한시간 초기화
//              
//                $(".hp_confirm_box").removeClass('active');
//                $(".hp_confirm_box").html('');
//                $('.hp_reconf_btn').css('display','none');
//                
//                SetTime = 180;
//                clearInterval(tid);
//            
//            }
//        $('.second_element').removeClass('active');
//        $('.hp_confirm_btn').css('display','none');
//        $('.hp_reconf_btn').css('display','none');
//
//    }       
        return; 
        
    } else if (check){ 
        $('.second_element').removeClass('active');
        $("#hp").removeAttr("disabled");
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
    $('.second_element').addClass('active');
    $("#hp").attr("disabled",true).attr("readonly",true);
    $("#renewel").attr("disabled",true).attr("readonly",false);
    $("#recommenderName").attr("disabled",true).attr("readonly",false);
    $("#recommenderID").attr("disabled",true).attr("readonly",false);
    $("#sponsorName").attr("disabled",true).attr("readonly",false);
    $("#sponsorID").attr("disabled",true).attr("readonly",false);
    $("#name").attr("disabled",true).attr("readonly",false);

    var Now = new Date();
    var NowTime  = {'time':Now.getHours(),'min':Now.getMinutes()}

//                if( ! (NowTime.time == 10 || NowTime.time == 11 || NowTime.time == 16 || NowTime.time == 17 || NowTime.time == 22 ||NowTime.time == 23 ) ){
        // 참이면 이름 변경 못 하게 막기
//                    $("#name").attr("disabled",true).attr("readonly",false);
//                }
  
};




function modiclose() {
    $('.second_element').removeClass('active');    
    $("#renewel").attr("readonly",false).attr("disabled",false);
    $("#recommenderName").attr("readonly",false).attr("disabled",false);
    $("#recommenderID").attr("readonly",false).attr("disabled",false);
    $("#sponsorName").attr("readonly",false).attr("disabled",false);
    $("#sponsorID").attr("readonly",false).attr("disabled",false);
    $("#name").attr("readonly",false).attr("disabled",false);
    $('#hp').css('background-color','#fff');
    $('#hp').css('color','#333');


    //정보수정을 취소할 때 기존 회원정보로 리셋합니다.
    $('#hp').val(originHp);
    $('#birth').val(originBirth);
    $('#emailform').val(originMail);


    //문자인증 비활성화
    if( $('.second_element').hasClass('active') ){
        
            if( $(".hp_confirm_box").hasClass('active') ){ 
              
                $(".hp_confirm_box").removeClass('active');
                $(".hp_confirm_box").html('');
                
                SetTime = 180;
                clearInterval(tid);
            }
            
        $('.second_element').removeClass('active');
        if( $('.hp_confirm_btn').css('display') == 'block' ){ //문자인증버튼이 보이면
                $('.hp_confirm_btn').css('display','none');
        }
    }               

};

// 리뉴얼 결제 팝업
function ViewLayer() { 
		
    var now = new Date();
    var startTime = new Date ( now.getFullYear() + "-" + (now.getMonth()+1) + "-" + now.getDate() + " 00:00:00" );
    var endTime = new Date ( now.getFullYear() + "-" + (now.getMonth()+1) + "-" + (now.getDate()) + " 01:00:00" );

//    	if( (startTime.getTime() <= now.getTime()) && (endTime.getTime() > now.getTime())) {
//              alert("금일 00:00 ~ 금일 01:00 점검시간입니다.");
//              return false;             
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


// 리뉴얼 VM 결제팝 취소했을때 팝업창 닫기 
function vmViewLayerCancel(){
    $('#spot').css('visibility','hidden');
    $('#renewal_pop').find('input').val('');
    $('#sponsor_pop').find('input[type=radio]').attr("checked", false);
    $('.vm_sum').find('#value').val('');
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
//                        PointOnchange2();
		   return false;
		 }
		 
		if(document.all.spot4.style.visibility=="visible") {
		   document.all.spot4.style.visibility="hidden";
		   return false;
		 }

	}


// sm가입 취소했을 때 팝업 닫기 
function smViewLayerCancle(){
  $("#spot4").css('visibility','hidden');
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
        
//결제취소클릭시 팝업창 닫기 
function cancelSubmit(num){
    let containerHtml =  $("#pop_container"+ num).find("#smsbox").html();

    if($("#pop_container").css("visibility","visible")){
        $("#pop_container").css("visibility","hidden");
        $("#pop_container input[type=text]").val('');
        $("#pop_container input[type=checkbox]").prop("checked",false);
    } 

    if($("#pop_container"+ num).css("visibility","visible")){
        $("#pop_container"+ num).css("visibility","hidden");
        $("#pop_container"+num).find(":text").val('');
        $("#pop_container"+num).find(":checkbox").prop("checked",false);
    
         //포인트 전송에서 취소한경우 입력값도 갱신 
         if($("#pop_container"+ num).has($("#totalvpoint3"))){
              $("#totalvpoint3").text("");
               SetTime = 180;
//            clearInterval(tid);	
             $("#smsbox_temp").show(function(){
              $("#smsbox_temp").attr( "id", "smsbox" );
              $("#smstext").html("");
               });
              $("#smsinnumber").val("");
              $("#smsNumber").val("");
              $("#pop_container"+ num).find("#smsbox").html(containerHtml);
          }
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
    


 <!-- V 광고센터 시작    -->
<?php
    // 광고 비용 관련 상수 정의
    define("IMAGE_AD_COST", 2); // 이미지 광고 1회 노출당 비용
    define("TEXT_AD_COST", 1); // 텍스트 광고 1회 노출당 비용
    
    $imageAdRow;
    $i=0;
    while( true ) {
        if( $i >= 15 ) {
            break;
        }
        $imageSQL = "select a.*, b.bizMoney from imageAdListTBL as a left join g5_member as b on a.mb_id = b.mb_id where state = 'Y' and ok = 'Y' and frDate <= '".date("Y-m-d")."' and toDate >= '".date("Y-m-d")."' and b.bizMoney >= ".IMAGE_AD_COST." order by rand() limit 1";
        $imageAdRow = mysql_fetch_array(mysql_query($imageSQL));
        $bizMoneyRow = mysql_fetch_array(mysql_query("select sum(bizMoney) as bizMoney from imageAdViewTBL where adNo = {$imageAdRow["no"]} and datetime >= '".date("Y-m-d")." 00:00:00' and datetime <= '".date("Y-m-d")." 23:59:59'"));
        
        if( $bizMoneyRow["bizMoney"] < $imageAdRow["budget"] ) {
            break;
        }
        $i++;
        unset($imageAdRow);
    }
    
    $textAdRow;
    $i = 0;
    while( true ) {
        if( $i >= 15 ) {
            break;
        }
        $textSQL = "select a.*, b.bizMoney from textAdListTBL as a left join g5_member as b on a.mb_id = b.mb_id where state = 'Y' and ok = 'Y' and frDate <= '".date("Y-m-d")."' and toDate >= '".date("Y-m-d")."' and b.bizMoney >= ".TEXT_AD_COST." order by rand() limit 1";
        $textAdRow = mysql_fetch_array(mysql_query($textSQL));
        $bizMoneyRow = mysql_fetch_array(mysql_query("select sum(bizMoney) as bizMoney from textAdViewTBL where adNo = {$textAdRow["no"]} and datetime >= '".date("Y-m-d")." 00:00:00' and datetime <= '".date("Y-m-d")." 23:59:59'"));
        if( $bizMoneyRow["bizMoney"] < $textAdRow["budget"] ) {
            break;
        }
        if( $textAdRow["budget"]=="" ) {
            break;
        }
        $i++;
        unset($textAdRow);
    }
    
    

    if($imageAdRow["image1"]!="") {
        mysql_query("update g5_member set bizMoney = bizMoney - ".IMAGE_AD_COST." where mb_id = '{$imageAdRow["mb_id"]}'");
        mysql_query("insert into imageAdViewTBL set adNo = {$imageAdRow["no"]}, bizMoney = ".IMAGE_AD_COST.", mb_id = '{$member["mb_id"]}'");
    }
    if($textAdRow["text"]!="") {
        mysql_query("update g5_member set bizMoney = bizMoney - ".TEXT_AD_COST." where mb_id = '{$textAdRow["mb_id"]}'");
        mysql_query("insert into textAdViewTBL set adNo = {$textAdRow["no"]}, bizMoney = ".TEXT_AD_COST.", mb_id = '{$member["mb_id"]}'");
    }
?>
<section id="v_center">
    <div class="vc_img_banner wow fadeInDown">
        <a <?php if( ! ($imageAdRow["url"] =="" && $imageAdRow["budget"]!="") ) {echo ' target="_blank" ';} ?> href="<?php if( $imageAdRow["url"] =="" && $imageAdRow["budget"]!="" ) {echo "#none";} else if($imageAdRow["image1"]=="") {echo '/myOffice/v_ad_center.php';} else {echo $imageAdRow["url"];}?>" class="vc_img_box">
            <img class="vcenter_img_pc" src="<?php if($imageAdRow["image1"]!="") {echo "/up/ad/image/".$imageAdRow["image1"];} else {echo "./images/img_vcenter_banner_pc.jpg";} ?>" alt="광고명"/>
            <img class="vcenter_img_mobile" src="<?php if($imageAdRow["image2"]!="") {echo "/up/ad/image/".$imageAdRow["image2"];} else {echo "./images/img_vcenter_banner_mobile.jpg";} ?>" alt="광고명"/>
        </a>
    </div>
    <div class="vc_txt_banner">
        <a <?php if( ! ($textAdRow["url"] =="" && $textAdRow["budget"]!="") ) {echo ' target="_blank" ';} ?> href="<?php if( $textAdRow["url"] =="" && $textAdRow["budget"]!="" ) {echo "#none";} else if($textAdRow["text"]=="") {echo '/myOffice/v_ad_center.php';} else {echo $textAdRow["url"];}?>" class="vc_txt_box"> 
            <span class="vc_txt_tit wow fadeInUp"><?php if($textAdRow["text"]!="") {echo $textAdRow["text"];} else {echo "효과적인 V광고 센터를 이용해보세요! 보다 효과적인 광고를 통해 귀사의 홍보를 극대화 시킬 수 있습니다.";} ?></span>
            <span class="vc_txt_more">더보기</span>
        </a>
    </div>
</section>
 <!-- V 광고센터 끝    -->
 <script>

//IE일 때 사진 비율 맞추기
$(function(){
    var userAgent, ieReg, ie;
    userAgent = window.navigator.userAgent;
    ieReg = /msie|Trident.*rv[ :]*11\./gi;
    ie = ieReg.test(userAgent);

    if(ie) {
    $(".img-container").each(function () {
        var $container = $(this),
            imgUrl = $container.find("img").prop("src");
        if (imgUrl) {
        $container.css("backgroundImage", 'url(' + imgUrl + ')').addClass("custom-object-fit");
        }
    });
    }

});

</script>

<!-- 스크린 시작  -->
<?php
include_once ('./rankArea.php');
?>
<!-- 스크린 끝  -->

<?php
$total = number_format($member['VMC'] + $member['VMP']);
$totalall = number_format( ($member['VMC'] + $member['VMP']) * 0.967);

$member['VMC'] = number_format($member['VMC']);
$member['V'] = number_format($member['V']);
$member['VMP'] = number_format($member['VMP']);

print_r($member);
?>

<!-- 본인정보 -->
	<section>

                    <!-- 보유 코인 포인트현황 및 포인트정산 -->
                    <div id="Point_Calculation">
                        <div class="coin_box clearfix">
                            <div class="vcoin_box">
                                <h2>보유 코인 현황  </h2>
                                <h3>V coin</h3>
                                <p><?=$member['V']?>개</p>
                            </div>

<?//	if(get_VMG_check($member['mb_id'])) {?>
                            <div class="vg_box">
                                <h2>보유 금고 현황</h2>
                                <h3>금고</h3>
                                <p><?=number_format($member['VMG'])?>원</p>
                            </div>

                            <div class="vmm_box">
                                <h2>VM몰 적립금</h2>
                                <h3>VMM</h3>
                                <p><?=number_format($member['VMM'])?>원</p>
                            </div>
<?//	}?>
                        </div>
                        

                        <div id="chargehave"><h1 style="font-weight: 800;font-size: 16.38px;float: left; padding-top:20px; padding-left:20px; background-color: white;">보유 포인트 현황</h1></div><br/>
                    <div class="charge">

                                    <div class="vmc">
                                            <div>VMC</div>
                                            <div>
                                                <strong id="vmcvar"><?=$member['VMC']?></strong>원
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
                                                    <strong id="vmpvar"><?=$member['VMP']?></strong>원
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
                            <div class="hot-container">
                                <p>
                                    <a href="simpleJoin/index.php" target="blank" class="btn btn-red">간편 회원가입</a>
                                </p>
                            </div>
                            <div class="hot-container" onclick="conSubmit2();">
                                <p>
                                    <a href="#" class="btn btn-red">비지니스 팩</a>
                                </p>
                            </div>
                            <div class="hot-container">
                                <p>
                                    <a href="myoffice_signUp.php" class="btn btn-red">비지니스 팩2</a>
                                </p>
                            </div>
<!--                            <div class="hot-container" onclick="conSubmit();">
                                <p>
                                    <a href="#" class="btn btn-red">포인트 충전</a>
                                </p>
                            </div>-->
<?php
    if( $member["accountType"] != "CU" ) {
        ?>
                            <div class="hot-container point_transfer" onclick="conSubmit3();">
                                <p>
                                    <a href="#" class="btn btn-red">포인트 전송</a>
                                </p>
                            </div>
        <?php
    }
?>


                            <div class="hot-container">
                            <p>
                                <a href="/myOffice/pay_cheak_skin.php" class="btn btn-red">정산 신청</a>
                            </p>
                            </div>
                            
                            <div class="hot-container">
                            <p>
                                <a href="/myOffice/box_1.php" class="btn btn-red">관리형1</a>
                            </p>
                            </div>
                            
                            <div class="hot-container">
                            <p>
                                <a href="/myOffice/box.devtmp.php" target="_blank" class="btn btn-red">관리형2</a>
                            </p>
                            </div>
<!--                            <div class="hot-container">
                            <p>
                                <a href="/myOffice/screen.php" id="screen" target="_blank" class="btn btn-red">스크린</a>
                            </p>
                            </div>-->
<!--                            <div class="hot-container">
                                <p>
                                    <a href="#none" class="btn btn-red" onclick="conSubmit4()">ARS 결제요청</a>
                                </p>
                            </div>-->
                            <div class="hot-container">
                                <p>
                                    <a href="./payCard.php" class="btn btn-red">카드 결제</a>
                                </p>
                            </div>
                            
                        </div>
                        <div id="pop_container" style="position: fixed; visibility: hidden;">
                            <div id="con_fix" class="popup_position">
                                <div class="cash_input">
                                    <label>충전금액</label>
                                    <input type="text" id="price" placeholder=" 금액 입력" onchange="onlyNumberComma(this)" onkeyup="onlyNumberComma(this)" onblur="call1(this)"><label>원</label>
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

                                <a href="#none"><div id="renewal_cancel" style="cursor: pointer;" onclick="cancelSubmit()">결제취소</div></a>
                            </div>
                        </div>

                    <form action="/adm/businessPack.php" method="POST" id="businessForm">
                    <input type="hidden" value="<?=$member['mb_id']?>" name="mb_id">
                        <div id="pop_container2" style="visibility: hidden;">
                            <div id="con_fix2" class="popup_position">
                                <div class="cash_input2">
                                    <label>비지니스팩 <img src="images/anfdma1.png" alt="물음표" style="width:13px; cursor: pointer;" id="bph"> <div id="BusinessP"> 비즈니스팩을 구매하시면 기존 VM가입비 대비 130,000원 저렴한 가격에 구매하실 수 있습니다.</div></label>
                                    <br/><br/>
                                    <hr style="display: block"><br>
                                    <label>결제할 포인트</label><br><br>
                                    VMC<input type='text' name='VMCpoint' id='VMCpoint' onkeyup="onlyNumberComma(this)" onchange="onlyNumberComma(this)" onblur='call2(this.id)' value="0"/><label>원</label><br/><br/>

                                    VMP<input type='text' name='VMPpoint' id='VMPpoint' onkeyup="onlyNumberComma(this)" onchange="onlyNumberComma(this)" onblur='call2(this.id)' value="0"/><label>원</label><br/><br/>

                                    <p>결제할 금액 : <b id="givemepoint">350,000</b>원</p>  
                                    <p>현재 입력금액 : <b id="totalvpoint">0</b>원</p>
                                </div>


                                <?php
//                                $busiRow = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'"));
                                echo "<input type='hidden' id='accountType' value='{$member['accountType']}'>";
                                if( $member['accountType'] == "CU" || $member['accountType'] == "SM" ) { ?>
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
                            <div id="con_fix3" class="popup_position">
                                <div class="cash_input3">
                                    <label>포인트 전송</label>
                                    <br/><br/>
                                    VMC<input type='text' name='VMCpoint3' id='VMCpoint3' onchange="onlyNumberComma(this)" onkeyup="onlyNumberComma(this)" onblur='call3()'  value="0"/><label>원</label><br/><br/>
            <!--                        VMR<input type='text' name='VMRpoint3' id='VMRpoint3' onkeydown="onlyNumber(this)" onblur='call3()'  value="0"/><label>원</label><br/><br/>-->
                                    VMP<input type='text' name='VMPpoint3' id='VMPpoint3' onchange="onlyNumberComma(this)" onkeyup="onlyNumberComma(this)" onblur='call3()'  value="0"/><label>원</label><br/><br/>

                                    <p>현재입력금액 : <b id="totalvpoint3">0</b>원</p>
                                    <br/>
                                    <p style="margin-bottom:5px;">포인트 전송받을 ID</p><input type='text' name='smsid' id='smsid' onkeydown="onlyNumber(this)" maxlength="8" style="width:92%;" value=""/><br/>
                                    <br>
                                    <hr style="display: block"><br>
                                <p>포인트 전송 정책</p>
                                포인트 전송 시 현금을 송금한 것과 동일한 효력이 발생하는 것에 동의하고 전송한 포인트는 환불 불가함에 동의합니다.
                                <br>동의 : <input type="checkbox" id="payCheckBox3" style="width: 20px">

                                </div>
                                <div id="smstext">
                                </div>
                                <div class="card1 cash" id="smsbox"><a href="javascript:void(0)" onclick="">문자인증</a></div>
                                <div class="card1 cash"><a href="javascript:void(0)" onclick="smsmsg3()">포인트 전송</a></div>

                                <a href="#none"><div id="renewal_cancel3" style="cursor: pointer;" onclick="cancelSubmit(3)">취소</div></a>
                            </div>
                        </div>
                        </form>
                        
                        <!--init_orderid-->
                        <form method="POST" action="/ars_kcp/vars/pp_cli_hub.php" id="arsForm">
                            <input type="hidden" id="req_tx" name="req_tx" value="pay" />
                            <input type="hidden" id="ordr_idxx" name="ordr_idxx" value="" />
                            <input type="hidden" id="pay_method" name="pay_method" value="VARS" />
                            <input type="hidden" id="good_name" name="good_name" value="VMP" />
                            <input type="hidden" id="buyr_name" name="buyr_name" value="<?php echo $member["mb_id"]; ?>" />
                            <!--<input type="hidden" id="expr_dt" name="expr_dt" value="" /> 결제 유효기간 생략 -->
                            <input type="hidden" id="comm_id" name="comm_id" value="SKT" />
                            <input type="hidden" id="cert_flg" name="cert_flg" value="Y" />
                            
                            
                            
                        <div id="pop_container4" class="popup_position">
                            <div class="con_fix4">
                                <div class="cash_input4">
                                    <label for="pay_request">결제요청 금액</label>
                                    <input type="text" id="pay_request" placeholder="금액 입력" name="phon_mny" onkeyup="onlyNumberComma(this)" onchange="onlyNumberComma(this)" onblur="call4(this)">
                                    <label>원</label>
                                    <p>※ 충전하신 금액은 VMP로 충전됩니다.</p>
                                    
<!--                                    <label for="pay_name">결제자 이름</label>
                                        <input type="text" id="pay_name">-->
                                    
                                    
<!--                                    <label for="pay_phone_company">통신사</label>
                                    <input type="radio" name="pay_phone_company" value="skt" id="pay_skt" ><label for="pay_skt">SKT</label> 
                                    <input type="radio" name="pay_phone_company" value="kt" id="pay_kt" ><label for="pay_kt">KT</label> 
                                    <input type="radio" name="pay_phone_company" value="lgu" id="pay_lgu"><label for="pay_lgu">LGU</label> -->
                                    
                                    
                                    <label for="pay_phone">결제자 휴대전화번호</label>
                                    <input type="text" id="pay_phone" name="phon_no" onkeydown="onlyNumber(this)" maxlength="11">
                                    <strong>결제정책</strong>
                                    <em>충전하신 금액은 환불이 불가합니다.</em>
                                    <label for="pay_agree">동의 : </label>
                                    <input type="checkbox" id="pay_agree">                                    
                                    <strong>결제수단</strong>
                                    <div class="card1 btn_pay_ars">
                                        <a href="#none" onclick="requestArs()">ARS 결제요청</a>
                                    </div>
                                    <a href="#none">
                                        <div class="ars_cancel" onclick="cancelSubmit(4)">결제취소</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        </form>


<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
<script>

            var SetTime = 180;

            function msg_time() {	// 1초씩 카운트
                mm = Math.floor(SetTime / 60) + "분 " + (SetTime % 60) + "초";// 남은 시간 계산

                var msg = "남은 시간 <font color='red'>" + mm + "</font>";

                document.all.ViewTimer.innerHTML = msg;// div 영역에 보여줌 

                SetTime--;	// 1초씩 감소

                if (SetTime < 0) {	// 시간이 종료 되었으면..

                    clearInterval(tid); // 타이머 해제
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
                   $("#smstext").append('<input type="text" name="smsinnumber" id="smsinnumber" autocomplete="off" value="" />');
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
<form action="update.php" method="post" name="myForm" id="myForm">
    <div class="user_Modified">
	<div class="first_element">
	<!--프로필 이미지 -->
	<!-- <div>
                <img src="images/profile.png" alt="">  
             </div> -->
            <div class="second_element">
		<ul>
                    <li><input class="user_name" id="name" placeholder="name" name="name" maxlength="4" type="text" value="<?=$member['mb_name']?>" readonly>
                        <span id="accountTypeSpan"><?=$member['accountType']?></span>
                        <span><?php echo rankCheck($member["mb_id"]); ?></span> | <span id="mb_info_id"><?=$member['mb_id']?></span>
                        <button type="button" class="pw_change" id="pw_btn" onclick="javascript:showmap();">비밀번호 변경</button> 
                        <span class="modifiedBt"> <img src="images/modified.png" alt="" onclick='modi();'>
			<ul class="modifiedBtUl">
                            <li class="confirmBt" onclick='check_submit();'>
                                <span><i class="fa fa-check" id="check-fa" aria-hidden="true"></i></span>
                            </li>
										<!--
                                        -->
                            <li class="closeBt" onclick='modiclose();'><span><i class="fa fa-times" id="close-fa" aria-hidden="true"></i></span></li>
			</ul>
			</span>
                    </li>
                    <li>
			<ul class="memberInfoUl">
                            <li class="hp_box">
                                <strong> 핸드폰 </strong>
				<p>
                                    <input class="infoInputM" id="hp" type="text" placeholder="휴대폰번호" name="hp" autocomplete="off" value="<?=$member['mb_hp']?>" readonly>
				</p>
                                <a href="#none" class="auth_number_btn">번호 변경하기</a>
                            </li>
                            <li><img src="images/dot.png" alt=""></li>
                            <li>
                                <strong> 생년월일 </strong>
				<p>
                                    <input class="infoInputM" id="birth" type="text" placeholder="생년월일" name="birth" value="<?=$member['birth']?>" readonly>
				</p>
                            </li>
                            <li><img src="images/dot.png" alt=""></li>
                            <li><strong>이메일</strong>
				<p>
                                    <input class="infoInputM" id="emailform" type="text" placeholder="email" name="email" value="<?=$member['mb_email']?>" readonly>
				</p>
                            </li>
                            <li><img src="images/dot.png" alt=""></li>
                            <li><strong>리뉴얼</strong>
				<p>
                                    <input class="infoInputM" id="renewel" type="text" placeholder="리뉴얼" name="renewel" value="<?php echo $TIMESTAMP; ?>" readonly>
				</p>
                            </li>
                        </ul>
                    </li>
                    <li id="post_element"><strong>주소</strong>
                                <?php if ($config['cf_use_addr']) { ?>
                                 <label for="reg_mb_zip" class="sound_only">우편번호<?php echo $config['cf_req_addr']?'<strong class="sound_only"> 필수</strong>':''; ?></label>
                                    <input type="text" name="mb_zip" value="<?php echo $member['mb_zip1'].$member['mb_zip2']; ?>" id="reg_mb_zip" <?php echo $config['cf_req_addr']?"required":""; ?> class="frm_input<?php echo $config['cf_req_addr']?"required":""; ?>" size="5" maxlength="6" placeholder="우편번호">
                                        <button type="button" class="adressSearchBt" id="postbtn" onclick="win_zip('myForm', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소 검색</button>
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
        <!--2차 번호인증-->
        <div class="auth_number">
            <div class="auth_number_content">
                <input type="hidden" id="hp_smsnumber" name="hp_smsnumber" value=""/>
                <input type="hidden" id="confirmedNum" name="confirmedNum" value=""/>
                <p class="auth_info"><i class="auth_icon"></i>보다 안전한 이용을 위해 두 번의 번호인증을 거친 후 휴대폰 번호를 변경하실 수 있습니다.</p>
                <div class="auth_process">
                    <ul>
                        <li class="on">
                            <span>1</span>
                            <em>1차 인증</em>
                        </li>
                        <li>
                            <span>2</span>
                            <em>2차 인증</em>
                        </li>  
                        <li>
                            <span>3</span>
                            <em>변경완료</em>
                        </li> 
                    </ul>
                </div>
                <div class="auth_step_1">
                    <label for="origin_mb_hp">기존 휴대폰번호</label>
                    <input class="" id="origin_mb_hp" type="text" placeholder="휴대폰번호" name="origin_mb_hp" autocomplete="off" value="<?=$member['mb_hp']?>" readonly>
                    <a href="#none" class="hp_btn hp_confirm_btn auth_btn step_1_btn" onclick="authNumber(this)">인증번호발송</a>
                    <p class="hp_info">인증문자 발송 버튼을 눌러주세요.</br>4자리 인증번호를 보내드리겠습니다.</p>
                    <div class="hp_confirm_box"></div>
                </div>
                <div class="auth_step_2">
                    <label for="mb_hp">변경하실 휴대폰번호</label>
                    <input class="" id="mb_hp" type="text" placeholder="휴대폰번호 입력" name="mb_hp" autocomplete="off" value="">
                    <a href="#none" class="auth_btn step_2_btn" onclick="authNumber(this)">인증번호발송</a>
                    <a href="#none" class="hp_btn hp_reconf_btn auth_btn" onclick='reconfirmHp()'>번호 변경하기</a>
                    <p class="hp_info">변경하실 휴대폰번호를 입력하신 후</br>인증문자 발송 버튼을 눌러주세요.</br>4자리 인증번호를 보내드리겠습니다.</p>
                    <div class="hp_confirm_box_2"></div>
                </div>
                <div class="auth_step_3">
                    <a href="#none" class="auth_btn btn_auth_ok">변경저장</a>
                    <a href="#none" class="auth_btn btn_cancel">취소</a>
                    
                </div>
            </div>
        </div>
					<!--second_element-->
					<div class="clr"></div>
				</div>
<?php
    if( $member['accountType'] == "CU" ) {
        $SmSignupText = "SM 가입";
    } else if( $member['accountType'] == "SM" ) { 
        $SmSignupText = "SM 리뉴얼";
    }
    $row = mysql_fetch_array(mysql_query("select * from genealogy where mb_id = '{$member["mb_id"]}'"));
?>			
				<div class="table_wrap">
					<table>
						<tr> <!-- 추천인 후원인 잠시 주석-->
							<th>추천인</th>
							<td><strong>이름</strong> <input style="display:none;" class="infoInputSS"
								id="recommenderName" type="text" placeholder="recommenderName"
								name="recommenderName" value="<?=$row['recommenderName']?>"
								readonly></td>
							<td><strong>회원번호</strong> <input style="display:none;" class="infoInputSM"
								id="recommenderID" type="text" placeholder="recommenderID"
								name="recommenderID" value="<?=$row['recommenderID']?>" readonly>
							</td>
                                                        

                                                        <td id="SmSignup" onclick="javascript:ViewLayer2();" >
                                                            <a href="javascript:;" class="SmSignupbtn underBtn-list" id="smBtn"><?=$SmSignupText?></a>
                                                        </td>
						</tr>
						<tr>
							<th>후원인</th>
							<td><strong>이름</strong> <input style="display:none;" class="infoInputSS"
								id="sponsorName" type="text" placeholder="sponsorName"
								name="sponsorName" value="<?=$row['sponsorName']?>" readonly></td>
							<td><strong>회원번호</strong> <input style="display:none;" class="infoInputSM"
								id="sponsorID" type="text" placeholder="sponsorID"
								name="sponsorID" value="<?=$row['sponsorID']?>" readonly></td>
							<td id="RenewalButton" onclick="javascript:ViewLayer();">
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
                                                    <td class="clearfix">
                                                        <a href="rankuplist_loading.php" class="underBtn-list underBtn3">
                                                            <i class="fa fa-users" aria-hidden="true"></i>승급자 리스트
                                                        </a>
                                                    </td>
                                                    <td class="clearfix"></td>
						</tr>
					</table>
					<!-- 4가지로 분류 -->
				</div>
			</div>
		
                </form>
                    <!-- 나눔 배너 시작 -->
    <div id="givePoint" class="clearfix">
            <div class="give_img">
                <img src="images/vmp-megaphone.png">
            </div>
            <h3>작은 나눔의 첫걸음</h3>
            <p class="give_1">지금까지 누적된 나눔은</p>
            <p class="give_num"><?=number_format($ANGELVMP)?></p>
            <p class="give_2">원 입니다.</p>
        </div>
        <!-- 나눔 배너 끝 -->  
	</section>
        
        
       
        
        
       <!--포인트 정산 숨김-->
        <div id="Pointname" style="display:none;"> 
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
var LIMIT = "<?=$REMAIN?>";
$(document).ready(function() {
	$('.closeBt2').click(function() {
		location.reload();
	});
         
        //갱신기간이 종료되었으면 모달창 닫기
//        if(LIMIT < 0 ){ 갱신 경고창 잠시 주석
//            $("#modal").hide();
//        } else if(LIMIT!="END"&&LIMIT){
//		$("#modal").show();
//	}
        
        
});       
</script>
<script>
$("#anfdma").click(function(){
    $("#Pointnametext").toggle();
});


//휴대폰 번호 변경하기 
$('.auth_number_btn').on('click',function(){
    $('.auth_number').addClass('active');
    $('.user_Modified input').removeClass('on').attr('readonly',false);
    $('#origin_mb_hp').attr('readonly',true);

});




// 숫자와 하이픈만 사용가능하게 하는 함수입니다.
function autoHypenPhone(str){
    str = str.replace(/[^0-9]/g, '');
    var tmp = '';
    if( str.length > 11 ){
        alert('휴대폰 번호를 정확히 입력해 주세요.');
        tmp += str.substr(0,11);
        return tmp;
    }
    if( str.length < 4 ){
      return str;
    } else if ( str.length < 7 ){
      tmp += str.substr(0, 3);
      tmp += '-';
      tmp += str.substr(3);
      return tmp;
    } else if ( str.length < 11 ){
      tmp += str.substr(0, 3);
      tmp += '-';
      tmp += str.substr(3, 3);
      tmp += '-';
      tmp += str.substr(6);
      return tmp;
    } else {
      tmp += str.substr(0, 3);
      tmp += '-';
      tmp += str.substr(3, 4);
      tmp += '-';
      tmp += str.substr(7);
      return tmp;
    } 
    return str;
}        

    

// 회원정보수정 휴대폰번호 문자인증 활성화 
$('#mb_hp').keyup(function(ev){
    var keyValue = this.value.trim();
    this.value = autoHypenPhone(keyValue);
    
//    numberConfirm(ev.target);
});     



// 회원정보 휴대폰 번호인증 
//function numberConfirm(obj){
//
//    // 수정이 불가능할 때 문자인증버튼 숨기기
//    if($(obj).attr('readonly') == 'readonly'){
//        return;
//    }
//    
//    //문자인증버튼 보이고 활성화
//    if( $('.second_element').hasClass('active') ){
//        return
//        
//    } else {
//        $('.second_element').addClass('active');
//        $(".hp_info").addClass('on');
//        if( $('.hp_confirm_btn').css('display') == 'none' ){ //문자인증버튼이 보이지않으면
//            $('.hp_confirm_btn').css('display','block');
//        } 
//    }    
//}


var SetTime = 180;
var tid = null;

function msg_time() {	// 1초씩 카운트
    mm = Math.floor(SetTime / 60) + "분 " + (SetTime % 60) + "초";// 남은 시간 계산

    var msg = "남은 시간 <font color='#f75e61'>" + mm + "</font>";

    document.all.ViewTimer.innerHTML = msg;// div 영역에 보여줌 

    SetTime--;	// 1초씩 감소

    if (SetTime < 0) {	// 시간이 종료 되었으면..

        clearInterval(tid); // 타이머 해제
        alert("입력 시간을 초과하였습니다.");
        window.location.reload();
    }
}

// 휴대전화번호 문자 재인증하기
function reconfirmHp(){
    $(".auth_process").find('li').eq(2).removeClass('on');

    $('.hp_confirm_box_2').html(''); // 인증문자 박스내용 지우기 
    $('.hp_reconf_btn').css('display','none'); // 번호변경하기 버튼 감추기
    $('.hp_info').addClass('on'); // 인증문자 설명 보이기
    regConfirmMsg('origin');

    $('#mb_hp').prop('readonly',false);

    //문자인증 초기화
    $('.step_2_btn').css('display','inline-block');
    $('#mb_hp').val('');
    
    SetTime = 180;
    
    if( tid !==  null ){
        clearInterval(tid);
    }   
}


 // 문자인증번호 값검증 메세지 띄우기
 function regConfirmMsg(str, obj){
    if( str == "success" ){
        if( $(obj).hasClass('step_1_btn') ){
            $('.auth_step_1 .hp_info').css('display','none');
            
        } else if ( $(obj).hasClass('step_2_btn') ){
            $('.hp_info').text('문자인증 완료');
            $('.hp_info').css('color','#4285F4');
        }
        
        
    } else if ( str == "fail" ){
        $('.hp_info').text('정확하게 입력하여 주세요.(4자리)');
        $('.hp_info').css('color','#e8104c');
        
    } else if ( str == 'origin' ){
        $('.hp_info').css('color','#354d90');
        $('.hp_info').html('<p>휴대전화 번호 입력후 인증문자 발송 버튼을 누르세요.</br>4자리 인증번호를 보내드리겠습니다.</p>');
    } 
 }
 



// 요기
// 문자인증번호 눌렀을 때 실행하기
function authNumber(obj){

    if( $(obj).hasClass('step_1_btn') ){
        sendNumber(obj);

    } else if ( $(obj).hasClass('step_2_btn') ){

        if( ! (parseInt($('#mb_hp').val().length) == 13) ){
            alert('휴대폰 번호를 정확히 입력해 주세요.');
            $('#mb_hp').focus();
            return
        }
        
        sendNumber(obj);

    }
        

    function sendNumber(obj){
        // 인증번호 생성 후 서버에 인증번호 전송 요청
        var result = Math.floor(Math.random() * 9999) + 1;
        var length = 4;
        result=result+""
        var str=""
        var hp;
        var confirmBox;
        
        if( $(obj).hasClass('step_1_btn') ){
            hp = $('#origin_mb_hp').val()
            confirmBox = $('.hp_confirm_box');
            
        } else if ( $(obj).hasClass('step_2_btn') ){
            hp = $('#mb_hp').val()
            confirmBox = $('.hp_confirm_box_2');

        }
        
        

        for(var i=0;i<length-result.length;i++){
          str=str+"0";
        }
        str=str+result;
        $("#hp_smsnumber").val(str);
        
        var data = "smsNumber=" + str + "&mb_id=" + $("#mb_info_id").text() + "&mb_hp=" + hp;

        $.ajax({
            url:'smsAuthentication.php',
            type:'POST',
            data: data
        });

        $(obj).hide(function(){
            if( $(obj).hasClass('step_2_btn') ){
                $('.hp_reconf_btn').css('display','inline-block');
                $('#mb_hp').prop('readonly',true);
            } 
            $(confirmBox).addClass('active');
            $(".hp_info").text( "휴대폰으로 전송된 인증번호를 입력하여 주세요" );
            $(confirmBox).append('<p id="ViewTimer"></p>');
            $(confirmBox).append('인증번호 입력');
            $(confirmBox).append('<input type="text" name="hpinnumber" id="hpinnumber" autocomplete="off" minlength="4" maxlength="4" value=""/>');
            $(confirmBox).append('<p class="hpinnumber_text" style="display:none">인증번호가 틀립니다.(4자리)</br>다시 입력해주세요</p>')
            checkHpinnumber(obj, confirmBox);
            
        });
        tid=setInterval('msg_time()',1000);
    }
    
    



}

//$(".hp_confirm_btn").click(function(){      
//    
//    $('#hp').val(autoHypenPhone( $('#hp').val() ));
//    
//    if( ! (parseInt($('#hp').val().length) == 13) ){
//        alert('휴대폰 번호를 정확히 입력해 주세요.');
//        return
//    }
//
//
//    // 인증번호 생성 후 서버에 인증번호 전송 요청
//    var result = Math.floor(Math.random() * 9999) + 1;
//    var length = 4;
//    result=result+""
//    var str=""
//
//    for(var i=0;i<length-result.length;i++){
//      str=str+"0";
//    }
//    str=str+result;
//    $("#hp_smsnumber").val(str);
//
//    var data = "smsNumber=" + str + "&mb_id=" + $("#mb_info_id").text() + "&mb_hp=" + $("#hp").val();
//
//    $.ajax({
//        url:'smsAuthentication.php',
//        type:'POST',
//        data: data
//    });
//    
//    $(".hp_confirm_btn").hide(function(){
//        $(".hp_confirm_box").addClass('active');
////        $('#hp').prop('readonly',true);
////        $('.hp_reconf_btn').css('display','block');
//        $(".hp_info").text( "휴대폰으로 전송된 인증번호를 입력하여 주세요" );
//        $(".hp_confirm_box").append('<p id="ViewTimer"></p>');
//        $(".hp_confirm_box").append('인증번호 입력');
//        $(".hp_confirm_box").append('<input type="text" name="hpinnumber" id="hpinnumber" autocomplete="off" minlength="4" maxlength="4" value=""/>');
//        $(".hp_confirm_box").append('<p class="hpinnumber_text" style="display:none">인증번호가 틀립니다.(4자리)</br>다시 입력해주세요</p>')
//        checkHpinnumber();
//    });
//    tid=setInterval('msg_time()',1000);
// });



// 문자인증번호 값검증
function checkHpinnumber(obj, type){
    $('#hpinnumber').keyup(function(ev){
        var keyValue = this.value.trim();
        var onlyNumber = keyValue.replace(/[^0-9]/g, '');
        var tmp = '';
        var confirmBox = type;
        
        if( onlyNumber.length < 4 ){
           this.value = onlyNumber;
           
           if( $('#hpinnumber').val() !== $("#hp_smsnumber").val() ){
                $(".hpinnumber_text").css('display','block');
                return;
                
            } else {
                $(".hpinnumber_text").css('display','none');
                
            }
            
        } else if( onlyNumber.length == 4 ){
            this.value = onlyNumber.substr(0,4);
            
            if( $('#hpinnumber').val() !== $("#hp_smsnumber").val() ){
                $(".hpinnumber_text").css('display','block');
                return;
                
            } else {

                $(".auth_process").find('li').eq(1).addClass('on');
                $(".hpinnumber_text").css('display','none');
                regConfirmMsg('success', obj);
                $(".auth_step_2").css('display','block');
                $('#confirmedNum').val( $('#mb_hp').val() );
                $(confirmBox).html('');
                SetTime = 180;
                clearInterval(tid);
                
                if( $(".auth_process").find('li').eq(1).hasClass('on') && $('.auth_step_2 .hp_info').text() == '문자인증 완료' ){ //auth_process 단계 버튼 활성화하기
                    $(".auth_process").find('li').eq(2).addClass('on');
                }
                
            }
        }
    });

}

//여기요
//문자인증 값검증
$('.auth_step_3 > a').on('click', function(){

    
    if( $(this).hasClass('btn_auth_ok') ){ // 변경 저장을 눌렀을 때

        if( $('#confirmedNum').val() == '' ){ 
            alert('번호인증을 거친 후 휴대폰 번호를 변경하실 수 있습니다.');
            return;
            
        } else if( $('#confirmedNum').val() !== $('#mb_hp').val() ){
            alert('번호인증을 해주세요');
            return
            
        } else {
            $('#hp').val( $('#confirmedNum').val() );
            $('.auth_number').removeClass('active');
            $('#hp').css('background-color','#4285F4');
            $('#hp').css('color','#fff');

        }
        
        
        
    } else if ( $(this).hasClass('btn_cancel') ){ // 취소를 눌렀을 때
        $('.auth_number').removeClass('active');
        $('.step_1_btn').css('display','inline-block');
        $('.hp_confirm_box').removeClass('active');
        $('.hp_confirm_box').html('');
        $('.auth_step_2').css('display','none');
        $('#mb_hp').val('');
        $('#mb_hp').prop('readonly',false);
        $('.hp_confirm_box_2').html(''); // 인증문자 박스내용 지우기 
        $('.hp_reconf_btn').css('display','none'); // 번호변경하기 버튼 감추기
        $('.hp_info').addClass('on'); // 인증문자 설명 보이기
        $('.step_2_btn').css('display','inline-block'); // 인증문자 전송버튼 보이기
        $('.user_Modified input').addClass('on').attr('readonly',false);
        $(".auth_process").find('li').eq(0).addClass('on').siblings('li').removeClass('on');
        regConfirmMsg('origin');

        SetTime = 180;

        if( tid !==  null ){
            clearInterval(tid);
        }   
    }
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


        <input type="hidden" id="calendarType" value="2">
        <input type="hidden" value="test" id="popUpVal">

        
	<!-- 달력 -->
        
	<div class="calendar">
		<div class="calendar_tab">
                    <ul class="c_tab clearfix">
                        <li class="on" onclick="calendarType(2);">입금 내역</li>
                        <li onclick="calendarType(3);">출금 내역</li>
                        <li onclick="calendarType(1);">입/출금 내역</li>
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
                <input type="hidden" name="VMG" value=""> 
                <input type="hidden" name="pay" value=""> 
                <input type="hidden" name="totalpoint" value="">
                <input type="hidden" name="H_reg_mb_sponsor" id="H_reg_mb_sponsor" value="">
                <input type="hidden" name="H_reg_mb_sponsorID" id="H_reg_mb_sponsorID" value="">
                <input type="hidden" name="H_radiobtn" id="H_radiobtn" value="">
	</form>

<div id="spot" style="visibility: hidden;">
					
<?php
//    $renewalTitle = mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'");
//    $renewalTitleRow = mysql_fetch_array($renewalTitle);
    
    if( $member['accountType'] == 'VM' || ($member['accountType'] == "CU" && $member['renewal'] != null) ) {
        echo '<div class="pop_tit" id="pop_tit_com">리뉴얼 결제</div>';
    } else if( $member['accountType'] == 'CU' ) {
        echo '<div class="pop_tit" id="pop_tit_com">VM 가입</div>';
    }
?>
                    
    <div id="renewal_pop">
        <div id="sponsor_pop2">
            <p>후원인 정보입력</p>
            <input class="team_infor" type="text" name="reg_mb_sponsor" id="reg_mb_sponsor" placeholder="후원인 이름" minlength="3"/> 
            <input onkeydown="onlyNumber(this)" class="team_infor" type="text" name="reg_mb_sponsorID" id="reg_mb_sponsorID" placeholder="후원인 회원번호" minlength="3" maxlength="8"/>
	</div>
	<div id="sponsor_pop">
            <p>후원인 팀배치</p>
            <input type="radio" name="radiobtn" value="1" onclick="teamCheckFunc()"/> 1팀 
            <input type="radio" name="radiobtn" value="2" class="team_sel" onclick="teamCheckFunc()"/> 2팀
	</div>
	<div class="pop_vmc pop">
            <div>
                <p>VMC</p>
                <span><?=$member['VMC']?></span>
            </div>
            <input type="number" name="pop_vmc" placeholder="VMC 차감" onkeyup="PointOnchange(this);">
	</div>
        <div class="pop_vmr pop" style="display:none;">
            <div>
		<p>VMR</p>
		<span><?=$member['VMR']?></span>
            </div>
            <input type="number" name="pop_vmr" placeholder="VMR 차감" onkeyup="PointOnchange(this);">
	</div>                                                
	<div class="pop_vmp pop">
            <div>
		<p>VMP</p>
		<span><?=$member['VMP']?></span>
            </div>
            <input type="number" name="pop_vmp" placeholder="VMP 차감" onkeyup="PointOnchange(this);">
	</div>
        <div class="pop_vmg pop" >
            <div>
                <p>금고</p>
                <span><?=number_format($member['VMG'])?></span>
            </div>
            <input type="number" name="pop_vmg" placeholder="금고 차감" onkeyup="PointOnchange(this);">
	</div>
	<div class="vm_sum">
            <div>
                <p>합 계 :</p>
                <span id="value">0</span>
            </div>
        </div>
	<div class="summary">
            <?php
                if ($member['accountType'] == "CU" && $member['renewal'] != null && $TIMESTAMP <= $now_temp) {
                    echo "<div><p>결제할 금액 : </p><span id=\"sum_count\">{$money}</span></div>";
                } else {
                       echo "<div><p>결제할 금액 : </p><span id=\"sum_count\">2080000</span></div>";
                    }                     
            ?>                     
        </div>
        <div id="renewal_submit" style="cursor: pointer;" onclick="submitCheck();">결제하기</div>
            <a href="#none"><div id="renewal_cancel" style="cursor: pointer;" onclick="vmViewLayerCancel()">취소</div></a>
        </div>
    </div>

<!--sm가입-->

    <div id="spot4" style="visibility: hidden;">		
        <?php
//            $renewalTitle = mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'");
//            $renewalTitleRow = mysql_fetch_array($renewalTitle);

            if( $member['accountType'] == 'CU' ) {
                $renewalTitleText = "SM 가입";
            } else if( $member['accountType'] == 'SM' ) { 
                $renewalTitleText = "SM 리뉴얼";
            }
        ?>
        <form method="POST" action="smJoin.php" id="smJoinForm">
            <div class="pop_tit2"><?=$renewalTitleText?></div>
            <div id="sm_pop">
                <div class="pop_vmp2 pop2">
                    <div>
                        <p>보유 VMP</p>
                        <span id="myVMP"><?=$member['VMP']?></span>
                    </div>
                </div>
                <div class="summary2">
                    <div><p>결제할 금액 : </p><span id="sum_count2">20,000원</span></div>    
                </div>
                <div id="sm_submit" onclick="submitCheck2();">결제하기</div>
                <a href="#none"><div id="sm_cancel" onclick="smViewLayerCancle()">취소</div></a>
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

            
                    if( $("#accountTypeSpan").text() == 'CU' ) {
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
                      } else if(Number(popsummary.text()) == 0) {
                          
                        if( $("#pop_tit_com").text() == "VM 가입" ) {
                          var data = "mb_id=" + $('#mb_info_id').text() + "&"
                            + "spon_id=" + $('#reg_mb_sponsorID').val();
                            var returnTemp = true;
                             $.ajax({
                                 url:'./recommenderCheck.php',
                                 type:'POST',
                                 data: encodeURI(data),
                                 async: false,
                                 success:function(result){
                                     if( result == "false" ) {
                                         alert("본인의 추천인 산하로만 배치될 수 있습니다.");
                                         returnTemp = false;
                                     }
                                }
                             });
                             if(!returnTemp)
                                 return false;
                          }
                          
                          
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
            
             // 포인트를 입력하지 않은상태에선 return
             if(val == undefined){
                 return
             }
            
                var popvmc = $('input[name=pop_vmc]').val();
                var popvmr = $('input[name=pop_vmr]').val();
                var popvmp = $('input[name=pop_vmp]').val();
                var popvmg = $('input[name=pop_vmg]').val();
                
                var popsum = $('.vm_sum').find('span');
                var popsummary = $('.summary').find('span');
                               

                var selectInput = $(val).attr('name');
                var selectInputVal = $(val).val().replace(/,/g,'');
                var parInputVal = Number($('.'+selectInput).find('span').text().replace(/,/g,''));
                if(selectInputVal > parInputVal){
                    alert('잔여포인트 보다 차감액이 큽니다!');
                   if(parInputVal > 0){
                        $(val).val(parInputVal);
                        PointOnchange(val);
                    }else{
                        $(val).val('');
                        PointOnchange();
                    }
                    return;
                }
                
                
               popsum.text(Number(popvmc)+Number(popvmr)+Number(popvmp)+Number(popvmg));
               
               if(popsum.text()>0){
                popsummary.text(2080000-Number(popsum.text()));
               }
               
               $('input[name=pay]').val(2080000-Number(popsum.text()));
               $('input[name=VMC]').val(popvmc);
               $('input[name=VMR]').val(popvmr);
               $('input[name=VMP]').val(popvmp);
               $('input[name=VMG]').val(popvmg);
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

<!--        <div id="modal"> 갱신 경고창 잠시 주석
            <div class="modal_content clearfix">
                <div class="img_box">
                    <img src="images/vmp-alert-time.png" />
                </div>
                <div class="text_box">
                    <p><?=$member['mb_name']?>님의 VM갱신기간이</p>
                    <p><span><?=$REMAIN?></span>일 남았습니다.</p>
                    <a href="#" onClick="$('#modal').fadeOut();">확인</a>
                </div>
            </div>
        </div>-->
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
    $('.loading_msg').addClass('on');

    
    
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
   calendar += '<div class="loading_msg on">';
   calendar += '<img class="loading_logo_img" src="images/mobile_logo_img2.png" alt="VMP"/>'
   calendar += '<p class="vmp_slogan">" 사랑합니다. 존경합니다."</p>'
   calendar += '<p>내역을 불러오고 있습니다.</br>잠시만 기다려주세요.</p>'
   calendar += '<img class="loading_img" src="images/loading_rolling2.gif" alt="로딩메세지"/>'
   calendar += '</div> '
   
   kCalendar2.innerHTML = calendar;
   
   
   var data = "year=" + currentYear + "&month=" + currentMonth + "&type=" + $("#calendarType").val();
   
    $.ajax({
           url:'index2.php',
           type:'POST',
           data: data,
           success:function(result){
               $('.loading_msg').removeClass('on');
               var json = JSON.parse(result);  
            
               if( json.length == 0 ) {
//                   console.log("결과가 없습니다.");
               }

                for( var i=0 ; i<json.length ; i++ ) {
                        var cont = ""
                        if( json[i][6] != null ) {
                                cont = '<font style="color:red; float:left; padding-left:7px;">PASS</font>';
                        }
//					$('#'+json[i][5]).html(cont + json[i][5]+'<div class=\"box_wrap\"><ul class=\"box\"><li class=\"box1\">VMC</li><li class="">￦'+json[i][0]+'</li></ul><ul class=\"box\"><li class=\"box2\">VMR</li><li>￦'+json[i][1]+'</li></ul><ul class=\"box\"><li class=\"box3\">VMP</li><li>￦'+json[i][2]+'</li></ul><ul class=\"box\"><li class=\"box2\">금고</li><li>￦'+json[i][7]+'</li></ul></div>');
                        $('#'+json[i][5]).html(cont + json[i][5]+'<div class=\"box_wrap\"><ul class=\"box\"><li class=\"box1\">VMC</li><li class="">￦'+json[i][0]+'</li></ul><ul class=\"box\"><li class=\"box3\">VMP</li><li>￦'+json[i][2]+'</li></ul><ul class=\"box\"><li class=\"box4\">VMM</li><li>￦'+json[i][8]+'</li></ul><ul class=\"box\"><li class=\"box5\">Biz</li><li>￦'+json[i][9]+'</li></ul><ul class=\"box\"><li class=\"box2\">금고</li><li>￦'+json[i][7]+'</li></ul></div>');
                        
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
         async: false,
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
        //콤마 제거하기
        $("#VMPpoint").val($("#VMPpoint").val().replace(/[^0-9]/g,""));
        $("#VMCpoint").val($("#VMCpoint").val().replace(/[^0-9]/g,""));
    
    
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
function call1(){
    $(".cash_input > #price").val($("#price").val().replace(/[^0-9]/g,""));
}
function call3(){
        $("#VMCpoint3").val($("#VMCpoint3").val().replace(/[^0-9]/g,""));
        $("#VMPpoint3").val($("#VMPpoint3").val().replace(/[^0-9]/g,""));
    
        if( $("#VMCpoint3").val() == "" ) {
            $("#VMCpoint3").val('0');
        }
        if( $("#VMPpoint3").val() == "" ) {
            $("#VMPpoint3").val('0');
        }
    
    
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


function call4(obj){
   $(obj).val($(obj).val().replace(/[^0-9]/g,"")); 
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
//ARS 결제요청 팝업창 열기
function conSubmit4(){
    if($('#pop_container4').css('visibility','hidden')){
        $('#pop_container4').css('visibility','visible');
    } else if ($('#pop_container4').css('visibility','visible')){
        $('#pop_container4').css('visibility','hidden');
    }
}

// ARS 결제요청 동의구하기 컨펌창 열기
function requestArs(){
 //금액 체크 
    if($('#pay_request').val() <= 0 || $('#pay_request').val() == "") {
        alert("금액을 입력해 주세요.");
    } else if($('#pay_agree').is(':checked') == false) {
        alert('동의에 체크하셔야 결제가 진행됩니다.');        
    } else {
        let agreePay = prompt('결제하신 금액은 환불이 불가합니다.\n동의하시면 아래 동의란에 "동의합니다"라고 쓰십시오.');
        
        if( ! checkAgree(agreePay) )
            return;
    }
 

    function checkAgree(agreePay){
        if(agreePay == '동의합니다') {
            alert('동의하셨습니다.');
            return true;
        } else if (agreePay == null) {
            return false;
        } else if (agreePay == '' || agreePay !== '동의합니다') {
            agreePay = prompt('결제하신 금액은 환불이 불가합니다.\n동의하시면 아래 동의란에 "동의합니다"라고 쓰십시오.');
            checkAgree(agreePay);           
        }
        return false;
    }
    
    $("#ordr_idxx").val(   init_orderid()  ); // 주문번호 세팅
    $("#buyr_name").val(    " "  ); // 주만자명 세팅
    $('#arsForm').submit();
    
    function init_orderid() { // ARS 결제 주문번호 생성 함수 정의부
        var today = new Date();
        var year  = today.getFullYear();
        var month = today.getMonth()+ 1;
        var date  = today.getDate();
        var time  = today.getTime();

        if(parseInt(month) < 10)
        {
            month = "0" + month;
        }

        var vOrderID = year + "" + month + "" + date + "" + time + ";" + $("#buyr_name").val() + ";" + $("#pay_phone").val();

        return vOrderID;
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
var SUBCHECK = true;
function smsmsg3(){
   var pointVmc = $('#VMCpoint3').val();
   var pointVmp = $('#VMPpoint3').val();
   var ckpointVmc = parseInt(pointVmc.replace(/,/g,""));
   var ckpointVmp = parseInt(pointVmp.replace(/,/g,""));
   
   if( ! $("#payCheckBox3").prop("checked") ) {
            alert("포인트 전송 정책 동의 후 전송이 가능합니다.");
            return false;
        }

   if ( ckpointVmc < 10000  &&  ckpointVmc > 0 ) {
	   alert('최소 전송포인트는 10,000원 입니다');
	   $('#VMCpoint3').val('0');
	   return false;
   }
   
   if (  ckpointVmp < 10000 && ckpointVmp > 0  ) {
	   alert('최소 전송포인트는 10,000원 입니다');
		$('#VMPpoint3').val('0');
	   return false;
   }

	var point3 = $("#totalvpoint3").text();

	if( point3 == 0 ) {
		alert ("0원을 전송하실 수 없습니다.");
		$("#VMCpoint3").focus();
		return;
	}
	
	var num = $("#smsid").val();
	num = num.toString();
	numDigit = num.length;

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
	
        
	if ( $("#smsinnumber").val() != $("#smsNumber").val() ){
		alert("인증번호가 틀렸습니다.");
		$("#smsinnumber").val("");
		$("#smsinnumber").focus();
		return;
	}

	
	if ( point3 != 0 && numDigit == 8 ){
		if(SUBCHECK) {
			SUBCHECK = false;
			var data = "mb_id=" + $("#smsid").val();
			$.ajax({
				url:'idTOname.php',
				type:'POST',
				data: data,
				async: false,
				success:function(result){
					if( result == "" ) {
						alert("존재하지 않는 ID입니다.");
						SUBCHECK = true;
						return;
					}
					if( confirm(result+"님에게 "+ $("#totalvpoint3").text() +"원을 전송하시겠습니까?") ) {
						$("#pointTransferF").submit();
					}else{
						SUBCHECK = true;
					}
				}
			});
		}
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
       
      
	var popOption = "width=610, height=810, top=50, left=50, toolbar=no,  menubar=no, resizeable=no, scrollbars=yes";
        var windowObj = window.open("/myOffice/point_pop.php","vmp_point",popOption);
      

}

//스크린 버튼 반짝이기
//setInterval(function(){
//  $("#screen").toggleClass('on');
//}, 600);


function onlyNumberComma(obj){
 
        
         var num01;
         var num02;
         num01 = obj.value;
         num02 = num01.replace(/\D/g,""); //숫자가 아닌것을 제거, 
                                          //즉 [0-9]를 제외한 문자 제거; /[^0-9]/g 와 같은 표현
         num01 = setComma(num02); //콤마 찍기
         obj.value =  num01;


    function setComma(n) {
         var reg = /(^[+-]?\d+)(\d{3})/;   // 정규식
         n += '';                          // 숫자를 문자열로 변환         
         while (reg.test(n)) {
            n = n.replace(reg, '$1' + ',' + '$2');
         }         
         return n;
    }
}



</script>
