<?php
    include_once ('./dbConn.php');
    // 회원가입에서 계좌인증이 성공했을 때 DB에 인증 계좌 정보 남기는 코드
    
    mysql_query("insert into accountCheck set bankName = '{$_GET['bankName']}', accountHolder = '{$_GET['accountHolder']}', accountNumber = '{$_GET['accountNumber']}', time = now()");
    
?>

<script>
    alert("계좌인증이 완료되었습니다. 회원가입을 이어서 진행해 주세요.");
    window.open("about:blank","_self").close();
</script>
