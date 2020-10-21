<?php
    $cssCheck = false; // _common.php에 인클루드 되는 CSS 적용 안 되게 하기 위한 변수
    include_once ('./_common.php');
    include_once ('../dbConn.php');
    
    $pageCheck;
    if( $_GET["check"] == "true" ) { // 참이면 정보 수정 가능한 리스트
        $pageCheck = "true";
    } else if( $_GET["check"] == "false" ) { // 참이면 정보 수정 불가능한 리스트
        $pageCheck = "false";
    } else {
        $pageCheck = "true";
    }
    
?>


<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>회원가입권 목록</title>
    <link rel="stylesheet" href="css/jquery.bxslider.min.css">
    <link rel="stylesheet" href="css/member_list.css">
    <link rel="stylesheet" href="css/font.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.bxslider.min.js"></script>
    <link rel="shortcut icon" href="/img/vmp_logo.ico" />
</head>

<script>
    $(document).ready(function(){
        tabToggle();

        //탭버튼으로 수정가능,불가능 리스트 열기
        function tabToggle(){
            $('.tab_box').on('click',function(ev){
                
                if($(ev.target).hasClass('possible_btn')){
                    
                    $(ev.target).removeClass('off'); 
                    $(ev.target).siblings().addClass('off');
                    $('.impossible_list').css('display','none');
                    $('.possible_list').css('display','block');
                    $('.bx-pager').css('bottom','5%')

                    $("#check").val("1");
                } else if($(ev.target).hasClass('impossible_btn')){

                    $(ev.target).removeClass('off');
                    $(ev.target).siblings().addClass('off');
                    $('.possible_list').css('display','none');
                    $('.impossible_list').css('display','block');
                    $('.bx-pager').css('bottom','15%')
                    
                    $("#check").val("2");
                } else {
                    return;
                }
            });
        }


        var PossibleSlider;
        var ImpossibleSlider;
        var resizeEvnt;
        
        $(window).resize(function(){
            clearTimeout(resizeEvnt);
            resizeEvnt = setTimeout(slider,150);
        });
        
        //bxSlider 실행하기
        slider();
        function slider(){
//            var sliderName = "";
//            
//            if( name == "1" ) {
//                sliderName = ".slider";
//            } else if( name == "2" ) {
//                sliderName = ".slider_2";
//            } else {
//                sliderName = ".slider";
//            }
//

            var windowWidth = $(window).width();
            
            if(windowWidth <= 480){    
                PossibleSlider = $('.slider').bxSlider();
                ImpossibleSlider = $('.slider_2').bxSlider();

            } else {

                    PossibleSlider.destroySlider();
                    ImpossibleSlider.destroySlider();

            }
        }
    
        
    
        
        
        
    //window.onload 끝    
    });
    
    function modify(mb_id){ // 정보수정하기 버튼 클릭 이벤트로 동작하는 함수 정의부
        var url = 'register_form.php?mb_id=' + mb_id;
        window.open(url, "PopupWin", "width=500,height=600");
    }
    
</script>
<body>
<input type="hidden" id="check" value="">
<div class="wrap">
    <div class="container">
        <div class="tab_box"> 
            <a href="#none" class="possible_btn tab_btn">정보 수정 가능한 ID</a>
            <a href="#none" class="impossible_btn tab_btn off">정보 수정 불가능한 ID</a>
        </div>
        <ul class="list_wrap">
            <li class="possible_list">
                <div class="info_box clearfix">
                    <i><img src="img/vmp_info.png" alt="정보 아이콘"></i>
                    <p>가입 후, 한 달 이내 정보 수정이 가능합니다. / 수정 마감 임박순으로 정렬 됨</p>
                </div>  
                <div class="slider_wrap">
                    <ul class="slider clearfix">
                        
