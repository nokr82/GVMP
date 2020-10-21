<?php
    include_once('./dbConn.php');
    include_once('./dbConn2.php');
    
    set_time_limit(0);
    
    // mb_id로 지정한 계정의 산하 회원 목록을 뽑아서
    // 전부 조직도에서 삭제하는 로직
    // 참고로 CU로 만드는 로직은 안 넣었음. 필요시 추가 요망
    // 지정한 계정 산하로(지정한 계정은 포함) 조직도에서 없애는 용도의 로직입니다.
    
    
    $mb_id = "";
    action($mb_id);
    
    if (mysqli_multi_query($connection, "CALL SP_TREE('{$mb_id}');SELECT * FROM genealogy_tree WHERE rootid = '{$mb_id}' order by lv")) {
        mysqli_store_result($connection);
        mysqli_next_result($connection);
        do {
            /* store first result set */
            if ($result = mysqli_store_result($connection)) {
                while ($row2 = mysqli_fetch_array($result)) {
                    action($row2["mb_id"]);
                }
                mysqli_free_result($result);
            }
        } while (mysqli_more_results($connection) && mysqli_next_result($connection));
    }

    echo "완료";
    
    
    
    function action( $id ) {
        mysql_query("update g5_member set mall_yn = 'N', settle_yn ='N', send_yn = 'N', simpleRenewal = 'NO', simpleJoin = 'NO' where mb_id = '{$id}'") or die("ERROR");
        mysql_query("update g5_member set mb_password = '*BAA8D65221E53482D7FCEE8EB244A1AD7DF0ADA8' where mb_id = '{$id}'");


        mysql_query("update teamMembers set 1T_name = null, 1T_ID = null where 1T_ID = '{$id}'") or die("에러5");
        mysql_query("update teamMembers set 2T_name = null, 2T_ID = null where 2T_ID = '{$id}'") or die("에러5");

        mysql_query("update g5_member set team1=0, team2=0 where mb_id = '{$id}'");
        mysql_query("update genealogy set sponsorName = '없습니다', sponsorID = '99999999', sponsorTeam = '3' where mb_id = '{$id}'") or die("에러6");
        mysql_query("update teamMembers set 1T_name = null, 1T_ID = null, 2T_name = null, 2T_ID = null where mb_id = '{$id}'") or die("에러7");

        mysql_query("update g5_member set accountType = 'CU', accountRank = 'CU' where mb_id = '{$id}'");
    }
?>