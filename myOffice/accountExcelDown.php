<?php
    include_once('./dbConn.php');
    include_once("./Classes/PHPExcel.php");
    include_once("./Classes/PHPExcel/IOFactory.php");
    
    function column_char($i) { return chr( 65 + $i ); }
    
    $time = date("Y년m월d일 h시i분s초");
    
    
    // 자료 생성
    $headers = array('아이디', '이름', '은행명', '계좌번호', '예금주');
    $rows = array();
    
    
    
    
    $data = array_merge(array($headers), $rows);

    // 스타일 지정
    $widths = array(15, 15, 12, 15, 15);
    $header_bgcolor = 'FFD5D5D5';

    // 엑셀 생성
    $last_char = column_char( count($headers) - 1 );

    $excel = new PHPExcel();
    $excel->setActiveSheetIndex(0)->getStyle( "A1:E1" )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($header_bgcolor);
    $excel->setActiveSheetIndex(0)->getStyle( "J1:E1" )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFB2CCFF');
    $excel->setActiveSheetIndex(0)->getStyle( "A:$last_char" )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
    
    $excel->setActiveSheetIndex(0)->getStyle( "A1:${last_char}1" )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // 중앙 정렬
    $excel->setActiveSheetIndex(0)->getStyle( "A:$last_char" )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // 중앙 정렬
    
    
    
    
    foreach($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension( column_char($i) )->setWidth($w);
    $excel->getActiveSheet()->fromArray($data,NULL,'A1');
    
    $sql = "select mb_name as '이름', mb_id as '아이디', bankName as '은행명', accountNumber as '계좌번호', accountHolder as '예금주' from g5_member where mb_id != 'admin'";
    $result = mysql_query($sql);
    
    $num = 0;
    while( $row = mysql_fetch_array($result) ) {
        $num++;
        $excel->getActiveSheet()->setCellValueExplicit('A' . ($num+1), "{$row['이름']}", PHPExcel_Cell_DataType::TYPE_STRING);
        $excel->getActiveSheet()->setCellValueExplicit('B' . ($num+1), $row['아이디'], PHPExcel_Cell_DataType::TYPE_STRING); 
        $excel->getActiveSheet()->setCellValueExplicit('C' . ($num+1), $row['은행명'], PHPExcel_Cell_DataType::TYPE_STRING); 
        $excel->getActiveSheet()->setCellValueExplicit('D' . ($num+1), $row['계좌번호'], PHPExcel_Cell_DataType::TYPE_STRING);
        $excel->getActiveSheet()->setCellValueExplicit('E' . ($num+1), $row['예금주'], PHPExcel_Cell_DataType::TYPE_STRING);
        
        if( $num % 2 == 0 )
            $excel->setActiveSheetIndex(0)->getStyle( "A" . ($num+1) . ":E" . ($num+1) )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFEAEA');
    }
    
    
    
    $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="회원 정보 전체 계좌('.$time.').xls"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    
    
?>