<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');
// 컨텐츠 타입을 json으로 지정합니다.
header("Content-Type: application/json");

$pk_id = $_GET['pk_id'];
$vmp_mbid = $_GET['vmp_mbid'];
if ($pk_id != '' && $vmp_mbid != '') {
    $member_t = mysql_fetch_array(mysql_query("select * from g5_member where pk_id = '{$pk_id}'"));
    $change_member = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$vmp_mbid}'"));
}

if ($member_t && $change_member) {

    $sql = "UPDATE `gyc5`.`g5_member` SET `pk_id` = '' WHERE (`mb_id` = '{$member_t['mb_id']}')";
    $data['sql'] = $sql;
    $change_se = mysql_query($sql);

    if ($change_se) {
        $sql = "UPDATE `gyc5`.`g5_member` SET `pk_id` = '{$pk_id}' WHERE (`mb_id` = '{$change_member['mb_id']}')";
        $change_se2 = mysql_query($sql);
        $data['sql2'] = $sql;
        if ($change_se2) {
            $data['success'] = 'success';
        } else {
            $data['success'] = 'error2';
        }
    } else {
        $data['success'] = 'error1';
    }

} elseif ($change_member) {
    $sql = "UPDATE `gyc5`.`g5_member` SET `pk_id` = '{$pk_id}' WHERE (`mb_id` = '{$change_member['mb_id']}')";
    mysql_query($sql);
    $data['sql3'] = $sql;
    $data['success'] = 'success2';
} else {
    $data['success'] = 'fail';
}
echo json_encode($data);

?>
