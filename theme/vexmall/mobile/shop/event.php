<?php
include_once('./_common.php');


$sql = " UPDATE {$g5['g5_shop_event_table']} SET ev_use = '0' WHERE ev_end_date < '".date("Y-m-d")."'";
sql_query($sql);


$ev_use = (isset($_GET['ev_use'])&&trim($_GET['ev_use']!=""))?trim($_GET['ev_use']):'1';
$sql = " SELECT * FROM {$g5['g5_shop_event_table']} WHERE ev_use = {$ev_use} order by ev_id DESC";
$result = sql_query($sql);
$total_rows = sql_num_rows($result);
include_once(G5_MSHOP_PATH.'/_head.php');
?>


<div id="tabEvent">
    <ul class="event_state clearfix">        
        <li>
            <a href="?ev_use=1" <? if($ev_use=="1"){?>class="on"<?}?>>진행중인 이벤트</a>
        </li>
        <li>
            <a href="?ev_use=0" <? if($ev_use=="0"){?>class="on"<?}?>>종료된 이벤트</a>
        </li>
    </ul>
    <!--display none 해제시 이벤트 내역 보임 style="display: none;"-->
        <!--li class end 추가시 종료된 이벤트 모양-->
<? if($total_rows>0){?>
    <ul class="event_list">
<?	while($row=sql_fetch_array($result)){?>
        <li <?if($ev_use=="0"){?>class="end"<?}?>>
            <a href="./eventdetail.php?ev_id=<?=$row['ev_id']?>">
                <div>
                    <img src="/data/event/<?=$row['ev_id']?>_m" alt="이벤트 이미지" />
                </div>                
                <h3><?=$row['ev_subject']?></h3>
                <p><?=substr($row['ev_start_date'],0,10)?> ~ <?=substr($row['ev_end_date'],0,10)?></p>
            </a>
        </li>
<?	}?>
    </ul>
<?}else{?>
    <div class="none_event">
        <img src="<?=G5_THEME_IMG_URL?>/event/vroad_alert.svg" alt="경고표시" />
        <h3>이벤트가 없습니다.</h3>
    </div>
<?}?>
</div>



<?php
include_once(G5_MSHOP_PATH.'/_tail.php');
?>
