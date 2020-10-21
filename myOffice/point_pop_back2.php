<?php
    $cssCheck = false; // _common.php에 인클루드 되는 CSS 적용 안 되게 하기 위한 변수
    include_once ('./_common.php');
    include_once ('./dbConn.php');
    include_once ('./point_pop_back2_func.php');
    
    
    if( isset($_POST['calendarType']) && isset($_POST['inputType']) && isset($_POST['mb_id']) && isset($_POST['date']) ) {
        $inputType;
        switch( $_POST['inputType'] ) {
            case "1" :
                $inputType = "VCash"; break;
            case "2" :
                $inputType = "VPay"; break;
        }

        $where;
        switch( $_POST['calendarType'] ) {
            case "1" :
                $where = "and ({$inputType} > 0 or {$inputType} < 0)"; break;
            case "2" :
                $where = "and {$inputType} > 0"; break;
            case "3" :
                $where = "and {$inputType} < 0"; break;
        }
        $sql = "select {$inputType}, way from dayPoint where mb_id = '{$_POST['mb_id']}' and date like '{$_POST['date']}%' {$where}";

        $result = mysql_query($sql);
        
        $resultQ = array();
        while( $row = mysql_fetch_array( $result ) ) {
            array_push( $resultQ, array( $row[$inputType], parsing2($row['way']) ) );
        }

        echo json_encode($resultQ); 
    }


    
?>