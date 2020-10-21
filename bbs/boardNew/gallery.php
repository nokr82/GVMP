<?php
include_once '/var/www/html/gvmp/myOffice/dbConn.php';

if ($_GET['search'] != '') {
    $where = "where wr_subject like '%{$_GET['search']}%'";
}

$rpp = 10;  # record/page
$adjacents = 3; # 양 옆에 표시될 페이지 수

if ($_GET['page'] == '') {
    $_GET['page'] = 1;
}

if ($board['bo_table'] == 'free') {
    $table = 'g5_write_free';
} elseif ($board['bo_table'] == 'youtube') {
    $table = 'g5_write_youtube';
}

$page = $_GET['page'];
$sLimit = ($page - 1) * $rpp;

$sql = "select count(*) as cnt from {$table} {$where} order by wr_id desc";
$cnt = mysql_query($sql);
$cnt = mysql_fetch_array($cnt);

$total = $cnt['cnt'];


$sql = "select * from {$table} {$where} order by wr_id desc limit {$sLimit},{$rpp}";
$result = mysql_query($sql);


//관리자 리스트페이지 페이징
function doPaging($page, $total, $rpp, $adjacents = 1, $url, $param)
{

    if (($last = ceil($total / $rpp)) > 1) {
        if ($last < ($defc = 1 + $adjacents * 2)) {
            $i = 1;
            $cond = $last;
        } elseif ($last >= $defc) {
            if ($page < 2 + $adjacents) {
                $i = 1;
                $cond = $defc / 2 + $page;
                $laston = true;
            } elseif ($page >= $last - $defc / 2) {
                $i = $last + 1 - $defc;
                $cond = $last;
                $firston = true;
            } elseif ($page >= 2 + $adjacents) {
                $i = $page - $adjacents;
                $cond = $page + $adjacents;
                $firston = true;
                $laston = true;
            }
        }
        echo '<div class="page_list">
                <table class="pager">
                    <tbody>
                    <tr>
                        <td class="prev"  " >
                        <a href="' . $url . $param . '">
                            <i class="icon_prev"><span class="blind">이전 페이지 보기</span></i>
                            </a>
                        </td>';
        for ($i; $i <= $cond; $i++) {
            if ($i == $page) {
                echo '<td class="current_page"><span class="txt_page">' . $i . '</span></td>';
            } else {
                echo ' <td><a href="' . $url . $param . $i . '"><span class="txt_page">' . $i . '</span></a></td> ';
            }
        }
        echo '<td class="next">
                           <a href="' . $url . $param . $last . '"> <i class="icon_next"><span class="blind">다음 페이지 보기</span></i></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>';
    } else {
        return;
    }
}

function get_youtube_thumb($url)
{
    $video_id = str_replace("https://www.youtube.com/watch?v=", "", $url);
    return $video_id;
}

?>
<h2 class="blind">유튜브 게시판</h2>

<script src="http://gvmp.company/js/jquery-1.8.3.min.js"></script>
<link rel="stylesheet" href="http://gvmp.company/theme/everyday/mobile/skin/board/blog/style.css?ver=171222">
<link rel="stylesheet" href="../../css/normalize.css">
<link rel="stylesheet" href="../../css/board.css">
<body>
<div class="container_bbs">
    <div class="head_area">
        <h2 class="bbs_tit">
            <div class="board_type">
                <a href="/bbs/board.php?bo_table=free" class="bo_subject <?php if ($board['bo_table'] == 'free') echo 'on' ?>">파일 게시판</a>
                <a href="/bbs/board.php?bo_table=youtube" class="bo_subject  <?php if ($board['bo_table'] == 'youtube') echo 'on' ?>">유튜브 게시판</a>
            </div>
        </h2>
        <div class="search_box">
            <form class="js-bbssearch-form">
                <div class="inp_box">
                    <input type="hidden" name="bo_table" value="<?= $board['bo_table'] ?>">
                    <input type="text" name="search" placeholder="검색어를 입력해주세요"/>
                    <i class="icon_zoom" onclick="$('.js-bbssearch-form').submit();"><input type="submit" hidden/><span class="blind">돋보기 아이콘</span></i>
    
                </div>
            </form>
        </div>
    </div>  
    <div class="cont_area">
        <div class="bbs_gallery_area">
            <?php
                $cnt = 0;
                echo "<ul class=\"bbs_grry\">";
               
                while ($row = mysql_fetch_array($result)) {
                    $cnt++;
                    $date = substr($row['wr_datetime'], 0, 10);
                    $y_url = get_youtube_thumb($row['wr_10']);
                    $addr = 'http://gvmp.company/bbs/board.php?bo_table=' . $board['bo_table'] . '&wr_id=' . $row['wr_id'];
                    echo " <li class=\"bbs_notice_li\">
                    <a href=\"{$addr}\" class=\"fst_img_area\">
                   <img class=\"first_postimg\" src=\"https://img.youtube.com/vi/{$y_url}/sddefault.jpg\" alt=\"첫번째 사진\">
                    </a>
                    <dl class=\"simple_info_area\">
                    <dt class=\"tit_dt\" >{$row['wr_subject']}</dt>
                        <dd class=\"wrap_post_info\">                        
                            <div class=\"date_area\">
                                <i class=\"icon_clock\"><span class=\"blind\">시계 아이콘</span>
                                </i>
                                <span class=\"date_txt\">$date</span>
                            </div>
                            <div class=\"view_area\">
                                <i class=\"icon_eye\"><span class=\"blind\">조회수 눈 아이콘</span></i>
                                <span class=\"view_txt\">조회: {$row['wr_hit']}</span>
                            </div>
                        </dd>
                    </dl>
                </li>";
                }

                echo "</ul>";
            ?> 
            

            <?php
                if ($cnt == 0) {
                    echo "<div class=\"non_post\">게시물이 없습니다</div>";
                }
            ?>
        </div>
        <div class="tbl_ft_area">
            <div class="wrap_btn">
                <span class="btn_write"
                      onclick="location.href='./write.php?bo_table=<?= $board['bo_table'] ?>'">글쓰기</span>
            </div>
            <?php
            doPaging($page, $total, $rpp, $adjacents, $_SERVER['SCRIPT_NAME'] . '?bo_table=' . $board['bo_table'], '&page=');
            ?>

        </div>
    </div>
</div>
<script>
    $(document).on('click', '.pager .txt_page', function () {//페이지 표시
        $(this).parent('td').addClass('current_page').siblings('td').removeClass('current_page');
    });
</script>
</body>

</html>