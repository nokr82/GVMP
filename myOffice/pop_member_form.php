<?php
include_once('./inc/title.php');
include_once('./dbConn.php');
include_once('./_common.php');
include_once('./getMemberInfo.php');
include_once('./OrganizationChartInfo.php');
?>
<link rel="stylesheet" href="./css/box1122.css">    

<script src="<?php echo G5_JS_URL ?>/jquery-1.8.3.min.js"></script>
<?php
if (defined('_SHOP_')) {
    if (!G5_IS_MOBILE) {
        ?>
        <script src="<?php echo G5_JS_URL ?>/jquery.shop.menu.js?ver=<?php echo G5_JS_VER; ?>"></script>
        <?php
    }
} else {
    ?>
    <script src="<?php echo G5_JS_URL ?>/jquery.menu.js?ver=<?php echo G5_JS_VER; ?>"></script>
<?php } ?>
<script src="<?php echo G5_JS_URL ?>/common.js?ver=<?php echo G5_JS_VER; ?>"></script>
<script src="<?php echo G5_JS_URL ?>/wrest.js?ver=<?php echo G5_JS_VER; ?>"></script>
<script src="<?php echo G5_JS_URL ?>/placeholders.min.js"></script>

<script type="text/javascript" src="http://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<!-- 주소찾기 -->
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script> 
<script src="<?php echo G5_JS_URL ?>/common.js"></script>

<?php
if ($config['cf_use_addr'])
    add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js


$rank = $member['accountRank'];
$admin_mb_id = $member['mb_id'];

if (G5_IS_MOBILE) {
    echo '<meta name="viewport" content="initial-scale=1.0,user-scalable=no,maximum-scale=1,width=device-width" />' . PHP_EOL;
    echo '<meta name="HandheldFriendly" content="true">' . PHP_EOL;
    echo '<meta name="format-detection" content="telephone=no">' . PHP_EOL;
    echo '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">' . PHP_EOL;
} else {
    echo '<meta http-equiv="imagetoolbar" content="no">' . PHP_EOL;
    echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">' . PHP_EOL;
}

if ($config['cf_add_meta']) echo $config['cf_add_meta'] . PHP_EOL;

$mb = mysql_query("select * from g5_member where mb_id = '{$mb_id}'");
$mb_row = mysql_fetch_array($mb);
$count = $mb_row['modi_count'];


if ($rank != 'AMBASSADOR' && $rank != '5 STAR' && $rank != 'Crown AMBASSADOR' && $rank != 'Double AMBASSADOR' && $rank != 'Royal Crown AMBASSADOR' && $rank != 'Triple AMBASSADOR') {
    if ($admin_mb_id !="00003656"&&$admin_mb_id !="00000182"&&$admin_mb_id !="00003710"&&$admin_mb_id !="00031544"&&$admin_mb_id !="00003696"&&$admin_mb_id !="00003686"&&$admin_mb_id !="00003943"&&$admin_mb_id !="00003571"&&$admin_mb_id !="00003697"&&$admin_mb_id !="00003879"&&$admin_mb_id !="00003660"&&$admin_mb_id !="00003880"&&$admin_mb_id !="00066958"&&$admin_mb_id !="00447591"&&$admin_mb_id !="00447972"&&$admin_mb_id !="00447976"&&$admin_mb_id !="00003967"&&$admin_mb_id !="00019492"&&$admin_mb_id !="00104175"&&$admin_mb_id !="00104204"){
         close_caution('수정권한이 없습니다.');
    }
}
if ($_GET["check"] == "true") {
    close_caution('수정되었습니다.');
}

// 한동안 무제한 수정 가능
if ($count >= 3) {
    close_caution('이미 수정한 회원입니다.');
}

function close_caution($msg) {
    $str = "<script>";
    $str .= "alert('{$msg}');";
    $str .= "self.close();";
    $str .= "</script>";
    echo("$str");
    exit;
}
?>







