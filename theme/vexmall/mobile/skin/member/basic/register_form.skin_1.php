
    <script  src="http://code.jquery.com/jquery-latest.min.js"></script>

    
    
    <script>
    $(document).ready(function(){
//        v로드 회원가입 수정 
       /* 회원가입시 추천인 이름과 ID가 존재하는 지 검증 로직  */
            $('#reg_mb_recommender_no').blur(function() {
                
                var data = "reg_mb_recommender=" + $('#reg_mb_recommender').val() + "&"
                + "reg_mb_recommender_no=" + $('#reg_mb_recommender_no').val();
                
                 $.ajax({
                     url:'/bbs/register_value_checking.php',
                     type:'get',
                     data: encodeURI(data),
                     success:function(result){
                         if( result == "false" ) {
                             alert("입력하신 추천인 정보가 잘 못 됐습니다.");
                             $("#reg_mb_recommender_no").val("");
                             $("#reg_mb_recommender").val("");
                             $("#reg_mb_recommender").focus();
                         }
               		}
                });
           });

           /* 회원가입시 후원인 이름과 ID가 존재하는 지 검증 로직  */
//            $('#reg_mb_sponsor_no').blur(function() {
//                var data = "reg_mb_sponsor=" + $('#reg_mb_sponsor').val() + "&"
//                + "reg_mb_sponsor_no=" + $('#reg_mb_sponsor_no').val();
//             
//                 $.ajax({
//                     url:'/bbs/register_sponsorValue_checking.php',
//                     type:'get',
//                     data: data,
//                     success:function(result){
//                         if( result == "false" ) {
//                             alert("입력하신 후원인 정보가 잘 못 됐습니다.");
//                             $("#reg_mb_sponsor_no").val("");
//                             $("#reg_mb_sponsor").val("");
//                             $("#reg_mb_sponsor").focus();
//                         }
//               }
//                 });
//           });           
    });
    </script>

<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<div>
    <SCRIPT LANGUAGE="JavaScript">

    function onlyNumber(obj) {
        $(obj).keyup(function(){
             $(this).val($(this).val().replace(/[^0-9]/g,""));
        }); 
    }
   /* 후원인 팀배치 라디오버튼 */
    function Check(x,y) {
        for(i=1;i<=2;i++) { // 라디오 버튼의 갯수
            z = "option" + i ; // name 부분
            document.getElementById(z).src = '/theme/everyday/mobile/skin/member/basic/img/radio.png';
        }
       
        document.getElementById(x).src = '/theme/everyday/mobile/skin/member/basic/img/radio_checked.png';

        if( x == "option1" ) {
           document.getElementById("radiobtn").value = '1';
        } else if( x == "option2" ) { 
           document.getElementById("radiobtn").value = '2';
        }

        $(document).ready(function(){
            
            var data = "reg_mb_sponsor=" + $('#reg_mb_sponsor').val() + "&"
            + "reg_mb_sponsor_no=" + $('#reg_mb_sponsor_no').val() + "&"
            + "reg_mb_sponser_team=" + $('#radiobtn').val();
            $.ajax({
                url:'/bbs/register_sponsorTeam_checking.php',
                type:'get',
                data: data,
                success:function(result){
                    if( result == "false" ) {
                        alert("선택하신 후원인 팀으로 배치될 수 없습니다.\n팀 선택을 다시 해 주시기 바랍니다.");
                    }
                           }
            });
         
        });
    }
 
    function Check2(x,y) {
        for(i=4;i<=6;i++) { // 라디오 버튼의 갯수
            z = "option" + i ; // name 부분
                document.all[z].src = "/theme/everyday/mobile/skin/member/basic/img/radio.png" ; // 라디오 버튼 off
        }
            document.all[x].src = "/theme/everyday/mobile/skin/member/basic/img/radio_checked.png"; // 라디오 버튼 on

        if( x == "option5" ) { // CU 계정 신청 아이콘
           $('#hide_area').css('display', 'none');
           document.getElementById("radiobtn").value = '3'; // 무료 계정임을 뜻 함
           document.getElementById("reg_mb_sponsor").value = '없습니다';
           document.getElementById("reg_mb_sponsor_no").value = '99999999';
                document.getElementById("accountType").value = 'CU';

        } else if( x == "option4" ) { // VM 계정 신청 아이콘
           $('#hide_area').css('display', 'block');
           document.getElementById("radiobtn").value = '0';
           document.getElementById("reg_mb_sponsor").value = '';
           document.getElementById("reg_mb_sponsor_no").value = '';
                document.getElementById("accountType").value = 'VM';
        } else if( x == "option6" ) { // VD 계정 신청 아이콘
           $('#hide_area').css('display', 'none');
           alert("VD계정은 벤더사 가입 계정입니다.\nVM으로 가입을 희망하시는 분은 CU로 가입 후 VM으로 전환하실 수 있습니다.\n\nVM : 네트워크 사업자\nVD : 판매자\nCU : 무료회원");
           document.getElementById("radiobtn").value = '4';
           document.getElementById("reg_mb_sponsor").value = '없습니다';
           document.getElementById("reg_mb_sponsor_no").value = '99999999';
                document.getElementById("accountType").value = 'VD';
        }

        for(i=1;i<=2;i++) { // 라디오 버튼의 갯수
            z = "option" + i ; // name 부분
            document.getElementById(z).src = '/theme/everyday/mobile/skin/member/basic/img/radio.png';
        }
       }
    //
    </script>
    <script src="<?php echo G5_JS_URL ?>/jquery.register_form.js"></script>
    <?php if($config['cf_cert_use'] && ($config['cf_cert_ipin'] || $config['cf_cert_hp'])) { ?>
    <script src="<?php echo G5_JS_URL ?>/certify.js?v=<?php echo G5_JS_VER; ?>"></script>
    <?php } ?>

