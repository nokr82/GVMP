<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once($_SERVER['DOCUMENT_ROOT'] . "/myOffice/dbConn.php");


// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_CSS_URL.'/style.css">', 0);
add_javascript('<script src="'.G5_THEME_JS_URL.'/jquery.shop.list.js"></script>', 10);
?>

<?php
    $row = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'"));
    if( $row['accountType'] != "VM" ) { // VM몰이면 참
        if( $member['mb_id'] != "admin" )
            echo "<script>alert('접근할 수 없는 계정입니다.');history.back();</script><style>body{display:none;}</style>";
    }
?>
<link rel="stylesheet" href="/theme/everyday/mobile/skin/shop/basic/list.10.skin_gifts.css">
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>

<?php if($config['cf_kakao_js_apikey']) { ?>
<script src="https://developers.kakao.com/sdk/js/kakao.min.js"></script>
<script src="<?php echo G5_JS_URL; ?>/kakaolink.js"></script>

<script>
    // 사용할 앱의 Javascript 키를 설정해 주세요.
    Kakao.init("<?php echo $config['cf_kakao_js_apikey']; ?>");
</script>
<?php } ?>

<?php
    $row = mysql_fetch_array(mysql_query("select * from selectedProducts where mb_id = '{$member['mb_id']}'"));
    if( $row['name'] == "" )
        echo "<script>alert('배송지 정보를 입력하지 않으셨습니다. 배송받을 주소를 입력바랍니다.');</script>"
?>

<div class="my_pd">
    <div class="my_pd_wrap">
        
        <h1>VM 제품 신청</h1>
        <div class="my_pd_cheak clearfix">
            <div class="cheak_pd">
                <h2>신청하신 제품</h2>
                <div class="cheak_pd_img">
                    <?php
                    if( $row['productNumber'] != "" ) {
                        $row2 = mysql_fetch_array(mysql_query("select * from g5_shop_item where it_id = '{$row['productNumber']}'"));
                        echo get_it_image($row['productNumber'], 280, 280, '', '', "" )."\n"; 
                    } else {
                        echo "<img src=\"\" width=\"280\" height=\"280\" alt=\"\">";
                    }
                    ?>
                    <p class="cheak_pd_p"><?=$row2['it_name']?></p>
                </div>
            </div>

            <div class="cheak_addr">
                <h2>배송지 정보</h2>
                <form action="/myOffice/giftsBack.php" method="POST" id="frm">
                    <input type="hidden" id="smsNumber" name="smsNumber">
                    <input type="hidden" id="product_ck" name="product_ck">
                    <ul>
                        <li class="clearfix"><div class="lender_name">이름</div><input type="text" id="lender_name" class="lender_txt" name="name" placeholder="이름" value="<?=$row['name']?>"></li>
                        <li class="clearfix"><div class="lender_name">휴대전화</div><input type="text" id="lender_phone" class="lender_txt" placeholder="전화번호" name="hp" onkeydown='return onlyNumber(event)' onkeyup='removeChar(event)'  maxlength="11" value="<?=$row['hp']?>"></li>
