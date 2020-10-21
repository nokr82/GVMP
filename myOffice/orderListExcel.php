<?php
    include_once('./_common.php');
    include_once('./dbConn.php');
    require_once "./Classes/PHPExcel.php";
    require_once "./Classes/PHPExcel/IOFactory.php";
    
    function column_char($i) { return chr( 65 + $i ); }
    
    $time = date("Y년m월d일 h시i분s초");
    
    // 자료 생성
    $headers = array( '상품명', '주문일시', '회원ID', '금액', '커미션');
    $rows = array();
    
    
    
    
    $data = array_merge(array($headers), $rows);

    // 스타일 지정
    $widths = array(50, 20, 10, 12, 12);
    $header_bgcolor = 'FFD5D5D5';

    // 엑셀 생성
    $last_char = column_char( count($headers) - 1 );

    $excel = new PHPExcel();
    $excel->setActiveSheetIndex(0)->getStyle( "A1:${last_char}1" )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($header_bgcolor);
    $excel->setActiveSheetIndex(0)->getStyle( "A:$last_char" )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
    
    $excel->setActiveSheetIndex(0)->getStyle( "A1:${last_char}1" )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // 중앙 정렬
    $excel->setActiveSheetIndex(0)->getStyle( "A:$last_char" )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // 중앙 정렬
    
    
    
    
    foreach($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension( column_char($i) )->setWidth($w);
    $excel->getActiveSheet()->fromArray($data,NULL,'A1');
    
    
    $excel->getActiveSheet()->setCellValueExplicit('A2', '안마의자 방문 상담(광고)' );
    $excel->getActiveSheet()->setCellValueExplicit('B2', '2018-04-08' );
    $excel->getActiveSheet()->setCellValueExplicit('C2', '00000001', PHPExcel_Cell_DataType::TYPE_STRING );
    $excel->getActiveSheet()->setCellValueExplicit('D2', 0, PHPExcel_Cell_DataType::TYPE_NUMERIC );
    $excel->getActiveSheet()->setCellValueExplicit('E2', 50000, PHPExcel_Cell_DataType::TYPE_NUMERIC );
    
    $excel->getActiveSheet()->setCellValueExplicit('A3', '보온 물병(판매)' );
    $excel->getActiveSheet()->setCellValueExplicit('B3', '2018-04-08' );
    $excel->getActiveSheet()->setCellValueExplicit('C3', '00000123', PHPExcel_Cell_DataType::TYPE_STRING );
    $excel->getActiveSheet()->setCellValueExplicit('D3', 200000, PHPExcel_Cell_DataType::TYPE_NUMERIC );
    $excel->getActiveSheet()->setCellValueExplicit('E3', 2000, PHPExcel_Cell_DataType::TYPE_NUMERIC );


    $excel->getActiveSheet()->getStyle('C2')->getNumberFormat()->setFormatCode("#,##0");
    $excel->getActiveSheet()->getStyle('D2')->getNumberFormat()->setFormatCode("#,##0");
    $excel->getActiveSheet()->getStyle('E2')->getNumberFormat()->setFormatCode("#,##0");
    
    $excel->getActiveSheet()->getStyle('C3')->getNumberFormat()->setFormatCode("#,##0");
    $excel->getActiveSheet()->getStyle('D3')->getNumberFormat()->setFormatCode("#,##0");
    $excel->getActiveSheet()->getStyle('E3')->getNumberFormat()->setFormatCode("#,##0");
    
    $excel->getActiveSheet()->getStyle('C2')->getNumberFormat()->setFormatCode("#,##0");
    $excel->getActiveSheet()->getStyle('D2')->getNumberFormat()->setFormatCode("#,##0");
    $excel->getActiveSheet()->getStyle('E2')->getNumberFormat()->setFormatCode("#,##0");
    
    
    $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="주문판매내역('.$time.').xls"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
?>