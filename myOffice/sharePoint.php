<?php
    include_once ('./dbConn.php');

    // 공유 포인트를 지급하는 로직
    // 상위 20명에게 500원씩 지급
    
    set_time_limit(0);

    
    $now_temp = strtotime(date("Y-m-d")); // 날짜 비교용 변수
    $point = 500; // 지급될 VMC 포인트
    
    $dateSTR = "NOW()";
//    $dateSTR = "'2019-06-13'";

    $allRe = mysql_query("SELECT 
                            RE.*, GN.sponsorID
                        FROM
                            (SELECT 
                                GM.*
                            FROM
                                g5_member AS GM
                            LEFT JOIN dayPoint AS DP ON GM.mb_id = DP.mb_id
                            WHERE
                                (DP.way = 'newRenewal'
                                    OR DP.way = 'vmJoin2'
                                    OR DP.way = 'Renewal'
                                    OR DP.way = 'autoRenewal'
                                    OR DP.way = 'businessPack'
                                    OR DP.way = 'adminBusinessPack')
                                    AND DP.date = DATE_ADD({$dateSTR}, INTERVAL - 1 DAY)
                                    AND GM.accountType = 'VM'
                                    AND GM.mb_id != '00000001'
                                    AND GM.mb_id != '00003655') AS RE
                                LEFT JOIN
                            genealogy AS GN ON RE.mb_id = GN.mb_id
                        WHERE
                            GN.sponsorID != '99999999'");
    
    
    
    while( $allRow = mysql_fetch_array($allRe) ) {
        $mb_id = $allRow["mb_id"];

        
        
        // 금일 공유 보너스를 지급한 이력이 있으면 CONTINUE !! 중복 지급을 막기 위함.
        $shareCheckRow = mysql_fetch_array(mysql_query("select * from dayPoint where date like '{$dateSTR}%' and way like '%sharePoint%{$mb_id}%' limit 1"));
        if( $shareCheckRow["mb_id"] != "" )
            continue;
        /////////////////////////////////////////////////



        
        
        $info = array();
        array_push($info, $allRow["sponsorID"]);

        $check = 0;
        while( true ) {
            $check++;
            $genealogyRow = mysql_fetch_array(mysql_query("select * from genealogy where mb_id = '{$info[ count($info)-1 ]}'"));

            if( $genealogyRow["sponsorID"] == "00000000" ) {
                break;
            }
            if( $genealogyRow["sponsorID"] != "" ) {
                array_push($info, $genealogyRow["sponsorID"]);
            }
            if( $check > 100 ) {
                $check = true; // 참이면 무한 루프 발생
                break;
            }
        }

        if( $check === true ) {
            continue;
        }



        $countCheck = 0;
        foreach ($info as $value) {
//            if( $value == "00000009" || $value == "00000001" ) { 특정 확률로 공유 보너스 지급하는 로직
//                if( probability(10) )
//                    continue;
//            }
            $g5_memberRow = mysql_fetch_array(mysql_query("select * from g5_member where mb_id = '{$value}'"));
            $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$g5_memberRow['renewal']}', interval +4 month) AS date"));
            if( $g5_memberRow['accountType'] == 'VM' &&  strtotime($dateCheck_1["date"]) >= $now_temp ){
                $countCheck++;
                if( $countCheck <= 15 ) {
                    mysql_query("insert into dayPoint set mb_id = '{$value}', VMC={$point}, VMR=0, VMP=0,V=0,VCash=0,VPay=0, date={$dateSTR}, way = 'sharePoint;{$mb_id}'");
                    mysql_query("update g5_member set VMC = VMC + {$point} where mb_id = '{$value}'");
                    
                    echo "insert into dayPoint set mb_id = '{$value}', VMC={$point}, VMR=0, VMP=0,V=0,VCash=0,VPay=0, date={$dateSTR}, way = 'sharePoint;{$mb_id}'<br>";
                    echo "update g5_member set VMC = VMC + {$point} where mb_id = '{$value}'<br>";
                }
            }
        }
    }
    
    
    
    
    function probability($p) { // 넘긴 퍼센트 확률로 1을 반환하는 함수
        $n = 0;
        $t = 0;
        $c = 0;
        $n = $p * 10000;

        if ($n > 1000000 ) 
            $n = 1000000;

        if ($n < 1) $n = 0;

        $t = mt_rand(0, 1000000);
        if ($t <= $n) $c = 1;

        return $c;
    }

?>