<?php
include_once('./_common.php');
include_once('./dbConn.php');

$MEMBERTABLE = "g5_member";
$GENEALOGYTABLE = "genealogy";
$TEAMMEMBERTABLE = "teamMembers";
$DAYPOINTTABLE = "dayPoint";

$VMP = (isset($_POST['payvmc']) && trim($_POST['payvmp'])) ? trim($_POST['payvmp']) : 0;
$VMC = (isset($_POST['payvmc']) && trim($_POST['payvmc'])) ? trim($_POST['payvmc']) : 0;
$PSUM = (isset($_POST['paypoint_sum']) && trim($_POST['paypoint_sum'])) ? trim($_POST['paypoint_sum']) : 0;

$MEMTOTAL = (isset($_POST['memTotal']) && trim($_POST['memTotal'])) ? trim($_POST['memTotal']) : "";
$TEAM[0]['name'] = (isset($_POST['team1']) && trim($_POST['team1'])) ? trim($_POST['team1']) : "";
$TEAM[1]['name'] = (isset($_POST['team2']) && trim($_POST['team2'])) ? trim($_POST['team2']) : "";
$TEAM[2]['name'] = (isset($_POST['team3']) && trim($_POST['team3'])) ? trim($_POST['team3']) : "";
$TEAM[3]['name'] = (isset($_POST['team4']) && trim($_POST['team4'])) ? trim($_POST['team4']) : "";
$TEAM[4]['name'] = (isset($_POST['team5']) && trim($_POST['team5'])) ? trim($_POST['team5']) : "";
$TEAM[5]['name'] = (isset($_POST['team6']) && trim($_POST['team6'])) ? trim($_POST['team6']) : "";

$PUSER[0]['name'] = (isset($_POST['user_name1']) && trim($_POST['user_name1'])) ? trim($_POST['user_name1']) : "";
$PUSER[0]['num'] = (isset($_POST['user_num1']) && trim($_POST['user_num1'])) ? trim($_POST['user_num1']) : "";
$PUSER[0]['team'] = (isset($_POST['s1_teamChoice']) && trim($_POST['s1_teamChoice'])) ? trim($_POST['s1_teamChoice']) : "";

if ($MEMTOTAL == "full") {
    $TEAM[6]['name'] = (isset($_POST['team7']) && trim($_POST['team7'])) ? trim($_POST['team7']) : "";
    $TEAM[7]['name'] = (isset($_POST['team8']) && trim($_POST['team8'])) ? trim($_POST['team8']) : "";
    $TEAM[8]['name'] = (isset($_POST['team9']) && trim($_POST['team9'])) ? trim($_POST['team9']) : "";
    $TEAM[9]['name'] = (isset($_POST['team10']) && trim($_POST['team10'])) ? trim($_POST['team10']) : "";
    $TEAM[10]['name'] = (isset($_POST['team11']) && trim($_POST['team11'])) ? trim($_POST['team11']) : "";
    $TEAM[11]['name'] = (isset($_POST['team12']) && trim($_POST['team12'])) ? trim($_POST['team12']) : "";

    $PUSER[1]['name'] = (isset($_POST['user_name2']) && trim($_POST['user_name2'])) ? trim($_POST['user_name2']) : "";
    $PUSER[1]['num'] = (isset($_POST['user_num2']) && trim($_POST['user_num2'])) ? trim($_POST['user_num2']) : "";
    $PUSER[1]['team'] = (isset($_POST['s2_teamChoice']) && trim($_POST['s2_teamChoice'])) ? trim($_POST['s2_teamChoice']) : "";
}

$USERID = $member['mb_id'];
$USERNAME = $member['mb_name'];


$result = mysql_query("select * from {$MEMBERTABLE} where mb_id = '{$USERID}'");
$row = mysql_fetch_array($result);
$parent = $row;


