<?php
include_once('./dbConn.php');


// 컨텐츠 타입을 json으로 지정합니다.
header("Content-Type: application/json");


$pay_name = $_POST["pay_name"]; // 성명
$pay_tel = $_POST["pay_tel"]; // 카드주 연락처 or 가상계좌: 실구매자 성명
$Amt = $_POST["Amt"];
$Amt = str_replace(",","",$Amt);
$pay_type = $_POST['pay_type']; // 카드결제3 or 가상계좌
$amt = $_POST['amt']; // 결제금액
$amt = str_replace(",","",$amt);
$mb_id = $_POST['mb_id']; // 로그인된 id
$result['sadasd'] = $pay_type;

// 1 : 가상결제 2: 카드결제3
if($pay_type == '2'){
    $sql = "insert into `gyc5`.`pay_list`(`pay_tel`,`pay_name`,`pay_type`,`amt`,`datetime`,`mb_id`)values('$pay_tel','$pay_name','2','$amt',now(),'$mb_id')";
    $query = mysql_query($sql);
}
$result['sql'] = $sql;
if ($query){
    $result['success'] = 'ok';
}else{
    $result['success'] = 'fail';
}

echo json_encode($result);
?>