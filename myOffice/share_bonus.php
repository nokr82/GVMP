<?php
set_time_limit(0);
include_once('./getMemberInfo.php');
include_once ('./_common.php');

$now_date = date("Y-m");
$timestamp = strtotime("-1 months");
$prev_date = date("Y-m",$timestamp);
$pre_y = substr($prev_date, 0, 4);
$pre_m = substr($prev_date, 5, 2);
//P('저번달 => '.$prev_date);
//P('이번달 => '.$now_date);
//매출가져오기
$sql = "
	SELECT * FROM sale_setting WHERE set_year = '".$pre_y."' AND set_month = '".$pre_m."'
";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);
$all_sale = $row['set_sale'];
$run_sale = $row['runBonus'];
//P('매출액 => '.number_format($all_sale));
//P('런 => '.$run_sale);
if($all_sale && $run_sale == "N"){
	$rankorder = array();
	$sql = " SELECT * FROM rankOrderBy WHERE orderNum <= 4 ORDER BY orderNum DESC";
	$res = mysql_query($sql);
	while ( $row = mysql_fetch_array($res) ) {
		$rankorder[] = $row;
	}
	if($rankorder){
		foreach($rankorder as $val){
			$sql = "
				SELECT COUNT(*)  FROM g5_member_t  mb
				INNER JOIN rankOrderBy ro ON mb.accountRank = ro.rankAccount
				 WHERE mb_id != 'admin' AND ro.orderNum <= ".$val['orderNum']."
				 AND  mb_id   NOT IN (
					SELECT mb_id
					FROM rank_history
					WHERE first_check='Y' AND LEFT(DATETIME,7) = '".$now_date."'
				)
			";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res);
			switch ( $val['orderNum'] ) {
			//로얄크라운엠버서더
			  case 0:
					$rm_count = $row[0];
					$rm_payments = cutting(floor(($all_sale * 0.003 ) / $rm_count));
				break;
			//크라운엠버서더
			  case 1:
					$km_count = $row[0];
					$km_payments = cutting(floor(($all_sale * 0.005 ) / $km_count));
				break;
			//트리플엠버서더
			  case 2:
					$tm_count = $row[0];
					$tm_payments = cutting(floor(($all_sale * 0.007 ) / $tm_count));
				break;
			//더블엠버서더
			  case 3:
					$dm_count = $row[0];
					$dm_payments = cutting(floor(($all_sale * 0.01 ) / $dm_count));
				break;
			//엠버서더
			  case 4:
					$em_count = $row[0];
					$em_payments = cutting(floor(($all_sale * 0.03 ) / $em_count));
				break;
			}
		}
	}
//엠버서더
$em_all =  $em_payments;
//P('엠버서더 카운트 => '.$em_count);
//P('엠버서더 금액 => '.number_format($em_all));
//P('엠버서더 지급 => '.number_format(cutting($em_all)));

//더블엠버서더
$dm_all = $dm_payments + $em_payments;

//P('더블엠버서더 카운트 => '.$dm_count);
//P('더블엠버서더 금액 => '.number_format($dm_payments));
//P('더블엠버서더 지급 => '.number_format(cutting($dm_all)));
//echo "<br>";

//트리플엠버서더
$tm_all = $tm_payments + $dm_payments + $em_payments;
//P('트리플엠버서더 카운트 => '.$tm_count);
//P('트리플엠버서더 금액 => '.number_format($tm_payments));
//P('트리플엠버서더 지급 => '.number_format(cutting($tm_all)));

//크라운엠버서더
$km_all = $km_payments + $tm_payments + $dm_payments + $em_payments;
//P('크라운엠버서더 카운트 => '.$km_count);
//P('크라운엠버서더 금액 => '.number_format($km_payments));
//P('크라운엠버서더 지급 => '.number_format(cutting($km_all)));

//로얄크라운엠버서더
$rm_all = $rm_payments + $km_payments + $tm_payments + $dm_payments + $em_payments;
//P('로얄크라운 카운트 => '.$rm_count);
//P('로얄크라운 금액 => '.number_format($rm_payments));
//P('로얄크라운 지급 => '.number_format(cutting($rm_all)));

$newAmb = array();

	foreach($rankorder as $val2){
	//신규 달성지급
	//  UPDATE MEMBER VMC / INSERT DAYPOINT WAY='NewAmbassadorBonus';
		$sqlem = "
			SELECT mb_id FROM  rank_history
			 WHERE mb_id != 'admin' AND change_rank = '".$val2['rankAccount']."' AND first_check='Y' AND LEFT(DATETIME,7) = '{$prev_date}'";
		$resem = mysql_query($sqlem);
		while ($row2 = mysql_fetch_array($resem)) {
			switch ( $val2['orderNum'] ) {
			//로얄크라운엠버서더
			  case 0:
					mysql_query("INSERT INTO dayPoint_t (mb_id,  DATE, way) VALUES ('".$row2['mb_id']."', NOW(), 'BonusNewRoyalCrown')");
				break;
			//크라운엠버서더
			  case 1:
						mysql_query("INSERT INTO dayPoint_t (mb_id, DATE, way) VALUES ('".$row2['mb_id']."', NOW(), 'BonusNewCrownAmbassador')");
				break;
			//트리플엠버서더
			  case 2:
						mysql_query("INSERT INTO dayPoint_t (mb_id, DATE, way) VALUES ('".$row2['mb_id']."', NOW(), 'BonusNewTripleAmbassador')");
				break;
			//더블엠버서더
			  case 3:
						mysql_query("INSERT INTO dayPoint_t (mb_id, DATE, way) VALUES ('".$row2['mb_id']."', NOW(), 'BonusNewDoubleAmbassador')");
				break;
			//엠버서더
			  case 4:
					$newAmb[] = $row2['mb_id'];
					$sqlMem="UPDATE g5_member_t SET VMC = VMC+".$em_all." WHERE mb_id = ".$row2['mb_id'];
					$resMem = mysql_query($sqlMem);
					if($resMem){
						mysql_query("INSERT INTO dayPoint_t (mb_id, VMC, DATE, way) VALUES ('".$row2['mb_id']."', '".$em_payments."', NOW(), 'BonusNewPayAmbassador')");
					}
				break;
			}
		}
	}


	// 유지 지급
	foreach($rankorder as $val1){
	//  UPDATE MEMBER VMC / INSERT DAYPOINT WAY='ShareBonus';
		$sql = "
			SELECT g5_member_t.mb_id FROM g5_member_t  
			WHERE mb_id != 'admin'  AND accountRank = '".$val1['rankAccount']."'
			AND  mb_id   NOT IN (
				SELECT mb_id
				FROM rank_history
				WHERE  change_rank = '".$val1['rankAccount']."' AND first_check='Y'  AND LEFT(DATETIME,7) = '".$now_date."'
			)
		";
		$res = mysql_query($sql);
		while($row = mysql_fetch_array($res)){
				switch ( $val1['orderNum'] ) {
				//로얄크라운엠버서더
				  case 0:
							$sqlmt="UPDATE g5_member_t SET VMC = VMC+".$rm_all." WHERE mb_id = ".$row['mb_id'];
							$resmt = mysql_query($sqlmt);
							if($resmt){
								mysql_query("INSERT INTO dayPoint_t (mb_id, VMC, DATE, way) VALUES ('".$row['mb_id']."', '".$rm_payments."', NOW(), 'ShareBonusRoyalCrown')");
								mysql_query("INSERT INTO dayPoint_t (mb_id, VMC, DATE, way) VALUES ('".$row['mb_id']."', '".$km_payments."', NOW(), 'ShareBonusCrownAmbassador')");
								mysql_query("INSERT INTO dayPoint_t (mb_id, VMC, DATE, way) VALUES ('".$row['mb_id']."', '".$tm_payments."', NOW(), 'ShareBonusTripleAmbassador')");
								mysql_query("INSERT INTO dayPoint_t (mb_id, VMC, DATE, way) VALUES ('".$row['mb_id']."', '".$dm_payments."', NOW(), 'ShareBonusDoubleAmbassador')");
								mysql_query("INSERT INTO dayPoint_t (mb_id, VMC, DATE, way) VALUES ('".$row['mb_id']."', '".$em_payments."', NOW(), 'ShareBonusAmbassador')");
							}
					break;
				//크라운엠버서더
				  case 1:
							$sqlmt="UPDATE g5_member_t SET VMC = VMC+".$km_all." WHERE mb_id = ".$row['mb_id'];
							$resmt = mysql_query($sqlmt);
							if($resmt){
								mysql_query("INSERT INTO dayPoint_t (mb_id, VMC, DATE, way) VALUES ('".$row['mb_id']."', '".$km_payments."', NOW(), 'ShareBonusCrownAmbassador')");
								mysql_query("INSERT INTO dayPoint_t (mb_id, VMC, DATE, way) VALUES ('".$row['mb_id']."', '".$tm_payments."', NOW(), 'ShareBonusTripleAmbassador')");
								mysql_query("INSERT INTO dayPoint_t (mb_id, VMC, DATE, way) VALUES ('".$row['mb_id']."', '".$dm_payments."', NOW(), 'ShareBonusDoubleAmbassador')");
								mysql_query("INSERT INTO dayPoint_t (mb_id, VMC, DATE, way) VALUES ('".$row['mb_id']."', '".$em_payments."', NOW(), 'ShareBonusAmbassador')");
							}
					break;
				//트리플엠버서더
				  case 2:
							$sqlmt="UPDATE g5_member_t SET VMC = VMC+".$tm_all." WHERE mb_id = ".$row['mb_id'];
							$resmt = mysql_query($sqlmt);
							if($resmt){
								mysql_query("INSERT INTO dayPoint_t (mb_id, VMC, DATE, way) VALUES ('".$row['mb_id']."', '".$tm_payments."', NOW(), 'ShareBonusTripleAmbassador')");
								mysql_query("INSERT INTO dayPoint_t (mb_id, VMC, DATE, way) VALUES ('".$row['mb_id']."', '".$dm_payments."', NOW(), 'ShareBonusDoubleAmbassador')");
								mysql_query("INSERT INTO dayPoint_t (mb_id, VMC, DATE, way) VALUES ('".$row['mb_id']."', '".$em_payments."', NOW(), 'ShareBonusAmbassador')");
							}
					break;
				//더블엠버서더
				  case 3:
							$sqlmt="UPDATE g5_member_t SET VMC = VMC+".$dm_all." WHERE mb_id = ".$row['mb_id'];
							$resmt = mysql_query($sqlmt);
							if($resmt){
								mysql_query("INSERT INTO dayPoint_t (mb_id, VMC, DATE, way) VALUES ('".$row['mb_id']."', '".$dm_payments."', NOW(), 'ShareBonusDoubleAmbassador')");
								mysql_query("INSERT INTO dayPoint_t (mb_id, VMC, DATE, way) VALUES ('".$row['mb_id']."', '".$em_payments."', NOW(), 'ShareBonusAmbassador')");
							}
					break;
				//엠버서더
				  case 4:
					if (!in_array($row['mb_id'],$newAmb)){
							$sqlmt="UPDATE g5_member_t SET VMC = VMC+".$em_all." WHERE mb_id = ".$row['mb_id'];
							$resmt = mysql_query($sqlmt);
							if($resmt){
								mysql_query("INSERT INTO dayPoint_t (mb_id, VMC, DATE, way) VALUES ('".$row['mb_id']."', '".$em_payments."', NOW(), 'ShareBonusAmbassador')");
							}
					}
					break;
				}
		}
	}



// 돌아갔을때 runBonus -> Y
	mysql_query("UPDATE  sale_setting SET runBonus = 'Y' WHERE set_year = '".$pre_y."' AND set_month = '".$pre_m."'");
}


function cutting($str){
	$val = floor($str/1000)*1000;
	return $val;
}



?>
