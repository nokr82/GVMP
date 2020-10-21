<?php
    include_once('./dbConn.php');

// 조직도 출력을 위해 하위 3명에 대한 정보를 담는 로직

    function selectFunc($selectID, $checkCount,$mb_id) {
        global $memberInfo;
        $a = 0; $n1 = 0; $n2 = 0;


        switch( $checkCount ) {
            case 1 : $a = 1; $n1 = 1; $n2 = 2; break;
            case 2 : $a = 2; $n1 = 1; $n2 = 2; break;
            case 3 : $a = 2; $n1 = 3; $n2 = 4; break;
            case 4 : $a = 3; $n1 = 1; $n2 = 2; break;
            case 5 : $a = 3; $n1 = 3; $n2 = 4; break;
            case 6 : $a = 3; $n1 = 5; $n2 = 6; break;
            case 7 : $a = 3; $n1 = 7; $n2 = 8; break;
            case 8 : $a = 4; $n1 = 1; $n2 = 2; break;
            case 9 : $a = 4; $n1 = 3; $n2 = 4; break;
            case 10 : $a = 4; $n1 = 5; $n2 = 6; break;
            case 11 : $a = 4; $n1 = 7; $n2 = 8; break;
            case 12 : $a = 4; $n1 = 9; $n2 = 10; break;
            case 13 : $a = 4; $n1 = 11; $n2 = 12; break;
            case 14 : $a = 4; $n1 = 13; $n2 = 14; break;
            case 15 : $a = 4; $n1 = 15; $n2 = 16; break;
        }




        $result3 = mysql_query("select * from teamMembers where mb_id = '{$selectID}'");
        $row3 = mysql_fetch_array($result3);


        if( $row3['1T_name'] != null && $row3['1T_name'] != "null" ) {
            $result4 = mysql_query("SELECT A.renewal, A.accountRank, A.mb_open_date, B.recommenderName, A.team1, A.team2 FROM g5_member AS A INNER JOIN genealogy AS B ON A.mb_id = B.mb_id where A.mb_id = '{$row3['1T_ID']}'");
            $row4 = mysql_fetch_array($result4);

            $result5 = mysql_query("select count(*) as team3 from team3_list where mb_id = '{$row3['1T_ID']}'");
            $row5 = mysql_fetch_array($result5);

            $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row4['renewal']}', interval +4 month) AS date"));
            $timestamp2 = $dateCheck_1["date"];



            $memberInfo[$a . '촌'][$n1 . '번']['존재유무'] = true;
            $memberInfo[$a . '촌'][$n1 . '번']['이름'] = $row3['1T_name'];
            $memberInfo[$a . '촌'][$n1 . '번']['회원가입날짜'] = $row4['mb_open_date'];
            $memberInfo[$a . '촌'][$n1 . '번']['리뉴얼날짜'] = $timestamp2;
            $memberInfo[$a . '촌'][$n1 . '번']['1팀인원수'] = $row4["team1"];
            $memberInfo[$a . '촌'][$n1 . '번']['2팀인원수'] = $row4["team2"];
            $memberInfo[$a . '촌'][$n1 . '번']['3팀인원수'] = $row5['team3'];
            $memberInfo[$a . '촌'][$n1 . '번']['추천인'] = $row4['recommenderName'];
            $memberInfo[$a . '촌'][$n1 . '번']['아이디'] = $row3['1T_ID'];
            $memberInfo[$a . '촌'][$n1 . '번']['직급'] = $row4['accountRank'];
            $memberInfo[$a . '촌'][$n1 . '번']['색상코드'] = memberColor(rankCheck($row3['1T_ID']));
            $memberInfo[$a . '촌'][$n1 . '번']['이미지경로'] = imagePath(rankCheck($row3['1T_ID']));
            //동우추가 수정권한 옵션
            $rank_num = mysql_fetch_array(mysql_query("select * from rankOrderBy where rankAccount = '{$row4['accountRank']}'"));
            if ($mb_id == '00447591'||$mb_id=='00447972'||$mb_id == '00447976'||$mb_id == '00003967'){
                if ($rank_num['orderNum'] >= 5){
                    $memberInfo[$a . '촌'][$n1 . '번']['수정권한'] = true;
                }
            }else{
                
                if ($rank_num['orderNum'] >= 10){
                    $memberInfo[$a . '촌'][$n1 . '번']['수정권한'] = true;
                }
            }



        }

        if( $row3['2T_name'] != null && $row3['2T_name'] != "null" ) {
            $result44 = mysql_query("SELECT A.renewal, A.accountRank, A.mb_open_date, B.recommenderName, A.team1, A.team2 FROM g5_member AS A INNER JOIN genealogy AS B ON A.mb_id = B.mb_id where A.mb_id = '{$row3['2T_ID']}'");
            $row44 = mysql_fetch_array($result44);

            $result55 = mysql_query("select count(*) as team3 from team3_list where mb_id = '{$row3['2T_ID']}'");
            $row55 = mysql_fetch_array($result55);


            $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row44['renewal']}', interval +4 month) AS date"));
            $timestamp1 = $dateCheck_1["date"];



            $memberInfo[$a . '촌'][$n2 . '번']['존재유무'] = true;
            $memberInfo[$a . '촌'][$n2 . '번']['이름'] = $row3['2T_name'];
            $memberInfo[$a . '촌'][$n2 . '번']['회원가입날짜'] = $row44['mb_open_date'];
            $memberInfo[$a . '촌'][$n2 . '번']['리뉴얼날짜'] = $timestamp1;
            $memberInfo[$a . '촌'][$n2 . '번']['1팀인원수'] = $row44["team1"];
            $memberInfo[$a . '촌'][$n2 . '번']['2팀인원수'] = $row44["team2"];
            $memberInfo[$a . '촌'][$n2 . '번']['3팀인원수'] = $row55['team3'];
            $memberInfo[$a . '촌'][$n2 . '번']['추천인'] = $row44['recommenderName'];
            $memberInfo[$a . '촌'][$n2 . '번']['아이디'] = $row3['2T_ID'];
            $memberInfo[$a . '촌'][$n2 . '번']['직급'] = $row44['accountRank'];
            $memberInfo[$a . '촌'][$n2 . '번']['색상코드'] = memberColor(rankCheck($row3['2T_ID']));
            $memberInfo[$a . '촌'][$n2 . '번']['이미지경로'] = imagePath(rankCheck($row3['2T_ID']));
            
            //동우추가 수정권한 옵션
            $rank_num = mysql_fetch_array(mysql_query("select * from rankOrderBy where rankAccount = '{$row44['accountRank']}'"));
            if ($mb_id == '00447591'||$mb_id=='00447972'||$mb_id == '00447976'||$mb_id == '00003967'){
                if ($rank_num['orderNum'] >= 5){
                    $memberInfo[$a . '촌'][$n2 . '번']['수정권한'] = true;
                }
            }else{
                if ($rank_num['orderNum'] >= 10){
                    $memberInfo[$a . '촌'][$n2 . '번']['수정권한'] = true;
                }
            }




            
        }

        return array( $row3['1T_ID'], $row3['2T_ID'] );
    }

    function memberColor($accountRank) {
        switch( $accountRank ) {
            case "VM" :
                return "#6AA7D4"; break;
            case "MASTER" :
                return "#54BFCD"; break;
            case "Double MASTER" :
                return "#2EA8AD"; break;
            case "Triple MASTER" :
                return "#117F71"; break;
            case "1 STAR" :
                return "#E6BA75"; break;
            case "2 STAR" :
                return "#F4A445"; break;
            case "3 STAR" :
                return "#EF8124"; break;
            case "4 STAR" :
                return "#DF6B1F"; break;
            case "5 STAR" :
                return "#CC4319"; break;
            case "AMBASSADOR" :
                return "#8372B2"; break;
            case "Double AMBASSADOR" :
                return "#6953A0"; break;
            case "Triple AMBASSADOR" :
                return "#5A377B"; break;
            case "Crown AMBASSADOR" :
                return "#3E2554"; break;
            case "Royal Crown AMBASSADOR" :
                return "#610B4B"; break;
            case "회원탈퇴" :
                return "#FF0000"; break;
            case "CU" :
                return "#F76E5E"; break;
            case "" :
                return "#F76E5E"; break;
            default :
                return "false";
        }
    }
?>
