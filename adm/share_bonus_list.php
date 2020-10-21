<?php
$sub_menu = "200990";
include_once('./_common.php');
$g5['title'] = '공유보너스';
include_once('./admin.head.php');
function freeyymm($flag, $yymm){
 if(!$yymm) return date("Ym");
 return date("Ym",strtotime($flag, strtotime($yymm."01")));
}

//auth_check($auth[$sub_menu], 'r');
$colspan = 6;
$year = date('Y');
$month = date('m');
$date = date('d');
$selecty = $syear?$syear:$year;
$selectm = $smonth?$smonth:$month;
$sdate = $selecty."-".$selectm;
$prev_date = freeyymm("-4 month", $selecty.$selectm);
$prev_y = substr($prev_date, 0, 4);
$prev_m = sprintf('%02d',substr($prev_date, 5, 2));
$sFlag = 0;
if($amb){
	$sFlag = 1;
	$where[] = " dp.way = 'BonusNewPayAmbassador' or dp.way='ShareBonusAmbassador'";
}
if($damb){
	$sFlag = 1;
	$where[] = " dp.way='ShareBonusDoubleAmbassador'";
}
if($tamb){
	$sFlag = 1;
	$where[] = " dp.way='ShareBonusTripleAmbassador'";
}
if($camb){
	$sFlag = 1;
	$where[] = " dp.way='ShareBonusCrownAmbassador'";
}
if($rcamb){
	$sFlag = 1;
	$where[] = " dp.way='ShareBonusRoyalCrown'";
}
if($stx){
	$stx_search = " WHERE (mb.mb_name like '%".$stx."%' or mb.mb_id like '%".$stx."%') ";
}
if($sFlag == 1){
	$sAm = "AND (".implode(' or ', $where).")";
}
$sql_common = " FROM (SELECT * FROM dayPoint_t AS dp ";
$sql_join = " LEFT JOIN g5_member_t mb ON mb.mb_id = TOTAL.mb_id ";
$sql_search = " WHERE (dp.way like 'BonusNewPay%' or dp.way like 'ShareBonus%') AND LEFT(dp.date,7) = '".$sdate."' ".$sAm." ORDER BY dp.date DESC) AS TOTAL  ";

$sql = " select count(*) as cnt
            {$sql_common}
            {$sql_search}
			{$sql_join} 
            {$stx_search}
			";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

//$rows = $config['cf_page_rows'];
$rows = 45;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select TOTAL.*, mb.mb_name, mb.accountRank
            {$sql_common}
            {$sql_search}
            {$sql_join} 
            {$stx_search}
			LIMIT {$from_record}, {$rows} ";
			//P($sql);
$result = sql_query($sql);

$sql1 = " SELECT * FROM sale_setting  WHERE set_year = '".$prev_y."' AND set_month='".$prev_m."' ";
$res = sql_query($sql1);
$rowSale=sql_fetch_array($res);
$hidden = "";
if($date < 20 || $sdate != date("Y-m")){
	$hidden = "hidden";
}else{
	if($rowSale['runBonus'] == 'Y' || $rowSale['id'] == "" ){
		$hidden = "hidden";
	}
}
?>
<form name="fvisit" id="fvisit" class="local_sch03 local_sch" method="get" style="position: relative;">
    <input type="checkbox" name="amb" value="Y" id="amb" <?=get_checked($amb, 'Y')?>>
    <label for="amb">AMBASSADOR</label>
    <input type="checkbox" name="damb" value="Y" id="damb" <?=get_checked($damb, 'Y')?>>
    <label for="damb">DOUBLE AMBASSADOR</label>
    <input type="checkbox" name="tamb" value="Y" id="tamb" <?=get_checked($tamb, 'Y')?>>
    <label for="tamb">TRIPLE AMBASSADOR</label>
    <input type="checkbox" name="camb" value="Y" id="camb" <?=get_checked($camb, 'Y')?>>
    <label for="camb">CROWN AMBASSADOR</label>
    <input type="checkbox" name="rcamb" value="Y" id="rcamb" <?=get_checked($rcamb, 'Y')?>>
    <label for="rcamb">Royal Crown AMBASSADOR</label>
	<div class="select_contain mg1 ">
		<select id="year_1" class="chg_select" name="syear" style="padding: 0 15px;">
			<?php
			for ($x = 2018; $x <= $year; $x++) {
				if ($x==$selecty){
					echo '<option value="' . $x . '" selected>' . $x . '년</option>';
				}else{
					echo '<option value="' . $x . '">' . $x . '년</option>';
				}
			}
			?>
		</select>
		<select id="month_1" class="chg_select" name="smonth" style="padding: 0 10px;">
			<?php
			for ($x = 1; $x <= 12; $x++) {
				if ($x==$selectm){
					echo '<option value="' . sprintf('%02d',$x) . '" selected>' . sprintf('%02d',$x). '월</option>';
				}else{
					echo '<option value="' . sprintf('%02d',$x) . '">' . sprintf('%02d',$x) . '월</option>';
				}
			}
			?>
		</select>
		<input type="text" name="stx" size="30" value="<?=$stx?>" id="sch_word" class="frm_input" placeholder="회원이름 ID">
		<input type="submit" value=" 검색 " class="btn_submit">&nbsp;
 		<span> <a href="/adm/share_bonus_list.php" class=""  style="background:#fff;padding:6px 15px;border:1px solid #999; position:absolute;"> 초기화 </a></span>
	</div>

    <div id="" onclick="" style="position:absolute;right:15px;top: 15px;">
        <button id="btn_run" class="btn_submit" <?=$hidden?>> 수동실행 </button>
    </div>
