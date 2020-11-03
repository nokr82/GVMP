<?php
include_once('./dbConn.php');

//이사람의 리뉴얼을 찾는다

//$to_day = date("Y-m-d");
//$this_day = '2020-10-05';
//
//
//$renewal_sql = "select a.mb_id,a.renewal,a.accountrank as memberRank,b.mb_id,b.change_rank,b.rank from g5_member a, rank_history b
//                where a.mb_id = b.mb_id
//                and b.datetime like '2020-10-05%'";
//$query = mysql_query($renewal_sql);
//echo $renewal_sql;
//while($row = mysql_fetch_array($query)){
//    //echo "@@@".$row['mb_id']."<br>";
//    rankCheck($row['mb_id'],$row['memberRank'],$row['change_rank'],$row['rank']);
//}
//
////00000183
//
////그날의 등급 체크하기
//function rankCheck($id,$m_rank,$c_rank,$f_rank){
//
//    echo "아이디는 : ".$id."회원등급은 : ".$m_rank."바뀐 등급은 :".$c_rank."전 등급은 : ".$f_rank."<br><hr>";
//
//    $sql = "select * from g5_member";
//
//
//}
