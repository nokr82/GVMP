<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);

if( $_GET['bo_table'] == "used" ){ 
    add_stylesheet('<link rel="stylesheet" href="/myOffice/css/used_market.css">', 1);
}

$gwuRow = mysql_fetch_array(mysql_query("select * from g5_write_used where wr_id = " . $_GET["wr_id"]));

?>
<script src="https://use.fontawesome.com/releases/v5.2.0/js/all.js"></script>
<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<?php if( !($_GET['bo_table'] == "used" )){ ?>
<!-- <div id="bo_v_table"><?php echo ($board['bo_mobile_subject'] ? $board['bo_mobile_subject'] : $board['bo_subject']); ?></div> -->
    <div class="btn_top top"> 
        <?php if ($reply_href) { ?><a href="<?php echo $reply_href ?>" class="btn_b01"><i class="fa fa-reply" aria-hidden="true"></i> 답변</a><?php } ?>
        <?php if ($write_href) { ?><a href="<?php echo $write_href ?>" class="btn_b02 btn"><i class="fa fa-pencil" aria-hidden="true"></i> 글쓰기</a><?php } ?>

    </div>
<?php } ?>

<?php
$filename = $view['file'][0]['file'];
$filepath = G5_DATA_PATH.'/file/'.$bo_table;
$filesrc = G5_DATA_URL.'/file/'.$bo_table.'/'.$filename;
$thumb = thumbnail($filename, $filepath , $filepath , 600, 600, false, true);
$thumbsrc = G5_DATA_URL.'/file/'.$bo_table.'/'.$thumb;
?>

<!--중고장터 타이틀 출력-->
<?php if( $_GET['bo_table'] == "used" ){ ?>
<div class="used_head bo_v_head">
    <h2><a href="http://gvmp.company/bbs/board.php?bo_table=used">중고장터</a></h2>
</div>
<?php } ?>