<form name="fregisterform" id="fregisterform" action="/bbs/register_form_update_app.php" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="url" value="<?php echo $urlencode ?>">
<input type="hidden" name="agree" value="<?php echo $agree ?>">
<input type="hidden" name="agree2" value="<?php echo $agree2 ?>">
<input type="hidden" name="cert_type" value="<?php echo $member['mb_certify']; ?>">
<input type="hidden" name="cert_no" value="">
<?php if (isset($member['mb_sex'])) { ?><input type="hidden" name="mb_sex" value="<?php echo $member['mb_sex'] ?>"><?php } ?>
<?php if (isset($member['mb_nick_date']) && $member['mb_nick_date'] > date("Y-m-d", G5_SERVER_TIME - ($config['cf_nick_modify'] * 86400))) { // 닉네임수정일이 지나지 않았다면 ?>
<input type="hidden" name="mb_nick_default" value="<?php echo get_text($member['mb_nick']) ?>">
<input type="hidden" name="mb_nick" value="<?php echo get_text($member['mb_nick']) ?>">
<?php } ?>

    <div class="form_01">
        <h2 style="background:#1cb934;">비밀번호 입력</h2>
        <li>
            <label for="reg_mb_id" class="sound_only">아이디<strong>필수</strong></label>
            <input type="hidden" name="mb_id" value="<?php if($member['mb_id'] == "") { echo "temp";} else {echo $member['mb_id'];} ?>" id="reg_mb_id" class="frm_input full_input <?php echo $required ?> <?php echo $readonly ?>" minlength="3" maxlength="20" <?php echo $required ?> <?php echo $readonly ?> placeholder="아이디">
            <span id="msg_mb_id"></span>
            <span class="frm_info">* 영문자, 숫자, _ 만 입력 가능. 최소 3자이상 입력하세요.</span>
        </li>
        <li>
            <label for="reg_mb_password" class="sound_only">비밀번호<strong>필수</strong></label>
            <input type="password" name="mb_password" id="reg_mb_password" class="frm_input full_input <?php echo $required ?>" minlength="3" maxlength="20" <?php echo $required ?> placeholder="비밀번호">
        </li>
        <li>
            <label for="reg_mb_password_re" class="sound_only">비밀번호 확인<strong>필수</strong></label>
            <input type="password" name="mb_password_re" id="reg_mb_password_re" class="frm_input full_input <?php echo $required ?>" minlength="3" maxlength="20" <?php echo $required ?>  placeholder="비밀번호확인">
        </li>
        <div style="display:none">
            <h2 style="background:#1cb934;">계정종류</h2>
            <!-- VD계정신청 --> <img src="/theme/everyday/mobile/skin/member/basic/img/radio.png" name="option6" onclick="Check2(this.name,'')" style="display:none">
            CU계정신청 <img id='cu_ck' src="/theme/everyday/mobile/skin/member/basic/img/radio.png" name="option5" onclick="Check2(this.name,'')">
            <!-- VM계정신청  --><img id='vm_ck' src="/theme/everyday/mobile/skin/member/basic/img/radio.png" name="option4" onclick="Check2(this.name,'')" style="display:none">
            <input type="hidden" id="radiobtn2" name="radiobtn2" value="0">
            <br><br>
        </div>

        
        
        
        
        <script>
        // 추천인 검색 관련 스크립트
function searchClick() {
           if( $("#nameNick option:selected").val() == "아이디" ) {
                $('#search_info_tbl > tbody:last > tr').remove();
                $(".search_info").css("display", "none");
               var data = "mb_id=" + $('#searchInput').val();

               $.ajax({
                   url:'/myOffice/memberIDSearch2.php',
                   type:'POST',
                   data: encodeURI(data),
                   async: false,
                   success:function(result){
                       if( result == "false" ) {
                           alert("검색 결과가 없습니다.");
                           $("#reg_mb_recommender").val( "" );
                           $("#reg_mb_recommender_no").val( "" );
                           $("#searchInput").val("");
                       } else {
                           $("#reg_mb_recommender").val( result );
                           $("#reg_mb_recommender_no").val( $('#searchInput').val() );
                       }
                   }
               });

               recommCheck();
           } else if( $("#nameNick option:selected").val() == "이름" ) {

               var data = "searchName=" + $('#searchInput').val() + "&loginID=" + "admin" + "&check=회원가입";

               $.ajax({
                   url:'/myOffice/memberNameSearch.php',
                   type:'POST',
                   data: encodeURI(data),
                   async: false,
                   success:function(result){

                       var json = JSON.parse(result);

                        var submenu = $(".search_info");
                        if( submenu.is(":visible") ){
                            $('#search_info_tbl > tbody:last > tr').remove();
                        }

                       if( json.length == 0 ) {
                            $("#reg_mb_recommender").val( "" );
                           $("#reg_mb_recommender_no").val( "" );
                           $("#searchInput").val("");
                           alert("검색 결과가 없습니다."); exit();
                       }

                       var submenu = $(".search_info");
                       submenu.show();

                       for( var i=0 ; i<json.length ; i++ ) {
                           if( json[i][3] == "CU" || json[i][3] == "VD" )
                               continue;
                           $('#search_info_tbl > tbody:last').append('<tr id=\"'+json[i][1]+'\" onclick=\"searchSelect(this.id);\" style=\"cursor: pointer;\" onmouseover=\"this.style.background=\'#EAEAEA\'" onmouseout=\"this.style.background=\'white\'\"><td>'+json[i][0]+'</td><td>'+json[i][1]+'</td></a></tr>');
                       }
                   }
               });
               recommCheck();
           }
       }
       
        function Enter_Check(){ // 회원검색에서 엔터 키 입력하면 검색 진행하게 하는 함수
             if(event.keyCode == 13){
                  searchClick();
             }
         }
         
        function searchSelect(id) {
            var data = "mb_id=" + id;
            $.ajax({
              url:'/myOffice/memberIDSearch2.php',
              type:'POST',
              data: encodeURI(data),
              async: false,
              success:function(result){
                  if( result == "false" ) {
                      alert("검색할 수 없는 아이디입니다.");
                  } else {
                      $('#search_info_tbl > tbody:last > tr').remove();
                      $(".search_info").css("display", "none");
                      $("#reg_mb_recommender").val( result );
                      $("#reg_mb_recommender_no").val( id );
                      recommCheck();
                  }
              }
          });
       }
        
       function recommCheck() {
                      var data = "reg_mb_recommender=" + $('#reg_mb_recommender').val() + "&"
                + "reg_mb_recommender_no=" + $('#reg_mb_recommender_no').val();
                
                 $.ajax({
                     url:'/bbs/register_value_checking.php',
                     type:'get',
                     data: encodeURI(data),
                     async: false,
                     success:function(result){
                         if( result == "false" ) {
                             alert("입력하신 추천인 정보가 잘 못 됐습니다.");
                             $("#reg_mb_recommender_no").val("");
                             $("#reg_mb_recommender").val("");
                             $("#reg_mb_recommender").focus();
                         }
               		}
                });
       } 
        
        </script>
        
        
        
        
        
        
        
        
        <div style="display:none">
            <h2 style="background:#f2a80b;">추천인 정보입력</h2>
            <li>
            <div id="select_box">
                <select id="nameNick" style="width:24%;">
                    <option value="이름" selected="selected">이름</option>
                    <option value="아이디" >회원아이디</option>
                </select>
                <input type="text" style="height: 40px; width: 66.2%" placeholder="회원검색" id="searchInput" onkeydown="//Enter_Check();"/>
                <button type="button" style="height: 40px; width: 8%;" onclick="//searchClick();" id="searchclick"><i class="fa fa-search" aria-hidden="true"></i></button>
            </div>   
                <div class="search_info" style="display: none;">
                <table id="search_info_tbl" style="border: 1px solid #bbb; font-size: 14px; width: 100%; text-align: center; line-height: 30px;">
                    <thead>
                        <tr>
                            <th>이름</th>
                            <th>아이디</th>
