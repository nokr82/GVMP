<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . "/myOffice/Classes/PHPExcel.php");
    include_once($_SERVER['DOCUMENT_ROOT'] . "/myOffice/Classes/PHPExcel/IOFactory.php");

    function column_char($i) { return chr( 65 + $i ); }
    
    $time = date("Y년m월d일 h시i분s초");
    
    $headers = array('아이디', 'VM가입일', '상품명', '이름', '전화번호', '주소', '상세주소');
    $rows = array();
    $data = array_merge(array($headers), $rows);



    // 스타일 지정
    $widths = array(20, 20, 60, 20, 20, 60, 60);
    $header_bgcolor = 'FFD5D5D5';

    // 엑셀 생성
    $last_char = column_char( count($headers) - 1 );

    $excel = new PHPExcel();
    $excel->setActiveSheetIndex(0)->getStyle( "A1:G1" )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($header_bgcolor);
    $excel->setActiveSheetIndex(0)->getStyle( "J1:G1" )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFB2CCFF');
    $excel->setActiveSheetIndex(0)->getStyle( "A:$last_char" )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
    
    $excel->setActiveSheetIndex(0)->getStyle( "A1:${last_char}1" )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // 중앙 정렬
    $excel->setActiveSheetIndex(0)->getStyle( "A:$last_char" )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // 중앙 정렬
    
    
    
    
    foreach($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension( column_char($i) )->setWidth($w);
    $excel->getActiveSheet()->fromArray($data,NULL,'A1');
    
    $sql = "SELECT 
    b.mb_id,
    a.date,
    d.it_name,
    c.name,
    c.hp,
    c.address1,
    c.address2
FROM
    dayPoint AS a
        INNER JOIN
    g5_member AS b ON a.mb_id = b.mb_id
        INNER JOIN
    selectedProducts AS c ON b.mb_id = c.mb_id
        INNER JOIN
    g5_shop_item AS d ON c.productNumber = d.it_id
WHERE
    a.date >= '{$_GET['Datepicker1']}'
        AND a.date <= '{$_GET['Datepicker2']}'
        AND (a.way = 'renewal' OR a.way = 'autoRenewal')
        AND a.VMR != 20000";


    
    $result = mysql_query($sql);
    $num = 1;
    while( $row = mysql_fetch_array($result) ) {
        $excel->getActiveSheet()->setCellValueExplicit('A' . ($num+1), "{$row['mb_id']}", PHPExcel_Cell_DataType::TYPE_STRING);
        $excel->getActiveSheet()->setCellValueExplicit('B' . ($num+1), $row['date'], PHPExcel_Cell_DataType::TYPE_STRING); 
        $excel->getActiveSheet()->setCellValueExplicit('C' . ($num+1), $row['it_name'], PHPExcel_Cell_DataType::TYPE_STRING); 
        $excel->getActiveSheet()->setCellValueExplicit('D' . ($num+1), $row['name'], PHPExcel_Cell_DataType::TYPE_STRING);
        $excel->getActiveSheet()->setCellValueExplicit('E' . ($num+1), $row['hp'], PHPExcel_Cell_DataType::TYPE_STRING);
        $excel->getActiveSheet()->setCellValueExplicit('F' . ($num+1), $row['address1'], PHPExcel_Cell_DataType::TYPE_STRING);
        $excel->getActiveSheet()->setCellValueExplicit('G' . ($num+1), $row['address2'], PHPExcel_Cell_DataType::TYPE_STRING);

        
        if( $num % 2 == 0 )
            $excel->setActiveSheetIndex(0)->getStyle( "A" . ($num+1) . ":G" . ($num+1) )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFEAEA');
        $num++;
    }
    
    
    
    $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="신청제품관리.xls"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    
    
    

?>