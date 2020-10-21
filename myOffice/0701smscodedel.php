<?php
include_once('./dbConn.php');
//동우작성 시간지날시 문자인증번호삭제
// 컨텐츠 타입을 json으로 지정합니다.
header("Content-Type: application/json");

// SMS 인증번호를 전송하는 로직

$sql = "DELETE FROM smsCode WHERE n = '{$_POST['sms_id']}'";
mysql_query($sql);
$result['success'] = 'ok';
echo json_encode($result);

?>
