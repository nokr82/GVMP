<?php
include_once('./dbConn.php');
// 동우작성 문잔인증 체크
// 컨텐츠 타입을 json으로 지정합니다.
header("Content-Type: application/json");

$query = mysql_query("select * from smsCode where sms_code = '{$_POST['sms_code']}' and n = '{$_POST['sms_id']}'");
$sms_code = mysql_fetch_array($query);


if ($sms_code){
    $result['success'] = 'ok';
}else{
    $result['success'] = 'fail';
}

echo json_encode($result);

?>
