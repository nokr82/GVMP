<!--V 광고 만들기--> 

<link rel="shortcut icon" href="/img/vmp_logo.ico" />
<link rel="stylesheet" href="../font/nanumsquare.css"/>
<link rel="stylesheet" href="css/animate.css"/>
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />

<script src="./js/wow.min.js"/></script>
<link href="https://fonts.googleapis.com/css?family=Noto+Sans+KR&display=swap&subset=korean" rel="stylesheet">
<?php
include_once ('./inc/title.php');
include_once ('./_common.php');
include_once ('./dbConn.php');
include_once('./getMemberInfo.php');
include_once ('../shop/shop.head.php');


include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
if (empty($fr_date) || ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = G5_TIME_YMD;
if (empty($to_date) || ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = G5_TIME_YMD;

$qstr = "fr_date=".$fr_date."&amp;to_date=".$to_date;
$query_string = $qstr ? '?'.$qstr : '';




?>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.8.22/jquery-ui.min.js"></script> 
<script>
// datepicker
// Cannot read property 'msie' of undefined 에러 나올때
jQuery.browser = {};
(function () {
    jQuery.browser.msie = false;
    jQuery.browser.version = 0;
    if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
        jQuery.browser.msie = true;
        jQuery.browser.version = RegExp.$1;
    }
})();


$.datepicker.regional["ko"] = {
 prevText: "이전달",
 nextText: "다음달",
 currentText: "오늘",
 monthNames: ["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"],
 monthNamesShort: ["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"],
 dayNames: ["일","월","화","수","목","금","토"],
 dayNamesShort: ["일","월","화","수","목","금","토"],
 dayNamesMin: ["일","월","화","수","목","금","토"],
 weekHeader: "Wk",
 dateFormat: "yy-mm-dd",
 firstDay: 0, // Sunday is 0, Monday is 1
 isRTL: false,
 showMonthAfterYear: true,
 yearSuffix: "년",
 closeText: '닫기'
};

$.datepicker.setDefaults($.datepicker.regional["ko"]);

</script>
<link rel="stylesheet" href="./css/v_ad_center.css" />
<link rel="stylesheet" href="./css/jquery.mCustomScrollbar.min.css" />
<script src="./js/jquery.mCustomScrollbar.concat.min.js"></script>
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo G5_PLUGIN_URL ?>/jquery-ui/jquery-ui.css">

<div class="v_ad_wrap">
    <div class="v_ad_content">
        <?php
        include_once './v_ad_helper.php';
        ?>
        <div class="v_ad_make">
            <?php
                if( $_GET["type"] == "" || $_GET["no"] == "" ) {
                    echo '<h3>광고 만들기</h3>';
                } else {
                    echo '<h3>광고 수정</h3>';
                }
            ?>
            
            <div class="make_txt_info wow fadeInUp">
                <p>광고가 노출될 때 마다 광고비가 지불되는 <strong>종량제 및 가격정찰제 방식의 광고</strong>입니다.</p>
            </div>
            <?php
            include_once './v_ad_point.php';
            ?>
            <div class="v_ad_banner wow fadeInDown">
                <div class="banner_teaser">
                    <div class="banner_icon">
                        <i>C</i>
                        <i>P</i>
                        <i>M</i>
                    </div>
                    <p><i class="banner_icon_point banner_icon_vmc">VMC</i><i>, </i><i class="banner_icon_point banner_icon_vmp">VMP</i><i>, </i><i class="banner_icon_point banner_icon_vmm">VMM</i> 포인트로 광고 배너를 제작하실 수 있습니다.</p>
                    <div class="banner_tit">
                        <em>광고 배너</em><strong>제작 서비스</strong>   
                    </div>
                    <a href="#none">배너 제작문의</a>
                </div>
                <!--banner_pop_wrap에 클래스 on을 추가하면 활성화 됩니다-->
                <div class="banner_pop_wrap">
                    <div class="banner_pop_content">
                        <form name="ask_ad_prod" id="ask_ad_prod" method="" onsubmit="return bannerMake($(this))">
                            <input type="hidden" name="mb_id" value="<?=$member["mb_id"]?>">
                        <a href="#none" class="btn_close">닫기</a>
                        <h3>광고배너 제작문의</h3>
                        <p>고객님께 맞는 디자인 방향을 제대로 진단하고 선별, 추천하여 제작합니다.<br><b style="padding-top : 10px; display: block;">제작 비용 : 10만원(포인트 사용 가능)</b></p>
                        <div class="banner_user_info">
                            <div class="col_1">
                                <label for="mb_name">성함 :</label>
                                <input type="text" id="mb_name" name="mb_name" value="" placeholder="홍길동" required>
                            </div>
                            <div class="col_2">
                                <label for="mb_addr_number">전화번호 :</label>
                                <input type="text" id="mb_addr_number" name="mb_addr_number" value="" placeholder="010-1234-5678" onkeyup="onlyNumber(this)" required>
                            </div>
                        </div>
                        <input type="submit" class="btn_banner_pop" value="문의하기">
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="v_ad_user_content wow fadeInDown">
                <h4>광고정보 입력</h4>
                <?php
                    if( $_GET["type"] == "" || $_GET["no"] == "" ) {
                        ?>
                            <form action="" name="ad_select" id="ad_select">
                            <div class="v_ad_choice">
                                <div class="choice_img user_select">
                                    <input type="radio" value="img" id="ad_img" name="ad_choice" checked="true">
                                    <label for="ad_img">이미지 광고</label>
                                    <p>이미지 형식의 배너 광고입니다.</p>
                                </div>
                                <div class="choice_txt user_select">
                                    <input type="radio" value="text" id="ad_text" name="ad_choice">
                                    <label for="ad_text">텍스트 광고</label>
                                    <p>텍스트로 나열되는 형식의 광고입니다.</p>
                                </div>
                            </div>
                            </form>
                        <?php
                    }
                ?>

                
            <?php
                if( $_GET["type"] == "image" && $_GET["no"] != "" || ($_GET["type"]!='image' && $_GET["type"]!='text') ) {
                    $imageRow = mysql_fetch_array(mysql_query("select * from imageAdListTBL where no = {$_GET["no"]}"));
                    ?>
                    <form action="" name="ad_select" id="ad_select" method="POST" enctype="multipart/form-data" onsubmit="return adInfoForm('img')">
                        <input type="hidden" name="mb_id" value="<?=$member["mb_id"]?>">
                        <input type="hidden" name="no" value="<?=$_GET["no"]?>">
                    <input type="hidden" name="select_type" id="select_type_img" value="image">
                    <?php
                        if( $_GET["type"] != "" && $_GET["no"] != "" ) {
                            echo '<input type="hidden" id="modi" name="modi" value="Y">';
                        } else {
                            echo '<input type="hidden" id="modi" name="modi" value="N">';
                        }
                    ?>
                    <ul class="v_ad_user_info user_info_img">
                        <li>
                            <div class="col_1">
                                <label for="mb_ad_title">광고명</label>
                            </div>
                            <div class="col_2">
                                <input type="text" name="mb_ad_title" id="mb_ad_title" value="<?=$imageRow["adName"]?>" placeholder="광고대행사 마케팅" required>
                            </div>
                        </li>
                        <li>
                            <div class="col_1">
                                <label for="mb_company">회사명</label>
                            </div>
                            <div class="col_2">
                                <input type="text" name="mb_company" id="mb_company" value="<?=$imageRow["companyName"]?>" placeholder="VMP" required>
                            </div>
                        </li>
                        <li>
                            <div class="col_1">
                                <label for="mb_money">하루예산</label>
                            </div>
                            <div class="col_2">
                                <input type="text" name="mb_money" id="mb_money" value="<?php if($imageRow["budget"]!="") {echo number_format($imageRow["budget"]);}?>" placeholder="50,000" onkeyup="onlyNumberComma(this)" required>
                                <b>원</b>
                            </div>
                        </li>
                        <li>
                            <div class="col_1">
                                <strong>기간</strong>
                            </div>
                            <div class="col_2">
                                <div class="v_ad_sch sch_list">
                                    <input type="text" name="fr_date" id="fr_date" class="frm_input" value="<?=$imageRow["frDate"]?>" size="11" maxlength="10" autocomplete="off" placeholder="시작일" required>
                                    <label for="fr_date" class="sound_only">시작일</label>
                                    <i>~</i>
                                    <input type="text" name="to_date" id="to_date" class="frm_input" value="<?=$imageRow["toDate"]?>" size="11" maxlength="10" autocomplete="off" placeholder="종료일" required>
                                    <label for="to_date" class="sound_only">종료일</label>
                                </div>  
                            </div> 
                        </li>
                        <li class="user_info_file">
                            <div class="col_1">
                                <strong>배너이미지(PC)</strong>
                                <em>권장 사이즈 1024px X 215px</em>
                            </div>
                            <div class="col_2">
                                <input type="file" name="mb_ad_img_pc" id="mb_ad_img_pc" accept="image/*" src="/up/ad/image/<?=$imageRow["image1"]?>">
                                <label for="mb_ad_img_pc" class="btn_ad_user btn_add_img">첨부파일 등록</label>
                                <a href="#none" class="btn_ad_user btn_preview" onclick="previewImg(this)">미리보기</a>
                                <!-- v_ad_preview 클래스 active를 추가하면 활성화 됩니다. -->
                                <div class="v_ad_preview">
                                    <div class="preview_img_box scrollbar">
                                        <div class="preview_img">
                                            <img src="/up/ad/image/<?=$imageRow["image1"]?>" alt="이미지 미리보기">
                                        </div>  
                                        <div class="scrollbar_info">
                                            <i class="icon_arrow arrow_blink_1"></i>
                                            <i class="icon_arrow arrow_blink_1"></i>
                                            <span>좌우로 스크롤을 해주세요</span>
                                            <i class="icon_arrow right_arrow arrow_blink_2"></i>
                                            <i class="icon_arrow right_arrow arrow_blink_2"></i>
                                        </div>
                                    </div>
                                    <a href="#none" class="btn_close">닫기</a>
                                </div>
                                <p class="mb_file_name">파일명: <i><?php if($imageRow["image1"]=="") {echo "파일없음";} else {echo $imageRow["image1"];} ?></i></p>
                            </div>
                        </li>
                        <li class="user_info_file">
                            <div class="col_1">
                                <strong>배너이미지(MOBILE)</strong>
                                <em>권장 사이즈 725px X 323px</em>
                            </div>
                            <div class="col_2">
                                <input type="file" name="mb_ad_img_mobile" id="mb_ad_img_mobile" accept="image/*" src="/up/ad/image/<?=$imageRow["image1"]?>">
                                <label for="mb_ad_img_mobile" class="btn_ad_user btn_add_img">첨부파일 등록</label>
                                <a href="#none" class="btn_ad_user btn_preview" onclick="previewImg(this)">미리보기</a>
                                <!-- v_ad_preview 클래스 active를 추가하면 활성화 됩니다. -->
                                <div class="v_ad_preview">
                                    <div class="preview_img_box mobile_img_box scrollbar">
                                        <div class="preview_img mobile_img">
                                            <img src="/up/ad/image/<?=$imageRow["image2"]?>" alt="이미지 미리보기">
                                        </div> 
                                        <div class="scrollbar_info">
                                            <i class="icon_arrow arrow_blink_1"></i>
                                            <i class="icon_arrow arrow_blink_1"></i>
                                            <span>좌우로 스크롤을 해주세요</span>
                                            <i class="icon_arrow right_arrow arrow_blink_2"></i>
                                            <i class="icon_arrow right_arrow arrow_blink_2"></i>
                                        </div>
                                    </div>
                                    <a href="#none" class="btn_close">닫기</a>
                                </div>
                                <p class="mb_file_name">파일명: <i><?php if($imageRow["image1"]=="") {echo "파일없음";} else {echo $imageRow["image1"];} ?></i></p>
                            </div>
                        </li>
                        <li>
                            <div class="col_1">
                                <label for="mb_url">광고 URL</label>
                            </div>
                            <div class="col_2">
                                <input type="text" name="mb_url" id="mb_url" value="<?=$imageRow["url"]?>" placeholder="http://gvmp.company">
                            </div>
                        </li>
                    </ul>
                    <div class="user_info_btn_box user_info_img_btn">
                        <input type="submit" value="<?php if($_GET["type"]!="") {echo "수정하기";} else {echo '등록하기';} ?>">
                        <a href="./v_ad_center.php">목록</a>
                    </div>
                    </form>
                    <?php
                }
            ?>
            
                
                
                
            <?php
                if( $_GET["type"] == "text" && $_GET["no"] != "" || ($_GET["type"]!='image' && $_GET["type"]!='text') ) {
                    $textRow = mysql_fetch_array(mysql_query("select * from textAdListTBL where no = {$_GET["no"]}"));
                    ?>
                        <form action="" name="ad_select" id="ad_select" onsubmit="return adInfoForm('txt')">
                            <input type="hidden" name="mb_id" value="<?=$member["mb_id"]?>">
                            <input type="hidden" name="no" value="<?=$_GET["no"]?>">
                        <input type="hidden" name="select_type" id="select_type_txt" value="text">
                        <?php
                            if( $_GET["type"] != "" && $_GET["no"] != "" ) {
                                echo '<input type="hidden" id="modi" name="modi" value="Y">';
                            } else {
                                echo '<input type="hidden" id="modi" name="modi" value="N">';
                            }
                        ?>
                        
                        <ul class="v_ad_user_info user_info_txt">
                            <li>
                                <div class="col_1">
                                    <label for="mb_ad_title">광고명</label>
                                </div>
                                <div class="col_2">
                                    <input type="text" name="mb_ad_title" id="mb_ad_title" value="<?=$textRow["adName"]?>" placeholder="광고대행사 마케팅" required>
                                </div>
                            </li>
                            <li>
                                <div class="col_1">
                                    <label for="mb_company">회사명</label>
                                </div>
                                <div class="col_2">
                                    <input type="text" name="mb_company" id="mb_company" value="<?=$textRow["companyName"]?>" placeholder="VMP" required>
                                </div>
                            </li>
                            <li>
                                <div class="col_1">
                                    <label for="mb_money">하루예산</label>
                                </div>
                                <div class="col_2">
                                    <input type="text" name="mb_money" id="mb_money" value="<?php if($textRow["budget"]!="") {echo number_format($textRow["budget"]);}?>" placeholder="50,000" onkeyup="onlyNumberComma(this)" required>
                                    <b>원</b>
                                </div>
                            </li>
                            <li>
                                <div class="col_1">
                                    <strong>기간</strong>
                                </div>
                                <div class="col_2">
                                    <div class="v_ad_sch sch_list">
                                        <input type="text" name="fr_date" id="fr_date_2" class="frm_input" value="<?=$textRow["frDate"]?>" size="11" maxlength="10" autocomplete="off" placeholder="시작일" required>
                                        <label for="fr_date" class="sound_only">시작일</label>
                                        <i>~</i>
                                        <input type="text" name="to_date" id="to_date_2" class="frm_input" value="<?=$textRow["toDate"]?>" size="11" maxlength="10" autocomplete="off" placeholder="종료일" required>
                                        <label for="to_date" class="sound_only">종료일</label>
                                    </div>  
                                </div> 
                            </li>
                            <li class="user_info_textarea">
                                <div class="col_1">
                                    <label for="mb_textarea">광고문구</label>
                                </div>
                                <div class="col_2">
                                    <textarea type="text" name="mb_textarea" id="mb_textarea" value="" placeholder="광고문구를 입력해주세요." onKeyUp="javascript:fnChkByte(this,'100')" required><?=$textRow["text"]?></textarea>
                                    <span id="byteInfo"><i class="byteInfo_limit">0</i>/ 100bytes</span>
                                </div>
                            </li>
                            <li>
                                <div class="col_1">
                                    <label for="mb_url">광고 URL</label>
                                </div>
                                <div class="col_2">
                                    <input type="text" name="mb_url" id="mb_url" value="<?=$textRow["url"]?>" placeholder="http://gvmp.company">
                                </div>
                            </li>
                        </ul>
                        <div class="user_info_btn_box user_info_txt_btn">
                            <input type="submit" value="<?php if($_GET["type"]!="") {echo "수정하기";} else {echo '등록하기';} ?>">
                            <a href="./v_ad_center.php">목록</a>
                        </div>
                        </form>
                        <?php
                            if( $_GET["type"] == "text" ) {
                                ?>
                                    <script>
                                        $('.user_info_img').css('display', 'none');
                                        $('.user_info_txt').css('display', 'block');
                                        $('.user_info_img_btn').css('display', 'none');
                                        $('.user_info_txt_btn').css('display', 'block');    
                                    </script>
                                <?php
                            }
                        ?>

                    <?php
                }
            ?>
                
                
                

            </div>
        </div>
    </div>
</div>

<?php
include_once ('./v_ad_alert.php');
?>
<?php
include_once ('./alert.php');
?>

<script>
  

  
// 애니메이션 효과 
new WOW().init();


//배너 광고 제작문의하기
$('.v_ad_banner').on('click', function(){
    
    $('.banner_pop_wrap').addClass('on');
    
    $('.banner_pop_wrap .btn_close').on('click', function(ev){
        ev.stopPropagation();
        $('.banner_pop_wrap').removeClass('on');
    });
});


//선택사항에 따라 입력폼 변경하기    
$('.user_select').on('click', function(){

    if( $(this).hasClass('choice_img') ){
        $('#ad_img').prop('checked',true);
        $('#ad_text').prop('checked',false);
        $('.user_info_txt').css('display', 'none');
        $('.user_info_img').css('display', 'block');
        $('.user_info_img_btn').css('display', 'block');
        $('.user_info_txt_btn').css('display', 'none');

        
    } else if ( $(this).hasClass('choice_txt') ){
        $('#ad_text').prop('checked',true);
        $('#ad_img').prop('checked',false);
        $('.user_info_img').css('display', 'none');
        $('.user_info_txt').css('display', 'block');
        $('.user_info_img_btn').css('display', 'none');
        $('.user_info_txt_btn').css('display', 'block');
    }
});
    
    
// 숫자와 콤마 입력가능하게 처리하는 부분 
function onlyNumberComma(obj){
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

//광고문구 글자수 제한하기
function fnChkByte(obj, maxByte){
    var str = obj.value;
    var str_len = str.length;

    var rbyte = 0;
    var rlen = 0;
    var one_char = "";
    var str2 = "";

    for(var i=0; i<str_len; i++){
        one_char = str.charAt(i);
        
        if(escape(one_char).length > 4){
            rbyte += 2; //한글2Byte                                         
        } else {
            rbyte++; //영문 등 나머지 1Byte                                      
        }


        if(rbyte <= maxByte){
            rlen = i+1; //return할 문자열 갯수                                         
        }
     }


    if(rbyte > maxByte){
        
        str2 = str.substr(0,rlen);//문자열 자르기                                  
        obj.value = str2;
        fnChkByte(obj, maxByte);
        
    } else {
        $('.byteInfo_limit').text(rbyte);
     }
}
    
    
    
//첨부파일 미리보기 
function previewImg(obj){
   var imgVal = $(obj).siblings('.mb_file_name').find('i').text();
   if( imgVal == '파일없음' ){
       msgAlert('error','첨부파일을 먼저 등록해주세요.', function(){alertOff()});
       return
   }
    
   $(obj).siblings( $('.v_ad_preview') ).addClass('active');
    
    $('.v_ad_preview .btn_close').on('click', function(){
        $('.v_ad_preview').removeClass('active');
    });
};



//첨부파일의 이미지경로 등록하기
function imgReadUrl(input, obj) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            obj.siblings().find("img").attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$('.user_info_file').find('input[type=file]').each(function(idx,obj){
    $(obj).change(function() {
        imgReadUrl(this, $(this));
        if(window.FileReader){ // modern browser 
        var filename = $(this)[0].files[0].name; 
        } else { // old IE 
            var filename = $(this).val().split('/').pop().split('\\').pop(); // 파일명만 추출 
        } 
        // 추출한 파일명 삽입 
        $(this).siblings('.mb_file_name').find('i').text(filename); 
    });

});


// 숫자만 입력가능하게 처리하는 부분 
function onlyNumber(obj){
    $(obj).keyup(function (event) {
        regexp = /[^0-9]/gi;
        value = $(this).val();
        $(this).val(value.replace(regexp, ''));

    });
}




// 스크롤효과
$(function() {
    fn_scroll_plugin();
});

// jQuery Scroll Plugin 적용
function fn_scroll_plugin() {
    $(".scrollbar").mCustomScrollbar({
        theme : "inset-2", // 테마 적용
        mouseWheelPixels : 100, // 마우스휠 속도
        scrollInertia : 900, // 부드러운 스크롤 효과 적용
        horizontalScroll:true,
        setLeft: '0px'
        
        
    });
}


$(function(){
    $('#fr_date').datepicker({ 
        changeMonth: true, 
        changeYear: true, 
        dateFormat: "yy-mm-dd", 
        showButtonPanel: true, 
        yearRange: "c-99:c+99",
        minDate: 0,
        onClose: function( selectedDate ) {    
                    $("#to_date").datepicker( "option", "minDate", selectedDate );
                 }   
    });
    
    $('#to_date').datepicker({ 
        changeMonth: true, 
        changeYear: true, 
        dateFormat: "yy-mm-dd", 
        showButtonPanel: true, 
        yearRange: "c-99:c+99",
        minDate: 0,
        onClose: function( selectedDate ) {
                    $("#fr_date").datepicker( "option", "maxDate", selectedDate );
                 }                
    });
});

$(function(){
    $('#fr_date_2').datepicker({ 
        changeMonth: true, 
        changeYear: true, 
        dateFormat: "yy-mm-dd", 
        showButtonPanel: true, 
        yearRange: "c-99:c+99",
        minDate: 0,
        onClose: function( selectedDate ) {    
                    $("#to_date_2").datepicker( "option", "minDate", selectedDate );
                 }   
    });
    
    $('#to_date_2').datepicker({ 
        changeMonth: true, 
        changeYear: true, 
        dateFormat: "yy-mm-dd", 
        showButtonPanel: true, 
        yearRange: "c-99:c+99",
        minDate: 0,
        onClose: function( selectedDate ) {
                    $("#fr_date_2").datepicker( "option", "maxDate", selectedDate );
                 }                
    });
    
});


//최종 값 검증
function adInfoForm(obj){
    var valCheck = false;

    if( obj == 'img' ){ //이미지 광고인 경우
        $('.user_info_img').find('input').each(function(idx,item){
            
            if( $(item).val() == '' ){
                                
                if( $('#mb_ad_img_pc').val() == '' && $("#modi").val() != "Y" ){
                    msgAlert('error','배너이미지(PC)를 등록해주세요',function(){alertOff();});
                    $('label[for="mb_ad_img_pc"]').focus();
                    valCheck =  false;
                    return false;
                    
                    
                } else if ( $('#mb_ad_img_mobile').val() == '' && $("#modi").val() != "Y" ){
                    msgAlert('error','배너이미지(MOBILE)을 등록해주세요',function(){alertOff();}   );
                    $('label[for="mb_ad_img_mobile"]').focus();
                    valCheck =  false;
                    return false;
                }
                
                
                if( $('#mb_url').val() == '' ){
                   valCheck =  true;
                   return true;     
                }
                
                valCheck =  false;
                return false;
                
            }
        });
        
        
        var num = 4;
        if( $("#modi").val() == "Y" ) {
            num = 3;
        }
        
        var form = $('form')[num];
        var data = new FormData(form);



         $.ajax({
             url:'v_ad_make_image_back.php',
             enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
             type:'POST',
             data: data,
             async: false,
             success:function(result){
                 if( result == "false" ) {
                     msgAlert('error','에러가 발생했습니다.',function(){alertOff();});
                     valCheck =  false;
                 } else {
                     msgAlert('success','광고 등록이 완료 되었습니다.',function(){alertOff(); window.location.href = "v_ad_center.php";});
                     valCheck =  false;
                 }
            }
         });
        
    } else if ( obj == 'txt' ){ //텍스트 광고인 경우
        $('.user_info_txt').find('input').each(function(idx,item){
            
            if( $(item).val() == '' ){
                $(item).focus();

                if( $('#mb_url').val() == '' ){
                   valCheck =  true;
                   return true;     
                }
                
                valCheck =  false;
                return false;
                
            }
        });
        
        var num = 5;
        if( $("#modi").val() == "Y" ) {
            num = 3;
        }
        
        var form = $('form')[num];
        var data = new FormData(form);



         $.ajax({
             url:'v_ad_make_text_back.php',
             enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
             type:'POST',
             data: data,
             async: false,
             success:function(result){
                 if( result == "false" ) {
                     msgAlert('error','에러가 발생했습니다.',function(){alertOff();});
                     valCheck =  false;
                 } else {
                     msgAlert('success','광고 등록이 완료 되었습니다.',function(){alertOff(); window.location.href = "v_ad_center.php";});
                     valCheck =  false;
                 }
            }
         });
    }
    
    return valCheck;
}







// 배너 제작 문의 SBUMIT 함수
function bannerMake(obj) {
    $.ajax({
        url:'bannerMake_back.php',
        type:'POST',
        data: obj.serialize(),
        async: false,
        success:function(result){
            if( result == "false" ) {
                msgAlert('error','에러가 발생했습니다.',function(){alertOff();});
                valCheck =  false;
            } else {
                msgAlert('success','접수가 완료 되었습니다.',function(){alertOff();});
                $("#success_aTAG").attr("href","v_ad_center.php");
                $(".banner_pop_wrap ").removeClass("on");
                valCheck =  false;
            }
       }
    });
    
    return false;
}

</script>



<?php
include_once ('../shop/shop.tail.php');
?>