<?php

// 욜로몰로 보내주려고 만든 REST API 조회 로직을 구현한 소스입니다.
// 작성자 : 이정현


$id = "00003571";           // 조회할 ID 입력하세요. 암호되지 않은 평문 입력해야합니다.
$pw = '1111';               // 조회할 ID의 PW입력하세요. 암호화되지 않은 평문 입력해야합니다.





$arr = json_decode(getFromUrl("http://gvmp.company/myOffice/memberInfo.php?id=".Encrypt($id)."&pw=".Encrypt($pw), "POST"));

echo "TEST<br>";
echo $arr->check . "<br>";              // true or false -> false면 계정 정보가 틀린 것을 뜻합니다. false일 때 하위 정보는 조회되지 않습니다. 또한 true일 때 아이디와 비번이 일치함을 뜻 합니다.
echo $arr->info->mb_id . "<br>";        // 아이디
echo $arr->info->mb_name . "<br>";      // 이름
echo $arr->info->mb_email . "<br>";     // 이메일
echo $arr->info->mb_hp . "<br>";        // 연락처
echo $arr->info->mb_addr1 . "<br>";     // 주소1
echo $arr->info->mb_addr2 . "<br>";     // 주소2
echo $arr->info->mb_addr3 . "<br>";     // 주소3
echo $arr->info->mb_zip . "<br>";       // 우편번호
echo $arr->info->birth . "<br>";        // 생년월일







?>


<?php
    function Encrypt($str)
    {
        $secret_key = "yolo";       // 암호화 키. 수정 금지
        $secret_iv = "vmp0001";     // 암호화 IV. 수정 금지
        
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 32)    ;

        return str_replace("=", "", base64_encode(
                     openssl_encrypt($str, "AES-256-CBC", $key, 0, $iv))
        );
    }
    
    function getFromUrl($url, $method = 'GET')
    {
        $ch = curl_init();
        $agent = 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.0; Trident/5.0)';

        switch(strtoupper($method))
        {
            case 'GET':
                curl_setopt($ch, CURLOPT_URL, $url);
                break;

            case 'POST':
                $info = parse_url($url);
                $url = $info['scheme'] . '://' . $info['host'] . $info['path'];
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $info['query']);
                break;

            default:
                return false;
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }
?>