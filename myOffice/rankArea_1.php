<?php //
    include_once ('./_common.php');
    include_once ('./dbConn.php');

    // 직급을 매개변수로 넘기면 이미지 경로를 리턴 해 주는 함수

    
    
    
    // 롤링 배너에 필요한 직급자들 정보 뽑아오기
    $result1 = mysql_query( "SELECT 
    a.*, b.Allowance
FROM
    (SELECT 
        rankCheck.*, g5_member.mb_name
    FROM
        rankCheck
    INNER JOIN g5_member ON rankCheck.mb_id = g5_member.mb_id
    WHERE
        rankAccount LIKE '%STAR'
            OR rankAccount LIKE '%AMBASSADOR'
    LIMIT 10) AS a
        INNER JOIN
    jobAllowance AS b ON a.rankAccount = b.accountRank
ORDER BY b.Allowance DESC , a.date" );
    
    
    $result2 = mysql_query( "SELECT 
        rankCheck.*, g5_member.mb_name
    FROM
        rankCheck
            INNER JOIN
        g5_member ON rankCheck.mb_id = g5_member.mb_id
    WHERE
        rankAccount LIKE '%MASTER'
    ORDER BY date DESC
    LIMIT 20" );
    
    
    $result3 = mysql_query( "SELECT 
        rankCheck.*, g5_member.mb_name
    FROM
        rankCheck
            INNER JOIN
        g5_member ON rankCheck.mb_id = g5_member.mb_id
    WHERE
        rankAccount LIKE 'VM'
    ORDER BY date DESC
    LIMIT 30" );
    /////////////////////////////////////////////////////////////////////
    
    
    
    
    

    
?>
<link rel="stylesheet" href="./css/simpleBanner.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="./js/simpleBanner.js"></script>
    <!-- ㅇㅇㅇㅇㅇㅇㅇ여기부터ㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇ-->
<body>
    
<div id="banner02box">
    
    <div id="border_all">
        <div id="M_box_border"></div>
        <div id="S_box_border">
            <div class="loader">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div id="VM_box_border"></div>

    </div>

    
    <!-- 마스터 롤링 배너1 -->
    <div class="simple_banner_wrap banner02 bn1" data-type="vslide" data-interval="3000">
        <ul>
        <?php
            while( $row2 = mysql_fetch_array($result2) ) {
                $date = date('Y-m-d', strtotime($row2['date']));
                $imagePath = imagePath( $row2['rankAccount'] );
                echo "<li>
                    <div id=\"M_box\" class=\"M_box_all\">
                        <div>직급</div>
                        <div>{$row2['rankAccount']}</div>
                        <div><img src=\"{$imagePath}\" width=\"90%;\"></div>
                        <div>이름</div>
                        <div>{$row2['mb_name']}</div>
                        <div>달성시기</div>
                        <div>{$date}</div>
                    </div>
                </li>";
            }
        ?>
        </ul>
    </div>
    
    <!-- 마스터 롤링 배너2 -->
    <div class="simple_banner_wrap banner02 bn2" data-type="vslide" data-interval="3000">
        <ul>
            <li>
                <div id="SS_box">
                    <div>
                        <p><img src="images/zik_up.png" width="100%;"></p>
                        <p>직급</p><p>대표 사업자</p>
                        <p><img src="images/zik_down.png" width="100%;"></p>
                    </div>
                    <div>
                        <p><img src="./images/VMP DELEGATE.png" width="100%;"></p>
                    </div>
                    <div>
                        <p>이름 <b style="text-align: left">배형무</b></p>
                        <p></p>
                    </div>
                </div>
            </li>
            <?php
                while( $row1 = mysql_fetch_array($result1) ) {
                    if( $row1['mb_id'] == "00000001" ) {
                        continue;
                    }

                    $date = date('Y-m-d', strtotime($row1['date']));
                    $imagePath = imagePath( $row1['rankAccount'] );
                    echo "<li>
                            <div id=\"S_box\">
                                <div>
                                    <p><img src=\"images/zik_up.png\" width=\"100%;\"></p>
                                    <p>직급</p><p>{$row1['rankAccount']}</p>
                                    <p><img src=\"images/zik_down.png\" width=\"100%;\"></p>
                                </div>
                                <div>
                                    <p><img src=\"{$imagePath}\" width=\"100%;\"></p>
                                </div>
                                <div>
                                    <p>이름 <b style=\"text-align: left\">{$row1['mb_name']}</b></p>
                                    <p>달성시기 <b style=\"text-align: left\">{$date}</b></p>
                                </div>
                            </div>
                        </li>";
                }
            ?>
        </ul>
    </div>
    
    <!-- 마스터 롤링 배너3 -->
    <div class="simple_banner_wrap banner02 bn3" data-type="vslide" data-interval="3000">
        <ul>  
        <?php
            while( $row3 = mysql_fetch_array($result3) ) {
                $date = date('Y-m-d', strtotime($row3['date']));
                $imagePath = imagePath( $row3['rankAccount'] );


                echo "<li>
                        <div id=\"VM_box\">
                            <div>직급</div>
                            <div>{$row3['rankAccount']}</div>
                            <div><img src=\"{$imagePath}\" width=\"90%;\"></div>
                            <div>이름</div>
                            <div>{$row3['mb_name']}</div>
                            <div>달성시기</div>
                            <div>{$date}</div>
                        </div>
                    </li>";
            }
        ?>
        </ul>
    </div>
</div> 
    
    
    </div>
</body>
    <!-- ㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇ여기까지ㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇㅇ-->