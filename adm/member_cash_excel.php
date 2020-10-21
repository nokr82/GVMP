<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/myOffice/Classes/PHPExcel.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/myOffice/Classes/PHPExcel/IOFactory.php");
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn2.php');
function column_char($i)
{
    return chr(65 + $i);
}

$time = date("Y년m월d일 h시i분s초");


$data = array();

// 스타일 지정
$widths = array(30, 30, 30, 20, 10, 10, 30, 30, 30, 30, 30, 30);
$header_bgcolor = 'FFD5D5D5';

// 엑셀 생성
$last_char = "M";

$excel = new PHPExcel();
$excel->setActiveSheetIndex(0)->getStyle("A1:F1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($header_bgcolor);
$excel->setActiveSheetIndex(0)->getStyle("J1:F1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFB2CCFF');
$excel->setActiveSheetIndex(0)->getStyle("A:$last_char")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);

$excel->setActiveSheetIndex(0)->getStyle("A1:${last_char}1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // 중앙 정렬
$excel->setActiveSheetIndex(0)->getStyle("A:$last_char")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // 중앙 정렬


foreach ($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension(column_char($i))->setWidth($w);
$excel->getActiveSheet()->fromArray($data, NULL, 'A1');

$where = "";
if ($_GET['cash_id'] != '') {
    $where .= "and (a.mb_id = '{$_GET['cash_id']}'";
    if (mysqli_multi_query($connection, "CALL SP_TREE('{$_GET['cash_id']}');SELECT * FROM genealogy_tree WHERE rootid = '{$_GET['cash_id']}'")) {
        mysqli_store_result($connection);
        mysqli_next_result($connection);
        do {
            /* store first result set */
            if ($result = mysqli_store_result($connection)) {

                while ($row2 = mysqli_fetch_array($result)) {
                    $where .= " or a.mb_id = '{$row2['mb_id']}'";
                    $cnt = 0;
                    $cnt++;
                }
                mysqli_free_result($result);
            }
        } while (mysqli_next_result($connection));
    } else {
        echo mysqli_error($connection);
    }
    $where .= ")";

}


$sql = "select a.*, b.bankName, b.accountNumber, b.accountHolder, b.mb_name, b.accountRank, c.mb_name as mb_name2, c.RRN_F, c.RRN_B
 from calculateTBL as a inner join g5_member as b on a.mb_id = b.mb_id inner join memberRRNTBL as c on a.mb_id = c.mb_id
  where a.settlementDate = '{$_GET['dateS']}' {$where} order by (a.VMC+a.VMP) desc";


$result = mysql_query($sql);
$num = 0;
while ($row = mysql_fetch_array($result)) {
    $total = ($row['VMC'] + $row['VMP']);
    $total = $total * 0.967;
    $total = floor($total / 100) * 100;


    $excel->getActiveSheet()->setCellValueExplicit('A' . ($num + 1), "{$row['bankName']}", PHPExcel_Cell_DataType::TYPE_STRING);
    $excel->getActiveSheet()->setCellValueExplicit('B' . ($num + 1), $row['accountNumber'], PHPExcel_Cell_DataType::TYPE_STRING);
    $excel->getActiveSheet()->setCellValueExplicit('C' . ($num + 1), (int)$total);
    $excel->getActiveSheet()->setCellValueExplicit('D' . ($num + 1), $row['accountHolder'], PHPExcel_Cell_DataType::TYPE_STRING);
    $excel->getActiveSheet()->setCellValueExplicit('E' . ($num + 1), "", PHPExcel_Cell_DataType::TYPE_STRING);
    $excel->getActiveSheet()->setCellValueExplicit('F' . ($num + 1), "", PHPExcel_Cell_DataType::TYPE_STRING);
    $excel->getActiveSheet()->setCellValueExplicit('G' . ($num + 1), "브이엠피_{$_GET['cash_month']}월{$_GET['cash_b']}분기정산", PHPExcel_Cell_DataType::TYPE_STRING);
    $excel->getActiveSheet()->setCellValueExplicit('H' . ($num + 1), $row['mb_id'], PHPExcel_Cell_DataType::TYPE_STRING);
    $excel->getActiveSheet()->setCellValueExplicit('I' . ($num + 1), $row['mb_name2'], PHPExcel_Cell_DataType::TYPE_STRING);
    $excel->getActiveSheet()->setCellValueExplicit('J' . ($num + 1), "{$row['RRN_F']}-{$row['RRN_B']}", PHPExcel_Cell_DataType::TYPE_STRING);
    $excel->getActiveSheet()->setCellValueExplicit('K' . ($num + 1), ($row['VMC'] + $row['VMP']), PHPExcel_Cell_DataType::TYPE_STRING);
    $excel->getActiveSheet()->setCellValueExplicit('L' . ($num + 1), ($row['accountRank']), PHPExcel_Cell_DataType::TYPE_STRING);
    $excel->getActiveSheet()->setCellValueExplicit('M' . ($num + 1), ($row['mb_name']), PHPExcel_Cell_DataType::TYPE_STRING);


    if ($num % 2 == 0)
        $excel->setActiveSheetIndex(0)->getStyle("A" . ($num + 1) . ":H" . ($num + 1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFEAEA');
    $num++;
}


$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="우리은행정산(' . $_GET['dateS'] . ').xls"');
header('Cache-Control: max-age=0');

$writer->save('php://output');


?>