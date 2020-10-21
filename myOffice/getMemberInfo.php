<?php
include_once('./dbConn.php');
include_once('./dbConn2.php');

// 본 파일은 ID 기준으로 하위 회원 1팀과 2팀 정보를 배열에 담고
// 하위 1, 2팀 인원 수를 배열로 리턴 해 주는 로직


$mb_info_1T = array(); // 1Team의 하위 회원 정보 담을 배열
$mb_info_2T = array(); // 2Team의 하위 회원 정보 담을 배열
$nextID = array(); // mb_info 함수로 넘겨줄 ID 잠시 담는 용도...ㅎㅎ

function mb_info($param, $team)
{
    global $mb_info_1T;
    global $mb_info_2T;
    global $nextID;

    $key = array_search($param, $nextID);
    array_splice($nextID, $key, 1);


//        $result = mysql_query("select * from teamMembers where mb_id = '{$param}'");
    $result = mysql_query("SELECT 
    T1.n,
    T1.mb_id,
    T1.1T_ID,
    T1.1T_name,
    T1.recommenderID AS '1T_recommenderID',
    T1.recommenderName AS '1T_recommenderName',
    T2.2T_ID,
    T2.2T_name,
    T2.recommenderID AS '2T_recommenderID',
    T2.recommenderName AS '2T_recommenderName'
FROM
    (SELECT 
        te.n,
            te.mb_id,
            te.1T_ID,
            te.1T_name,
            ge.recommenderID,
            ge.recommenderName
    FROM
        teamMembers te
    LEFT JOIN genealogy ge ON ge.mb_id = te.1T_ID) AS T1,
    (SELECT 
        te.n,
		te.mb_id,
		te.2T_ID,
		te.2T_name,
		ge.recommenderID,
		ge.recommenderName
    FROM
        teamMembers te
    LEFT JOIN genealogy ge ON ge.mb_id = te.2T_ID) AS T2
WHERE
    T1.mb_id = T2.mb_id and T1.mb_id = '{$param}'");
    $row = mysql_fetch_array($result);

    if ($row['1T_ID'] != null) {
        if ($team == 1) {
            array_push($mb_info_1T, array($row['1T_name'], $row['1T_ID'], $row['1T_recommenderID'], $row['1T_recommenderName']));
        } else if ($team == 2) {
            array_push($mb_info_2T, array($row['1T_name'], $row['1T_ID'], $row['1T_recommenderID'], $row['1T_recommenderName']));
        }
        array_push($nextID, $row['1T_ID']);
    }

    if ($row['2T_ID'] != null) {
        if ($team == 1) {
            array_push($mb_info_1T, array($row['2T_name'], $row['2T_ID'], $row['2T_recommenderID'], $row['2T_recommenderName']));
        } else if ($team == 2) {
            array_push($mb_info_2T, array($row['2T_name'], $row['2T_ID'], $row['2T_recommenderID'], $row['2T_recommenderName']));
        }
        array_push($nextID, $row['2T_ID']);
    }
}


function teamCount($id, $check)
{
    // 작성자 : 이정현. 20200226
    // check 파라미터 사용 안 하고 있는 중.
    // mb_info_1T, 2T 배열 지워야 하는 경우 함수 밖에서 unset으로 지우고 다시 만들어서 사용해야 함.
    // 함수에서 unset 시키면 전역 변수라 의미가 없음.
    
    // teamCount 함수는 파라미터로 넘겨 받은 ID의 산하 회원 중 리뉴얼 기간이 유지 중인 회원이 몇 명인지 인원수를 카운팅 해 1,2,3팀 별로 인원수를 리턴 해 주는 함수.
    global $mb_info_1T;
    global $mb_info_2T;
    global $nextID;
    global $connection;


    $result = mysql_query("select * from teamMembers where mb_id = '{$id}'");
    $row = mysql_fetch_array($result);


    if ($row['1T_ID'] != null) {
        $result_1T = mysql_query("select * from genealogy as a inner join g5_member as b on a.mb_id=b.mb_id where a.mb_id='{$row['1T_ID']}' and date_add( b.renewal, interval +4 month ) >= now()");
        $row_1T = mysql_fetch_array($result_1T);
        if( $row_1T['mb_id'] != "" ) {
            array_push($mb_info_1T, array($row_1T['mb_name'], $row_1T['mb_id'], $row_1T['recommenderID'], $row_1T['recommenderName'], $row_1T['renewal'], $row_1T['accountType']));
        }


        if (mysqli_multi_query($connection, "CALL SP_TREE('{$row['1T_ID']}');SELECT * FROM genealogy_tree WHERE rootid = '{$row['1T_ID']}' order by lv")) {
            mysqli_store_result($connection);
            mysqli_next_result($connection);
            do {
                /* store first result set */
                if ($result = mysqli_store_result($connection)) {
                    while ($row2 = mysqli_fetch_array($result)) {
                        array_push($mb_info_1T, array($row2['mb_name'], $row2['mb_id'], $row2['recommenderID'], $row2['recommenderName'], $row2['renewal'], $row2['accountType']));
                    }
                    mysqli_free_result($result);
                }
            } while (mysqli_next_result($connection));
        } else {
            echo mysqli_error($connection);
        }
    }


    if ($row['2T_ID']) {
        $result_2T = mysql_query("select * from genealogy as a inner join g5_member as b on a.mb_id=b.mb_id where a.mb_id='{$row['2T_ID']}' and date_add( b.renewal, interval +4 month ) >= now()");
        $row_2T = mysql_fetch_array($result_2T);
        if( $row_2T['mb_id'] != "" ) {
            array_push($mb_info_2T, array($row_2T['mb_name'], $row_2T['mb_id'], $row_2T['recommenderID'], $row_2T['recommenderName'], $row_2T['renewal'], $row_2T['accountType']));
        }

        if (mysqli_multi_query($connection, "CALL SP_TREE('{$row['2T_ID']}');SELECT * FROM genealogy_tree WHERE rootid = '{$row['2T_ID']}' order by lv")) {
            mysqli_store_result($connection);
            mysqli_next_result($connection);
            do {
                /* store first result set */
                if ($result = mysqli_store_result($connection)) {
                    while ($row3 = mysqli_fetch_array($result)) {
                        array_push($mb_info_2T, array($row3['mb_name'], $row3['mb_id'], $row3['recommenderID'], $row3['recommenderName'], $row3['renewal'], $row3['accountType']));
                    }
                    mysqli_free_result($result);
                }
            } while (mysqli_next_result($connection));
        }
    }

    
    // 리뉴얼 유예기간 인원 카운팅 제외를 위한 복제 작업
    $mb_info_1T_copy = $mb_info_1T;
    $mb_info_2T_copy = $mb_info_2T;

    # 1T, 2T 회원의 유예기간을 알아내기 위한 쿼리
    $nowTime = date("Y-m-d"); // 오늘 날짜
    $nowTime_str = strtotime($nowTime); // 오늘 날짜 스트링


    // 리뉴얼 유예기간일 시 복제한 배열에서 삭제하기
    foreach ($mb_info_1T_copy as $value) {
        $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$value[4]}', interval +4 month) AS date"));
        $rowGrace_1T_str_strart = $dateCheck_1["date"]; // 1T 리뉴얼 시작 날짜 스트링
        $rowGrace_1T_str_strart = strtotime($rowGrace_1T_str_strart);

        // 현재날짜가 유예기간 시작날짜 미만이면 거짓
        if (!($nowTime_str <= $rowGrace_1T_str_strart) || $value[5] == '회원탈퇴') {
            $key = array_search($mb_info_1T_copy, $value);
            array_splice($mb_info_1T_copy, $key, 1);
        }
    }
    foreach ($mb_info_2T_copy as $value) {
        $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$value[4]}', interval +4 month) AS date"));
        $rowGrace_2T_str_strart = $dateCheck_1["date"]; // 2T 리뉴얼 시작 날짜 스트링
        $rowGrace_2T_str_strart = strtotime($rowGrace_2T_str_strart);


        // 현재날짜가 유예기간 시작날짜 미만이면 참
        if (!($nowTime_str <= $rowGrace_2T_str_strart) || $value[5] == '회원탈퇴') {
            $key = array_search($mb_info_2T_copy, $value);
            array_splice($mb_info_2T_copy, $key, 1);
        }
    }


    $count_1T = count($mb_info_1T_copy);
    $count_2T = count($mb_info_2T_copy);




    $result = mysql_query("select count(*) from team3_list where mb_id = '{$id}'");
    $row = mysql_fetch_row($result);

    return array($count_1T, $count_2T, $row[0]); // 1, 2, 3팀 인원 리턴
}


