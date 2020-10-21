<?php
ini_set('memory_limit','-1');
ini_set('max_execution_time', 300);
set_time_limit(300);
//include_once('./inc/title.php');
include_once('./dbConn.php');
//include_once('./_common.php');
include_once('./getMemberInfo.php');
include_once('./OrganizationChartInfo.php');
include_once('./ArraySearch.php');

if( $member['mb_id'] == "admin" ) {
	$member['mb_id'] = "00000001";
}

$mbid = (isset($_GET['mb_id'])&&$_GET['mb_id'])?$_GET['mb_id']:$member["mb_id"];


$NOWDATA = strtotime(date("Y-m-d"));
$MEMBER = getAllMemeber();
$TREE = jsonMemberTreeDetail($mbid);
$DATAHTML = "";

$query = "mid='".$mbid."'";
$Result = ArraySearch($TREE, $query,1,'direct');
$data = jsonPrintTreeDetail($Result);
if($mbid!="00000001"){
	$data = str_replace("<ul><ul>","",$data);
	$data = '<ul id="organisation">'.$data;
}

?>

</head>
<!-- header -->
<link rel="stylesheet" href="./css/neungChart.css">
<link rel="stylesheet" href="./css/box_new.css">
<script>



</script>
<input type="hidden" id="loginID" value="<?=$member['mb_id']?>">
<?php
echo "<div id=\"neungOrgChart\">";
echo "<div class=\"li_table\">";
echo preg_replace("/<ul>/", "<ul id=\"organisation\">", $data, 1);
echo "</div>";
echo "</div>";
?>
