
<?php
include_once('./_common.php');
include_once('./dbConn.php');

$orderNum = $_POST['orderNum'];




$result = mysql_query("select a.mb_id from genealogy as a
inner join g5_member as b
on a.mb_id = b.mb_id
where recommenderID = '{$_POST['mb_id']}' and b.accountType != 'VM'");

$list = array();

while( $row = mysql_fetch_array($result) ) {
    array_push( $list, $row['mb_id'] );
}

array_push( $list, $_POST['mb_id'] );

$Qu = "select AL.*, GM.mb_name from orderList as AL inner join g5_member as GM
on AL.mb_id = GM.mb_id where ";

for( $i=0 ; $i<count($list) ; $i++ ) {
    $Qu .= "GM.mb_id = '{$list[$i]}'";
    if( $i+1 != count($list) )
        $Qu .= " or ";
        
}
    
$Qu .= "order by orderDate desc limit {$orderNum}, 10";

$result = mysql_query($Qu);



$resultQ = array();

while( $row = mysql_fetch_array($result) ) {
    array_push( $resultQ, array( $row['productName'], $row['n'], $row['orderDate'], $row['mb_name'], $row['money'], $row['commission'], $row['mb_id'] ) );
}

echo json_encode($resultQ); 

?>   