function getAllMemeber()
{
    $data = "";
    $sql = "SELECT mb_id, mb_name, renewal FROM g5_member where mb_id <> 'admin'";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $data[$row['mb_id']] = $row['mb_name'];
    }
    $data['999999999999'] = '+';
    return $data;
}

function memberTreeDetail($MBID)
{
    global $connection;


    $CHECK = "";
    $TEAMBILD = false;
    $TMPTEAM = 1;
    if (mysqli_multi_query($connection, "CALL SP_TREE('{$MBID}');SELECT * FROM genealogy_tree WHERE rootid = '{$MBID}' ORDER BY sponsorID,sponsorTeam")) {
        mysqli_store_result($connection);
        mysqli_next_result($connection);
        do {
            /* store first result set */
            if ($result = mysqli_store_result($connection)) {
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($CHECK != $row['sponsorID']) {
                        if ($row['scnt'] == "1" && $row['sponsorTeam'] == "1") {
                            $TEAMBILD = true;
                        }
                        $TMPTEAM = 1;
                        $CHECK = $row['sponsorID'];
                    }
                    if ($TMPTEAM == 1 && $row['sponsorTeam'] == "2") {
                        $DATA[$row['mb_id'] . ':first'] = $row['sponsorID'] . "||1||false||" . $row['sponsorName'] . "||" . $row['recommenderName'];
                    }

                    $DATA[$row['mb_id']] = $row['sponsorID'] . "||" . $row['sponsorTeam'] . "||" . $row['newstate'] . "||" . $row['sponsorName'] . "||" . $row['recommenderName'];
                    if ($TEAMBILD) {
                        $DATA[$row['mb_id'] . ':second'] = $row['sponsorID'] . "||2||false||" . $row['sponsorName'] . "||" . $row['recommenderName'];
                        $TEAMBILD = false;
                    }
                    $TMPTEAM++;
                }
                mysqli_free_result($result);
            }
        } while (mysqli_next_result($connection));
    } else {
        echo mysqli_error($connection);
    }
    $DATA[$MBID] = NULL;
    return $DATA;

