<?php
$sub_menu = "200100";
include_once("./_common.php");
include_once(G5_LIB_PATH."/register.lib.php");
include_once(G5_LIB_PATH.'/thumbnail.lib.php');
include_once($_SERVER['DOCUMENT_ROOT']."/myOffice/nameUpdate.php");

if ($w == 'u')
    check_demo();

auth_check($auth[$sub_menu], 'w');

check_admin_token();

$mb_id = trim($_POST['mb_id']);

// 휴대폰번호 체크
$mb_hp = hyphen_hp_number($_POST['mb_hp']);
if($mb_hp) {
    $result = exist_mb_hp($mb_hp, $mb_id);
    if ($result)
        alert($result);
}

// 인증정보처리
if($_POST['mb_certify_case'] && $_POST['mb_certify']) {
    $mb_certify = $_POST['mb_certify_case'];
    $mb_adult = $_POST['mb_adult'];
} else {
    $mb_certify = '';
    $mb_adult = 0;
}

$mb_zip1 = substr($_POST['mb_zip'], 0, 3);
$mb_zip2 = substr($_POST['mb_zip'], 3);



// 판매/구매 내역 추가하는 로직

if( $_POST['inputNum'] != "0" ) { // 판매/구매 내역이 1개라도 있다면 참
    for( $i=1 ; $i<=$_POST['inputNum'] ; $i++ ) { // 판매/구매 내역을 입력한 개수만큼 반복
        // 주문리스트 테이블에 DB 넣기
        mysql_query("insert into orderList values(null, '{$_POST['orderName' . $i]}', '{$_POST['orderDate' . $i]}', '{$_POST['orderID' . $i]}', {$_POST['orderCount' . $i]}, {$_POST['orderComs' . $i]} )");
        
        // 데일리 포인트 테이블에 DB 넣기
        mysql_query("insert into dayPoint(mb_id,VMC, VMR, VMP,date,way) value ('{$_POST['orderID' . $i]}', 0, 0, {$_POST['orderComs' . $i]}, '{$_POST['orderDate' . $i]} ".date("H:i:s")."', 'orderListAdmin2')");
        
        // 보유 포인트 증가 시키기
        mysql_query("update g5_member set VMP = VMP+{$_POST['orderComs' . $i]} where mb_id = '{$_POST['orderID' . $i]}'");
    }
}

///////////////////////////////////





$_POST['P_vmc'] = preg_replace("/[^0-9]/", "", $_POST['P_vmc']);
$_POST['P_vmr'] = preg_replace("/[^0-9]/", "", $_POST['P_vmr']);
$_POST['P_vmp'] = preg_replace("/[^0-9]/", "", $_POST['P_vmp']);
$_POST['P_vmg'] = preg_replace("/[^0-9]/", "", $_POST['P_vmg']);
$_POST['P_vmm'] = preg_replace("/[^0-9]/", "", $_POST['P_vmm']);

$_POST['M_vmc'] = preg_replace("/[^0-9]/", "", $_POST['M_vmc']);
$_POST['M_vmr'] = preg_replace("/[^0-9]/", "", $_POST['M_vmr']);
$_POST['M_vmp'] = preg_replace("/[^0-9]/", "", $_POST['M_vmp']);
$_POST['M_vmg'] = preg_replace("/[^0-9]/", "", $_POST['M_vmg']);
$_POST['M_vmm'] = preg_replace("/[^0-9]/", "", $_POST['M_vmm']);



// 지급/감소 포인트 통합하기
$P_vmc; $P_vmr; $P_vmp; $P_vmm;
$M_vmc; $M_vmr; $M_vmp; $M_vmm;

if( $_POST['P_vmc'] == "" )
    $P_vmc = 0;
else
    $P_vmc = $_POST['P_vmc'];

if( $_POST['P_vmr'] == "" )
    $P_vmr = 0;
else
    $P_vmr = $_POST['P_vmr'];

if( $_POST['P_vmp'] == "" )
    $P_vmp = 0;
else
    $P_vmp = $_POST['P_vmp'];

if( $_POST['P_vmg'] == "" )
    $P_vmg = 0;
else
    $P_vmg = $_POST['P_vmg'];

if( $_POST['P_vmm'] == "" )
    $P_vmm = 0;
else
    $P_vmm = $_POST['P_vmm'];

