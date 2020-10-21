<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');
// 컨텐츠 타입을 json으로 지정합니다.
header("Content-Type: application/json");

$mb_id = $_POST['mb_id'];
$vmm = $_POST['vmm'];
$use_yn = $_POST['use_yn'];

$member = mysql_query("select * from g5_member where mb_id = '{$mb_id}'");
if ($member && $use_yn == 'Y') {
    $res2 = mysql_query("INSERT INTO `gyc5`.`dayPoint` (`mb_id`, `VMM`, `date`,`way`) VALUES ('{$mb_id}', '-{$vmm}',curtime(), 'vmmpay');");
    if ($res2) {
        $res = mysql_query("update g5_member set VMM = VMM - {$vmm} where mb_id = '{$mb_id}'");
        if ($res){
            $data['vmm'] = $vmm;
            $data['success'] = 'ok';
        }else{
            $data['success'] = 'fail3';
        }
    } else {
        $data['success'] = 'fail2';
    }
} else {
    $data['success'] = 'fail';
}
echo json_encode($data);


?>