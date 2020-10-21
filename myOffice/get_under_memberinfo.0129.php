<?php
include_once('./dbConn.php');
include_once('./dbConn2.php');
$p_num = $_GET['num'];
$num = 0 + $p_num;
$id = $_GET['mb_id'];
$arr_account = $_GET['arr_account'];
$arr_level = $_GET['arr_level'];
$search_type = $_GET['search_type'];
$search = $_GET['search_content'];
$pc_mobile = $_GET['pc_mobile'];


$sql_search = '';
if ($search_type == 'id' && $search != '') {
    $search_type = 't.mb_id';
    $sql_search = "and {$search_type} = '{$search}'";
} else if ($search_type == 'name' && $search != '') {
    $search_type = 't.mb_name';
    $sql_search = "and {$search_type} like '{$search}%'";
}

if (isset($arr_level)) {
    if (count($arr_level) == 1) {
        $sql_level .= " and t.accountRank='{$arr_level[0]}'";
    } else {
        $sql_level .= " and (t.accountRank='{$arr_level[0]}'";
        for ($i = 1, $iMax = count($arr_level); $i < $iMax; $i++) {
            $sql_level .= " or t.accountRank='{$arr_level[$i]}'";
            if ($iMax - 1 == $i) {
                $sql_level .= ')';
            }
        }
    }
}

if (isset($arr_account)) {
    if (count($arr_account) == 1) {
        $sql_type .= " and t.accountType='{$arr_account[0]}'";
    } else {
        $sql_type .= " and (t.accountType='{$arr_account[0]}'";
        for ($i = 1, $iMax = count($arr_account); $i < $iMax; $i++) {
            $sql_type .= " or t.accountType='{$arr_account[$i]}'";
            if ($iMax - 1 == $i) {
                $sql_type .= ')';
            }
        }
    }
}

$pg_num = $_GET['page'];
if ($pg_num == 1 || $pg_num == '') {
    $pg_num = 0;
}

if ($pc_mobile == 'pc') {
    $page = '50';
} else {
    $page = 10;
    $num = 10 * $pg_num;
}

//echo "CALL SP_TREE('{$id}');SELECT t.*, m.renewal,m.mb_email,m.mb_hp,m.mb_datetime FROM genealogy_tree as t INNER JOIN g5_member as m ON t.mb_no = m.mb_no WHERE rootid = '{$id}' {$sql_search} {$sql_level} {$sql_type} order by lv limit {$num}, 50";
////exit();
if ($sql_search != '' || ($sql_type != '' && $sql_level != '')) {
    $sql = "SELECT t.*,m.mb_email,m.mb_hp,m.mb_datetime FROM genealogy_tree as t INNER JOIN g5_member as m ON t.mb_no = m.mb_no WHERE rootid = '{$id}' {$sql_search} {$sql_level} {$sql_type} order by t.mb_id desc limit {$num}, {$page}";

    $memberInfo = mysql_query($sql);
    $count = mysql_num_rows($memberInfo);
    if ($count == 0) {
        echo "empty";
    } else {
        while ($row2 = mysql_fetch_array($memberInfo)) {
            $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row2['renewal']}', interval +4 month) AS date"));
            $renewal = $dateCheck_1["date"];
            $today = date('Y-m-d');
            echo " 
   <dl>
            <dt class=\"dot\"><span class=\"disib\"></span>이름(ID)</dt>
            <dd>{$row2['mb_name']}({$row2['mb_id']})</dd>
            <dt class=\"dot\"><span class=\"disib\"></span>계정 종류 / 직급</dt>
            <dd>{$row2['accountType']} / {$row2['accountRank']}</dd>
            <dt class=\"dot\"><span class=\"disib\"></span>추천인 이름 / 추천인 회원 번호 </dt>
            <dd>{$row2['recommenderName']} / {$row2['recommenderID']}</dd>
            <dt class=\"dot\"><span class=\"disib\"></span>후원인 이름 / 후원인 회원 번호 / 후원인 팀</dt>
            <dd>{$row2['sponsorName']} / {$row2['sponsorID']} / {$row2['sponsorTeam']}팀 소속</dd>
            <dt class=\"dot\"><span class=\"disib\"></span>리뉴얼 날짜</dt>";
            if ($renewal < $today) {
                if ($row2['accountType']=='CU'){
                    echo "<dd>CU</dd>";
                }else{
                    echo " <dd style=\"color: red\"> {$renewal} </dd>";
                }
            } else {
                if ($row2['renewal']=='' || $row2['accountType']=='CU'){
                    echo "<dd>CU</dd>";
                }else{
                    echo "<dd> {$renewal} </dd>";
                }

            }
            echo "<dt class=\"dot\"><span class=\"disib\"></span>가입일</dt>
            <dd><time datetime=\"2020-01-14T19:47:14\">{$row2['mb_datetime']}</time></dd>
            <dt class=\"dot\"><span class=\"disib\"></span>연락처</dt>
            <dd>{$row2['mb_hp']}</dd>
            <dt class=\"dot\"><span class=\"disib\"></span>이메일</dt>
            <dd>{$row2['mb_email']}</dd>
        </dl>";
        }
    }

}


?>