<!--         문자인증 주석처리               <li class="clearfix cheak_me cheak_btn">                            
                            <div class="lender_name cheak_me">인증번호</div>
                            <input type="text" name="smsinnumber" id="smsinnumber" value=""  placeholder="인증번호 입력" onkeydown='return onlyNumber(event)' onkeyup='removeChar(event)' maxlength="4"/><a id="smsbox" class="btn" href="javascript:void(0)">문자인증</a>
                            <div id="smstext" class="clearfix"></div>
                        </li>-->
                        <li class="clearfix"><div class="lender_name">주소</div><input type="text" id="addr_text" name="addr1" class="addr_text"  placeholder="검색주소입력" readonly value="<?=$row['address1']?>"/><a id="addr_search0" class="addr_search0 btn">검색</a></li>
                        <li class="clearfix"><div class="lender_name">상세주소</div><input type="text" id="addradd_text" name="addr2" class="lender_txt" placeholder="상세주소" value="<?=$row['address2']?>"></li>
                    </ul>
                   <a class="btn fin">저장</a>
                </form>
            </div>
        </div>
        <!-- 상품진열 10 시작 { -->
<div class="sct_wrap" >
<?php
$li_width = intval(100 / $this->list_mod);
$li_width_style = ' style="width:'.$li_width.'%;"';

for ($i=0; $row=sql_fetch_array($result); $i++) {
    if ($i == 0) {
        if ($this->css) {
            echo "<ul id=\"sct_wrap\" class=\"{$this->css}\">\n";
        } else {
            echo "<ul id=\"sct_wrap\" class=\"sct sct_10\">\n";
        }
    }
    
    if( $row['view'] != "YES" )
        continue;

    if($i % $this->list_mod == 0)
        $li_clear = ' sct_clear';
    else
        $li_clear = '';

  
    echo "<li class=\"sct_li\"  id=\"{$row['it_id']}\"> <div class=\"sct_li_wr\">\n";
  
   
   
    echo "<div class=\"img_wr\">";
    

    if ($this->href) {
        echo "<div class=\"sct_img\"><a href=\"javascript:void(0);\" class=\"sct_a\">\n";
    }

    if ($this->view_it_img) {
        echo get_it_image($row['it_id'], $this->img_width, $this->img_height, '', '', stripslashes($row['it_name']))."\n";
    }

    if ($this->href) {
        echo "</a></div>\n";
    }


    
    echo "</div>";

    echo "<div class=\"sct_cartop\"></div>\n";
    
    if ($this->href) {
        echo "<div class=\"sct_txt\"><a href=\"javascript:void(0);\" class=\"sct_a\">\n";
    }

    if ($this->view_it_name) {
        echo stripslashes($row['it_name'])."\n";
    }

    if ($this->href) {
        echo "</a></div>\n";
    }



 echo "<input type=\"radio\" class=\"pd_ck\" name=\"gift_ck\"> \n";
    echo "</div></li>\n";
}

if ($i > 0) echo "</ul>\n";

if($i == 0) echo "<p class=\"sct_noitem\">등록된 상품이 없습니다.</p>\n";
?>

</div>
    </div>
    
</div>
<div class="index_hidden">
<input type="radio" class="pd_ck index_hidden" name="gift_ck_sub" id="h_sub_1" >
<input type="radio" class="pd_ck index_hidden" name="gift_ck_sub" id="h_sub_2">
<input type="radio" class="pd_ck index_hidden" name="gift_ck_sub" id="h_sub_3">
<input type="radio" class="pd_ck index_hidden" name="gift_ck_sub" id="h_sub_4">
</div>

<script>

    $(".sct-size button").click(function () {
        $(".sct-size button").removeClass("active");
        $(this).addClass("active");
    });
    $("#btn-small").click(function () {
        $(".sct_wrap").removeClass("big").addClass("small");
    });
    $("#btn-big").click(function () {
        $(".sct_wrap").removeClass("small").addClass("big");
    });
  
// $('#sct_wrap li').eq(1).children('input').css({'display':'none'});
//  if(list_1){
//      alert('test');
//  }
//  

</script>
<!-- } 상품진열 10 끝 -->

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
        <script>
            
            var SetTime = 180;
                                function msg_time() {	// 1초씩 카운트
                         mm = Math.floor(SetTime / 60) + "분 " + (SetTime % 60) + "초";	// 남은 시간 계산

                         var msg = "남은 시간 <font color='red'>" + mm + "</font>";

                         document.all.ViewTimer.innerHTML = msg;		// div 영역에 보여줌 

                         SetTime--;					// 1초씩 감소

                         if (SetTime < 0) {			// 시간이 종료 되었으면..

                                 clearInterval(tid);		// 타이머 해제
                                 alert("입력 시간을 초과하였습니다.");
                                 window.location.reload();
                         }

                 }
            
            
            $("#smsbox").click(function(){
                if ($("input#lender_phone").val().trim() == "") {
                        alert("전화번호를 입력해 주세요.");
                        $("input#lender_phone").focus();
                         return false;
                    } 
                    
                $('#smstext').fadeIn();
                $('.cheak_me').animate({'height':'80'});    
        
                // 인증번호 생성 후 서버에 인증번호 전송 요청
                var result = Math.floor(Math.random() * 9999) + 1;
                var length = 4;
                result=result+""
                var str=""

                for(var i=0;i<length-result.length;i++){
                  str=str+"0";
                }
                str=str+result;
                $("#smsNumber").val(str);

                var data = "smsNumber=" + str + "&mb_hp=" + $("#lender_phone").val();
                $.ajax({
                    url:'/myOffice/smsAuthentication.php',
                    type:'POST',
                    data: data
                });
                
                alert("입력하신 휴대폰으로 인증번호를 전송하였습니다.");
            });
            
            $(document).on("click","#cheak_agree",function(){
             
//                 if ($("#smsinnumber").val().trim() == "") {
//                        alert("인증번호를 입력해 주세요.");
//                        $("#smsinnumber").focus();
//                         return false;
//                    }; 
                 
             });       
            
          


             $("#addr_search0").click(function() { 

		 //daum.postcode.load(function(){
		new daum.Postcode({
			oncomplete : function(data) {
				// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분
				// 도로명 주소의 노출 규칙에 따라 주소를 조합한다.
				// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
				var fullRoadAddr = data.roadAddress; // 도로명 주소 변수
				var extraRoadAddr = ''; // 도로명 조합형 주소 변수
				// 법정동명이 있을 경우 추가한다. (법정리는 제외)
				// 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
				if (data.bname !== '' && /[동|로|가]$/g.test(data.bname)) {
					extraRoadAddr += data.bname;
				}
				// 도로명, 지번 조합형 주소가 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
				if (extraRoadAddr !== '') {
					extraRoadAddr = ' (' + extraRoadAddr + ')';
				}
				// 도로명, 지번 주소의 유무에 따라 해당 조합형 주소를 추가한다.
				if (fullRoadAddr !== '') {
					fullRoadAddr += extraRoadAddr;
				}
				// 우편번호와 주소 정보를 해당 필드에 넣는다.
				// document.getElementById('sample4_postcode').value =
				// data.zonecode; //5자리 새우편번호 사용
				document.getElementById('addr_text').value = fullRoadAddr;
				// document.getElementById('sample4_jibunAddress').value =
				// data.jibunAddress;
				// return;
			}
		}).open();
		// });
		return;

	});
        
        //사은품 선택시 확인질문
        $('.sct_li').click(function(){
            var id_check = $(this).attr("id");
            $('#product_ck').val(id_check);
            var img_check = $(this).find('img').attr("src");
            $(".cheak_pd_img").find('img').attr("src", img_check);
            var name_check = $(this).find('img').attr("alt");
            $('.cheak_pd_p').text(name_check);
            $(this).find('input').prop("checked", true);
            
            var li_index = $(this).index();
            if(li_index == 0){
                //alert('1번이 선택되었습니다');
                $('#h_sub_1').prop("checked", true);
            }else if(li_index == 1){
                //alert('2번이 선택되었습니다');
                $('#h_sub_2').prop("checked", true);
            }else if(li_index == 2){
                //alert('3번이 선택되었습니다');
                $('#h_sub_3').prop("checked", true);
            }else if(li_index == 3){
                //alert('4번이 선택되었습니다');
                $('#h_sub_4').prop("checked", true);
            }
            
        });
        
        
        //저장 선택시 값검증
        $('.fin').click(function(){
           if ($("input#lender_name").val().trim() == "") {
                        alert("이름을 입력해 주세요.");
                        $("input#lender_name").focus();
                         return false;
                    } 
           if ($("input#lender_phone").val().trim() == "") {
                        alert("전화번호를 입력해 주세요.");
                        $("input#lender_phone").focus();
                         return false;
                    } 
//           if ($("#smsinnumber").val().trim() == "") {
//                        alert("인증번호를 입력해 주세요.");
//                        $("#smsinnumber").focus();
//                         return false;
//                    };
           if ($("input#addr_text").val().trim() == "") {
                        alert("주소를 입력해 주세요.");
                        $("input#addr_text").focus();
                         return false;
                    }
           if ($("input#addradd_text").val().trim() == "") {
                        alert("상세주소를 입력해 주세요.");
                        $("input#addradd_text").focus();
                         return false;
                    }

//            if( $("input#smsinnumber").val() != $("input#smsNumber").val() ) {
//                alert("인증번호가 일치하지 않습니다.");
//                $("input#smsinnumber").focus();
//                return false;
//                $('#h_sub_1').prop("checked", true);
//            }
            if (!($("#h_sub_1").attr("checked") || $("#h_sub_2").attr("checked") || $("#h_sub_3").attr("checked") || $("#h_sub_4").attr("checked"))) {
                     alert("제품을 선택해 주세요.");
                        $("input.pd_ck").focus();
                         return false;
            }

            if (confirm('제품을 신청하시겠습니까?')) {
                document.getElementById('frm').submit(); 
            } else {
                return false;
            }
            
        });
        
        
      
//숫자만 입력
        function onlyNumber(event) {
	event = event || window.event;
	var keyID = (event.which) ? event.which : event.keyCode;
	if ((keyID >= 48 && keyID <= 57) || (keyID >= 96 && keyID <= 105) || keyID == 8 || keyID == 46 || keyID == 37 || keyID == 39 || keyID == 9)
		return;
	else
		return false;
        }
      
// 숫자이외의 문자 지움
        function removeChar(event) {
                event = event || window.event;
                var keyID = (event.which) ? event.which : event.keyCode;
                if (keyID == 8 || keyID == 46 || keyID == 37 || keyID == 39 || keyID == 9)
                        return;
                else
                        event.target.value = event.target.value.replace(/[^0-9]/g, "");


                document.getElementById('bite').value = String(document.getElementById('bite').value).replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');

                document.getElementById('contract_text').value = String(document.getElementById('contract_text').value).replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');


                }

	</script>

