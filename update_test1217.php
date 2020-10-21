<?php
include_once('./_common.php');
include_once('./dbConn.php');


$sql = "UPDATE g5_member

        SET mb_email = 'jhlee0295@naver.com',birth = '720807'

        WHERE mb_id = '00010370'";
sql_query($sql);


echo "레코드 수정 성공!" . $sql;


mysqli_close($connection);

?>