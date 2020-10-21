<?php
//    include_once('./_common.php');
    include_once('./dbConn.php');
    require_once "./Classes/PHPExcel.php";
    require_once "./Classes/PHPExcel/IOFactory.php";
    
    function column_char($i) { return chr( 65 + $i ); }
    
    $time = date("Y년m월d일 h시i분s초");
    
    
    // 자료 생성
    $headers = array('N', '아이디', '회원명', '계정종류', '직급', '현재 총 포인트', '현재 VMC', '현재 VMR', '현재 VMP', 'VMC(증가)', 'VMR(증가)', 'VMP(증가)', 'VMC(감소)', 'VMR(감소)', 'VMP(감소)');
    $rows = array();
    
    
    
    
    $data = array_merge(array($headers), $rows);

    // 스타일 지정
    $widths = array(4, 10, 12, 10, 15, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14);
    $header_bgcolor = 'FFD5D5D5';

    // 엑셀 생성
    $last_char = column_char( count($headers) - 1 );

    $excel = new PHPExcel();
    $excel->setActiveSheetIndex(0)->getStyle( "A1:I1" )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($header_bgcolor);
    $excel->setActiveSheetIndex(0)->getStyle( "J1:O1" )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFB2CCFF');
    $excel->setActiveSheetIndex(0)->getStyle( "A:$last_char" )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
    
    $excel->setActiveSheetIndex(0)->getStyle( "A1:${last_char}1" )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // 중앙 정렬
    $excel->setActiveSheetIndex(0)->getStyle( "A:$last_char" )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // 중앙 정렬
    
    
    
    
    foreach($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension( column_char($i) )->setWidth($w);
    $excel->getActiveSheet()->fromArray($data,NULL,'A1');
    
    $result = mysql_query("select * from g5_member where mb_id != 'admin' order by mb_no");
    $num = 0;
    while( $row = mysql_fetch_array($result) ) {
        $num++;
        $excel->getActiveSheet()->setCellValueExplicit('A' . ($num+1), $num);
        $excel->getActiveSheet()->setCellValueExplicit('B' . ($num+1), "{$row['mb_id']}", PHPExcel_Cell_DataType::TYPE_STRING); 
        $excel->getActiveSheet()->setCellValueExplicit('C' . ($num+1), $row['mb_name']); 
        $excel->getActiveSheet()->setCellValueExplicit('D' . ($num+1), $row['accountType']);
        $excel->getActiveSheet()->setCellValueExplicit('E' . ($num+1), $row['accountRank']);
        $excel->getActiveSheet()->setCellValueExplicit('F' . ($num+1), ($row['VMC']+$row['VMR']+$row['VMP']+$row['VMM']), PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $excel->getActiveSheet()->setCellValueExplicit('G' . ($num+1), $row['VMC'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $excel->getActiveSheet()->setCellValueExplicit('H' . ($num+1), $row['VMR'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $excel->getActiveSheet()->setCellValueExplicit('I' . ($num+1), $row['VMP'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $excel->getActiveSheet()->setCellValueExplicit('J' . ($num+1), 0, PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $excel->getActiveSheet()->setCellValueExplicit('K' . ($num+1), 0, PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $excel->getActiveSheet()->setCellValueExplicit('L' . ($num+1), 0, PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $excel->getActiveSheet()->setCellValueExplicit('M' . ($num+1), 0, PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $excel->getActiveSheet()->setCellValueExplicit('N' . ($num+1), 0, PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $excel->getActiveSheet()->setCellValueExplicit('O' . ($num+1), 0, PHPExcel_Cell_DataType::TYPE_NUMERIC);
        
        $excel->getActiveSheet()->getStyle('F' . ($num+1))->getNumberFormat()->setFormatCode("#,##0");
        $excel->getActiveSheet()->getStyle('G' . ($num+1))->getNumberFormat()->setFormatCode("#,##0");
        $excel->getActiveSheet()->getStyle('H' . ($num+1))->getNumberFormat()->setFormatCode("#,##0");
        $excel->getActiveSheet()->getStyle('I' . ($num+1))->getNumberFormat()->setFormatCode("#,##0");
        $excel->getActiveSheet()->getStyle('J' . ($num+1))->getNumberFormat()->setFormatCode("#,##0");
        $excel->getActiveSheet()->getStyle('K' . ($num+1))->getNumberFormat()->setFormatCode("#,##0");
        $excel->getActiveSheet()->getStyle('L' . ($num+1))->getNumberFormat()->setFormatCode("#,##0");
        $excel->getActiveSheet()->getStyle('M' . ($num+1))->getNumberFormat()->setFormatCode("#,##0");
        $excel->getActiveSheet()->getStyle('N' . ($num+1))->getNumberFormat()->setFormatCode("#,##0");
        $excel->getActiveSheet()->getStyle('O' . ($num+1))->getNumberFormat()->setFormatCode("#,##0");
    }
    
    
    
    $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="회원포인트('.$time.').xls"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');

?> 