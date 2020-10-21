<?php
//include_once ('./myOffice/dbConn.php');
//$test = mysql_query("SELECT * FROM genealogy_tree  WHERE rootid = '00003571' order by lv");
//while( $row2 = mysql_fetch_array($test) ) {
//    $sql = "INSERT INTO rank_history(mb_id,mb_name, rank,change_rank,date) VALUES ('{$row2['mb_id']}','{$row2['mb_name']}','{$row2['accountRank']}','1 STAR',CURRENT_TIMESTAMP())";
//    mysql_query($sql);
//
//}
include_once($_SERVER["DOCUMENT_ROOT"].'/myOffice/dbConn.php');
include_once ($_SERVER["DOCUMENT_ROOT"].'/myOffice/change_rank_func.php');
//$first_yn = rank_history('00003571','CU');
//echo $first_yn;
phpinfo();
//rank_com('00003572','VM','SM');

?>

