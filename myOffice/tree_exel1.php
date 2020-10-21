<?php
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '2048M');
set_time_limit(0);
include_once('./dbConn.php');
require_once "./Classes/PHPExcel.php";
require_once "./Classes/PHPExcel/IOFactory.php";
include_once('./dbConn2.php');



$mb_id = $_POST["mb_id"];

if ($mb_id == 'admin') {
    $mb_id = '00000001';
}


$result = mysql_query("select * from teamMembers where mb_id = '{$mb_id}'");
$row = mysql_fetch_array($result);
$mb_info_1T = [];
$mb_info_2T = [];
$time = date("Y년m월d일 h시i분s초");

$today = date("Y-m-d");


if ($row['1T_ID'] != null) {
    $result_1T = mysql_query("select * from genealogy as a inner join g5_member as b on a.mb_id=b.mb_id where a.mb_id='{$row['1T_ID']}'");
    $row_1T = mysql_fetch_array($result_1T);
    $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row_1T['renewal']}', interval +4 month) AS date"));
    $timestamp = $dateCheck_1["date"];

    array_push($mb_info_1T, array($row_1T['mb_name'] . "(" . $row_1T['mb_id'] . ")", $row_1T['recommenderName'], $row_1T['mb_hp'], $row_1T['mb_email'], $row_1T['accountRank'], $timestamp));

    if (mysqli_multi_query($connection, "CALL SP_TREE('{$row['1T_ID']}');SELECT * FROM genealogy_tree WHERE rootid = '{$row['1T_ID']}' order by lv")) {
        mysqli_store_result($connection);
        mysqli_next_result($connection);
        do {
            /* store first result set */
            if ($result = mysqli_store_result($connection)) {
                while ($row2 = mysqli_fetch_array($result)) {
                    $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row2['renewal']}', interval +4 month) AS date"));
                    $timestamp = $dateCheck_1["date"];
                    array_push($mb_info_1T, array($row2['mb_name'] . "(" . $row2['mb_id'] . ")", $row2['recommenderName'], $row2['mb_hp'], $row2['mb_email'], $row2['accountRank'], $timestamp));
                }
                mysqli_free_result($result);
            }
        } while (mysqli_next_result($connection));
    } else {
        echo mysqli_error($connection);
    }
}

if ($row['2T_ID']) {
    $result_2T = mysql_query("select * from genealogy as a inner join g5_member as b on a.mb_id=b.mb_id where a.mb_id='{$row['2T_ID']}'");
    $row_2T = mysql_fetch_array($result_2T);

    $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row_2T['renewal']}', interval +4 month) AS date"));
    $timestamp = $dateCheck_1["date"];
    array_push($mb_info_2T, array($row_2T['mb_name'] . "(" . $row_2T['mb_id'] . ")", $row_2T['recommenderName'], $row_2T['mb_hp'], $row_2T['mb_email'], $row_2T['accountRank'], $timestamp));

    if (mysqli_multi_query($connection, "CALL SP_TREE('{$row['2T_ID']}');SELECT * FROM genealogy_tree WHERE rootid = '{$row['2T_ID']}' order by lv")) {
        mysqli_store_result($connection);
        mysqli_next_result($connection);
        do {
            /* store first result set */
            if ($result = mysqli_store_result($connection)) {
                while ($row3 = mysqli_fetch_array($result)) {
                    $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row3['renewal']}', interval +4 month) AS date"));
                    $timestamp = $dateCheck_1["date"];
                    array_push($mb_info_2T, array($row3['mb_name'] . "(" . $row3['mb_id'] . ")", $row3['recommenderName'], $row3['mb_hp'], $row3['mb_email'], $row3['accountRank'], $timestamp));
                }
                mysqli_free_result($result);
            }
        } while (mysqli_next_result($connection));
    }

}

function column_char($i)
{
    return chr(65 + $i);
}

$time = date("Y년m월d일 h시i분s초");

// 자료 생성
$headers = array('이름', '추천인', '휴대폰', '이메일', '직급', '리뉴얼');
$rows = array();


$data = array_merge(array($headers), $rows);

// 스타일 지정
$widths = array(50, 30, 30, 50, 30, 50);
$header_bgcolor = 'FFD5D5D5';

// 엑셀 생성
$last_char = column_char(count($headers) - 1);

$excel = new PHPExcel();




$excel->setActiveSheetIndex(0)->getStyle("A1:{$last_char}1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($header_bgcolor);
$excel->setActiveSheetIndex(0)->getStyle("A:$last_char")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);

