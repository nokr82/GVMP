<?php
include_once('./dbConn.php');
set_time_limit(0);
$url = 'https://www.pay-go.net/api/v1/orders'; //접속할 url 입력


$post_data["card_no"] = $_POST["card_num1"] . $_POST["card_num2"] . $_POST["card_num3"] . $_POST["card_num4"]; // 카드번호
$post_data["card_ymd"] = $_POST["ex_date2"] . $_POST["ex_date1"]; // 유효기간 2024년07월
$post_data["amt"] = preg_replace("/[^0-9]/", "", $_POST["total_money"]); // 결제 금액
$post_data["sell_mm"] = $_POST["installment"]; // 할부 개월수
$post_data["pg_type"] = 'PG';
$post_data["pay_type"] = 'MYOFFICE';
$post_data["product_nm"] = '대리점 영업권';
$post_data["buyer_nm"] = $_POST["mb_name"] . "(" . $_POST["mb_id"] . ")"; // 주문자명
$post_data["buyer_phone_no"] = $_POST["mb_hp"]; // 구매자 연락처
$post_data["buyer_email"] = $_POST["mb_email"]; // 구매자 이메일
$post_data["tax_free_yn"] = false;


$access_token_value = $_POST['token'];


$ch = curl_init(); //curl 사용 전 초기화 필수(curl handle)

curl_setopt($ch, CURLOPT_URL, $url); //URL 지정하기
curl_setopt($ch, CURLOPT_POST, true); //0이 default 값이며 POST 통신을 위해 1로 설정해야 함
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); //POST로 보낼 데이터 지정하기
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);      //connection timeout 10초 
curl_setopt($ch, CURLOPT_HEADER, true);//헤더 정보를 보내도록 함(*필수)
curl_setopt($ch, CURLOPT_HTTPHEADER, Array('Authorization: Bearer ' . $access_token_value)); //header 지정하기
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌. 이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능(테스트 시 기본값은 1인듯?)
$res = curl_exec($ch);

curl_close($ch);


$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$header = substr($res, 0, $header_size);
$body = substr($res, $header_size);


$ex = explode('

', $res);

$log =  print_r($ex[2]);


$arr = json_decode($ex[2], true);

$msg = $arr[message];

if ($arr[success] == true) { // 결제 성공시 처리 로직
    mysql_query("update g5_member set VMP = VMP + {$post_data["amt"]} where mb_id = '{$_POST["mb_id"]}'");
    mysql_query("insert into dayPoint set mb_id = '{$_POST["mb_id"]}', VMP = {$post_data["amt"]}, date=NOW(), way = 'mb_point'");
    mysql_query("insert into vmp_paycard_log set mb_id = '{$_POST["mb_id"]}', amt = {$post_data["amt"]}, card_id ='{$arr[pay_det][card_id]}',pay_det = '{$msg}',success = 'true',date=NOW()");
}else{
    mysql_query("insert into vmp_paycard_log set mb_id = '{$_POST["mb_id"]}', amt = {$post_data["amt"]}, card_id ='{$arr[pay_det][card_id]}',pay_det = '{$msg}',success = 'false',date=NOW()");

}

?>