<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');
    
    // 관리자가 신분증 등록을 승인 또는 거절하는 것을 백엔드 처리하는 로직
    
    $dbName = "idCertificateTBL";
    
    $sql = "update {$dbName} set ok = '{$_POST["ok"]}', comm = '{$_POST["comment"]}' where no = {$_POST["no"]}";
    mysql_query($sql) or die("false");
    
    echo "true";
?>