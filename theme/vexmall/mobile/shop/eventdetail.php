<?php
include_once('./_common.php');

$ev_use = (isset($_GET['ev_use'])&&trim($_GET['ev_use']!=""))?trim($_GET['ev_use']):'1';
$sql = " SELECT * FROM {$g5['g5_shop_event_table']} WHERE ev_id = '{$ev_id}'";
$result = sql_query($sql);
$total_rows = sql_num_rows($result);

if($total_rows) {
	$row=sql_fetch_array($result);
}
include_once(G5_MSHOP_PATH.'/_head.php');
?>

<div id="event_detail">
    <div class="detail_head" onclick="history.back(-1);">
        <h3><?=$row['ev_subject']?></h3>
        <p>이벤트 기간: <?=substr($row['ev_start_date'],0,10)?> ~ <?=substr($row['ev_end_date'],0,10)?></p>
    </div>
    <div class="detail_content">
        <?=$row['ev_head_html']?>
    </div>
</div>

<?php
include_once(G5_MSHOP_PATH.'/_tail.php');
?>