<?php
    $trueRe = mysql_query("select * from membershipListTBL as a inner join g5_member as b on a.mb_id = b.mb_id inner join genealogy as c on b.mb_id = c.mb_id where a.constructor_id = '{$member["mb_id"]}' and a.modi_datetime is null and date_add(a.datetime, interval +4 month) >= now()");
    $falseRe = mysql_query("select * from membershipListTBL as a inner join g5_member as b on a.mb_id = b.mb_id inner join genealogy as c on b.mb_id = c.mb_id where constructor_id = '{$member["mb_id"]}' and (modi_datetime is not null or date_add(datetime, interval +4 month) < now())");
    
    while( $trueRow = mysql_fetch_array($trueRe) ) {
?>
        <li>
            <ul class="member_info">
                <li>
                    <em>이름(ID)</em>
                    <strong><?=$trueRow["mb_name"]?><b>(</b><b id="id_num"><?=$trueRow["mb_id"]?></b><b>)</b></strong>
                </li>
                <li>
                    <em>핸드폰 번호</em>
                    <strong><?=$trueRow["mb_hp"]?></strong>
                </li>
                <li class="recomm_box">
                    <em>추천인 이름/추천인 회원 번호</em>
                    <strong><?=$trueRow["recommenderName"]?></strong><i>/</i><strong><?=$trueRow["recommenderID"]?></strong>
                </li>
                <li class="sponsor_box">
                    <em>후원인 이름/후원인 회원 번호</em>
                    <strong><?=$trueRow["sponsorName"]?></strong><i>/</i><strong><?=$trueRow["sponsorID"]?></strong>
                </li>
                <li>
                    <label for="sponsor_team">후원인 팀배치</label>
                    <input type="radio" name="sponsor_team" id="1team" value="1" <?php if($trueRow["sponsorTeam"] == "1") echo "style='background-color: #3db8db'"; ?>>
                    <label for="1team">1팀</label>
                    <input type="radio" id="testteset1" name="sponsor_team" id="2team" value="2" <?php if($trueRow["sponsorTeam"] == "2") echo "style='background-color: #3db8db'"; ?>>
                    <label for="2team">2팀</label>
                </li>
                <li>
                    <em>가입일</em>
                    <strong><?=$trueRow["datetime"]?></strong>
                </li>
                <li>
                    <a href="#none" class="modify_btn" onclick="modify('<?=$trueRow["mb_id"]?>')">정보 수정하기</a>
                </li>
            </ul>
        </li>         
<?php
    }
?>
   
                    </ul>
                </div>
            </li>
            
            
            
            <li class="impossible_list">
                <div class="info_box clearfix">
                    <i><img src="img/vmp_info.png" alt="정보 아이콘"></i>
                    <p>가입 후, 한 달이 경과해서 정보 수정이 불가능합니다.</p>
                </div>  
                <div class="slider_wrap">
                    <ul class="slider_2">
                        
<?php
    while( $falseRow = mysql_fetch_array($falseRe) ) {
?>
        <li>
            <ul class="member_info">
                <li>
                    <em>이름(ID)</em>
                    <strong><?=$falseRow["mb_name"]?><b>(</b><b class="id_num"><?=$falseRow["mb_id"]?></b><b>)</b></strong>
                </li>
                <li>
                    <em>핸드폰 번호</em>
                    <strong><?=$falseRow["mb_hp"]?></strong>
                </li>
                <li class="recomm_box">
                    <em>추천인 이름/추천인 회원 번호</em>
                    <strong><?=$falseRow["recommenderName"]?></strong><i>/</i><strong><?=$falseRow["recommenderID"]?></strong>
                </li>
                <li class="sponsor_box">
                    <em>후원인 이름/후원인 회원 번호</em>
                    <strong><?=$falseRow["sponsorName"]?></strong><i>/</i><strong><?=$falseRow["sponsorID"]?></strong>
                </li>
                <li>
                    <label for="sponsor_team">후원인 팀배치</label>
                    <input type="radio" name="sponsor_team" id="1team" disabled <?php if($falseRow["sponsorTeam"] == "1") echo "style='background-color: #ccc'"; ?>>
                    <label for="1team">1팀</label>
                    <input type="radio" name="sponsor_team" id="2team" disabled <?php if($falseRow["sponsorTeam"] == "2") echo "style='background-color: #ccc'"; ?>>
                    <label for="2team">2팀</label>
                </li>
                <li>
                    <em>가입일</em>
                    <strong><?=$falseRow["datetime"]?></strong>
                </li>
                <li>
                    <a href="#none" class="modify_btn">정보 수정불가능</a>
                </li>
            </ul>
        </li>           
<?php
    }
?>
                    </ul>
                </div>
            </li>
        </ul>
        <div class="modify_box" style="display: none"></div>
    </div>
</div>
</body>
</html>