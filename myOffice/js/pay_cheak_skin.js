$(document).ready(function(){
    

//다음1 버튼 클릭
$('#next1').click(function(){

    if ($("#name").val().trim() == "") {
    alert("이름을 입력해 주세요.");
    $("#name").focus();
     return false;
    }
    
    var value_check1 = String($('#unum_f').val()).length;
    var value_check2 = String($('#unum_b').val()).length;

     if ($("#unum_f").val().trim() == "") {
    alert("주민번호 앞자리를 입력해 주세요.");
    $("#unum_f").focus();
     return false;
    }

     if($('#unum_f').val() == '' || (value_check1 > 0 && value_check1 < 6)){
      alert('주민번호 앞자리를 6자리로 입력하세요.');
      $("#unum_f").focus();
      return false;
  }


    if ($("#unum_b").val().trim() == "") {
    alert("주민번호 뒷자리를 입력해 주세요.");
    $("#unum_b").focus();
     return false;
    }

    if($('#unum_b').val() == '' || (value_check2 > 0 && value_check2 < 7)){
      alert('주민번호 뒷자리를 7자리로 입력하세요.');
      $("#unum_f").focus();
      return false;      
    }
    
    if ($("#bank_name").val().trim() == "") {
    alert("은행을 입력해 주세요.");
    $("#bank_name").focus();
     return false;
    }
    
    if ($("#bank_user").val().trim() == "") {
    alert("예금주를 입력해 주세요.");
    $("#bank_user").focus();
     return false;
    }
     /*191125 추가 - 예금주명 가입자명과 일치 확인*/
   var bankuser = document.getElementById("bank_user").value;
   var chkname = document.getElementById("name").value;
   
   if( bankuser != chkname ) {
      alert("이름과 예금주명이 일치하지 않습니다.");
      $("#bank_user").focus();
       return false;
   }/*191125 추가 - 예금주명 가입자명과 일치 확인 끝*/    
    
    if ($("#bank_num").val().trim() == "") {
    alert("계좌번호를 입력해 주세요.");
    $("#bank_num").focus();
     return false;
    }   
    
    $('.form02').fadeIn();
    $('.form01').css({'display':'none'});
    $('.certify_idcard_wrap').css({'display':'none'});

    

});
    

//포인트 선택시 변화    


 $('#point_c').click(function(){
    $('.point_p').removeClass('on');
    $('.point_p_fales').removeClass('on'); 
    $('.point_c_fales').removeClass('on'); 
    $('.point_c').addClass('on'); 
      
     $('.tt_3').fadeIn();
     $('.tt_4').css({'display':'none'});
     $('.tt_5').css({'display':'none'});
    
    
    var comvmc = $("#possibleVMC").val().toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    
    $("#day_pay").val(comvmc);
    
    $("#last_pay_in").val(""); 
    $("#last_pay").val("");
    $('.form03').fadeIn();
    $('.form04').fadeIn();
    var ck_vmc = $("#point_c").val();
     $("#ck_point").val(ck_vmc);
   
 });
 
  $('#point_c_fales').click(function(){
    $('.point_p').removeClass('on');
    $('.point_p_fales').removeClass('on'); 
    $('.point_c').removeClass('on'); 
    $('.point_c_fales').addClass('on');
    $("#last_pay_in").val(""); 
    $("#last_pay").val("");
       $('.tt_5').fadeIn();
     $('.tt_4').css({'display':'none'});
     $('.tt_3').css({'display':'none'});
      
      $('.form03').css({'display':'none'});
    $('.form04').fadeIn();
    var ck_vmc_f = $("#point_c_fales").val();
     $("#ck_point").val(ck_vmc_f);
  
      
  });
  
  $('#point_p_fales').click(function(){
    $('.point_p').removeClass('on');
    $('.point_c_fales').removeClass('on'); 
    $('.point_c').removeClass('on'); 
    $('.point_p_fales').addClass('on');
    $("#last_pay_in").val(""); 
    $("#last_pay").val("");
      $('.tt_5').fadeIn();
     $('.tt_4').css({'display':'none'});
     $('.tt_3aler').css({'display':'none'});

    $('.form03').css({'display':'none'});
    $('.form04').fadeIn();
    var ck_vmp_f = $("#point_p_fales").val();
     $("#ck_point").val(ck_vmp_f);
     
 
      
  });
 
 $('#point_p').click(function(){
     var d= new Date();
     var day= d.getDate();
     if(day>15){
         alert('P포인트 신청기간이 아닙니다.');
         $('#point_p').attr("checked",false);
         return;
     }
     
    $('.point_c').removeClass('on');
    $('.point_p_fales').removeClass('on'); 
    $('.point_c_fales').removeClass('on'); 
    $('.point_p').addClass('on'); 
    
     $('.tt_4').fadeIn();
     $('.tt_5').css({'display':'none'});
     $('.tt_3').css({'display':'none'});
    
    var comvmp = $("#possibleVMP").val().toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); 
    $("#day_pay").val(comvmp); 
    $("#last_pay_in").val("");
    $("#last_pay").val("");
    $('.form03').fadeIn();
    $('.form04').fadeIn();
    var ck_vmp = $("#point_p").val();
     $("#ck_point").val(ck_vmp);
     
    
 });
 
 
 //전액신청시 변화
 $('#all_pay').click(function(){
    var all_point = $('#day_pay').val(); 
    $('#last_pay_in').val(all_point);
    var start_pay = parseInt(document.getElementById("last_pay_in").value.replace(/,/g,""));
      var tax_pay = start_pay*0.967;
      var last_pay = Math.floor(tax_pay/100) * 100;
      var comlast = last_pay.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      $('#last_pay').val(comlast);
     
 
      
     //숫자외 문자 지우기 
     event = event || window.event;
	var keyID = (event.which) ? event.which : event.keyCode;
	if (keyID == 8 || keyID == 46 || keyID == 37 || keyID == 39 || keyID == 9)
		return;
	else
		event.target.value = event.target.value.replace(/[^0-9]/g, "");
   
      
     document.getElementById('last_pay_in').value = String(document.getElementById('last_pay_in').value).replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');  
     
                var day_pay_num = parseInt(document.getElementById("day_pay").value.replace(/,/g,""));
            var last_pay_in_num = parseInt(document.getElementById("last_pay_in").value.replace(/,/g,""));
     
    if (document.getElementById("day_pay").value) {
            

            
            if(day_pay_num < last_pay_in_num){ //대여금 변제된 값이 0이나 -값이 나오지 않도록 이벤트처리
                alert('보유하신 포인트 이상을 입력하였습니다.');
                event.stopPropagation();
                document.getElementById('last_pay_in').value = null;
                document.getElementById('last_pay').value = null;
                return false;
            }
            
            
            
   
        }
     payCheck();
 });
 
 
//이전버튼 클릭시 변화 
  $('#prev1').click(function(){
    $('.form01').fadeIn();
    $('.form02').css({'display':'none'});
    $('.form03').css({'display':'none'});
    $('.form04').css({'display':'none'});
  });
 
 //완료 클릭시 변화
$("#form_2").on("submit", function() {
     if (!($("#point_c").attr("checked") || $("#point_p").attr("checked") || $("#point_c_fales").attr("checked") || $("#point_p_fales").attr("checked"))) {
        alert('신청종류를 선택 해 주십시오.');
        $("#point_p").focus();
        return false;
    }
      
      
      if($("#point_c").attr("checked")){
        if ($("#last_pay_in").val().trim() == "") {
          alert("신청금액을 입력해 주세요.");
          $("#last_pay_in").focus();
           return false;
          }

          var point_ck= $("#ck_point").val();
          var pay_ck= $("#last_pay_in").val();

          console.log("끝");
          var all_ok = confirm(point_ck+'를 '+pay_ck+'원 신청하시겠습니까?');
          if (all_ok) {
              $('.form04').hide();
              $('.pay_btn').hide();
              console.log("끝2");
             return true;
          }
          else {
             return false;
          }
      };
      
      if($("#point_p").attr("checked")){
        if ($("#last_pay_in").val().trim() == "") {
          alert("신청금액을 입력해 주세요.");
          $("#last_pay_in").focus();
           return false;
          }

          var point_ck= $("#ck_point").val();
          var pay_ck= $("#last_pay_in").val();

          var all_ok = confirm( point_ck+'를 '+pay_ck+'원 신청하시겠습니까?');
          if (all_ok) {
             return true;
          }
          else {
             return false;
          }
      };
      
      if($("#point_p_fales").attr("checked")){
        

          var point_ck= $("#ck_point").val();
          var pay_ck= $("#last_pay_in").val();

          var all_ok = confirm(point_ck+'하시겠습니까?');
          if (all_ok) {
             return true;
          }
          else {
             return false;
          }
      };
      
      if($("#point_c_fales").attr("checked")){
       

          var point_ck= $("#ck_point").val();
          var pay_ck= $("#last_pay_in").val();

          var all_ok = confirm(point_ck+'하시겠습니까?');
          if (all_ok) {
             return true;
          }
          else {
             return false;
          }
      };
     
     
        
     
     
     
     });
    
    

    
    

    
    
    
    
    
    
});

