<?php
include_once('./dbConn.php');
include_once("./Classes/PHPExcel.php");
include_once("./Classes/PHPExcel/IOFactory.php");

$filename = '//var/www/html/gvmp/myOffice/excel_update/GV보너스.xlsx'; // 엑셀 파일의 경로와 파일명


// PHPExcel은 메모리를 사용하므로 메모리 최대치를 늘려준다.

// 이부분은 엑셀파일이 클때는 적절히 더욱 늘려줘야 제대로 읽어올수 있다.
ini_set('memory_limit', '1024M');


try {


    // 업로드 된 엑셀 형식에 맞는 Reader객체를 만든다.

    $objReader = PHPExcel_IOFactory::createReaderForFile($filename);


    // 읽기전용으로 설정

    $objReader->setReadDataOnly(true);


    // 엑셀파일을 읽는다

    $objExcel = $objReader->load($filename);


    // 첫번째 시트를 선택

    $objExcel->setActiveSheetIndex(0);


    $objWorksheet = $objExcel->getActiveSheet();

    $rowIterator = $objWorksheet->getRowIterator();


    foreach ($rowIterator as $row) {

        $cellIterator = $row->getCellIterator();

        $cellIterator->setIterateOnlyExistingCells(false);

    }


    $maxRow = $objWorksheet->getHighestRow();


    // echo $maxRow . "<br>";


    for ($i = 2; $i < $maxRow; $i++) {


        $b = $objWorksheet->getCell('B' . $i)->getValue(); // mb_id


        $c = $objWorksheet->getCell('C' . $i)->getValue(); // C열

//        $d = $objWorksheet->getCell('D' . $i)->getValue(); // D열
//
//        $e = round($d*0.05);
//
//        $f = round($d + $e);



//        echo $a . " / " . $f . "<br>";




//        if($c != ''){
//            $sql1 = "update g5_member set VMC = VMC + {$c} where mb_id = '{$a}';";
//            $sql2 = "INSERT INTO `gyc5`.`dayPoint` (`mb_id`,`date`, `VMC`, `way`) VALUES ('{$a}',now(), '{$c}', 'admin2;8월1분기 C환불');";
//        }else{
//            $sql1 = "update g5_member set VMC = VMC + {$f} where mb_id = '{$a}';";
//            $sql2 = "INSERT INTO `gyc5`.`dayPoint` (`mb_id`,`date`, `VMC`, `way`) VALUES ('{$a}',now(), '{$f}', 'admin2;8월1분기 C환불');";
//        }

        $sql1 = "update g5_member set V = V + {$c} where mb_id = '{$b}';";
        //$sql2 = "INSERT INTO `gyc5`.`dayPoint` (`mb_id`,`date`, `VMC`, `way`) VALUES ('{$b}',now(), '{$c}', 'admin2;8월1분기 C환불');";

//        mysql_query($sql2) or die("insert Error !");
        echo $sql1 . "@@@@@@@@@@" . $i . " <br>\n";
        //echo $sql2 . "@@@@@@@@@@" . $i . " <br>\n";


//          $query = "insert into 삽입할 테이블 (continent,country_code,country,city_code,city,use_yn) values ('$b','$c','$d','$e','$f','$g')";
         //mysql_query($sql1) or die("Insert Error 1!");
          //mysql_query($sql2) or die("Insert Error 2!");

    }

//    echo $maxRow - 1 . " Data inserting finished !";


}
catch (exception $e) {
    echo '엑셀파일을 읽는도중 오류가 발생하였습니다 . !';
}


?>

