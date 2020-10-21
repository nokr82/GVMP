<?php
include_once('./dbConn.php');
include_once('./dbConn2.php');
$p_num = $_GET['num'];
$id = $_GET['mb_id'];
$arr_level = $_GET['arr_level'];
$arr_first = $_GET['arr_first'];
$search_type = $_GET['search_type'];
$search = $_GET['search_content'];

$date_start = $_GET['date_start'];
$date_end = $_GET['date_end'];

$pc_mobile = $_GET['pc_mobile'];


$mysql = "";

$pg_num = $_GET['page'];
if ($pg_num == 1 || $pg_num == '') {
    $pg_num = 0;
}

if ($pc_mobile == 'pc') {
    $page = 50 + $p_num;
    $p_num = $p_num + 1;
} else {
    if ($pg_num > 0) {
        $p_num = 10 * $pg_num - 10 + 1;
    } else {
        $p_num = 10 * $pg_num + 1;
    }
    $page = 10 + $p_num;

}
$e_date = str_replace('-', '', $date_end);
$s_date = str_replace('-', '', $date_start);
$arr_level_str = implode(',', $arr_level);

$v_Self = 0;
$v_first = 'N';

if (in_array('1', $arr_first)) {
    $v_Self = 1;
}
if (in_array('2', $arr_first)) {
    $v_first = 'Y';
}
$sql = "CALL sp_history ( '{$id}', {$e_date}, {$s_date}, '{$arr_level_str}', '{$v_first}', {$v_Self}, 0, {$p_num}, {$page} ,null,null);";

if ($search_type == 'id' && $search != '') {
    $sql = "CALL sp_history ( '{$id}', {$e_date}, {$s_date}, '{$arr_level_str}', '{$v_first}', {$v_Self}, 0, {$p_num}, {$page},null,'{$search}');";
} else if ($search_type == 'name' && $search != '') {
    $sql = "CALL sp_history ( '{$id}', {$e_date}, {$s_date}, '{$arr_level_str}', '{$v_first}', {$v_Self}, 0, {$p_num}, {$page} ,'{$search}',null);";
}

if ($arr_level_str != '') {
    $memberInfo = mysql_query($sql);
    while ($row2 = mysql_fetch_array($memberInfo)) {
        $mysql .= "
          <dl>
                <dt class=\"dot\"><span class=\"disib\"></span>이름(ID)</dt>
                <dd>{$row2['mb_name']}</dd>
                <dt class=\"dot\"><span class=\"disib\"></span>직급 변경 전/후</dt>
                <dd>
                    <span>{$row2['rank']}</span>
                    <p><span class=\"blind blue_arrow disib\">화살표</span>{$row2['change_rank']}</p>
                </dd> ";

        $mysql .= "<dt class=\"dot\"><span class=\"disib\"></span>최초 달성 여부</dt>";
        if ($row2['first_check'] == 'Y') {
            $mysql .= "<dd class=\"color_red\">{$row2['first_check']}</dd>";
        } else {
            ;
            $mysql .= "<dd>{$row2['first_check']}</dd>";
        }
        $mysql .= "<dt class=\"dot\"><span class=\"disib\"></span>변경 시기</dt>
                <dd><time datetime=\"{$row2['datetime']}\">{$row2['datetime']}</time></dd>
            </dl>";
    }
}
if (mysql_num_rows($memberInfo) > 0) {
    echo $mysql;
} else {
    echo 'empty';
}

?>
