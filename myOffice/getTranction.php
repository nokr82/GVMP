<?php
include_once('./dbConn.php');


$mb_id = $_GET['mb_id'];
$VMC = $_GET['VMC'];
$VMR = $_GET['VMR'];
$VMP = $_GET['VMP'];
$way = $_GET['way'];

   
if(isset($mb_id)){
    if(isset($VMC)&&isset($VMR)&&isset($VMP)){
        mysql_query("insert into dayPoint(mb_id,VMC,VMR,VMP,date,way) value ('{$mb_id}','{$VMC}','{$VMR}','{$VMP}',NOW(),'{$way}')");
    } else if(isset($VMC)&&isset($VMR)){
        mysql_query("insert into dayPoint(mb_id,VMC,VMR,date,way) value ('{$mb_id}','{$VMC}','{$VMR}', NOW(),'{$way}')");
    } else if(isset($VMC)&&isset($VMP)){
        mysql_query("insert into dayPoint(mb_id,VMC,VMP,date,way) value ('{$mb_id}','{$VMC}','{$VMP}',NOW(),'{$way}')");
    } else if(isset($VMR)&&isset($VMP)){
        mysql_query("insert into dayPoint(mb_id,VMR,VMP,date,way) value ('{$mb_id}','{$VMR}','{$VMP}',NOW(),'{$way}')");
    } else if(isset($VMC)||isset($VMR)||isset($VMP)){
        if($VMC){
            mysql_query("insert into dayPoint(mb_id,VMC,date,way) value ('{$mb_id}','{$VMC}',NOW(),'{$way}')");
        } else if($VMR){
            mysql_query("insert into dayPoint(mb_id,VMR,date,way) value ('{$mb_id}','{$VMR}',NOW(),'{$way}')");
        } else if($VMP){
            mysql_query("insert into dayPoint(mb_id,VMP,date,way) value ('{$mb_id}','{$VMP}',NOW(),'{$way}')");
        }
    }
} else {
    echo '0';
}

?>