//    튜닝으로 인해 주석 처리
//	$sql = "SELECT *,
//	(CASE WHEN ANGCHA < 0 THEN 'angry' 
//	ELSE CASE WHEN SADCHA < 0 THEN 'sad' ELSE '' END 
//	END) AS newstate 
//	FROM (SELECT *,
//	DATE_ADD(CONCAT(renewal), INTERVAL +1 MONTH) AS saddata, 
//	DATE_ADD(CONCAT(renewal), INTERVAL +2 MONTH) AS angry, 
//	TO_DAYS(DATE_ADD(CONCAT(renewal), INTERVAL +1 MONTH)) - TO_DAYS(NOW()) AS MEMCHA, 
//	TO_DAYS(DATE_ADD(CONCAT(renewal), INTERVAL +2 MONTH)) - TO_DAYS(NOW()) AS SADCHA, 
//	TO_DAYS(DATE_ADD(CONCAT(renewal), INTERVAL +3 MONTH)) - TO_DAYS(NOW()) AS ANGCHA
//	FROM (SELECT c.mb_name AS mb_name, c.mb_id, c.sponsorID, c.sponsorName, c.sponsorTeam, c.recommenderID, c.recommenderName, v.level
//	,(SELECT COUNT(sponsorID) FROM genealogy WHERE sponsorID = c.sponsorID) AS scnt 
//	,(SELECT renewal FROM g5_member WHERE mb_id = c.mb_id) AS renewal
//    FROM
//        (SELECT 
//        F_CATEGORY() AS mb_id, @level AS LEVEL
//    FROM
//        (SELECT @start_with:='".$MBID."', @id:=@start_with, @level:=0) vars
//    INNER JOIN genealogy
//    WHERE
//        @id IS NOT NULL) v
//    INNER JOIN genealogy c ON v.mb_id = c.mb_id) as d ORDER BY sponsorID,sponsorTeam) CNT";
//	$result = mysql_query($sql);
//
//	$CHECK="";
//	$TEAMBILD=false;
//	$TMPTEAM=1;
//	while( $row = mysql_fetch_assoc($result) ) {
//		if($CHECK!=$row['sponsorID']){
//			if($row['scnt']=="1"&&$row['sponsorTeam']=="1"){
//				$TEAMBILD=true;
//			}
//			$TMPTEAM = 1;
//			$CHECK=$row['sponsorID'];
//		}
//		if($TMPTEAM==1&&$row['sponsorTeam']=="2"){
//			$DATA[$row['mb_id'].':first']= $row['sponsorID'] . "||1||false||".$row['sponsorName']."||".$row['recommenderName'];
//		}
//
//		$DATA[$row['mb_id']]= $row['sponsorID'] . "||" . $row['sponsorTeam'] . "||" . $row['newstate']."||".$row['sponsorName']."||".$row['recommenderName'];
//		if($TEAMBILD){
//			$DATA[$row['mb_id'].':second']= $row['sponsorID'] . "||2||false||".$row['sponsorName']."||".$row['recommenderName'];
//			$TEAMBILD=false;
//		}
//		$TMPTEAM++;
////		$DATA[$row['mb_id']]= $row['sponsorID'];
//	}
//	$DATA[$MBID] = NULL;
//	return $DATA;
}

