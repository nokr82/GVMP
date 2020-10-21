<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/myOffice/dbConn.php");


    if( isset($_GET['type']) && isset($_GET['it_id']) ) {
        $view = "";
        if( $_GET['type'] == "add" )
            $view = "YES";
        
        mysql_query("update g5_shop_item set view = '{$view}' where it_id = '{$_GET['it_id']}'") or die("실패");
    }
    

?>

<script>
    alert("처리가 완료되었습니다.");
    window.location.href = "/adm/shop_admin/itemlist.php";
</script>

