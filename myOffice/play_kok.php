<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');

$url = 'https://www.play-kok.com/api/vmp/function_Inc.php?vmp_id=' . $_POST['vmp_id'] . '&email=' . $_POST['api_email'] . '&pw=' . urlencode($_POST['api_pw']); //접속할 url 입력
//vmp 1:1 적용
$pk = mysql_fetch_array(mysql_query("select count(*) as cnt from g5_member where pk_id = '{$_POST['api_email']}'"));
if ($pk['cnt']>0){
    echo 'overlap';
    exit();
}



$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
$result = json_decode(urldecode($response));

if (trim($response) == 'Success'){
    mysql_query("update g5_member set pk_id = '{$_POST['api_email']}' where mb_id = '{$_POST['vmp_id']}'");
}



echo $response;


?>