function parseTreeDetail($tree, $root = null)
{
    global $MEMBER;
    $return = array();
    foreach ($tree as $child => $parent) {
        if ($parent != null || $parent) {
            $tmp = explode('||', $parent);
            $parent = $tmp[0];
            $team = $tmp[1];
            $sponsorName = $tmp[3];
            $recommName = $tmp[4];
            if ($tmp[2] != "false") {
                $RENEWAL = $tmp[2];
            } else {
                $RENEWAL = "";
            }
        } else {
            $parent = $parent;
            $team = "super";
            $RENEWAL = "";
        }

        if (strpos($child, ":")) {
            $NAME = $MEMBER['999999999999'];
            $MID = $parent;
            $RENEWAL = "plus";
        } else {
            $NAME = $MEMBER[$child];
            $MID = $child;
        }
        if ($parent == $root) {
            unset($tree[$child]);
            $return[] = array(
                'name' => $NAME,
                'mid' => $MID,
                'team' => $team,
                'renewal' => $RENEWAL,
                'parent' => $parent,
                'parentName' => $sponsorName,
                'recommName' => $recommName,
                'children' => parseTreeDetail($tree, $child)
            );
        }
    }
    return empty($return) ? null : $return;
}

$POSITION = 0;
function printTreeDetail($tree)
{
    global $POSITION;
    if (!is_null($tree) && count($tree) > 0) {
        echo '<ul';
        if ($POSITION == 0) {
            echo ' id="organisation" style="display:none"';
//			echo ' id="organisation"';
        }
        echo '>';
        $POSITION++;
        foreach ($tree as $key => $node) {
            $ADDC = ($node['renewal']) ? $node['renewal'] : "";
//            echo "<li><a href=\"#none\" OnClick=\"DetailShow('".$node['mid']."')\" alt=\"".$ADDC."\">".$node['name']."</a>";

            if ($node['name'] == "+") {
                echo "<li><a href=\"#none\" OnClick=\"$('#infoTemp').val('/bbs/register_form2.php?mb_id=" . $node['parent'] . "&mb_name=" . $node['parentName'] . "&team=" . $node['team'] . "','small','width=450,height=605,scrollbars=yes,menubar=no,location=no');$('#spot').css('visibility', 'visible');\" alt=\"" . $ADDC . "\">" . $node['name'] . "</a>";
            } else {
                echo "<li><a href=\"#none\" OnClick=\"detailMember('" . $node['mid'] . "','" . $node['recommName'] . "');\" alt=\"" . $ADDC . "\">" . $node['name'] . "</a>";
            }
            printTreeDetail($node['children']);
            echo '</li>';
        }
        echo '</ul>';
    }
}