<!--                            <th>후원인</th>
                            <th>직급</th>
                            <th>리뉴얼</th>-->
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </li>
         <li>
            <label for="reg_mb_recommender" class="sound_only">추천인<strong>필수</strong></label>
            <input type="text" name="reg_mb_recommender" id="reg_mb_recommender" class="frm_input full_input <?php echo $required ?>" minlength="2" maxlength="20" <?php echo $required ?>  placeholder="추천인">
        </li>
        <li>
            <label for="reg_mb_recommender_no" class="sound_only">추천인 회원번호<strong>필수</strong></label>
            <input onkeydown="onlyNumber(this)" maxlength="8" type="text" name="reg_mb_recommender_no" id="reg_mb_recommender_no" class="frm_input full_input <?php echo $required ?>" minlength="3" maxlength="20" <?php echo $required ?>  placeholder="추천인 회원번호">
            
        </li> 
        <div id="hide_area">
        <li style="display: none;">
            <label for="reg_mb_sponsor" class="sound_only">후원인<strong>필수</strong></label>
            <input type="text" name="reg_mb_sponsor" id="reg_mb_sponsor" class="frm_input full_input <?php echo $required ?>" minlength="3" maxlength="20" <?php echo $required ?>  placeholder="후원인" value="없습니다">
        </li>
        <li style="display: none;">
            <label for="reg_mb_sponsor_no" class="sound_only">후원인 회원번호<strong>필수</strong></label>
            <input onkeydown="onlyNumber(this)" maxlength="8" type="text" name="reg_mb_sponsor_no" id="reg_mb_sponsor_no" class="frm_input full_input <?php echo $required ?>" minlength="3" maxlength="8" <?php echo $required ?>  placeholder="후원인 회원번호" value="99999999">
        </li>
  <!-- <h2 style="background:#f2a80b;">후원인 팀배치</h2>
        1팀 <img src="/theme/everyday/mobile/skin/member/basic/img/radio.png" name="option1" id="option1" onclick="Check(this.id,'')">
        2팀 <img src="/theme/everyday/mobile/skin/member/basic/img/radio.png" name="option2" id="option2" onclick="Check(this.id,'')">
        3팀 <img src="/theme/everyday/mobile/skin/member/basic/img/radio.png" name="option3" onclick="Check(this.name,'')"> 
        -->
        <input type="hidden" id="radiobtn" name="radiobtn" value="0">
        <input type="hidden" id="accountType" name="accountType" value="VM">
        
        </div>
        </div>
    </div>

    <div class="form_01">
        
        <h2 style="background:#2399f1;">개인정보 입력</h2>
        <li class="rgs_name_li">
            <?php
            if($config['cf_cert_use']) {
                if($config['cf_cert_ipin'])
                    echo '<button type="button" id="win_ipin_cert" class="btn_frmline btn">아이핀 본인확인</button>'.PHP_EOL;
                if($config['cf_cert_hp'] && $config['cf_cert_hp'] != 'lg')
                    echo '<button type="button" id="win_hp_cert" class="btn_frmline btn">휴대폰 본인확인</button>'.PHP_EOL;

                echo '<noscript>본인확인을 위해서는 자바스크립트 사용이 가능해야합니다.</noscript>'.PHP_EOL;
            }
            ?>
            <?php
            if ($config['cf_cert_use'] && $member['mb_certify']) {
                if($member['mb_certify'] == 'ipin')
                    $mb_cert = '아이핀';
                else
                    $mb_cert = '휴대폰';
            ?>
            <?php if ($config['cf_cert_use']) { ?>
            <span class="frm_info">아이핀 본인확인 후에는 이름이 자동 입력되고 휴대폰 본인확인 후에는 이름과 휴대폰번호가 자동 입력되어 수동으로 입력할수 없게 됩니다.</span>
            <?php } ?>
            <div id="msg_certify">
                <strong><?php echo $mb_cert; ?> 본인확인</strong><?php if ($member['mb_adult']) { ?> 및 <strong>성인인증</strong><?php } ?> 완료
            </div>
            <?php } ?>
            <label for="reg_mb_name" class="sound_only">이름<strong>필수</strong></label>
            <input type="text" id="reg_mb_name" name="mb_name" value="<?php echo get_text($member['mb_name']) ?>" <?php echo $required ?> <?php echo $readonly; ?> class="frm_input full_input <?php echo $required ?> <?php echo $readonly ?>" placeholder="이름">
        </li>
        <?php if ($req_nick) { ?>
        <li style="display:none;">
            <label for="reg_mb_nick" class="sound_only">닉네임<strong>필수</strong></label>
            
            <span class="frm_info">
                공백없이 한글,영문,숫자만 입력 가능 (한글2자, 영문4자 이상)<br>
                닉네임을 바꾸시면 앞으로 <?php echo (int)$config['cf_nick_modify'] ?>일 이내에는 변경 할 수 없습니다.
            </span>
            <input type="hidden" name="mb_nick_default" value="<?php echo isset($member['mb_nick'])?get_text($member['mb_nick']):'닉네임'; ?>">
            <input type="hidden" name="mb_nick" value="<?php echo isset($member['mb_nick'])?get_text($member['mb_nick']):'닉네임'; ?>" id="reg_mb_nick" required class="frm_input full_input required nospace" maxlength="20" placeholder="닉네임">
            <span id="msg_mb_nick"></span>
            
        </li>
        <?php } ?>



        <?php if ($config['cf_use_homepage']) { ?>
        <li>
            <label for="reg_mb_homepage" class="sound_only">홈페이지<?php if ($config['cf_req_homepage']){ ?><strong>필수</strong><?php } ?></label>
            <input type="url" name="mb_homepage" value="<?php echo get_text($member['mb_homepage']) ?>" id="reg_mb_homepage" class="frm_input full_input <?php echo $config['cf_req_homepage']?"required":""; ?>" maxlength="255" <?php echo $config['cf_req_homepage']?"required":""; ?> placeholder="홈페이지">
        </li>
        <?php } ?>

        <?php if ($config['cf_use_tel']) { ?>
        <li>
            <label for="reg_mb_tel" class="sound_only">전화번호<?php if ($config['cf_req_tel']) { ?><strong>필수</strong><?php } ?></label>
            <input type="text" name="mb_tel" value="<?php echo get_text($member['mb_tel']) ?>" id="reg_mb_tel" class="frm_input full_input <?php echo $config['cf_req_tel']?"required":""; ?>" maxlength="20" <?php echo $config['cf_req_tel']?"required":""; ?> placeholder="전화번호">
        </li>
        <?php } ?>

        <?php if ($config['cf_use_hp'] || $config['cf_cert_hp']) {  ?>
        <li class="reg_hp_box">
            <input type="hidden" id="reg_hp_smsnumber" name="reg_hp_smsnumber" value=""/>
            <input type="hidden" id="confirmedNum" name="confirmedNum" value=""/>
            <p class="reg_hp_info">휴대전화 번호 입력후 인증문자 발송 버튼을 누르세요.</br>4자리 인증번호 문자를 보내드리겠습니다. </p>
            <label for="reg_mb_hp" class="sound_only">휴대폰번호<?php if ($config['cf_req_hp']) { ?><strong>필수</strong><?php } ?></label>
            <input onkeydown="onlyNumber(this)" type="text" name="mb_hp" value="<?php echo get_text($member['mb_hp']) ?>" id="reg_mb_hp" <?php echo ($config['cf_req_hp'])?"required":""; ?> class="frm_input full_input required <?php echo ($config['cf_req_hp'])?"required":""; ?>" minlength="11" maxlength="11" title="휴대전화 번호" placeholder="휴대전화 번호를 숫자만 입력" required readonly="false">
            <a href="#none" class="reg_hp_btn reg_hp_reconf_btn" onclick='reconfirmHp()'>번호 변경하기</a>
            <!--<a href="#none" class="reg_hp_btn reg_hp_confirm_btn">인증문자 발송</a>-->
            <!--<div class="reg_hp_confirm_box"></div>-->
            <?php if ($config['cf_cert_use'] && $config['cf_cert_hp']) {?>
            <input type="hidden" name="old_mb_hp" value="<?php echo get_text($member['mb_hp']) ?>">
            <?php } ?>
            <!--2차 번호인증-->
            <div class="auth_number">
                <div class="auth_number_content">
                    <input type="hidden" id="hp_smsnumber" name="hp_smsnumber" value=""/>
                    <input type="hidden" id="confirmedNum" name="confirmedNum" value=""/>
                    <p class="auth_info"><i class="auth_icon"></i>보다 안전한 이용을 위해 두 번의 번호인증을 거친 후 휴대폰 번호를 변경하실 수 있습니다.</p>
                    <div class="auth_process">
                        <ul>
                            <li class="on">
                                <span>1</span>
                                <em>1차 인증</em>
                            </li>
                            <li>
                                <span>2</span>
                                <em>2차 인증</em>
                            </li>  
                            <li>
                                <span>3</span>
                                <em>변경완료</em>
                            </li> 
                        </ul>
                    </div>
                    <div class="auth_step_1">
                        <label for="origin_mb_hp">기존 휴대폰번호</label>
                        <input class="" id="origin_mb_hp" type="text" placeholder="휴대폰번호" name="origin_mb_hp" autocomplete="off" value="<?=$member['mb_hp']?>" readonly>
                        <a href="#none" class="hp_btn hp_confirm_btn auth_btn step_1_btn" onclick="authNumber(this)">인증번호발송</a>
                        <p class="hp_info">인증문자 발송 버튼을 눌러주세요.</br>4자리 인증번호를 보내드리겠습니다.</p>
                        <div class="hp_confirm_box"></div>
                    </div>
                    <div class="auth_step_2">
                        <label for="mb_hp">변경하실 휴대폰번호</label>
                        <input class="" id="mb_hp" type="text" placeholder="휴대폰번호 입력" name="mb_hp" autocomplete="off" value="">
                        <a href="#none" class="auth_btn step_2_btn" onclick="authNumber(this)">인증번호발송</a>
                        <a href="#none" class="hp_btn hp_reconf_btn auth_btn" onclick='reconfirmHp()'>번호 변경하기</a>
                        <p class="hp_info">변경하실 휴대폰번호를 입력하신 후</br>인증문자 발송 버튼을 눌러주세요.</br>4자리 인증번호를 보내드리겠습니다.</p>
                        <div class="hp_confirm_box_2"></div>
                    </div>
                    <div class="auth_step_3">
                        <a href="#none" class="auth_btn btn_auth_ok">변경저장</a>
                        <a href="#none" class="auth_btn btn_cancel">취소</a>

                    </div>
                </div>
            </div>
            
        </li>
        <?php } ?>
        <li>
            <label for="reg_mb_email" class="sound_only">E-mail<strong>필수</strong></label>
            
                <?php if ($config['cf_use_email_certify']) {  ?>
                <span class="frm_info">
                    <?php if ($w=='') { echo "E-mail 로 발송된 내용을 확인한 후 인증하셔야 회원가입이 완료됩니다."; }  ?>
                    <?php if ($w=='u') { echo "E-mail 주소를 변경하시면 다시 인증하셔야 합니다."; }  ?>
                </span>
                <?php }  ?>
                <input type="hidden" name="old_email" value="<?php echo $member['mb_email'] ?>">
                <input type="email" name="mb_email" value="<?php echo isset($member['mb_email'])?$member['mb_email']:''; ?>" id="reg_mb_email" required class="frm_input email required" size="50" maxlength="100" placeholder="E-mail">
            
        </li>
        
        <li style="margin-bottom: 20px;">
            <label for="reg_mb_tel" class="sound_only">생년월일<strong>필수</strong></label>
            <input onkeydown="onlyNumber(this)" maxlength="6" type="text" name="mb_birth" id="reg_mb_tel" class="frm_input full_input required" maxlength="20" placeholder="생년월일 (ex. 890803)" value="<?=$member['birth']?>">
        </li>

        <?php if ($config['cf_use_addr']) { ?>
        <li>
            <span class="frm_label">주소<?php if ($config['cf_req_addr']) { ?><strong class="sound_only">필수</strong><?php } ?></span>
            <label for="reg_mb_zip" class="sound_only">우편번호<?php echo $config['cf_req_addr']?'<strong class="sound_only"> 필수</strong>':''; ?></label>
            <input type="text" name="mb_zip" value="<?php echo $member['mb_zip1'].$member['mb_zip2']; ?>" id="reg_mb_zip" <?php echo $config['cf_req_addr']?"required":""; ?> class="frm_input required <?php echo $config['cf_req_addr']?"required":""; ?>" size="5" maxlength="6" placeholder="우편번호">
            <button type="button" class="btn_frmline btn" onclick="win_zip('fregisterform', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소 검색</button><br>
            <label for="reg_mb_addr1" class="sound_only">주소<?php echo $config['cf_req_addr']?'<strong class="sound_only"> 필수</strong>':''; ?></label>
            <input type="text" name="mb_addr1" value="<?php echo get_text($member['mb_addr1']) ?>" id="reg_mb_addr1" <?php echo $config['cf_req_addr']?"required":""; ?> class="frm_input frm_address required <?php echo $config['cf_req_addr']?"required":""; ?>" size="50" placeholder="주소"><br>
            <label for="reg_mb_addr2" class="sound_only">상세주소</label>
            <input type="text" name="mb_addr2" value="<?php echo get_text($member['mb_addr2']) ?>" id="reg_mb_addr2" class="frm_input frm_address required" size="50" placeholder="상세주소">
            <br>
            <!-- <label for="reg_mb_addr3" class="sound_only">참고항목</label> -->
            <input type="hidden" name="mb_addr3" value="<?php echo get_text($member['mb_addr3']) ?>" id="reg_mb_addr3" class="frm_input frm_address" size="50" readonly="readonly" placeholder="참고항목">
            <input type="hidden" name="mb_addr_jibeon" value="<?php echo get_text($member['mb_addr_jibeon']); ?>">
            
        </li>
        <?php } ?>
    </div>

   <div class="form_01">
        <h2 style="background:#1cb934;">사용자 계좌정보</h2>
        <li>
            <label for="bankName" class="sound_only">은행명<strong>필수</strong></label>
            <select name="bankName" id="bankName" class="frm_input full_input <?php echo $required ?>">
                <option value="00" selected="">입금할 은행을 선택하세요.</option>
                <option value="IBK기업은행" <?if ($member['bankName']=="IBK기업은행"){?>selected<?}?>>IBK기업은행</option>
                <option value="KB국민은행" <?if ($member['bankName']=="KB국민은행"){?>selected<?}?>>KB국민은행</option>
                <option value="수협중앙회" <?if ($member['bankName']=="수협중앙회"){?>selected<?}?>>수협중앙회</option>
                <option value="NH농협은행" <?if ($member['bankName']=="NH농협은행"){?>selected<?}?>>NH농협은행</option>
                <option value="우리은행" <?if ($member['bankName']=="우리은행"){?>selected<?}?>>우리은행</option>
                <option value="KEB하나은행" <?if ($member['bankName']=="KEB하나은행"){?>selected<?}?>>KEB하나은행</option>
                <option value="신한은행" <?if ($member['bankName']=="신한은행"){?>selected<?}?>>신한은행</option>
                <option value="KDB산업은행" <?if ($member['bankName']=="KDB산업은행"){?>selected<?}?>>KDB산업은행</option>
                <option value="SC제일은행" <?if ($member['bankName']=="SC제일은행"){?>selected<?}?>>SC제일은행</option>
                <option value="대구은행" <?if ($member['bankName']=="대구은행"){?>selected<?}?>>대구은행</option>
                <option value="광주은행" <?if ($member['bankName']=="광주은행"){?>selected<?}?>>광주은행</option>
                <option value="부산은행" <?if ($member['bankName']=="부산은행"){?>selected<?}?>>부산은행</option>
                <option value="제주은행" <?if ($member['bankName']=="제주은행"){?>selected<?}?>>제주은행</option>
                <option value="전북은행" <?if ($member['bankName']=="전북은행"){?>selected<?}?>>전북은행</option>
                <option value="경남은행" <?if ($member['bankName']=="경남은행"){?>selected<?}?>>경남은행</option>
            </select>
        </li>
