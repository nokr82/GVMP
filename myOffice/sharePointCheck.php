<?php 
    include_once ('./dbConn.php');
    include_once ('./dbConn2.php');

    // 월 예상 공유 보너스가 얼마인지 DB에 하루에 한번 update 시키는 로직입니다

    $re = mysql_query("select * from g5_member where mb_id != 'admin' and accountType = 'VM'");
    
    while( $row = mysql_fetch_array($re) ) {
        if (mysqli_multi_query($connection, "CALL SP_TREE('{$row["mb_id"]}');SELECT * FROM genealogy_tree WHERE rootid = '{$row["mb_id"]}' and accountType = 'VM' and lv <= 20 and renewal >= LEFT(DATE_ADD(NOW(), INTERVAL -1 MONTH),10)")) {
            mysqli_store_result($connection);
            mysqli_next_result($connection);
            do {
                /* store first result set */
                if ($result = mysqli_store_result($connection)) {
                    while ($sharePointRe2 = mysqli_fetch_array($result)) {
                        mysql_query("update g5_member set sharePoint = ". ($sharePointRe2["c"]*500) ." where mb_id = '{$row["mb_id"]}'") or die("DB ERROR");
                    }
                    mysqli_free_result($result);
                }
            } while (mysqli_next_result($connection));
        } else {
            echo mysqli_error($connection);
        }
        
//        튜닝으로 인한 주석 처리
//        $sharePointRe2 = mysql_fetch_array(mysql_query("select count(*) as c from
//            (SELECT c.mb_name AS mb_name
//               ,c.mb_id, c.sponsorID, c.sponsorName, c.recommenderID, c.recommenderName, v.level
//            FROM
//            (
//               SELECT f_category() AS mb_id, @level AS LEVEL
//               FROM (SELECT @start_with:='{$row["mb_id"]}', @id:=@start_with, @level:=0) vars
//                      INNER JOIN genealogy
//               WHERE @id IS NOT NULL
//            ) v
//               INNER JOIN genealogy c ON v.mb_id = c.mb_id
//            ) t
//                            INNER JOIN g5_member m ON t.mb_id = m.mb_id
//                    where accountType = 'VM' and level <= 20 and renewal >= LEFT(DATE_ADD(NOW(), INTERVAL -1 MONTH),10)"));
//               
//            mysql_query("update g5_member set sharePoint = ". ($sharePointRe2["c"]*500) ." where mb_id = '{$row["mb_id"]}'") or die("DB ERROR");
//            
    }
    
    
    $re2 = mysql_query("select * from g5_member where accountType != 'VM'");
    while( $row2 = mysql_fetch_array($re2) ) {
        mysql_query("update g5_member set sharePoint = 0 where mb_id = '{$row2["mb_id"]}'") or die("DB ERROR");
    }
    
    
?>