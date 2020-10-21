<?php 
    // 회원가입시 지정한 후원인 팀으로 배치 가능한지 체크하는 로직
    // 리턴 값이 false면 해당 팀으로 배치 불가함을 나타냄

    header("Content-Type: text/html; charset=UTF-8");
    
    $reg_mb_sponsor_no = $_GET['reg_mb_sponsor_no']; // 후원인 ID
    $reg_mb_sponser_team = $_GET['reg_mb_sponser_team']; // 후원인 team
    
    
    
    if( $reg_mb_sponsor_no == "00000000" ) {
        echo "true";
        exit;
    }
    

    $conn = mysql_connect("192.168.0.30", "root", "Neungsoft1!");
    mysql_select_db("gyc5", $conn);
    
    mysql_query("set session character_set_results=utf8;");
    
    
    
    
    $vmCheckRow = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$reg_mb_sponsor_no}'"));
    if( $vmCheckRow["accountType"] == "CU" && $vmCheckRow["renewal"] == "" && $_GET["nameCheck"] == "true" ) {
        echo "false"; exit();
    }
    
    
    
    

	$result = mysql_query("SELECT * FROM teamMembers WHERE mb_id = '{$reg_mb_sponsor_no}'");
	$row = mysql_fetch_array($result);

	if(!$row[$reg_mb_sponser_team.'T_name']) { // 참이면 누군가 없다
            if( $_GET["nameCheck"] == "true" ) {
                echo $vmCheckRow["mb_name"]; exit();
            }
		echo "true";
	}else{
		echo "false";
	}
?>