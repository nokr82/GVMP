<!--V 광고센터--> 



<link rel="shortcut icon" href="/img/vmp_logo.ico" />
<link rel="stylesheet" href="../font/nanumsquare.css"/>
<link rel="stylesheet" href="./css/v_ad_center.css" />
<link rel="stylesheet" href="css/animate.css"/>
<script src="./js/wow.min.js"></script>
<link href="https://fonts.googleapis.com/css?family=Noto+Sans+KR&display=swap&subset=korean" rel="stylesheet" />
<?php
include_once ('./inc/title.php');
include_once ('./_200923common.php');
include_once ('./dbConn.php');
include_once('./getMemberInfo.php');
//include_once ('../shop/shop.head.php');
include_once(G5_THEME_MSHOP_PATH.'/200923shop.head.php');
?>

<!-- V광고센터 시작 -->
<div class="v_ad_wrap">
    <div class="v_ad_content">
        <?php
        include_once './v_ad_helper.php';
        ?>
        <?php
        include_once './v_ad_point.php';
        ?>
        <div class="ad_list_wrap">
            <h4>광고 리스트</h4>
            <a href="./v_ad_make.php" class="btn_v_ad btn_make_ad">광고 만들기<i></i></a>
            <div class="ad_list wow fadeInUp">
                <form action="" method="get" name="ad_list_form" id="ad_list_form">
                    <table>
                        <caption class="haze">광고 리스트</caption>
                        <thead>
                            <tr>
                                <th>구분</th>
                                <th>광고명</th>
                                <th>ON/OFF</th>
                                <th>하루예산</th>
                                <th>상태</th>
                                <th>관리</th>
                            </tr>
                        </thead>
                        <tbody>
                            
<?php
    $countNumber = 0;
    $imageAdListTBLRe = mysql_query("select * from imageAdListTBL where mb_id = '{$member["mb_id"]}' order by datetime desc");
    while( $imageAdListTBLRow = mysql_fetch_array($imageAdListTBLRe) ) {
        $countNumber++;
        ?>
            <tr>
                <td class="ad_cat"><b class="ad_txt">구분</b>이미지</td>
                <td class="ad_tit"><?=$imageAdListTBLRow["adName"]?></td>
                <td class="ad_toggle <?php if($imageAdListTBLRow["state"]=="Y") {echo "on";} ?>" onclick="onOffFunc($(this), 'image', <?=$imageAdListTBLRow["no"]?>);">
                    <b class="ad_txt">ON/OFF</b>
                    <a href="#none" class="btn_toggle">광고 상태 변경</a>
                </td>
                <td class="ad_money"><b class="ad_txt">하루예산</b><?php echo number_format($imageAdListTBLRow["budget"]); ?>원</td>
                <td class="ad_state">
                    <b class="ad_txt">상태</b>
<?php
    if( $imageAdListTBLRow["ok"] == "W" ) { // 승인 대기
        echo '<a href="#none" class="btn_v_ad_list btn_await">승인대기</a>';
    } else if( $imageAdListTBLRow["ok"] == "N" ) { // 승인 거절
        echo '<a href="#none" class="btn_v_ad_list btn_reject" onclick="rejectMsg(\''.$imageAdListTBLRow["comment"].'\')">승인거절</a>';
    } else if( $imageAdListTBLRow["ok"] == "Y" ) { // 승인 완료
        echo '<a href="#none" class="btn_v_ad_list btn_seal">승인완료</a>';
    }
?>
                    
                                    
                    
                </td>
                <td>
                    <b class="ad_txt">관리</b>
                    <a href="v_ad_make.php?type=image&no=<?=$imageAdListTBLRow["no"]?>" class="btn_v_ad_list btn_modi">수정</a>
                    <a href="#none" class="btn_v_ad_list btn_modi btn_delete" onclick="ad_delete_func( 'image', '<?=$imageAdListTBLRow["no"]?>' );">삭제</a>
                </td>
            </tr>
        <?php
    }
?>
                            

           
            
