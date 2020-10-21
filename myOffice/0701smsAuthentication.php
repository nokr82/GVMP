<?php
include_once ('../common.php');
include_once ('./dbConn.php');

//동우작성
//문자인증 보안강회

// SMS 인증번호를 전송하는 로직



// 컨텐츠 타입을 json으로 지정합니다.
header("Content-Type: application/json");


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


$mb_hp = $member['mb_hp'];


$rand_num = sprintf('%06d', rand(000000, 999999));

$strData = urlencode("[ VMP ]\n인증번호는 {$rand_num}입니다. 정확히 입력해주세요.");


$mb_hp = preg_replace("/[^0-9]*/s", "", $mb_hp);

if( isset($_POST["mb_hp"]) && $_POST["mb_hp"] != "" ) {
    $mb_hp = $_POST["mb_hp"];
}



if (getFromUrl("http://gvmp.company/sms/sms.php?strData={$strData}&strTelList={$mb_hp};&strCallBack=07074515121")){
    $sql = "insert into smsCode set mb_id = '{$member['mb_id']}', sms_code = '{$rand_num}',mb_hp='{$mb_hp}',  stat ='W'";
    mysql_query($sql);
    $fm_id = mysql_insert_id();
    $result['last_id'] = $fm_id;
    $result['success'] = 'ok';
}else{
    $result['success'] = 'fail';
}

echo json_encode($result);
?>
