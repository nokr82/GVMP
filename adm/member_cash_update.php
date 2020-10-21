<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');

    
    $timenow = date("Y-m-d"); 
    $timetarget = $_POST['dateS'];

    $str_now = strtotime($timenow);
    $str_target = strtotime($timetarget);


    if($str_now >= $str_target) {
        echo "<script>alert('신청기간이 지난 경우에는 수정할 수 없습니다.'); window.location = '{$_SERVER["HTTP_REFERER"]}';</script>";
        exit();
    }
    
    

    
    
    $i = 0;
    while( true ) {
        $i++;
        
        if( isset($_POST['cash_id_' . $i]) && isset($_POST['sell1_' . $i]) && isset($_POST['sell2_' . $i]) ) {
            
            
            $result = mysql_query("select * from calculateTBL where mb_id = '{$_POST['cash_id_' . $i]}' and settlementDate = '{$_POST['dateS']}'");
            $row = mysql_fetch_array( $result );
            
            $diffVMC = $row['VMC'] - $_POST['sell1_' . $i];
            $diffVMP = $row['VMP'] - $_POST['sell2_' . $i];

            
            if( $_POST['sell1_' . $i] == "0" && $_POST['sell2_' . $i] == "0" ) {
                mysql_query("delete from calculateTBL where mb_id = '{$_POST['cash_id_' . $i]}' and settlementDate = '{$_POST['dateS']}'");
                mysql_query("delete from dayPoint where mb_id = '{$_POST['cash_id_' . $i]}' and date like '".date("Y-m-d", strtotime($row['applicationDate']))."%' and way = 'calculate'");
            } else {
                mysql_query("update calculateTBL set VMC = {$_POST['sell1_' . $i]}, VMP = {$_POST['sell2_' . $i]} where mb_id = '{$_POST['cash_id_' . $i]}' and settlementDate = '{$_POST['dateS']}'");
                mysql_query("update dayPoint set VMC = -{$_POST['sell1_' . $i]}, VMP = -{$_POST['sell2_' . $i]} where mb_id = '{$_POST['cash_id_' . $i]}' and date = '".date("Y-m-d", strtotime($row['applicationDate']))."' and way = 'calculate'");
            }
            mysql_query("update g5_member set VMC = VMC + {$diffVMC}, VMP = VMP + {$diffVMP} where mb_id = '{$_POST['cash_id_' . $i]}'");

            
        } else {
            break;
        }
        
    }
    
    echo "<script>alert('정산 수정이 완료되었습니다.'); window.location = '{$_SERVER["HTTP_REFERER"]}';</script>";
    
?>