<article id="<?php if( $_GET['bo_table'] == "used" ){ ?>used_market_bo_v<?php } else {?> bo_v<?php }?>" style="width:<?php if( !($_GET['bo_table'] == "used") ){ echo $width; }?>">
    <header>
        <?php if( $_GET['bo_table'] == "used" ){ ?>
        <input type="hidden" id="mb_addr" value="<?php echo $view['seller_hp']; ?>"/>
        <div class="used_sum_info clearfix">
            <div class="img_box">
                <?php
                       echo '<img src="'.$thumbsrc.'" alt="'.$filename.'">'; 
                ?>
            </div>
            <div class="txt_box">
                <div class="bo_v_tit"><?php echo cut_str(get_text($view['wr_subject']), 70); // 글제목 출력 ?></div>
                <div class="bo_v_sub_tit"><?php echo $view['summaryInfo']; ?></div>
                <div class="bo_v_utill clearfix">
                    <div class="bo_v_info">
                        <h2>페이지 정보</h2>
                        <div class="gall_info_col wish_btn"><span class="sound_only">찜 개수</span><span class="wish_list"><i class="fas fa-heart"></i><?php echo $view['heartNum']; ?></span></div>
                        <div class="gall_info_col used_seller"><span class="sound_only">작성자 </span><i class="far fa-grin"></i><?php echo $view['name'] ?><span class="ip"><?php if ($is_ip_view) { echo "&nbsp;($ip)"; } ?></span></div>
                        <div class="gall_info_col"><span class="sound_only">조회</span><span><i class="fa fa-eye" aria-hidden="true"></i> <?php echo number_format($view['wr_hit']) ?>회</span></div>
                    </div>
                    <div class="bo_v_etc">
                        <a href="#none" class="mb_addr"><i><img src="/theme/everyday/mobile/skin/board/gallery/img/icon_exclamation.svg"></i>연락처</a>
                        <a href="#none" class="mb_wish off" onclick="wishFunc('<?=$member["mb_id"]?>', '<?=$_GET["wr_id"]?>')"><i><img src="/theme/everyday/mobile/skin/board/gallery/img/icon_heart_ccc.svg"></i>찜하기</a>
                    </div>
                </div>
            </div>
            <div class="bo_v_buy clearfix">
                    <div class="col_1">
                        <!--가격기입--> 
                        <strong>&#8361;</strong><strong> <?php echo number_format($view['price']); ?></strong>
                    </div>
                    <div class="col_2">
<?php
    if( $gwuRow["saleStatus"] == "결제대기" ) {
        ?> <a href="/myOffice/used_market_pay.php?wr_id=<?=$_GET["wr_id"]?>" class="btn_buy">구매하기</a> <?php
    } else if( $gwuRow["saleStatus"] == "결제완료" ) {
        if( $gwuRow["buyer_id"] == $member["mb_id"] ) {
            ?> <a href="#none" onclick="okFunc('<?=$_GET["wr_id"]?>','<?=$member["mb_id"]?>');" class="btn_deal">구매 확정</a> <?php
        } else {
            ?> <a href="#none" class="btn_deal">거래중</a> <?php
        }
        
    } else if( $gwuRow["saleStatus"] == "거래완료" ) {
        ?> <a href="#none" class="btn_complete">거래완료</a> <?php
    } else if( $gwuRow["saleStatus"] == "환불완료" ) {
        ?> <a href="#none" class="btn_complete">환불완료</a> <?php
    }
?>

                    </div>
             </div>
        </div>
        
        
        
        
        <?php } else { ?>

        <h2 id="bo_v_title">
            <?php if ($category_name) { ?>
            <span class="bo_v_cate"><?php echo $view['ca_name']; // 분류 출력 끝 ?></span> 
            <?php } ?>
            <span class="bo_v_tit">
            <?php
            echo cut_str(get_text($view['wr_subject']), 70); // 글제목 출력
            ?></span>
        </h2>
        <p><span class="sound_only">작성일</span><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo date("y-m-d H:i", strtotime($view['wr_datetime'])) ?></p>
    </header>

    <section id="bo_v_info">
        <h2>페이지 정보</h2>
        <span class="sound_only">작성자 </span><?php echo $view['name'] ?><span class="ip"><?php if ($is_ip_view) { echo "&nbsp;($ip)"; } ?></span>
        <span class="sound_only">조회</span><strong><i class="fa fa-eye" aria-hidden="true"></i> <?php echo number_format($view['wr_hit']) ?>회</strong>
        <span class="sound_only">댓글</span><strong><i class="fa fa-commenting-o" aria-hidden="true"></i> <?php echo number_format($view['wr_comment']) ?>건</strong>
    </section>
        <?php } ?>
    <div id="bo_v_top">
        <?php
        ob_start();
         ?>
        
        
        <?php if( !($_GET['bo_table'] == "used") ){ ?>
        <ul class="bo_v_left">
            <?php if ($update_href) { ?><li><a href="<?php echo $update_href ?>" class="btn_b01 btn"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> 수정</a></li><?php } ?>
            <?php if ($delete_href) { ?><li><a href="<?php echo $delete_href ?>" class="btn_b01 btn" onclick="del(this.href); return false;"><i class="fa fa-trash-o" aria-hidden="true"></i> 삭제</a></li><?php } ?>
            <?php if ($copy_href) { ?><li><a href="<?php echo $copy_href ?>" class="btn_admin btn" onclick="board_move(this.href); return false;"><i class="fa fa-files-o" aria-hidden="true"></i> 복사</a></li><?php } ?>
            <?php if ($move_href) { ?><li><a href="<?php echo $move_href ?>" class="btn_admin btn" onclick="board_move(this.href); return false;"><i class="fa fa-arrows" aria-hidden="true"></i> 이동</a></li><?php } ?>
            <?php if ($search_href) { ?><li><a href="<?php echo $search_href ?>" class="btn_b01 btn">검색</a></li><?php } ?>

        </ul>
        <?php } ?>

        <?php
        $link_buttons = ob_get_contents();
        ob_end_flush();
         ?>
    </div>

    <section id="bo_v_atc">
        <h2 id="bo_v_atc_title">본문</h2>

        <?php
        // 파일 출력
        $v_img_count = count($view['file']);
        if($v_img_count) {
            echo "<div id=\"bo_v_img\">\n";

            for ($i=0; $i<=count($view['file']); $i++) {
                if ($view['file'][$i]['view']) {
                    //echo $view['file'][$i]['view'];
                    echo get_view_thumbnail($view['file'][$i]['view']);
                }
            }

            echo "</div>\n";
        }
         ?>

        <div id="bo_v_con"><?php echo get_view_thumbnail($view['content']); ?></div>
        <?php//echo $view['rich_content']; // {이미지:0} 과 같은 코드를 사용할 경우 ?>

        <?php if ($is_signature) { ?><p><?php echo $signature ?></p><?php } ?>

        <?php if ( $good_href || $nogood_href) { ?>
        <div id="bo_v_act">
            <?php if ($good_href) { ?>
            <span class="bo_v_act_gng">
                <a href="<?php echo $good_href.'&amp;'.$qstr ?>" id="good_button"  class="bo_v_good"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i><br><span class="sound_only">추천</span><strong><?php echo number_format($view['wr_good']) ?></strong></a>
                <b id="bo_v_act_good">이 글을 추천하셨습니다</b>
            </span>
            <?php } ?>
            <?php if ($nogood_href) { ?>
            <span class="bo_v_act_gng">
                <a href="<?php echo $nogood_href.'&amp;'.$qstr ?>" id="nogood_button" class="bo_v_nogood"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i><br><span class="sound_only">비추천</span><strong><?php echo number_format($view['wr_nogood']) ?></strong></a>
                <b id="bo_v_act_nogood"></b>
            </span>
            <?php } ?>
        </div>
        <?php } else {
            if($board['bo_use_good'] || $board['bo_use_nogood']) {
        ?>
        <div id="bo_v_act">
            <?php if($board['bo_use_good']) { ?><span class="bo_v_good"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i><br><span class="sound_only">추천</span><strong><?php echo number_format($view['wr_good']) ?></strong></span><?php } ?>
            <?php if($board['bo_use_nogood']) { ?><span class="bo_v_nogood"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i><br><span class="sound_only">비추천</span> <strong><?php echo number_format($view['wr_nogood']) ?></strong></span><?php } ?>
        </div>
        <?php
            }
        }
        ?>

        <div id="bo_v_share">
            <?php if ($scrap_href) { ?><a href="<?php echo $scrap_href;  ?>" target="_blank" class=" btn_scrap" onclick="win_scrap(this.href); return false;"><i class="fa fa-thumb-tack" aria-hidden="true"></i> 스크랩</a><?php } ?>

            <?php
            include_once(G5_SNS_PATH."/view.sns.skin.php");
            ?>
        </div>
    </section>


    
    <?php
    if ($view['file']['count']) {
        $cnt = 0;
        for ($i=0; $i<count($view['file']); $i++) {
            if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view'])
                $cnt++;
        }
    }
     ?>

    <?php if($cnt) { ?>
    <section id="bo_v_file">
        <h2>첨부파일</h2>
        <ul>
        <?php
        // 가변 파일
        for ($i=0; $i<count($view['file']); $i++) {
            if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view']) {
         ?>
            <li>
                <a href="<?php echo $view['file'][$i]['href'];  ?>" class="view_file_download">
                    <i class="fa fa-download" aria-hidden="true"></i>
                    <strong><?php echo $view['file'][$i]['source'] ?></strong>
                    <?php echo $view['file'][$i]['content'] ?> (<?php echo $view['file'][$i]['size'] ?>)
                </a>
                <span class="bo_v_file_cnt"><?php echo $view['file'][$i]['download'] ?>회 다운로드</span> |
                <span>DATE : <?php echo $view['file'][$i]['datetime'] ?></span>
            </li>
        <?php
            }
        }
         ?>
        </ul>
    </section>
    <?php } ?>

    <?php
    if (count($view['link']) > 0) {
        $cnt = 0;

        for ($i=1; $i<=count($view['link']); $i++) {
            if( $view['link'][$i] == "" ) {
                continue;
            }
            $cnt++;
        }
    }
     ?>
    
    <?php if($cnt) { ?>
    <section id="bo_v_link">
        <h2>관련링크</h2>
        <ul>
        <?php
        // 링크
        $cnt = 0;
        for ($i=1; $i<=count($view['link']); $i++) {
            if ($view['link'][$i]) {
                $cnt++;
                $link = cut_str($view['link'][$i], 70);
         ?>
            <li>
                <a href="<?php echo $view['link_href'][$i] ?>" target="_blank">
                    <i class="fa fa-link" aria-hidden="true"></i>
                    <strong><?php echo $link ?></strong>
                </a>
                <span class="bo_v_link_cnt"><?php echo $view['link_hit'][$i] ?>회 연결</span>
            </li>
        <?php
            }
        }
         ?>
        </ul>
    </section>
    <?php } ?>
    <br/>

    
    <?php if( !($_GET['bo_table'] == "used") ){ ?>
        <?php if ($prev_href || $next_href) { ?>
        <ul class="bo_v_nb">
            <?php if ($prev_href) { ?><li class="bo_v_prev"><a href="<?php echo $prev_href ?>"><i class="fa fa-caret-left" aria-hidden="true"></i> 이전글</a></li><?php } ?>
            <?php if ($next_href) { ?><li class="bo_v_next"><a href="<?php echo $next_href ?>">다음글 <i class="fa fa-caret-right" aria-hidden="true"></i></a></li><?php } ?>
            <li><a href="<?php echo $list_href ?>" class="btn_list"><i class="fa fa-list" aria-hidden="true"></i> 목록</a></li>

        </ul>
        <?php } ?>
    <?php } ?>
    
    <?php
    // 코멘트 입출력
    include_once(G5_BBS_PATH.'/view_comment.php');

     ?>
    
    
    <!-- 중고장터 수정, 목록, 삭제 버튼 모음-->
    <?php if( $_GET['bo_table'] == "used" ){ ?>
        <ul class="bo_v_left">
            <?php if ( ($update_href && $gwuRow["saleStatus"] == "결제대기") || $is_admin ) { ?><li><a href="<?php echo $update_href ?>" class="btn_b01 btn">수정</a></li><?php } ?>
            <li><a href="<?php echo $list_href ?>" class="btn_list">목록</a></li>
            <?php if ( ($delete_href && $gwuRow["saleStatus"] == "결제대기") || $is_admin ) { ?><li><a href="<?php echo $delete_href ?>" class="btn_b01 btn" onclick="del(this.href); return false;">삭제</a></li><?php } ?>
        </ul>
    <?php } ?>
    
    
    
    
