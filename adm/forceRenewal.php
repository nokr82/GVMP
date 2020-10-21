<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');
    set_time_limit(0);
    $link = mysqli_connect("192.168.0.30","root","Neungsoft1!", "gyc5");
    
    // 강제 리뉴얼을 처리하는 백엔드 로직
    
    
    $_POST["before"] = trim($_POST["before"]);
    
    if( $_POST["before"] == "" ) {
        ?>
        <script>alert("ERROR : 파라미터 에러"); window.location.href = "/adm/member_modify.php";</script>
        <?php
    }
    
    $row = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$_POST["before"]}'"));
    if( $row["mb_id"] == "" ) {
        ?>
        <script>alert("ERROR : 존재하지 않는 계정입니다."); window.location.href = "/adm/member_modify.php";</script>
        <?php
    }
    if( $row["accountType"] != "CU" ) {
        ?>
        <script>alert("ERROR : CU 계정이 아닙니다."); window.location.href = "/adm/member_modify.php";</script>
        <?php
    }
    
    $row2 = mysql_fetch_array(mysql_query("select * from genealogy where mb_id = '{$_POST["before"]}'"));
    if( $row2["sponsorID"] == "99999999" ) {
        ?>
        <script>alert("ERROR : 조직도에 편성되지 않은 계정입니다."); window.location.href = "/adm/member_modify.php";</script>
        <?php
    }
    
    mysql_query("update g5_member set renewal = curdate(), accountType = 'VM', accountRank = 'VM' where mb_id = '{$_POST["before"]}'") or die("에러1");
    mysql_query("insert into dayPoint set mb_id = '{$_POST["before"]}', date = now(), way = 'forceRenewal'") or die("에러2");
    

    
    
    
    mysqli_multi_query($link, "CALL SP_TREE_R('{$_POST['before']}', +1)");


?>
<script>alert("리뉴얼이 완료되었습니다."); window.location.href = "/adm/member_modify.php";</script>