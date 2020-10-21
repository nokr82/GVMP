<?php
    include_once($_SERVER["DOCUMENT_ROOT"] . '/myOffice/dbConn.php');
    
    //# VM일 때 이름 변경하는 로직
    //# CU인데 리뉴얼 날짜가 있을 때
    //1. g5_member에 mb_name 변경
    //2. genealogy에 내꺼 mb_name 변경
    //3. 내 후원인의 teamMembers에 내 이름 변경
    //4. 나를 추천인으로 지정한 회원들의
    //genealogy에 추천인 이름 변경
    //5. 나를 후원인으로 지정한 회원들의
    //genealogy에 후원인 이름 변경
    //
    //# CU, VD일 때 이름 변경하는 로직
    //1. g5_member에 mb_name 변경
    //2. genealogy에 내꺼 mb_name 변경

    function nameUpdateFunc( $mb_name, $mb_id ) {
        $result = mysql_query("select * from g5_member where mb_id = '{$mb_id}'");
        $row = mysql_fetch_array($result);
        
        $Previous_mb_name = $row['mb_name']; // 바꾸기 전 이름
        
        if( $row['accountType'] == 'VM' || ($row['accountType'] == 'CU' && $row['renewal'] != null)  ) {
            // 계정이 VM 이거나 CU인데 리뉴얼 날짜가 기록 돼 있으면 참
            // 즉, VM이거나 VM 이였던 회원이면 참
            mysql_query("update genealogy set mb_name = '{$mb_name}' where mb_id = '$mb_id'");
            
            mysql_query("update genealogy set sponsorName = '{$mb_name}' where sponsorID = '{$mb_id}'");
            mysql_query("update genealogy set recommenderName = '{$mb_name}' where recommenderID = '{$mb_id}'");
            
            $result2 = mysql_query("select * from genealogy where mb_id = '{$mb_id}'");
            $row2 = mysql_fetch_array($result2);
            
            mysql_query("update teamMembers set {$row2['sponsorTeam']}T_name = '{$mb_name}' where mb_id = '{$row2['sponsorID']}'");
        } else if( $row['accountType'] == 'CU' || $row['accountType'] == 'VD' ) {
            // 계정이 처음부터 지금까지 CU이거나 VD면 참
            mysql_query("update genealogy set mb_name = '{$mb_name}' where mb_id = '{$mb_id}'");
            mysql_query("update team3_list set 3T_name = '{$mb_name}' where 3T_id = '{$mb_id}'");
        }

    }
?>