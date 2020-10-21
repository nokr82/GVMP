<?php
$sub_menu = "200100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from g5_member as a inner join genealogy as b on a.mb_id = b.mb_id ";


include_once('./admin_menu_check.php');

if ($is_admin != 'super') {
    menu_check($sub_menu, $member['mb_id']);
}

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_point' :
            $sql_search .= " ({$sfl} >= '{$stx}') ";
            break;
        case 'mb_level' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        case 'mb_tel' :
        case 'mb_hp' :
            $sql_search .= " ({$sfl} like '%{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}
print_r($stx_t);
if ($stx_t){
$sql_search .= " and {$sfl} ='{$stx_t}' ";
}

if ($is_admin != 'super')
    $sql_search .= " and mb_level <= '{$member['mb_level']}' ";

if (!$sst) {
    $sst = "mb_datetime";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1)
    $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
// 탈퇴회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_leave_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$leave_count = $row['cnt'];

// 차단회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_intercept_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$intercept_count = $row['cnt'];

$listall = '<a href="' . $_SERVER['SCRIPT_NAME'] . '" class="ov_listall">전체목록</a>';

$g5['title'] = '회원관리';
include_once('./admin.head.php');

$sql = " select a.*, b.recommenderName, b.recommenderID, b.sponsorName, b.sponsorID, b.sponsorTeam {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$colspan = 16;
?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">총회원수 </span><span class="ov_num"> <?php echo number_format($total_count) ?>명 </span></span>

    <?php
    $vmCount = sql_fetch_array(sql_query("select count(mb_id) as c from g5_member where accountType = 'VM' and renewal >= '" . date("Y-m-d", strtotime("-4 month", time())) . "'"));
    ?>

    <span class="btn_ov01"><span class="ov_txt">VM회원수 </span><span class="ov_num"> <?php echo number_format($vmCount["c"]); ?>명 </span></span>
    <a href="?sst=mb_intercept_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>" class="btn_ov01"> <!--<span class="ov_txt">차단 </span><span class="ov_num"><?php echo number_format($intercept_count) ?>명</span></a>-->
        <a href="?sst=mb_leave_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>" class="btn_ov01"> <span class="ov_txt">탈퇴  </span><span class="ov_num"><?php echo number_format($leave_count) ?>명</span></a>
</div>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">

    <label for="sfl" class="sound_only">검색대상</label>
    <select name="sfl" id="sfl">
        <option value="a.mb_name"<?php echo get_selected($_GET['sfl'], "a.mb_name"); ?>>이름</option>
        <option value="a.mb_id"<?php echo get_selected($_GET['sfl'], "a.mb_id"); ?>>회원아이디</option>
        <option value="a.accountType"<?php echo get_selected($_GET['sfl'], "a.accountType"); ?>>계정종류</option>
        <option value="a.accountRank"<?php echo get_selected($_GET['sfl'], "a.accountRank"); ?>>직급종류</option>
        <!-- <option value="a.mb_level"<?php echo get_selected($_GET['sfl'], "mb_level"); ?>>권한</option>-->
        <!-- <option value="a.mb_email"<?php echo get_selected($_GET['sfl'], "mb_email"); ?>>E-MAIL</option>-->
        <!-- <option value="a.mb_tel"<?php echo get_selected($_GET['sfl'], "mb_tel"); ?>>전화번호</option>-->
        <option value="mb_hp"<?php echo get_selected($_GET['sfl'], "mb_hp"); ?>>휴대폰번호</option>
        <option value="a.mb_datetime"<?php echo get_selected($_GET['sfl'], "a.mb_datetime"); ?>>가입일시</option>
        <!-- <option value="a.mb_ip"<?php echo get_selected($_GET['sfl'], "mb_ip"); ?>>IP</option>-->
        <!-- <option value="a.mb_recommend"<?php echo get_selected($_GET['sfl'], "mb_recommend"); ?>>추천인</option>-->
    </select>
    <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
    <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
    <input type="submit" class="btn_submit" value="검색">

</form>

