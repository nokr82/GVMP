<?php
    include_once('./dbConn.php');

    // 중고 장터 상품 결제를 처리하는 로직
    
    $_POST["buyer_addr"] = trim($_POST["buyer_addr"]);
    $_POST["mb_vmc_point"] = preg_replace("/[^0-9]/", "",$_POST["mb_vmc_point"]);
    $_POST["mb_vmp_point"] = preg_replace("/[^0-9]/", "",$_POST["mb_vmp_point"]);
    $_POST["mb_vmm_point"] = preg_replace("/[^0-9]/", "",$_POST["mb_vmm_point"]);
    
    if( $_POST["mb_vmc_point"] == "" ) {    $_POST["mb_vmc_point"] = 0; }
    if( $_POST["mb_vmp_point"] == "" ) {    $_POST["mb_vmp_point"] = 0; }
    if( $_POST["mb_vmm_point"] == "" ) {    $_POST["mb_vmm_point"] = 0; }
    
    $total_point = $_POST["mb_vmc_point"]+$_POST["mb_vmp_point"]+$_POST["mb_vmm_point"];
    
    $gwuRow = mysql_fetch_array(mysql_query("select * from g5_write_used where wr_id = " . $_POST["wr_id"])) or die("false");
    $buy_memberRow = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$_POST["mb_id"]}'")) or die("false");
    
    if( $_POST["mb_id"] == "" || $_POST["buyer_addr"] == "" || $_POST["wr_id"] == "" ) { // 필수 값 검사
        echo "false";
        exit();
    }
    
    
    if( $gwuRow["mb_id"] == "" || $gwuRow["saleStatus"] != "결제대기" ) { // 존재하는 상품인지, 판매중인 상품인지 검사
        echo "false";
        exit();
    }
    
    if( $total_point != $gwuRow["price"] ) { // 사용하는 포인트와 판매하는 금액이 일치하는 지 체크
        echo "false";
        exit();
    }
    
    if( $buy_memberRow["VMC"] < $_POST["mb_vmc_point"] || $buy_memberRow["VMP"] < $_POST["mb_vmp_point"] || $buy_memberRow["VMM"] < $_POST["mb_vmm_point"] ) { // 포인트 부족한지 체크
        echo "false";
        exit();
    }
    
    
    
    
    // 결제 완료 처리
    mysql_query("update g5_member set VMC = VMC - {$_POST["mb_vmc_point"]}, VMP = VMP - {$_POST["mb_vmp_point"]}, VMM = VMM - {$_POST["mb_vmm_point"]} where mb_id = '{$_POST["mb_id"]}'") or die("false");
    mysql_query("insert into dayPoint set mb_id = '{$_POST["mb_id"]}', VMC=-{$_POST["mb_vmc_point"]}, VMR=0, VMP=-{$_POST["mb_vmp_point"]}, VMM=-{$_POST["mb_vmm_point"]}, VMG=0, V=0, VCash=0, VPay=0, bizMoney=0, date=NOW(), way = 'usedBuy;{$gwuRow["wr_subject"]}'") or die("false");
    mysql_query("update g5_write_used set saleStatus = '결제완료', buyer_id = '{$_POST["mb_id"]}', buyer_hp = '{$_POST["buyer_addr"]}', buy_datetime = now() where wr_id = {$_POST["wr_id"]}") or die("false");
    
    $strData = urlencode("[VMP 중고장터]"
    . "\n결제가 완료되었습니다. 판매자와 연락하여 물품을 받으시기 바랍니다. 물품을 받으신 후에 중고장터에 접속하셔서 구매 확정을 진행 해 주세요.\n판매자가 물품을 보내지 않을 시 VMP 서포트로 문의바랍니다."
    . "\n\n판매자 연락처 : " . $gwuRow["seller_hp"]);

    getFromUrl("http://gvmp.company/sms/sms.php?strData={$strData}&strTelList={$_POST["buyer_addr"]};&strCallBack=07074515121");
    
    $strData = urlencode("[VMP 중고장터]"
    . "\n결제가 완료되었습니다. 구매자와 연락하여 물품을 보내시기 바랍니다. 물품을 보내신 후 구매자가 구매 확정을 해야 판매 대금이 입금됩니다."
    . "\n\n구매자 연락처 : " . $_POST["buyer_addr"]);

    getFromUrl("http://gvmp.company/sms/sms.php?strData={$strData}&strTelList={$gwuRow["seller_hp"]};&strCallBack=07074515121");
    
    echo "true";


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
?>