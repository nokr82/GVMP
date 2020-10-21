<?php
    echo "<br><div style='font-size:30px; width:600px;text-align:center;'><b>수당 시뮬레이션</b></div><br><br>";
    $count = 1;
    $arr = Array();


    array_push($arr, 1);
    
    $money = 2080000;
    
    
    for($i=1;$i<17;$i++) {
        $count += pow(2,$i);
        array_push($arr, pow(2,$i));
    }
    

    echo "<div style='border:1px solid red; width:600px; padding:3px; line-height:25px;'>";
    $temp=0; $step = 1; $totalMoney = 0;
    foreach ($arr as $value) {
        $temp += $value;
        echo $step . "대 : ".$value . "명.....". check(($count - $temp) / $value)[0]. ".....4개월 지급 수당 : ". number_format(check(($count - $temp) / $value)[1]*$value)."원<br>";
        $step++;
        $totalMoney += check(($count - $temp) / $value)[1]*$value;
    }
    echo "</div>";
    
    echo "<br><hr align='left' style='width:600px;'>총 인원 : " . number_format($count) . "명<br>";
    echo "4개월 회비 : " . number_format($money) . "원<br><br><br>";
    echo "총 회비 : " . number_format($count*$money) . "원";
    echo "<br>총 지급된 4개월 수당 : " . number_format($totalMoney). "원<br>";
    echo "<br><br>";
    echo "회비 대비 수당 : ". $totalMoney / ($count*$money) * 100 . "%";
  
    
    
    function check($check) {
        $day = 104;
        if( $check >= 3200 ) {
            return array("Royal Crown AMBASSADOR", 2200000*$day);
        } else if( $check >= 1600 ) {
            return array("Crown AMBASSADOR", 1650000*$day);
        } else if( $check >= 1000 ) {
            return array("Triple AMBASSADOR", 1100000*$day);
        } else if( $check >= 600 ) {
            return array("Double AMBASSADOR", 800000*$day);
        } else if( $check >= 400 ) {
            return array("AMBASSADOR", 500000*$day);
        } else if( $check >= 250 ) {
            return array("5 STAR", 300000*$day);
        } else if( $check >= 168 ) {
            return array("4 STAR", 200000*$day);
        } else if( $check >= 100 ) {
            return array("3 STAR", 150000*$day);
        } else if( $check >= 48 ) {
            return array("2 STAR", 100000*$day);
        } else if( $check >= 26 ) {
            return array("1 STAR", 50000*$day);
        } else if( $check >= 12 ) {
            return array("Triple MASTER", 25000*$day);
        } else if( $check >= 4 ) {
            return array("Double MASTER", 15000*$day);
        } else if( $check >= 2 ) {
            return array("MASTER", 8000*$day);
        } else {
            return array("VM", 8000*$day);
        }
        
        
    }
?>