if( $_POST['M_vmc'] == "" )
    $M_vmc = 0;
else
    $M_vmc = $_POST['M_vmc'];

if( $_POST['M_vmr'] == "" )
    $M_vmr = 0;
else
    $M_vmr = $_POST['M_vmr'];

if( $_POST['M_vmp'] == "" )
    $M_vmp = 0;
else
    $M_vmp = $_POST['M_vmp'];

if( $_POST['M_vmg'] == "" )
    $M_vmg = 0;
else
    $M_vmg = $_POST['M_vmg'];

if( $_POST['M_vmm'] == "" )
    $M_vmm = 0;
else
    $M_vmm = $_POST['M_vmm'];



$T_VMC = $P_vmc + -$M_vmc;
$T_VMR = $P_vmr + -$M_vmr;
$T_VMP = $P_vmp + -$M_vmp;
$T_VMG = $P_vmg + -$M_vmg;
$T_VMM = $P_vmm + -$M_vmm;



if( $P_vmc > 0 || $P_vmr > 0 || $P_vmp > 0  || $P_vmg > 0  || $P_vmm > 0 ) {
    sql_query("insert into dayPoint(mb_id,VMC,VMR,VMP,VMM,VMG,date,way) value ('{$mb_id}',{$P_vmc},{$P_vmr},{$P_vmp},{$P_vmm},{$P_vmg},NOW(),'admin2')");
}

if( $M_vmc > 0 || $M_vmr > 0 || $M_vmp > 0 || $M_vmg > 0 || $M_vmm > 0 ) {
    mysql_query("insert into dayPoint(mb_id,VMC,VMR,VMP,VMM,VMG,date,way) value ('{$mb_id}',-{$M_vmc},-{$M_vmr},-{$M_vmp},-{$M_vmm},-{$M_vmg},NOW(),'admin2')");
}

if( $_POST["card_possible"] != "YES" )
    $_POST["card_possible"] = "NO";
if( $_POST["yolo"] != "YES" )
    $_POST["yolo"] = "NO";
    
$sql_common = "  mb_name = '{$_POST['mb_name']}',
                 mb_nick = '{$mb_id}',
                 mb_email = '{$_POST['mb_email']}',
                 mb_homepage = '{$_POST['mb_homepage']}',
                 mb_tel = '{$_POST['mb_tel']}',
                 mb_hp = '{$mb_hp}',
                 mb_certify = '{$mb_certify}',
                 mb_adult = '{$mb_adult}',
                 mb_zip1 = '$mb_zip1',
                 mb_zip2 = '$mb_zip2',
                 mb_addr1 = '{$_POST['mb_addr1']}',
                 mb_addr2 = '{$_POST['mb_addr2']}',
                 mb_addr3 = '{$_POST['mb_addr3']}',
                 mb_addr_jibeon = '{$_POST['mb_addr_jibeon']}',
                 mb_signature = '{$_POST['mb_signature']}',
                 mb_leave_date = '{$_POST['mb_leave_date']}',
                 mb_intercept_date='{$_POST['mb_intercept_date']}',
                 mb_memo = '{$_POST['mb_memo']}',
                 mb_mailling = '{$_POST['mb_mailling']}',
                 mb_sms = '{$_POST['mb_sms']}',
                 mb_open = '{$_POST['mb_open']}',
                 mb_profile = '{$_POST['mb_profile']}',
                 mb_level = '2',
                 birth = '{$_POST['mb_birth']}',
                 mb_1 = '{$_POST['mb_1']}',
                 mb_2 = '{$_POST['mb_2']}',
                 mb_3 = '{$_POST['mb_3']}',
                 mb_4 = '{$_POST['mb_4']}',
                 mb_5 = '{$_POST['mb_5']}',
                 mb_6 = '{$_POST['mb_6']}',
                 mb_7 = '{$_POST['mb_7']}',
                 mb_8 = '{$_POST['mb_8']}',
                 mb_9 = '{$_POST['mb_9']}',
                 mb_10 = '{$_POST['mb_10']}',"
                 . "bankName = '{$_POST['bankName']}',"
                 . "accountHolder = '{$_POST['accountHolder']}',"
                 . "accountNumber = '{$_POST['accountNumber']}',"
                 . "VMC = VMC + {$T_VMC},"
                 . "VMR = VMR + {$T_VMR},"
                 . "VMP = VMP + {$T_VMP},"
                 . "VMM = VMM + {$T_VMM},"
                 . "VMG = VMG + {$T_VMG},"
                 . "yolo = '{$_POST["yolo"]}',"
                 . "payment = '{$_POST["card_possible"]}' ";

