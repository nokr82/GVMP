<!-- 관리자 버니설정 -->
<?php 
$sub_menu = "000400";
include_once('./_common.php');
$g5['title'] = "포인트관리 > 버니 설정";
include_once('./admin.head.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/beosyong/dbConn.php');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

$colspan = 5;

include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
if (empty($fr_date) || ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = G5_TIME_YMD;
if (empty($to_date) || ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = G5_TIME_YMD;

$qstr = "fr_date=".$fr_date."&amp;to_date=".$to_date;
$query_string = $qstr ? '?'.$qstr : '';

$row = mysql_fetch_array(mysql_query("select * from bunnyConfTBL where name = 'commission'"));
?>


<!-- 버니관리 시작 -->
<div class="set_bn_wrap">
    <div class="set_bn_content">
        <div class="set_bn_tab">
            <strong>선배 버니에게 지급될 적립금</strong>
        </div>
        <form name="set_bn_form" id="set_bn_form">
        <div class="set_bn_form_box">
            <div class="set_form_box col_1">
                <input type="text" name="set_bn_point" id="set_bn_point" value="<?php echo number_format($row["value"]); ?>" autocomplete="off" onkeyup="onlyNumber(this)">
                <label for="set_bn_point">원</label>
            </div>
            <div class="set_form_box col_2">
                <a href="#none" class="btn set_bn_btn" onclick="submitF();">적용</a>
            </div>
        </div>
        </form>
    </div>
</div>
<!-- 버숑 설정 끝 -->

<script>
// 숫자만 입력가능하게 처리하는 부분 
function onlyNumber(obj){
    $(obj).keyup(function (event) {
        regexp = /[^0-9]/gi;
        value = $(this).val();
        $(this).val(comma(value.replace(regexp, '')));
    });
}

//콤마찍기
function comma(str) {
    str = String(str);
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}


    function submitF() {
        $.ajax({
            url:'set_beony_back.php',
            type:'POST',
           data : "set_bn_point="+$("#set_bn_point").val(),
            async: false,
            success:function(result){
                if( result == "false" ) {
                    alertMsg('error', '에러가 발생했습니다.', function() {alertOff();} );
                } else {
                    alertMsg('success', '수정이 완료되었습니다.', function() {window.location.reload();} );
                }
           }
        });
    }


</script>
<?php
include_once ('./admin.tail.php');
include_once ($_SERVER['DOCUMENT_ROOT'].'/beosyong/alert.php');
include_once ('./alert.php');
?>