$valCheckRow = mysql_fetch_array(mysql_query("select * from dayPoint where date like '" . date("Y-m-d") . "%' and way = 'businessPack2' and mb_id = '{$USERID}'"));
if ($valCheckRow["mb_id"] != "") {
    $valCheckRow2 = mysql_fetch_array(mysql_query("select SUM(VMC+VMP) as total from dayPoint where mb_id = '{$USERID}' and date like '" . date("Y-m-d") . "' and (VMC<0 or VMP<0) and way like 'transfer;%'"));
    if ((int)$valCheckRow2["total"] == -1920000) {
        echo "<script>window.location.href = '/myOffice';</script>";
        exit();
    }
}

//구매자 에게 businessPack2 Log
dayPoint($USERID, '0', 'businessPack2');

foreach ($PUSER as $key => $value) {
    teamMembersCheck($value['num']);
}
///////////////////////////////
////////////////////////////////

$dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$member['renewal']}', interval +4 month) AS date"));
$timestamp = $dateCheck_1["date"];
$timenow = date("Y-m-d");

$str_now = strtotime($timenow);
$timestamp2 = strtotime($timestamp);

$cVMC = $member['VMC'] - $VMC;
$cVMP = $member['VMP'] - $VMP;

$renewalDate;
$way;

if ($member['accountType'] == "VM" || ($member['accountType'] == "CU" && $member['renewal'] != null)) {
    // VM 기간인지 익액티브 상태인지를 체크하여 리뉴얼 날짜를 결정하는 로직
    $now_temp = strtotime(date("Y-m-d"));
    $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$member['renewal']}', interval +4 month) AS date"));
    $timestamp_temp = strtotime($dateCheck_1["date"]); // VM 기간 마지막 일

    if ($timestamp_temp >= $now_temp) { // 참이면 VM 기간
        $renewalDate = $timestamp; // 리뉴얼 날짜 + 1달
    } else {
        $renewalDate = $timenow; // 리뉴얼 날짜 오늘
    }
    $way = 'signup';
}


mysql_query("update {$MEMBERTABLE} set VMC = '{$cVMC}', VMR = '{$cVMR}', VMP = '{$cVMP}' where mb_id = '{$USERID}'");
//    mysql_query("insert into {$DAYPOINTTABLE} set mb_id = '{$USERID}', VMC = -{$VMC}, VMR = 0, VMP = -{$VMP}, date = CURDATE(), way = '{$way}'"); 잠시 주석
$data = insertMember($USERID, $USERNAME, $TEAM, $PUSER, $MEMTOTAL);

alert("비즈니스 팩 2 구매가 완료되어 관리형 페이지로 이동합니다.", "/myOffice/box_1.php");


//////////////////////////////////////////////// 함수 정의 부 //////////////////////////////////////////
function dayPoint($MBID, $VMP, $WAY)
{
    global $DAYPOINTTABLE;
    $sql = "INSERT INTO {$DAYPOINTTABLE} SET ";
    $sql .= " mb_id = '" . $MBID . "'";
    $sql .= ", VMP = '" . $VMP . "'";
    $sql .= ", date = '" . date('Y-m-d') . "'";
    $sql .= ", way = '" . $WAY . "'";
    mysql_query($sql);
}

function teamMembersCheck($MBID)
{
    global $TEAMMEMBERTABLE;
    $row = mysql_fetch_array(mysql_query("SELECT count(n) as cnt FROM {$TEAMMEMBERTABLE} where mb_id = '{$MBID}'"));
    if($row['cnt']==0){
        mysql_query("INSERT INTO {$TEAMMEMBERTABLE} SET mb_id = '{$MBID}'");
    }
    return true;
}

