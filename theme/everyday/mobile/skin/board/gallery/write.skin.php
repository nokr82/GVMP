<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
if( $_GET['bo_table'] == "used" ){ 
    add_stylesheet('<link rel="stylesheet" href="/myOffice/css/used_market.css">', 1);
}
?>
<script src="https://use.fontawesome.com/releases/v5.2.0/js/all.js"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="../font/nanumsquare.css">
<link rel="stylesheet" href="/myOffice/css/used_market.css">

    <?php if( $_GET['bo_table'] == "used" ){ ?>
    <div class="used_head bo_w_head">
        <?php
            if( $_GET["w"] == "u" ) {
                ?> <h2>상품수정</h2> <?php
            } else {
                ?> <h2>상품등록</h2> <?php
            }
        ?>
    </div>
    <?php } ?>
<section id="<?php if( $_GET['bo_table'] == "used" ){ ?>used_market_bo_w<?php } else {?>bo_w<?php }?>">
    <form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <?php
    $option = '';
    $option_hidden = '';
    if ($is_notice || $is_html || $is_secret || $is_mail) {
        $option = '';
        
        if ($is_notice) {
            if( !($_GET['bo_table'] == "used") ){ // 중고장터 아닐때만 보이도록 수정 190918 이지양
                $option .= PHP_EOL.'<input type="checkbox" id="notice" name="notice" value="1" '.$notice_checked.'>'.PHP_EOL.'<label for="notice">공지</label>';
             }
        }

        if ($is_html) {
            if ($is_dhtml_editor) {
                $option_hidden .= '<input type="hidden" value="html1" name="html">';
            } else {
                $option .= PHP_EOL.'<input type="checkbox" id="html" name="html" onclick="html_auto_br(this);" value="'.$html_value.'" '.$html_checked.'>'.PHP_EOL.'<label for="html">html</label>';
            }
        }

        if ($is_secret) {
            if ($is_admin || $is_secret==1) {
                $option .= PHP_EOL.'<input type="checkbox" id="secret" name="secret" value="secret" '.$secret_checked.'>'.PHP_EOL.'<label for="secret">비밀글</label>';
            } else {
                $option_hidden .= '<input type="hidden" name="secret" value="secret">';
            }
        }

        if ($is_mail) {
            $option .= PHP_EOL.'<input type="checkbox" id="mail" name="mail" value="mail" '.$recv_email_checked.'>'.PHP_EOL.'<label for="mail">답변메일받기</label>';
        }
    }

    echo $option_hidden;
    ?>

    <div class="form_01">
        <h2 class="sound_only"><?php echo $g5['title'] ?></h2>

        <?php if ($is_category) { ?>
        <div class="bo_w_select">
            <label for="ca_name" class="sound_only">분류<strong>필수</strong></label>
            <select id="ca_name" name="ca_name" required>
                <option value="">선택하세요</option>
                <?php echo $category_option ?>
            </select>
        </div>
        <?php } ?> 
        
        <?php if( !($_GET['bo_table'] == "used") ){ ?>
            <?php if ($is_name) { ?>
            <div>
                <label for="wr_name" class="sound_only">이름<strong>필수</strong></label>
                <input type="text" name="wr_name" value="<?php echo $name ?>" id="wr_name" required class="frm_input full_input required" maxlength="20" placeholder="이름">
            </div>
            <?php } ?>

            <?php if ($is_password) { ?>
            <div>
                <label for="wr_password" class="sound_only">비밀번호<strong>필수</strong></label>
                <input type="password" name="wr_password" id="wr_password" <?php echo $password_required ?> class="frm_input full_input <?php echo $password_required ?>" maxlength="20" placeholder="비밀번호">
            </div>
            <?php } ?>

            <?php if ($is_email) { ?>
            <div>
                <label for="wr_email" class="sound_only">이메일</label>
                <input type="email" name="wr_email" value="<?php echo $email ?>" id="wr_email" class="frm_input full_input  email" maxlength="100" placeholder="이메일">
            </div>
            <?php } ?>

            <?php if ($is_homepage) { ?>
            <div>
                <label for="wr_homepage" class="sound_only">홈페이지</label>
                <input type="url" name="wr_homepage" value="<?php echo $homepage ?>" id="wr_homepage" class="frm_input full_input " placeholder="홈페이지">
            </div>
            <?php } ?>

            <?php if ($option) { ?>
            <div>
                <span class="sound_only">옵션</span>
                <?php echo $option ?>
            </div>
            <?php } ?>
         <?php } ?>
        
        <!--중고장터 글쓰기인 경우 상품명, 요약정보등 상단으로 위치하기-->
        <?php if( $_GET['bo_table'] == "used" ){ ?>
        <div class="bo_w_tit used_bo_w_list">
            <label for="wr_subject"><i class="fas fa-cart-plus"></i>상품명</label>
            <input type="text" name="wr_subject" value="<?php echo $write['wr_subject'];?>" id="wr_subject" required class="frm_input full_input required" onKeyUp="javascript:fnChkByte(this,'50')" placeholder="상품명을 입력해주세요.">
            <span id="byteInfo"><i class="byteInfo_limit">0</i>/ 50bytes</span>
        </div>
        <div class="bo_w_sub_tit used_bo_w_list">
            <label for="wr_sub_subject"><i class="fas fa-pencil-alt"></i>요약정보</label>
            <input type="text" name="wr_sub_subject" value="<?php echo $write['summaryInfo'];?>" id="wr_sub_subject" required class="frm_input full_input required" onKeyUp="javascript:fnChkByte(this,'50')" placeholder="요약정보를 입력해주세요.">
            <span id="byteInfo"><i class="byteInfo_limit">0</i>/ 50bytes</span>
        </div>
        <div class="bo_w_price used_bo_w_list">
            <label for="wr_price"><i class="fas fa-won-sign"></i>가격</label>
            <input type="text" name="wr_price" value="<?php echo number_format($write['price']);?>" id="wr_price" required class="frm_input full_input required" onkeyup="onlyNumberComma(this)" placeholder="제품가격을 입력해주세요.">
        </div>
        <div class="bo_w_mb_number used_bo_w_list">
            <label for="wr_mb_number"><i class="fas fa-phone"></i>연락처</label>
            <input type="text" name="wr_mb_number" value="<?php echo $write['seller_hp'];?>" id="wr_mb_number" required class="frm_input full_input required" onkeyup="hypenNumber(this)" placeholder="판매자분의 연락처를 입력해주세요.">
        </div>
        <div class="bo_w_file_wr used_bo_w_list">
            <label for="bf_file_<?php echo $i+1 ?>" class="lb_icon"><i class="material-icons">add_photo_alternate</i>메인사진</label>
            <input type="file" name="bf_file[]" id="bf_file_<?php echo $i+1 ?>" title="파일첨부 <?php echo $i+1 ?> : 용량 <?php echo $upload_max_filesize ?> 이하만 업로드 가능" class="frm_file ">
            <?php
                if( $_GET["w"] == "u" ) {
                    ?> <span id="byteInfo"><?php echo substr($file[0]['source'], 0, 10); ?>..</span> <?php
                }
            ?>
        </div>
        <?php } else {?>
        
        <div class="bo_w_tit">
            <label for="wr_subject" class="sound_only">제목<strong>필수</strong></label>
            <input type="text" name="wr_subject" value="<?php echo $subject ?>" id="wr_subject" required class="frm_input full_input required" placeholder="제목">
        </div>
        
        <?php } ?>

        <div>
            <label for="wr_content" class="sound_only">내용<strong>필수</strong></label>
            <?php if($write_min || $write_max) { ?>
            <!-- 최소/최대 글자 수 사용 시 -->
            <p id="char_count_desc">이 게시판은 최소 <strong><?php echo $write_min; ?></strong>글자 이상, 최대 <strong><?php echo $write_max; ?></strong>글자 이하까지 글을 쓰실 수 있습니다.</p>
            <?php } ?>
            <?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
            <?php if($write_min || $write_max) { ?>
            <!-- 최소/최대 글자 수 사용 시 -->
            <div id="char_count_wrap"><span id="char_count"></span>글자</div>
            <?php } ?>
        </div>


        <?php for ($i=1; $is_link && $i<=G5_LINK_COUNT; $i++) { ?>
        <div class="bo_w_link">
            <label for="wr_link<?php echo $i ?>"><i class="fa fa-link" aria-hidden="true"></i> <span class="sound_only">링크 #<?php echo $i ?></span><?php if( $_GET['bo_table'] == "used") { echo '관련링크';}?></label>
            <input type="url" name="wr_link<?php echo $i ?>" value="<?php if($w=="u"){echo$write['wr_link'.$i];} ?>" id="wr_link<?php echo $i ?>" class="frm_input wr_link" placeholder="관련링크를 입력해주세요.">
        </div>
        <?php } ?>

        <?php if( !($_GET['bo_table'] == "used") ){ ?>
            <?php for ($i=0; $is_file && $i<$file_count; $i++) { ?>
            <div class="bo_w_flie">
                <div class="file_wr">
                    <label for="bf_file_<?php echo $i+1 ?>" class="lb_icon"><i class="fa fa-download" aria-hidden="true"></i><span class="sound_only">파일 #<?php echo $i+1 ?></span></label>
                    <input type="file" name="bf_file[]" id="bf_file_<?php echo $i+1 ?>" title="파일첨부 <?php echo $i+1 ?> : 용량 <?php echo $upload_max_filesize ?> 이하만 업로드 가능" class="frm_file ">
                </div>
                <?php if ($is_file_content) { ?>
                <input type="text" name="bf_content[]" value="<?php echo ($w == 'u') ? $file[$i]['bf_content'] : ''; ?>" title="파일 설명을 입력해주세요." class="full_input frm_input" size="50" placeholder="파일 설명을 입력해주세요.">
                <?php } ?>

                <?php if($w == 'u' && $file[$i]['file']) { ?>
                <span class="file_del">
                    <input type="checkbox" id="bf_file_del<?php echo $i ?>" name="bf_file_del[<?php echo $i;  ?>]" value="1"> <label for="bf_file_del<?php echo $i ?>"><?php echo $file[$i]['source'].'('.$file[$i]['size'].')';  ?> 파일 삭제</label>
                </span>
                <?php } ?>

            </div>
            <?php } ?>
        <?php } ?>

        <?php if ($is_use_captcha) { //자동등록방지 ?>
        <div>
            <span class="sound_only">자동등록방지</span>
            <?php echo $captcha_html ?>
            
        </div>
        <?php } ?>

    </div>

    <div class="btn_top top">
        <input type="submit" value="<?php if( $_GET['bo_table'] == "used"){ if($_GET["w"]=="u") {echo '수정';} else {echo '등록';}} else { echo '작성완료';}?>" id="btn_submit" class="btn_submit" accesskey="s">
        <a href="./board.php?bo_table=<?php echo $bo_table ?>" class="btn_cancel">취소</a>
    </div>
    </form>
