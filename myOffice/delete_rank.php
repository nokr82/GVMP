<?php
include_once('./dbConn.php');


mysql_query("DELETE FROM rank_history WHERE datetime < '2020-02-11'");


?>