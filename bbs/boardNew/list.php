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

if ($board['bo_table'] == 'free'){
    $table = 'g5_write_free';
}elseif ($board['bo_table'] == 'youtube'){
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

?>


<h2 class="blind">파일 게시판</h2>

<script src="http://gvmp.company/js/jquery-1.8.3.min.js"></script>
<link rel="stylesheet" href="http://gvmp.company/theme/everyday/mobile/skin/board/blog/style.css?ver=171222">
<link rel="stylesheet" href="../../css/normalize.css">
<link rel="stylesheet" href="../../css/board.css">

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
        <div class="bbs_tbl_area">
            <div class="bbs_tbl">
                <ul class="bbs_tbl_tr">
                    <li class="col_line_th">번호</li>
                    <li class="tit_th">제목</li>
                    <li class="writer_th">글쓴이</li>
                    <li class="date_th">날짜</li>
                    <li class="view_th">조회</li>
                </ul>

                <?php
                $cnt = 0;
                while ($row = mysql_fetch_array($result)) {
                    $cnt++;
                    $date = substr($row['wr_datetime'], 0, 10);
                    $addr = 'http://gvmp.company/bbs/board.php?bo_table='.$board['bo_table'].'&wr_id=' . $row['wr_id'];
//                    echo " <ul class=\"bbs_common_tr\">
//                        <li class=\"col_line_td\">{$row['wr_id']}</li>
//                        <li class=\"tit_td\">
//                            <div class=\"wrap_post_tit\">
//                                <strong onclick=\"location.href='{$addr}'\">{$row['wr_subject']}</strong>
//                                <div class=\"badge_area\">
//                                    <span class=\"badge_cnt\">{$row['wr_comment']}</span>
//                                </div>
//                            </div>
//                        </li>
//                        <li class=\"view_td\">{$row['wr_hit']}</li>
//
//                        <li class=\"writer_td\">{$row['wr_name']}</li>
//                        <li class=\"date_td\">{$date}</li>
//                    </ul>";
                    echo " <ul class=\"bbs_common_tr\">
                        <li class=\"col_line_td\">{$row['wr_id']}</li>
                        <li class=\"tit_td\">
                            <div class=\"wrap_post_tit\">
                                <strong onclick=\"location.href='{$addr}'\">{$row['wr_subject']}</strong>                               
                            </div>
                        </li>
                        <li class=\"writer_td\"><i class=\"icon_person\"><span class=\"blind\">사람 아이콘</span></i> {$row['wr_name']}</li>
                        <li class=\"date_td\"><i class=\"icon_clock\"><span class=\"blind\">시계 아이콘</span></i> {$date}</li>
                        <li class=\"view_td\"><i class=\"icon_eye\"><span class=\"blind\">조회수 눈 아이콘</span></i> {$row['wr_hit']}</li>
                    </ul>";
                }

                ?>

                <!--                    <ul class="bbs_common_tr">-->
                <!--                        <li class="col_line_td">14567</li>-->
                <!--                        <li class="tit_td">-->
                <!--                            <div class="wrap_post_tit">-->
                <!--                                <strong>지금 오세요</strong>-->
                <!--                                <i class="icon_video"><span class="blind">비디오 아이콘</span></i>-->
                <!--                            </div>-->
                <!--                        </li>-->
                <!--                        <li class="view_td">12345678</li>-->
                <!---->
                <!--                        <li class="writer_td">사람1</li>-->
                <!--                        <li class="date_td">2020.04.29</li>-->
                <!--                    </ul>-->

                <?php
                if ($cnt == 0) {
                    echo " <div class=\"non_post\">게시물이 없습니다.</div>";
                }
                ?>
            </div>
        </div>
        <div class="tbl_ft_area">
            <div class="wrap_btn">
                <span class="btn_write" onclick="location.href='./write.php?bo_table=<?=$board['bo_table']?>'">글쓰기</span>
            </div>
            <!--            <div class="page_list">-->
            <!--                <table class="pager">-->
            <!--                    <tbody>-->
            <!--                    <tr>-->
            <!--                        <td class="prev">-->
            <!--                            <i class="icon_prev"><span class="blind">이전 페이지 보기</span></i>-->
            <!--                        </td>-->
            <!--                        <td class="current_page"><span class="txt_page">1</span></td>-->
            <!--                        <td><span class="txt_page">2</span></td>-->
            <!--                        <td><span class="txt_page">3</span></td>-->
            <!--                        <td><span class="txt_page">4</span></td>-->
            <!--                        <td><span class="txt_page">5</span></td>-->
            <!--                        <td class="next">-->
            <!--                            <i class="icon_next"><span class="blind">다음 페이지 보기</span></i>-->
            <!--                        </td>-->
            <!--                    </tr>-->
            <!--                    </tbody>-->
            <!--                </table>-->
            <!--            </div>-->
            <?php
            doPaging($page, $total, $rpp, $adjacents, $_SERVER['SCRIPT_NAME'] . '?bo_table=' . $board['bo_table'], '&page=');
            ?>

        </div>
    </div>
</div>
</main>