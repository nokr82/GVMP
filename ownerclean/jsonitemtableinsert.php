<?
include_once('./_common.php');


$sql = "select * from ownertmp where idx > 926 order by idx asc";
$result = sql_query($sql);
$number = 0;
for($i=0; $row=sql_fetch_array($result); $i++) {
	$jsondata = $row['jsoncontent'];
	$jsondata = preg_replace('/\r\n|\r|\n/','',$jsondata);
	$jsondata = str_replace('\n','',$jsondata);

	$data = json_decode($jsondata,true);
	$data = $data['edges'];
	foreach($data as $key => $val) {
		$tmp = $val['node'];	
		$crow = sql_fetch_array(sql_query("SELECT count(*) as cnt FROM g5_shop_item_tmp WHERE it_4 = '".$tmp['id']."' AND it_3 = '".$tmp['key']."'"));
		if($crow['cnt']==0){
			$csql = "INSERT INTO g5_shop_item_tmp SET";
			$WHERE = "";
//		}else{
//			$csql = "UPDATE g5_shop_item_tmp SET";
//			$WHERE = " WHERE it_4 = '".$tmp['id']."' AND it_3 = '".$tmp['key']."'";
//		}
			$csql .= " it_id = '".time()."'";
			$csql .= ",ca_id = '90'";
			$csql .= ",ca_id2 = '".$tmp['category']['key']."'";
			$csql .= ",it_name = '".$tmp['name']."'";
			$csql .= ",it_maker = '".$tmp['production']."'";
			$csql .= ",it_origin = '".$tmp['origin']."'";
			$csql .= ",it_brand = '".$tmp['production']."'";
			$csql .= ",it_model = '".$tmp['model']."'";
			$csql .= ",it_basic = '".preg_replace('/\r\n|\r|\n/','',$tmp['content'])."'";
			$csql .= ",it_explan = '".$tmp['content']."'";
			$csql .= ",it_explan2 = '".strip_tags($tmp['content'])."'";
			$csql .= ",it_mobile_explan = '".$tmp['content']."'";
			$csql .= ",it_cust_price = '".$tmp['price']."'";
			$csql .= ",it_price = '".$tmp['price']."'";
			$csql .= ",it_sc_price = '".$tmp['shippingFee']."'";
			$csql .= ",it_use = '1'";
			$csql .= ",it_soldout = '0'";
			$csql .= ",it_stock_qty = '99999'";
			$csql .= ",it_ip = '".$_SERVER['REMOTE_ADDR']."'";
			$csql .= ",it_time = '".date("Y-m-d H:i:s")."'";

			if(is_array($tmp['images'])){
				foreach($tmp['images'] as $imgkey => $imgval) {
					$num = $imgkey + 1;
					$csql .= ",it_img{$num} = '".$imgval."'";
				}
			}
			$csql .= ",it_1 = '".$tmp['createdAt']."'";
			$csql .= ",it_2 = '".$tmp['updatedAt']."'";
			$csql .= ",it_3 = '".$tmp['key']."'";
			$csql .= ",it_4 = '".$tmp['id']."'";

			sql_query($csql.$WHERE);
		}
	}
	echo "idx===>".$row['idx'] . "<br>";
}
?>