<?
$sub_menu = "200910";
include_once('./_common.php');

check_demo();

auth_check($auth[$sub_menu], 'd');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

/*영수증 등록한사람*/
/*가맹점의 추천인*/

$crow = sql_fetch("SELECT mb_id FROM vdMapInfoTBL WHERE n = (SELECT vdMapidx FROM Receipt WHERE idx = '{$_POST['idx']}')");
$cuserid = $crow['mb_id'];

$row = sql_fetch("SELECT mb_id, billSum FROM Receipt WHERE idx='{$_POST['idx']}'");
$userid = $row['mb_id'];
$billSum = $row['billSum'];

$row = sql_fetch("SELECT accountType FROM g5_member WHERE mb_id = '".$userid."'");
$accountType = $row['accountType'];

$row = sql_fetch("SELECT cashback FROM vdMapInfoTBL WHERE n = (SELECT vdMapidx FROM Receipt WHERE idx='{$_POST['idx']}')");
$UserCashBack = ((int)$row['cashback']+1) / 100;

$row = sql_fetch("SELECT recommenderID FROM genealogy WHERE mb_id = '{$cuserid}'");
$RecomId = $row['recommenderID'];
$RecomCashBack = 1 / 100;

$row = sql_fetch("SELECT accountType FROM g5_member WHERE mb_id = '{$RecomId}'");
$RecomAcountType = $row['accountType'];



$state	= (isset($_POST['state'])&&trim($_POST['state']))?trim($_POST['state']):"";
$idx	= (isset($_POST['idx'])&&trim($_POST['idx']))?trim($_POST['idx']):"";
$agree	= (isset($_POST['agree'])&&trim($_POST['agree']))?trim($_POST['agree']):"";

if($state=="modify"){
	if($agree=="2") { //승인
		$userCashBack = $billSum * $UserCashBack;
		$RecomCashBack = $billSum * $RecomCashBack;

//		if(getRenewalDate($userid,$accountType)){ //renewal 날짜 체크
		sql_query("update g5_member set VCash = VCash + {$userCashBack} WHERE mb_id = '{$userid}'");
		sql_query("insert into dayPoint set mb_id = '{$userid}', VCash = +{$userCashBack}, date = NOW(), way = 'billPoint'");
//		}
		/*가맹점의 추천인의 Account Type*/
		if(getRenewalDate($RecomId,$RecomAcountType)){ //renewal 날짜 체크
			sql_query("update g5_member set VCash = VCash + {$RecomCashBack} WHERE mb_id = '{$RecomId}'");
			sql_query("insert into dayPoint set mb_id = '{$RecomId}', VCash = +{$RecomCashBack}, date = NOW(), way = 'billPoint'");
		}
		sql_query("update Receipt set state = '2' WHERE idx = '{$_POST['idx']}'");
		

		/*user의 추천인*/
		if($accountType=="CU"||$accountType=="SM"||$accountType=="VD"){
			$row = sql_fetch("SELECT recommenderID FROM genealogy WHERE mb_id = '{$userid}'");
			$memRecomId = $row['recommenderID'];
			$row = sql_fetch("SELECT accountType FROM g5_member WHERE mb_id = '{$memRecomId}'");
			$memRecomAcountType = $row['accountType'];
			if(getRenewalDate($memRecomId,$memRecomAcountType)){ //renewal 날짜 체크
				sql_query("update g5_member set VCash = VCash + {$RecomCashBack} WHERE mb_id = '{$memRecomId}'");
				sql_query("insert into dayPoint set mb_id = '{$memRecomId}', VCash = +{$RecomCashBack}, date = NOW(), way = 'billPoint'");
			}
		}
		/*user의 추천인*/

		//push처리
		$message = "등록하신 영수증이 승인되었습니다.";
		$push_token = getMemmberPushToken($userid);
		$result = Android_Chat_Push($push_token, "영수증알림", $message,"alram", "", "");
		if($result) {
			savePushContent("영수증알림","영수증승인",$message,$userid,$result);
		}
		//push처리
	}else if($agree=="3") { //거절
		sql_query("update Receipt set state = '3' WHERE idx = '{$_POST['idx']}'");

		//push처리
		$message = "등록하신 영수증이 거절되었습니다.";
		$push_token = getMemmberPushToken($userid);
		$result = Android_Chat_Push($push_token, "영수증알림", $message,"alram", "", "");
		if($result) {
			savePushContent("영수증알림","영수증거절",$message,$userid,$result);
		}
		//push처리
	}
}else if($state=="del") { //삭제

}
?>
<script type="text/javascript">
<!--
alert("처리 되었습니다.");
location.href="<?=$_SERVER['HTTP_REFERER']?>";
//-->
</script>