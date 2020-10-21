<?php
include_once('./dbConn.php');

//최초여부체크
function rank_history($m_id, $rank)
{
    $sql = "select EXISTS (select * from rank_history where mb_id = '{$m_id}' and change_rank = '{$rank}') as success";
    $row_r = mysql_fetch_array(mysql_query($sql));

    if ($row_r['success'] > 0) {
        return 'N';
    } else {
        return 'Y';
    }
}

/////////////////////////////////////////////////////////////////////동우작성 0203 직급변경함수///////////////////////////////////////////////////////////
function rank_com($m_id, $rank, $public_rank)
{

    //회원가입시
    if ($rank == 'CU' && $public_rank == 'VM') {
        //기존랭크 VM에서 CU가 되는 경우
        $first_yn = 'N';
        $sql = "insert into rank_history set mb_id = '{$m_id}',rank = '{$public_rank}',change_rank =  '{$rank}',first_check='{$first_yn}',datetime=current_timestamp()";
        mysql_query($sql);
        return;
    }elseif ($public_rank=='SM'&&$rank=='VM'){
        //기존랭크 SM 에서 VM이 되는경우
        $first_yn = 'Y';
        $sql = "insert into rank_history set mb_id = '{$m_id}',rank = '{$public_rank}',change_rank =  '{$rank}',first_check='{$first_yn}',datetime=current_timestamp()";
        mysql_query($sql);
        return;
    }elseif ($public_rank=='SM'&&$rank=='CU'){
        //기존랭크 SM 에서 CU가 되는경우 이경우는 기록하지 않음
        return;
    }

    $sql = "select * from rankOrderBy where rankAccount = '{$rank}'";
    $query = mysql_query($sql);
    //새로운랭크의 랭크 넘버를 가져옴
    $rank_count = mysql_fetch_array($query);

    //혹시 모를 오류대비 이걸지우면 값이 비었을경우 엄청난반복문이돌아감
    if (empty($rank_count)) {
        $rank_count['orderNum'] = 14;
    }

    if ($public_rank == 'CU') {
        //기존랭크가 CU일 경우 오더넘버를 찾을수 없으므로 14로 고정
        $min_rank_count['orderNum'] = 14;
    } else {
        //CU가 아니면 기존랭크의 오더넘버찾기
        $sql5 = "select * from rankOrderBy where rankAccount = '{$public_rank}'";
        $query5 = mysql_query($sql5);
        $pu_rank_count = mysql_fetch_array($query5);
        $min_rank_count['orderNum'] = $pu_rank_count['orderNum'];
    }

    //새로운랭크넘버가 기존랭크넘버와  같거나 크면 리턴 2단누락
    if ($rank_count['orderNum'] >= $min_rank_count['orderNum']) {
        //최초여부
        $first_yn = 'N';
        for ($i = $min_rank_count['orderNum'] + 1; $i <= $rank_count['orderNum']; $i++) {
            //배열을 체크해서 없다면 인서트
            //바뀔랭크를 가져옴
            $sql3 = "select * from rankOrderBy where orderNum = '{$i}'";
            $query3 = mysql_query($sql3);
            $ar_vm = mysql_fetch_array($query3);
            if (empty($ar_vm)) {
                $ar_vm['rankAccount'] = 'CU';
            }

//            $sql = "insert into rank_history(mb_id,rank,change_rank,first_check,datetime) value ('{$m_id}','{$public_rank}','{$ar_vm['rankAccount']}','{$first_yn}',current_timestamp())";
            $sql = "insert into rank_history set mb_id = '{$m_id}',rank = '{$public_rank}',change_rank =  '{$ar_vm['rankAccount']}',first_check='{$first_yn}',datetime=current_timestamp()";
            mysql_query($sql);
            //순차적으로 바뀌기 위해 기존랭크를 바뀐랭크로 저장
            $public_rank = $ar_vm['rankAccount'];
        }
        return;
    }

    //그사람의 변경이력을 orderNum으로 묶어서 넘버를 배열에 넣음  수정
    $sql2 = "select o.orderNum from rank_history as r inner join rankOrderBy as o on r.change_rank = o.rankAccount and mb_id = '{$m_id}' group by o.orderNum";
    $query2 = mysql_query($sql2);
    $arr_num = [];
    while ($row = mysql_fetch_array($query2)) {
        array_push($arr_num, $row['orderNum']);
    }

    //기존랭크의 오더넘버찾기
    $sql5 = "select * from rankOrderBy where rankAccount = '{$public_rank}'";
    $query5 = mysql_query($sql5);
    $pu_rank_count = mysql_fetch_array($query5);
    if (empty($pu_rank_count)) {
        $pu_rank_count['orderNum'] = 14;
    }

    //이것은 높은랭크일경우에만 돌아감 2단승급
    for ($i = $pu_rank_count['orderNum'] - 1; $i >= $rank_count['orderNum']; $i--) {
        //배열을 체크해서 없다면 인서트
        //랭크정보에서 해당직급가져오기
        $sql3 = "select * from rankOrderBy where orderNum = '{$i}'";
        $query3 = mysql_query($sql3);
        $ar_vm = mysql_fetch_array($query3);
        if (!in_array($i, $arr_num)) {
            //DB에 승급정보가 없을때
            //새로들어오는랭크가 기존랭크보다 낮으면 최초가아님
            if ($pu_rank_count['orderNum'] > $ar_vm['orderNum']) {
                $first_yn = 'Y';
            } else {
                $first_yn = 'N';
            }
//            $sql = "insert into rank_history(mb_id,rank,change_rank,first_check,datetime) value ('{$m_id}','{$public_rank}','{$ar_vm['rankAccount']}','{$first_yn}',current_timestamp())";
            $sql = "insert into rank_history set mb_id = '{$m_id}',rank = '{$public_rank}',change_rank =  '{$ar_vm['rankAccount']}',first_check='{$first_yn}',datetime=current_timestamp()";
            mysql_query($sql);
            $public_rank = $ar_vm['rankAccount'];
        } else {
            //DB에 승급정보가 있을떄
            $first_yn = 'N';
//            $sql = "insert into rank_history(mb_id,rank,change_rank,first_check,datetime) value ('{$m_id}','{$public_rank}','{$ar_vm['rankAccount']}','{$first_yn}',current_timestamp())";
            $sql = "insert into rank_history set mb_id = '{$m_id}',rank = '{$public_rank}',change_rank =  '{$ar_vm['rankAccount']}',first_check='{$first_yn}',datetime=current_timestamp()";
            mysql_query($sql);
            $public_rank = $ar_vm['rankAccount'];
        }
    }

}




?>