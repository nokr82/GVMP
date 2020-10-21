<?
include_once('./_common.php');
$row['mb_id'] = "00010847";
$message = "문의하신 내용에 답변이 등록되었습니다.";
$push_token = getMemmberPushToken($row['mb_id']);

echo $row['mb_id']  . "<Br>";
echo $push_token . "<bR>";
if ($push_token) {
	$result = Android_Chat_Push($push_token, "문의알림", $message,"alram", "", "");
	echo $result . "<br>";
	if ($result) {
		savePushContent("문의알림","문의",$message,$row['mb_id'],$result);
	}
}
?>