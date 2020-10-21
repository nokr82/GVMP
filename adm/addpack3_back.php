<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/myOffice/dbConn.php');
    
    // 애드팩 #3 수당을 지급하고 간접수익을 지급하는 로직
    
    $_POST['mb_id'] = str_pad($_POST['mb_id'],"8","0",STR_PAD_LEFT);
    
    $row = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$_POST['mb_id']}'"));
    $roww = mysql_fetch_array(mysql_query("select count(*) as n from dayPoint where mb_id = '{$_POST['mb_id']}' and date like '{$_POST['date_add3']}%' and way = '애드팩 #3'"));
    $rowww = mysql_fetch_array(mysql_query("select sum(VMP) as s from dayPoint where mb_id = '{$_POST['mb_id']}' and date like '{$_POST['date_add3']}%' and way like '애드팩 #3%'"));
    
    if( $row['mb_id'] == "" ) {
        echo "입력한 아이디가 존재하지 않습니다.";
        exit();
    } else if( ! ($row["accountType"] == "CU" || $row["accountType"] == "VD" || $row["accountType"] == "SM" || $row["accountType"] == "VM") ) {
        echo "입력한 아이디 계정 종류가 판별되지 않습니다.";
        exit();
    } else if( $roww['n'] == 5 ) {
        echo "입력한 아이디가 금일 5회 지급되었습니다.";
        exit();
    } else if( $rowww['s'] >= 5000 ) {
        echo "입력한 아이디가 금일 5,000P 지급되었습니다.";
        exit();
    }
    
    
    // 계정 종류에 따라 지급될 VMP 포인트 산정
    // CU : SM : VM    200 : 400 : 700
    $point; // 입력 된 아이디가 지급받을 P 포인트
    switch( $row["accountType"] ) {
        case "CU" :
           $point = 200; break;
        case "VD" :
           $point = 200; break;
        case "SM" :
           $point = 400; break;
        case "VM" :
           $point = 700; break;
    }
    $commissionP = 700 - $point; // 직추에게 간접수익으로 지급될 P 포인트
    
    if( $rowww['s']+$point > 5000 ) {
        $point = 5000 - $rowww['s'];
    }
    
    
    // 포인트 지급
    mysql_query("update g5_member set VMP = VMP + {$point} where mb_id = '{$_POST['mb_id']}'") or die("수행 중 장애가 발생 하였습니다.");
    mysql_query("insert into dayPoint set mb_id = '{$_POST['mb_id']}', VMP = {$point}, date = '{$_POST['date_add3']}', way = '애드팩 #3'") or die("수행 중 장애가 발생 하였습니다.");
    
    
    
    
    // 아래 로직을 통해 추천인한테 포인트를 커미션 시키는 로직입니다
    $row3 = mysql_fetch_array(mysql_query("select * from genealogy where mb_id = '{$row['mb_id']}'")) or die("수행 중 장애가 발생 하였습니다.");
    $row4 = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$row3['recommenderID']}'")) or die("수행 중 장애가 발생 하였습니다.");
    
    if( $row3['recommenderID'] != "" && $row['accountType'] != "VM" && $commissionP != 0 ) {
        // 추천인이 존재하면서 자기 자신이 VM 아니면서 지급될 커미션 포인트가 0원이 아니면 참
        $now = date("Y-m-d");
        $now_temp = strtotime($now);
        $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row4['renewal']}', interval +4 month) AS date"));
        $timestamp = $dateCheck_1["date"];
        $timestamp_temp = strtotime($timestamp);

        // 밑에 조건을 통해 추천인이 VM이면서 리뉴얼 유예기간이 아니면 참
        if( $row4['accountType'] == "VM" &&  $timestamp_temp >= $now_temp ) {
            $roww4 = mysql_fetch_array(mysql_query("select sum(VMP) as s from dayPoint where mb_id = '{$row3['recommenderID']}' and date like '{$_POST['date_add3']}%' and way like '애드팩 #3%'"));
            // 금일 애드팩 #3로 받은 수당이 5000원 미만이여야 참
            if( ! ($roww4['s'] >= 5000) ) {
                // 금일 받은 애드팩 수당과 지금 받은 수당을 더했을 때 5000원이 넘으면 지금 줄 수당을 줄이기
                if( $roww4['s']+$commissionP > 5000 ) {
                    $commissionP = 5000 - $roww4['s'];
                }
                mysql_query("insert into dayPoint set mb_id = '{$row4['mb_id']}', VMP = {$commissionP}, date = '{$_POST['date_add3']}', way = '애드팩 #3 커미션;{$row['mb_id']}'") or die("수행 중 장애가 발생 하였습니다.");
                mysql_query("update g5_member set VMP = VMP + {$commissionP} where mb_id = '{$row4['mb_id']}'") or die("수행 중 장애가 발생 하였습니다.");
            }
        }
    }

    echo "<script> 
    alert(\"승인 완료. {$row['mb_name']}({$_POST['mb_id']})님에게 {$point}P를 지급하였습니다.\");
    document.location.href='/adm/addpack3.php'; 
    </script>";
?>

























