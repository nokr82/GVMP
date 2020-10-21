<?php
    include_once ('./dbConn.php');
    
    // 이미지 광고 만들기를 처리하는 백엔드 로직
    
    
    $_POST["mb_id"] = trim($_POST["mb_id"]);
    $_POST["mb_ad_title"] = trim($_POST["mb_ad_title"]);
    $_POST["mb_company"] = trim($_POST["mb_company"]);
    $_POST["mb_money"] = preg_replace("/[^0-9]/", "",$_POST["mb_money"]) ;
    $_POST["fr_date"] = trim($_POST["fr_date"]);
    $_POST["to_date"] = trim($_POST["to_date"]);
    $_POST["mb_url"] = trim($_POST["mb_url"]);
    
    if( $_POST["mb_id"] == "" || $_POST["mb_ad_title"] == "" || $_POST["mb_company"] == "" || $_POST["mb_money"] == "" || $_POST["fr_date"] == "" || $_POST["to_date"] == "" ) {
        echo "false"; exit();
    }
    
    if( $_POST["mb_money"] == "" ) {
        $_POST["mb_money"] = 0;
    }
    

    $fileName = "";
    if( $_FILES['mb_ad_img_pc']['name'] != "" ) {
        // 설정
        $uploads_dir = '/var/www/html/gvmp/up/ad/image';

        // 변수 정리
        $error = $_FILES['mb_ad_img_pc']['error'];
        $name = $_FILES['mb_ad_img_pc']['name'];

        // 오류 확인
        if( $error != UPLOAD_ERR_OK ) {
                switch( $error ) {
                        case UPLOAD_ERR_INI_SIZE:
                        case UPLOAD_ERR_FORM_SIZE:
//                                echo "파일이 너무 큽니다. ($error)";
                            echo "false"; exit();
                                break;
                        case UPLOAD_ERR_NO_FILE:
//                                echo "파일이 첨부되지 않았습니다. ($error)";
                            echo "false"; exit();
                                break;
                        default:
//                                echo "파일이 제대로 업로드되지 않았습니다. ($error)";
                            echo "false"; exit();
                }
                exit;
        }

        $ext = array_pop(explode('.', $name));
        $time = time();
        $fileName = "{$_POST["mb_id"]}_pc_{$time}.{$ext}";
        $path = "{$uploads_dir}/{$fileName}";

        // 파일 이동
        move_uploaded_file( $_FILES['mb_ad_img_pc']['tmp_name'], $path);
    }
    
    
    
    
    
    
    
    $fileName2 = "";
    if( $_FILES['mb_ad_img_mobile']['name'] != "" ) {
        // 설정
        $uploads_dir = '/var/www/html/gvmp/up/ad/image';

        // 변수 정리
        $error = $_FILES['mb_ad_img_mobile']['error'];
        $name = $_FILES['mb_ad_img_mobile']['name'];

        // 오류 확인
        if( $error != UPLOAD_ERR_OK ) {
                switch( $error ) {
                        case UPLOAD_ERR_INI_SIZE:
                        case UPLOAD_ERR_FORM_SIZE:
//                                echo "파일이 너무 큽니다. ($error)";
                            echo "false"; exit();
                                break;
                        case UPLOAD_ERR_NO_FILE:
//                                echo "파일이 첨부되지 않았습니다. ($error)";
                            echo "false"; exit();
                                break;
                        default:
//                                echo "파일이 제대로 업로드되지 않았습니다. ($error)";
                            echo "false"; exit();
                }
                exit;
        }

        $ext = array_pop(explode('.', $name));
        $time = time();
        $fileName2 = "{$_POST["mb_id"]}_mobile_{$time}.{$ext}";
        $path = "{$uploads_dir}/{$fileName2}";

        // 파일 이동
        move_uploaded_file( $_FILES['mb_ad_img_mobile']['tmp_name'], $path);
    }
    
    
    
    
    
    
    
    
    
    
    $fileNameSQL = "";
    if( $fileName != "" ) {
        $fileNameSQL = "image1 = '{$fileName}',";
    }
    
    $fileNameSQL2 = "";
    if( $fileName2 != "" ) {
        $fileNameSQL2 = "image2 = '{$fileName2}',";
    }
    
    
    
    
    
    

    if( $_POST["modi"] == "Y" ) {
        // 업데이트
        mysql_query("update imageAdListTBL set mb_id = '{$_POST["mb_id"]}', adName = '{$_POST["mb_ad_title"]}', companyName = '{$_POST["mb_company"]}', budget = {$_POST["mb_money"]}, frDate = '{$_POST["fr_date"]}', toDate = '{$_POST["to_date"]}', {$fileNameSQL} {$fileNameSQL2} url = '{$_POST["mb_url"]}' where no = {$_POST["no"]}");
    } else {
        // 첫 등록
        mysql_query("insert into imageAdListTBL set mb_id = '{$_POST["mb_id"]}', adName = '{$_POST["mb_ad_title"]}', companyName = '{$_POST["mb_company"]}', budget = {$_POST["mb_money"]}, frDate = '{$_POST["fr_date"]}', toDate = '{$_POST["to_date"]}', {$fileNameSQL} {$fileNameSQL2} url = '{$_POST["mb_url"]}'");
    }
    
    echo "true";
    
    

?>