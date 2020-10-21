<?php
include_once ('./_common.php');
include_once ('./dbConn.php');

// SMS 인증번호를 전송하는 로직

$result = mysql_query("select * from g5_member where mb_id = '{$_POST['mb_id']}'");
$row = mysql_fetch_array($result);



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

$mb_hp;
if( ! isset($_POST['mb_id']) ) {
    $mb_hp = $_POST['mb_hp'];
} else {
    $mb_hp = $row['mb_hp'];
}



$strData = urlencode("[ VMP ]\n인증번호는 {$_POST['smsNumber']}입니다. 정확히 입력해주세요.");


$mb_hp = preg_replace("/[^0-9]*/s", "", $mb_hp);

if( isset($_POST["mb_hp"]) && $_POST["mb_hp"] != "" ) {
    $mb_hp = $_POST["mb_hp"];
}

getFromUrl("http://gvmp.company/sms/sms.php?strData={$strData}&strTelList={$mb_hp};&strCallBack=07074515121");


?>
