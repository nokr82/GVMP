<?php
    include_once ('./dbConn.php');
    
    set_time_limit(0);
    
    // 조직도에서 죽은 VM을 조직도에서 빼는 로직
    // crontab으로 주기적으로 로직을 도는 중
    // 주기는 서버에 부하가 가지 않을 정도로 하루 1회 이상 돌리면 적정


    // 죽은 사람 추출하는 쿼리.
    $vmListRe = mysql_query("SELECT 
                                a.*,
                                b.sponsorID,
                                b.sponsorName,
                                b.sponsorTeam
                            FROM
                                g5_member AS a
                                    INNER JOIN
                                genealogy AS b ON a.mb_id = b.mb_id
                            WHERE
                                a.renewal IS NOT NULL
                                    AND a.renewal != ''
                                    AND b.sponsorID != '99999999'
                                    AND a.renewal < DATE_ADD(CURDATE(), INTERVAL -5 MONTH)
                            ORDER BY renewal");
    
    
    
    while( $vmListRow = mysql_fetch_array($vmListRe) ) {        
        $teamMembersRow = mysql_fetch_array(mysql_query("select * from teamMembers where mb_id = '{$vmListRow["mb_id"]}'")) or die("select * from teamMembers where mb_id = '{$vmListRow["mb_id"]}'");
        $genealogyRow = mysql_fetch_array(mysql_query("select * from genealogy where mb_id = '{$vmListRow["mb_id"]}'")) or die("에러에러에러");
        
        if( $genealogyRow["sponsorID"] == '99999999' )
            continue;
        
        if( $teamMembersRow["1T_ID"] == '' && $teamMembersRow["2T_ID"] == '' ) { // 밑에 아무도 없을 때
            mysql_query("update g5_member set team1=0, team2=0 where mb_id = '{$vmListRow["mb_id"]}'");
            mysql_query("update teamMembers set {$genealogyRow["sponsorTeam"]}T_name = null, {$genealogyRow["sponsorTeam"]}T_ID = null where mb_id = '{$genealogyRow["sponsorID"]}'") or die("에러1");
            mysql_query("update genealogy set sponsorName = '없습니다', sponsorID = '99999999', sponsorTeam = '3' where mb_id = '{$vmListRow["mb_id"]}'") or die("에러2");
            echo "{$vmListRow["mb_id"]} / {$vmListRow["mb_name"]} / {$vmListRow["renewal"]} / {$teamMembersRow["1T_ID"]} / {$teamMembersRow["2T_ID"]} 삭제 완료<br>";
            
        } else if( $teamMembersRow["1T_ID"] != '' && $teamMembersRow["2T_ID"] != '' ) { // 밑에 둘 다 있을 때
            
            continue;
            
        } else { // 밑에 한명만 있을 때
            
            $team; $teamID;
            if( $teamMembersRow["1T_ID"] != '' ) {
                $teamID = $teamMembersRow["1T_ID"];
                $team = 1;
            } else {
                $teamID = $teamMembersRow["2T_ID"];
                $team = 2;
            }
            
            $teamInfoRow = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$teamID}'")) or die("에러33");
            

            
            mysql_query("update genealogy set sponsorName = '{$genealogyRow["sponsorName"]}', sponsorID = '{$genealogyRow["sponsorID"]}', sponsorTeam = '{$genealogyRow["sponsorTeam"]}' where mb_id = '{$teamID}'") or die("에러4");
            mysql_query("update teamMembers set {$genealogyRow["sponsorTeam"]}T_name = '{$teamInfoRow["mb_name"]}', {$genealogyRow["sponsorTeam"]}T_ID = '{$teamInfoRow["mb_id"]}' where mb_id = '{$genealogyRow["sponsorID"]}'") or die("에러5");
            
            mysql_query("update g5_member set team1=0, team2=0 where mb_id = '{$vmListRow["mb_id"]}'");
            mysql_query("update genealogy set sponsorName = '없습니다', sponsorID = '99999999', sponsorTeam = '3' where mb_id = '{$vmListRow["mb_id"]}'") or die("에러6");
            mysql_query("update teamMembers set {$team}T_name = null, {$team}T_ID = null where mb_id = '{$vmListRow["mb_id"]}'") or die("에러7");
            
            echo "{$vmListRow["mb_id"]} / {$vmListRow["mb_name"]} / {$vmListRow["renewal"]} 롤업 완료<br>";
            
        }
        
    }
    
    
echo "완료";
?>