<?php
include_once ('./_common.php');
include_once ('./dbConn.php');

// 이거 소스 개 판임. 다시 가동할 때 반드시 로직 QA 요망
// 우선 사용하지 않는 소스인듯하여 주석 처리함 : 이정현 2019-06-15





//$sql = "SELECT mb_id,VMC,VMG FROM g5_member WHERE accountRank IN (SELECT rankAccount FROM rankOrderBy WHERE orderNum < 11)";
//$result = mysql_query($sql);
//
//while( $row = mysql_fetch_array($result) ) {
//	$cVMG = 2080000;
//	$cVMC = $row['VMC'];
//	/*금고에 16만원없을때 체우는 로직*/
//	if ($row['VMG'] < 2080000) { //금고에 16만원 미만이 있을때
//		if($cVMC > 0){
//			$sendVMC = $cVMC - $cVMG;
//			if($sendVMC > 2080000)$sendVMC = 2080000;
//			$cVMC = $cVMC - $sendVMC;
//			mysql_query("update g5_member set VMC = '{$cVMC}', VMG = '{$cVMG}', renewal = '{$renewalDate}' where mb_id = '{$row['mb_id']}'");
//			mysql_query("insert into dayPoint set mb_id = '{$row['mb_id']}', VMC = -{$sendVMC}, VMG = +{$sendVMC} , date = NOW(), way = 'vmgMove'");
//		}
//	}
//	/*금고에 16만원없을때 체우는 로직*/
//}
		
?>