if ($w == '')
{
    $mb = get_member($mb_id);
    if ($mb['mb_id'])
        alert('이미 존재하는 회원아이디입니다.\\nＩＤ : '.$mb['mb_id'].'\\n이름 : '.$mb['mb_name'].'\\n닉네임 : '.$mb['mb_nick'].'\\n메일 : '.$mb['mb_email']);

    // 닉네임중복체크
    $sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_nick = '{$_POST['mb_nick']}' ";
    $row = sql_fetch($sql);
    if ($row['mb_id'])
        alert('이미 존재하는 닉네임입니다.\\nＩＤ : '.$row['mb_id'].'\\n이름 : '.$row['mb_name'].'\\n닉네임 : '.$row['mb_nick'].'\\n메일 : '.$row['mb_email']);

    // 이메일중복체크
    $sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_email = '{$_POST['mb_email']}' ";
    $row = sql_fetch($sql);
//    if ($row['mb_id'])
//        alert('이미 존재하는 이메일입니다.\\nＩＤ : '.$row['mb_id'].'\\n이름 : '.$row['mb_name'].'\\n닉네임 : '.$row['mb_nick'].'\\n메일 : '.$row['mb_email']);

    sql_query(" insert into {$g5['member_table']} set mb_id = '{$mb_id}', mb_password = '".get_encrypt_string($mb_password)."', mb_datetime = '".G5_TIME_YMDHIS."', mb_ip = '{$_SERVER['REMOTE_ADDR']}', mb_email_certify = '".G5_TIME_YMDHIS."', {$sql_common} ");
}
else if ($w == 'u')
{
    $mb = get_member($mb_id);
    if (!$mb['mb_id'])
        alert('존재하지 않는 회원자료입니다.');

    if ($is_admin != 'super' && $mb['mb_level'] >= $member['mb_level'])
        alert('자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.');

    if ($is_admin !== 'super' && is_admin($mb['mb_id']) === 'super' ) {
        alert('최고관리자의 비밀번호를 수정할수 없습니다.');
    }

    if ($_POST['mb_id'] == $member['mb_id'] && $_POST['mb_level'] != $mb['mb_level'])
        alert($mb['mb_id'].' : 로그인 중인 관리자 레벨은 수정 할 수 없습니다.');

    // 닉네임중복체크
    $sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_nick = '{$_POST['mb_nick']}' and mb_id <> '$mb_id' ";
    $row = sql_fetch($sql);
//    if ($row['mb_id'])
//        alert('이미 존재하는 닉네임입니다.\\nＩＤ : '.$row['mb_id'].'\\n이름 : '.$row['mb_name'].'\\n닉네임 : '.$row['mb_nick'].'\\n메일 : '.$row['mb_email']);

    // 이메일중복체크
    $sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_email = '{$_POST['mb_email']}' and mb_id <> '$mb_id' ";
    $row = sql_fetch($sql);
//    if ($row['mb_id'])
//        alert('이미 존재하는 이메일입니다.\\nＩＤ : '.$row['mb_id'].'\\n이름 : '.$row['mb_name'].'\\n닉네임 : '.$row['mb_nick'].'\\n메일 : '.$row['mb_email']);

    $mb_dir = substr($mb_id,0,2);

    // 회원 아이콘 삭제
    if ($del_mb_icon)
        @unlink(G5_DATA_PATH.'/member/'.$mb_dir.'/'.$mb_id.'.gif');

    $image_regex = "/(\.(gif|jpe?g|png))$/i";
    $mb_icon_img = $mb_id.'.gif';

    // 아이콘 업로드
    if (isset($_FILES['mb_icon']) && is_uploaded_file($_FILES['mb_icon']['tmp_name'])) {
        if (!preg_match($image_regex, $_FILES['mb_icon']['name'])) {
            alert($_FILES['mb_icon']['name'] . '은(는) 이미지 파일이 아닙니다.');
        }

        if (preg_match($image_regex, $_FILES['mb_icon']['name'])) {
            $mb_icon_dir = G5_DATA_PATH.'/member/'.$mb_dir;
            @mkdir($mb_icon_dir, G5_DIR_PERMISSION);
            @chmod($mb_icon_dir, G5_DIR_PERMISSION);

            $dest_path = $mb_icon_dir.'/'.$mb_icon_img;

            move_uploaded_file($_FILES['mb_icon']['tmp_name'], $dest_path);
            chmod($dest_path, G5_FILE_PERMISSION);
            
            if (file_exists($dest_path)) {
                $size = @getimagesize($dest_path);
                if ($size[0] > $config['cf_member_icon_width'] || $size[1] > $config['cf_member_icon_height']) {
                    $thumb = null;
                    if($size[2] === 2 || $size[2] === 3) {
                        //jpg 또는 png 파일 적용
                        $thumb = thumbnail($mb_icon_img, $mb_icon_dir, $mb_icon_dir, $config['cf_member_icon_width'], $config['cf_member_icon_height'], true, true);
                        if($thumb) {
                            @unlink($dest_path);
                            rename($mb_icon_dir.'/'.$thumb, $dest_path);
                        }
                    }
                    if( !$thumb ){
                        // 아이콘의 폭 또는 높이가 설정값 보다 크다면 이미 업로드 된 아이콘 삭제
                        @unlink($dest_path);
                    }
                }
            }
        }
    }
    
    $mb_img_dir = G5_DATA_PATH.'/member_image/';
    if( !is_dir($mb_img_dir) ){
        @mkdir($mb_img_dir, G5_DIR_PERMISSION);
        @chmod($mb_img_dir, G5_DIR_PERMISSION);
    }
    $mb_img_dir .= substr($mb_id,0,2);

    // 회원 이미지 삭제
    if ($del_mb_img)
        @unlink($mb_img_dir.'/'.$mb_icon_img);

    // 아이콘 업로드
    if (isset($_FILES['mb_img']) && is_uploaded_file($_FILES['mb_img']['tmp_name'])) {
        if (!preg_match($image_regex, $_FILES['mb_img']['name'])) {
            alert($_FILES['mb_img']['name'] . '은(는) 이미지 파일이 아닙니다.');
        }
        
        if (preg_match($image_regex, $_FILES['mb_img']['name'])) {
            @mkdir($mb_img_dir, G5_DIR_PERMISSION);
            @chmod($mb_img_dir, G5_DIR_PERMISSION);
            
            $dest_path = $mb_img_dir.'/'.$mb_icon_img;
            
            move_uploaded_file($_FILES['mb_img']['tmp_name'], $dest_path);
            chmod($dest_path, G5_FILE_PERMISSION);

            if (file_exists($dest_path)) {
                $size = @getimagesize($dest_path);
                if ($size[0] > $config['cf_member_img_width'] || $size[1] > $config['cf_member_img_height']) {
                    $thumb = null;
                    if($size[2] === 2 || $size[2] === 3) {
                        //jpg 또는 png 파일 적용
                        $thumb = thumbnail($mb_icon_img, $mb_img_dir, $mb_img_dir, $config['cf_member_img_width'], $config['cf_member_img_height'], true, true);
                        if($thumb) {
                            @unlink($dest_path);
                            rename($mb_img_dir.'/'.$thumb, $dest_path);
                        }
                    }
                    if( !$thumb ){
                        // 아이콘의 폭 또는 높이가 설정값 보다 크다면 이미 업로드 된 아이콘 삭제
                        @unlink($dest_path);
                    }
                }
            }
        }
    }

    if ($mb_password)
        $sql_password = " , mb_password = '".get_encrypt_string($mb_password)."' ";
    else
        $sql_password = "";

    if ($passive_certify)
        $sql_certify = " , mb_email_certify = '".G5_TIME_YMDHIS."' ";
    else
        $sql_certify = "";

    nameUpdateFunc( $_POST['mb_name'], $mb_id );
    
    $sql = " update {$g5['member_table']}
                set {$sql_common}
                     {$sql_password}
                     {$sql_certify}
                where mb_id = '{$mb_id}' ";
    sql_query($sql);
    

    
    
    
}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');


goto_url('./member_form.php?'.$qstr.'&amp;w=u&amp;mb_id='.$mb_id . "&check=true", false);
?>