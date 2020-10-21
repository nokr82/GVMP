
 <?php
$cssCheck = false; // _common.php에 인클루드 되는 CSS 적용 안 되게 하기 위한 변수
include_once('./_common.php');
include_once('./dbConn.php');

    $type;
    switch( $_POST['type'] ) {
        case 1 :
			$type = "";
			$VMGSTATE = "";
		break;  
			
        case 2 :
			$type = "AND ! (VCash < 0 OR VPay < 0)";
		break;
        case 3 :
			$type = "AND (VCash < 0 OR VPay < 0)";
		break;
    }

    $Array = array();
    $year = $_POST['year'];
    $month = $_POST['month'];

	$kCalendar = mysql_query("SELECT 
        mb_id,
            SUM(VCash) AS VCash,
            SUM(VPay) AS VPay,
            DATE_FORMAT(date, '%Y') AS YY,
            DATE_FORMAT(date, '%c') AS MM,
            DATE_FORMAT(date, '%e') AS DD,
            date,
			accountRank
    FROM
        calendarList
    WHERE
        (VCash != 0 or VPay != 0) AND
        mb_id = '{$member['mb_id']}'
            AND DATE_FORMAT(date, '%Y') = '{$year}'
            AND DATE_FORMAT(date, '%c') = '{$month}' {$type}
	GROUP BY DATE_FORMAT(date, '%d')
	ORDER BY date");

            
	while($CRow = mysql_fetch_array($kCalendar)){
		array_push( $Array, array(number_format($CRow['VCash']),number_format($CRow['VPay']),number_format(0),$CRow['YY'], $CRow['MM'],$CRow['DD'], $CRow['accountRank']) );
	}
   echo json_encode($Array);
 ?>        