</form>
		

<div class="tbl_head01 tbl_wrap">
    <table>
    <thead>
    <tr>
        <th scope="col">날자</th>
        <th scope="col" style="min-width:240px">회원이름(ID)</th>
        <th scope="col" style="min-width:180px">현직급</th>
        <th scope="col">지급 (VMC)</th>
        <th scope="col">내용</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
			switch ( $row['way'] ) {
			  case 'BonusNewPayAmbassador':
					$contents = "엠버서더 신규지급 VMC : ".number_format($row['VMC']);
				break;
			  case 'ShareBonusRoyalCrown':
					$contents = "로얄 크라운 엠버서더 유지지급 VMC : ".number_format($row['VMC']);
				break;
			  case 'ShareBonusCrownAmbassador':
					$contents = "크라운 엠버서더 유지지급 VMC : ".number_format($row['VMC']);
				break;
			  case 'ShareBonusTripleAmbassador':
					$contents = "트리플 엠버서더 유지지급 VMC : ".number_format($row['VMC']);
				break;
			  case 'ShareBonusDoubleAmbassador':
					$contents = "더블 엠버서더 유지지급 VMC : ".number_format($row['VMC']);
				break;
			  case 'ShareBonusAmbassador':
					$contents = "엠버서더 유지지급 VMC : ".number_format($row['VMC']);
				break;
			}
    ?>
    <tr class="<?php echo $bg; ?>">
        <td class="td_datetime"><?=date($row['date'])?></td>
        <td  class="td_category"><?=$row['mb_name']?> [<?=$row['mb_id']?>]</td>
        <td class="td_category"><?=$row['accountRank']?></td>
        <td class="td_category"><?=number_format($row['VMC'])?></td>
        <td><?=$contents?></td>
    </tr>

    <?php
    }
	if($i == 0 && $rowSale["id"] == "" ){
		echo  '<tr><td colspan="'.$colspan.'" class="empty_table">매월20일 자동실행 됩니다. 매출금액 설정해주세요. <br> 미실행시 매월 20일 이후 매출금액설정후 상단 수동실행버튼으로 실행 가능 합니다.  <br><a href="/adm/member_modify.0109.php" style="color:#f00"> <strong>매출금액 설정하기</strong></a></td></tr>';
	}
    ?>
    </tbody>
    </table>
</div>
<style>
    .loading_wrap.on {display: block}
    .loading_wrap.off {display: none}
    .loading_wrap {display: none; position: fixed; top: 0; margin-top:70px; left: 0; right: 0; bottom: 0;z-index: 9999; background: rgba(255,255,255,0.7); text-align: center}
    .loading_wrap .loading_content {position: absolute; top: 50%; left: 50%; margin-top: -150px; margin-left: -200px; width: 400px; height: 300px;}
    .loading_wrap .loading_content .loading_img {margin: 0 auto}
    .loading_wrap.on .loading_content .loading_txt {font-family: 'Noto Sans KR'; font-size: 1rem; color: #333}
    @media (min-width: 320px) and (max-width: 480px){
        .loading_wrap .loading_content {margin-left: -100px; width: 200px;}
        .loading_wrap .loading_content .loading_img {width: 80%}
        .loading_wrap.on .loading_content .loading_txt {font-size: 1.2rem}
    }
</style>
<div class="loading_wrap">
<div class="loading_content">
    <img class="loading_img" src="/myOffice/images/vmp_loading_icon.gif" alt="로딩메세지"/>
    <p class="loading_txt">공유보너스를 지급중입니다.<br>
    잠시만 기다려 주세요.</p>
</div>
</div>
<?php
if (isset($domain))
$qstr .= "&amp;domain=$domain";
$qstr .= "&amp;page=";
$qstr .= "&amp;syear=$selecty";
$qstr .= "&amp;smonth=$selectm";
$qstr .= "&amp;amb=$amb";
$qstr .= "&amp;damb=$damb";
$qstr .= "&amp;tamb=$tamb";
$qstr .= "&amp;camb=$camb";
$qstr .= "&amp;rcamb=$rcamb";

$pagelist = get_paging($config['cf_write_pages'], $page, $total_page,  "{$_SERVER['SCRIPT_NAME']}?$qstr");
echo $pagelist;
include_once('./admin.tail.php');
?>
<script>
$(document).ready(function(){
	$('#btn_run').click(function(){
		var result = confirm('5~10분 정도 소요될 예정입니다. 실행하겠습니까?');
		if(result){
			$(this).attr( 'disabled', true );
			$.ajax({
				type : 'POST',  
				url : '/myOffice/share_bonus_ajax.php',
				success : function (data) {
				  if (data != ''){
                      $('.loading_wrap').removeClass('on');
                      location.reload();
                  }
				} , beforeSend: function () {
				    console.log("2222");
                    $('.loading_wrap').addClass('on')
                }
			});
		}
	});
});

function loading() {
    $('.check_msg').addClass('load');
    $('.loading_msg p').text('데이터를 조회중입니다. 잠시만 기다려주세요');
}

function fvisit_submit(act)
{
    var f = document.fvisit;
    f.action = act;
    f.submit();
}
</script>