<!--        <li>
            <label for="bankName" class="sound_only">은행명<strong>필수</strong></label>
            <input type="text" name="bankName" id="bankName" class="frm_input full_input <?php echo $required ?>" minlength="3" maxlength="20" <?php echo $required ?> placeholder="은행명 ex)국민은행">
        </li>-->
        <li>
            <label for="reg_mb_payment" class="sound_only">예금주<strong>필수</strong></label>
            <input type="text" name="mb_accountHolder" id="reg_mb_payment" class="frm_input full_input <?php echo $required ?>" minlength="2" maxlength="20" <?php echo $required ?> placeholder="예금주" value="<?=$member['accountHolder']?>">
        </li>
        <li>
            <label for="reg_mb_account" class="sound_only">계좌번호<strong>필수</strong></label>
            <input type="text" name="mb_accountNumber" id="reg_mb_account" class="frm_input full_input <?php echo $required ?>" minlength="3" maxlength="20" <?php echo $required ?> placeholder="계좌번호 (숫자만 입력)" onkeydown="onlyNumber(this)" value="<?=$member['accountNumber']?>">
        </li>
<!--        <li>
            <input type="button" value="계좌 인증 하기" onclick="accountPop();" style="height: 40px; width: 100%;">
        </li>-->
    </div>
    
    
