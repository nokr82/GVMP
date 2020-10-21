<!-- 관리자 포인트지급 내역  -->
<?php
$sub_menu = "000500";
include_once('./_common.php');
$g5['title'] = "포인트관리 > 회원 총 포인트 내역";
include_once('./admin.head.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/beosyong/dbConn.php');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');





include_once(G5_PLUGIN_PATH . '/jquery-ui/datepicker.php');
if (empty($fr_date) || !preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date))
    $fr_date = G5_TIME_YMD;
if (empty($to_date) || !preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date))
    $to_date = G5_TIME_YMD;

$qstr = "fr_date=" . $fr_date . "&amp;to_date=" . $to_date;
$query_string = $qstr ? '?' . $qstr : '';



$pointRow1 = mysql_fetch_array(mysql_query("select sum(point) as p from g5_member"));


 $pointRe2 = mysql_query("select point-withdrawPoint as p, datetime from pointTBL where point != withdrawPoint and (payType = '카드 결제' or payType = '네이버 결제')");
    $pointRow3 = mysql_fetch_array(mysql_query("select sum(point-withdrawPoint) as p from pointTBL where point != withdrawPoint and ((payType != '카드 결제' and payType != '네이버 결제')or payType is null)"));
    $pointRow2Total = 0;
  while( $pointRow2 = mysql_fetch_array($pointRe2) ) {
        $str_now = strtotime(date("Y-m-d", strtotime(dateFunc(date("Y-m-d", strtotime($pointRow2["datetime"]))))));
        $str_target = strtotime(date("Y-m-d"));
        
        if( $str_now <= $str_target ) {
            $pointRow2Total += $pointRow2["p"];
        }
    }

    function dateFunc( $curdate ) {
        $mydate=getdate(strtotime($curdate));
        switch($mydate['wday']) {
            case 0: // sun
                $days = 5;
                break;
            case 1: // mon
                $days = 7;
                break;
            case 2:
            case 3:
            case 4:
            case 5:
                $days = 7;
                break;
            case 6: // sat
                $days = 6;
                break;
        }
        return date('Y-m-d', strtotime("$curdate +$days days"));
    }


?>

<!-- 회원 총 포인트 내역 시작 -->
<div class="pay_point_wrap">
    <div class="pay_point_content">
        <div class="pay_point_view view_sum_box total_list">
            <div class="total_reqst view_cont mr10">
                <span class="reqst_info_box info_box">
                    <em>총 보유 포인트</em>
                    <strong>&#92;<b id="totalMoney"><?= number_format($pointRow1['p']); ?> </b>원</strong>
                </span>
                <span class="reqst_icon_box icon_box">
                    <img src="./img/adm_coin.png" alt="총 지급된 포인트"/>
                </span>
            </div>
            <div class="total_reqst view_cont">
                <span class="reqst_info_box info_box">
                    <em>총 출금 가능 포인트</em>
                    <strong>&#92;<b id="totalMoney"><?php echo number_format($pointRow2Total+$pointRow3["p"]) ?></b>원</strong>
                </span>
                <span class="reqst_icon_box icon_box">    
                    <img src="./img/adm_coin.png" alt="총 지급된 포인트"/>
                </span>
            </div>
        </div>
    </div>
</div>
<!-- 회원 총 포인트 내역 끝 -->

<?php
include_once ('./admin.tail.php');
include_once ($_SERVER['DOCUMENT_ROOT'] . '/beosyong/alert.php');
?>

