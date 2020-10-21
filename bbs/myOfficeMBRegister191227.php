<!--191227원본-->
<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');
include_once(G5_LIB_PATH.'/register.lib.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

$birth = trim($_POST['mb_birth']); // 생일
$bankName = trim($_POST['bankName']); // 은행명
$accountHolder = trim($_POST['mb_accountHolder']); // 예금주
$accountNumber = trim($_POST['mb_accountNumber']); // 계좌번호


$mb_recommender = trim($_POST['reg_mb_recommender']); // 추천인 이름
$mb_recommender_no = trim($_POST['reg_mb_recommender_no']); // 추천인 ID
$mb_sponsor = trim($_POST['reg_mb_sponsor']); // 후원인 이름
$mb_sponsor_no = trim($_POST['reg_mb_sponsor_no']); // 후원인 ID
$radiobtn = trim($_POST['radiobtn']); // 후원인 소속 팀

$accountType = trim($_POST['accountType']);

$mb_password    = trim($_POST['mb_password']);
$mb_password_re = trim($_POST['mb_password_re']);
$mb_name        = trim($_POST['mb_name']);
$mb_nick        = trim($_POST['mb_nick']);
$mb_email       = trim($_POST['mb_email']);
$mb_sex         = isset($_POST['mb_sex'])           ? trim($_POST['mb_sex'])         : "";
$mb_birth       = isset($_POST['mb_birth'])         ? trim($_POST['mb_birth'])       : "";
$mb_homepage    = isset($_POST['mb_homepage'])      ? trim($_POST['mb_homepage'])    : "";
$mb_tel         = isset($_POST['mb_tel'])           ? trim($_POST['mb_tel'])         : "";
$mb_hp          = isset($_POST['mb_hp'])            ? trim($_POST['mb_hp'])          : "";
$mb_zip1        = isset($_POST['mb_zip'])           ? substr(trim($_POST['mb_zip']), 0, 3) : "";
$mb_zip2        = isset($_POST['mb_zip'])           ? substr(trim($_POST['mb_zip']), 3)    : "";
$mb_addr1       = isset($_POST['mb_addr1'])         ? trim($_POST['mb_addr1'])       : "";
$mb_addr2       = isset($_POST['mb_addr2'])         ? trim($_POST['mb_addr2'])       : "";
$mb_addr3       = isset($_POST['mb_addr3'])         ? trim($_POST['mb_addr3'])       : "";
$mb_addr_jibeon = isset($_POST['mb_addr_jibeon'])   ? trim($_POST['mb_addr_jibeon']) : "";
$mb_signature   = isset($_POST['mb_signature'])     ? trim($_POST['mb_signature'])   : "";
$mb_profile     = isset($_POST['mb_profile'])       ? trim($_POST['mb_profile'])     : "";
$mb_recommend   = isset($_POST['mb_recommend'])     ? trim($_POST['mb_recommend'])   : "";
$mb_mailling    = isset($_POST['mb_mailling'])      ? trim($_POST['mb_mailling'])    : "";
$mb_sms         = isset($_POST['mb_sms'])           ? trim($_POST['mb_sms'])         : "";
$mb_1           = isset($_POST['mb_1'])             ? trim($_POST['mb_1'])           : "";
$mb_2           = isset($_POST['mb_2'])             ? trim($_POST['mb_2'])           : "";
$mb_3           = isset($_POST['mb_3'])             ? trim($_POST['mb_3'])           : "";
$mb_4           = isset($_POST['mb_4'])             ? trim($_POST['mb_4'])           : "";
$mb_5           = isset($_POST['mb_5'])             ? trim($_POST['mb_5'])           : "";
$mb_6           = isset($_POST['mb_6'])             ? trim($_POST['mb_6'])           : "";
$mb_7           = isset($_POST['mb_7'])             ? trim($_POST['mb_7'])           : "";
$mb_8           = isset($_POST['mb_8'])             ? trim($_POST['mb_8'])           : "";
$mb_9           = isset($_POST['mb_9'])             ? trim($_POST['mb_9'])           : "";
$mb_10          = isset($_POST['mb_10'])            ? trim($_POST['mb_10'])          : "";

$mb_name        = clean_xss_tags($mb_name);
$mb_email       = get_email_address($mb_email);
$mb_homepage    = clean_xss_tags($mb_homepage);
$mb_tel         = clean_xss_tags($mb_tel);
$mb_zip1        = preg_replace('/[^0-9]/', '', $mb_zip1);
$mb_zip2        = preg_replace('/[^0-9]/', '', $mb_zip2);
$mb_addr1       = clean_xss_tags($mb_addr1);
$mb_addr2       = clean_xss_tags($mb_addr2);
$mb_addr3       = clean_xss_tags($mb_addr3);
$mb_addr_jibeon = preg_match("/^(N|R)$/", $mb_addr_jibeon) ? $mb_addr_jibeon : '';

$mb_hp = hyphen_hp_number($mb_hp);

$link = mysql_connect("192.168.0.30","root","Neungsoft1!");

mysql_select_db("gyc5", $link);
$result = mysql_query("select mb_no from g5_member order by mb_no desc limit 1");

$temp2;
while($row = mysql_fetch_row($result)) {
    $row_temp = $row[0];
    $temp = (int)$row_temp;
    $temp2 = (string)$temp;
    $temp2 = str_pad($temp2,"8","0",STR_PAD_LEFT);
}

if( ! isset($_POST['vmc']) ) {
    $_POST['vmc'] = 0;
}
if( ! isset($_POST['vmr']) ) {
    $_POST['vmr'] = 0;
}
if( ! isset($_POST['vmp']) ) {
    $_POST['vmp'] = 0;
}




mysql_query("update g5_member set vmc = vmc - {$_POST['vmc']}, vmr = vmr - {$_POST['vmr']}, vmp = vmp - {$_POST['vmp']} where mb_id = '{$_POST['loginID']}'");
mysql_query("insert into dayPoint set mb_id = '{$_POST['loginID']}', VMC=-{$_POST['vmc']}, VMR=-{$_POST['vmr']}, VMP=-{$_POST['vmp']}, date=NOW(), way = 'vmJoin;{$temp2}'");

mysql_query("insert into dayPoint set mb_id = '{$temp2}', VMC=0, VMR=0, VMP=0,V=0,VCash=0,VPay=0, date=NOW(), way = 'vmJoin2'");

mysql_query("insert into teamMembers values(null, '{$temp2}', null, null, null, null, null, null)");

sql_query("insert into genealogy values(NULL, '{$temp2}', '{$mb_name}', '{$mb_recommender}', '{$mb_recommender_no}', '{$mb_sponsor}', '{$mb_sponsor_no}', {$radiobtn})");



if( $mb_sponsor_no == "99999999" ) {
    mysql_query("set session character_set_connection=utf8");
    mysql_query("set session character_set_results=utf8");
    mysql_query("set session character_set_client=utf8");
    mysql_query("insert into team3_list values(null, '{$mb_recommender_no}', '{$mb_name}', '{$temp2}')");
} else {
    // 유료 회원가입이라면 teamMembers DB 업데이트 시키기
    mysql_query("set session character_set_connection=utf8");
    mysql_query("set session character_set_results=utf8");
    mysql_query("set session character_set_client=utf8");
    mysql_query("update teamMembers set {$radiobtn}T_name = '{$mb_name}', {$radiobtn}T_ID = '{$temp2}' where mb_id = '{$mb_sponsor_no}'");

    mysql_query("insert into plusCountListTBL set mb_id = '{$temp2}'"); // VM 카운팅 리스트(증가)
}


$subject = "VMP 계정 정보";
$content = "회원 가입을 진심으로 감사드립니다.<br><br>아이디 : {$temp2}";
mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $mb_email, $subject, $content, 1);