</section>



<script>

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

// 숫자와 하이픈만 입력가능하게 처리하는 부분 
function hypenNumber(obj){
    $(obj).keyup(function (event) {
        regexp = /[^0-9\-]/gi;
        value = $(this).val();
        $(this).val(value.replace(regexp, ''));
    });
}


//상품명 글자수 제한하기
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
        $(obj).siblings('#byteInfo').find('.byteInfo_limit').text(rbyte);
     }
}
    

</script>







<script>
<?php if($write_min || $write_max) { ?>
// 글자수 제한
var char_min = parseInt(<?php echo $write_min; ?>); // 최소
var char_max = parseInt(<?php echo $write_max; ?>); // 최대
check_byte("wr_content", "char_count");

$(function() {
    $("#wr_content").on("keyup", function() {
        check_byte("wr_content", "char_count");
    });
});

<?php } ?>
function html_auto_br(obj)
{
    if (obj.checked) {
        result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
        if (result)
            obj.value = "html2";
        else
            obj.value = "html1";
    }
    else
        obj.value = "";
}

function fwrite_submit(f)
{
    <?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>

    var subject = "";
    var content = "";
    $.ajax({
        url: g5_bbs_url+"/ajax.filter.php",
        type: "POST",
        data: {
            "subject": f.wr_subject.value,
            "content": f.wr_content.value
        },
        dataType: "json",
        async: false,
        cache: false,
        success: function(data, textStatus) {
            subject = data.subject;
            content = data.content;
        }
    });

    if (subject) {
        alert("제목에 금지단어('"+subject+"')가 포함되어있습니다");
        f.wr_subject.focus();
        return false;
    }

    if (content) {
        alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
        if (typeof(ed_wr_content) != "undefined")
            ed_wr_content.returnFalse();
        else
            f.wr_content.focus();
        return false;
    }

    if (document.getElementById("char_count")) {
        if (char_min > 0 || char_max > 0) {
            var cnt = parseInt(check_byte("wr_content", "char_count"));
            if (char_min > 0 && char_min > cnt) {
                alert("내용은 "+char_min+"글자 이상 쓰셔야 합니다.");
                return false;
            }
            else if (char_max > 0 && char_max < cnt) {
                alert("내용은 "+char_max+"글자 이하로 쓰셔야 합니다.");
                return false;
            }
        }
    }

    <?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함  ?>

    document.getElementById("btn_submit").disabled = "disabled";

    return true;
}
</script>
