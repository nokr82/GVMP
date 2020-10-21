 <?php
    include_once('./_common.php');
    include_once('./dbConn.php');

    
    switch( $_POST['type'] ) {
        case 1 :
            mysql_query("set @V_TYPE = 1");
		break;  
        case 2 :
            mysql_query("set @V_TYPE = 2");	
		break;
        case 3 :
            mysql_query("set @V_TYPE = 3");
		break;
    }

    $Array = array();
    $year = $_POST['year'];
    $month = $_POST['month'];
    
    if( (int)$month < 10 ) {
        $month = "0" . $month;
    }

    $kCalendar = mysql_query(" SELECT 
        d.mb_id,
        SUM(d.VMC) AS VMC,
        SUM(d.VMR) AS VMR,
        SUM(d.VMP) AS VMP,
        SUM(d.VMM) AS VMM,
        SUM(d.bizMoney) AS bizMoney,
        SUM(d.VMG) AS VMG,
        DATE_FORMAT(d.date, '%Y') AS YY,
        DATE_FORMAT(d.date, '%c') AS MM,
        DATE_FORMAT(d.date, '%e') AS DD,
        d.date,
        MAX(accountRank) AS accountRank,
        SUM(CASE WHEN d.way = 'dayPass' THEN 1 ELSE 0 END) AS daypass_Cnt
    FROM
        dayPoint d
            LEFT OUTER JOIN
        (SELECT 
            mb_id, date, accountRank
        FROM
            passTBL
        WHERE
            1 = 1 AND mb_id = '{$member['mb_id']}'
                AND date LIKE '{$year}-{$month}%'
        LIMIT 0 , 1) z ON z.mb_id = d.mb_id AND z.date = d.date
    WHERE
        1 = 1 AND d.date LIKE '{$year}-{$month}%'
            AND d.mb_id = '{$member['mb_id']}'
            AND ((@V_TYPE = 1 AND 1 = 1)
            OR (@V_TYPE = 2
            AND !( VMC < 0 OR VMR < 0 OR VMP < 0 OR VMM < 0
            OR bizMoney < 0
            OR VMG < 0))
            OR (@V_TYPE = 3
            AND (VMC < 0 OR VMR < 0 OR VMP < 0 OR VMM < 0
            OR bizMoney < 0
            OR VMG < 0)))
    GROUP BY mb_id , DATE_FORMAT(date, '%Y') , DATE_FORMAT(date, '%c') , DATE_FORMAT(date, '%e')
    ORDER BY d.date");
    
    while($CRow = mysql_fetch_array($kCalendar)){
            array_push( $Array, array(number_format($CRow['VMC']),number_format($CRow['VMR']),number_format($CRow['VMP']),$CRow['YY'], $CRow['MM'],$CRow['DD'], $CRow['accountRank'], number_format($CRow['VMG']),number_format($CRow['VMM']),number_format($CRow['bizMoney']),$CRow['daypass_Cnt']) );
    }
    
   echo json_encode($Array);
 ?>