function renewalData($data, $sadData, $angryData)
{
    global $NOWDATA;
    $DATA = "";
    if ($NOWDATA <= $sadData) {
        $DATA = "";
    } else if ($NOWDATA > $sadData && $NOWDATA <= $angryData) {
        $DATA = "sad";
    } else if ($NOWDATA > $angryData) {
        $DATA = "angry";
    } else {
        $DATA = "";
    }
    return $DATA;
}

function jsonMemberTreeDetail($MBID)
{
    $sql = "SELECT * FROM orgChartTable WHERE n = '1'";
    $row = mysql_fetch_array(mysql_query($sql));
    $DATA = json_decode($row['jsonData'], true);
    return $DATA;
}


$POSITION = 0;
//이곳이다 성지다
function jsonPrintTreeDetail($tree)
{
    global $POSITION, $DATAHTML;
    if (!is_null($tree) && count($tree) > 0) {
        $DATAHTML .= '<ul>';
        $POSITION++;
        foreach ($tree as $key => $node) {
            $ADDC = (isset($node['renewal']) && $node['renewal']) ? $node['renewal'] : "";
            if (isset($node['name']) && $node['name']) {
                if ($node['name'] == "+") {
                    $DATAHTML .= "<li>";
                    $DATAHTML .= "<table><tr><td></td><td></td></tr></table>";
                    $DATAHTML .= "<span>" . $node['name'] . "</span>";
                    $DATAHTML .= "</table>";
                } else {
                    $DATACLASS = ($node['childrenCNT'] == "1") ? 'OrgSingle' : 'OrgDoublue';
                    $SUBCLASS = ($node['team'] == "1") ? 'OrgLeftLine' : 'OrgRightLine';
                    $TEAMICON = ($node['team'] == "1") ? '①' : '';
                    $TEAMICON = ($TEAMICON == "" && $node['team'] == "2") ? '②' : $TEAMICON;
                    $NOSPCLASS = $node['renewal'];
                    $DATAHTML .= "<li class='unit " . $DATACLASS . " " . $SUBCLASS . "'\">";
                    $DATAHTML .= "<table><tr><td></td><td></td></tr></table>";
                    $DATAHTML .= "<span class='" . $node['renewal'] . "'><font style=\"font-size:16px\">" . $TEAMICON . "</font> <a href=\"javascript:detailMemberModal('" . $node['mid'] . "','" . $node['recommName'] . "')\">" . $node['name'] . "</a></span>";
                    $DATAHTML .= "<table><tr><td style='border-right:1px solid #000000;height:18px;'></td><td></td></tr></table>";
                }
            }
            jsonPrintTreeDetail($node['children']);
            $DATAHTML .= '</li>';
        }
        $DATAHTML .= '</ul>';
    }
    
    
    
    return $DATAHTML;
}


