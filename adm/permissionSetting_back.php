<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');


    if( $_POST["type"] == "" || $_POST["accountRank"] == "" ) {
        echo "ERROR1"; exit();
    }
    
    mysql_query("update config set value = '{$_POST["accountRank"]}' where config = '{$_POST["type"]}'") or die("ERROR2");
?>

<script>
    alert("처리가 완료되었습니다.");
    window.location.href = "/adm/member_modify.php";
</script>