$mb_id = $temp2;
mysql_query("set session character_set_connection=utf8");
mysql_query("set session character_set_results=utf8");
mysql_query("set session character_set_client=utf8");




$sql = " insert into {$g5['member_table']}
                set mb_id = '{$temp2}',
                     mb_password = '".get_encrypt_string($mb_password)."',
                     mb_name = '{$mb_name}',
                     mb_nick = '{$temp2}',
                     mb_nick_date = '".G5_TIME_YMD."',
                     mb_email = '{$mb_email}',
                     mb_homepage = '{$mb_homepage}',
                     mb_tel = '{$mb_tel}',
                     mb_hp = '{$mb_hp}',
                     mb_zip1 = '{$mb_zip1}',
                     mb_zip2 = '{$mb_zip2}',
                     mb_addr1 = '{$mb_addr1}',
                     mb_addr2 = '{$mb_addr2}',
                     mb_addr3 = '{$mb_addr3}',
                     mb_addr_jibeon = '{$mb_addr_jibeon}',
                     mb_signature = '{$mb_signature}',
                     mb_profile = '{$mb_profile}',
                     mb_today_login = '".G5_TIME_YMDHIS."',
                     mb_datetime = '".G5_TIME_YMDHIS."',
                     mb_ip = '{$_SERVER['REMOTE_ADDR']}',
                     mb_level = '{$config['cf_register_level']}',
                     mb_recommend = '{$mb_recommend}',
                     mb_login_ip = '{$_SERVER['REMOTE_ADDR']}',
                     mb_mailling = '{$mb_mailling}',
                     mb_sms = '{$mb_sms}',
                     mb_open = '{$mb_open}',
                     mb_open_date = '".G5_TIME_YMD."',
                     mb_1 = '{$mb_1}',
                     mb_2 = '{$mb_2}',
                     mb_3 = '{$mb_3}',
                     mb_4 = '{$mb_4}',
                     mb_5 = '{$mb_5}',
                     mb_6 = '{$mb_6}',
                     mb_7 = '{$mb_7}',
                     mb_8 = '{$mb_8}',
                     mb_9 = '{$mb_9}',
                     mb_10 = '{$mb_10}',
                     birth = '{$birth}',
                     bankName = '{$bankName}',
                     accountHolder = '{$accountHolder}',
                     accountNumber = '{$accountNumber}',
                     accountType = '{$accountType}',
                     accountRank = '{$accountType}',
                     renewal = curdate()
                     {$sql_certify} ";

