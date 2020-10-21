<?php
    include_once ('./dbConn.php');

    //나의 추천인이
    //지정하려는 후원인 상위 상위 상위 중에 있는 지 체크하기(AJAX응답용) 계정을 만듬과 동시에 후원인을 지정할 때 사용하기 적절한 로직(간편 회원가입)
    
    $_POST["recommender_number"] = trim($_POST["recommender_number"]);
    $_POST["sponsor_number"] = trim($_POST["sponsor_number"]);
    
    

    
    // 지정하려는 후원인이 추천인이면 참
    if( $_POST["recommender_number"] == $_POST["sponsor_number"] ) {
        echo "true";
        exit();
    }
    
    $temp = true;
    $tempID = $_POST["sponsor_number"];
    while( $temp ) {
        $row = mysql_fetch_array(mysql_query("select * from genealogy where mb_id = '{$tempID}'"));
        if( $row["sponsorID"] == $_POST["recommender_number"] ) {
            echo "true";
            exit();
        }
        
        if( $row["sponsorID"] == "99999999" || $row["sponsorID"] == "00000000" || $row["sponsorID"] == "00003655" || $row["sponsorID"] == "" ) {
            echo "false";
            exit();
        }
        $tempID = $row["sponsorID"];
    }
    
    echo "false";
?>