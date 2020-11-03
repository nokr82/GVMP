<meta charset="utf-8">
<?php
include_once('./dbConn.php');


set_time_limit(0);


// 기준 날짜로 지정한 날짜에 수당이 미지급 됐는 지 확인해서
// 지급을 처리하는 로직
// 2020-10-12 이정현





$standardDate = '2020-10-16'; // 기준 날짜
echo $standardDate."<br>";
$standardDate2 = date("Y-m-d",strtotime("+1 day", strtotime($standardDate))); // 기준 날짜 하루 후

$re = mysql_query("select * from g5_member where accountType = 'VM' and mb_no > 30");

$time1 = strtotime($standardDate);
while( $row = mysql_fetch_array($re) ) {
    //$row2 = mysql_fetch_array(mysql_query("select * from dayPoint where mb_id = '{$row["mb_id"]}' and way = 'newRenewal' limit 1"));
    $row2 = mysql_fetch_array(mysql_query("select * from dayPoint where mb_id = '{$row["mb_id"]}' and way = 'newRenewal' or way = 'vmJoin2' limit 1"));
    if( $row2["mb_id"] != "" ) {
        $time2 = strtotime(date("Y-m-d", strtotime($row2["date"])));

        if( $time1 > $time2 ) {
            $row3 = mysql_fetch_array(mysql_query("select * from dayPoint where mb_id = '{$row["mb_id"]}' and way = 'autoVMC' and date like '{$standardDate}%' limit 1"));
            if( $row3["mb_id"] == "" ) {
                $row4 = mysql_fetch_array(mysql_query("select * from rank_history where mb_id = '{$row["mb_id"]}' and datetime < '{$standardDate2}' order by id desc limit 1"));

                if( $row4["change_rank"] == "VM" ) {
                    $row4["change_rank"] = "MASTER";
                }

                $row5 = mysql_fetch_array(mysql_query("select * from jobAllowance where accountRank = '{$row4["change_rank"]}'"));

                //수당이 없을시 패스한다.
                if(!$row5["Allowance"]){
                    echo "패스회원 ->".$row["mb_id"] . " / VM가입일:" . date("Y-m-d", strtotime($row2["date"])) . " / 직급:" . $row4["change_rank"] . " / 수당:" . $row5["Allowance"] . "<br>";
                }else{
                mysql_query("insert into dayPoint set mb_id = '{$row["mb_id"]}', VMC = {$row5["Allowance"]}, date = '{$standardDate} 23:59:59', way = 'autoVMC'") or die("ERROR1");
                mysql_query("update g5_member set VMC = VMC + {$row5["Allowance"]} where mb_id = '{$row["mb_id"]}'") or die("ERROR2");
                echo "입력회원 ->". $row["mb_id"] . " / VM가입일:" . date("Y-m-d", strtotime($row2["date"])) . " / 직급:" . $row4["change_rank"] . " / 수당:" . $row5["Allowance"] . "<br>";
                }

            }
        }

    }

}


?>
완료
