<?php
    include_once('./dbConn.php');
    include_once('./dbConn2.php');
    
    set_time_limit(0);
    
    // 지급된 수당을 다시 환수하는 로직
    // 지정한 ID 포함 산하 전체를 지정한 날짜에 지급된 수당 금액 만큼 다시 환수한다
    
    
    
    $mb_id = "00093874"; // 지정한 계정 포함 산하 전체를 환수하게 됨
    $date = '2020-09-05'; // 환수할 수당 일자
    $totalMoney = 0;
    
    echo $mb_id . " 계정 포함 산하 전체 ";
    echo $date . " 지급된 수당을 ";
    echo date("Y-m-d") . "에 환수 하였습니다.<br><br>";
    
    action($mb_id, $date);
    
    if (mysqli_multi_query($connection, "CALL SP_TREE('{$mb_id}');SELECT * FROM genealogy_tree WHERE rootid = '{$mb_id}' order by lv")) {
        mysqli_store_result($connection);
        mysqli_next_result($connection);
        do {
            /* store first result set */
            if ($result = mysqli_store_result($connection)) {
                while ($row2 = mysqli_fetch_array($result)) {
                    action($row2["mb_id"], $date);
                }
                mysqli_free_result($result);
            }
        } while (mysqli_next_result($connection));
    }
    echo "환수 금액 총합 : " . $totalMoney . "원<br>";
    echo "완료";
    
    
    
    function action( $id, $date ) {
        global $totalMoney;
        $row = mysql_fetch_array(mysql_query("select * from dayPoint where date like '{$date}%' and mb_id = '{$id}' and way = 'autoVMC'"));
        if( $row["VMC"] != "" ) {
            $totalMoney += $row["VMC"];
//            mysql_query("insert into dayPoint set mb_id = '{$id}', VMC = -{$row["VMC"]}, date = now(), way = 'admin2;그룹 정기 환수'") or die("ERROR2");
//            mysql_query("update g5_member set VMC = VMC - {$row["VMC"]} where mb_id = '{$id}'") or die("ERROR3");            
            
            echo "환수 완료 ID : " . $id . " / 환수 금액 : " . number_format($row["VMC"]) . "VMC<br>";
        }
    }
?>