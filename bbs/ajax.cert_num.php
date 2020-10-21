<?php
/************************************************************************************
 ********************************만든이 : 홍동우 ****************************************
 *******************************만든시기 : 2020/06/04 **********************************
 ******************************내용: 로그인 인증번호    **********************************
 ************************************************************************************/
include_once('./_common.php');

// 컨텐츠 타입을 json으로 지정합니다.
header("Content-Type: application/json");

$id = $_POST['id'];
$pw = $_POST['pw'];
$date = date('Y-m-d H:i:s');
$date = strtotime($date);
if (strpos($id, "@")) {
    $re = sql_fetch("select mb_id from g5_member where mb_email = '{$id}'");
    $id = $re['mb_id'];
}

$rand_num = sprintf('%06d', rand(000000, 999999));
$mb = get_member($id);
if (!check_password($pw, $mb['mb_password'])) {
    $result['success'] = 'fail';
} else {
    $mb_date = strtotime($mb['mb_cert'] . '+1 days');
    if ($mb_date > $date || $mb['mb_id'] == 'admin') {
        $result['ck_auth'] = 'Y';
    } else {
        $result['ck_auth'] = 'N';
    }
    $result['code3'] = $mb_date;
    $result['code2'] = $date;
    $result['code'] = $rand_num;
    $result['success'] = 'ok';
    $result['id'] = $mb['mb_id'];
}


echo json_encode($result);

?>