<?php
$sub_menu = "200100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

if ($w == '') {
    $required_mb_id = 'required';
    $required_mb_id_class = 'required alnum_';
    $required_mb_password = 'required';
    $sound_only = '<strong class="sound_only">필수</strong>';

    $mb['mb_mailling'] = 1;
    $mb['mb_open'] = 1;
    $mb['mb_level'] = $config['cf_register_level'];
    $html_title = '추가';
} else if ($w == 'u') {
    $mb = get_member($mb_id);
    if (!$mb['mb_id'])
        alert('존재하지 않는 회원자료입니다.');

    if ($is_admin != 'super' && $mb['mb_level'] >= $member['mb_level'])
        alert('자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.');

    $required_mb_id = 'readonly';
    $required_mb_password = '';
    $html_title = '수정';

    $mb['mb_name'] = get_text($mb['mb_name']);
    $mb['mb_nick'] = get_text($mb['mb_nick']);
    $mb['mb_email'] = get_text($mb['mb_email']);
    $mb['mb_homepage'] = get_text($mb['mb_homepage']);
    $mb['birth'] = get_text($mb['birth']);
    $mb['mb_tel'] = get_text($mb['mb_tel']);
    $mb['mb_hp'] = get_text($mb['mb_hp']);
    $mb['bankName'] = get_text($mb['bankName']);
    $mb['accountHolder'] = get_text($mb['accountHolder']);
    $mb['accountNumber'] = get_text($mb['accountNumber']);
    $mb['mb_addr1'] = get_text($mb['mb_addr1']);
    $mb['mb_addr2'] = get_text($mb['mb_addr2']);
    $mb['mb_addr3'] = get_text($mb['mb_addr3']);
    $mb['mb_signature'] = get_text($mb['mb_signature']);
    $mb['mb_recommend'] = get_text($mb['mb_recommend']);
    $mb['mb_profile'] = get_text($mb['mb_profile']);
    $mb['mb_1'] = get_text($mb['mb_1']);
    $mb['mb_2'] = get_text($mb['mb_2']);
    $mb['mb_3'] = get_text($mb['mb_3']);
    $mb['mb_4'] = get_text($mb['mb_4']);
    $mb['mb_5'] = get_text($mb['mb_5']);
    $mb['mb_6'] = get_text($mb['mb_6']);
    $mb['mb_7'] = get_text($mb['mb_7']);
    $mb['mb_8'] = get_text($mb['mb_8']);
    $mb['mb_9'] = get_text($mb['mb_9']);
    $mb['mb_10'] = get_text($mb['mb_10']);
} else
    alert('제대로 된 값이 넘어오지 않았습니다.');

// 본인확인방법
switch ($mb['mb_certify']) {
    case 'hp':
        $mb_certify_case = '휴대폰';
        $mb_certify_val = 'hp';
        break;
    case 'ipin':
        $mb_certify_case = '아이핀';
        $mb_certify_val = 'ipin';
        break;
    case 'admin':
        $mb_certify_case = '관리자 수정';
        $mb_certify_val = 'admin';
        break;
    default:
        $mb_certify_case = '';
        $mb_certify_val = 'admin';
        break;
}

// 본인확인
$mb_certify_yes = $mb['mb_certify'] ? 'checked="checked"' : '';
$mb_certify_no = !$mb['mb_certify'] ? 'checked="checked"' : '';

// 성인인증
$mb_adult_yes = $mb['mb_adult'] ? 'checked="checked"' : '';
$mb_adult_no = !$mb['mb_adult'] ? 'checked="checked"' : '';

//메일수신
$mb_mailling_yes = $mb['mb_mailling'] ? 'checked="checked"' : '';
$mb_mailling_no = !$mb['mb_mailling'] ? 'checked="checked"' : '';

// SMS 수신
$mb_sms_yes = $mb['mb_sms'] ? 'checked="checked"' : '';
$mb_sms_no = !$mb['mb_sms'] ? 'checked="checked"' : '';

// 정보 공개
$mb_open_yes = $mb['mb_open'] ? 'checked="checked"' : '';
$mb_open_no = !$mb['mb_open'] ? 'checked="checked"' : '';

if (isset($mb['mb_certify'])) {
    // 날짜시간형이라면 drop 시킴
    if (preg_match("/-/", $mb['mb_certify'])) {
        sql_query(" ALTER TABLE `{$g5['member_table']}` DROP `mb_certify` ", false);
    }
} else {
    sql_query(" ALTER TABLE `{$g5['member_table']}` ADD `mb_certify` TINYINT(4) NOT NULL DEFAULT '0' AFTER `mb_hp` ", false);
}

//if (isset($mb['mb_adult'])) {
//    // 디비에 엄청난 부하를 주는 lock을 걸어 주석처리. 불필요한듯 판단 됨.
//    sql_query(" ALTER TABLE `{$g5['member_table']}` CHANGE `mb_adult` `mb_adult` TINYINT(4) NOT NULL DEFAULT '0' ", false);
//} else {
//    sql_query(" ALTER TABLE `{$g5['member_table']}` ADD `mb_adult` TINYINT NOT NULL DEFAULT '0' AFTER `mb_certify` ", false);
//}

