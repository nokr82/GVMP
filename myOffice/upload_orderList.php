<?php
include_once('./_common.php');
include_once('./dbConn.php');
require_once "./Classes/PHPExcel.php";
require_once "./Classes/PHPExcel/IOFactory.php";
require_once 'excel_reader2.php';

// 설정
$uploads_dir = '/var/www/html/gvmp/up/order';
$allowed_ext = array('xls');

$time = date("Y년m월d일 h시i분s초");

// 변수 정리
$error = $_FILES['myfile']['error'];
$name = $_FILES['myfile']['name'];
$ext = array_pop(explode('.', $name));

// 오류 확인
if( $error != UPLOAD_ERR_OK ) {
	switch( $error ) {
		case UPLOAD_ERR_INI_SIZE:
		case UPLOAD_ERR_FORM_SIZE:
			echo "파일이 너무 큽니다. ($error)";
			break;
		case UPLOAD_ERR_NO_FILE:
			echo "파일이 첨부되지 않았습니다. ($error)";
			break;
		default:
			echo "파일이 제대로 업로드되지 않았습니다. ($error)";
	}
	exit;
}
 
// 확장자 확인
if( !in_array($ext, $allowed_ext) ) {
	echo "허용되지 않는 확장자입니다.";
	exit;
}
 
// 파일 이동
move_uploaded_file( $_FILES['myfile']['tmp_name'], "{$uploads_dir}/{$time}_{$name}");


        
        

// 업로드한 파일 DB 처리
    $data = new Spreadsheet_Excel_Reader("../up/order/{$time}_{$name}");
    
    // 엑셀 첫번째 sheet 행 개수
    $rowcount = $data->rowcount($sheet_index=0); 

    for($i=2 ;$i<=$rowcount; $i++){
        //val(행,열)
        $orderName    = $data->val($i,1); // 상품명
        $orderDate = $data->val($i,2); // 주문일시
        $mb_id = $data->val($i,3); // 회원ID
        $money = preg_replace("/[^\d]/","",$data->val($i,4)); // 금액
        $commission = preg_replace("/[^\d]/","",$data->val($i,5)); // 커미션

        if( $orderName == "" )
            break;

        echo $orderName . " | " . $orderDate . " | ". $mb_id . " | ". $money . " | ". $commission . "<br>";
        
        // 주문리스트 테이블에 DB 넣기
        mysql_query("insert into orderList values(null, '{$orderName}', '{$orderDate}', '{$mb_id}', {$money}, {$commission} )");
        
        // 데일리 포인트 테이블에 DB 넣기
        mysql_query("insert into dayPoint(mb_id,VMC, VMR, VMP,date,way) value ('{$mb_id}', 0, 0, {$commission}, '{$orderDate}', 'orderListAdmin')");
        
        mysql_query("update g5_member set VMP = VMP+{$commission} where mb_id = '{$mb_id}'");
        
        
        
        
    
    } 
    alert("업로드가 완료 됐습니다.");

?>