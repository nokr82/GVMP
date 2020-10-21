<?php
include_once ('./dbConn.php');

// 욜로몰에서 계정 정보를 조회할 수 있게 REST API 제공
// id, pw만 POST로 넘겨 받은 후 
// 회원 정보를 리턴
// 복호화 키, IV 욜로몰과 안 맞으면 동작 안 하게 설계 됨

$encrypted1 = $_POST["id"];
$encrypted2 = $_POST["pw"];

$secret_key = "yolo";       // 복호화 키
$secret_iv = "vmp0001";     // 복호화 IV


$decrypted1 = Decrypt($encrypted1, $secret_key, $secret_iv);
$decrypted2 = Decrypt($encrypted2, $secret_key, $secret_iv);

$decrypted2 = get_encrypt_string($decrypted2);


$row = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$decrypted1}' and mb_password = '{$decrypted2}' and yolo = 'YES' and accountType = 'VM'"));
$returnArray;

$now_temp = strtotime(date("Y-m-d"));
$dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row['renewal']}', interval +4 month) AS date"));
$timestamp_temp = strtotime($dateCheck_1["date"]);
if( $timestamp_temp < $now_temp ) { // 리뉴얼 유예기간이 지났으면 참
    $returnArray = array('check'=>false);
} else {
    if( $row["mb_id"] != "" ) {
        $returnArray = array('check'=>true, 'info'=>array(
            'mb_id'=>$row["mb_id"],
            'mb_name'=>$row["mb_name"],
            'mb_email'=>$row["mb_email"],
            'mb_hp'=>$row["mb_hp"],
            'mb_addr1'=>$row["mb_addr1"],
            'mb_addr2'=>$row["mb_addr2"],
            'mb_addr3'=>$row["mb_addr3"],
            'mb_zip'=>$row["mb_zip1"].$row["mb_zip2"],
            'birth'=>$row["birth"]
        ));
    } else {
        $returnArray = array('check'=>false);
    }
}






header('Content-type: application/json');
echo json_encode($returnArray);
?>



<?php
    function Decrypt($str, $secret_key='secret key', $secret_iv='secret iv')
    {
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 32);

        return openssl_decrypt(
                base64_decode($str), "AES-256-CBC", $key, 0, $iv
        );
    }
    
    // 문자열 암호화
    function get_encrypt_string($str)
    {
        $row = mysql_fetch_array(mysql_query(" select password('$str') as pass "));
        return $row['pass'];
    }
?>