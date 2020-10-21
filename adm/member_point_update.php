<?php
    include_once('./_common.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');

    $i = 0;
    while(true) {
        $i++;
        if( isset( $_POST['dataN' . $i] ) ) {
            if( $_POST['VMC' . $i] == "" )
                $_POST['VMC' . $i] = 0;
            if( $_POST['VMP' . $i] == "" )
                $_POST['VMP' . $i] = 0;
            if( $_POST['VMM' . $i] == "" )
                $_POST['VMM' . $i] = 0;
            if( $_POST['VMG' . $i] == "" )
                $_POST['VMG' . $i] = 0;
            if( $_POST['bizMoney' . $i] == "" )
                $_POST['bizMoney' . $i] = 0;
            
            if( $_POST['VMC'.$i] === "삭제" || $_POST['VMP'.$i] === "삭제" || $_POST['VMM'.$i] === "삭제" || $_POST['VMG'.$i] === "삭제" || $_POST['bizMoney'.$i] === "삭제" || $_POST['date'.$i] === "삭제" ) {
                $row = mysql_fetch_array(mysql_query("select * from dayPoint where no = {$_POST['dataN' . $i]}"));
                mysql_query("delete from dayPoint where no = {$_POST['dataN' . $i]}");
                
                mysql_query("update g5_member set VMC = VMC - {$row['VMC']}, VMP = VMP - {$row['VMP']}, VMM = VMM - {$row['VMM']}, VMG = VMG - {$row['VMG']}, bizMoney = bizMoney - {$row['bizMoney']} where mb_id = '{$row['mb_id']}'");
            } else {
                $row = mysql_fetch_array(mysql_query("select * from dayPoint where no = {$_POST['dataN' . $i]}"));
                
                mysql_query("update dayPoint set VMC = ".str_replace(',', '', $_POST['VMC'.$i]).", VMP = ".str_replace(',', '', $_POST['VMP'.$i]).", VMM = ".str_replace(',', '', $_POST['VMM'.$i]).", VMG = ".str_replace(',', '', $_POST['VMG'.$i]).", bizMoney = ".str_replace(',', '', $_POST['bizMoney'.$i])." where no = {$_POST['dataN' . $i]}");
                
                $VMC = preg_replace("/[^\d]/","",$_POST['VMC'.$i]);
                $VMP = preg_replace("/[^\d]/","",$_POST['VMP'.$i]);
                $VMM = preg_replace("/[^\d]/","",$_POST['VMM'.$i]);
                $VMG = preg_replace("/[^\d]/","",$_POST['VMG'.$i]);
                $bizMoney = preg_replace("/[^\d]/","",$_POST['bizMoney'.$i]);
                
                $VMC_val; $VMP_val; $VMM_val; $VMG_val; $bizMoney_val;
                if( ! ($row['VMC'] < 0 || $row['VMP'] < 0 || $row['VMM'] < 0 || $row['VMG'] < 0 || $row['bizMoney'] < 0) ) { // 참이면 양수.
                    $VMC_val = preg_replace("/[^\d]/","",$row['VMC']) - $VMC;
                    $VMP_val = preg_replace("/[^\d]/","",$row['VMP']) - $VMP;
                    $VMM_val = preg_replace("/[^\d]/","",$row['VMM']) - $VMM;
                    $VMG_val = preg_replace("/[^\d]/","",$row['VMG']) - $VMG;
                    $bizMoney_val = preg_replace("/[^\d]/","",$row['bizMoney']) - $bizMoney;
                } else { // 거짓이면 음수.
                    $VMC_val = $VMC - preg_replace("/[^\d]/","",$row['VMC']);
                    $VMP_val = $VMP - preg_replace("/[^\d]/","",$row['VMP']);
                    $VMM_val = $VMM - preg_replace("/[^\d]/","",$row['VMM']);
                    $VMG_val = $VMG - preg_replace("/[^\d]/","",$row['VMG']);
                    $bizMoney_val = $bizMoney - preg_replace("/[^\d]/","",$row['bizMoney']);
                }
                mysql_query("update g5_member set VMC = VMC - {$VMC_val}, VMP = VMP - {$VMP_val}, VMM = VMM - {$VMM_val}, VMG = VMG - {$VMG_val}, bizMoney = bizMoney - {$bizMoney_val} where mb_id = '{$row['mb_id']}'");
            }
        } else {
            break;
        }
    }
    
    
    
echo "<script>
    window.onload = function() {
        alert('수정되었습니다.');
        window.location.href = '{$_SERVER["HTTP_REFERER"]}';
    }
</script>";
?>



