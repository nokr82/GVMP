<link rel="stylesheet" href="./css/box.css">
<div class="boxContent" style="left:0%;">
        <ul class="preview">
        	<li><a href="#"><img src="images/previous.png" alt="" onclick="boxBack();"></a></li>
        	<li><a href="#"><img src="images/replay.png" alt="" onclick="selectView('<?=$member['mb_id']?>')";></a></li>
        </ul>
        	<div class="boxDepth0">
            	<div class="boxLevel0 boxNone">
                	<a href="" onclick="return false;" style="text-decoration:none; cursor: default;">
                    	<div class="img_element">
                    	
                    	<?php 
                    	    $mainID = "";
                        	if( isset( $_GET['mb_id'] ) ) {
                        	    $mainID = $_GET['mb_id'];
                        	} else {
                        	    $mainID = $member['mb_id'];
                        	}
                    	
                        	mysql_query("set session character_set_connection=utf8");
                        	mysql_query("set session character_set_results=utf8");
                        	mysql_query("set session character_set_client=utf8");
                        	
                        	$result = mysql_query("SELECT A.mb_name, A.mb_open_date, B.recommenderName FROM g5_member AS A INNER JOIN genealogy AS B ON A.mb_id = B.mb_id where A.mb_id = '{$mainID}'");
                        	$row = mysql_fetch_array($result);
                        	
                        	$result2 = mysql_query("select count(*) as team3 from team3_list where mb_id = '{$mainID}'");
                        	$row2 = mysql_fetch_array($result2);
                        	
                        	
                        	/* 하위 정보 연관배열에 담는 로직 */
                        	$memberInfo = array(
                        	   "1촌" => array(
                        	       "1번" => array(
                            	       "존재유무"=>false,
                            	       "이름"=>"",
                            	       "회원가입날짜"=>"",
                            	       "리뉴얼날짜"=>"",
                            	       "1팀인원수"=>"",
                            	       "2팀인원수"=>"",
                            	       "3팀인원수"=>"",
                            	       "추천인"=>"",
                            	       "아이디"=>""
                        	       ),
                        	       "2번" => array(
                        	       "존재유무"=>false,
                        	       "이름"=>"",
                        	       "회원가입날짜"=>"",
                        	       "리뉴얼날짜"=>"",
                        	       "1팀인원수"=>"",
                        	       "2팀인원수"=>"",
                        	       "3팀인원수"=>"",
                        	       "추천인"=>"",
                        	       "아이디"=>""
                        	       )
                                ),
                                
                                "2촌" => array(
                                    "1번" => array(
                                    "존재유무"=>false,
                                    "이름"=>"",
                                    "회원가입날짜"=>"",
                                    "리뉴얼날짜"=>"",
                                    "1팀인원수"=>"",
                                    "2팀인원수"=>"",
                                    "3팀인원수"=>"",
                                    "추천인"=>"",
                                    "아이디"=>""
                            	    ),
                            	    "2번" => array(
                            	    "존재유무"=>false,
                            	    "이름"=>"",
                            	    "회원가입날짜"=>"",
                            	    "리뉴얼날짜"=>"",
                            	    "1팀인원수"=>"",
                            	    "2팀인원수"=>"",
                            	    "3팀인원수"=>"",
                            	    "추천인"=>"",
                            	    "아이디"=>""
                                	),
                                	"3번" => array(
                                	"존재유무"=>false,
                                	"이름"=>"",
                                	"회원가입날짜"=>"",
                                	"리뉴얼날짜"=>"",
                                	"1팀인원수"=>"",
                                	"2팀인원수"=>"",
                                	"3팀인원수"=>"",
                                	"추천인"=>"",
                                	"아이디"=>""
                    	            ),
                    	           "4번" => array(
                    	           "존재유무"=>false,
                    	           "이름"=>"",
                    	           "회원가입날짜"=>"",
                    	           "리뉴얼날짜"=>"",
                    	           "1팀인원수"=>"",
                    	           "2팀인원수"=>"",
                    	           "3팀인원수"=>"",
                    	           "추천인"=>"",
                    	           "아이디"=>""
                                    )
                        	    ),
                        	    
                        	    "3촌" => array(
                            	    "1번" => array(
                                	    "존재유무"=>false,
                                	    "이름"=>"",
                                	    "회원가입날짜"=>"",
                                	    "리뉴얼날짜"=>"",
                                	    "1팀인원수"=>"",
                                	    "2팀인원수"=>"",
                                	    "3팀인원수"=>"",
                                	    "추천인"=>"",
                                	    "아이디"=>""
                            	        ),
                            	        "2번" => array(
                            	        "존재유무"=>false,
                            	        "이름"=>"",
                            	        "회원가입날짜"=>"",
                            	        "리뉴얼날짜"=>"",
                            	        "1팀인원수"=>"",
                            	        "2팀인원수"=>"",
                            	        "3팀인원수"=>"",
                            	        "추천인"=>"",
                            	        "아이디"=>""
                                	    ),
                                	    "3번" => array(
                                	    "존재유무"=>false,
                                	    "이름"=>"",
                                	    "회원가입날짜"=>"",
                                	    "리뉴얼날짜"=>"",
                                	    "1팀인원수"=>"",
                                	    "2팀인원수"=>"",
                                	    "3팀인원수"=>"",
                                	    "추천인"=>"",
                                	    "아이디"=>""
                    	               ),
                    	               "4번" => array(
                    	               "존재유무"=>false,
                    	               "이름"=>"",
                    	               "회원가입날짜"=>"",
                    	               "리뉴얼날짜"=>"",
                    	               "1팀인원수"=>"",
                    	               "2팀인원수"=>"",
                    	               "3팀인원수"=>"",
                    	               "추천인"=>"",
                    	               "아이디"=>""
                            	        ),
                            	        "5번" => array(
                            	        "존재유무"=>false,
                            	        "이름"=>"",
                            	        "회원가입날짜"=>"",
                            	        "리뉴얼날짜"=>"",
                            	        "1팀인원수"=>"",
                            	        "2팀인원수"=>"",
                            	        "3팀인원수"=>"",
                            	        "추천인"=>"",
                            	        "아이디"=>""
                        	            ),
                        	            "6번" => array(
                        	            "존재유무"=>false,
                        	            "이름"=>"",
                        	            "회원가입날짜"=>"",
                        	            "리뉴얼날짜"=>"",
                        	            "1팀인원수"=>"",
                        	            "2팀인원수"=>"",
                        	            "3팀인원수"=>"",
                        	            "추천인"=>"",
                        	            "아이디"=>""
                            	        ),
                            	        "7번" => array(
                            	        "존재유무"=>false,
                            	        "이름"=>"",
                            	        "회원가입날짜"=>"",
                            	        "리뉴얼날짜"=>"",
                            	        "1팀인원수"=>"",
                            	        "2팀인원수"=>"",
                            	        "3팀인원수"=>"",
                            	        "추천인"=>"",
                            	        "아이디"=>""
                	                   ),
                	                   "8번" => array(
                	                   "존재유무"=>false,
                	                   "이름"=>"",
                	                   "회원가입날짜"=>"",
                	                   "리뉴얼날짜"=>"",
                	                   "1팀인원수"=>"",
                	                   "2팀인원수"=>"",
                	                   "3팀인원수"=>"",
                	                   "추천인"=>"",
                	                   "아이디"=>""
                    	               )
                            	       ),
                        	    
                                	    "4촌" => array(
                                	    "1번" => array(
                                    	    "존재유무"=>false,
                                    	    "이름"=>"",
                                    	    "회원가입날짜"=>"",
                                    	    "리뉴얼날짜"=>"",
                                    	    "1팀인원수"=>"",
                                    	    "2팀인원수"=>"",
                                    	    "3팀인원수"=>"",
                                    	    "추천인"=>"",
                                    	    "아이디"=>""
                        	                ),
                        	                "2번" => array(
                        	                "존재유무"=>false,
                        	                "이름"=>"",
                        	                "회원가입날짜"=>"",
                        	                "리뉴얼날짜"=>"",
                        	                "1팀인원수"=>"",
                        	                "2팀인원수"=>"",
                        	                "3팀인원수"=>"",
                        	                "추천인"=>"",
                        	                "아이디"=>""
                            	            ),
                            	            "3번" => array(
                            	            "존재유무"=>false,
                            	            "이름"=>"",
                            	            "회원가입날짜"=>"",
                            	            "리뉴얼날짜"=>"",
                            	            "1팀인원수"=>"",
                            	            "2팀인원수"=>"",
                            	            "3팀인원수"=>"",
                            	            "추천인"=>"",
                            	            "아이디"=>""
                	                        ),
                	                       "4번" => array(
                	                       "존재유무"=>false,
                	                       "이름"=>"",
                	                       "회원가입날짜"=>"",
                	                       "리뉴얼날짜"=>"",
                	                       "1팀인원수"=>"",
                	                       "2팀인원수"=>"",
                	                       "3팀인원수"=>"",
                	                       "추천인"=>"",
                	                       "아이디"=>""
                            	            ),
                                	        "5번" => array(
                                	        "존재유무"=>false,
                                	        "이름"=>"",
                                	        "회원가입날짜"=>"",
                                	        "리뉴얼날짜"=>"",
                                	        "1팀인원수"=>"",
                                	        "2팀인원수"=>"",
                                	        "3팀인원수"=>"",
                                	        "추천인"=>"",
                                	        "아이디"=>""
                        	                ),
                    	                    "6번" => array(
                    	                    "존재유무"=>false,
                    	                    "이름"=>"",
                    	                    "회원가입날짜"=>"",
                    	                    "리뉴얼날짜"=>"",
                    	                    "1팀인원수"=>"",
                    	                    "2팀인원수"=>"",
                    	                    "3팀인원수"=>"",
                    	                    "추천인"=>"",
                    	                    "아이디"=>""
                        	                ),
                        	                "7번" => array(
                        	                "존재유무"=>false,
                        	                "이름"=>"",
                        	                "회원가입날짜"=>"",
                        	                "리뉴얼날짜"=>"",
                        	                "1팀인원수"=>"",
                        	                "2팀인원수"=>"",
                        	                "3팀인원수"=>"",
                        	                "추천인"=>"",
                        	                "아이디"=>""
            	                           ),
            	                           "8번" => array(
            	                           "존재유무"=>false,
            	                           "이름"=>"",
            	                           "회원가입날짜"=>"",
            	                           "리뉴얼날짜"=>"",
            	                           "1팀인원수"=>"",
            	                           "2팀인원수"=>"",
            	                           "3팀인원수"=>"",
            	                           "추천인"=>"",
            	                           "아이디"=>""
                            	            ),
                            	            "9번" => array(
                            	            "존재유무"=>false,
                            	            "이름"=>"",
                            	            "회원가입날짜"=>"",
                            	            "리뉴얼날짜"=>"",
                            	            "1팀인원수"=>"",
                            	            "2팀인원수"=>"",
                            	            "3팀인원수"=>"",
                            	            "추천인"=>"",
                            	            "아이디"=>""
                	                        ),
                	                        "10번" => array(
                	                        "존재유무"=>false,
                	                        "이름"=>"",
                	                        "회원가입날짜"=>"",
                	                        "리뉴얼날짜"=>"",
                	                        "1팀인원수"=>"",
                	                        "2팀인원수"=>"",
                	                        "3팀인원수"=>"",
                	                        "추천인"=>"",
                	                        "아이디"=>""
                    	                    ),
                    	                    "11번" => array(
                    	                    "존재유무"=>false,
                    	                    "이름"=>"",
                    	                    "회원가입날짜"=>"",
                    	                    "리뉴얼날짜"=>"",
                    	                    "1팀인원수"=>"",
                    	                    "2팀인원수"=>"",
                    	                    "3팀인원수"=>"",
                    	                    "추천인"=>"",
                    	                    "아이디"=>""
        	                               ),
        	                               "12번" => array(
        	                               "존재유무"=>false,
        	                               "이름"=>"",
        	                               "회원가입날짜"=>"",
        	                               "리뉴얼날짜"=>"",
        	                               "1팀인원수"=>"",
        	                               "2팀인원수"=>"",
        	                               "3팀인원수"=>"",
        	                               "추천인"=>"",
        	                               "아이디"=>""
                        	                ),
                        	                "13번" => array(
                        	                "존재유무"=>false,
                        	                "이름"=>"",
                        	                "회원가입날짜"=>"",
                        	                "리뉴얼날짜"=>"",
                        	                "1팀인원수"=>"",
                        	                "2팀인원수"=>"",
                        	                "3팀인원수"=>"",
                        	                "추천인"=>"",
                        	                "아이디"=>""
            	                            ),
            	                            "14번" => array(
            	                            "존재유무"=>false,
            	                            "이름"=>"",
            	                            "회원가입날짜"=>"",
            	                            "리뉴얼날짜"=>"",
            	                            "1팀인원수"=>"",
            	                            "2팀인원수"=>"",
            	                            "3팀인원수"=>"",
            	                            "추천인"=>"",
            	                            "아이디"=>""
                	                        ),
                	                        "15번" => array(
                	                        "존재유무"=>false,
                	                        "이름"=>"",
                	                        "회원가입날짜"=>"",
                	                        "리뉴얼날짜"=>"",
                	                        "1팀인원수"=>"",
                	                        "2팀인원수"=>"",
                	                        "3팀인원수"=>"",
                	                        "추천인"=>"",
                	                        "아이디"=>""
    	                                    ),
    	                                   "16번" => array(
    	                                   "존재유무"=>false,
    	                                   "이름"=>"",
    	                                   "회원가입날짜"=>"",
    	                                   "리뉴얼날짜"=>"",
    	                                   "1팀인원수"=>"",
    	                                   "2팀인원수"=>"",
    	                                   "3팀인원수"=>"",
    	                                   "추천인"=>"",
    	                                   "아이디"=>""
        	                                )
                            	    )
                        	);
                        	
                        	

                        	
                        	function selectFunc($selectID, $checkCount) {
                        	    global $memberInfo;
                        	    $a = 0; $n1 = 0; $n2 = 0;
                        	    
                        	    
                        	    switch( $checkCount ) {
                        	        case 1 : $a = 1; $n1 = 1; $n2 = 2; break;
                        	        case 2 : $a = 2; $n1 = 1; $n2 = 2; break;
                        	        case 3 : $a = 2; $n1 = 3; $n2 = 4; break;
                        	        case 4 : $a = 3; $n1 = 1; $n2 = 2; break;
                        	        case 5 : $a = 3; $n1 = 3; $n2 = 4; break;
                        	        case 6 : $a = 3; $n1 = 5; $n2 = 6; break;
                        	        case 7 : $a = 3; $n1 = 7; $n2 = 8; break;
                        	        case 8 : $a = 4; $n1 = 1; $n2 = 2; break;
                        	        case 9 : $a = 4; $n1 = 3; $n2 = 4; break;
                        	        case 10 : $a = 4; $n1 = 5; $n2 = 6; break;
                        	        case 11 : $a = 4; $n1 = 7; $n2 = 8; break;
                        	        case 12 : $a = 4; $n1 = 9; $n2 = 10; break;
                        	        case 13 : $a = 4; $n1 = 11; $n2 = 12; break;
                        	        case 14 : $a = 4; $n1 = 13; $n2 = 14; break;
                        	        case 15 : $a = 4; $n1 = 15; $n2 = 16; break;
                        	    }
                        	    
                        	    
                        	    
                        	    
                        	    $result3 = mysql_query("select * from teamMembers where mb_id = '{$selectID}'");
                        	    $row3 = mysql_fetch_array($result3);
                        	    
                        	    
                        	    if( $row3['1T_name'] != null && $row3['1T_name'] != "null" ) {
                        	        $result4 = mysql_query("SELECT A.mb_open_date, B.recommenderName FROM g5_member AS A INNER JOIN genealogy AS B ON A.mb_id = B.mb_id where A.mb_id = '{$row3['1T_ID']}'");
                        	        $row4 = mysql_fetch_array($result4);
                        	        
                        	        $result5 = mysql_query("select count(*) as team3 from team3_list where mb_id = '{$row3['1T_ID']}'");
                        	        $row5 = mysql_fetch_array($result5);
                        	        
                        	        $memberInfo[$a . '촌'][$n1 . '번']['존재유무'] = true;
                        	        $memberInfo[$a . '촌'][$n1 . '번']['이름'] = $row3['1T_name'];
                        	        $memberInfo[$a . '촌'][$n1 . '번']['회원가입날짜'] = $row4['mb_open_date'];
                        	        $memberInfo[$a . '촌'][$n1 . '번']['리뉴얼날짜'] = "9999-99-99";
                        	        $memberInfo[$a . '촌'][$n1 . '번']['1팀인원수'] = 0;
                        	        $memberInfo[$a . '촌'][$n1 . '번']['2팀인원수'] = 0;
                        	        $memberInfo[$a . '촌'][$n1 . '번']['3팀인원수'] = $row5['team3'];
                        	        $memberInfo[$a . '촌'][$n1 . '번']['추천인'] = $row4['recommenderName'];
                        	        $memberInfo[$a . '촌'][$n1 . '번']['아이디'] = $row3['1T_ID'];
                        	    }
                        	    
                        	    if( $row3['2T_name'] != null && $row3['2T_name'] != "null" ) {
                        	        $result4 = mysql_query("SELECT A.mb_open_date, B.recommenderName FROM g5_member AS A INNER JOIN genealogy AS B ON A.mb_id = B.mb_id where A.mb_id = '{$row3['2T_ID']}'");
                        	        $row4 = mysql_fetch_array($result4);
                        	        
                        	        $result5 = mysql_query("select count(*) as team3 from team3_list where mb_id = '{$row3['2T_ID']}'");
                        	        $row5 = mysql_fetch_array($result5);
                        	        
                        	        $memberInfo[$a . '촌'][$n2 . '번']['존재유무'] = true;
                        	        $memberInfo[$a . '촌'][$n2 . '번']['이름'] = $row3['2T_name'];
                        	        $memberInfo[$a . '촌'][$n2 . '번']['회원가입날짜'] = $row4['mb_open_date'];
                        	        $memberInfo[$a . '촌'][$n2 . '번']['리뉴얼날짜'] = "9999-99-99";
                        	        $memberInfo[$a . '촌'][$n2 . '번']['1팀인원수'] = 0;
                        	        $memberInfo[$a . '촌'][$n2 . '번']['2팀인원수'] = 0;
                        	        $memberInfo[$a . '촌'][$n2 . '번']['3팀인원수'] = $row5['team3'];
                        	        $memberInfo[$a . '촌'][$n2 . '번']['추천인'] = $row4['recommenderName'];
                        	        $memberInfo[$a . '촌'][$n2 . '번']['아이디'] = $row3['2T_ID'];
                        	    }
                        	    
                        	    return array( $row3['1T_ID'], $row3['2T_ID'] );
                        	}
                        	
                        	$infoRe = selectFunc($mainID, 1);
                        	
                        	$infoRe2 = selectFunc($infoRe[0], 2);
                        	$infoRe3 = selectFunc($infoRe[1], 3);
                        	
                        	$infoRe4 = selectFunc($infoRe2[0], 4);
                        	$infoRe5 = selectFunc($infoRe2[1], 5);
                        	$infoRe6 = selectFunc($infoRe3[0], 6);
                        	$infoRe7 = selectFunc($infoRe3[1], 7);
                        	
                        	$infoRe8 = selectFunc($infoRe4[0], 8);
                        	$infoRe9 = selectFunc($infoRe4[1], 9);
                        	$infoRe10 = selectFunc($infoRe5[0], 10);
                        	$infoRe11 = selectFunc($infoRe5[1], 11);
                        	
                        	$infoRe12 = selectFunc($infoRe6[0], 12);
                        	$infoRe13 = selectFunc($infoRe6[1], 13);
                        	$infoRe14 = selectFunc($infoRe7[0], 14);
                        	$infoRe15 = selectFunc($infoRe7[1], 15);
                        	

                        	
                        	echo "<span>{$row['mb_name']}</span><span>[임시]</span></div><p>가입 : {$row['mb_open_date']}<br />리뉴얼 : 9999-99-99<br />인원 : 120/123/{$row2['team3']}<br />추천 : {$row['recommenderName']}<br /></p></a><div class=\"boxLine\"></div></div></div>";
                    	?>
                    	
						
                            
                            
                        
            <div class="boxDepth1">
            	 
            	<?php 
                	if( $memberInfo['1촌']['1번']['존재유무'] == true ) {
                	    echo "<div class=\"boxLevel1 boxRight\" ><a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['1촌']['1번']['아이디']}\"><div class=\"img_element\"><span>{$memberInfo['1촌']['1번']['이름']}</span><span>[임시]</span></div><p>가입 : {$memberInfo['1촌']['1번']['회원가입날짜']}<br />리뉴얼 : {$memberInfo['1촌']['1번']['리뉴얼날짜']}<br />인원 : {$memberInfo['1촌']['1번']['1팀인원수']}/{$memberInfo['1촌']['1번']['2팀인원수']}/{$memberInfo['1촌']['1번']['3팀인원수']}<br />추천 : {$memberInfo['1촌']['1번']['추천인']}<br /></p></a><div class=\"boxLine\"></div></div>";
                	} else {
                	    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                	}
            	?> 
            	 
                <div class="boxEmpty hidden1">
                	<span>&nbsp;</span>
                </div>
                <div class="boxEmpty hidden1">
                	<span>&nbsp;</span>
                </div>
                <div class="boxEmpty hidden1">
                	<span>&nbsp;</span>
                </div>
                <div class="boxEmpty hidden1">
                	<span>&nbsp;</span>
                </div>
                <div class="boxEmpty hidden1">
                	<span>&nbsp;</span>
                </div>
                <div class="boxEmpty hidden1">
                	<span>&nbsp;</span>
                </div>
                <div class="boxEmpty hidden1">
                	<span>&nbsp;</span>
                </div>
                
                <?php 
                if( $memberInfo['1촌']['2번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel2 boxLeft\" >
                    <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['1촌']['2번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['1촌']['2번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['1촌']['2번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['1촌']['2번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['1촌']['2번']['1팀인원수']}/{$memberInfo['1촌']['2번']['2팀인원수']}/{$memberInfo['1촌']['2번']['3팀인원수']}<br />
                            추천 : {$memberInfo['1촌']['2번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
                
                
                
            </div><!--boxDepth1-->
            
            
            <div class="boxDepth2">
            
            <?php 
            if( $memberInfo['2촌']['1번']['존재유무'] == true ) {
                echo "<div class=\"boxLevel1 boxRight\">
                    <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['2촌']['1번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['2촌']['1번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['2촌']['1번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['2촌']['1번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['2촌']['1번']['1팀인원수']}/{$memberInfo['2촌']['1번']['2팀인원수']}/{$memberInfo['2촌']['1번']['3팀인원수']}<br />
                            추천 : {$memberInfo['2촌']['1번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
            } else {
                echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
            }
            ?>
            	
                
                <div class="boxEmpty hidden1">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div><!--
                --><div class="boxEmpty">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div><!--
                --><div class="boxEmpty hidden1">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div>
                
                
                <?php 
                if( $memberInfo['2촌']['2번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['2촌']['2번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['2촌']['2번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['2촌']['2번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['2촌']['2번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['2촌']['2번']['1팀인원수']}/{$memberInfo['2촌']['2번']['2팀인원수']}/{$memberInfo['2촌']['2번']['3팀인원수']}<br />
                            추천 : {$memberInfo['2촌']['2번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
                
                
                <div class="boxEmpty hidden2">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div><!--
                --><div class="boxEmpty hidden2">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div><!--
                --><div class="boxEmpty hidden2">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div>
                
                <?php 
                if( $memberInfo['2촌']['3번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['2촌']['3번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['2촌']['3번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['2촌']['3번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['2촌']['3번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['2촌']['3번']['1팀인원수']}/{$memberInfo['2촌']['3번']['2팀인원수']}/{$memberInfo['2촌']['3번']['3팀인원수']}<br />
                            추천 : {$memberInfo['2촌']['3번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
				
                
                
                <div class="boxEmpty hidden1">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div><!--
                --><div class="boxEmpty">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div><!--
                --><div class="boxEmpty hidden1">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div>
                
                <?php 
                if( $memberInfo['2촌']['4번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['2촌']['4번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['2촌']['4번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['2촌']['4번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['2촌']['4번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['2촌']['4번']['1팀인원수']}/{$memberInfo['2촌']['4번']['2팀인원수']}/{$memberInfo['2촌']['4번']['3팀인원수']}<br />
                            추천 : {$memberInfo['2촌']['4번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
				
                
                
            </div><!--boxDepth2-->
            
           
            <div class="boxDepth3">
            
            
            <?php 
                if( $memberInfo['3촌']['1번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['3촌']['1번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['3촌']['1번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['3촌']['1번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['3촌']['1번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['3촌']['1번']['1팀인원수']}/{$memberInfo['3촌']['1번']['2팀인원수']}/{$memberInfo['3촌']['1번']['3팀인원수']}<br />
                            추천 : {$memberInfo['3촌']['1번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
            	


				<div class="boxEmpty">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div>


				<?php 
                if( $memberInfo['3촌']['2번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['3촌']['2번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['3촌']['2번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['3촌']['2번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['3촌']['2번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['3촌']['2번']['1팀인원수']}/{$memberInfo['3촌']['2번']['2팀인원수']}/{$memberInfo['3촌']['2번']['3팀인원수']}<br />
                            추천 : {$memberInfo['3촌']['2번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
				


				<div class="boxEmpty hidden2">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div>


				<?php 
                if( $memberInfo['3촌']['3번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['3촌']['3번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['3촌']['3번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['3촌']['3번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['3촌']['3번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['3촌']['3번']['1팀인원수']}/{$memberInfo['3촌']['3번']['2팀인원수']}/{$memberInfo['3촌']['3번']['3팀인원수']}<br />
                            추천 : {$memberInfo['3촌']['3번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
				



				<div class="boxEmpty">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div>

				<?php 
                if( $memberInfo['3촌']['4번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['3촌']['4번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['3촌']['4번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['3촌']['4번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['3촌']['4번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['3촌']['4번']['1팀인원수']}/{$memberInfo['3촌']['4번']['2팀인원수']}/{$memberInfo['3촌']['4번']['3팀인원수']}<br />
                            추천 : {$memberInfo['3촌']['4번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
				


				<div class="boxEmpty hidden2">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div>

				<?php 
                if( $memberInfo['3촌']['5번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['3촌']['5번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['3촌']['5번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['3촌']['5번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['3촌']['5번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['3촌']['5번']['1팀인원수']}/{$memberInfo['3촌']['5번']['2팀인원수']}/{$memberInfo['3촌']['5번']['3팀인원수']}<br />
                            추천 : {$memberInfo['3촌']['5번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
				



				<div class="boxEmpty">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div>

				<?php 
                if( $memberInfo['3촌']['6번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['3촌']['6번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['3촌']['6번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['3촌']['6번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['3촌']['6번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['3촌']['6번']['1팀인원수']}/{$memberInfo['3촌']['6번']['2팀인원수']}/{$memberInfo['3촌']['6번']['3팀인원수']}<br />
                            추천 : {$memberInfo['3촌']['6번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
				


				<div class="boxEmpty hidden2">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div>

				<?php 
                if( $memberInfo['3촌']['7번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['3촌']['7번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['3촌']['7번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['3촌']['7번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['3촌']['7번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['3촌']['7번']['1팀인원수']}/{$memberInfo['3촌']['7번']['2팀인원수']}/{$memberInfo['3촌']['7번']['3팀인원수']}<br />
                            추천 : {$memberInfo['3촌']['7번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
				


				<div class="boxEmpty">
                	<span>
                    	<div class="img"><img src="" alt="" /></div>
                        <p>&nbsp;</p>
                    </span>
                </div>

				<?php 
                if( $memberInfo['3촌']['8번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['3촌']['8번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['3촌']['8번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['3촌']['8번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['3촌']['8번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['3촌']['8번']['1팀인원수']}/{$memberInfo['3촌']['8번']['2팀인원수']}/{$memberInfo['3촌']['8번']['3팀인원수']}<br />
                            추천 : {$memberInfo['3촌']['8번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
				
                
                
                
            </div><!--boxDepth3-->
        	<div class="boxDepth4">
        	
        	
        	<?php 
                if( $memberInfo['4촌']['1번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['1번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['4촌']['1번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['4촌']['1번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['4촌']['1번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['4촌']['1번']['1팀인원수']}/{$memberInfo['4촌']['1번']['2팀인원수']}/{$memberInfo['4촌']['1번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['1번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
            	

				<?php 
                if( $memberInfo['4촌']['2번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['2번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['4촌']['2번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['4촌']['2번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['4촌']['2번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['4촌']['2번']['1팀인원수']}/{$memberInfo['4촌']['2번']['2팀인원수']}/{$memberInfo['4촌']['2번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['2번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
				

				<?php 
                if( $memberInfo['4촌']['3번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['3번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['4촌']['3번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['4촌']['3번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['4촌']['3번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['4촌']['3번']['1팀인원수']}/{$memberInfo['4촌']['3번']['2팀인원수']}/{$memberInfo['4촌']['3번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['3번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
				

				<?php 
                if( $memberInfo['4촌']['4번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['4번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['4촌']['4번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['4촌']['4번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['4촌']['4번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['4촌']['4번']['1팀인원수']}/{$memberInfo['4촌']['4번']['2팀인원수']}/{$memberInfo['4촌']['4번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['4번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
				

				<?php 
                if( $memberInfo['4촌']['5번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['5번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['4촌']['5번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['4촌']['5번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['4촌']['5번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['4촌']['5번']['1팀인원수']}/{$memberInfo['4촌']['5번']['2팀인원수']}/{$memberInfo['4촌']['5번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['5번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
				


				<?php 
                if( $memberInfo['4촌']['6번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['6번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['4촌']['6번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['4촌']['6번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['4촌']['6번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['4촌']['6번']['1팀인원수']}/{$memberInfo['4촌']['6번']['2팀인원수']}/{$memberInfo['4촌']['6번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['6번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
				

	
				<?php 
                if( $memberInfo['4촌']['7번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['7번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['4촌']['7번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['4촌']['7번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['4촌']['7번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['4촌']['7번']['1팀인원수']}/{$memberInfo['4촌']['7번']['2팀인원수']}/{$memberInfo['4촌']['7번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['7번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
				


				<?php 
                if( $memberInfo['4촌']['8번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['8번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['4촌']['8번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['4촌']['8번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['4촌']['8번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['4촌']['8번']['1팀인원수']}/{$memberInfo['4촌']['8번']['2팀인원수']}/{$memberInfo['4촌']['8번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['8번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
				


				<?php 
                if( $memberInfo['4촌']['9번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['9번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['4촌']['9번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['4촌']['9번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['4촌']['9번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['4촌']['9번']['1팀인원수']}/{$memberInfo['4촌']['9번']['2팀인원수']}/{$memberInfo['4촌']['9번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['9번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
				



				<?php 
                if( $memberInfo['4촌']['10번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['10번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['4촌']['10번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['4촌']['10번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['4촌']['10번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['4촌']['10번']['1팀인원수']}/{$memberInfo['4촌']['10번']['2팀인원수']}/{$memberInfo['4촌']['10번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['10번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
				


				<?php 
                if( $memberInfo['4촌']['11번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['11번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['4촌']['11번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['4촌']['11번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['4촌']['11번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['4촌']['11번']['1팀인원수']}/{$memberInfo['4촌']['11번']['2팀인원수']}/{$memberInfo['4촌']['11번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['11번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
				


				<?php 
                if( $memberInfo['4촌']['12번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['12번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['4촌']['12번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['4촌']['12번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['4촌']['12번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['4촌']['12번']['1팀인원수']}/{$memberInfo['4촌']['12번']['2팀인원수']}/{$memberInfo['4촌']['12번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['12번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
				


				<?php 
                if( $memberInfo['4촌']['13번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['13번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['4촌']['13번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['4촌']['13번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['4촌']['13번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['4촌']['13번']['1팀인원수']}/{$memberInfo['4촌']['13번']['2팀인원수']}/{$memberInfo['4촌']['13번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['13번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
				


				<?php 
                if( $memberInfo['4촌']['14번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['14번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['4촌']['14번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['4촌']['14번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['4촌']['14번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['4촌']['14번']['1팀인원수']}/{$memberInfo['4촌']['14번']['2팀인원수']}/{$memberInfo['4촌']['14번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['14번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                            
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
				


				<?php 
                if( $memberInfo['4촌']['15번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel2 boxRigh\">
                   <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['15번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['4촌']['15번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['4촌']['15번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['4촌']['15번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['4촌']['15번']['1팀인원수']}/{$memberInfo['4촌']['15번']['2팀인원수']}/{$memberInfo['4촌']['15번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['15번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel2 boxRight\">
                   <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                    	
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
				


				<?php 
                if( $memberInfo['4촌']['16번']['존재유무'] == true ) {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a onclick=\"selectView(this.id)\" href=\"#\" id=\"{$memberInfo['4촌']['16번']['아이디']}\">
                    	<div class=\"img_element\">
                    		<span>{$memberInfo['4촌']['16번']['이름']}</span>
                    		<span>[임시]</span>
                    	</div>
                        <p>
                            가입 : {$memberInfo['4촌']['16번']['회원가입날짜']}<br />
                            리뉴얼 : {$memberInfo['4촌']['16번']['리뉴얼날짜']}<br />
                            인원 : {$memberInfo['4촌']['16번']['1팀인원수']}/{$memberInfo['4촌']['16번']['2팀인원수']}/{$memberInfo['4촌']['16번']['3팀인원수']}<br />
                            추천 : {$memberInfo['4촌']['16번']['추천인']}<br />
                        </p>
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                } else {
                    echo "<div class=\"boxLevel1 boxLeft\">
                    <a href=\"#\" style=\"background:none\" id=\"insert\">
                    <img src=\"images/box_insert.png\" alt=\"\">
                    	
                    </a>
                    <div class=\"boxLine\"></div>
                </div>";
                }
                ?>
				
                
                
                
            </div><!--boxDepth4-->
           
        </div><!--boxContent-->