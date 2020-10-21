<?php
    include_once('./getMemberInfo.php');
    include_once('./dbConn2.php');
    
    // 검색하겠다고한 ID가 로그인한 사용자의 하위에 포함되는 지 체크해서
    // 포함되면 true 리턴 포함되지 않으면 false 리턴하는 로직
    
    if( $_POST['loginID'] == $_POST['searchID'] ) {
        // 로그인한 ID를 서치하는 경우 트루 리턴
        echo "true";
        exit();
    }
    
    if( $_POST['loginID'] == 'admin' ) {
        echo "true"; exit();
    }

    
    if (mysqli_multi_query($connection, "CALL SP_TREE('{$_POST['loginID']}');SELECT count(*) as c FROM genealogy_tree where rootid = '{$_POST['loginID']}' and mb_id = '{$_POST['searchID']}'")) {
        mysqli_store_result($connection);
        mysqli_next_result($connection);
        do {
            /* store first result set */
            if ($result = mysqli_store_result($connection)) {
                while ($row = mysqli_fetch_array($result)) {
                    if( $row["c"] == 1 ) {
                        echo "true";
                        exit();
                    }
                }
                mysqli_free_result($result);
            }
        } while (mysqli_next_result($connection));
    }
    
    
    
    echo "false";
    
    mysqli_close($connection);
?>