// 지번주소 필드추가
if (!isset($mb['mb_addr_jibeon'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_addr_jibeon` varchar(255) NOT NULL DEFAULT '' AFTER `mb_addr2` ", false);
}

// 건물명필드추가
if (!isset($mb['mb_addr3'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_addr3` varchar(255) NOT NULL DEFAULT '' AFTER `mb_addr2` ", false);
}

// 중복가입 확인필드 추가
if (!isset($mb['mb_dupinfo'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_dupinfo` varchar(255) NOT NULL DEFAULT '' AFTER `mb_adult` ", false);
}

// 이메일인증 체크 필드추가
if (!isset($mb['mb_email_certify2'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_email_certify2` varchar(255) NOT NULL DEFAULT '' AFTER `mb_email_certify` ", false);
}

if ($mb['mb_intercept_date'])
    $g5['title'] = "차단된 ";
else
    $g5['title'] .= "";
$g5['title'] .= '회원 ' . $html_title;
include_once('./admin.head.php');

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js
?>

<script>
    function mb_delete() {
        if (window.confirm("회원 탈퇴를 진행 하시겠습니까?")) {
            window.location.href = '/myOffice/withdrawal2.php?mb_id=' + $("#mb_id").val();
        }
    }
</script>

<form name="fmember" id="fmember" action="./member_form_update_test.php" onsubmit="return fmember_submit(this);" method="post" enctype="multipart/form-data">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="">

    <div class="tbl_frm01 tbl_wrap">
        <table>
            <caption><?php echo $g5['title']; ?></caption>
            <colgroup>
                <col class="grid_4">
                <col>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
                <tr>
                    <th scope="row"><label for="mb_id">아이디<?php echo $sound_only ?></label></th>
                    <td>
                        <input type="text" name="mb_id" value="<?php echo $mb['mb_id'] ?>" id="mb_id" <?php echo $required_mb_id ?> class="frm_input <?php echo $required_mb_id_class ?>" size="15"  maxlength="20">
                        <?php if ($w == 'u') { ?>
                            <!--<a href="./boardgroupmember_form.php?mb_id=-->
                            <?php // echo $mb['mb_id'] ?>
                            <!--" class="btn_frmline">접근가능그룹보기</a>-->
                        <?php } ?>
                    </td>
                    <th scope="row"><label for="mb_password">비밀번호<?php echo $sound_only ?></label></th>
                    <td><input type="password" name="mb_password" id="mb_password" <?php echo $required_mb_password ?> class="frm_input <?php echo $required_mb_password ?>" size="15" maxlength="20"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="mb_name">이름(실명)<strong class="sound_only">필수</strong></label></th>
                    <td><input type="text" name="mb_name" value="<?php echo $mb['mb_name'] ?>" id="mb_name" required class="required frm_input" size="15"  maxlength="20"></td>
                    <th scope="row">생년월일</th>
                    <td><input type="text" name="mb_birth" value="<?php echo $mb['birth'] ?>" id="mb_birth" required class="required frm_input"></td>

                </tr>
                <tr>
                    <!-- <th scope="row"><label for="mb_level">회원 권한</label></th>
                    <td><?php echo get_member_level_select('mb_level', 1, $member['mb_level'], $mb['mb_level']) ?></td>
                            <th scope="row"><label for="mb_nick">닉네임<strong class="sound_only">필수</strong></label></th>
                    <td><input type="text" name="mb_nick" value="<?php echo $mb['mb_nick'] ?>" id="mb_nick" required class="required frm_input" size="15"  maxlength="20"></td> -->
                </tr>
                <tr>
                    <th scope="row"><label for="mb_hp">휴대폰번호</label></th>
                    <td><input type="text" name="mb_hp" value="<?php echo $mb['mb_hp'] ?>" id="mb_hp" class="frm_input" size="15" maxlength="20"></td>
                    <th scope="row"><label for="mb_email">E-mail<strong class="sound_only">필수</strong></label></th>
                    <td><input type="text" name="mb_email" value="<?php echo $mb['mb_email'] ?>" id="mb_email" maxlength="100" required class="required frm_input email" size="30"></td>
                    <!-- <th scope="row"><label for="mb_homepage">홈페이지</label></th>
                    <td><input type="text" name="mb_homepage" value="<?php echo $mb['mb_homepage'] ?>" id="mb_homepage" class="frm_input" maxlength="255" size="15"></td> -->
                </tr>
                <tr>
                    <th scope="row">회원포인트 지급</th>
                    <td class="tbl_layout">
                        <label for="vmc">VMC</label>
                        <input type="text" name="P_vmc" value="" id="vmc" class="frm_input" size="15" maxlength="20" onchange="getNumber(this);" onkeyup="getNumber(this);" ><br>
                        <!--        	<label for="vmr">VMR</label>
                                        <input type="text" name="P_vmr" value="" id="vmr" class="frm_input" size="15" maxlength="20" onchange="getNumber(this);" onkeyup="getNumber(this);"><br>-->
                        <label for="vmp">VMP</label>
                        <input type="text" name="P_vmp" value="" id="vmp" class="frm_input" size="15" maxlength="20" onchange="getNumber(this);" onkeyup="getNumber(this);"><br>
                        <label for="vmc">VMM</label>
                        <input type="text" name="P_vmm" value="" id="vmm" class="frm_input" size="15" maxlength="20" onchange="getNumber(this);" onkeyup="getNumber(this);" ><br>
                        <label for="biz">비즈머니</label>
                        <input type="text" name="P_biz" value="" id="biz" class="frm_input" size="15" maxlength="20" onchange="getNumber(this);" onkeyup="getNumber(this);"><br>
                        <label class="text_letter" for="vmg">금 고</label>
                        <input type="text" name="P_vmg" value="" id="vmg" class="frm_input" size="15" maxlength="20" onchange="getNumber(this);" onkeyup="getNumber(this);">
                    </td>
                    <th scope="row">회원포인트 감소</th>
                    <td class="tbl_layout">
                        <label for="M_vmc">VMC</label>
                        <input type="text" name="M_vmc" value="" id="M_vmc" class="frm_input" size="15" maxlength="20" onchange="getNumber(this);" onkeyup="getNumber(this);"><br>
                        <!--        	<label for="M_vmr">VMR</label>
                                        <input type="text" name="M_vmr" value="" id="M_vmr" class="frm_input" size="15" maxlength="20" onchange="getNumber(this);" onkeyup="getNumber(this);"><br>-->
                        <label for="M_vmp">VMP</label>
                        <input type="text" name="M_vmp" value="" id="M_vmp" class="frm_input" size="15" maxlength="20" onchange="getNumber(this);" onkeyup="getNumber(this);"><br>
                        <label for="M_vmp">VMM</label>
                        <input type="text" name="M_vmm" value="" id="M_vmm" class="frm_input" size="15" maxlength="20" onchange="getNumber(this);" onkeyup="getNumber(this);"><br>
                        <label for="M_biz">비즈머니</label>
                        <input type="text" name="M_biz" value="" id="M_biz" class="frm_input" size="15" maxlength="20" onchange="getNumber(this);" onkeyup="getNumber(this);"><br>
                        <label class="text_letter" for="M_vmg">금 고</label>
                        <input type="text" name="M_vmg" value="" id="M_vmg" class="frm_input" size="15" maxlength="20" onchange="getNumber(this);" onkeyup="getNumber(this);">
                    </td>
                </tr>

<!-- <tr>
    <th scope="row"><label for="mb_tel">전화번호</label></th>
    <td><input type="text" name="mb_tel" value="<?php echo $mb['mb_tel'] ?>" id="mb_tel" class="frm_input" size="15" maxlength="20"></td>
</tr> --> 
                <tr>
                    <th scope="row">은행정보</th>
                    <td>
                        <!-- <input type="radio" name="mb_certify_case" value="ipin" id="mb_certify_ipin" <?php if ($mb['mb_certify'] == 'ipin') echo 'checked="checked"'; ?>>
                        <label for="mb_certify_ipin">아이핀</label>
                        <input type="radio" name="mb_certify_case" value="hp" id="mb_certify_hp" <?php if ($mb['mb_certify'] == 'hp') echo 'checked="checked"'; ?>>
                        <label for="mb_certify_hp">휴대폰</label> -->
                        <label for="bankName">은행명</label>
                        <input type="text" name="bankName" value="<?php echo $mb['bankName'] ?>" id="bankName" required class="required frm_input" placeholder="은행명" style="margin: 0 0 5px 12px;"><br>
                        <label for="accountHolder">예금주</label>
                        <input type="text" name="accountHolder" value="<?php echo $mb['accountHolder'] ?>" id="accountHolderbirth" required class="required frm_input" placeholder="예금주" style="margin: 0 0 5px 12px;"><br>
                        <label for="accountNumber">계좌번호</label>
                        <input type="text" name="accountNumber" value="<?php echo $mb['accountNumber'] ?>" id="accountNumber" required class="required frm_input" placeholder="계좌번호">
                    </td>
                    <th scope="row">카드결제<br>리브몰</th>
                    <td>
                        <input type="checkbox" name="card_possible" value="YES" style="margin-right: 5px" <?php if ($mb['payment'] == "YES") echo 'checked="checked"'; ?>>카드결제 가능<br>
                        <input type="checkbox" name="yolo" value="YES" style="margin-right: 5px" <?php if ($mb['yolo'] == "YES") echo 'checked="checked"'; ?>>리브몰 사용 가능
                    </td>
                </tr>
               <!-- <tr>
                    <th scope="row">본인확인</th>
                    <td>
                        <input type="radio" name="mb_certify" value="1" id="mb_certify_yes" <?php echo $mb_certify_yes; ?>>
                        <label for="mb_certify_yes">예</label>
                        <input type="radio" name="mb_certify" value="" id="mb_certify_no" <?php echo $mb_certify_no; ?>>
                        <label for="mb_certify_no">아니오</label>
                    </td>
                    <th scope="row">성인인증</th>
                    <td>
                        <input type="radio" name="mb_adult" value="1" id="mb_adult_yes" <?php echo $mb_adult_yes; ?>>
                        <label for="mb_adult_yes">예</label>
                        <input type="radio" name="mb_adult" value="0" id="mb_adult_no" <?php echo $mb_adult_no; ?>>
                        <label for="mb_adult_no">아니오</label>
                    </td>
                </tr> --> 
                <tr>
                    <th scope="row">주소</th>
                    <td colspan="3" class="td_addr_line">
                        <label for="mb_zip" class="sound_only">우편번호</label>
                        <input type="text" name="mb_zip" value="<?php echo $mb['mb_zip1'] . $mb['mb_zip2']; ?>" id="mb_zip" class="frm_input readonly" size="5" maxlength="6">
                        <button type="button" class="btn_frmline" onclick="win_zip('fmember', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소 검색</button><br>
                        <input type="text" name="mb_addr1" value="<?php echo $mb['mb_addr1'] ?>" id="mb_addr1" class="frm_input readonly" size="60">
                        <label for="mb_addr1">기본주소</label><br>
                        <input type="text" name="mb_addr2" value="<?php echo $mb['mb_addr2'] ?>" id="mb_addr2" class="frm_input" size="60">
                        <label for="mb_addr2">상세주소</label>
                        <br>
                        <input type="text" name="mb_addr3" value="<?php echo $mb['mb_addr3'] ?>" id="mb_addr3" class="frm_input" size="60">
                        <label for="mb_addr3">참고항목</label>
                        <input type="hidden" name="mb_addr_jibeon" value="<?php echo $mb['mb_addr_jibeon']; ?>"><br>
                    </td>
                </tr>
               <!--  <tr>
                    <th scope="row"><label for="mb_icon">회원아이콘</label></th>
                    <td colspan="3">
                <?php echo help('이미지 크기는 <strong>넓이 ' . $config['cf_member_icon_width'] . '픽셀 높이 ' . $config['cf_member_icon_height'] . '픽셀</strong>로 해주세요.') ?>
                        <input type="file" name="mb_icon" id="mb_icon">
                <?php
                $mb_dir = substr($mb['mb_id'], 0, 2);
                $icon_file = G5_DATA_PATH . '/member/' . $mb_dir . '/' . $mb['mb_id'] . '.gif';
                if (file_exists($icon_file)) {
                    $icon_url = G5_DATA_URL . '/member/' . $mb_dir . '/' . $mb['mb_id'] . '.gif';
                    echo '<img src="' . $icon_url . '" alt="">';
                    echo '<input type="checkbox" id="del_mb_icon" name="del_mb_icon" value="1">삭제';
                }
                ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="mb_img">회원이미지</label></th>
                    <td colspan="3">
                <?php echo help('이미지 크기는 <strong>넓이 ' . $config['cf_member_img_width'] . '픽셀 높이 ' . $config['cf_member_img_height'] . '픽셀</strong>로 해주세요.') ?>
                        <input type="file" name="mb_img" id="mb_img">
                <?php
                $mb_dir = substr($mb['mb_id'], 0, 2);
                $icon_file = G5_DATA_PATH . '/member_image/' . $mb_dir . '/' . $mb['mb_id'] . '.gif';
                if (file_exists($icon_file)) {
                    $icon_url = G5_DATA_URL . '/member_image/' . $mb_dir . '/' . $mb['mb_id'] . '.gif';
                    echo '<img src="' . $icon_url . '" alt="">';
                    echo '<input type="checkbox" id="del_mb_img" name="del_mb_img" value="1">삭제';
                }
                ?>
                    </td>
                </tr> -->
                <tr>
                    <th scope="row">메일 수신</th>
                    <td>
                        <input type="radio" name="mb_mailling" value="1" id="mb_mailling_yes" <?php echo $mb_mailling_yes; ?>>
                        <label for="mb_mailling_yes">예</label>
                        <input type="radio" name="mb_mailling" value="0" id="mb_mailling_no" <?php echo $mb_mailling_no; ?>>
                        <label for="mb_mailling_no">아니오</label>
                    </td>
                    <th scope="row"><label for="mb_sms_yes">SMS 수신</label></th>
                    <td>
                        <input type="radio" name="mb_sms" value="1" id="mb_sms_yes" <?php echo $mb_sms_yes; ?>>
                        <label for="mb_sms_yes">예</label>
                        <input type="radio" name="mb_sms" value="0" id="mb_sms_no" <?php echo $mb_sms_no; ?>>
                        <label for="mb_sms_no">아니오</label>
                    </td>
                </tr>
               <!-- <tr>
                    <th scope="row">정보 공개</th>
                    <td colspan="3">
                        <input type="radio" name="mb_open" value="1" id="mb_open_yes" <?php echo $mb_open_yes; ?>>
                        <label for="mb_open_yes">예</label>
                        <input type="radio" name="mb_open" value="0" id="mb_open_no" <?php echo $mb_open_no; ?>>
                        <label for="mb_open_no">아니오</label>
                    </td>
                </tr> --> 
                <!-- <tr>
                    <th scope="row"><label for="mb_signature">서명</label></th>
                    <td colspan="3"><textarea  name="mb_signature" id="mb_signature"><?php echo $mb['mb_signature'] ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><label for="mb_profile">자기 소개</label></th>
                    <td colspan="3"><textarea name="mb_profile" id="mb_profile"><?php echo $mb['mb_profile'] ?></textarea></td>
                </tr> -->
                <tr>
                    <th scope="row"><label for="mb_memo">메모</label></th>
                    <td colspan="3"><textarea name="mb_memo" id="mb_memo"><?php echo $mb['mb_memo'] ?></textarea></td>
                </tr>

                <?php if ($w == 'u') { ?>
                    <tr>
                        <th scope="row">회원가입일</th>
                        <td><?php echo $mb['mb_datetime'] ?></td>
                        <th scope="row">최근접속일</th>
                        <td><?php echo $mb['mb_today_login'] ?></td>
                    </tr>
                   <!-- <tr>
                        <th scope="row">IP</th>
                        <td colspan="3"><?php echo $mb['mb_ip'] ?></td>
                    </tr> --> 
                    <?php if ($config['cf_use_email_certify']) { ?>
                        <tr>
                            <th scope="row">인증일시</th>
                            <td colspan="3">
                                <?php if ($mb['mb_email_certify'] == '0000-00-00 00:00:00') { ?>
                                    <?php echo help('회원님이 메일을 수신할 수 없는 경우 등에 직접 인증처리를 하실 수 있습니다.') ?>
                                    <input type="checkbox" name="passive_certify" id="passive_certify">
                                    <label for="passive_certify">수동인증</label>
                                <?php } else { ?>
                                    <?php echo $mb['mb_email_certify'] ?>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>

                <?php if ($config['cf_use_recommend']) { // 추천인 사용 ?>
                    <tr>
                        <th scope="row">추천인</th>
                        <td colspan="3"><?php echo ($mb['mb_recommend'] ? get_text($mb['mb_recommend']) : '없음'); // 081022 : CSRF 보안 결함으로 인한 코드 수정                 ?></td>
                    </tr>
                <?php } ?>

                <tr>
                    <th scope="row"><label for="mb_leave_date">회원탈퇴</label></th>
                    <td>
                        <button type="button" class="btn_frmline" onclick="mb_delete()">탈퇴 시키기</button>
            <!--            <input type="text" name="mb_leave_date" value="<?php echo $mb['mb_leave_date'] ?>" id="mb_leave_date" class="frm_input" maxlength="8">
                        <input type="checkbox" value="<?php echo date("Ymd"); ?>" id="mb_leave_date_set_today" onclick="if (this.form.mb_leave_date.value==this.form.mb_leave_date.defaultValue) {
            this.form.mb_leave_date.value=this.value; } else { this.form.mb_leave_date.value=this.form.mb_leave_date.defaultValue; }">
                        <label for="mb_leave_date_set_today">탈퇴일을 오늘로 지정</label>-->
                    </td>
                   <!-- <th scope="row">접근차단일자</th>
                    <td>
                        <input type="text" name="mb_intercept_date" value="<?php echo $mb['mb_intercept_date'] ?>" id="mb_intercept_date" class="frm_input" maxlength="8">
                        <input type="checkbox" value="<?php echo date("Ymd"); ?>" id="mb_intercept_date_set_today" onclick="if
            (this.form.mb_intercept_date.value==this.form.mb_intercept_date.defaultValue) { this.form.mb_intercept_date.value=this.value; } else {
            this.form.mb_intercept_date.value=this.form.mb_intercept_date.defaultValue; }">
                        <label for="mb_intercept_date_set_today">접근차단일을 오늘로 지정</label>
                    </td> --> 
                </tr>
                <!--2019년 11월 18일 추가, 권한 설정 시작 -->
                <?php if ($is_admin == 'super') {
                    ?>

                    <tr>
                        <th scope="row">권한 설정</th>
                        <td colspan="3" class="j​urisdiction">

                            <?php
                            $admin_sql = "select * from admin_menu";
                            $admin_menu = sql_query($admin_sql);



                            for ($i = 0; $admin_row = sql_fetch_array($admin_menu); $i++) {
                                if ($admin_row['no'] <= 3) {
                                    if ($admin_row['no'] == 1) {
                                        echo '<p>환경설정</p>';
                                    }
                                    echo "<label for='ck_{$admin_row['no']}'><input class ='admin_menu' type='checkbox' name='menu_list[]' id='ck_{$admin_row['no']}' value='{$admin_row['no']}'>{$admin_row['menu_title']}</label>";
                                } elseif ($admin_row['no'] <= 21) {
                                    if ($admin_row['no'] == 4) {
                                        echo '<p>회원관리</p>';
                                    }
                                    echo "<label for='ck_{$admin_row['no']}'><input class ='admin_menu' type='checkbox' name='menu_list[]' id='ck_{$admin_row['no']}' value='{$admin_row['no']}'>{$admin_row['menu_title']}</label>";
                                } elseif ($admin_row['no'] <= 26) {
                                    if ($admin_row['no'] == 22) {
                                        echo '<p>게시판관리</p>';
                                    }
                                    echo "<label for='ck_{$admin_row['no']}'><input class ='admin_menu' type='checkbox' name='menu_list[]' id='ck_{$admin_row['no']}' value='{$admin_row['no']}'>{$admin_row['menu_title']}</label>";
                                } elseif ($admin_row['no'] <= 37) {
                                    if ($admin_row['no'] == 27) {
                                        echo '<p>쇼핑몰관리</p>';
                                    }
                                    echo "<label for='ck_{$admin_row['no']}'><input class ='admin_menu' type='checkbox' name='menu_list[]' id='ck_{$admin_row['no']}' value='{$admin_row['no']}'>{$admin_row['menu_title']}</label>";
                                } elseif ($admin_row['no'] <= 43) {
                                    if ($admin_row['no'] == 38) {
                                        echo '<p>쇼핑몰현황/기타</p>';
                                    }
                                    echo "<label for='ck_{$admin_row['no']}'><input class ='admin_menu' type='checkbox' name='menu_list[]' id='ck_{$admin_row['no']}' value='{$admin_row['no']}'>{$admin_row['menu_title']}</label>";
                                } elseif ($admin_row['no'] <= 51) {
                                    if ($admin_row['no'] == 44) {
                                        echo '<p>SMS관리</p>';
                                    }
                                    echo "<label for='ck_{$admin_row['no']}'><input class ='admin_menu' type='checkbox' name='menu_list[]' id='ck_{$admin_row['no']}' value='{$admin_row['no']}'>{$admin_row['menu_title']}</label>";
                                } elseif ($admin_row['no'] <= 62) {
                                    if ($admin_row['no'] == 52) {
                                        echo '<p>쇼핑몰설정</p>';
                                    }
                                    echo "<label for='ck_{$admin_row['no']}'><input class ='admin_menu' type='checkbox' name='menu_list[]' id='ck_{$admin_row['no']}' value='{$admin_row['no']}'>{$admin_row['menu_title']}</label>";
                                }
                            };
                            ?>
                        </td>
                    </tr>


                    <?php
                }
                ?>
                <!--2019년 11월 18일 추가, 권한설정 끝 -->

                <?php
//소셜계정이 있다면
                if (function_exists('social_login_link_account') && $mb['mb_id']) {
                    if ($my_social_accounts = social_login_link_account($mb['mb_id'], false, 'get_data')) {
                        ?>

                        <tr>
                            <th>소셜계정목록</th>
                            <td colspan="3">
                                <ul class="social_link_box">
                                    <li class="social_login_container">
                                        <h4>연결된 소셜 계정 목록</h4>
                                        <?php
                                        foreach ($my_social_accounts as $account) {     //반복문
                                            if (empty($account))
                                                continue;

                                            $provider = strtolower($account['provider']);
                                            $provider_name = social_get_provider_service_name($provider);
                                            ?>
                                            <div class="account_provider" data-mpno="social_<?php echo $account['mp_no']; ?>" >
                                                <div class="sns-wrap-32 sns-wrap-over">
                                                    <span class="sns-icon sns-<?php echo $provider; ?>" title="<?php echo $provider_name; ?>">
                                                        <span class="ico"></span>
                                                        <span class="txt"><?php echo $provider_name; ?></span>
                                                    </span>

                                                    <span class="provider_name"><?php echo $provider_name;   //서비스이름                ?> ( <?php echo $account['displayname']; ?> )</span>
                                                    <span class="account_hidden" style="display:none"><?php echo $account['mb_id']; ?></span>
                                                </div>
                                                <div class="btn_info"><a href="<?php echo G5_SOCIAL_LOGIN_URL . '/unlink.php?mp_no=' . $account['mp_no'] ?>" class="social_unlink" data-provider="<?php echo $account['mp_no']; ?>" >연동해제</a> <span class="sound_only"><?php echo substr($account['mp_register_day'], 2, 14); ?></span></div>
                                            </div>
                                        <?php } //end foreach   ?>
                                    </li>
                                </ul>
                                <script>
                                    jQuery(function ($) {
                                        $(".account_provider").on("click", ".social_unlink", function (e) {
                                            e.preventDefault();

                                            if (!confirm('정말 이 계정 연결을 삭제하시겠습니까?')) {
                                                return false;
                                            }

                                            var ajax_url = "<?php echo G5_SOCIAL_LOGIN_URL . '/unlink.php' ?>";
                                            var mb_id = '',
                                                    mp_no = $(this).attr("data-provider"),
                                                    $mp_el = $(this).parents(".account_provider");

                                            mb_id = $mp_el.find(".account_hidden").text();

                                            if (!mp_no) {
                                                alert('잘못된 요청! mp_no 값이 없습니다.');
                                                return;
                                            }

                                            $.ajax({
                                                url: ajax_url,
                                                type: 'POST',
                                                data: {
                                                    'mp_no': mp_no,
                                                    'mb_id': mb_id
                                                },
                                                dataType: 'json',
                                                async: false,
                                                success: function (data, textStatus) {
                                                    if (data.error) {
                                                        alert(data.error);
                                                        return false;
                                                    } else {
                                                        alert("연결이 해제 되었습니다.");
                                                        $mp_el.fadeOut("normal", function () {
                                                            $(this).remove();
                                                        });
                                                    }
                                                }
                                            });

                                            return;
                                        });
                                    });

                                </script>

                            </td>
                        </tr>

                        <?php
                    }   //end if
                }   //end if
                ?>

                <?php for ($i = 1; $i <= 10; $i++) { ?>
                                                                    <!-- 여분필드<tr>
                                                                        <th scope="row"><label for="mb_<?php echo $i ?>">여분 필드 <?php echo $i ?></label></th>
                                                                        <td colspan="3"><input type="text" name="mb_<?php echo $i ?>" value="<?php echo $mb['mb_' . $i] ?>" id="mb_<?php echo $i ?>" class="frm_input" size="30" maxlength="255"></td>
                                                                    </tr> -->
                <?php } ?>

            </tbody>
        </table>
    </div>

    <div class="btn_fixed_top">
        <a href="./member_list.php?<?php echo $qstr ?>" class="btn btn_02">목록</a>
        <input type="submit" value="확인" class="btn_submit btn" accesskey='s' onclick="doubleSubmitCheck()">
    </div>
    <script>

        //    확인버튼 클릭시 submit 중복방지  
        var doubleSubmitFlag = false;
        function doubleSubmitCheck() {
            if (doubleSubmitFlag) {
                console.log('중복클릭됨')
                return doubleSubmitFlag;
            } else {
                $('.btn_submit').css('visibility', 'hidden');
                console.log("클릭됨");
                doubleSubmitFlag = true;
                return false;
            }
        }

    </script>




    <h3 id="h3_admin">판매/구매내역 추가</h3>
    <button id="table_btn"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
    <button id="table_btn2" style="margin: 0 0 0 0;"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>
    <div class="historyTableW">
        <table id="table_click">
            <thead>
                <tr>
                    <th>상품명</th>
                    <th>주문일시</th>
                    <th>회원ID</th>
                    <th>금액</th>
                    <th>커미션</th>
                </tr>
            </thead>
            <tbody id="orderTB">
                <tr class="item0" style="display: none">
                    <td><input type="text" value="" class="orderName" name="orderName"></td>
                    <td><input type="text" value="" class="orderDate" name="orderDate"></td>
                    <td><input type="text" value="" class="orderID" name="orderID" maxlength="8"></td>
                    <td><input type="text" value="" class="orderCount" name="orderCount"></td>
                    <td><input type="text" value="" class="orderComs" name="orderComs"></td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" value="0" name="inputNum" id="inputNum"/>
    </div>


</form>


<style>
    #h3_admin {font-size:16px; float:left;margin-top:11px;}
    .historyTableW {margin-top:20px}
    .historyTableW table{width:60%}
    .historyTableW th{border:1px solid #555; background:#f1f1f1; color:#000; padding:15px 0}
    #orderTB td{border:1px solid #555;padding:6px 5px; text-align:center}
    #orderTB td input {width:100%; height:100%; border:none}
    #table_btn, #table_btn2 {font-size:30px; font-weight:800; border:none; background:none; margin:0 15px 0 15px}

    /* 2019년 11월 18일 추가, 권한설정 테이블 */
    tr td.j​urisdiction p {font-weight:bold; padding-top:10px;}
    tr td.j​urisdiction label {padding-left:10px;}
    tr td.j​urisdiction input {margin-right:5px;}    
</style>




<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.18/themes/base/jquery-ui.css" type="text/css" />  
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>  
<script src="http://code.jquery.com/ui/1.8.18/jquery-ui.min.js"></script>  
<script>
        $(function () {
            $(".orderDate").datepicker({
                changeMonth: true,
                changeYear: true,
                dayNames: ['월요일', '화요일', '수요일', '목요일', '금요일', '토요일', '일요일'],
                dayNamesMin: ['월', '화', '수', '목', '금', '토', '일'],
                monthNamesShort: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
                monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
                dateFormat: 'yy-mm-dd'
            });
        });

</script>



<script type="text/javascript">


    $(document).ready(function () {


        // 추가 버튼 클릭시
        $("#table_btn").click(function () {
            $("#inputNum").val(parseInt($("#inputNum").val()) + 1); // 행 카운팅 수 증가

            var lastItemNo = $("#table_click tr:last").attr("class").replace("item", "");
            var newitem = $("#table_click tr:eq(1)").clone();
            newitem.css("display", "table-row");
            newitem.find("td:eq(0)").html('<input type="text" value="" class="orderName" name="orderName' + $("#inputNum").val() + '" required>');
            newitem.find("td:eq(1)").html('<input type="text" value="" class="orderDate" name="orderDate' + $("#inputNum").val() + '" required>');
            newitem.find("td:eq(2)").html('<input type="text" value="" class="orderID" name="orderID' + $("#inputNum").val() + '" required maxlength="8">');
            newitem.find("td:eq(3)").html('<input type="text" value="" class="orderCount" name="orderCount' + $("#inputNum").val() + '" required>');
            newitem.find("td:eq(4)").html('<input type="text" value="" class="orderComs" name="orderComs' + $("#inputNum").val() + '" required>');
            newitem.removeClass();
            newitem.find("td:eq(0)").attr("rowspan", "1");
            newitem.addClass("item" + (parseInt(lastItemNo) + 1));
            $("#table_click").append(newitem);
            $(".ui-datepicker-trigger").remove();
            $(".orderDate").removeClass('hasDatepicker').datepicker({
                changeMonth: true,
                changeYear: true,
                dayNames: ['월요일', '화요일', '수요일', '목요일', '금요일', '토요일', '일요일'],
                dayNamesMin: ['월', '화', '수', '목', '금', '토', '일'],
                monthNamesShort: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
                monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
                dateFormat: 'yy-mm-dd'
            });
            return false;
        });
        // 삭제 버튼 클릭시
        $("#table_btn2").click(function () {
            $('#table_click > tbody:last > tr:last').remove();
            $("#inputNum").val(parseInt($("#inputNum").val()) - 1); // 행 카운팅 수 감소
            return false;
        });

        var auth_menu = new Array();

<?php
if ($mb['mb_id']) {

    $auth_sql = "select * from admin_auth where mb_id='{$mb['mb_id']}' order by date ";
    $auth_menu = sql_query($auth_sql);
    for ($l = 0; $auth_row = sql_fetch_array($auth_menu); $l++) {
        ?>
                auth_menu.push(<?php echo $auth_row['menu_id'] ?>);
                console.log(<?php echo $auth_row['menu_id'] ?>);
        <?php
    }
}
?>
        if (auth_menu) {
            for (var i = 0; i < auth_menu.length; i++) { //배열 출력
                $('.admin_menu').each(function () {
                    var value = $(this).attr('value');
                    if (auth_menu[i] == value) {
                        $(this).attr('checked', 'checked')
                    }
                });
            }
        }

    });</script>


<script>
    function fmember_submit(f) {
        if (f.mb_icon == undefined) {
            console.log("이미지없음");
            return
        }
        if (!f.mb_icon.value.match(/\.(gif|jpe?g|png)$/i) && f.mb_icon.value) {
            alert('아이콘은 이미지 파일만 가능합니다.');
            return false;
        }
        if (!f.mb_img.value.match(/\.(gif|jpe?g|png)$/i) && f.mb_img.value) {
            alert('회원이미지는 이미지 파일만 가능합니다.');
            return false;
        }

        return true;
    }




//function commaChar() {
//    document.getElementById('vmc').value = String(document.getElementById('vmc').value).replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
//}

    var rgx1 = /\D/g; // /[^0-9]/g 와 같은 표현
    var rgx2 = /(\d+)(\d{3})/;
    function getNumber(obj) {

        var num01;
        var num02;
        num01 = obj.value;
        num02 = num01.replace(rgx1, "");
        num01 = setComma(num02);
        obj.value = num01;
    }

    function setComma(inNum) {

        var outNum;
        outNum = inNum;
        while (rgx2.test(outNum)) {
            outNum = outNum.replace(rgx2, '$1' + ',' + '$2');
        }
        return outNum;
    }

</script>
<!-- 주소찾기 -->
<script src="<?php echo G5_PLUGIN_URL ?>/postcodify/zip.js"></script> 
<?php
include_once('./admin.tail.php');
?>



<?php
if ($_GET["check"] == "true") {
    ?>

    <script>
        alert('수정되었습니다.');
    </script>
    <?php
}
?>