<style>
    #account p {margin:10px 0;font-size:14px}
    #account span{border:1px solid #fff; width:50px; height:50px; background:#ddd; border-radius:50%;padding:10px; display:inline-block;text-align:center;line-height:28px}
    #account span a{color:#fff;font-size:14px}
    #account span:nth-child(1) {background:#fc565e}
    #account span:nth-child(2) {background:#f2a80a}
    #account span:nth-child(3) {background:#1bb934}
    #account span:nth-child(4) {background:#EDA0C7}

    #position {margin-bottom:50px}
    #position p {margin:10px 0; font-size:14px}
    #position div{float:left; padding:10px 15px; margin-right:2px}
    #position div span a {color:#fff}
    #position .po1 {background:#6aa7d4; margin-right:10px}
    #position .po2 {background:#54bfcd}
    #position .po3 {background:#2ea8ad}
    #position .po4 {background:#117f71; margin-right:10px}
    #position .po5 {background:#e6ba75}
    #position .po6 {background:#f4a445}
    #position .po7 {background:#ef8124}
    #position .po8 {background:#df6b1f}
    #position .po9 {background:#cc4319;  margin-right:10px}
    #position .po10 {background:#8372b2}
    #position .po11 {background:#6953a0}
    #position .po12 {background:#5a377b}
    #position .po13 {background:#3e2554}
    #position .po14 {background:#610B4B}

    #fmemberlist table {font-size:12px;} /*200103 추가*/
</style>

<div id="account">
    <p>계정별 바로가기</p>
    <div>
        <span><a href="/adm/member_list.php?token=7bfe86ce9269c0d2f6105823e95f4bfe&sfl=accountType&stx_t=VM">VM</a></span>
        <span><a href="/adm/member_list.php?token=720e3954d9d7c2cff97e267c9b8b686f&sfl=accountType&stx_t=VD">VD</a></span>
        <span><a href="/adm/member_list.php?token=e91b6ff7d101f4742869cb6380e3a26b&sfl=accountType&stx_t=CU">CU</a></span>
        <span><a href="/adm/member_list.php?token=e91b6ff7d101f4742869cb6380e3a26b&sfl=accountType&stx_t=SM">SM</a></span>
    </div>
</div>
<div id="position">
    <p>직급별 바로가기</p>
    <div class="po_wrap po1">
        <span><a href="/adm/member_list.php?token=bd4bdd69b13752b8cfd710aee95b8df6&sfl=accountRank&stx_t=VM">VM</a></span>
    </div>
    <div class="po_wrap po2">
        <span><a href="/adm/member_list.php?token=611a0c9aad242b9cb86c161f54a3007e&sfl=accountRank&stx_t=MASTER">MASTER</a></span>
    </div>
    <div class="po_wrap po3">
        <span><a href="/adm/member_list.php?token=9ea7ff12139da9253bd98ed32b93b132&sfl=accountRank&stx_t=DOUBLE+MASTER">DOUBLE MASTER</a></span>
    </div>
    <div class="po_wrap po4">
        <span><a href="/adm/member_list.php?token=0c1d043121739ed2ba8aba5e944b46a9&sfl=accountRank&stx_t=TRIPLE+MASTER">TRIPLE MASTER</a></span>
    </div>
    <div class="po_wrap po5">
        <span><a href="/adm/member_list.php?token=72ef0182acd155fd3a4fc6495fc25c73&sfl=accountRank&stx_t=1+STAR">1 STAR</a></span>
    </div>
    <div class="po_wrap po6">
        <span><a href="/adm/member_list.php?token=a602f26c0087a0f1b60053119eea1f29&sfl=accountRank&stx_t=2+STAR">2 STAR</a></span>
    </div>
    <div class="po_wrap po7">
        <span><a href="/adm/member_list.php?token=65a03f5ff3a1ebcd0e59a520e0890623&sfl=accountRank&stx_t=3+STAR">3 STAR</a></span>
    </div>
    <div class="po_wrap po8">
        <span><a href="/adm/member_list.php?token=3d9fbb1eb08b4073ca309208e7bd3882&sfl=accountRank&stx_t=4+STAR">4 STAR</a></span>
    </div>
    <div class="po_wrap po9">
        <span><a href="/adm/member_list.php?token=0c1836d0d8222fe0b01d88620cdee310&sfl=accountRank&stx_t=5+STAR">5 STAR</a></span>
    </div>
    <div class="po_wrap po10">
        <span><a href="/adm/member_list.php?token=89195aa50c21dbe2943dd816e3c6c262&sfl=accountRank&stx_t=AMBASSADOR">AMBASSADOR</a></span>
    </div>
    <div class="po_wrap po11">
        <span><a href="/adm/member_list.php?token=ce0429ec74be1a407da56b9498920c0c&sfl=accountRank&stx_t=DOUBLE+AMBASSADOR">DOUBLE AMBASSADOR</a></span>
    </div>
    <div class="po_wrap po12">
        <span><a href="/adm/member_list.php?token=6157439049e23b0b45dffec0a786c65c&sfl=accountRank&stx_t=TRIPLE+AMBASSADOR">TRIPLE AMBASSADOR</a></span>
    </div>
    <div class="po_wrap po13">
        <span><a href="/adm/member_list.php?token=5c298af6f769431c76bf1926b1fea89e&sfl=accountRank&stx_t=CROWN+AMBASSADOR">CROWN AMBASSADOR</a></span>
    </div>

    <div class="po_wrap po14">
        <span><a href="/adm/member_list.php?token=5c298af6f769431c76bf1926b1fea89e&sfl=accountRank&stx_t=ROYAL+CROWN+AMBASSADOR">ROYAL CROWN AMBASSADOR</a></span>
    </div>

