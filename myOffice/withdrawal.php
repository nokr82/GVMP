<?php
include_once('./_common.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/myOffice/dbConn.php');

// 회원이 직접 탈퇴를 요청했을 때 처리하는 로직


// 해킹에 악용되어 주석처리 함.
// 현재 회원이 직접 회원탈퇴하는 기능은 제공되지 않고 있음
// 불필요한 로직이지만 혹시 필요할까 삭제는 하지 않음
// 이정현

//if (!$member['mb_id'])
//    alert('회원만 접근하실 수 있습니다.');
//    
//    if ($is_admin == 'super')
//        alert('최고 관리자는 탈퇴할 수 없습니다');
//        
//        
//    if (!($_POST['pw_confirm2'] && check_password($_POST['pw_confirm2'], $member['mb_password'])))
//        alert('비밀번호가 틀립니다.');
//        
//        // 회원탈퇴일을 저장
//        $date = date("Ymd");
//        $sql = " update {$g5['member_table']} set mb_leave_date = '{$date}' where mb_id = '{$member['mb_id']}' ";
//        sql_query($sql);
//        
//
//    function getFromUrl($url, $method = 'GET')
//{
//    $ch = curl_init();
//    $agent = 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.0; Trident/5.0)';
//    
//    switch(strtoupper($method))
//    {
//        case 'GET':
//            curl_setopt($ch, CURLOPT_URL, $url);
//            break;
//            
//        case 'POST':
//            $info = parse_url($url);
//            $url = $info['scheme'] . '://' . $info['host'] . $info['path'];
//            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_POST, true);
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $info['query']);
//            break;
//            
//        default:
//            return false;
//    }
//    
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
//    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
//    curl_setopt($ch, CURLOPT_HEADER, false);
//    curl_setopt($ch, CURLOPT_REFERER, $url);
//    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
//    $res = curl_exec($ch);
//    curl_close($ch);
//    
//    return $res;
//}
//
//    include_once('./dbConn.php');
//    include_once('./_common.php');
//    include_once(G5_LIB_PATH.'/mailer.lib.php');
//    
//    $result = mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'");
//    $row = mysql_fetch_array($result);
//    
//    // 관리자한테 문자 보내기
//    $strData = urlencode("[회원탈퇴] {$row['mb_name']}({$row['mb_id']})\nVMC : {$row['VMC']} \nVMR : {$row['VMR']} \nVMP : {$row['VMP']}\n"
//    . "\n이메일 : {$row['mb_email']}\n연락처 : {$row['mb_hp']}"
//    . "\n주소 : {$row['mb_addr1']} {$row['mb_addr2']} {$row['mb_addr1']}\n"
//    . "생년월일 : {$row['birth']}\n"
//    . "은행명 : {$row['bankName']}\n"
//    . "예금주 : {$row['accountHolder']}\n"
//    . "계좌번호 : {$row['accountNumber']}\n"
//    . "회원가입일 : {$row['mb_open_date']}\n"
//    . "최근 리뉴얼 : {$row['renewal']}");
//
//    getFromUrl("http://gvmp.company/sms/sms.php?strData={$strData}&strTelList=01026591346;&strCallBack=07074515121");
////    getFromUrl("http://gvmp.company/sms/sms.php?strData={$strData}&strTelList=01026591346;&strCallBack=07074515121");
//    
//    
//    
//    // 디비에 탈퇴 전에 포인트 백업
//    mysql_query("insert into member_info_backup values( null, '{$row['mb_id']}', '{$row['mb_name']}', {$row['VMC']}, {$row['VMR']}, {$row['VMP']}, CURDATE() )");
//    
//    
//    
////    // 비밀번호 Neungschool1!으로 변경시키고 포인트 전부 0원으로 변경
//    mysql_query("update g5_member set mb_password = '*70A2D2A3CCBF85CF3E22E9A334D46B019F854012', accountType = '회원탈퇴', accountRank = '회원탈퇴', VMC = 0, VMR = 0, VMP = 0 where mb_id = '{$member['mb_id']}'");
//    
//    $subject = "VMP 회원탈퇴 {$row['mb_name']}({$row['mb_id']}))";
//    $content = "<h3>회원탈퇴</h3><br><br>";
//    $content .= "{$row['mb_name']}({$row['mb_id']})<br><br>"
//       . "이메일 : {$row['mb_email']}<br>연락처 : {$row['mb_hp']}<br>"
//    . "\n주소 : {$row['mb_addr1']} {$row['mb_addr2']} {$row['mb_addr1']}<br>"
//    . "생년월일 : {$row['birth']}<br>"
//    . "은행명 : {$row['bankName']}<br>"
//    . "예금주 : {$row['accountHolder']}<br>"
//    . "계좌번호 : {$row['accountNumber']}<br>"
//    . "회원가입일 : {$row['mb_open_date']}<br>"
//    . "최근 리뉴얼 : {$row['renewal']}";
//    mailer("VMP 최고관리자", "vmp@gvmp.company", "991050@naver.com", $subject, $content, 1);
////    mailer("VMP 최고관리자", "vmp@gvmp.company", "2jeonghyeon@naver.com", $subject, $content, 1);
//    
//    
//
//        
//        
//        
//        
//        
//        // 3.09 수정 (로그아웃)
//        unset($_SESSION['ss_mb_id']);
//        
//    if (!$url)
//        $url = G5_URL;
//        
//        //소셜로그인 해제
//    if(function_exists('social_member_link_delete')){
//        social_member_link_delete($member['mb_id']);
//    }
//    
//    alert(''.$member['mb_nick'].'님께서는 '. date("Y년 m월 d일") .'에 회원에서 탈퇴 하셨습니다.', $url);
?>