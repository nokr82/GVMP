<?php
$link = mysqli_connect("192.168.0.30", "root", "Neungsoft1!", "gyc5");

set_time_limit(0);

// 1팀, 2팀 인원수 증가 또는 감소를 처리하는 로직
$start = get_time();
//카운트 프로세스 돌아가고있는지 아닌지 판별 동우작성
$stat_row = mysqli_fetch_array(mysqli_query($link, "select * from count_stat where id = '1'"));
if ($stat_row['stat'] == 'START') {
    //돌아가고있는중이면 프로세스를 돌리지않음
    exit();
}
//돌아가고있지 않다면 START로 바꾸고 진행
mysqli_query($link, "UPDATE count_stat SET stat = 'START',datetime = CURRENT_TIMESTAMP WHERE id = 1");



mysqli_multi_query($link, "CALL SP_TREE_R('', 1)") or die(mysqli_error($link));


//$check = true;
//while ($check) {
//    // 증가 로직
//    $PCL_row = mysqli_fetch_array(mysqli_query($link, "select * from plusCountListTBL order by datetime limit 1"));
//
//    if ($PCL_row["mb_id"] != "") {
//        mysqli_query($link, "delete from plusCountListTBL where mb_id = '{$PCL_row["mb_id"]}'") or die("ERROR2");
//        $sponCheckRow = mysqli_fetch_array(mysqli_query($link, "select * from genealogy where mb_id = '{$PCL_row["mb_id"]}'"));
//        if ($sponCheckRow["sponsorID"] != "" && $sponCheckRow["sponsorID"] != "99999999" && $sponCheckRow["sponsorID"] != "00000000") { // 후원인 정보가 존재하면 참
//            mysqli_multi_query($link, "call sp_count('{$PCL_row["mb_id"]}', +{$PCL_row["num"]})") or die("ERROR3");
//            do {
//                if ($result = mysqli_store_result($link)) {
//                    while ($row = mysqli_fetch_row($result))
//                        mysqli_free_result($link);
//                }
//            } while (mysqli_next_result($link));
//        }
//    } else {
//        $check = false;
//    }
//}
//
//
//$check = true;
//while ($check) {
//    // 감소 로직
//    $PCL_row = mysqli_fetch_array(mysqli_query($link, "select * from minusCountListTBL order by datetime limit 1"));
//
//
//    if ($PCL_row["mb_id"] != "") {
//        mysqli_query($link, "delete from minusCountListTBL where mb_id = '{$PCL_row["mb_id"]}'") or die("ERROR2");
//        $sponCheckRow = mysqli_fetch_array(mysqli_query($link, "select * from genealogy where mb_id = '{$PCL_row["mb_id"]}'"));
//        if ($sponCheckRow["sponsorID"] != "" && $sponCheckRow["sponsorID"] != "99999999" && $sponCheckRow["sponsorID"] != "00000000") { // 후원인 정보가 존재하면 참
//            mysqli_multi_query($link, "call sp_count('{$PCL_row["mb_id"]}', -{$PCL_row["num"]})") or die("ERROR3");
//
//
//            do {
//                if ($result = mysqli_store_result($link)) {
//                    $row = mysqli_fetch_row($result);
//                }
//            } while (mysqli_next_result($link));
//        }
//    } else {
//        $check = false;
//    }
//}




//더이상없다면 END로 바꿈
mysqli_query($link, "UPDATE count_stat SET stat = 'END',datetime = CURRENT_TIMESTAMP WHERE id = 1");




mysqli_close($link);

$end = get_time();
$time = $end - $start;
echo $time . 's 끝<br>';


function get_time()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

?>