<script>
// 계좌인증 팝업을 띄우는 JS
    function accountPop() {
        if( $("#bankName option:selected").val() == "00" || $("#reg_mb_payment").val() == "" || $("#reg_mb_account").val() == "" ) {
            alert("은행명, 예금주명, 계좌번호를 입력 후 진행할 수 있습니다.");
            return false;
        }
        if( !( $.trim($("#reg_mb_name").val()) ==  $.trim($("#reg_mb_payment").val()) )) {
            alert("이름과 예금주가 동일하지 않습니다.");
            return false;
        }
        
   
        
        url = "name=" + $("#reg_mb_payment").val() + "&bank_holder_number=" + $("#reg_mb_account").val() + "&bankName=" + $("#bankName option:selected").val();
        window.open('http://vmptomcat.cafe24.com/account_popup_nh.jsp?'+encodeURI(url));
        
        
        
        
    }
 
 
 
// 여기여기


// 휴대폰번호 문자인증을 안할경우 멘트띄우기
$('#reg_mb_hp').focusout(function(){
    if( ! ($('#reg_mb_hp').val().length == 11)){
        return
    } else {
        if( $('.reg_hp_confirm_btn').css('display') == 'inline' ){
            regConfirmMsg('origin');
            
        } else {
            return
        }
    }
});



// 인증번호 제한시간 타이머
var SetTime = 180;
var tid = null;

