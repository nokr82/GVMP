<?php
include_once('./_common.php');


//if($_SERVER["REMOTE_ADDR"]=="175.197.91.15"){
//
//}else{
//	if($_SESSION['app']){
//		alert("준비 중 입니다.");
//	}
//}

$sql = " select *
           from {$g5['g5_shop_category_table']}
          where ca_id = '$ca_id'
            and ca_use = '1'  ";
$ca = sql_fetch($sql);

if (!$ca['ca_id']){
	$ca['ca_id'] = $_GET['ca_id'];
	$ca['ca_name'] = "vendor몰";
	$ca['ca_stock_qty'] = "99999";
	$ca['ca_explan_html'] = "1";
	$ca['ca_skin'] = "list.10.skin.php";
	$ca['ca_img_width'] = "230";
	$ca['ca_img_height'] = "230";
	$ca['ca_mobile_img_width'] = "230";
	$ca['ca_mobile_img_height'] = "230";
	$ca['ca_mobile_list_mod'] = "3";
	$ca['ca_mobile_list_row'] = "5";
	$ca['ca_mobile_skin'] = "list.10.skin.php";
}

if (!$ca['ca_id'])
    alert('등록된 분류가 없습니다.', G5_SHOP_URL);

// 테마미리보기 스킨 등의 변수 재설정
//if(defined('_THEME_PREVIEW_') && _THEME_PREVIEW_ === true) {
    $ca['ca_mobile_skin']       = (isset($tconfig['ca_mobile_skin']) && $tconfig['ca_mobile_skin']) ? $tconfig['ca_mobile_skin'] : $ca['ca_mobile_skin'];
    $ca['ca_mobile_img_width']  = (isset($tconfig['ca_mobile_img_width']) && $tconfig['ca_mobile_img_width']) ? $tconfig['ca_mobile_img_width'] : $ca['ca_mobile_img_width'];
    $ca['ca_mobile_img_height'] = (isset($tconfig['ca_mobile_img_height']) && $tconfig['ca_mobile_img_height']) ? $tconfig['ca_mobile_img_height'] : $ca['ca_mobile_img_height'];
    $ca['ca_mobile_list_mod']   = (isset($tconfig['ca_mobile_list_mod']) && $tconfig['ca_mobile_list_mod']) ? $tconfig['ca_mobile_list_mod'] : 3;
    $ca['ca_mobile_list_row']   = (isset($tconfig['ca_mobile_list_row']) && $tconfig['ca_mobile_list_row']) ? $tconfig['ca_mobile_list_row'] : 8;
//}

// 본인인증, 성인인증체크
if(!$is_admin) {
    $msg = shop_member_cert_check($ca_id, 'list');
    if($msg)
        alert($msg, G5_SHOP_URL);
}

$g5['title'] = $ca['ca_name'];

// /var/www/html/gvmp/mobile/shop
include_once(G5_MSHOP_PATH.'/_head.php');

// 스킨경로
$skin_dir = G5_MSHOP_SKIN_PATH;

if($ca['ca_mobile_skin_dir']) {
    if(preg_match('#^theme/(.+)$#', $ca['ca_mobile_skin_dir'], $match))
        $skin_dir = G5_THEME_MOBILE_PATH.'/'.G5_SKIN_DIR.'/shop/'.$match[1];
    else
        $skin_dir = G5_MOBILE_PATH.'/'.G5_SKIN_DIR.'/shop/'.$ca['ca_mobile_skin_dir'];

    if(is_dir($skin_dir)) {
        $skin_file = $skin_dir.'/'.$ca['ca_mobile_skin'];

        if(!is_file($skin_file))
            $skin_dir = G5_MSHOP_SKIN_PATH;
    } else {
        $skin_dir = G5_MSHOP_SKIN_PATH;
    }
}
define('G5_SHOP_CSS_URL', str_replace(G5_PATH, G5_URL, $skin_dir));
?>
<script>
var g5_shop_url = "<?php echo G5_SHOP_URL; ?>";
</script>
<script src="<?php echo G5_JS_URL; ?>/shop.mobile.list.js"></script>

<div id="sct">
<?php
	// 상단 HTML
	echo '<div id="sct_hhtml">'.conv_content($ca['ca_mobile_head_html'], 1).'</div>';
//	/var/www/html/gvmp/theme/vexmall/mobile/skin/shop/basic/listcategory.skin.php    
    if(!is_file($cate_skin))
        $cate_skin = G5_MSHOP_SKIN_PATH.'/listcategory.skin.php';
    include $cate_skin;
    // 테마미리보기 베스트상품 재설정
//    if(defined('_THEME_PREVIEW_') && _THEME_PREVIEW_ === true) {
        if(isset($theme_config['ca_mobile_list_best_mod']))
            $theme_config['ca_mobile_list_best_mod'] = (isset($tconfig['ca_mobile_list_best_mod']) && $tconfig['ca_mobile_list_best_mod']) ? $tconfig['ca_mobile_list_best_mod'] : 3;
        if(isset($theme_config['ca_mobile_list_best_row']))
            $theme_config['ca_mobile_list_best_row'] = (isset($tconfig['ca_mobile_list_best_row']) && $tconfig['ca_mobile_list_best_row']) ? $tconfig['ca_mobile_list_best_row'] : 8;
