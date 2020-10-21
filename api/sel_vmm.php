<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');
// 컨텐츠 타입을 json으로 지정합니다.
header("Content-Type: application/json");

$mb_id = $_POST['mb_id'];
$member = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$mb_id}'"));
if ($member) {
    $data['mb_id'] = $mb_id;
    $data['vmm'] = $member['VMM'];
    $data['success'] = 'ok';
} else {
    $data['success'] = 'fail';
}
echo json_encode( $data);

?>
