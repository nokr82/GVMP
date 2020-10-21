<?php
    include_once('./_common.php');
    include_once ('./dbConn.php');
    include_once(G5_LIB_PATH.'/mailer.lib.php');


    $mb_hp = "010-2659-1346";
    $mb_email = "2jeonghyeon@naver.com";


    
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
    
    
    
    
    
    
    
    $strData = urlencode("배너제작\n이름:{$_POST["mb_name"]}\n연락처:{$_POST["mb_addr_number"]}\n아이디:{$_POST["mb_id"]}");

    

    
    getFromUrl("http://gvmp.company/sms/sms.php?strData={$strData}&strTelList={$mb_hp};&strCallBack=07074515121");        
    
    
    
    
    $subject = "VMP 배너 제작 문의";
    $content = "배너제작<br><br>이름:{$_POST["mb_name"]}<br>연락처:{$_POST["mb_addr_number"]}<br>아이디:{$_POST["mb_id"]}";
    mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $mb_email, $subject, $content, 1);




?>