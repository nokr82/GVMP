
<script  src="http://code.jquery.com/jquery-latest.min.js"></script>
<script>

    	var data = "strData=[VMP] 회원가입을 축하드립니다. 아이디는 1234입니다.&strTelList=01026591346;&strCallBack=07074515121";
    	alert(data); 
         $.ajax({
             url:'/sms/sms.php',
             type:'POST',
             data: data
         });
         alert("문자발송 후");

</script>