<!--191227원본-->
<?php
include_once ('../dbConn.php');

// 간편 회원가입을 처리하는 로직


$_POST["user_name"] = trim(urldecode($_POST["user_name"])); // 회원 이름
$_POST["user_p_number"] = trim($_POST["user_p_number"]); // 핸드폰 번호

$_POST["recommender_name"] = trim(urldecode($_POST["recommender_name"])); // 추천인 이름
$_POST["recommender_number"] = trim($_POST["recommender_number"]); // 추천인 회원번호

$_POST["sponsor_name"] = trim(urldecode($_POST["sponsor_name"])); // 후원인 이름
$_POST["sponsor_number"] = trim($_POST["sponsor_number"]); // 후원인 회원번호
$_POST["sponsor_team"] = trim($_POST["sponsor_team"]); // 후원인 팀 배치  1 또는 2

$_POST["mb_id"] = trim($_POST["mb_id"]); // 구매자 ID

$_POST["user_p_number"] = hyphen_hp_number( $_POST["user_p_number"] ); // 핸드폰 번호에 하이픈 붙이기



if( $_POST["user_name"] == "" || $_POST["user_p_number"] == "" || $_POST["recommender_name"] == "" || $_POST["recommender_number"] == "" || $_POST["sponsor_name"] == "" || $_POST["sponsor_number"] == "" || $_POST["sponsor_team"] == "" || $_POST["user_p_number"] == "" || $_POST["mb_id"] == "" ) {
    echo "입력되지 않은 값이 존재합니다."; exit();
}

$checkRow = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$_POST["mb_id"]}'"));
if( (int)$checkRow["membership"] < 1 ) {
    echo "간편 회원가입권이 부족합니다."; exit();
}

$checkRow = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$_POST["recommender_number"]}'"));
if( $checkRow["mb_name"] != $_POST["recommender_name"] ) {
    echo "추천인 정보가 잘 못 되었습니다."; exit();
}

$checkRow = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$_POST["sponsor_number"]}'"));
if( $checkRow["mb_name"] != $_POST["sponsor_name"] ) {
    echo "후원인 정보가 잘 못 되었습니다."; exit();
}

$checkRow = mysql_fetch_array(mysql_query("select * from teamMembers where mb_id = '{$_POST["sponsor_number"]}'"));
if( $checkRow[$_POST["sponsor_team"]."T_ID"] != "" ) {
    echo "후원인 팀 선택이 잘 못 되었습니다."; exit();
}



// 사용될 ID 8자리 추출하는 로직
$temp2;
$row = mysql_fetch_row(mysql_query("select mb_no from g5_member order by mb_no desc limit 1"));
$row_temp = $row[0];
$temp = (int)$row_temp;
$temp2 = (string)$temp;
$temp2 = str_pad($temp2,"8","0",STR_PAD_LEFT);
///////////////////////////////




mysql_query("insert into g5_member set
        mb_id = '{$temp2}',
        mb_password = '*89C6B530AA78695E257E55D63C00A6EC9AD3E977',
        mb_name = '{$_POST["user_name"]}',
        mb_nick = '{$temp2}',
        mb_nick_date = curdate(),
        mb_email = '{$temp2}@gvmp.company',
        mb_level = '2',
        mb_hp = '{$_POST["user_p_number"]}',
        mb_adult = '0',
        mb_datetime = now(),
        mb_ip = '8.8.8.8',
        mb_email_certify = now(),
        mb_mailling = '1',
        mb_sms = '1',
        mb_open = '1',
        mb_open_date = curdate(),
        birth = '',
        bankName = '',
        accountHolder = '',
        accountNumber = '',
        accountType = 'VM',
        accountRank = 'VM',
        renewal = curdate()");
mysql_query("update g5_member set membership = membership - 1 where mb_id = '{$_POST["mb_id"]}'");
mysql_query("insert into membershipListTBL set mb_id = '{$temp2}', datetime = now(), constructor_id = '{$_POST["mb_id"]}'");
mysql_query("insert into teamMembers values(null, '{$temp2}', null, null, null, null, null, null)");
mysql_query("insert into genealogy values(NULL, '{$temp2}', '{$_POST["user_name"]}', '{$_POST["recommender_name"]}', '{$_POST["recommender_number"]}', '{$_POST["sponsor_name"]}', '{$_POST["sponsor_number"]}', {$_POST["sponsor_team"]})");
mysql_query("update teamMembers set {$_POST["sponsor_team"]}T_name = '{$_POST["user_name"]}', {$_POST["sponsor_team"]}T_ID = '{$temp2}' where mb_id = '{$_POST["sponsor_number"]}'");
mysql_query("insert into plusCountListTBL set mb_id = '{$temp2}'"); // VM 카운팅 리스트(증가)
mysql_query("insert into dayPoint set mb_id = '{$temp2}', date = NOW(), way = 'newRenewal'");

echo "true";



function hyphen_hp_number($hp) {
    $hp = preg_replace("/[^0-9]/", "", $hp);
    return preg_replace("/([0-9]{3})([0-9]{3,4})([0-9]{4})$/", "\\1-\\2-\\3", $hp);
}



?>