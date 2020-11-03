<?php
include_once ('./_common.php');
include_once ('./dbConn.php');

//$sql = "select * from dayPoint where (1) and  way = 'autoVMC' and date like '2020-10-14%'";

$sql ="SELECT a.mb_id AS mb_id,a.vmc AS dayVmc,a.way,b.VMC AS memberVmc,b.vmg  FROM dayPoint a, g5_member b WHERE a.mb_id = b.mb_id
        AND a.way = 'autoVMC' 
        AND  a.DATE LIKE '2020-10-15%' 
        ORDER BY DATE DESC";
//echo $sql;
$query = mysql_query($sql);
while ($row = mysql_fetch_array($query)){

    $updateVMC = $row['memberVmc']-$row['dayVmc'];

    //echo "dayVMC는 >>> ".$row['dayVmc']."|||memberVmc >>> ".$row['memberVmc']."||".$row['mb_id']."<br>";
    //echo $updateVMC."||||".$row['mb_id']."<br>";

    if($updateVMC < 0){
        echo $row['mb_id']."<br>";
    }

    //mysql_query("update g5_member set VMC = '{$updateVMC}' where mb_id = '{$row['mb_id']}'");
}




echo "완료";
