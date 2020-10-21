<?php 
	$postpriinfo = $_REQUEST["priinfo"];
	if($postpriinfo != NULL){
		$prikey = "D:/cert/개인키Pri.key";// 개인키와 비밀번호를 입력
		$keyPasswd = "비밀번호";
		$oED = new Crypt();
		$sDevelopedData = $oED->Decrypt($postpriinfo, $prikey, $keyPasswd);

		if($sDevelopedData == false )
		{
			echo "Error : xxxPri.key 경로 및 keyPasswd가 정확한지 확인하세요.";
		}else{
			$rtn = split("\\$", $sDevelopedData);
			echo "result = ".$rtn[0]."<br>";
			echo "ci = ".$rtn[1]."<br>";
			echo "di = ".$rtn[2]."<br>";
			echo "전화번호 = ".$rtn[3]."<br>";
			echo "이동통신사 = ".$rtn[4]."<br>";
			echo "생년월일 = ".$rtn[5]."<br>";
			echo "성별 = ".$rtn[6]."<br>";
			echo "내외국인 = ".$rtn[7]."<br>";
			echo "이름 = ".$rtn[8]."<br>";
			echo "요청번호 = ".$rtn[9]."<br>";
			echo "요청일시 = ".$rtn[10]."<br>";
		}
	}//정상적인 경우를 제외하고 priinfo 값이 없습니다.
	
	$result = $_REQUEST["result"];
	$resultCd = $_REQUEST["resultCd"];
?>