function rankCheck($mb_id)
{
    $row = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$mb_id}'"));

    if ($row["rank_yn"] == "Y")
        return $row["accountRank"];

    if ($row["accountType"] != "VM")
        return $row["accountType"];
    



    $row2 = mysql_fetch_array(mysql_query("SELECT 
            COUNT(*) AS C
        FROM
            genealogy AS G
                LEFT JOIN
            g5_member AS M ON G.mb_id = M.mb_id
        WHERE
            G.recommenderID = '{$mb_id}'
                AND DATE_ADD(M.renewal, INTERVAL + 3 month) >= CURDATE()"));

    $rank = "";
    if ($row["team1"] >= 800 && $row["team2"] >= 800) {
        if (rankCountCheck($mb_id, 'Crown AMBASSADOR', 1)) {
            $rank = "Royal Crown AMBASSADOR";
        } else {
            $rank = "Crown AMBASSADOR";
        }
    } else if ($row["team1"] >= 500 && $row["team2"] >= 500) {
        $rank = "Triple AMBASSADOR";
    } else if ($row["team1"] >= 300 && $row["team2"] >= 300) {
        $rank = "Double AMBASSADOR";
    } else if ($row["team1"] >= 200 && $row["team2"] >= 200) {
        $rank = "AMBASSADOR";
    } else if ($row["team1"] >= 125 && $row["team2"] >= 125) {
        $rank = "5 STAR";
    } else if ($row["team1"] >= 84 && $row["team2"] >= 84) {
        $rank = "4 STAR";
    } else if ($row["team1"] >= 50 && $row["team2"] >= 50) {
        $rank = "3 STAR";
    } else if ($row["team1"] >= 24 && $row["team2"] >= 24) {
        $rank = "2 STAR";
    } else if ($row["team1"] >= 13 && $row["team2"] >= 13) {
        $rank = "1 STAR";
    } else if ($row["team1"] >= 6 && $row["team2"] >= 6) {
        $rank = "Triple MASTER";
    } else if ($row["team1"] >= 2 && $row["team2"] >= 2) {
        $rank = "Double MASTER";
    } else {
        $rank = "MASTER";
    }

    return $rank;
}


function rankCountCheck($mb_id, $accountRank, $N)
{
    global $connection;
    // 해당 아이디 산하로 해당 직급 이상인 회원이 1팀, 2팀에 지정한 인원 만큼 있는 지 체크 해서 참 거짓 리턴하는 함수
    
    $rankOrderByRow = mysql_fetch_array(mysql_query("select * from rankOrderBy where rankAccount = '{$accountRank}'"));

    $teamCount = Array(0, 0);

    $TMRow = mysql_fetch_array(mysql_query("select * from teamMembers where mb_id = '{$mb_id}'"));

    
    $check1 = mysql_fetch_array(mysql_query("select a.*, b.orderNum  from g5_member as a inner join rankOrderBy as b on a.accountRank = b.rankAccount where mb_id = '{$TMRow["1T_ID"]}'"));
    $check2 = mysql_fetch_array(mysql_query("select a.*, b.orderNum  from g5_member as a inner join rankOrderBy as b on a.accountRank = b.rankAccount where mb_id = '{$TMRow["2T_ID"]}'"));

    if ($TMRow["1T_ID"] == "" || $TMRow["2T_ID"] == "") {
        return false;
    }

    if ($check1["orderNum"] <= $rankOrderByRow["orderNum"] && $check1["orderNum"] != "") {
        $teamCount[0]++;
    }
    if ($check2["orderNum"] <= $rankOrderByRow["orderNum"] && $check2["orderNum"] != "") {
        $teamCount[1]++;
    }

    if ($teamCount[0] >= $N && $teamCount[1] >= $N) {
        return true;
    }

    $T1CRow1 = mysqli_fetch_array(mysqli_query($connection, "CALL SP_TREE_CNT ( '{$TMRow["1T_ID"]}', '{$accountRank}', " . ($N - $teamCount[0]) . ", @A )"));
    mysqli_store_result($connection);
    mysqli_next_result($connection);
    $teamCount[0] += $T1CRow1["N_RTN"];
    
    
    $T1CRow2 = mysqli_fetch_array(mysqli_query($connection, "CALL SP_TREE_CNT ( '{$TMRow["2T_ID"]}', '{$accountRank}', " . ($N - $teamCount[1]) . ", @A )"));
    mysqli_store_result($connection);
    mysqli_next_result($connection);
    $teamCount[1] += $T1CRow2["N_RTN"];

    
    

    if ($T1CRow1["N_RTN"] == 1 && $T1CRow2["N_RTN"] == 1) {
        return true;
    }
    
    return false;
}


?>
