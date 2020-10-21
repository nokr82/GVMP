<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . "/myOffice/Classes/PHPExcel.php");
    include_once($_SERVER['DOCUMENT_ROOT'] . "/myOffice/Classes/PHPExcel/IOFactory.php");

    function column_char($i) { return chr( 65 + $i ); }
    
    $time = date("Y년m월d일 h시i분s초");
    
    
    // 자료 생성
    $headers = array('상품명', '주문서번호', '주민일시', '회원이름(ID)', '주문/판매/광고', '커미션');
    $rows = array();
    $data = array_merge(array($headers), $rows);

    // 스타일 지정
    $widths = array(60, 15, 15, 20, 12, 12);
    $header_bgcolor = 'FFD5D5D5';

    // 엑셀 생성
    $last_char = column_char( count($headers) - 1 );

    $excel = new PHPExcel();
    $excel->setActiveSheetIndex(0)->getStyle( "A1:F1" )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($header_bgcolor);
    $excel->setActiveSheetIndex(0)->getStyle( "J1:F1" )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFB2CCFF');
    $excel->setActiveSheetIndex(0)->getStyle( "A:$last_char" )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
    
    $excel->setActiveSheetIndex(0)->getStyle( "A1:${last_char}1" )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // 중앙 정렬
    $excel->setActiveSheetIndex(0)->getStyle( "A:$last_char" )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // 중앙 정렬
    
    
    
    
    foreach($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension( column_char($i) )->setWidth($w);
    $excel->getActiveSheet()->fromArray($data,NULL,'A1');
    
    $sql = "select OL.*, GM.mb_name from orderList as OL inner join g5_member as GM on OL.mb_id = GM.mb_id ";
                
    if( $_GET['sfl'] == 'mb_name' ) {
        $sql .= "where GM.mb_name = '{$_GET['stx']}'";
    } else if( $_GET['sfl'] == 'mb_id' ) {
        $sql .= "where GM.mb_id = '{$_GET['stx']}'";
    } else if( $_GET['sfl'] == 'productName' ){
        $sql .= "where OL.productName = '{$_GET['stx']}'";
    } else if( $_GET['sfl'] == 'no' ){
        $sql .= "where ";
    }

    if( $_GET['sfl'] != 'no' && $_GET['fr_date'] != "" && $_GET['la_date'] != "" ) {
        $sql .= " and ";
    }

    if( $_GET['fr_date'] != "" && $_GET['la_date'] != "" ) {
        $sql .= " orderDate >= '{$_GET['fr_date']}' and orderDate <= '{$_GET['la_date']}' ";
    }



    $sql .= "order by orderDate desc";
    $result = mysql_query($sql);
    $num = 0;
    while( $row = mysql_fetch_array($result) ) {
        $num++;
        $excel->getActiveSheet()->setCellValueExplicit('A' . ($num+1), "{$row['productName']}", PHPExcel_Cell_DataType::TYPE_STRING);
        $excel->getActiveSheet()->setCellValueExplicit('B' . ($num+1), $row['n']); 
        $excel->getActiveSheet()->setCellValueExplicit('C' . ($num+1), $row['orderDate']); 
        $excel->getActiveSheet()->setCellValueExplicit('D' . ($num+1), $row['mb_name'] . "({$row['mb_id']})", PHPExcel_Cell_DataType::TYPE_STRING);
        $excel->getActiveSheet()->setCellValueExplicit('E' . ($num+1), number_format($row['money']), PHPExcel_Cell_DataType::TYPE_STRING);
        $excel->getActiveSheet()->setCellValueExplicit('F' . ($num+1), number_format($row['commission']), PHPExcel_Cell_DataType::TYPE_STRING);
        
        $excel->getActiveSheet()->getStyle('E' . ($num+1))->getNumberFormat()->setFormatCode("#,##0");
        $excel->getActiveSheet()->getStyle('F' . ($num+1))->getNumberFormat()->setFormatCode("#,##0");
        
        if( $num % 2 == 0 )
            $excel->setActiveSheetIndex(0)->getStyle( "A" . ($num+1) . ":F" . ($num+1) )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFEAEA');
    }
    
    
    
    $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="판매구매 전체 내역('.$time.').xls"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');

?>