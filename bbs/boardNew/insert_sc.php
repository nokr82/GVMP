<?php
include_once '/var/www/html/gvmp/myOffice/dbConn.php';


for ($i = 0; $i < 100; $i++) {
    echo $i;
    $sql = "INSERT INTO `gyc5`.`g5_write_youtube` (`wr_id`, `wr_subject`, `wr_content`, `wr_link1`, `mb_id`, `wr_password`, `wr_name`, `wr_email`, `wr_datetime`, `wr_file`, `wr_last`, `wr_ip`) VALUES ('', 'test{$i}', '<p>hrttrhth{$i}</p>', '', '00003571', '*89C6B530AA78695E257E55D63C00A6EC9AD3E977', '00003571', 'nokr82@naver.com', '2020-08-11 11:34:51', '0', '2020-08-11 11:34:51', '2020-08-11 11:34:51');";
    $result = mysql_query($sql);
}


?>