</article>


<?php
include_once ($_SERVER['DOCUMENT_ROOT'].'/myOffice/alert.php');
?>



<script>

//연락처 클릭시
$('.mb_addr').on('click', function(){
    msgAlert('success','판매자 연락처 : '+$("#mb_addr").val(), function(){alertOff()});
});


// 구매 확정 버튼 클릭시
function okFunc(wr_id, mb_id) {
    msgAlert('confirm', '구매 확정하시겠습니까?', function() {alertOff(); tempFunc(wr_id, mb_id);}, function() {alertOff();} );
    
    function tempFunc( wr_id, mb_id ) {
        var data = "mb_id=" + mb_id + "&wr_id=" + wr_id;

        $.ajax({
            url:'/myOffice/purchaseConfirmation.php',
            type:'POST',
            data: data,
            async: false,
            success:function(result){
                if( result == "true" ) {
                    msgAlert('success','구매 확정하였습니다.', function(){alertOff(); window.location.href = '/bbs/board.php?bo_table=used'; });
                } else if(result == "false") {
                    msgAlert('error','구매 확정 에러', function(){alertOff();});
                }
            }
        });
    }
}



//찜하기 버튼 클릭시
function wishFunc( mb_id, wr_id ){
    
    if( $('.mb_wish').hasClass('on') ){
        $('.mb_wish').removeClass('on');
        $('.mb_wish').addClass('off');
        $('.mb_wish').find('img').attr('src','/theme/everyday/mobile/skin/board/gallery/img/icon_heart_ccc.svg');

        heartAjax( mb_id, wr_id, 'off' );
    } else if ( $('.mb_wish').hasClass('off') ){
        $('.mb_wish').removeClass('off');
        $('.mb_wish').addClass('on');
        $('.mb_wish').find('img').attr('src','/theme/everyday/mobile/skin/board/gallery/img/icon_heart_color.svg');
        
        heartAjax( mb_id, wr_id, 'on' );
    }
    
    function heartAjax(mb_id, wr_id, type ) {
        var data = "mb_id=" + mb_id + "&wr_id=" + wr_id + "&type=" + type;

        $.ajax({
            url:'/myOffice/heartNum_back.php',
            type:'POST',
            data: data
        });
    }
};