$excel->setActiveSheetIndex(0)->getStyle("A1:{$last_char}1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // 중앙 정렬
$excel->setActiveSheetIndex(0)->getStyle("A:$last_char")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // 중앙 정렬


foreach ($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension(column_char($i))->setWidth($w);
$excel->getActiveSheet()->fromArray($data, NULL, 'A1');

$num = 1;
$excel->getActiveSheet()->setCellValueExplicit('A' . $num, '이름', PHPExcel_Cell_DataType::TYPE_STRING);
$excel->getActiveSheet()->setCellValueExplicit('B' . $num, '추천인', PHPExcel_Cell_DataType::TYPE_STRING);
$excel->getActiveSheet()->setCellValueExplicit('C' . $num, '휴대폰', PHPExcel_Cell_DataType::TYPE_STRING);
$excel->getActiveSheet()->setCellValueExplicit('D' . $num, '이메일', PHPExcel_Cell_DataType::TYPE_STRING);
$excel->getActiveSheet()->setCellValueExplicit('E' . $num, '직급', PHPExcel_Cell_DataType::TYPE_STRING);
$excel->getActiveSheet()->setCellValueExplicit('F' . $num, '리뉴얼', PHPExcel_Cell_DataType::TYPE_STRING);


foreach ($mb_info_1T as $value) {

    $excel->getActiveSheet()->setCellValueExplicit('A' . ($num + 1), $value[0], PHPExcel_Cell_DataType::TYPE_STRING);
    $excel->getActiveSheet()->setCellValueExplicit('B' . ($num + 1), $value[1], PHPExcel_Cell_DataType::TYPE_STRING);
    $excel->getActiveSheet()->setCellValueExplicit('C' . ($num + 1), $value[2], PHPExcel_Cell_DataType::TYPE_STRING);
    $excel->getActiveSheet()->setCellValueExplicit('D' . ($num + 1), $value[3], PHPExcel_Cell_DataType::TYPE_STRING);
    $excel->getActiveSheet()->setCellValueExplicit('E' . ($num + 1), $value[4], PHPExcel_Cell_DataType::TYPE_STRING);
    if ($value[5] < $today) {
        $excel->getActiveSheet()->getStyle("F")->getFont()->getColor()->setARGB("FFFF0000");
        $excel->getActiveSheet()->setCellValueExplicit('F' . ($num + 1), $value[5], PHPExcel_Cell_DataType::TYPE_STRING);
    } else {
        $excel->getActiveSheet()->getStyle("F")->getFont()->getColor()->setARGB("00000000");
        $excel->getActiveSheet()->setCellValueExplicit('F' . ($num + 1), $value[5], PHPExcel_Cell_DataType::TYPE_STRING);
    }


    if ($num % 2 == 0)
        $excel->setActiveSheetIndex(0)->getStyle("A" . ($num + 1) . ":F" . ($num + 1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFEAEA');
    $num++;
}

foreach ($mb_info_2T as $value2) {

    $excel->getActiveSheet()->setCellValueExplicit('A' . ($num + 1), $value2[0], PHPExcel_Cell_DataType::TYPE_STRING);
    $excel->getActiveSheet()->setCellValueExplicit('B' . ($num + 1), $value2[1], PHPExcel_Cell_DataType::TYPE_STRING);
    $excel->getActiveSheet()->setCellValueExplicit('C' . ($num + 1), $value2[2], PHPExcel_Cell_DataType::TYPE_STRING);
    $excel->getActiveSheet()->setCellValueExplicit('D' . ($num + 1), $value2[3], PHPExcel_Cell_DataType::TYPE_STRING);
    $excel->getActiveSheet()->setCellValueExplicit('E' . ($num + 1), $value2[4], PHPExcel_Cell_DataType::TYPE_STRING);
    if ($value2[5] < $today) {
        $excel->getActiveSheet()->getStyle("F")->getFont()->getColor()->setARGB("FFFF0000");
        $excel->getActiveSheet()->setCellValueExplicit('F' . ($num + 1), $value2[5], PHPExcel_Cell_DataType::TYPE_STRING);
    } else {
        $excel->getActiveSheet()->getStyle("F")->getFont()->getColor()->setARGB("00000000");
        $excel->getActiveSheet()->setCellValueExplicit('F' . ($num + 1), $value2[5], PHPExcel_Cell_DataType::TYPE_STRING);
    }

    if ($num % 2 == 0)
        $excel->setActiveSheetIndex(0)->getStyle("A" . ($num + 1) . ":F" . ($num + 1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFEAEA');
    $num++;
}

setcookie('fileDownloadToken','TRUE');

header('Content-Disposition: attachment;filename=""산하회원정보-리스트형(' . $time . ').xlsx"');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
exit();
?>