// 이메일 인증을 사용하지 않는다면 이메일 인증시간을 바로 넣는다
if (!$config['cf_use_email_certify'])
    $sql .= " , mb_email_certify = '".G5_TIME_YMDHIS."' ";
sql_query($sql);

// 회원가입 포인트 부여
insert_point($mb_id, $config['cf_register_point'], '회원가입 축하', '@member', $mb_id, '회원가입');

// 추천인에게 포인트 부여
if ($config['cf_use_recommend'] && $mb_recommend)
    insert_point($mb_recommend, $config['cf_recommend_point'], $mb_id.'의 추천인', '@member', $mb_recommend, $mb_id.' 추천');

// 회원님께 메일 발송
//    if ($config['cf_email_mb_member']) {
//        $subject = '['.$config['cf_title'].'] 회원가입을 축하드립니다.';
//
//        // 어떠한 회원정보도 포함되지 않은 일회용 난수를 생성하여 인증에 사용
//        if ($config['cf_use_email_certify']) {
//            $mb_md5 = md5(pack('V*', rand(), rand(), rand(), rand()));
//            sql_query(" update {$g5['member_table']} set mb_email_certify2 = '$mb_md5' where mb_id = '$mb_id' ");
//            $certify_href = G5_BBS_URL.'/email_certify.php?mb_id='.$mb_id.'&amp;mb_md5='.$mb_md5;
//        }
//
//        ob_start();
//        include_once ('./register_form_update_mail1.php');
//        $content = ob_get_contents();
//        ob_end_clean();
//
//        mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $mb_email, $subject, $content, 1);
//
//        // 메일인증을 사용하는 경우 가입메일에 인증 url이 있으므로 인증메일을 다시 발송되지 않도록 함
//        if($config['cf_use_email_certify'])
//            $old_email = $mb_email;
//    }

