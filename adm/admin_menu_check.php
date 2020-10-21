<?php

// 페이지 추가될떄 권한체크하는 함수
//$val은 admin_menu의 sub_menu를 불러온다 즉 페이지의 $sub_menu로 구분을한다

function menu_check($val,$mb_id) {
    if ($mb_id==''){
        return alert('접근권한이 없습니다.');
    }
    $count= 0;
    $sql = "SELECT
              count(*) as cnt
FROM
    admin_auth AS a
        INNER JOIN
    admin_menu as am
    on a.menu_id = am.no
    where 
    a.mb_id = '{$mb_id}' and
    am.sub_menu = {$val} limit 1";
    $row = sql_fetch($sql);
    $total_count = $row['cnt'];
   
    
    
    if ($total_count == 0) {
       return alert('접근권한이 없습니다');
    } else {
        return ;
    }
}

?>