//3자리마다 콤마 붙여주기
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}






function math(event) {
       
      //입금예정금액 계산식 
      
      var start_pay = parseInt(document.getElementById("last_pay_in").value.replace(/,/g,""));
      var tax_pay = start_pay*0.967;
      var last_pay = Math.floor(tax_pay/100) * 100;
      var comlast = last_pay.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      $('#last_pay').val(comlast);
     
 
      
     //숫자외 문자 지우기 
     event = event || window.event;
	var keyID = (event.which) ? event.which : event.keyCode;
	if (keyID == 8 || keyID == 46 || keyID == 37 || keyID == 39 || keyID == 9)
		return;
	else
		event.target.value = event.target.value.replace(/[^0-9]/g, "");
   
      
     document.getElementById('last_pay_in').value = String(document.getElementById('last_pay_in').value).replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');  
     
                var day_pay_num = parseInt(document.getElementById("day_pay").value.replace(/,/g,""));
            var last_pay_in_num = parseInt(document.getElementById("last_pay_in").value.replace(/,/g,""));
     
    if (document.getElementById("day_pay").value) {
            

            
            if(day_pay_num < last_pay_in_num){ //대여금 변제된 값이 0이나 -값이 나오지 않도록 이벤트처리
                alert('보유하신 포인트 이상을 입력하였습니다.');
                event.stopPropagation();
                document.getElementById('last_pay_in').value = null;
                document.getElementById('last_pay').value = null;
                return false;
            }
            
            
            
   
        }
}


