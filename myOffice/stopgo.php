<?php
    include_once('./dbConn.php');
    include_once('./dbConn2.php');
    
    // STOP & GO(가칭)
    
    $list = array(); // 제외할 기준들 목록 배열
    $listTotal = array(); // 제외할 회원들 목록 배열
    
    array_push($list, '00015495');
    array_push($list, '00079792');
    array_push($list, '00032421');
    array_push($list, '00031739');

    for( $i=0 ; $i<count($list) ; $i++ ) {
        array_push($listTotal, $list[$i]);
        if (mysqli_multi_query($connection, "CALL SP_TREE('{$list[$i]}');SELECT * FROM genealogy_tree WHERE rootid = '{$list[$i]}' order by lv")) {
            mysqli_store_result($connection);
            mysqli_next_result($connection);
            do {
                /* store first result set */
                if ($result = mysqli_store_result($connection)) {
                    while ($row2 = mysqli_fetch_array($result)) {
                        array_push($listTotal, $row2["mb_id"]);
                    }
                    mysqli_free_result($result);
                }
            } while (mysqli_next_result($connection));
        } else {
            echo mysqli_error($connection);
        }
    }
    
    $textTemp = "";
    for( $i=0 ; $i<count($listTotal) ; $i++ ) {
        $textTemp .= $listTotal[$i] . ",";
    }
    $textTemp = substr($textTemp , 0, -1);
    
    $datetime = "2020-05-06";
    $checkCount = 0;
    if (mysqli_multi_query($connection, "CALL SP_TREE('00010379');SELECT * FROM genealogy_tree WHERE rootid = '00010379' AND mb_id not in ({$textTemp}) order by lv")) {
        mysqli_store_result($connection);
        mysqli_next_result($connection);
        do {
            /* store first result set */
            if ($result = mysqli_store_result($connection)) {
                while ($row2 = mysqli_fetch_array($result)) {
                    $checkCount++;
                    
//                    mysql_query("update g5_member set renewal = date_add( renewal, interval + 4 day ) where mb_id = '{$row2["mb_id"]}'") or die("ERROR1");
                    
                    $autoVMC = mysql_fetch_array(mysql_query("select * from dayPoint where date like '{$datetime}%' and way = 'autoVMC' and mb_id = '{$row2["mb_id"]}'"));
                    if( $autoVMC["VMC"] > 0 ) {
//                        $autoVMC2 = mysql_fetch_array(mysql_query("select * from dayPoint where date like '{$datetime}%' and way = 'admin2;STOP&GO 수당 환수' and mb_id = '{$row2["mb_id"]}'"));
//                        if( $autoVMC2["mb_id"] == "" ) {
//                            echo $autoVMC2["mb_id"] . "안 빠짐<br>";
//                        }
//                        mysql_query("update g5_member set VMC = VMC - {$autoVMC["VMC"]} where mb_id = '{$row2["mb_id"]}'") or die("ERROR3");
//                        mysql_query("insert into dayPoint set mb_id = '{$row2["mb_id"]}', VMC = -{$autoVMC["VMC"]}, date='{$datetime} 16:00:00', way = 'admin2;STOP&GO 수당 환수'") or die("ERROR4");
//                        echo "포인트차감완료 / ";
                        
                        $dayVMM = mysql_fetch_array(mysql_query("select * from dayPoint where date like '{$datetime}%' and way = 'autoVMM' and VMM > 0 and mb_id = '{$row2["mb_id"]}'"));
                        if( $dayVMM["mb_id"] != "" ) {
                            mysql_query("update g5_member set VMM = VMM - {$dayVMM["VMM"]}, VMC = VMC + {$dayVMM["VMM"]} where mb_id = '{$dayVMM["mb_id"]}'") or die("ERROR10");
                            mysql_query("delete from dayPoint where mb_id = '{$dayVMM["mb_id"]}' and date like '{$datetime}%' and way = 'autoVMM'") or die("ERROR11");
                        }
                    }
                    
//                    echo $row2["mb_id"] . " / " . $row2["mb_name"] . " -> 처리 완료<br>";
                }
                mysqli_free_result($result);
            }
        } while (mysqli_next_result($connection));
    } else {
        echo mysqli_error($connection);
    }
    
//    echo "제외된 회원 수 : " . count($listTotal) . "<br>";
    echo "처리된 회원 수 : " . $checkCount;
    
    
?>