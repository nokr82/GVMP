<?php
include_once('./_common.php');


// 컨텐츠 타입을 json으로 지정합니다.
header("Content-Type: application/json");

//동우 0604
// SMS 인증번호를 전송하는 로직

$re = sql_fetch("select * from g5_member where mb_id = '{$_POST['mb_id']}'");
$rand_num = sprintf('%06d',rand(000000,999999));
$strData = urlencode("[ VMP ]\n인증번호는 [{$rand_num}]입니다. 정확히 입력해주세요.");


$mb_hp = preg_replace("/[^0-9]*/s", "", $re['mb_hp']);


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
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    $res = curl_exec($ch);
    curl_close($ch);

    return $res;
}

if ($re['mb_hp'] != ''){
    getFromUrl("http://gvmp.company/sms/sms.php?strData={$strData}&strTelList={$mb_hp};&strCallBack=07074515121");
    $result['hp']  = $mb_hp;
    $result['rand_num']  = $rand_num;
    $result['success']  = 'ok';
}else{
    $result['success']  = 'fail';
}



echo json_encode($result);

?>
