<?php

//0623 홍동우 관리자로그인 보안강화
include_once('./_common.php');

header("Content-Type: application/json");

$password = $_POST['password'];

if ($password == 'gv40364620') {

    $result['success'] = 'ok';
    $result['html'] = "
<nav class=\"menu\">
<input type=\"checkbox\" href=\"#\" class=\"menu-open\" name=\"menu-open\" id=\"menu-open\">
        <label class=\"menu-open-button\" for=\"menu-open\">
            <span class=\"vmplines line-1\"></span>
            <span class=\"vmplines line-2\"></span>
            <span class=\"vmplines line-3\"></span>
        </label>
        <a href=\"#\" class=\"menu-item item-1\">EXIT</a>
        <a href=\"#\" class=\"menu-item item-2\"><img src=\"/myOffice/images/collaboration.png\" alt=\"계정변환\"></a>
        <a href=\"#\" class=\"menu-item item-3\">2</a>
        <a href=\"#\" class=\"menu-item item-4\">3</a>
        <a href=\"#\" class=\"menu-item item-5\">4</a>
        <a href=\"#\" class=\"menu-item item-6\">5</a
            </nav>
";

} else {
    $result['success'] = 'fail';
}

echo json_encode($result);


?>