//    }

    // 분류 Best Item
    $list_mod = (isset($theme_config['ca_mobile_list_best_mod']) && $theme_config['ca_mobile_list_best_mod']) ? (int)$theme_config['ca_mobile_list_best_mod'] : $ca['ca_mobile_list_mod'];
    $list_row = (isset($theme_config['ca_mobile_list_best_row']) && $theme_config['ca_mobile_list_best_row']) ? (int)$theme_config['ca_mobile_list_best_row'] : $ca['ca_mobile_list_row'];
    $limit = $list_mod * $list_row;
    $best_skin = G5_MSHOP_SKIN_PATH.'/list.best.10.skin.php';

    $sql = " select *
                from {$g5['g5_shop_item_table']} WHERE ";
	$sql .= " ( ca_id = '$ca_id%')";
//	$sql .= " ( ca_id like '$ca_id%' or ca_id2 like '$ca_id%' or ca_id3 like '$ca_id%' )";
	$sql .= " and it_use = '1'
                  and it_type4 = '1'
                order by it_order, it_id desc
                limit 0, $limit ";


    $list = new item_list($best_skin, $list_mod, $list_row, $ca['ca_mobile_img_width'], $ca['ca_mobile_img_height']);
    $list->set_query($sql);
    $list->set_mobile(true);
    $list->set_view('it_img', true);
    $list->set_view('it_id', false);
    $list->set_view('it_name', true);
    $list->set_view('it_price', true);
    echo $list->run();

    // 상품 출력순서가 있다면
    if ($sort != "")
        $order_by = $sort.' '.$sortodr.' , it_order, it_id desc';
    else
        $order_by = 'it_order, it_id desc';

    $error = '<p class="sct_noitem">등록된 상품이 없습니다.</p>';

	if(!$ca['ca_mobile_skin']){
	    $ca['ca_mobile_skin'] = 'list.10.skin.php';
	}

    // 리스트 스킨 /var/www/html/gvmp/theme/vexmall/mobile/skin/shop/basic/list.10.skin.php
	// /var/www/html/gvmp/theme/everyday/mobile/skin/shop/basic/list.10.skin.php
	// /var/www/html/gvmp/theme/everyday/mobile/skin/shop/basic/list.10.skin.php
    $skin_file = is_include_path_check($skin_dir.'/'.$ca['ca_mobile_skin']) ? $skin_dir.'/'.$ca['ca_mobile_skin'] : $skin_dir.'/list.10.skin.php';

    if (file_exists($skin_file)) {

        echo '<div id="sct_sortlst">';
		/*정렬 부분*/
        $sort_skin = $skin_dir.'/list.sort.skin.php';
        if(!is_file($sort_skin))
			$sort_skin = G5_MSHOP_SKIN_PATH.'/list.sort.skin.php';
//        include $sort_skin;

		// 상품 보기 타입 변경 버튼
        $sub_skin = $skin_dir.'/list.sub.skin.php';


        if(!is_file($sub_skin))
            $sub_skin = G5_MSHOP_SKIN_PATH.'/list.sub.skin.php';
        include $sub_skin;
        echo '</div>';

        // 총몇개
        $items = $ca['ca_mobile_list_mod'] * $ca['ca_mobile_list_row'];
        // 페이지가 없으면 첫 페이지 (1 페이지)
        if ($page < 1) $page = 1;
        // 시작 레코드 구함
        $from_record = ($page - 1) * $items;

        $list = new item_list($skin_file, $ca['ca_mobile_list_mod'], $ca['ca_mobile_list_row'], $ca['ca_mobile_img_width'], $ca['ca_mobile_img_height']);
        $list->set_category($ca['ca_id'], 1);
//        $list->set_category($ca['ca_id'], 3);
        $list->set_is_page(true);
        $list->set_mobile(true);
        $list->set_order_by($order_by);
        $list->set_from_record($from_record);
        $list->set_view('it_img', true);
        $list->set_view('it_id', true);
        $list->set_view('it_name', true);
        $list->set_view('it_price', true);
        $list->set_view('sns', true);
        echo $list->run();

        // where 된 전체 상품수
        $total_count = $list->total_count;
        $total_page  = ceil($total_count / $items);
	} else {
		echo '<div class="sct_nofile">'.str_replace(G5_PATH.'/', '', $skin_file).' 파일을 찾을 수 없습니다.<br>관리자에게 알려주시면 감사하겠습니다.</div>';
	}


$qstr1 .= 'ca_id='.$ca_id;
$qstr1 .='&amp;sort='.$sort.'&amp;sortodr='.$sortodr;
echo get_paging($config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr1.'&amp;page=');
?>

<?php
    // 하단 HTML
	echo '<div id="sct_thtml">'.conv_content($ca['ca_mobile_tail_html'], 1).'</div>';
?>
</div>
<?php
include_once(G5_MSHOP_PATH.'/_tail.php');
echo "\n<!-- {$ca['ca_mobile_skin']} -->\n";
?>