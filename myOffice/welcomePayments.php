<?php
    include_once ('./dbConn.php');

    // 웰컴페이먼츠 비 인증 결제 처리하는 로직
    

    
    $row = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$_POST["mb_id"]}'"));
    if( $row["payment"] == "NO" ) {
        echo "권한 없음";
        exit();
    }

$_POST["mb_name"] = urldecode($_POST["mb_name"]);
$_POST["total_money"] = urldecode($_POST["total_money"]);
$_POST["total_money"] = str_replace(',', '', $_POST["total_money"]);

//$apikey = "1c052ebabeb37022a68b8e807593b5ea"; // API KEY
//$iv = "82eb4273a8fbf47bed5045bd8c08b35a"; // IV VALUE
//동우수정 20200203
$apikey = "6bf718ca50c91ae0e779d8c5ea78e0b1"; // API KEY
$iv = "2f3f47870cbe80b2e93bb5c16eeeb2b1"; // IV VALUE


//$mid = 'wa00135';
$mid = 'wa00324';
$pay_type = 'CREDIT_CARD';
$pay_method = 'CREDIT_UNAUTH_API';
$millis = current_millis();
$api_key = $apikey;

$card_num = $_POST["card_num1"].$_POST["card_num2"].$_POST["card_num3"].$_POST["card_num4"]; // 승인되지 않는 번호입니다. 실제 승인테스트할 카드번호로 변경해주세요.
$amount = $_POST["total_money"];
$order_no = str_pad(mt_rand(1, 999999999999999),"15","0",STR_PAD_LEFT);
$card_expiry_ym = $_POST["ex_date2"].$_POST["ex_date1"];
$orderName = "{$_POST["mb_name"]}({$_POST["mb_id"]})";
$product_name = "수소수텀블러";
$card_sell_mm = $_POST["installment"];




////////////////////////////
// AES 암호화 PHP 테스트 코드 //
////////////////////////////



$card_no = bin2hex(openssl_encrypt($card_num, 'AES-128-CBC', hex2bin($apikey), OPENSSL_RAW_DATA, hex2bin($iv)));






/////////////////////////
// SHA256 해시 테스트 코드 //
/////////////////////////

function current_millis() {
    list($usec, $sec) = explode(" ", microtime());
    return round(((float)$usec + (float)$sec) * 1000);
}



$hash_value = hash("sha256", $mid.$pay_type.$pay_method.$order_no.$amount.$millis.$api_key);





    

    $data = array(
        'mid' => $mid,
        'pay_type' => $pay_type,
        'pay_method' => $pay_method,
        'card_no' => $card_no,
        'card_expiry_ym' => $card_expiry_ym, // 카드 유효 년월
        'order_no' => $order_no,
        'user_name' => $orderName, // 주문자명
        'amount' => $amount,
        'product_name' => $product_name, // 상품명
        'card_sell_mm' => $card_sell_mm, // 할부개월
        'echo' => '',
        'millis' => $millis,
        'hash_value' => $hash_value
    );

    $url = "https://payapi.welcomepayments.co.kr/api/payment/approval";

    $ch = curl_init();                                 //curl 초기화
    curl_setopt($ch, CURLOPT_URL, $url);               //URL 지정하기
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환 
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);      //connection timeout 10초 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   //원격 서버의 인증서가 유효한지 검사 안함
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));       //POST data
    curl_setopt($ch, CURLOPT_POST, true);              //true시 post 전송 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));

    $response = curl_exec($ch);
    curl_close($ch);
    
    
    
    print_r($response); // 웰컴이준 결과 프론트로 그대로 리턴~

    

    $arr = json_decode($response, true);
    
    if( $arr[result_code] == "0000" ) { // 결제 성공시 처리 로직
        mysql_query("update g5_member set VMP = VMP + {$_POST["total_money"]} where mb_id = '{$_POST["mb_id"]}'");
        mysql_query("insert into dayPoint set mb_id = '{$_POST["mb_id"]}', VMP = {$_POST["total_money"]}, date=NOW(), way = 'mb_point'");
    }
    
    
    
?>