</script>





<script>
<?php if ($board['bo_download_point'] < 0) { ?>
$(function() {
    $("a.view_file_download").click(function() {
        if(!g5_is_member) {
            alert("다운로드 권한이 없습니다.\n회원이시라면 로그인 후 이용해 보십시오.");
            return false;
        }

        var msg = "파일을 다운로드 하시면 포인트가 차감(<?php echo number_format($board['bo_download_point']) ?>점)됩니다.\n\n포인트는 게시물당 한번만 차감되며 다음에 다시 다운로드 하셔도 중복하여 차감하지 않습니다.\n\n그래도 다운로드 하시겠습니까?";

        if(confirm(msg)) {
            var href = $(this).attr("href")+"&js=on";
            $(this).attr("href", href);

            return true;
        } else {
            return false;
        }
    });
});
<?php } ?>

function board_move(href)
{
    window.open(href, "boardmove", "left=50, top=50, width=500, height=550, scrollbars=1");
}
</script>

<!-- 게시글 보기 끝 -->

<script>
$(function() {
    $("a.view_image").click(function() {
        window.open(this.href, "large_image", "location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no");
        return false;
    });

    // 추천, 비추천
    $("#good_button, #nogood_button").click(function() {
        var $tx;
        if(this.id == "good_button")
            $tx = $("#bo_v_act_good");
        else
            $tx = $("#bo_v_act_nogood");

        excute_good(this.href, $(this), $tx);
        return false;
    });

    // 이미지 리사이즈
    $("#bo_v_atc").viewimageresize();
});

