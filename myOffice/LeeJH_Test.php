<?php
    echo "<br><div style='font-size:30px; width:600px;text-align:center;'><b>수당 시뮬레이션</b></div><br><br>";
    $count = 1;
    $arr = Array();


    array_push($arr, 1);
    
    $money = 1300000;
    
    
    for($i=1;$i<17;$i++) {
        $count += pow(2,$i);
        array_push($arr, pow(2,$i));
    }
    

    echo "<div style='border:1px solid red; width:600px; padding:3px; line-height:25px;'>";
    $totalInfo1 = array(0,0); // 인원, 수당
    $totalInfo2 = array(0,0);
    $totalInfo3 = array(0,0);
    $totalInfo4 = array(0,0);
    $totalInfo5 = array(0,0);
    $totalInfo6 = array(0,0);
    $totalInfo7 = array(0,0);
    $totalInfo8 = array(0,0);
    $totalInfo9 = array(0,0);
    $totalInfo10 = array(0,0);
    
    
    $temp=0; $step = 1; $totalMoney = 0;
    foreach ($arr as $value) {
        $temp += $value;
        
        $arrTemp1 = check(($count - $temp) / $value)[0];
        $arrTemp2 = check(($count - $temp) / $value)[1]*$value;
        
        if( $arrTemp1 == "카운셀러" ) {
            $totalInfo1[0] += $value;
            $totalInfo1[1] += $arrTemp2;
        } else if( $arrTemp1 == "더블카운셀러" ) {
            $totalInfo2[0] += $value;
            $totalInfo2[1] += $arrTemp2;
        } else if( $arrTemp1 == "트리플카운셀러" ) {
            $totalInfo3[0] += $value;
            $totalInfo3[1] += $arrTemp2;
        } else if( $arrTemp1 == "매니저" ) {
            $totalInfo4[0] += $value;
            $totalInfo4[1] += $arrTemp2;
        } else if( $arrTemp1 == "더블매니저" ) {
            $totalInfo5[0] += $value;
            $totalInfo5[1] += $arrTemp2;
        } else if( $arrTemp1 == "제너럴매니저" ) {
            $totalInfo6[0] += $value;
            $totalInfo6[1] += $arrTemp2;
        } else if( $arrTemp1 == "디렉터" ) {
            $totalInfo7[0] += $value;
            $totalInfo7[1] += $arrTemp2;
        } else if( $arrTemp1 == "제너럴디렉터" ) {
            $totalInfo8[0] += $value;
            $totalInfo8[1] += $arrTemp2;
        } else if( $arrTemp1 == "인터네셔날디렉터" ) {
            $totalInfo9[0] += $value;
            $totalInfo9[1] += $arrTemp2;
        } else if( $arrTemp1 == "탑코리아" ) {
            $totalInfo10[0] += $value;
            $totalInfo10[1] += $arrTemp2;
        }
        
        echo $step . "대 : ".$value . "명.....". $arrTemp1 . ".....3개월 지급 수당 : ". number_format($arrTemp2)."원<br>";
        $step++;
        $totalMoney += check(($count - $temp) / $value)[1]*$value;
    }
    echo "</div>";
    
    echo "<br><hr align='left' style='width:600px;'>총 인원 : " . number_format($count) . "명<br>";
    echo "3개월 회비 : " . number_format($money) . "원<br><br><br>";
    echo "총 회비 : " . number_format($count*$money) . "원";
    echo "<br>총 지급된 3개월 수당 : " . number_format($totalMoney). "원<br>";
    echo "<br><br>";
    echo "회비 대비 수당 : ". $totalMoney / ($count*$money) * 100 . "%";
    
    
    echo "<br><br>";
    echo "카운셀러 총 : " . number_format($totalInfo1[0]) . "명, 총 수당 : " . number_format($totalInfo1[1]) . "원<br>";
    echo "더블카운셀러 총 : " . number_format($totalInfo2[0]) . "명, 총 수당 : " . number_format($totalInfo2[1]) . "원<br>";
    echo "트리플카운셀러 총 : " . number_format($totalInfo3[0]) . "명, 총 수당 : " . number_format($totalInfo3[1]) . "원<br>";
    echo "매니저 총 : " . number_format($totalInfo4[0]) . "명, 총 수당 : " . number_format($totalInfo4[1]) . "원<br>";
    echo "더블매니저 총 : " . number_format($totalInfo5[0]) . "명, 총 수당 : " . number_format($totalInfo5[1]) . "원<br>";
    echo "제너럴매니저 총 : " . number_format($totalInfo6[0]) . "명, 총 수당 : " . number_format($totalInfo6[1]) . "원<br>";
    echo "디렉터 총 : " . number_format($totalInfo7[0]) . "명, 총 수당 : " . number_format($totalInfo7[1]) . "원<br>";
    echo "제너럴디렉터 총 : " . number_format($totalInfo8[0]) . "명, 총 수당 : " . number_format($totalInfo8[1]) . "원<br>";
    echo "인터네셔날디렉터 총 : " . number_format($totalInfo9[0]) . "명, 총 수당 : " . number_format($totalInfo9[1]) . "원<br>";
    echo "탑코리아 총 : " . number_format($totalInfo10[0]) . "명, 총 수당 : " . number_format($totalInfo10[1]) . "원<br>";
  
    
    
    function check($check) {
        $day = 78;
        if( $check >= 4800 ) {
            return array("탑코리아", (2500000*$day)-(19500000*3));
        } else if( $check >= 2400 ) {
            return array("인터네셔날디렉터", (1100000*$day)-(8580000*3));
        } else if( $check >= 1200 ) {
            return array("제너럴디렉터", (800000*$day)-(6240000*3));
        } else if( $check >= 600 ) {
            return array("디렉터", (500000*$day)-(3900000*3));
        } else if( $check >= 300 ) {
            return array("제너럴매니저", (300000*$day)-(2340000*3));
        } else if( $check >= 180 ) {
            return array("더블매니저", (150000*$day)-(1170000*3));
        } else if( $check >= 60 ) {
            return array("매니저", (70000*$day)-(546000*3));
        } else if( $check >= 30 ) {
            return array("트리플카운셀러", (30000*$day)-(234000*3));
        } else if( $check >= 12 ) {
            return array("더블카운셀러", (10000*$day)-(78000*3));
        } else {
            return array("카운셀러", (7000*$day));
        }
    }
?>