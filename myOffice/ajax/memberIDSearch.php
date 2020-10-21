<?php
include_once('../getMemberInfo.php');
include_once('../dbConn.php');

// 검색하겠다고한 ID가 로그인한 사용자의 하위에 포함되는 지 체크해서
// 포함되면 true 리턴 포함되지 않으면 false 리턴하는 로직

if ($_POST['loginID'] == $_POST['searchID']) {
    // 로그인한 ID를 서치하는 경우 트루 리턴
    echo "true";
    exit();
}

if ($_POST['loginID'] == 'admin') {
    echo "true";
    exit();
}
$sql = "SELECT count(*) as c FROM genealogy_tree where rootid = '{$_POST['loginID']}' and mb_id = '{$_POST['searchID']}'";

if ($row = mysql_fetch_array(mysql_query($sql))) {
    if ($row['c'] == 1) {
        echo "true";
        exit();
    }
}

echo "false";

mysqli_close($connection);
?>