function excute_good(href, $el, $tx)
{
    $.post(
        href,
        { js: "on" },
        function(data) {
            if(data.error) {
                alert(data.error);
                return false;
            }

            if(data.count) {
                $el.find("strong").text(number_format(String(data.count)));
                if($tx.attr("id").search("nogood") > -1) {
                    $tx.text("이 글을 비추천하셨습니다.");
                    $tx.fadeIn(200).delay(2500).fadeOut(200);
                } else {
                    $tx.text("이 글을 추천하셨습니다.");
                    $tx.fadeIn(200).delay(2500).fadeOut(200);
                }
            }
        }, "json"
    );
}


$(function(){
    var userAgent, ieReg, ie;
    userAgent = window.navigator.userAgent;
    ieReg = /msie|Trident.*rv[ :]*11\./gi;
    ie = ieReg.test(userAgent);

    if(ie) {
    $(".img_box").each(function () {
        var $container = $(this),
            imgUrl = $container.find("img").prop("src");
        if (imgUrl) {
        $container.css("backgroundImage", 'url(' + imgUrl + ')').addClass("custom-object-fit");
        }
    });
    }

});



<?php
    $UHLRow = mysql_fetch_array(mysql_query("select * from used_heart_list where wr_id = {$_GET["wr_id"]} and mb_id = '{$member["mb_id"]}'"));
    
    if( $UHLRow["mb_id"] != "" ) {
        ?>
            $('.mb_wish').removeClass('off');
            $('.mb_wish').addClass('on');
            $('.mb_wish').find('img').attr('src','/theme/everyday/mobile/skin/board/gallery/img/icon_heart_color.svg');
        <?php
    }
?>





</script>