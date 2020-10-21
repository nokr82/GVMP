<?php 
include_once('./_common.php');

$check_selecte = $_GET['check_selecte'];
$od_id = $_GET['od_id'];


if($check_selecte == 1){
    sql_query(" update {$g5['g5_shop_order_table']} set od_completionTime = now() where od_id = '$od_id' ");
} else {
    sql_query(" update {$g5['g5_shop_order_table']} set od_completionTime = '' where od_id = '$od_id' ");
}
?>