<!--2019년 11월 26일 추가-->
<div class="pop_companymap">

    <div class="wrap_pop">
        <form method="post" action="./update_test.php" id="fregisterform" name="fregisterform" onsubmit="return checkIt()">
            <input type="hidden" name="modi_id" id="modi_id" value="<?= $member['mb_id'] ?>">
            <input type="hidden" id="mb_id" name="mb_id" value="<?= $mb_row['mb_id'] ?>" />
            <input type="hidden" id="modi_count" name="modi_count" value="<?= $mb_row['modi_count'] ?>" />
            <h4>회원정보 변경</h4>
			<div class="table_row">
               <p><label for="">이름</label></p>
               <div class="">
                   <input type="text" id="name" name="name" value="<?= $mb_row['mb_name'] ?>" placeholder="이름"/>
               </div>
            </div>
            <div class="table_row wt1 desktop_half1">
               <p><label for="hp">휴대폰 번호</label></p>
               <div class="">
                   <input type="text" id="hp" name="hp" required="required" value="<?= $mb_row['mb_hp'] ?>" placeholder="01012345678"/>
                   <a href="javascript:void(0);" onclick="authNumber(this)">인증번호발송</a>       
                   <div class="count_text"></div>
                   <span id="timer"></span>
               </div>
            </div>
            <div class="table_row wt1 desktop_half2 ">
               <p><label for="con_num">인증번호</label></p>
               <div class="">
                   <input type="number" id="con_num" name="con_num" min="0" max="9999"   placeholder="인증번호를 입력해주세요."/>
               </div>
            </div>
            <div class="table_row wt1">
               <p><label for="birth">생년월일</label></p>
               <div>
                   <input type="number" id="birth" name="birth" value="<?= $mb_row['birth'] ?>" placeholder="생년월일"/>
               </div>         
            </div>
            <div class="table_row">
               <p><label for="email">이메일</label></p>
               <div><input type="email" id="email" name="email" value="<?= $mb_row['mb_email'] ?>"  placeholder="email@naver.com"/></div>                            
            </div>          
            <div class="table_row">
                <p><label for="passwd">비밀번호</label></p>
                <div><input type="password" id="passwd" name="passwd" required="required"/></div>         
            </div>          
            <div class="table_row">
                    <p><label for="con_passwd">비밀번호 확인</label></p>
                    <div><input type="password" id="con_passwd" name="con_passwd" required="required"/></div>                            
              </div>          
            <div class="table_row aligntop">
               <p class='address_pd'><label for="mb_zip">주소</label></p>
               <div>
                   <label for="reg_mb_zip" class="sound_only">우편번호</label>
                   <input type="text" required="required"  id="mb_zip" name="mb_zip" value="<?= $mb_row['mb_zip1'] . $mb_row['mb_zip2'] ?>" size="5" maxlength="6" placeholder="우편번호">
                   <button type="button" class="" onclick="win_zip('fregisterform', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소 검색</button>
                   <div id="daum_juso_pagemb_zip">
                   </div>
                   <label for="mb_addr1"   class="sound_only">주소</label>
                   <input type="text" required="required" name="mb_addr1" value="<?= get_text($mb_row['mb_addr1']) ?>" id="mb_addr1" size="50" placeholder="주소를 검색해주세요.">
                   <label for="mb_addr2"  class="sound_only">상세주소</label>
                   <input type="text" required="required" name="mb_addr2" value="<?= get_text($mb_row['mb_addr2']) ?>" id="mb_addr2" size="50" placeholder="주소를 검색해주세요.">
               </div>
           <input type="hidden" name="mb_addr3" value="<?php echo get_text($mb_row['mb_addr3']) ?>" id="mb_addr3" class="frm_address" size="50" readonly="readonly" placeholder="참고항목">
           <input type="hidden" name="mb_addr_jibeon" id="mb_addr_jibeon" value="<?php echo get_text($mb_row['mb_addr_jibeon']); ?>">
          </div>          
         <div class="table_row desktop_half1 bankbox">
            <p><label for="bankName">은행명</label></p>
            <div><input type="text" required="required" id="bankName" name="bankName" value="<?= $mb_row['bankName'] ?>" placeholder="은행명"/></div>
         </div>          
         <div class="table_row desktop_half2">
            <p><label for="accountHolder">예금주</label></p>
            <div class="wt1"><input type="text" required="required" id="accountHolder" name="accountHolder" value="<?= $mb_row['accountHolder'] ?>" placeholder="예금주"/></div>
            </div>          
         <div class="table_row">
            <p><label for="accountNumber">계좌번호</label></p>
            <div class="wt1"><input type="text" required="required" id="accountNumber" name="accountNumber" value="<?= $mb_row['accountNumber'] ?>" placeholder="계좌번호"/></div>
         </div>             

         <div class="pop_submit_box"> 
            <button class="chagne_personal" id="btn_submit" onclick="">수정하기</button>
            <button type="button" class="cancel" onclick="button1_click()" >취소하기</button>
         </div>
      </form>
    </div>
</div>

<script>
    function button1_click() {
        self.close();
    }

    var g5_is_mobile = "<?php echo G5_IS_MOBILE ?>";
    function checkIt() {
        var passwd = $('#passwd').val();
        var con_passwd = $('#con_passwd').val();
        if (passwd != con_passwd) {
            alert("비밀번호가 일치하지 않습니다.");
            $('#con_passwd').focus();
            return false;
        } else if (!confirm_ck) {
            alert("인증번호가 일치하지않습니다.");
            return false;
        } else {
            return;
        }


    }



