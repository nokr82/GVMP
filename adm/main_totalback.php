
<?php
include_once ( $_SERVER["DOCUMENT_ROOT"] . '/myOffice/dbConn.php');

    $n_year =$_GET['year']+'1';
   
    $mon1 = $_GET['year'].'-01-01';
    $mon2 = $_GET['year'].'-02-01';
    $mon3 = $_GET['year'].'-03-01';
    $mon4 = $_GET['year'].'-04-01';
    $mon5 = $_GET['year'].'-05-01';
    $mon6 = $_GET['year'].'-06-01';
    $mon7 = $_GET['year'].'-07-01';
    $mon8 = $_GET['year'].'-08-01';
    $mon9 = $_GET['year'].'-09-01';
    $mon10 = $_GET['year'].'-10-01';
    $mon11 = $_GET['year'].'-11-01';
    $mon12 = $_GET['year'].'-12-01';
    $mon13 = $n_year.'-01-01';

$mon_arr = array($mon1,$mon2,$mon3,$mon4,$mon5,$mon6,$mon7,$mon8,$mon9,$mon10,$mon11,$mon12, $mon13);

    
    //매출
    $i = 0;
    $arr = array(
        array(),
        array(),
        array(),
        array(),
        array(),
        array(),
        array(),
        array(),
        array(),
        array(),
        array(),
        array(),
        array(),
    );
  
   
    while($i<12){
       //매출
        //VM리뉴얼비
        $re =  mysql_query("select SUM(VMC+VMR+VMP+VMG) as total from dayPoint where ((way =  'autoRenewal' and VMR != 20000) or (way =  'Renewal' and VMR != 20000) or (way like 'membershipRenewal%')) and date >= '{$mon_arr[$i]} 00:00:00' and date < '{$mon_arr[$i+1]} 00:00:00'");
        $row = mysql_fetch_array( $re );
        array_push($arr[0], $row['total']);

        //VM 가입비
        $re2 =mysql_query("select SUM(VMC+VMR+VMP) as total2 from dayPoint where (way = 'newRenewal' or way like 'vmJoin;%')  and date >= '{$mon_arr[$i]} 00:00:00' and date < '{$mon_arr[$i+1]} 00:00:00'"); 
        $row2 = mysql_fetch_array( $re2);
        array_push($arr[1], $row2['total2']);
        
        //간편회원가입권 구매
        $re9 =  mysql_query("select SUM(VMC+VMR+VMP+VMG) as total from dayPoint where (way like  'membershipBuy%') and date >= '{$mon_arr[$i]} 00:00:00' and date < '{$mon_arr[$i+1]} 00:00:00'");
        $row9 = mysql_fetch_array( $re9 );
        array_push($arr[9], $row9['total']);
        
        // 비즈니스 팩1 구매
        $re10 =  mysql_query("select SUM(VMC+VMR+VMP+VMG) as total from dayPoint where (way like '%adminBusinessPack%' or way = 'businessPack') and date >= '{$mon_arr[$i]} 00:00:00' and date < '{$mon_arr[$i+1]} 00:00:00'");
        $row10 = mysql_fetch_array( $re10 );
        array_push($arr[10], $row10['total']);
        
        // 비즈머니 전환 구매
        $re11 =  mysql_query("select SUM(VMC+VMR+VMP+VMG+VMM) as total from dayPoint where (way = 'bizMoneyChange') and date >= '{$mon_arr[$i]} 00:00:00' and date < '{$mon_arr[$i+1]} 00:00:00'");
        $row11 = mysql_fetch_array( $re11 );
        array_push($arr[11], $row11['total']);
        
        // 중고장터
        $re12 =  mysql_query("select (price*0.05) as total from g5_write_used where saleStatus = '거래완료' and purCon_datetime >= '{$mon_arr[$i]}' and purCon_datetime < '{$mon_arr[$i+1]}'");
        $row12 = mysql_fetch_array( $re12 );
        array_push($arr[12], $row12['total']);
        
        //SM 가입비
        $re3 =mysql_query("select SUM(VMC+VMR+VMP) as total3 from dayPoint where way = 'smJoin' and date >= '{$mon_arr[$i]} 00:00:00' and date < '{$mon_arr[$i+1]} 00:00:00'");
        $row3 = mysql_fetch_array( $re3);
        array_push($arr[2], $row3['total3']);
        
        //SM 리뉴얼비
        $re4 =mysql_query("select SUM(VMC+VMR+VMP) as total4 from dayPoint where (way = 'smRenewal' or way = 'smAutoRenewal') and date >= '{$mon_arr[$i]} 00:00:00' and date < '{$mon_arr[$i+1]} 00:00:00'"); 
        $row4 = mysql_fetch_array( $re4);
        array_push($arr[3], $row4['total4']);
        
        
        //매입
         //데일리수당으로 지급 된 C포인트
        $re5 =mysql_query("select SUM(VMC+VMR+VMP) as total5 from dayPoint where way = 'autoVMC' and date >= '{$mon_arr[$i]} 00:00:00' and date < '{$mon_arr[$i+1]} 00:00:00'"); 
        $row5 = mysql_fetch_array( $re5);
        array_push($arr[4], $row5['total5']);
        
        //리뉴얼시 2만원 캐시백 된 수당
        $re6 =  mysql_query("select SUM(VMC+VMR+VMP) as total6 from dayPoint where ((way =  'autoRenewal' and VMR = 20000) or (way =  'Renewal' and VMR = 20000)) and date >= '{$mon_arr[$i]} 00:00:00' and date < '{$mon_arr[$i+1]} 00:00:00'");
        $row6 = mysql_fetch_array( $re6 );
        array_push($arr[5], $row6['total6']);
                
        //SM 가입으로 커미션 된 수당
        $re7 =mysql_query("select SUM(VMC+VMR+VMP) as total7 from dayPoint where way like 'smJoinVM%' and date >= '{$mon_arr[$i]} 00:00:00' and date < '{$mon_arr[$i+1]} 00:00:00'");
        $row7 = mysql_fetch_array( $re7);
        array_push($arr[6], $row7['total7']);
        
        //SM 리뉴얼로 커미션 된 수당
        $re8 =mysql_query("select SUM(VMC+VMR+VMP) as total8 from dayPoint where (way like 'smRenewalVM%' or way like 'smAutoRenewalVM%') and date >= '{$mon_arr[$i]} 00:00:00' and date < '{$mon_arr[$i+1]} 00:00:00'"); 
        $row8 = mysql_fetch_array( $re8);
        array_push($arr[7], $row8['total8']);
        
        // 공유 보너스
        $re9 = mysql_query("SELECT SUM(VMC + VMR + VMP) AS total9 FROM dayPoint WHERE way like 'sharePoint;%' AND date >= '{$mon_arr[$i]} 00:00:00' AND date < '{$mon_arr[$i+1]} 00:00:00'"); 
        $row9 = mysql_fetch_array( $re9);
        array_push($arr[8], $row9['total9']);
        

                
        $i +=1;

    };
    
    

    

    
    echo json_encode($arr, JSON_UNESCAPED_UNICODE);

?>





