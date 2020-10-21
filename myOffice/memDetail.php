<?php
include_once ('./dbConn.php');
include_once('./getMemberInfo.php');


$MBID = (isset($_POST['mbid'])&&$_POST['mbid'])?trim($_POST['mbid']):"";
$RECOM = (isset($_POST['recom'])&&$_POST['recom'])?trim($_POST['recom']):"";

//$MBID = "00000674";
//$RECOM = "김은향";


if($MBID && $RECOM){
	$sql = "SELECT mb_id,mb_name,left(mb_open_date,10) as mb_open_date,left(renewal,10) as renewal,accountRank, team1, team2 FROM g5_member WHERE mb_id = '".$MBID."'";
	$result = mysql_query($sql);
	if($result){
		$row = mysql_fetch_array($result);


		$que = mysql_query("select a.*, b.renewal from genealogy as a inner join g5_member as b on a.mb_id = b.mb_id where a.recommenderID = '".$MBID."' and b.accountType = 'VM'");
		$now_temp = strtotime(date("Y-m-d"));
		$renewalCheckCount = 0; // 추천인이 몇 명인지 카운트하는 용도. 화면에 VIEW시킬 때도 씀
		while( $recCountRe = mysql_fetch_array($que) ) {
                        $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$recCountRe['renewal']}', interval +4 month) AS date"));
			$timestamp_temp = strtotime( $dateCheck_1["date"] );
			if( $timestamp_temp >= $now_temp ) {
				$renewalCheckCount++;
			}
		}




		$IMG = levelImg($row['accountRank']);
		if($IMG){
			$DATA['rankImg'] = './images/'.levelImg(rankCheck($row["mb_id"])).'.png';
		}else{
			$DATA['rankImg'] = '';
		}
                
                $team3_row = mysql_fetch_row(mysql_query("select count(*) as n from team3_list where mb_id = '{$row["mb_id"]}'"));
                
		$DATA['name'] = $row['mb_name'];
		$DATA['mbid'] = $row['mb_id'];
		$DATA['joindate'] = $row['mb_open_date'];
                $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row['renewal']}', interval +4 month) AS date"));
		$DATA['renewal'] = $dateCheck_1["date"];
		$DATA['people'] = $row['team1']."/".$row['team2']."/".$team3_row[0] . " 추천 " . $renewalCheckCount . "명";
		$DATA['recom'] = $RECOM;
		$DATA['code'] = "00";
	}else{
		$DATA['code'] = "01";
	}
}else{
	$DATA['code'] = "01";
}
echo json_encode($DATA);



function levelImg($str)
{
	$DATA;
	if($str=="VM"){
		$DATA = "VM";
	} else if ($str=="MASTER"){
		$DATA = "M_1";
	} else if ($str=="Double MASTER"){
		$DATA = "M_2";
	} else if ($str=="Triple MASTER"){
		$DATA = "M_3";
	} else if ($str=="AMBASSADOR"){
		$DATA = "A_1";
	} else if ($str=="Double AMBASSADOR"){
		$DATA = "A_2";
	} else if ($str=="Triple AMBASSADOR"){
		$DATA = "A_3";
	} else if ($str=="Crown AMBASSADOR"){
		$DATA = "A_4";
	} else if ($str=="Royal Crown AMBASSADOR"){
		$DATA = "A_5";
	} else if ($str=="1 STAR"){
		$DATA = "S_1";
	} else if ($str=="2 STAR"){
		$DATA = "S_2";
	} else if ($str=="3 STAR"){
		$DATA = "S_3";
	} else if ($str=="4 STAR"){
		$DATA = "S_4";
	} else if ($str=="5 STAR"){
		$DATA = "S_5";
	}
	return $DATA;
}
?>