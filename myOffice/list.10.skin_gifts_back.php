<?php
include_once ('./_common.php');
include_once("./dbConn.php");

// VM이 받고 싶은 제품을 선택했을 때 백엔드 처리하는 코드

if( isset($_GET['it_id']) ) {
    mysql_query("update selectedProducts set productNumber = '{$_GET['it_id']}' where mb_id = '{$member['mb_id']}'") or die("실패");
    
    alert("제품 선택이 완료되었습니다. 배송지 정보를 입력하지 않으셨다면 반드시 입력 바랍니다.", "/myOffice/gifts.php?ca_id=10");
}


?>
