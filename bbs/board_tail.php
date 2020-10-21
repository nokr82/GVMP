<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 게시판 관리의 하단 파일 경로
if (G5_IS_MOBILE) {
    echo stripslashes($board['bo_mobile_content_tail']);
    // 모바일의 경우 설정을 따르지 않는다.
    //include_once(G5_BBS_PATH.'/_tail.php');
    //푸터에 카피라이트겹침문제로 인한 주석처리
} else {
    echo stripslashes($board['bo_content_tail']);
    if(is_include_path_check($board['bo_include_tail'])) {  //파일경로 체크
        @include ($board['bo_include_tail']);
    } else {    //파일경로가 올바르지 않으면 기본파일을 가져옴
        include_once(G5_BBS_PATH.'/_tail.php');
    }
}
?>