function insertMember($USERID, $USERNAME, $TEAMARRAY, $PUSER, $MEMTOTAL)
{
    global $parent;
    global $MEMBERTABLE;
    global $GENEALOGYTABLE;
    global $TEAMMEMBERTABLE;
    foreach ($TEAMARRAY as $key => $value) {

        $temp2 = GenerateString(8);

        $sql = "INSERT INTO {$MEMBERTABLE}
				set mb_id = '{$temp2}',
					mb_password = '{$parent[mb_password]}',
					mb_name = '{$value[name]}',
					mb_nick = '{$temp2}',
					mb_nick_date = '" . G5_TIME_YMD . "',
					mb_email = '{$temp2}@gvmp.company',
					mb_homepage = '{$parent[mb_homepage]}',
					mb_level = '{$parent[mb_level]}',
					mb_sex = '{$parent[mb_sex]}',
					mb_birth = '{$parent[mb_birth]}',
					mb_tel = '{$parent[mb_tel]}',
					mb_hp = '{$parent[mb_hp]}',
					mb_certify = '{$parent[mb_certify]}',
					mb_adult = '{$parent[mb_adult]}',
					mb_dupinfo = '{$parent[mb_dupinfo]}',
					mb_zip1 = '{$parent[mb_zip1]}',
					mb_zip2 = '{$parent[mb_zip2]}',
					mb_addr1 = '{$parent[mb_addr1]}',
					mb_addr2 = '{$parent[mb_addr2]}',
					mb_addr3 = '{$parent[mb_addr3]}',
					mb_addr_jibeon = '{$parent[mb_addr_jibeon]}',
					mb_signature = '{$parent[mb_signature]}',
					mb_recommend = '{$parent[mb_recommend]}',
					mb_point	 = '0',
					mb_today_login = '" . G5_TIME_YMDHIS . "',
					mb_login_ip = '{$_SERVER['REMOTE_ADDR']}',
					mb_datetime = '" . G5_TIME_YMDHIS . "',
					mb_ip = '{$_SERVER['REMOTE_ADDR']}',
					mb_leave_date = '',
					mb_mailling = '{$parent[mb_mailling]}',
					mb_sms = '{$parent[mb_sms]}',
					mb_open = '{$parent[mb_open]}',
					mb_profile = '{$parent[mb_profile]}',
					mb_email_certify = '{$parent[mb_email_certify]}',
					mb_open_date = '" . G5_TIME_YMDHIS . "',
					birth = '{$parent[birth]}',
					bankName = '{$parent[bankName]}',
					accountHolder = '{$parent[accountHolder]}',
					accountNumber = '{$parent[accountNumber]}',
					accountType = 'VM',
					accountRank = 'VM',
					VMC = '0',
					VMR = '0',
					VMP = '0',
					V = '0',
					renewal = '" . G5_TIME_YMDHIS . "',
					VCash = '0',
					VPay = '0'";
        mysql_query($sql);

        $temp1 = mysql_insert_id();
        $temp = $temp1 - 1;
        $temp2 = (string)$temp;
        $temp2 = str_pad($temp2, "8", "0", STR_PAD_LEFT);

        $sql = "update {$MEMBERTABLE}
                set mb_nick = '{$temp2}',
                 mb_id = '{$temp2}',
                 mb_email = '{$temp2}@gvmp.company'
              where mb_no = '$temp1' ";
        $result = mysql_query($sql);

        if ($result) {
            $TEAMARRAY[$key]['num'] = $temp2;
            teamMembersCheck($temp2); //넣기

            $sendresult = dayPoint($USERID, '-2080000', 'transfer;' . $temp2 . '에게 포인트 전송'); //구입한 맴버들에게 160000VMP 지급 Log
            $responresult = dayPoint($temp2, '2080000', 'transfer;' . $USERID . '님한테 포인트 받음'); //구입한 맴버가 160000VMP 받음기록 Log
            $presult = dayPoint($temp2, '-2080000', 'newRenewal'); //구입한 맴버가 160000VMP 사용기록 Log
        }
    }

    foreach ($TEAMARRAY as $key => $value) {
        $sql = "INSERT INTO {$GENEALOGYTABLE} SET ";
        $sql .= " mb_id='" . $value['num'] . "'";
        $sql .= ", mb_name='" . $value['name'] . "'";
        $tsql = "UPDATE {$TEAMMEMBERTABLE} SET ";
        if ($key < 6) { //1번투터 6번까지
            $TEAM = $PUSER[0]['team'];
            $TMBID = ($key > 0) ? $TEAMARRAY[$key - 1]['num'] : "";
            $TID1 = $value['num'];
            $TNAME1 = $value['name'];
            $TID2 = "";
            $TNAME2 = "";
            if ($MEMTOTAL == "full") { //기존 12명 로직
                if ($key == 0) {
                    $RNAME = $USERNAME;            //추천인 로직에 따라 (산사람)
                    $RID = $USERID;                //추천인 로직에 따라 (산사람)
                    $SNAME = $PUSER[0]['name']; //후원인 바로 윗사람 (앞 page 입력한 1팀장)
                    $SID = $PUSER[0]['num'];    //후원인 바로 윗사람 (앞 page 입력한 1팀장)
                    $TEAM = $TEAM;                //팀				(앞 page 선택한 2팀장 팀)

                    $TMBID = $PUSER[0]['num'];
                    if ($TEAM == 1) {
                        $TID1 = $value['num'];
                        $TNAME1 = $value['name'];
                        $TID2 = "";
                        $TNAME2 = "";
                    } else {
                        $TID1 = "";
                        $TNAME2 = "";
                        $TID2 = $value['num'];
                        $TNAME2 = $value['name'];
                    }
                } elseif ($key == 1) {
                    $RNAME = $USERNAME;                        //추천인 로직에 따라
                    $RID = $USERID;                            //추천인 로직에 따라
                    $SNAME = $TEAMARRAY[$key - 1]['name'];    //후원인 바로 윗사람
                    $SID = $TEAMARRAY[$key - 1]['num'];        //후원인 바로 윗사람
                    $TEAM = "1";                            //팀
                } elseif ($key > 1 && $key < 5) {
                    $RNAME = $TEAMARRAY[0]['name'];            //추천인 로직에 따라
                    $RID = $TEAMARRAY[0]['num'];            //추천인 로직에 따라
                    $SNAME = $TEAMARRAY[$key - 1]['name'];    //후원인 바로 윗사람
                    $SID = $TEAMARRAY[$key - 1]['num'];        //후원인 바로 윗사람
                    $TEAM = "1";                            //팀
                } else {
                    $RNAME = $TEAMARRAY[1]['name'];            //추천인 로직에 따라
                    $RID = $TEAMARRAY[1]['num'];            //추천인 로직에 따라
                    $SNAME = $TEAMARRAY[$key - 1]['name'];    //후원인 바로 윗사람
                    $SID = $TEAMARRAY[$key - 1]['num'];        //후원인 바로 윗사람
                    $TEAM = "1";                            //팀
                }
            } else { //새로 추가된 6명 로직
                if ($key == 0) {
                    $RNAME = $USERNAME;            //추천인 로직에 따라 (산사람)
                    $RID = $USERID;                //추천인 로직에 따라 (산사람)
                    $SNAME = $PUSER[0]['name']; //후원인 바로 윗사람 (앞 page 입력한 1팀장)
                    $SID = $PUSER[0]['num'];    //후원인 바로 윗사람 (앞 page 입력한 1팀장)
                    $TEAM = $TEAM;                //팀				(앞 page 선택한 2팀장 팀)
                    $TMBID = $PUSER[0]['num'];
                } elseif ($key == 1 || $key == 2) {
                    $RID = $USERID;                            //추천인 로직에 따라
                    $SNAME = $TEAMARRAY[$key - 1]['name'];    //후원인 바로 윗사람
                    $SID = $TEAMARRAY[$key - 1]['num'];        //후원인 바로 윗사람
                    $TEAM = $PUSER[0]['team'];
                } else {
                    $RNAME = $TEAMARRAY[0]['name'];            //추천인 회원1을 추천
                    $RID = $TEAMARRAY[0]['num'];            //추천인 회원1을 추천
                    $SNAME = $TEAMARRAY[$key - 1]['name'];    //후원인 바로 윗사람
                    $SID = $TEAMARRAY[$key - 1]['num'];        //후원인 바로 윗사람
                    $TEAM = $PUSER[0]['team'];
                }

                if ($TEAM == 1) {
                    $TID1 = $value['num'];
                    $TNAME1 = $value['name'];
                    $TID2 = "";
                    $TNAME2 = "";
                } else {
                    $TID1 = "";
                    $TNAME2 = "";
                    $TID2 = $value['num'];
                    $TNAME2 = $value['name'];
                }
            }
        } else {
            $TEAM = $PUSER[1]['team'];
            $TMBID = $TEAMARRAY[$key - 1]['num'];
            $TID1 = $value['num'];
            $TNAME1 = $value['name'];
            $TID2 = "";
            $TNAME2 = "";
            /*1팀 2팀 정하는 구간*/
            if ($TEAM == 1) {
                $TID1 = $value['num'];
                $TNAME1 = $value['name'];
                $TID2 = "";
                $TNAME2 = "";
            } else {
                $TID1 = "";
                $TNAME1 = "";
                $TID2 = $value['num'];
                $TNAME2 = $value['name'];
            }
            /*1팀 2팀 정하는 구간*/
            if ($key == 6) {
                $RNAME = $USERNAME;            //추천인 로직에 따라 (산사람)
                $RID = $USERID;                //추천인 로직에 따라 (산사람)
                $SNAME = $PUSER[1]['name']; //후원인 바로 윗사람 (앞 page 입력한 2팀장)
                $SID = $PUSER[1]['num'];    //후원인 바로 윗사람 (앞 page 입력한 2팀장)
//				$TEAM = $TEAM;				//팀				(앞 page 선택한 2팀장 팀)
                $TMBID = $PUSER[1]['num'];
            } elseif ($key > 6 && $key < 10) {
                $RNAME = $TEAMARRAY[6]['name'];            //추천인 로직에 따라
                $RID = $TEAMARRAY[6]['num'];            //추천인 로직에 따라
                $SNAME = $TEAMARRAY[$key - 1]['name'];    //후원인 바로 윗사람
                $SID = $TEAMARRAY[$key - 1]['num'];        //후원인 바로 윗사람
//				$TEAM = "1";							//팀
//				$TEAM = $TEAM;							//팀				(앞 page 선택한 2팀장 팀)
            } else {
                $RNAME = $TEAMARRAY[7]['name'];            //추천인 로직에 따라
                $RID = $TEAMARRAY[7]['num'];            //추천인 로직에 따라
                $SNAME = $TEAMARRAY[$key - 1]['name'];    //후원인 바로 윗사람
                $SID = $TEAMARRAY[$key - 1]['num'];        //후원인 바로 윗사람
//				$TEAM = "1";							//팀
//				$TEAM = $TEAM;							//팀				(앞 page 선택한 2팀장 팀)
            }
        }
        $sql .= ", recommenderName='" . $RNAME . "'";
        $sql .= ", recommenderID='" . $RID . "'";
        $sql .= ", sponsorName='" . $SNAME . "'";
        $sql .= ", sponsorID='" . $SID . "'";
        $sql .= ", sponsorTeam='" . $TEAM . "'";
        $result = mysql_query($sql); //GENEALOGYTABLE 기록

        $ctsql = "";
        if ($TID1 && $TNAME1) {
            $ctsql .= " 1T_ID='" . $TID1 . "'";
            $ctsql .= ", 1T_name='" . $TNAME1 . "'";
        }
        if ($TID2 && $TNAME2) {
            if ($ctsql) $ctsql .= ",";
            $ctsql .= " 2T_ID='" . $TID2 . "'";
            $ctsql .= ", 2T_name='" . $TNAME2 . "'";
        }
        $tsql = $tsql . $ctsql . " WHERE mb_id = '" . $TMBID . "'";
        $tresult = mysql_query($tsql); //TEAMMEMBERTABLE Update

        mysql_query("insert into plusCountListTBL set mb_id = '{$value['num']}'"); // VM 카운팅 리스트(증가)
    }
}


function GenerateString($length)
{
    $characters = "0123456789";
    $characters .= "abcdefghijklmnopqrstuvwxyz";
    $characters .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $characters .= "_";

    $string_generated = "";

    $nmr_loops = $length;
    while ($nmr_loops--) {
        $string_generated .= $characters[mt_rand(0, strlen($characters) - 1)];
    }

    return $string_generated;
}

?>