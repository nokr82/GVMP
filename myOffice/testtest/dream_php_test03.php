<?php 
	$postpriinfo = $_REQUEST["priinfo"];
	if($postpriinfo != NULL){
		$prikey = "D:/cert/����ŰPri.key";// ����Ű�� ��й�ȣ�� �Է�
		$keyPasswd = "��й�ȣ";
		$oED = new Crypt();
		$sDevelopedData = $oED->Decrypt($postpriinfo, $prikey, $keyPasswd);

		if($sDevelopedData == false )
		{
			echo "Error : xxxPri.key ��� �� keyPasswd�� ��Ȯ���� Ȯ���ϼ���.";
		}else{
			$rtn = split("\\$", $sDevelopedData);
			echo "result = ".$rtn[0]."<br>";
			echo "ci = ".$rtn[1]."<br>";
			echo "di = ".$rtn[2]."<br>";
			echo "��ȭ��ȣ = ".$rtn[3]."<br>";
			echo "�̵���Ż� = ".$rtn[4]."<br>";
			echo "������� = ".$rtn[5]."<br>";
			echo "���� = ".$rtn[6]."<br>";
			echo "���ܱ��� = ".$rtn[7]."<br>";
			echo "�̸� = ".$rtn[8]."<br>";
			echo "��û��ȣ = ".$rtn[9]."<br>";
			echo "��û�Ͻ� = ".$rtn[10]."<br>";
		}
	}//�������� ��츦 �����ϰ� priinfo ���� �����ϴ�.
	
	$result = $_REQUEST["result"];
	$resultCd = $_REQUEST["resultCd"];
?>