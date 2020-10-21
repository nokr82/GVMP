
 <?php
 
include_once('./_common.php');
include_once('./dbConn.php');

    $type;
    switch( $_POST['type'] ) {
        case 1 :
          $type = ""; break;  
        case 2 :
          $type = "AND ! (VMC < 0 OR VMR < 0 OR VMP < 0)"; break;
        case 3 :
          $type = "AND (VMC < 0 OR VMR < 0 OR VMP < 0)"; break;
    }

    $Array = array();
    $year = $_POST['year'];
    $month = $_POST['month'];




    $kCalendar = mysql_query("SELECT 
    a.*, b.accountRank
FROM
    (SELECT 
        mb_id,
            SUM(vmc) AS VMC,
            SUM(vmr) AS VMR,
            SUM(vmp) AS VMP,
            DATE_FORMAT(date, '%Y') AS YY,
            DATE_FORMAT(date, '%c') AS MM,
            DATE_FORMAT(date, '%e') AS DD,
            date,
            SUM(vmg) AS VMG
    FROM
        dayPoint
    WHERE
        mb_id = '{$member['mb_id']}'
            AND DATE_FORMAT(date, '%Y') = '{$year}'
            AND DATE_FORMAT(date, '%c') = '{$month}' {$type}
    GROUP BY DATE_FORMAT(date, '%d')) AS a
        LEFT JOIN
    (SELECT 
        *
    FROM
        passTBL
    WHERE
        mb_id = '{$member['mb_id']}') AS b ON a.date = b.date
ORDER BY a.date");


            
	while($CRow = mysql_fetch_array($kCalendar)){
		array_push( $Array, array(number_format($CRow['VMC']),number_format($CRow['VMR']),number_format($CRow['VMP']),$CRow['YY'], $CRow['MM'],$CRow['DD'], $CRow['accountRank'], $CRow['VMG']) );
	}
   echo json_encode($Array);
 ?>        