function msg_time() {	// 1초씩 카운트
    mm = Math.floor(SetTime / 60) + "분 " + (SetTime % 60) + "초";// 남은 시간 계산

    var msg = "남은 시간 <font color='#f75e61'>" + mm + "</font>";

    document.all.ViewTimer.innerHTML = msg;// div 영역에 보여줌 

    SetTime--;	// 1초씩 감소

    if (SetTime < 0) {	// 시간이 종료 되었으면..

        clearInterval(tid); // 타이머 해제
        alert("입력 시간을 초과하였습니다.");
        window.location.reload();
    }
}


// 휴대전화번호 문자 재인증하기
function reconfirmHp(){
    $('.auth_number').addClass('active');
    $('.reg_hp_confirm_box').html(''); // 인증문자 박스내용 지우기 
    $('.reg_hp_reconf_btn').css('display','none'); // 번호변경하기 버튼 감추기
    $('.reg_hp_info').addClass('on'); // 인증문자 설명 보이기
    regConfirmMsg('origin');

    $('#reg_mb_hp').prop('readonly',false);

    //문자인증 초기화
    $('.reg_hp_confirm_btn').css('display','inline');
    $('#reg_mb_hp').val('');
    
    SetTime = 180;
    
    if( tid !==  null ){
        clearInterval(tid);
    }   
}


// 문자인증번호 눌렀을 때 실행하기
$(".reg_hp_confirm_btn").click(function(){    

    if( ! ($('#reg_mb_hp').val().length == 11) ){
        alert('휴대폰 번호를 정확히 입력해 주세요.');
        return
    }

    // 인증번호 생성 후 서버에 인증번호 전송 요청
    var result = Math.floor(Math.random() * 9999) + 1;
    var length = 4;
    result=result+""
    var str=""

    for(var i=0;i<length-result.length;i++){
      str=str+"0";
    }
    str=str+result;
    $("#reg_hp_smsnumber").val(str);

    var data = "smsNumber=" + str + "&mb_id=" + $('#reg_mb_id').val() + "&mb_hp=" + $("#reg_mb_hp").val();
    
    $('#confirmedNum').val( $("#reg_mb_hp").val() );
    
    $.ajax({
        url:'/myOffice/smsAuthentication.php',
        type:'POST',
        data: data
    });
    $(".reg_hp_confirm_btn").hide(function(){
        $('#reg_mb_hp').prop('readonly',true);
        $('.reg_hp_reconf_btn').css('display','inline');
        $(".reg_hp_info").text( "휴대폰으로 전송된 인증번호를 입력하여 주세요" );
        $(".reg_hp_info").css('color','#354d90');
        $(".reg_hp_info").css('height','20px');
        $(".reg_hp_confirm_box").css('margin','23px 0');
        $(".reg_hp_confirm_box").append('<p id="ViewTimer"></p>');
        $(".reg_hp_confirm_box").append('인증번호 입력');
        $(".reg_hp_confirm_box").append('<input type="text" name="reg_hp_innumber" id="reg_hp_innumber" minlength="4" maxlength="4" autocomplete="off" title="인증번호 입력" value=""/>');
        checkHpinnumber();
    });
    tid = setInterval('msg_time()',1000);
 });
 
 
 
 // 문자인증번호 값검증 메세지 띄우기
 function regConfirmMsg(str){
    if( str == "success" ){
        $('.reg_hp_info').text('문자인증 완료');
        $('.reg_hp_info').css('color','#4285F4');
        $('.reg_hp_info').css('text-align','center');
        
    } else if ( str == "fail" ){
        $('.reg_hp_info').text('정확하게 입력하여 주세요.(4자리)');
        $('.reg_hp_info').css('color','#e8104c');
    } else if ( str == 'origin' ){
        $('.reg_hp_info').css('height','48px');
        $('.reg_hp_info').css('color','#354d90');
        $('.reg_hp_info').css('text-align','left');
        $('.reg_hp_info').html('휴대전화 번호 입력후 인증문자 발송 버튼을 누르세요.</br>4자리 인증번호 문자를 보내드리겠습니다.</p>');

    } 
 }
 
 

 
 
// 문자인증번호 값검증
function checkHpinnumber(){
    $('#reg_hp_innumber').keyup(function(ev){
        var keyValue = this.value.trim();
        var onlyNumber = keyValue.replace(/[^0-9]/g, '');
        var tmp = '';
        
        if( onlyNumber.length < 4 ){
           this.value = onlyNumber;
           
           if( $('#reg_hp_innumber').val() !== $("#reg_hp_smsnumber").val() ){ // 인증번호가 틀렸을 때
                regConfirmMsg('fail');
                return;
                
            } else {
                $('#reg_mb_hp').prop('readonly',true);
                $('.reg_hp_reconf_btn').css('display','inline');
                regConfirmMsg('success');
            }
            
        } else if( onlyNumber.length == 4 ){
            this.value = onlyNumber.substr(0,4);
            
            if( $('#reg_hp_innumber').val() !== $("#reg_hp_smsnumber").val() ){ // 인증번호가 틀렸을 때
                regConfirmMsg('fail');
                return;
                
            } else {
                $('#reg_mb_hp').prop('readonly',true);
                $('.reg_hp_reconf_btn').css('display','inline');
                $('.reg_hp_confirm_box').css('display','none');
                regConfirmMsg('success');
            }
        }
    });
}


 //여기요
//문자인증 값검증
$('.auth_step_3 > a').on('click', function(){

    
    if( $(this).hasClass('btn_auth_ok') ){ // 변경 저장을 눌렀을 때

        if( $('#confirmedNum').val() == '' ){ 
            alert('번호인증을 거친 후 휴대폰 번호를 변경하실 수 있습니다.');
            return;
            
        } else if( $('#confirmedNum').val() !== $('#mb_hp').val() ){
            alert('번호인증을 해주세요');
            return
            
        } else {
            $('#hp').val( $('#confirmedNum').val() );
            $('.auth_number').removeClass('active');
            $('#hp').css('background-color','#4285F4');
            $('#hp').css('color','#fff');

        }
        
        
        
    } else if ( $(this).hasClass('btn_cancel') ){ // 취소를 눌렀을 때
        $('.auth_number').removeClass('active');
        $('.step_1_btn').css('display','inline-block');
        $('.hp_confirm_box').removeClass('active');
        $('.hp_confirm_box').html('');
        $('.auth_step_2').css('display','none');
        $('#mb_hp').val('');
        $('#mb_hp').prop('readonly',false);
        $('.hp_confirm_box_2').html(''); // 인증문자 박스내용 지우기 
        $('.hp_reconf_btn').css('display','none'); // 번호변경하기 버튼 감추기
        $('.hp_info').addClass('on'); // 인증문자 설명 보이기
        $('.step_2_btn').css('display','inline-block'); // 인증문자 전송버튼 보이기
        $('.user_Modified input').addClass('on').attr('readonly',false);
        $(".auth_process").find('li').eq(0).addClass('on').siblings('li').removeClass('on');
        regConfirmMsg('origin');

        SetTime = 180;

        if( tid !==  null ){
            clearInterval(tid);
        }   
    }
});
 
 
 
 
       
