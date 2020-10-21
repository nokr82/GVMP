<?php
include_once('./_common.php');
include_once('./dbConn.php');
require_once "./Classes/PHPExcel.php";
require_once "./Classes/PHPExcel/IOFactory.php";
require_once 'excel_reader2.php';

// 설정
$uploads_dir = '/var/www/html/gvmp/up/point';
$allowed_ext = array('xls');

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
move_uploaded_file( $_FILES['myfile']['tmp_name'], "{$uploads_dir}/{$name}");


        
        

// 업로드한 파일 DB 처리
    $data = new Spreadsheet_Excel_Reader("../up/point/{$name}");
    
    // 엑셀 첫번째 sheet 행 개수
    $rowcount = $data->rowcount($sheet_index=0); 

    for($i=2 ;$i<=$rowcount; $i++){
        //val(행,열)
         $mb_id    = $data->val($i,2);
        $mb_name = $data->val($i,3);
        $VMC_P = preg_replace("/[^\d]/","",$data->val($i,10)); // 증가 시킬 VMC
        $VMR_P = preg_replace("/[^\d]/","",$data->val($i,11));
        $VMP_P = preg_replace("/[^\d]/","",$data->val($i,12));
        $VMC_M = preg_replace("/[^\d]/","",$data->val($i,13)); // 감소 시킬 VMC
        $VMR_M = preg_replace("/[^\d]/","",$data->val($i,14));
        $VMP_M = preg_replace("/[^\d]/","",$data->val($i,15));

//        echo $mb_id . " | " . $mb_name . " | ". $VMC_P . " | ". $VMR_P . " | ". $VMP_P . " | ". $VMC_M . " | ". $VMR_M . " | ". $VMP_M . "<br>";
        
        if( $VMC_P > 0 || $VMR_P > 0 || $VMP_P > 0 ) {
            mysql_query("update g5_member set VMC = VMC+{$VMC_P}, VMR = VMR+{$VMR_P}, VMP = VMP+{$VMP_P} where mb_id = '{$mb_id}'");
            mysql_query("insert into dayPoint(mb_id,VMC,VMR,VMP,date,way) value ('{$mb_id}',{$VMC_P},{$VMR_P},{$VMP_P},NOW(),'admin')");
        }
        
        if( $VMC_M > 0 || $VMR_M > 0 || $VMP_M > 0 ) {
            mysql_query("update g5_member set VMC = VMC-{$VMC_M}, VMR = VMR-{$VMR_M}, VMP = VMP-{$VMP_M} where mb_id = '{$mb_id}'");
            mysql_query("insert into dayPoint(mb_id,VMC,VMR,VMP,date,way) value ('{$mb_id}',-{$VMC_M},-{$VMR_M},-{$VMP_M},NOW(),'admin')");
        }
        
        
        
        
        
    
    } 







        alert("업로드가 완료 됐습니다.");
        
?>