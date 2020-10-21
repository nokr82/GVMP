<?php
include_once('./dbConn.php');
$year = $_POST['year'];
$month = $_POST['month'];
$price = preg_replace("/[^\d]/","",$_POST['price']);
$mem_id = $_POST['mem_id'];
$up = $_POST['up'];
$sale_set = mysql_fetch_array(mysql_query("SELECT * FROM sale_setting where set_year={$year} and set_month={$month}"));
$data = array();
//검색할시
if ($up =='search'){

 if ($sale_set){
     $data['result'] = 'success';
     $data['set_price'] = $sale_set['set_sale'];
    }else{
     $data['result'] = 'empty';
 }

}else{
    if ($sale_set){
        if ($up =='up'){
            $sql = " update sale_setting set set_sale={$price},date =current_timestamp() where id = '{$sale_set['id']}'";
            mysql_query($sql);
            $data['result'] = 'success';
        }else{
            $data['result'] = 'already';
        }
    }else{
        $result = mysql_query("insert into sale_setting set
        mb_id = '{$mem_id}',
        set_sale = '{$price}',
         set_year = '{$year}',
          set_month = '{$month}'
        ");
        if ($result){
            $data['result'] = 'success';
        }else{
            $data['result'] = 'fail';
        }
    }
}


echo json_encode($data);


?>
