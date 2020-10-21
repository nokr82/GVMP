<?
include_once('./_common.php');

$jsondata = (isset($_POST['jsonData'])&&$_POST['jsonData'])?str_replace('\"','"',trim($_POST['jsonData'])):"";
$jsondata =' {"pageInfo":{"hasNextPage":false,"hasPreviousPage":true,"startCursor":"170000","endCursor":"170000"},"edges":[{"cursor":"170000","node":{"createdAt":1371547085000,"updatedAt":1551939322000,"key":"W068295","name":"고광택포토용지A4(20매)270g  5740-329","model":"고광택포토용지PremiumGlossy4027-300","production":"코닥","origin":"국내산","id":"Item,VzA2ODI5NQ==","price":11400,"pricePolicy":"free","fixedPrice":null,"searchKeywords":["인화지","포토용지","사진용지","문구","사무"],"category":{"key":"101004005002","name":"포토용지"},"content":"<img border=\"0\" alt=\"\" align=\"absMiddle\" src=\"http://ai.esmplus.com/omwworld/omw.jpg\" /><br>\r<img border=\"0\" align=\"absMiddle\" alt=\"\" src=\"http://www.omw.co.kr/goods_data/2011/01/4027-300.jpg\" /><br>\r<img border=\"0\" align=\"absMiddle\" alt=\"\" src=\"http://ai.esmplus.com/omwworld/knig/bsbs.jpg\" />","sellerOnlyContent":"","shippingFee":2500,"shippingType":"all","images":["http://cdn.ownerclan.com/t7voBwkOn~TCYp79cWgMmNN0__~qFnBG6widj2DjoNg/marketize/640/as/v1.jpg"],"status":"available","options":[{"optionAttributes":[],"price":11400,"quantity":9999,"key":"0"}],"taxFree":false,"adultOnly":false,"returnable":true,"noReturnReason":null,"guaranteedShippingPeriod":3,"openmarketSellable":true,"boxQuantity":0,"attributes":["OPENMARKET","IS_SALE"],"closingTime":"00:00:00","metadata":{"returnAddressCode":0,"vendorKey":2010011777,"vendorNotice":null,"coupangCategoryCode":"79969","auctionCategoryCode":"10090600","gmarketCategoryCode":"300008681","st11CategoryCode":"1010638","interparkCategoryCode":"001850103005002","smartstoreCategoryCode":"50003736","productNotificationInformation":{"code":35,"categorySpecific":["상품 상세정보에 별도 표기","상품 상세정보에 별도 표기","코닥","상품 상세정보에 별도 표기","판매자 연락처 참고","상품 상세정보에 별도 표기"],"common":["상품 상세정보에 별도 표기","상품 상세정보에 별도 표기","상품 상세정보에 별도 표기","상품 상세정보에 별도 표기"]},"certificateInformation":[],"returnShippingFee":2500,"grade":"9"}}}]}';
$jsondata = preg_replace('/\r\n|\r|\n/','',$jsondata);
$data = json_decode($jsondata,true);


$data = $data['edges'];

foreach($data as $key => $val) {
	$tmp = $val['node'];	
    $row = sql_fetch_array(sql_query("SELECT count(*) as cnt FROM g5_shop_item_tmp WHERE it_4 = '".$tmp['id']."' AND it_3 = '".$tmp['key']."'"));
	if($row['cnt']==0){
		$sql = "INSERT INTO g5_shop_item_tmp SET";
		$WHERE = "";
	}else{
		$sql = "UPDATE g5_shop_item_tmp SET";
		$WHERE = " WHERE it_4 = '".$tmp['id']."' AND it_3 = '".$tmp['key']."'";
	}
	$sql .= " it_id = '".time()."'";
	$sql .= ",ca_id = '90'";
	$sql .= ",ca_id2 = '".$tmp['category']['key']."'";
	$sql .= ",it_name = '".$tmp['name']."'";
	$sql .= ",it_maker = '".$tmp['production']."'";
	$sql .= ",it_origin = '".$tmp['origin']."'";
	$sql .= ",it_brand = '".$tmp['production']."'";
	$sql .= ",it_model = '".$tmp['model']."'";
	$sql .= ",it_basic = '".preg_replace('/\r\n|\r|\n/','',$tmp['content'])."'";
	$sql .= ",it_explan = '".$tmp['content']."'";
	$sql .= ",it_explan2 = '".strip_tags($tmp['content'])."'";
	$sql .= ",it_mobile_explan = '".$tmp['content']."'";
	$sql .= ",it_cust_price = '".$tmp['price']."'";
	$sql .= ",it_price = '".$tmp['price']."'";
	$sql .= ",it_sc_price = '".$tmp['shippingFee']."'";
	$sql .= ",it_use = '1'";
	$sql .= ",it_soldout = '0'";
	$sql .= ",it_stock_qty = '99999'";
	$sql .= ",it_ip = '".$_SERVER['REMOTE_ADDR']."'";
	$sql .= ",it_time = '".date("Y-m-d H:i:s")."'";

	if(is_array($tmp['images'])){
		foreach($tmp['images'] as $imgkey => $imgval) {
			$num = $imgkey + 1;
			$sql .= ",it_img{$num} = '".$imgval."'";
		}
	}
	$sql .= ",it_1 = '".$tmp['createdAt']."'";
	$sql .= ",it_2 = '".$tmp['updatedAt']."'";
	$sql .= ",it_3 = '".$tmp['key']."'";
	$sql .= ",it_4 = '".$tmp['id']."'";

//	echo $sql . "<br><br><br>";
	if($row['cnt']==0){
		$msg['cnt'] = $msg['cnt'] + 1;
		sql_query($sql.$WHERE);
	}
}

$msg['msg'] = "true";
echo json_encode($msg);
?>