// 최고관리자님께 메일 발송
if ($config['cf_email_mb_super_admin']) {
    $subject = '['.$config['cf_title'].'] '.$mb_nick .' 님께서 회원으로 가입하셨습니다.';

    ob_start();
    include_once ('./register_form_update_mail2.php');
    $content = ob_get_contents();
    ob_end_clean();

    mailer($mb_nick, $mb_email, $config['cf_admin_email'], $subject, $content, 1);
}





$result = mysql_query("select * from g5_member where mb_id = '{$temp2}'");
$row = mysql_fetch_array($result);


$strData;


// 관리자한테 문자 보내기
$strData = urlencode("[{$accountType} 회원가입] {$row['mb_name']}({$row['mb_id']})\nVMC : {$row['VMC']} \nVMR : {$row['VMR']} \nVMP : {$row['VMP']}\n"
    . "\n이메일 : {$row['mb_email']}\n연락처 : {$row['mb_hp']}"
    . "\n주소 : {$row['mb_addr1']} {$row['mb_addr2']} {$row['mb_addr1']}\n"
    . "생년월일 : {$row['birth']}\n"
    . "은행명 : {$row['bankName']}\n"
    . "예금주 : {$row['accountHolder']}\n"
    . "계좌번호 : {$row['accountNumber']}\n"
    . "회원가입일 : {$row['mb_open_date']}\n"
    . "추천인 : {$mb_recommender}({$mb_recommender_no}))\n"
    . "비밀번호 : {$_POST['mb_password']}");

$subject = "{$accountType} 회원가입 {$row['mb_name']}({$row['mb_id']}))";
$content = "<h3>{$accountType} 회원가입</h3><br><br>";
$content .= "{$row['mb_name']}({$row['mb_id']})<br><br>"
    . "이메일 : {$row['mb_email']}<br>연락처 : {$row['mb_hp']}<br>"
    . "주소 : {$row['mb_addr1']} {$row['mb_addr2']}<br>"
    . "생년월일 : {$row['birth']}<br>"
    . "은행명 : {$row['bankName']}<br>"
    . "예금주 : {$row['accountHolder']}<br>"
    . "계좌번호 : {$row['accountNumber']}<br>"
    . "회원가입일 : {$row['mb_open_date']}<br>"
    . "추천인 : {$mb_recommender}({$mb_recommender_no})"
    . "후원인 : {$mb_sponsor}({$mb_sponsor_no})의 {$radiobtn}팀<br>"
    . "비밀번호 : {$_POST['mb_password']}";
mysql_query("update teamMembers set {$radiobtn}T_name = '{$mb_name}', {$radiobtn}T_ID = '{$temp2}' where mb_id = '{$mb_sponsor_no}'");





//        getFromUrl("http://gvmp.company/sms/sms.php?strData={$strData}&strTelList=01021156866;&strCallBack=07074515121");
//    getFromUrl("http://gvmp.company/sms/sms.php?strData={$strData}&strTelList=01026591346;&strCallBack=07074515121");

mailer("VMP 최고관리자", "vmp@gvmp.company", "991050@naver.com", $subject, $content, 1);
//    mailer("VMP 최고관리자", "vmp@gvmp.company", "2jeonghyeon@naver.com", $subject, $content, 1);




echo "<input type=\"hidden\" id=\"id\" value=\"{$temp2}\">";
echo "<input type=\"hidden\" id=\"hp\" value=\"{$mb_hp}\">";

echo "<script>alert('회원가입이 완료되었습니다.');window.opener.location.reload();close();</script>";

?>
<script  src="http://code.jquery.com/jquery-latest.min.js"></script>
<!--<script>
    var data = "strData=[VMP] 회원가입을 축하드립니다. 아이디는 " + $('#id').val() + "입니다.&strTelList=" + $('#hp').val() + ";&strCallBack=07074515121";

    $.ajax({
        url:'/sms/sms.php',
        type:'POST',
        data: data
    });


    opener.parent.location.reload();
    close();
</script>-->