// 숫자와 하이픈만 사용가능하게 하는 함수입니다.
    function autoHypenPhone(str) {
        str = str.replace(/[^0-9]/g, '');
        var tmp = '';
        if (str.length > 11) {
            alert('휴대폰 번호를 정확히 입력해 주세요.');
            tmp += str.substr(0, 11);
            return tmp;
        }
        if (str.length < 4) {
            return str;
        } else if (str.length < 7) {
            tmp += str.substr(0, 3);
            tmp += '-';
            tmp += str.substr(3);
            return tmp;
        } else if (str.length < 11) {
            tmp += str.substr(0, 3);
            tmp += '-';
            tmp += str.substr(3, 3);
            tmp += '-';
            tmp += str.substr(6);
            return tmp;
        } else {
            tmp += str.substr(0, 3);
            tmp += '-';
            tmp += str.substr(3, 4);
            tmp += '-';
            tmp += str.substr(7);
            return tmp;
        }
        return str;
    }

    function $ComTimer() {
        //prototype extend
    }

    $ComTimer.prototype = {
        comSecond: ""
        , fnCallback: function () {}
        , timer: ""
        , domId: ""
        , fnTimer: function () {
            var m = Math.floor(this.comSecond / 60) + "분 " + (this.comSecond % 60) + "초";	// 남은 시간 계산
            this.comSecond--;					// 1초씩 감소
            this.domId.innerText = m;
            if (this.comSecond < 0) {			// 시간이 종료 되었으면..
                clearInterval(this.timer);		// 타이머 해제
                alert("인증시간이 초과하였습니다. 다시 인증해주시기 바랍니다.")
                location.reload();
            }
        }
        , fnStop: function () {
            clearInterval(this.timer);
        }
    }




// 회원정보수정 휴대폰번호 문자인증 활성화 



// 요기
// 문자인증번호 눌렀을 때 실행하기
    function authNumber(obj) {
        var mb_hp = $('#hp').val();
        console.log(mb_hp);
//        if (!(mb_hp.length == 11)) {
//            alert('휴대폰 번호를 정확히 입력해 주세요.');
//            $('#mb_hp').focus();
//            return
//        }
        sendNumber(obj);

    }

    var AuthTimer = new $ComTimer()
    var str = ""
    var confirm_ck = false
    function sendNumber(obj) {
        // 인증번호 생성 후 서버에 인증번호 전송 요청
        var result = Math.floor(Math.random() * 9999) + 1;
        var length = 4;
        result = result + ""
        str = "";

        var hp = $('#hp').val();
        //  var confirmBox;

        for (var i = 0; i < length - result.length; i++) {
            str = str + "0";
        }
        str = str + result;
        $("#hp_smsnumber").val(str);

        var data = "smsNumber=" + str + "&mb_id=" + $("#mb_id").text() + "&mb_hp=" + hp;
        $.ajax({
            url: 'smsAuthentication.php',
            type: 'POST',
            data: data
        });

        //인증타이머
        $(obj).html("인증번호 재발송").css("background-color","#cd3432");
        AuthTimer.comSecond = 60 * 3;
        AuthTimer.fnCallback = function () { }
        AuthTimer.timer = setInterval(function () {
            AuthTimer.fnTimer()
        }, 1000);
        AuthTimer.domId = document.getElementById("timer");
        $('.count_text').html("휴대폰으로 전송된 인증번호를 입력하여 주세요.").show();
        console.log(str);
    }

    $('#hp').keyup(function () {
        var keyValue = this.value.trim();
        this.value = autoHypenPhone(keyValue);
        console.log(keyValue);
//    numberConfirm(ev.target);
    });




    $("#con_num").on("propertychange change keyup paste input", function () {
        var currentVal = $(this).val();
        if (str != "") {
            if (currentVal == str) {
                AuthTimer.fnStop();
                confirm_ck = true;
                $('.count_text').html("인증번호가 일치합니다.").show();
                $('#timer').hide();
                $(this).attr('readonly', true);
                return;
            }
        }


    });
</script>
<style>
@media only screen and (min-width:801px) {
html {font-size:16px;}
}
@media only screen and (max-width:800px) {
html {font-size:14px;}
.wrap_pop {margin:0;}
}
@media only screen and (max-width:600px) {
html {font-size:12px;}
}
@media only screen and (max-width:400px) {
html {font-size:11px;}
}
</style>