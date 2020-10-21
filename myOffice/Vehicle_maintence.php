<?php
include_once('./dbConn.php');
include_once('./getMemberInfo.php');
include_once ('./_common.php');

//2020-01-23 차량유지비 지급 로직 다음달부터사용가능

set_time_limit(0);

//지난달 첫달성인지 체크하는로직
$timestamp = strtotime("-1 months");
$prev_date = date("Y-m",$timestamp);
$prev_date = "2020-02";
$sql2 = "select mb_id from  rank_history
 where mb_id != 'admin' and change_rank = '5 STAR' and first_check='Y' and datetime like '{$prev_date}%'";
$change_info = mysql_query($sql2);

// 지난달 5star 엠버서더 동시 첫달성
$check_overlap = [];
$qry = "
	SELECT a.* FROM (
		SELECT mb_id
		      FROM rank_history
			WHERE mb_id != 'admin' AND  change_rank = 'AMBASSADOR' AND first_check='Y' AND DATETIME LIKE '{$prev_date}%'
		) b JOIN rank_history a ON b.mb_id = a.mb_id
	WHERE a.mb_id != 'admin' AND a.change_rank = '5 STAR'  AND a.first_check='Y' AND a.datetime LIKE '{$prev_date}%'
";
$res = mysql_query($qry);
while ( $row1 = mysql_fetch_array($res) ) {
    array_push($check_overlap,$row1['mb_id']);
}
P($check_overlap);

//현재 5 STAR인 멤버를 뽑는 로직
$sql = "select mb_id from g5_member where mb_id != 'admin' and accountRank = '5 STAR'";
$member_info = mysql_query($sql);

$dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add(curdate(), interval +4 month) AS date"));


$now_date = $dateCheck_1["date"];

//최초달성아이디저장
$check_ids = [];

$defuald_ids = [];

while ( $row2 = mysql_fetch_array($change_info) ) {
    if (!in_array($row2['mb_id'],$check_overlap)){
		//update member where mb_id
		$sql="UPDATE g5_member SET VMP = VMP+1000000 WHERE mb_id = ".$row2['mb_id'];
		P($sql);
		//$resMem = mysql_query($sql);
		if($resMem){
			//mysql_query("INSERT INTO dayPoint (mb_id, VMC, VMR, VMP, VMM, VMG, V, VCash, VPay, bizMoney, DATE, way) VALUES ('".$row2['mb_id']."', '1000000', 0, 0, 0, 0, 0, 0, 0, 0, NOW(), 'newAutoVMC')");
		}
	}
 //최초달성아이디배열에 넣기
    array_push($check_ids,$row2['mb_id']);
}


while ( $row = mysql_fetch_array($member_info) ) {
 	//update member
	// daypoint  + 100 insert
	$minNum = '';
   //최초달성에 이미포한된아이디는 뺴고 불러오기
    if (!in_array($row['mb_id'],$check_ids)){
			//update member where mb_id
			$sql="UPDATE g5_member SET VMP = VMP+1000000 WHERE mb_id = '".$row['mb_id']."'";
			//$resMem = mysql_query($sql);
			if($resMem){
				//mysql_query("INSERT INTO dayPoint (mb_id, VMC, VMR, VMP, VMM, VMG, V, VCash, VPay, bizMoney, DATE, way) VALUES ('".$row['mb_id']."', '1000000', 0, 0, 0, 0, 0, 0, 0, 0, NOW(), 'AutoVMC')");
			}
		//array_push($defuald_ids,$row['mb_id']);
    }
}

?>

