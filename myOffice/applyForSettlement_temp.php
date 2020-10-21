<?php
    include_once ('./_common.php');
    include_once ('./dbConn.php');
    
    // 정산 신청 바뀌기 전 백엔드 처리 로직
    // 혹시 몰라서 남겨 둔 것임.
    // 현재는 사용되지 않음. 2018-10-10 이정현

    
    
    

    if ($is_guest) // 로그인 안 했을 때 로그인 페이지로 이동
        header('Location: /bbs/login.php');
    
    if( $member['mb_id'] == "admin" ) {
        echo "<script>alert('관리자는 정산 신청을 할 수 없습니다.'); window.location = 'index.php';</script>";
        exit();
    } 
    
    
    
    
    // 오늘이 며칠인지 알아내고 1~15 사이라면 1분기로 판별하고
    // 그게 아니라면 2분기로 판별 시키기
    $result;
    if( date("d") >= 16 ) { // 참이면 2분기
        $result = mysql_query("select * from calculateTBL where mb_id = '{$member['mb_id']}' and settlementDate = '".date( "Y-m-d", mktime(0,0,0,date("m")+1,1,date("Y")) )."'");
    } else { // 거짓이면 1분기
        $result = mysql_query("select * from calculateTBL where mb_id = '{$member['mb_id']}' and settlementDate = '".date("Y-m-")."16'");
    }

    $result2 = mysql_query("select * from g5_member where mb_id = '{$member['mb_id']}'");
    $row2 = mysql_fetch_array($result2);
    
    
    
    # 인액티브 상태가 아닌 VM만 VMC 신청이 가능해야 한다.
    $now = date("Y-m-d");
    $now_temp = strtotime($now);
    $dateCheck_1 = mysql_fetch_array(mysql_query("SELECT date_add('{$row2['renewal']}', interval +4 month) AS date"));
    $timestamp = $dateCheck_1["date"];
    $timestamp_temp = strtotime($timestamp);
    $VMCApplyCheck = false;
    if( $row2['accountType'] == "VM" && ( $timestamp_temp <= $now_temp ) ) { // 참이면 VM이면서 VM기간이 아님을 뜻 함. 해동 조건에 충족되면 VMC를 신청할 수 없게 차단할 것임
        $VMCApplyCheck = true;
    }
    
    
    
    // 현재 일자에 해당하는 분기에 정산 신청을 한적이 있는 지 판별
    $row = mysql_fetch_array($result);


    if( $row["mb_id"] != "" ) { // 참이면 정산 신청을 한적 있다.
        if( $_POST['calcCheck'] == "취소" ) { // 취소 요청이면 취소처리하는 로직
            mysql_query("delete from calculateTBL where n = {$row['n']}");
            mysql_query("update g5_member set VMC = VMC + {$row['VMC']}, VMP = VMP + {$row['VMP']} where mb_id = '{$member['mb_id']}'");
            mysql_query("delete from dayPoint where date like '".date("Y-m-d", strtotime($row['applicationDate']))."%' and way = 'calculate' and mb_id = '{$member['mb_id']}'");
            echo "<script>alert('신청 돼 있던 정산이 취소되었습니다.\\n\\n취소 VMC : ".$row['VMC']."\\n취소 VMP : ".$row['VMP']."'); window.location = 'index.php';</script>";
            exit();
        } else if( $_POST['calcCheck'] == "수정" ) { // 수정 요청이면 수정 처리하는 로직
            if( $_POST['sell1'] == "0" && $_POST['sell2'] == "0" ) {
                echo "<script>alert('신청한 포인트가 0원입니다. 신청이 중단되었습니다.'); window.location = 'index.php';</script>";
                exit();
            }
            
            if( $_POST['sell1'] != "0" && $VMCApplyCheck ) { // VMC 신청금액이 존재하는데 신청할 수 있는 조건이 충족되지 않으 때. 즉 VM기간 중인 VM이 아니라면 참.
                echo "<script>alert('VMC를 정산 신청할 수 있는 조건이 충족되지 않습니다.'); window.location = 'index.php';</script>";
                exit();
            }

            if( ($_POST['sell1'] > ( $row['VMC'] + $row2['VMC'] )) or ($_POST['sell2'] > ( $row['VMP'] + $row2['VMP'] )) ) { // 수정 요청한 포인트가 기존 신청 금액 + 보유 포인트 보다 많으면 참
                echo "<script>alert('보유하신 포인트 보다 많은 금액을 정산 요청할 수 없습니다.'); window.location = 'index.php';</script>";
                exit();
            } else {
                // 기존 신청 내역 삭제
                mysql_query("delete from calculateTBL where n = {$row['n']}");
                mysql_query("update g5_member set VMC = VMC + {$row['VMC']}, VMP = VMP + {$row['VMP']} where mb_id = '{$member['mb_id']}'");
                mysql_query("delete from dayPoint where date like '".date("Y-m-d", strtotime($row['applicationDate']))."%' and way = 'calculate' and mb_id = '{$member['mb_id']}'");
                
                // 수정 요청한 금액으로 DB에 재 작성하기
                if( date("d") >= 16 ) { // 참이면 2분기
                    mysql_query("insert into calculateTBL values(null, '".date( "Y-m-d", mktime(0,0,0,date("m")+1,1,date("Y")) )."', '{$member['mb_id']}', {$_POST['sell1']}, {$_POST['sell2']}, now())");
                } else { // 거짓이면 1분기
                    mysql_query("insert into calculateTBL values(null, '".date("Y-m-")."16', '{$member['mb_id']}', {$_POST['sell1']}, {$_POST['sell2']}, now())");
                }
                mysql_query("update g5_member set VMC = VMC - {$_POST['sell1']}, VMP = VMP - {$_POST['sell2']} where mb_id = '{$member['mb_id']}'");
                mysql_query("insert into dayPoint set mb_id = '{$member['mb_id']}', VMC = -{$_POST['sell1']}, VMR = 0, VMP = -{$_POST['sell2']}, date = NOW(), way = 'calculate'");
                
                echo "<script>alert('정산신청 금액 수정이 완료되었습니다.'); window.location = 'index.php';</script>";
            }
        }
        
    } else { // 거짓이면 정산 신청을 한적 없다. 거짓일 때 정산 신청을 처리한다.
        if( $_POST['sell1'] != "0" && $VMCApplyCheck ) { // VMC 신청금액이 존재하는데 신청할 수 있는 조건이 충족되지 않으 때. 즉 VM기간 중인 VM이 아니라면 참.
            $_POST['sell1'] = "0";
        }
        
        if( $_POST['sell1'] == "0" && $_POST['sell2'] == "0" ) {
            echo "<script>alert('신청한 포인트가 0원입니다. 신청이 중단되었습니다.'); window.location = 'index.php';</script>";
            exit();
        }
        
        
        
        if( date("d") >= 16 ) { // 참이면 2분기
            mysql_query("insert into calculateTBL values(null, '".date( "Y-m-d", mktime(0,0,0,date("m")+1,1,date("Y")) )."', '{$member['mb_id']}', {$_POST['sell1']}, {$_POST['sell2']}, now())");
        } else { // 거짓이면 1분기
            mysql_query("insert into calculateTBL values(null, '".date("Y-m-")."16', '{$member['mb_id']}', {$_POST['sell1']}, {$_POST['sell2']}, now())");
        }
        mysql_query("update g5_member set VMC = VMC - {$_POST['sell1']}, VMP = VMP - {$_POST['sell2']} where mb_id = '{$member['mb_id']}'");
        mysql_query("insert into dayPoint set mb_id = '{$member['mb_id']}', VMC = -{$_POST['sell1']}, VMR = 0, VMP = -{$_POST['sell2']}, date = NOW(), way = 'calculate'");
    }
    
    echo "<script>alert('정산 신청이 완료되었습니다.'); window.location = 'index.php';</script>";
    
    
?>
