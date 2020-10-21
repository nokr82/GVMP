<?php
    include_once ('./dbConn.php');
    
    // 신분증 인증 요청을 처리하는 백엔드 로직
    
    $_POST["mb_id"] = trim($_POST["mb_id"]);
    $_POST["mb_name"] = trim($_POST["mb_name"]);
    $_POST["mb_unum_f"] = trim($_POST["mb_unum_f"]);
    $_POST["mb_unum_b"] = trim($_POST["mb_unum_b"]);
    $_POST["resident_type"] = trim($_POST["resident_type"]);
    
    if( $_POST["mb_id"] == "" || $_POST["mb_name"] == "" || $_POST["mb_unum_f"] == "" || $_POST["mb_unum_b"] == "" || $_POST["resident_type"] == "" ) {
        echo "false";
        exit();
    }
    
    $fileName = "";
    if( $_FILES['img_file']['name'] != "" ) {
        // 설정
        $uploads_dir = '/var/www/html/gvmp/up/idCertificate';

        // 변수 정리
        $error = $_FILES['img_file']['error'];
        $name = $_FILES['img_file']['name'];

        // 오류 확인
        if( $error != UPLOAD_ERR_OK ) {
                switch( $error ) {
                        case UPLOAD_ERR_INI_SIZE:
                        case UPLOAD_ERR_FORM_SIZE:
                            echo "false"; exit();
                                break;
                        case UPLOAD_ERR_NO_FILE:
                            echo "false"; exit();
                                break;
                        default:
                            echo "false"; exit();
                }
                exit;
        }

        $ext = array_pop(explode('.', $name));
        $time = time();
        $fileName = "{$_POST["mb_id"]}_{$time}.{$ext}";
        $path = "{$uploads_dir}/{$fileName}";

        // 파일 이동
        move_uploaded_file( $_FILES['img_file']['tmp_name'], $path);
    }
    
    $fileNameSQL = "";
    if( $fileName != "" ) {
        $fileNameSQL = "image = '{$fileName}',";
    }
    
    $type = "";
    if( $_POST["resident_type"] == "내국인" ) {
        $type = "D";
    } else if( $_POST["resident_type"] == "외국인" ) {
        $type = "F";
    }
    
    $checkRe = mysql_query("select * from idCertificateTBL where mb_id = '{$_POST["mb_id"]}'") or die("false");
    $checkRow = mysql_fetch_array( $checkRe );
    
    if( $checkRow["mb_id"] != "" ) {
        // 업데이트
        mysql_query("update idCertificateTBL set mb_id = '{$_POST["mb_id"]}', name = '{$_POST["mb_name"]}', RRN1 = '{$_POST["mb_unum_f"]}', RRN2 = '{$_POST["mb_unum_b"]}', DnF = '{$type}', {$fileNameSQL} datetime = now(), ok = 'W' where no = '{$checkRow["no"]}'") or die("false");
    } else {
        // 첫 등록
        mysql_query("insert into idCertificateTBL set mb_id = '{$_POST["mb_id"]}', name = '{$_POST["mb_name"]}', RRN1 = '{$_POST["mb_unum_f"]}', RRN2 = '{$_POST["mb_unum_b"]}', DnF = '{$type}', {$fileNameSQL} datetime = now(), ok = 'W'") or die("false");
    }
    
    echo "true";

?>