</script>
    
    
    
   
   
    <div class="form_01 agree">
        
        <h2 class="noti">※ 문자 또는 이메일 수신 동의시 중요 혜택들을 받으실 수 있습니다.</h2>
        <?php if ($config['cf_use_signature']) { ?>
        <li>
            <label for="reg_mb_signature" class="sound_only">서명<?php if ($config['cf_req_signature']){ ?><strong>필수</strong><?php } ?></label>
            <textarea name="mb_signature" id="reg_mb_signature" class="<?php echo $config['cf_req_signature']?"required":""; ?>" <?php echo $config['cf_req_signature']?"required":""; ?> placeholder="서명"><?php echo $member['mb_signature'] ?></textarea>
        </li>
        <?php } ?>

        <?php if ($config['cf_use_profile']) { ?>
        <li>
            <label for="reg_mb_profile" class="sound_only">자기소개</label>
            <textarea name="mb_profile" id="reg_mb_profile" class="<?php echo $config['cf_req_profile']?"required":""; ?>" <?php echo $config['cf_req_profile']?"required":""; ?> placeholder="자기소개"><?php echo $member['mb_profile'] ?></textarea>
        </li>
        <?php } ?>

        <?php if ($config['cf_use_member_icon'] && $member['mb_level'] >= $config['cf_icon_level']) { ?>
        <li>
            <label for="reg_mb_icon" class="frm_label">회원아이콘</label>
            <input type="file" name="mb_icon" id="reg_mb_icon">
            <span class="frm_info">
                이미지 크기는 가로 <?php echo $config['cf_member_icon_width'] ?>픽셀, 세로 <?php echo $config['cf_member_icon_height'] ?>픽셀 이하로 해주세요.<br>
                gif만 가능하며 용량 <?php echo number_format($config['cf_member_icon_size']) ?>바이트 이하만 등록됩니다.
            </span>
            <?php if ($w == 'u' && file_exists($mb_icon_path)) { ?>
            <img src="<?php echo $mb_icon_url ?>" alt="회원아이콘">
            <input type="checkbox" name="del_mb_icon" value="1" id="del_mb_icon">
            <label for="del_mb_icon">삭제</label>
            <?php } ?>
            
        </li>
        <?php } ?>

        <?php if ($member['mb_level'] >= $config['cf_icon_level'] && $config['cf_member_img_size'] && $config['cf_member_img_width'] && $config['cf_member_img_height']) {  ?>
        <li style="display: none">
            <label for="reg_mb_img" class="frm_label">회원이미지</label>
            <input type="file" name="mb_img" id="reg_mb_img" >
            <span class="frm_info">
                이미지 크기는 가로 <?php echo $config['cf_member_img_width'] ?>픽셀, 세로 <?php echo $config['cf_member_img_height'] ?>픽셀 이하로 해주세요.<br>
                gif 또는 jpg만 가능하며 용량 <?php echo number_format($config['cf_member_img_size']) ?>바이트 이하만 등록됩니다.
            </span>
            <?php if ($w == 'u' && file_exists($mb_img_path)) {  ?>
            <img src="<?php echo $mb_img_url ?>" alt="회원아이콘">
            <input type="checkbox" name="del_mb_img" value="1" id="del_mb_img">
            <label for="del_mb_img">삭제</label>
            <?php }  ?>

        </li>
        <?php } ?>

        <li class="mailing_con">
            <span>메일링서비스</span>
            <input type="checkbox" name="mb_mailling" value="1" id="reg_mb_mailling" <?php echo ($w=='' || $member['mb_mailling'])?'checked':''; ?>>
            <label for="reg_mb_mailling" class="frm_label mailing">정보 메일을 받겠습니다.</label>
        </li>

        <?php if ($config['cf_use_hp']) { ?>
        <li class="sms_con">
            <span>SMS 수신여부</span>        
            <input type="checkbox" name="mb_sms" value="1" id="reg_mb_sms" <?php echo ($w=='' || $member['mb_sms'])?'checked':''; ?>>
            <label for="reg_mb_sms" class="frm_label sms">문자메세지를 받겠습니다.</label>
        </li>
        <?php } ?>

        <?php if (isset($member['mb_open_date']) && $member['mb_open_date'] <= date("Y-m-d", G5_SERVER_TIME - ($config['cf_open_modify'] * 86400)) || empty($member['mb_open_date'])) { // 정보공개 수정일이 지났다면 수정가능 ?>
        <!--  <li>
            <label for="reg_mb_open" class="frm_label">정보공개</label>
            <input type="checkbox" name="mb_open" value="1" id="reg_mb_open" <?php echo ($w=='' || $member['mb_open'])?'checked':''; ?>>
            다른분들이 나의 정보를 볼 수 있도록 합니다.
            <span class="frm_info">
                정보공개를 바꾸시면 앞으로 <?php echo (int)$config['cf_open_modify'] ?>일 이내에는 변경이 안됩니다.
            </span>
            <input type="hidden" name="mb_open_default" value="<?php echo $member['mb_open'] ?>">
            
        </li> -->
        
        
            <input type="hidden" name="mb_open" value="1" id="reg_mb_open">
            <input type="hidden" name="mb_open_default" value="<?php echo $member['mb_open'] ?>">
            
        <?php } else { ?>
        <li>
            <span  class="frm_label">정보공개</span>
            <input type="hidden" name="mb_open" value="<?php echo $member['mb_open'] ?>">
            
            <span class="frm_info">
                정보공개는 수정후 <?php echo (int)$config['cf_open_modify'] ?>일 이내, <?php echo date("Y년 m월 j일", isset($member['mb_open_date']) ? strtotime("{$member['mb_open_date']} 00:00:00")+$config['cf_open_modify']*86400:G5_SERVER_TIME+$config['cf_open_modify']*86400); ?> 까지는 변경이 안됩니다.<br>
                이렇게 하는 이유는 잦은 정보공개 수정으로 인하여 쪽지를 보낸 후 받지 않는 경우를 막기 위해서 입니다.
            </span>
        </li>
        <?php } ?>

        <?php
        //회원정보 수정인 경우 소셜 계정 출력
        if( $w == 'u' && function_exists('social_member_provider_manage') ){
            social_member_provider_manage();
        }
        ?>

        <?php if ($w == "" && $config['cf_use_recommend']) { ?>
        <li>
            <label for="reg_mb_recommend" class="sound_only">추천인아이디</label>
            <input type="text" name="mb_recommend" id="reg_mb_recommend" class="frm_input full_input" placeholder="추천인아이디">
        </li>
        <?php } ?>

        <li class="is_captcha_use">
            <span  class="frm_label none_auto_regi">자동등록방지</span>
            <?php echo captcha_html(); ?>
        </li>    
    </div>

    <div class="btn_top top">
        <input type="submit" value="<?php echo $w==''?'회원가입':'정보수정'; ?>" id="btn_submit" class="btn_submit" accesskey="s">
    </div>
    </form>

    <script>
        
    $(function() {
        
        $("#reg_zip_find").css("display", "inline-block");

        <?php if($config['cf_cert_use'] && $config['cf_cert_ipin']) { ?>
        // 아이핀인증
        $("#win_ipin_cert").click(function() {
            if(!cert_confirm())
                return false;

            var url = "<?php echo G5_OKNAME_URL; ?>/ipin1.php";
            certify_win_open('kcb-ipin', url);
            return;
        });

        <?php } ?>
        <?php if($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
        // 휴대폰인증
        $("#win_hp_cert").click(function() {
            if(!cert_confirm())
                return false;

            <?php
            switch($config['cf_cert_hp']) {
                case 'kcb':
                    $cert_url = G5_OKNAME_URL.'/hpcert1.php';
                    $cert_type = 'kcb-hp';
                    break;
                case 'kcp':
                    $cert_url = G5_KCPCERT_URL.'/kcpcert_form.php';
                    $cert_type = 'kcp-hp';
                    break;
                default:
                    echo 'alert("기본환경설정에서 휴대폰 본인확인 설정을 해주십시오");';
                    echo 'return false;';
                    break;
            }
            ?>

            certify_win_open("<?php echo $cert_type; ?>", "<?php echo $cert_url; ?>");
            return;
        });
        <?php } ?>
    });

    // 인증체크
    function cert_confirm()
    {
        var val = document.fregisterform.cert_type.value;
        var type;

        switch(val) {
            case "ipin":
                type = "아이핀";
                break;
            case "hp":
                type = "휴대폰";
                break;
            default:
                return true;
        }

        if(confirm("이미 "+type+"으로 본인확인을 완료하셨습니다.\n\n이전 인증을 취소하고 다시 인증하시겠습니까?"))
            return true;
        else
            return false;
    }