<?php
    $textAdListTBLRe = mysql_query("select * from textAdListTBL where mb_id = '{$member["mb_id"]}' order by datetime desc");
    while( $textAdListTBLRow = mysql_fetch_array($textAdListTBLRe) ) {
        $countNumber++;
        ?>
            <tr>
                <td class="ad_cat"><b class="ad_txt">구분</b>텍스트</td>
                <td class="ad_tit"><?=$textAdListTBLRow["adName"]?></td>
                <td class="ad_toggle <?php if($textAdListTBLRow["state"]=="Y") {echo "on";} ?>" onclick="onOffFunc($(this), 'text', <?=$textAdListTBLRow["no"]?>);">
                    <b class="ad_txt">ON/OFF</b>
                    <a href="#none" class="btn_toggle">광고 상태 변경</a>
                </td>
                <td class="ad_money"><b class="ad_txt">하루예산</b><?php echo number_format($textAdListTBLRow["budget"]); ?>원</td>
                <td class="ad_state">
                    <b class="ad_txt">상태</b>
<?php
    if( $textAdListTBLRow["ok"] == "W" ) { // 승인 대기
        echo '<a href="#none" class="btn_v_ad_list btn_await">승인대기</a>';
    } else if( $textAdListTBLRow["ok"] == "N" ) { // 승인 거절
        echo '<a href="#none" class="btn_v_ad_list btn_reject" onclick="rejectMsg(\''.$textAdListTBLRow["comment"].'\')">승인거절</a>';
    } else if( $textAdListTBLRow["ok"] == "Y" ) { // 승인 완료
        echo '<a href="#none" class="btn_v_ad_list btn_seal">승인완료</a>';
    }
?>
                    
                                    
                    
                </td>
                <td>
                    <b class="ad_txt">관리</b>
                    <a href="v_ad_make.php?type=text&no=<?=$textAdListTBLRow["no"]?>" class="btn_v_ad_list btn_modi">수정</a>
                    <a href="#none" class="btn_v_ad_list btn_modi btn_delete" onclick="ad_delete_func( 'text', '<?=$textAdListTBLRow["no"]?>' );">삭제</a>
                </td>
            </tr>          
        <?php
    }
    if( $countNumber == 0 ) {
        ?> <tr><td colspan="6" style="top:0; left: 0; line-height: 2.2">만들어진 광고가 없습니다.</td></tr> <?php
    }
?>                         
            
                            
                        </tbody>
                    </table>
                </form>

            </div>
        </div>
    </div>
</div>
<!-- V광고센터 끝 -->

<?php
include_once ('./v_ad_alert.php');
?>
<?php
include_once ('./alert.php');
?>

<script>
    

    
    
    
    
    
    
    
// 애니메이션 효과 
new WOW().init();

// 탭, 모바일 사이즈일 경우 도움말 알림서비스 보여주기
$( document ).ready( function() {
    var width = $(window).width();
    if( width <= 800 ){
        alertMsg('on', '도움말 알림서비스', '도움말이 필요하시면 ', '여기', ' 를 눌러주세요', '', function(){$('.helper_content').css('display','block');});
    }
});



//ON/OFF 스위치 
function onOffFunc(obj, type, no) {
    var check = "";
    if( obj.hasClass('on') ){
        obj.removeClass('on');
        alertMsg('on', 'ON/OFF 알림서비스', '광고서비스가 ', '비활성화(OFF)', ' 되었습니다.', '', function(){});
        check = "off";
    } else {
        obj.addClass('on')
        alertMsg('on', 'ON/OFF 알림서비스', '광고서비스가 ', '활성화(ON)', ' 되었습니다.', '', function(){});
        check = "on";
    }
    
     $.ajax({
        url:'v_ad_onOff_back.php',
        type:'POST',
        data: "type="+type+"&no="+no+"&check="+check,
        async: false,
        success:function(result){
            if( result == "false" ) {
                alertMsg('error','에러가 발생했습니다.');
            } else {
                
            }
       }
    });
}


//광고리스트 드롭다운
$('tbody > tr').on('click', function(ev){

    if( !($(ev.target).hasClass('ad_tit')) ){
        return;
    }
    
    if( $(this).hasClass('on') ){
        $(this).removeClass('on');
        
    } else {
        $(this).addClass('on')
    }
});



//승인거절 일 때 사유보여주기
function rejectMsg(msg){
    msgAlert('error', msg, function(){alertOff();});
}


// 광고 삭제 처리하는 함수
function ad_delete_func( type, no ) {


    msgAlert('confirm','삭제 하시겠습니까?',function(){ alertOff(); GO( type, no ) }, function(){ alertOff();});


    function GO( type, no ) {
        $.ajax({
            url:'v_ad_delete_back.php',
            type:'POST',
            data: "type="+type+"&no="+no,
            async: false,
            success:function(result){
                if( result == "false" ) {
                    alertMsg('error','에러가 발생했습니다.');
                } else {
                    location.reload();
                }
           }
        });
    }

}





</script>








<?php
//include_once ('../shop/shop.tail.php');
include_once(G5_THEME_MSHOP_PATH.'/201012shop.tail.php');

?>


