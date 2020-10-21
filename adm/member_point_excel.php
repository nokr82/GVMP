<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . "/myOffice/Classes/PHPExcel.php");
    include_once($_SERVER['DOCUMENT_ROOT'] . "/myOffice/Classes/PHPExcel/IOFactory.php");
    include_once ($_SERVER['DOCUMENT_ROOT'].'/myOffice/point_pop_back_func.php');

    function column_char($i) { return chr( 65 + $i ); }
    
    $time = date("Y년m월d일 h시i분s초");
    
    
    // 자료 생성
    $headers = array('아이디', '이름', 'VMC', 'VMP', 'VMM', '금고', 'bizMoney', '내용', '날짜');
    $rows = array();
    
    
    
    
    $data = array_merge(array($headers), $rows);

    // 스타일 지정
    $widths = array(15, 25, 12, 12, 12, 12, 12, 30, 15);
    $header_bgcolor = 'FFD5D5D5';

    // 엑셀 생성
    $last_char = column_char( count($headers) - 1 );

    $excel = new PHPExcel();
    $excel->setActiveSheetIndex(0)->getStyle( "A1:I1" )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($header_bgcolor);
    $excel->setActiveSheetIndex(0)->getStyle( "J1:I1" )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFB2CCFF');
    $excel->setActiveSheetIndex(0)->getStyle( "A:$last_char" )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
    
    $excel->setActiveSheetIndex(0)->getStyle( "A1:${last_char}1" )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // 중앙 정렬
    $excel->setActiveSheetIndex(0)->getStyle( "A:$last_char" )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // 중앙 정렬
    
    
    
    
    foreach($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension( column_char($i) )->setWidth($w);
    $excel->getActiveSheet()->fromArray($data,NULL,'A1');
    
    $sql = "select DP.*, MB.mb_name from dayPoint as DP inner join g5_member as MB on DP.mb_id = MB.mb_id ";
    
    if( $_GET['sfl'] == 'mb_name' ) {
        $sql .= "where MB.mb_name = '{$_GET['stx']}'";
    } else if( $_GET['sfl'] == 'mb_id' ) {
        $sql .= "where MB.mb_id = '{$_GET['stx']}'";
    } else if( $_GET['sfl'] == 'no' ){
        $sql .= "where ";
    }

    if( $_GET['sfl'] != 'no' && $_GET['fr_date'] != "" && $_GET['la_date'] != "" ) {
        $sql .= " and ";
    }

    if( $_GET['fr_date'] != "" && $_GET['la_date'] != "" ) {
        $sql .= " date >= '{$_GET['fr_date']} 00:00:00' and date <= '{$_GET['la_date']} 23:59:59' ";
    }
    
    $sql .= "order by date desc";
    $result = mysql_query($sql);
    $num = 0;
    while( $row = mysql_fetch_array($result) ) {
        if( $row['VMC']+$row['VMP']+$row['VMM']+$row['VMG']+$row['bizMoney'] == 0 ) {
            continue;
        }
        
        $num++;
        $excel->getActiveSheet()->setCellValueExplicit('A' . ($num+1), "{$row['mb_id']}", PHPExcel_Cell_DataType::TYPE_STRING);
        $excel->getActiveSheet()->setCellValueExplicit('B' . ($num+1), $row['mb_name']); 
        $excel->getActiveSheet()->setCellValueExplicit('C' . ($num+1), $row['VMC'], PHPExcel_Cell_DataType::TYPE_NUMERIC); 
        $excel->getActiveSheet()->setCellValueExplicit('D' . ($num+1), $row['VMP'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $excel->getActiveSheet()->setCellValueExplicit('E' . ($num+1), $row['VMM'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $excel->getActiveSheet()->setCellValueExplicit('F' . ($num+1), $row['VMG'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $excel->getActiveSheet()->setCellValueExplicit('G' . ($num+1), $row['bizMoney'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $excel->getActiveSheet()->setCellValueExplicit('H' . ($num+1), parsing($row['way']), PHPExcel_Cell_DataType::TYPE_STRING);
        $excel->getActiveSheet()->setCellValueExplicit('I' . ($num+1), $row['date']);
        
        $excel->getActiveSheet()->getStyle('C' . ($num+1))->getNumberFormat()->setFormatCode("#,##0");
        $excel->getActiveSheet()->getStyle('D' . ($num+1))->getNumberFormat()->setFormatCode("#,##0");
        $excel->getActiveSheet()->getStyle('E' . ($num+1))->getNumberFormat()->setFormatCode("#,##0");
        $excel->getActiveSheet()->getStyle('F' . ($num+1))->getNumberFormat()->setFormatCode("#,##0");
        $excel->getActiveSheet()->getStyle('G' . ($num+1))->getNumberFormat()->setFormatCode("#,##0");
        
        if( $num % 2 == 0 )
            $excel->setActiveSheetIndex(0)->getStyle( "A" . ($num+1) . ":I" . ($num+1) )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFEAEA');
    }
    
    
    
    $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="포인트 지급 내역('.$time.').xls"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');

?>