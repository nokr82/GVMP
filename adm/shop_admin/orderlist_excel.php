<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . "/myOffice/Classes/PHPExcel.php");
    include_once($_SERVER['DOCUMENT_ROOT'] . "/myOffice/Classes/PHPExcel/IOFactory.php");
    include_once('./_common.php');

    function column_char($i) { return chr( 65 + $i ); }
    
    $time = date("Y년m월d일 H시i분s초");
    
    
    // 자료 생성
    $headers = array('no', '주문날짜', '주문자', '회원번호', '주문자 연락처', '수취인', '수취인 연락처', '수취인 주소', '회사명', '품목명','옵션명', '수량', '판매액', '총 판매액', '배송비', '총 합계', '적립금', '결제방식', '비고');
    $rows = array();
    $data = array_merge(array($headers), $rows);

    // 스타일 지정
    $widths = array(10,30,13,15,30,13,30,100,40,80,80,10,30,30,30,30,30,30,30);
    $header_bgcolor = 'FFD5D5D5';

    // 엑셀 생성
    $last_char = column_char( count($headers) - 1 );

    $excel = new PHPExcel();
    $excel->setActiveSheetIndex(0)->getStyle( "A1:{$last_char}1" )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($header_bgcolor);
//    $excel->setActiveSheetIndex(0)->getStyle( "J1:F1" )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFB2CCFF');
    $excel->setActiveSheetIndex(0)->getStyle( "A:$last_char" )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
    
    $excel->setActiveSheetIndex(0)->getStyle( "A1:{$last_char}1" )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // 중앙 정렬
    $excel->setActiveSheetIndex(0)->getStyle( "A:$last_char" )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // 중앙 정렬
    
    
    
    
    foreach($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension( column_char($i) )->setWidth($w);
    $excel->getActiveSheet()->fromArray($data,NULL,'A1');
    
    
    
    
    $sql = "SELECT 
                od_id,
                it_id,
                od_time,
                od_name,
                od_tel,
                mb_id,
                od_b_name,
                od_b_tel,
                CONCAT(od_b_addr1, od_b_addr2, od_b_addr3) AS od_b_addr,
                ca_id,
                ca_id2,
                ca_id3,
                it_name,
                ct_option,
                ct_qty,
                ct_price,
                ct_qty * ct_price as ct_price_sum,
                od_settle_case,
                od_send_cost,
                ct_qty * ct_price + od_send_cost as totalMoney,
                od_status
            FROM
                (SELECT 
                    a.*,
                        b.it_id,
                        b.it_name,
                        b.ct_option,
                        c.ca_id,
                        c.ca_id2,
                        c.ca_id3,
                        b.ct_qty,
                        b.ct_price+b.io_price as ct_price,
                        b.it_sc_price
                FROM
                    g5_shop_order AS a
                INNER JOIN g5_shop_cart AS b ON a.od_id = b.od_id
                LEFT JOIN temp_item_shop AS c ON b.it_id = c.it_id) AS a
            WHERE
                od_time between '{$_GET["fr_date"]} 00:00:00' and '{$_GET["to_date"]} 23:59:59'
            ORDER BY od_id";
                
    
    $result = mysql_query($sql);
    $num = 0;
    while( $row = mysql_fetch_array($result) ) {
        $num++;
        
        $row2 = mysql_fetch_array(mysql_query("select * from g5_shop_category where ca_id = '{$row["ca_id3"]}'"));
        if($row2["ca_name"] == "") {
            $row2 = mysql_fetch_array(mysql_query("select * from g5_shop_category where ca_id = '{$row["ca_id2"]}'"));
            if($row2["ca_name"] == "")
                $row2 = mysql_fetch_array(mysql_query("select * from g5_shop_category where ca_id = '{$row["ca_id"]}'"));
        }
        
        
        $excel->getActiveSheet()->setCellValueExplicit('A' . ($num+1), "{$num}", PHPExcel_Cell_DataType::TYPE_STRING);
        $excel->getActiveSheet()->setCellValueExplicit('B' . ($num+1), $row["od_time"]); 
        $excel->getActiveSheet()->setCellValueExplicit('C' . ($num+1), $row['od_name']);
        $excel->getActiveSheet()->setCellValueExplicit('D' . ($num+1), $row['mb_id']);
        $excel->getActiveSheet()->setCellValueExplicit('E' . ($num+1), $row['od_tel']);
        $excel->getActiveSheet()->setCellValueExplicit('F' . ($num+1), $row['od_b_name']);
        $excel->getActiveSheet()->setCellValueExplicit('G' . ($num+1), $row['od_b_tel']);
        $excel->getActiveSheet()->setCellValueExplicit('H' . ($num+1), $row['od_b_addr']);
        $excel->getActiveSheet()->setCellValueExplicit('I' . ($num+1), $row2['ca_name']);
        $excel->getActiveSheet()->setCellValueExplicit('J' . ($num+1), $row['it_name']);
        $excel->getActiveSheet()->setCellValueExplicit('K' . ($num+1), $row['ct_option']);
        $excel->getActiveSheet()->setCellValueExplicit('L' . ($num+1), $row['ct_qty']);
        $excel->getActiveSheet()->setCellValueExplicit('M' . ($num+1), number_format($row['ct_price']), PHPExcel_Cell_DataType::TYPE_STRING);
        $excel->getActiveSheet()->setCellValueExplicit('N' . ($num+1), number_format($row['ct_price_sum']), PHPExcel_Cell_DataType::TYPE_STRING);
        $excel->getActiveSheet()->setCellValueExplicit('O' . ($num+1), number_format(get_item_sendcost($row['it_id'], $row['ct_price_sum'], $row['ct_qty'], $row["od_id"])), PHPExcel_Cell_DataType::TYPE_STRING);
        $excel->getActiveSheet()->setCellValueExplicit('P' . ($num+1), number_format($row['totalMoney']), PHPExcel_Cell_DataType::TYPE_STRING);
        $excel->getActiveSheet()->setCellValueExplicit('Q' . ($num+1), number_format($row['ct_price_sum']*0.05), PHPExcel_Cell_DataType::TYPE_STRING);
        $excel->getActiveSheet()->setCellValueExplicit('R' . ($num+1), $row['od_settle_case']);
        $excel->getActiveSheet()->setCellValueExplicit('S' . ($num+1), $row['od_status']);
        $case = "무료배송";
        if( (int)$row['it_sc_price'] > 0 )
            $case = "유료배송";
        $excel->getActiveSheet()->setCellValueExplicit('R' . ($num+1), $case);


        
        if( $num % 2 == 0 )
            $excel->setActiveSheetIndex(0)->getStyle( "A" . ($num+1) . ":S" . ($num+1) )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFEAEA');
    }
    
    
    
    $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="주문내역('.$time.').xls"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');

?>