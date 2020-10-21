<?php
    include_once ('./_common.php');
    include_once ('./dbConn.php');
    include_once ('./point_pop_back_func.php');
    
    
    if( isset($_POST['calendarType']) && isset($_POST['inputType']) && isset($_POST['mb_id']) && isset($_POST['date']) ) {
        $inputType;
        switch( $_POST['inputType'] ) {
            case "1" :
                $inputType = "VMC"; break;
            case "2" :
                $inputType = "VMP"; break;
            case "5" :
                $inputType = "VMG"; break;
            case "3" :
                $inputType = "VMM"; break;
            case "4" :
                $inputType = "bizMoney"; break;
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
            array_push( $resultQ, array( $row[$inputType], parsing($row['way']) ) );
        }

        echo json_encode($resultQ); 
    }


    
    
?>