</div>

<div class="local_desc01 local_desc">
    <p>
        회원자료 삭제 시 다른 회원이 기존 회원아이디를 사용하지 못하도록 회원아이디, 이름, 닉네임은 삭제하지 않고 영구 보관합니다.
    </p>
</div>


<form name="fmemberlist" id="fmemberlist" action="./member_list_update.php" onsubmit="return fmemberlist_submit(this);" method="post">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="stx_t" value="<?php echo $stx_t ?>">
    <input type="hidden" name="token" value="">

    <div class="tbl_head01 tbl_wrap">
        <table>
            <caption><?php echo $g5['title']; ?> 목록</caption>
            <thead>
                <tr>
                    <th scope="col" id="mb_list_chk" rowspan="2" >
                        <label for="chkall" class="sound_only">회원 전체</label>
                        <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
                    </th>
                    <th scope="col" id="mb_list_id" colspan="2">아이디</th>
                    <th scope="col" id="mb_list_date">직급달성일</th>
                    <!-- <th scope="col" rowspan="2" id="mb_list_cert"><?php echo subject_sort_link('mb_certify', '', 'desc') ?>본인확인</a></th> -->
                    <th scope="col" id="recommenderName">추천인</th>
                    <th scope="col" rowspan="2" id="mb_list_open">리뉴얼 날짜</th>
                    <th scope="col" rowspan="2" id="mb_list_vmc">VMC</th>
                    <th scope="col" rowspan="2" id="mb_list_vmr">VMP</th>
                    <th scope="col" rowspan="2" id="mb_list_vmm">VMM</th>
                    <th scope="col" rowspan="2" id="mb_list_vmm">GV Bonus</th>
                    <th scope="col" rowspan="2" id="mb_list_vmp">금고</th>
                    <!--<th scope="col" rowspan="2" id="mb_list_mobile">휴대폰</th>-->

                    <th scope="col" id="mb_list_lastcall"><?php echo subject_sort_link('mb_today_login', '', 'desc') ?>최종접속</a></th>
                    <th scope="col" rowspan="2" id="mb_list_mng">관리</th>
                </tr>

                <tr>
                    <th scope="col" id="mb_list_name">이름</a></th>
                    <th scope="col" id="mb_list_nick">계정종류</a></th>
                    <th scope="col" id="mb_list_nick2">직급종류</a></th>
                    <th scope="col" id="sponsorName">후원인</a></th>
                    <!-- <th scope="col" id="mb_list_adultc"><?php echo subject_sort_link('mb_adult', '', 'desc') ?>성인인증</a></th> -->
                    <!-- <th scope="col" id="mb_list_auth"><?php echo subject_sort_link('mb_intercept_date', '', 'desc') ?>접근차단</a></th> -->
                    <!-- <th scope="col" id="mb_list_deny"><?php echo subject_sort_link('mb_level', '', 'desc') ?>권한</a></th> -->
                    <!-- <th scope="col" id="mb_list_point"><?php echo subject_sort_link('mb_point', '', 'desc') ?> 포인트</a></th> -->
                    <!-- <th scope="col" id="mb_list_tel">전화번호</th> -->
                    <th scope="col" id="mb_list_join"><?php echo subject_sort_link('mb_datetime', '', 'desc') ?>가입일</a></th>

                </tr>
            </thead>
            <tbody>
                <?php
                for ($i = 0; $row = sql_fetch_array($result); $i++) {
                    // 접근가능한 그룹수
                    $sql2 = " select count(*) as cnt from {$g5['group_member_table']} where mb_id = '{$row['mb_id']}' ";
                    $row2 = sql_fetch($sql2);
                    $group = '';
                    if ($row2['cnt'])
                        $group = '<a href="./boardgroupmember_form.php?mb_id=' . $row['mb_id'] . '">' . $row2['cnt'] . '</a>';

                    if ($is_admin == 'group') {
                        $s_mod = '';
                    } else {
                        $s_mod = '<a href="./member_form.php?' . $qstr . '&amp;w=u&amp;mb_id=' . $row['mb_id'] . '" class="btn btn_03">수정</a>';
                    }
                    $s_grp = '<a href="./boardgroupmember_form.php?mb_id=' . $row['mb_id'] . '" class="btn btn_02">그룹</a>';

                    $leave_date = $row['mb_leave_date'] ? $row['mb_leave_date'] : date('Ymd', G5_SERVER_TIME);
                    $intercept_date = $row['mb_intercept_date'] ? $row['mb_intercept_date'] : date('Ymd', G5_SERVER_TIME);

                    $mb_nick = get_sideview($row['mb_id'], get_text($row['mb_nick']), $row['mb_email'], $row['mb_homepage']);

                    $mb_id = $row['mb_id'];
                    $leave_msg = '';
                    $intercept_msg = '';
                    $intercept_title = '';
                    if ($row['mb_leave_date']) {
                        $mb_id = $mb_id;
                        $leave_msg = '<span class="mb_leave_msg">탈퇴함</span>';
                    } else if ($row['mb_intercept_date']) {
                        $mb_id = $mb_id;
                        $intercept_msg = '<span class="mb_intercept_msg">차단됨</span>';
                        $intercept_title = '차단해제';
                    }
                    if ($intercept_title == '')
                        $intercept_title = '차단하기';

                    $address = $row['mb_zip1'] ? print_address($row['mb_addr1'], $row['mb_addr2'], $row['mb_addr3'], $row['mb_addr_jibeon']) : '';

                    $bg = 'bg' . ($i % 2);

                    switch ($row['mb_certify']) {
                        case 'hp':
                            $mb_certify_case = '휴대폰';
                            $mb_certify_val = 'hp';
                            break;
                        case 'ipin':
                            $mb_certify_case = '아이핀';
                            $mb_certify_val = '';
                            break;
                        case 'admin':
                            $mb_certify_case = '관리자';
                            $mb_certify_val = 'admin';
                            break;
                        default:
                            $mb_certify_case = '&nbsp;';
                            $mb_certify_val = 'admin';
                            break;
                    }
                    ?>

                    <tr class="<?php echo $bg; ?>">
                        <td headers="mb_list_chk" class="td_chk" rowspan="2">
                            <input type="hidden" name="mb_id[<?php echo $i ?>]" value="<?php echo $row['mb_id'] ?>" id="mb_id_<?php echo $i ?>">
                            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['mb_name']); ?> <?php echo get_text($row['mb_nick']); ?>님</label>
                            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
                        </td>
                        <td headers="mb_list_id" colspan="2" class="td_name sv_use">
                            <?php echo $mb_id ?>
                            <?php
                            //소셜계정이 있다면
                            if (function_exists('social_login_link_account')) {
                                if ($my_social_accounts = social_login_link_account($row['mb_id'], false, 'get_data')) {

                                    echo '<div class="member_social_provider sns-wrap-over sns-wrap-32">';
                                    foreach ((array) $my_social_accounts as $account) {     //반복문
                                        if (empty($account) || empty($account['provider']))
                                            continue;

                                        $provider = strtolower($account['provider']);
                                        $provider_name = social_get_provider_service_name($provider);

                                        echo '<span class="sns-icon sns-' . $provider . '" title="' . $provider_name . '">';
                                        echo '<span class="ico"></span>';
                                        echo '<span class="txt">' . $provider_name . '</span>';
                                        echo '</span>';
                                    }
                                    echo '</div>';
                                }
                            }
                            ?>
                        </td>
                       <!-- <td headers="mb_list_cert"  rowspan="2" class="td_mbcert">
                            <input type="radio" name="mb_certify[<?php echo $i; ?>]" value="ipin" id="mb_certify_ipin_<?php echo $i; ?>" <?php echo $row['mb_certify'] == 'ipin' ? 'checked' : ''; ?>>
                            <label for="mb_certify_ipin_<?php echo $i; ?>">아이핀</label><br>
                            <input type="radio" name="mb_certify[<?php echo $i; ?>]" value="hp" id="mb_certify_hp_<?php echo $i; ?>" <?php echo $row['mb_certify'] == 'hp' ? 'checked' : ''; ?>>
                            <label for="mb_certify_hp_<?php echo $i; ?>">휴대폰</label>
                        </td> --> 
                        <td headers="mb_list_date" class="td_name date"><div></div></td>
                        <!-- 추천인이름 -->
                        <td headers="mb_list_mailc"><?php echo $row['recommenderName'] . "({$row['recommenderID']})"; ?></td>

                        <td rowspan="2" headers="mb_list_open"><?php
                            if ($row['renewal'] != "") {
                                echo date("Y-m-d", strtotime("+4 month", strtotime($row['renewal'])));
                            };
                            ?></td>

                        <td rowspan="2" headers="mb_list_mailr">
                           <!-- <label for="mb_mailling_<?php echo $recommenderName; ?>" class="sound_only">메일수신</label> --> 
                            <!--<input type="checkbox" name="mb_mailling[<?php echo $i; ?>]" <?php echo $row['mb_mailling'] ? 'checked' : ''; ?> value="1" id="mb_mailling_<?php echo $i; ?>">-->
                            <?php echo number_format($row['VMC']); ?>
                        </td>
                        <td rowspan="2" headers="mb_list_auth" class="td_mbstat">
                            <?php
                            echo number_format($row['VMP']);
                            // if ($leave_msg || $intercept_msg) echo $leave_msg.' '.$intercept_msg;
                            //else echo "정상";
                            ?>
                        </td>
                        <td rowspan="2" headers="mb_list_vmp" class="td_vmm"><?php echo number_format($row['VMM']); ?></td>
                        <td rowspan="2" headers="mb_list_vmp" class="td_vmm"><?php echo number_format($row['V']); ?></td>
                        <td rowspan="2" headers="mb_list_vmm" class="td_vmg"><?php echo number_format($row['VMG']); ?></td>
                        <!--<td rowspan="2" headers="mb_list_mobile" class="td_tel"><?php echo get_text($row['mb_hp']); ?></td>-->
                        <td headers="mb_list_lastcall" class="td_date"><?php echo substr($row['mb_today_login'], 2, 8); ?></td>
                        <td headers="mb_list_mng" rowspan="2" class="td_mng td_mng_s" style="width: 76px;"><?php echo $s_mod ?><?php // echo $s_grp  ?>
                            <!--<a href="#" class="btn btn_03 bz_btnConfirm" style="padding: 0 3px; background-color: #ff4081;">비즈니스팩</a>-->
                        </td>
                    </tr>
                    <tr class="<?php echo $bg; ?>">
                        <td headers="mb_list_name" class="td_mbname"><?php echo get_text($row['mb_name']); ?></td>
                        <td headers="mb_list_nick" class="td_name sv_use"><div><?php echo $row['accountType'] ?></div></td>
                        <td headers="mb_list_position" class="td_name position"><div><?php echo $row['accountRank'] ?></div></td>


                        <!-- 후원인이름 -->
                        <td headers="mb_list_sms"><?php if ($row['sponsorID'] != "99999999") echo $row['sponsorName'] . "({$row['sponsorID']})님의 {$row['sponsorTeam']}팀"; ?></td>


                        <td headers="mb_list_join" class="td_date"><?php echo substr($row['mb_datetime'], 2, 8); ?></td>
                       <!--  <td headers="mb_list_adultc">
                            <label for="mb_adult_<?php echo $i; ?>" class="sound_only">성인인증</label>
                            <input type="checkbox" name="mb_adult[<?php echo $i; ?>]" <?php echo $row['mb_adult'] ? 'checked' : ''; ?> value="1" id="mb_adult_<?php echo $i; ?>">
                        </td> -->
                       <!-- <td headers="mb_list_deny">
                        <?php if (empty($row['mb_leave_date'])) { ?>
                                <input type="checkbox" name="mb_intercept_date[<?php echo $i; ?>]" <?php echo $row['mb_intercept_date'] ? 'checked' : ''; ?> value="<?php echo $intercept_date ?>" id="mb_intercept_date_<?php echo $i ?>" title="<?php echo $intercept_title ?>">
                                <label for="mb_intercept_date_<?php echo $i; ?>" class="sound_only">접근차단</label>
                        <?php } ?>
                        </td> --> 
                       <!-- <td headers="mb_list_auth" class="td_mbstat">
    <?php echo get_member_level_select("mb_level[$i]", 1, $member['mb_level'], $row['mb_level']) ?>
                        </td> --> 
                        <!-- <td headers="mb_list_tel" class="td_tel"><?php echo get_text($row['mb_tel']); ?></td> -->

    <!-- <td headers="mb_list_point" class="td_num"><a href="point_list.php?sfl=mb_id&amp;stx=<?php echo $row['mb_id'] ?>"><?php echo number_format($row['mb_point']) ?></a></td> --> 

                    </tr>

                    <?php
                }
                if ($i == 0)
                    echo "<tr><td colspan=\"" . $colspan . "\" class=\"empty_table\">자료가 없습니다.</td></tr>";
                ?>
            </tbody>
        </table>
    </div>

    <div class="btn_fixed_top">
    <!--    <input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="btn btn_02">
        <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_02">-->
<?php if ($is_admin == 'super') { ?>
            <!--<a href="./member_form.php" id="member_add" class="btn btn_01">회원추가</a>-->
<?php } ?>

    </div>


</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?' . $qstr ."&stx_t=".$stx_t. '&amp;page='); ?>

<script>
    function fmemberlist_submit(f)
    {
        if (!is_checked("chk[]")) {
            alert(document.pressed + " 하실 항목을 하나 이상 선택하세요.");
            return false;
        }

        if (document.pressed == "선택삭제") {
            if (!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
                return false;
            }
        }

        return true;
    }
</script>

<?php
include_once ('./admin.tail.php');
?>

<script>
    $('.bz_btnConfirm').on('click', bz_confirm);

    /* 비즈니스팩 알림창 */
    function bz_confirm() {
        var bz_confirmName = $(this).parent().prevAll('.td_name').text().trim();
        var bz_userName = $(this).parents('tr').next('tr').children('.td_mbname').text();

        if (confirm(bz_confirmName + '(' + bz_userName + ')님을 비즈니스 팩을 적용 시키겠습니까?') == true) {    //확인
            window.location.href = "businessPack.php?mb_id=" + bz_confirmName;
        } else {   //취소
            return;
        }
    }
</script>