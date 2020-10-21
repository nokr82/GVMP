<?php
//under_member_list 프로시저 비동기식으로 돌리기위한 로직
include_once('./dbConn.php');
include_once('./dbConn2.php');

$id = $_POST['mb_id'];

$sql =  mysql_query("CALL SP_TREE('{$id}')");

$data['result'] = 1;
echo json_encode($data['result']);

?>