function commaChar(event) {
    document.getElementById('last_pay_in').value = String(document.getElementById('last_pay_in').value).replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}



 function notNumberTo(event) {
	event = event || window.event;
	return false;
}
function not(event) {
	event = event || window.event;
	var keyID = (event.which) ? event.which : event.keyCode;
	event.target.value = event.target.value.replace(/[^0-9]/g, "");
}
function onlyNumber(event) {
	event = event || window.event;
	var keyID = (event.which) ? event.which : event.keyCode;
	if ((keyID >= 48 && keyID <= 57) || (keyID >= 96 && keyID <= 105) || keyID == 8 || keyID == 46 || keyID == 37 || keyID == 39 || keyID == 9)
            
		return;
	else
		return false;
        
        
            
}




// 숫자이외의 문자 지움
function removeChar(event) {
	event = event || window.event;
	var keyID = (event.which) ? event.which : event.keyCode;
	if (keyID == 8 || keyID == 46 || keyID == 37 || keyID == 39 || keyID == 9)
		return;
	else
		event.target.value = event.target.value.replace(/[^0-9]/g, "");
   
       
        
        }


function payCheck () {
    var payck =  $('#last_pay_in').val();
    var paychNum = parseInt(payck.replace(/,/g,""));
    if ( paychNum < 50000 ) {
        alert( '최소 정산 금액은 50,000원 입니다.' )
        $('#last_pay_in').val('')
        $("#last_pay").val("");
        return false;
    }
    
    
    
}

