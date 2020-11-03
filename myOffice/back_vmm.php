<?php

include_once('./dbConn.php');
include_once('./dbConn2.php');


$g5_memberRe = mysql_query("SELECT
                                    *
                                FROM
                                    g5_member AS a
                                WHERE
                                    a.accountType = 'VM'
                                        AND a.accountRank != 'VM'
                                        AND a.VMC > 0
                                        AND DATE_ADD(DATE_ADD(a.renewal, INTERVAL +4 month), interval 1 day) >= CURDATE()");



while( $g5_memberRow = mysql_fetch_array($g5_memberRe) ) {

    if($g5_memberRow['VMM']){
        $insert_vmc = $g5_memberRow['VMC']+$g5_memberRow['VMM'];
        $insert_vmm = $g5_memberRow['VMM'];
        echo $g5_memberRow['mb_id']."<br>";
        echo $insert_vmc."<br>";
        echo $insert_vmm."<br>";
        echo "-------------------<br>";

        mysql_query("update g5_member set VMC = {$insert_vmc}, VMM = 0 where mb_id = '{$g5_memberRow["mb_id"]}'");

    }

}
