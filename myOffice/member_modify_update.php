<?php
    include_once('./dbConn.php');
    include_once('./_common.php');
    set_time_limit(0);
    $link = mysqli_connect("192.168.0.30","root","Neungsoft1!", "gyc5");
    
    // 회원의 후원인을 변경하는 로직
    $result1 = mysql_query("select count(mb_id) as a, mb_name, accountType, renewal, team1, team2 from g5_member where mb_id = '{$_POST['before']}'");
    $row1 = mysql_fetch_array($result1);
    if( $row1['a'] == 0 ) {
        alert( $_POST['before'] . '는 존재하지 않는 아이디입니다.' , "/adm/member_modify.php");
    }
    
//    if( $row1['accountType'] != 'VM' && $row1['renewal'] == "" ) {
//        alert( $_POST['before'] . "는 VM 계정이 아닙니다.");
//    }
    
    $result2 = mysql_query("select count(mb_id) as a, mb_name, accountType from g5_member where mb_id = '{$_POST['after']}'");
    $row2 = mysql_fetch_array($result2);
    if( $row2['a'] == 0 ) {
        alert( $_POST['after'] . '는 존재하지 않는 아이디입니다.' , "/adm/member_modify.php");
    }
    
    if( $row2['accountType'] == 'VD' ) {
        alert( $_POST['after'] . "는 VD 계정입니다. 변경을 중단합니다.");
    }
    
    $result3 = mysql_query("select * from teamMembers where mb_id = '{$_POST['before']}'");
    $row3 = mysql_fetch_array($result3);
//    if( $row3['1T_name'] != null ) {
//        alert( $row1['mb_name'] . "님의 1팀에 {$row3['1T_name']}님이 배치 돼 있어 변경될 수 없습니다." );
//    }
//    if( $row3['2T_name'] != null ) {
//        alert( $row1['mb_name'] . "님의 2팀에 {$row3['2T_name']}님이 배치 돼 있어 변경될 수 없습니다." );
//    }
    
    
    $result4 = mysql_query("select * from teamMembers where mb_id = '{$_POST['after']}'");
    $row4 = mysql_fetch_array($result4);

    if( $_POST['team'] == 1 && $row4['1T_name'] == null ) {
        sp_call("-", $row1["team1"] + $row1["team2"] + 1); // 조직도 이동 전에 감소 시키기
        
        // 나의 원래 후원인 정보 뽑기
        $result5 = mysql_query("select * from genealogy where mb_id = '{$_POST['before']}'");
        $row5 = mysql_fetch_array($result5);
        
        // 나의 원래 후원인 팀배정에서 나를 빼기
        mysql_query("update teamMembers set {$row5['sponsorTeam']}T_name = null, {$row5['sponsorTeam']}T_ID = null where mb_id = '{$row5['sponsorID']}'");
        
        // 바뀔 후원인 정보 뽑기
        $result6 = mysql_query("select * from g5_member where mb_id = '{$_POST['after']}'");
        $row6 = mysql_fetch_array($result6);
        
        // 나의 후원인 정보를 바뀔 회원으로 변경
        mysql_query("update genealogy set sponsorName = '{$row6['mb_name']}', sponsorID = '{$_POST['after']}', sponsorTeam = '{$_POST['team']}' where mb_id = '{$_POST['before']}'");
        
        // 내 바뀔 후원인 팀배정에 나를 넣기
        mysql_query("update teamMembers set {$_POST['team']}T_name = '{$row1['mb_name']}', {$_POST['team']}T_ID = '{$_POST['before']}' where mb_id = '{$_POST['after']}'");

        sp_call("+", $row1["team1"] + $row1["team2"] + 1); // 조직도 이동 후에 증가 시키기
    } else if( $_POST['team'] == 2 && $row4['2T_name'] == null ) {
        sp_call("-", $row1["team1"] + $row1["team2"] + 1); // 조직도 이동 전에 감소 시키기
        
        $result5 = mysql_query("select * from genealogy where mb_id = '{$_POST['before']}'");
        $row5 = mysql_fetch_array($result5);
        
        mysql_query("update teamMembers set {$row5['sponsorTeam']}T_name = null, {$row5['sponsorTeam']}T_ID = null where mb_id = '{$row5['sponsorID']}'");
        
        $result6 = mysql_query("select * from g5_member where mb_id = '{$_POST['after']}'");
        $row6 = mysql_fetch_array($result6);
        mysql_query("update genealogy set sponsorName = '{$row6['mb_name']}', sponsorID = '{$_POST['after']}', sponsorTeam = '{$_POST['team']}' where mb_id = '{$_POST['before']}'");
        
        mysql_query("update teamMembers set {$_POST['team']}T_name = '{$row1['mb_name']}', {$_POST['team']}T_ID = '{$_POST['before']}' where mb_id = '{$_POST['after']}'");
        
        sp_call("+", $row1["team1"] + $row1["team2"] + 1); // 조직도 이동 후에 증가 시키기
    } else {
        alert( $row2['mb_name'] . "님의 {$_POST['team']}팀에 {$row4[$_POST['team'] . 'T_name']}님이 배치 돼 있어 변경될 수 없습니다.");
    }
    
    
    
    
    
    
    // 후원인 변경으로 인해 조직도 인원수 카운팅을 감소 또는 증가를 처리 해 주는 부분입니다.
    function sp_call($PM, $number) {
        global $link;
//        mysqli_multi_query($link, "call sp_count('{$_POST['before']}', {$PM}{$number})"); // 성능 저하로 아래 프로시저로 튜닝함. 이정현
        mysqli_multi_query($link, "CALL SP_TREE_R('{$_POST['before']}', {$PM}{$number})");

        do {
            if ($result=mysqli_store_result($link)) {
                while ($row=mysqli_fetch_row($result))
                    mysqli_free_result($link);
            }
        } while(mysqli_next_result( $link ));
    }
    //////////////////////////////////////////////////////////////////////////////////////////
    
    
    
    
    
    
    
    
    
    
    
    mysqli_close($link);
    
    alert( $row1['mb_name'] . "님을 " . $row2['mb_name'] . "님의 " . $_POST['team'] . "팀으로 변경 했습니다." );
    
//    안 쓰는 함수 같아서 주석처리. 이정현
//    function countCheck( $mb_id ) {
//        $countRow = mysql_fetch_array(mysql_query("SELECT 
//                        COUNT(m.mb_id) AS cnt
//                    FROM
//                        (SELECT 
//                            c.mb_name AS mb_name,
//                                c.mb_id,
//                                c.sponsorID,
//                                c.sponsorName,
//                                c.recommenderID,
//                                c.recommenderName,
//                                v.level
//                        FROM
//                            (SELECT 
//                            F_CATEGORY() AS mb_id, @level AS LEVEL
//                        FROM
//                            (SELECT @start_with:='{$mb_id}', @id:=@start_with, @level:=0) vars
//                        INNER JOIN genealogy
//                        WHERE
//                            @id IS NOT NULL) v
//                        INNER JOIN genealogy AS c ON v.mb_id = c.mb_id) t
//                            INNER JOIN
//                        g5_member AS m ON t.mb_id = m.mb_id
//                    WHERE
//                        renewal >= DATE_ADD(CURDATE(), INTERVAL - 1 MONTH)"));
//                            return $countRow["cnt"];
//    }
?>