// submit 최종 폼체크
function fregisterform_submit(f){

//        var cu_cek = $('img#cu_ck').attr('src');
//        var vm_cek = $('img#vm_ck').attr('src');
             
//       if(cu_cek==vm_cek){
//         alert("계정종류를 선택 해 주십시오.");
//
//         return false;
//        }
    
     
        if( $("#bankName option:selected").val() == "00" || $("#reg_mb_payment").val() == "" || $("#reg_mb_account").val() == "" ) {
            alert("은행명, 예금주명, 계좌번호를 입력 후 진행할 수 있습니다.");
            return false;
        }
        if( !( $.trim($("#reg_mb_name").val()) ==  $.trim($("#reg_mb_payment").val()) )) {
            alert("이름과 예금주가 동일하지 않습니다.");
            return false;
        }
        

        
        // 회원아이디 검사
//        if (f.w.value == "") {
//            var msg = reg_mb_id_check();
//            if (msg) {
//                alert(msg);
//                f.mb_id.select();
//                return false;
//            }
//        }


        if (f.mb_password.value.length < 3) {
                alert('비밀번호를 3글자 이상 입력하십시오.');
                f.mb_password.focus();
                return false;
        }
        if (f.mb_password.value != f.mb_password_re.value) {
            alert('비밀번호가 같지 않습니다.');
            f.mb_password_re.focus();
            return false;
        }

        if (f.mb_password.value.length > 0) {
            if (f.mb_password_re.value.length < 3) {
                alert('비밀번호를 3글자 이상 입력하십시오.');
                f.mb_password_re.focus();
                return false;
            }
        }
        
        
    //여기요
    // 문자인증 여부체크 
    if ( $('.reg_hp_confirm_btn').css('display') == 'inline' && $('#reg_mb_hp').val() !== $('#confirmedNum').val() ){
        alert('변경된번호로 문자인증을 진행해 주세요');
        
        $('#reg_mb_hp').focus();
        //문자인증 초기화
//        $('.reg_hp_confirm_btn').css('display','inline');
//        $('.reg_hp_confirm_box').html('');
//        regConfirmMsg('origin');
//        


        SetTime = 180;
        clearInterval(tid);
        return false;

    } else if ( $('.reg_hp_confirm_btn').css('display') == "none" && $('#reg_hp_smsnumber').val() !== '' && $('#reg_hp_innumber').val() !== $('#reg_hp_smsnumber').val()){
        alert('인증번호가 틀렸습니다.');
        $('#reg_hp_innumber').focus();
        return false;
    } 
   

// 후원인 팀배치 체크박스
//      if($('#radiobtn').val() == '0') {
//          alert("후원인 팀배치를 선택하지 않았습니다.");
//          return false;
//       }
        
// 이름 검사
        if (f.mb_name.value.length < 1) {
                alert('이름을 입력하십시오.');
                f.mb_name.focus();
                return false;
        }   
        
        
        <?php if($w == '' && $config['cf_cert_use'] && $config['cf_cert_req']) { ?>
        // 본인확인 체크
        if(f.cert_no.value=="") {
            alert("회원가입을 위해서는 본인확인을 해주셔야 합니다.");
            return false;
        }
        <?php } ?>
        <?php // echo chk_captcha_js(); ?>

//        document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }
    </script>
</div>

<script type="text/javascript">
// 은행점검시간 주석처리 2019-06-11 이지양
//    var myTime=new Date();
//    var myHours=myTime.getHours();
//    var myMin=myTime.getMinutes();
//
//    if( (myHours=="23" && myMin>="30") && (myHours=="23" && myMin<="59") || (myHours=="00" && myMin>="00") && (myHours=="00" && myMin<="30")){
//            alert('23:30 ~ 00:30 동안 은행 점검 시간으로 회원가입이 불가합니다. 점검 시간 이후에 회원가입을 진행 바랍니다.');
//    }
</script>