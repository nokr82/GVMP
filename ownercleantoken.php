<script src="http://code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript">
<!--
var authData = {
	service: "ownerclan",
	userType: "seller",
	username: "chan4423", // 오너클랜 셀러 ID.
	password: "cks929245377" // 셀러 비밀번호.
};
window.onload=function(){
	$.ajax({
		url: "https://auth.ownerclan.xyz/auth", // Production 환경에서는 "https://auth.ownerclan.xyz/auth"를 사용합니다.
		type: "POST",
		contentType: "application/json",
		processData: false,
		data: JSON.stringify(authData),
		success: function(data) {
			$("#owntoken").val(data);
			console.log(data);
			tokeninsert(data);
		},
		error: function(data) {
			console.error(data.responseText, data.status);
		}
	});
}

function tokeninsert(token){
	var formData = $("#inToken").serialize();
	$.ajax({
		url: "./ownercleantokenupdate.php", // Production 환경에서는 "https://auth.ownerclan.xyz/auth"를 사용합니다.
		type: "POST",
		dataType: "json",
		data:formData,
		success: function(data) {
			if(data.code=="00") {
				alert("OWNER CLEAN TOKEN을 정상적으로 업데이트 했습니다.");
			}
		},
		error: function(data) {
			console.error(data.responseText, data.status);
		}
	});
}
//-->
</script>
<form name="inToken" id="inToken" action="./ownercreantokenupdate.php">
<input type="hidden" name="owntoken" id="owntoken" value="">
</form>