<?php
    $cssCheck = false; // _common.php에 인클루드 되는 CSS 적용 안 되게 하기 위한 변수
    include_once ('./_common.php');
    include_once ('../dbConn.php');
    
    function add_hyphen($tel) {
        $tel = preg_replace("/[^0-9]/", "", $tel);    // 숫자 이외 제거
        if (substr($tel,0,2)=='02')
            return preg_replace("/([0-9]{2})([0-9]{3,4})([0-9]{4})$/", "\\1-\\2-\\3", $tel);
        else if (strlen($tel)=='8' && (substr($tel,0,2)=='15' || substr($tel,0,2)=='16' || substr($tel,0,2)=='18'))
            // 지능망 번호이면
            return preg_replace("/([0-9]{4})([0-9]{4})$/", "\\1-\\2", $tel);
        else
            return preg_replace("/([0-9]{3})([0-9]{3,4})([0-9]{4})$/", "\\1-\\2-\\3", $tel);
    }
    
    
    $_POST["mb_id"] = trim($_POST["mb_id"]);
    $_POST["mb_name"] = trim($_POST["mb_name"]);
    $_POST["mb_hp"] = trim($_POST["mb_hp"]);
    $_POST["mb_email"] = trim($_POST["mb_email"]);
    $_POST["mb_birth"] = trim($_POST["mb_birth"]);
    $_POST["mb_zip"] = trim($_POST["mb_zip"]);
    $_POST["mb_addr1"] = trim($_POST["mb_addr1"]);
    $_POST["mb_addr2"] = trim($_POST["mb_addr2"]);
    $_POST["bankName"] = trim($_POST["bankName"]);
    $_POST["mb_accountHolder"] = trim($_POST["mb_accountHolder"]);
    $_POST["mb_accountNumber"] = trim($_POST["mb_accountNumber"]);

    
    mysql_query("update g5_member set mb_name = '{$_POST["mb_name"]}', mb_hp = '".add_hyphen(trim($_POST["mb_hp"]))."', mb_email = '{$_POST["mb_email"]}', birth = '{$_POST["mb_birth"]}', mb_zip1 = '". substr($_POST["mb_zip"], 0,3)."', mb_zip2 = '". substr($_POST["mb_zip"], 2,2)."', mb_addr1 = '{$_POST["mb_addr1"]}', mb_addr2 = '{$_POST["mb_addr2"]}', bankName = '{$_POST["bankName"]}', accountHolder = '{$_POST["mb_accountHolder"]}', accountNumber = '{$_POST["mb_accountNumber"]}' where mb_id = '{$_POST["mb_id"]}'");
    mysql_query("update membershipListTBL set modi_datetime = now() where mb_id = '{$_POST["mb_id"]}'");
?>

<script>
    alert("회원정보 수정이 완료되었습니다.");
    opener.location.reload();
    close();
</script>

