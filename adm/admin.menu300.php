<?php
if($is_admin=="super"){
$menu['menu300'] = array (
    array('300000', '게시판관리', ''.G5_ADMIN_URL.'/board_list.php', 'board'),
    array('300100', '게시판관리', ''.G5_ADMIN_URL.'/board_list.php', 'bbs_board'),
    /* array('300200', '게시판그룹관리', ''.G5_ADMIN_URL.'/boardgroup_list.php', 'bbs_group'), */
    array('300300', '인기검색어관리', ''.G5_ADMIN_URL.'/popular_list.php', 'bbs_poplist', 1),
    array('300400', '인기검색어순위', ''.G5_ADMIN_URL.'/popular_rank.php', 'bbs_poprank', 1),
    /* array('300500', '1:1문의설정', ''.G5_ADMIN_URL.'/qa_config.php', 'qa'), */
    array('300600', '내용관리', G5_ADMIN_URL.'/contentlist.php', 'scf_contents', 1),
    /* array('300700', 'FAQ관리', G5_ADMIN_URL.'/faqmasterlist.php', 'scf_faq', 1), */
    array('300820', '글,댓글 현황', G5_ADMIN_URL.'/write_count.php', 'scf_write_count'),
);
} elseif ($is_admin == "manager") {
    $admin_sql = "SELECT 
   *
FROM
     admin_menu as am
        INNER JOIN
    admin_auth AS aa ON am.no = aa.menu_id
    where mb_id = '{$member['mb_id']}' and am.menu_category = 3";
    
    $admin_menu = sql_query($admin_sql);
     $menu['menu300']  = array();
    array_push(  $menu['menu300'] , array('300000', '게시판관리', ''.G5_ADMIN_URL.'/board_list.php', 'board'));
    for ($i = 0; $admin_row = sql_fetch_array($admin_menu); $i++) {
       array_push( $menu['menu300'],array($admin_row['sub_menu'], $admin_row['menu_title'], G5_ADMIN_URL .$admin_row['menu_url'], ''));
    }
}
?>