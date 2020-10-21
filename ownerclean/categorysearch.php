<?
include_once('./_common.php');

$row = sql_fetch_array(sql_query("SELECT * FROM g5_config"));
$TOKEN = $row['cf_10'];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="EditPlus®">
<meta name="Author" content="">
<meta name="Keywords" content="">
<meta name="Description" content="">
<title>Neung System</title>
</head>
<script src="http://code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript">
<!--
/**
 * 아래 예제는 인터넷 브라우저의 콘솔 창에서 실행해볼 수 있습니다.
 * 크롬 브라우저에서 테스트되었습니다.
 *
 * 토큰은 manual.md 파일의 JWT 인증 섹션에 있는 방식으로 발급받을 수 있으며,
 * 이 예제 코드 하단부에 있는 client.setRequestHeader("Authorizaiton", "Bearer YOUR_TOKEN"); 코드의
 * YOUR_TOKEN 부분에 발급받은 토큰을 넣어주시면 됩니다.
 */

/**
 * 이 예제는 ROOT 카테고리에 대한 descendants 정보를 요청해
 * 하위 카테고리 리스트를 불러오는 예시입니다.
 * descendants는 몇 개를 불러올 지 명시하지 않았으므로, 처음 100개를 불러옵니다.
 */
var client = new XMLHttpRequest();

/**
 * 정보를 읽어올 카테고리입니다.
 */
var startCursorValue = 0;
var count = 1000;
var category = "000000000000";
var qindex = 0;
function searchCategory() {
	var readQuery = `
	query {
		category(key: "${category}") {
		  descendants (after: "${startCursorValue}", first: ${count}) {
			pageInfo {
			  startCursor
			  endCursor
			}
			edges {
			  cursor
			  node {
				key
				name
			  }
			}
		  }
		}
	  }
	`;

	client.open("GET", "https://api.ownerclan.com/v1/graphql?query=" + encodeURIComponent(readQuery), true);
	client.setRequestHeader("Authorization", "Bearer <?=$TOKEN?>");
	client.onreadystatechange = function (aEvt) {
		if (client.readyState === 4) {
			if (client.status === 200) {
				var response = JSON.parse(client.responseText);
				if (response.errors) {
					// 에러가 있다면 에러를 콘솔에 씁니다.
					console.log(JSON.stringify(response.errors));
				} else {
					// 에러가 없다면 반환된 데이터를 콘솔에 씁니다.
					if(response.data.category.descendants.edges.length){
						$.ajax({ 
							type: 'POST', 
							url: './categoryupdata.php',
							dataType : 'json',
							data : {
								'jsonData':JSON.stringify(response.data.category.descendants.edges)
							},
							success: function(data) { 
								if(data.msg=="true"){
									$(".backprogress li").eq(qindex).addClass("addendback");
									startCursorValue=startCursorValue+1000;
									qindex=qindex+1;
									searchCategory();
								}
							} 
						});	
					}else{
						$(".backprogress li").css("background","#c6c6c6");
						alert("카테고리 업데이트가 완료 되었습니다.");
						$(".progressimg").hide();
					}
				}
			} else {
				// API 서버 응답이 정상이 아닌 경우 에러와 HTTP status code를 콘솔에 씁니다.
				console.error(client.status, client.responseText);
			}
		}
	}

	client.send(null);
}

window.onload=function(){
	searchCategory();
}
//-->
</script>
<style>
body {
	width:100vw;
	height:100vh;
	overflow:hidden;
}
.progressimg {width: 480px;height: 360px;margin: 0 auto;display: block;margin-top: 10%;position: relative;}
.backprogress{
	top:0px;
	left:0px;
	width:100vw;
    height: 100vh;
    position: absolute;
	margin:0px;
	padding:0px;
}
.backprogress li{list-style:none;width:12.5%;float:left;height:100vh;}
.addendback {background:#c6c6c6;}
</style>
<body>
<ul class="backprogress">
<?for($i=1;$i<=8;$i++){?>
	<li></li>
<?}?>
</ul>
<img src="/img/progress.gif" class="progressimg">
</body>
</html>