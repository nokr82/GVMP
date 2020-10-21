<?php
    include_once('./dbConn.php');


    // 구매 확정을 처리하는 백에드 로직
    
    if( $_POST["wr_id"] == "" || $_POST["mb_id"] == "" ) {
        echo "false";
        exit();
    }
    
    $row = mysql_fetch_array(mysql_query("select * from g5_write_used where wr_id = {$_POST["wr_id"]} and buyer_id = '{$_POST["mb_id"]}' and saleStatus = '결제완료'")) or die("false");
    
    if( $row["buyer_id"] == "" || $row["purCon_datetime"] != "" ) { // 없는 게시글인지, 이미 구매확정 했는 지 체크하기
        echo "false";
        exit();
    }
    
    $money = floor( $row["price"] * 0.95 );
    mysql_query("update g5_write_used set saleStatus = '거래완료', purCon_datetime = now() where wr_id = {$_POST["wr_id"]}") or die("false");
    mysql_query("update g5_member set VMP = VMP + {$money} where mb_id = '{$row["mb_id"]}'") or die("false");
    mysql_query("insert into dayPoint set mb_id = '{$row["mb_id"]}', VMC=0, VMR=0, VMP={$money},VMM=0,VMG=0,V=0,VCash=0,VPay=0,bizMoney=0,date=NOW(),way='usedSale;{$row["wr_subject"]}'") or die("false");
    
    
    $strData = urlencode("[VMP 중고장터]"
    . "\n구매자가 구매 확정을 하였습니다. 판매 대금은 VMP로 입금되었습니다.");

    getFromUrl("http://gvmp.company/sms/sms.php?strData={$strData}&strTelList={$row["seller_hp"]};&strCallBack=07074515121");
    
    
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