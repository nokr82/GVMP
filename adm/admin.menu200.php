<?php

if ($is_admin == "super") {
    $menu['menu200'] = array(
        array('200000', '회원관리', G5_ADMIN_URL . '/member_list.php', 'member'),
        array('200100', '회원관리', G5_ADMIN_URL . '/member_list.php', 'mb_list'),
        array('200300', '회원메일발송', G5_ADMIN_URL . '/mail_list.php', 'mb_mail'),
        array('200800', '접속자집계', G5_ADMIN_URL . '/visit_list.php', 'mb_visit', 1),
        array('200810', '접속자검색', G5_ADMIN_URL . '/visit_search.php', 'mb_search', 1),
        array('200820', '접속자로그삭제', G5_ADMIN_URL . '/visit_delete.php', 'mb_delete', 1),
        array('200830', 'VMP', G5_ADMIN_URL . '/member_modify.php', 'mb_delete', 1),
        array('200840', '포인트 내역', G5_ADMIN_URL . '/member_point.php', 'mb_delete', 1),
        array('200850', '판매/구매 전체 내역', G5_ADMIN_URL . '/member_count.php', 'mb_delete', 1),
        array('200860', '수당정산', G5_ADMIN_URL . '/member_cash.php', 'mb_point'),
        array('200870', '신청제품관리', G5_ADMIN_URL . '/pick_pd.php', ''),
        array('200890', '애드팩 #3 정산', G5_ADMIN_URL . '/addpack3.php', '', 1),
        array('200900', '매출/지출', G5_ADMIN_URL . '/main_total.php', '', 1),
        array('200910', '영수증 관리', G5_ADMIN_URL . '/vroad_receipt.php', '', 1),
        array('200920', '이벤트관리', G5_ADMIN_URL . '/shop_admin/itemevent.php', 'scf_event'),
        array('200930', '광고관리', G5_ADMIN_URL . '/v_ad_adm_center.php', ''),
        array('200940', '간편회원가입 리스트', G5_ADMIN_URL . '/simple_join.php', ''),
        array('200950', '신분증 인증 시스템', G5_ADMIN_URL . '/certify_id_card.php', ''),
        array('200960', '중고장터', G5_ADMIN_URL . '/used_market.php', ''),
        array('200970', '관리자 P 지급내역', G5_ADMIN_URL . '/adminPrvsPointList.php', ''),
        array('200980', '회원정보 변경이력', G5_ADMIN_URL . '/changeProfileList.php', ''),
        array('200990', '공유보너스(개발중)', G5_ADMIN_URL . '/share_bonus_list.php', ''),
        array('200999', '포인트충전내역', G5_ADMIN_URL . '/vmp_point_list.php', ''),
//    array('200920', '이벤트 관리', G5_ADMIN_URL.'/vroad_event.php', '', 1),
//    array('200200', '포인트관리', G5_ADMIN_URL.'/point_list.php', 'mb_point'),
            /* array('200900', '투표관리', G5_ADMIN_URL.'/poll_list.php', 'mb_poll') */
//        array('200990', '차량유지비 지급목록', G5_ADMIN_URL . '/car_maintenance.php', ''),
//        array('201000', '공유포인트 지급목록', G5_ADMIN_URL . '/share_point.php', '')

    );
} elseif ($is_admin == "manager") {
    $admin_sql = "SELECT 
   *
FROM
     admin_menu as am
        INNER JOIN
    admin_auth AS aa ON am.no = aa.menu_id
    where mb_id = '{$member['mb_id']}' and am.menu_category = 2";
    
    $admin_menu = sql_query($admin_sql);
    $menu['menu200'] = array();
    array_push( $menu['menu200'],array('200000', '회원관리', G5_ADMIN_URL . '/member_list.php', 'member'));
    for ($i = 0; $admin_row = sql_fetch_array($admin_menu); $i++) {
       array_push( $menu['menu200'],array($admin_row['sub_menu'], $admin_row['menu_title'], G5_ADMIN_URL .$admin_row['menu_url'], ''));
    }
}
?>