<?php
    include_once ('./dbConn.php');
    
    // 광고 센터에서 비즈머니 전환을 처리하는 백엔드 로직
    
    
    $_POST["mb_id"] = trim($_POST["mb_id"]);
    $_POST["mb_vmc_point"] = preg_replace("/[^0-9]/", "",trim($_POST["mb_vmc_point"])) ;
    $_POST["mb_vmp_point"] = preg_replace("/[^0-9]/", "",trim($_POST["mb_vmp_point"])) ;
    $_POST["mb_vmm_point"] = preg_replace("/[^0-9]/", "",trim($_POST["mb_vmm_point"])) ;
    
    if( $_POST["mb_id"] == "" || $_POST["mb_vmc_point"] == "" || $_POST["mb_vmp_point"] == "" || $_POST["mb_vmm_point"] == "" ) {
        echo "false"; exit();
    }
    
    $g5_memberRow = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$_POST["mb_id"]}'")) or die("false");
    
    if( $g5_memberRow["mb_id"] == "" ) {
        echo "false"; exit();
    }
    
    if( $g5_memberRow["VMC"] < $_POST["mb_vmc_point"] ) {
        echo "false"; exit();
    }
    if( $g5_memberRow["VMP"] < $_POST["mb_vmp_point"] ) {
        echo "false"; exit();
    }
    if( $g5_memberRow["VMM"] < $_POST["mb_vmm_point"] ) {
        echo "false"; exit();
    }
    
    mysql_query("update g5_member set VMC = VMC - {$_POST["mb_vmc_point"]}, VMP = VMP - {$_POST["mb_vmp_point"]}, VMM = VMM - {$_POST["mb_vmm_point"]}, bizMoney = bizMoney + ".($_POST["mb_vmc_point"]+$_POST["mb_vmp_point"]+$_POST["mb_vmm_point"])." where mb_id = '{$_POST["mb_id"]}'") or die("false");
    mysql_query("insert into dayPoint set mb_id = '{$_POST["mb_id"]}', VMC = -{$_POST["mb_vmc_point"]}, VMR=0, VMP=-{$_POST["mb_vmp_point"]}, VMM=-{$_POST["mb_vmm_point"]}, VMG=0,bizMoney=0, date=NOW(), way = 'bizMoneyChange'") or die("false");
    mysql_query("insert into dayPoint set mb_id = '{$_POST["mb_id"]}', bizMoney=".($_POST["mb_vmc_point"]+$_POST["mb_vmp_point"]+$_POST["mb_vmm_point"]).", date=NOW(), way = 'bizMoneyChange'") or die("false");
    
    echo "true";
?>