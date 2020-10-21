<?php
ini_set('memory_limit','-1');
ini_set('max_execution_time', 300);
set_time_limit(300);
include_once('./dbConn.php');
include_once('./ArraySearch.php');

error_reporting(E_ALL);
ini_set("display_errors", 1);

$MEMBER = getAllMemeber();

$NOWDATA = strtotime(date("Y-m-d"));
$MEMBER = getAllMemeber();
$TREE = jsonMemberTreeDetail($member['mb_id']);
$DATAHTML = "";

$query = "mid='".$member['mb_id']."'";
$Result = ArraySearch($TREE, $query,1,'direct');
$data = printTreeDetail($Result);
?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="./css/neungChart.css">

<!--테이블 뺀 css-->
<!--<link rel="stylesheet" href="./css/neungChart_test.css">-->

<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="js/jquery.ui.touch-punch.min.js"></script>
<script src="js/jquery.mousewheel.js"></script>
<script src="./js/printThis.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.2.61/jspdf.min.js"></script>
<script src="./js/html2canvas.js"></script>
<script>
  var win=null;
  function printIt(printThis)  {
    win = window.open();
    self.focus();
    win.document.open();
    win.document.write('<'+'html'+'><'+'head'+'><'+'style'+'>');
   // win.document.write('body{display:inline-block;}ul{margin:0;padding:0}li{list-style:none;display:table-cell;text-align:center!important;vertical-align:top!important;min-width:80px!important;margin:0;padding:0}.li_table{display:table;clear:both;width:100%;max-width:100%;margin-bottom:20px;border-spacing:0;border-collapse:collapse;box-sizing:border-box;background-color:transparent;list-style:none;padding:0}.li_table li ul{display:table;clear:both;width:100%;max-width:100%;list-style:none;padding:0}.li_table li ul li{display:inline-block;list-style:none;text-align:left;display:table-cell;line-height:1.42857143}.li_table li:first-child ul li{list-style:none;font-weight:700;border-bottom-width:2px;border-top:0;vertical-align:bottom}.li_table li > span{background:#c6c6c6;width:90px!important;display:inline-block;margin:0 10px;text-align:center;font-size:12px;font-weight:700;height:32px;line-height:32px;text-overflow:ellipsis;white-space:nowrap;background-color:#edc951;border:solid 1px #000;color:#000;width:50px;position:relative;display:inline-block}.li_table table{width:100%;padding:0;margin:0;border-spacing:0}.li_table table td{width:50%;padding:0;margin:0}.OrgLeftLine > table:first-child td:last-child{clear:both;border-left:1px solid #000;border-top:1px solid #000;height:15px}.OrgRightLine > table:first-child td:first-child{clear:both;border-right:1px solid #000;border-top:1px solid #000;height:15px}.OrgSingle > table:first-child > tbody > tr > td:first-child{clear:both;border-top:0!important;border-right:1px solid #000!important}.OrgSingle > table:first-child > tbody > tr > td:last-child{clear:both;border-top:0!important;border-left:0}.li_table li > span.sad{background-color:#5ea763;color:#fff}.li_table li > span.angry{background-color:#e56a77;color:#fff}.li_table li > span:after{content:"";position:absolute;top:-18px;left:-10px;width:28px;height:28px;background-image:url(../images/pt_smile.png);background-repeat:no-repeat;background-size:contain;background-position:center center;z-index:99997}.li_table li > span.sad:after{background-image:url(../images/pt_sad.png)}.li_table li > span.angry:after{background-image:url(../images/pt_angry.png)}');
    win.document.write('body{display:inline-block}'+
'ul{margin:0;padding:0}'+
'li{list-style:none;text-align:center!important;vertical-align:top!important;min-width:80px!important;margin:0;padding:0}'+
'.li_table{clear:both;width:100%;max-width:100%;margin-bottom:20px;border-spacing:0;border-collapse:collapse;box-sizing:border-box;background-color:transparent;list-style:none;padding:0}'+
'.li_table li ul{clear:both;width:100%;max-width:100%;list-style:none;padding:0}'+
'.li_table li ul li{display:inline-block;list-style:none;text-align:left;line-height:1.42857143}'+
'.li_table li:first-child ul li{list-style:none;font-weight:700;border-bottom-width:2px;border-top:0;vertical-align:bottom}'+
'.li_table li > span{background:#c6c6c6;width:90px!important;display:inline-block;margin:0 10px;text-align:center;font-size:12px;font-weight:700;height:32px;line-height:32px;text-overflow:ellipsis;white-space:nowrap;background-color:#edc951;border:solid 1px #000;color:#000;width:50px;position:relative;display:inline-block}'+
'.li_table table{width:100%;padding:0;margin:0;border-spacing:0}'+
'.li_table table td{width:50%;padding:0;margin:0}'+
'.OrgLeftLine > table:first-child td:last-child{clear:both;border-left:1px solid #000;border-top:1px solid #000;height:15px}'+
'.OrgRightLine > table:first-child td:first-child{clear:both;border-right:1px solid #000;border-top:1px solid #000;height:15px}'+
'.OrgSingle > table:first-child > tbody > tr > td:first-child{clear:both;border-top:0!important;border-right:1px solid #000!important}'+
'.OrgSingle > table:first-child > tbody > tr > td:last-child{clear:both;border-top:0!important;border-left:0}'+
'.li_table li > span.sad{background-color:#5ea763;color:#fff}'+
'.li_table li > span.angry{background-color:#e56a77;color:#fff}'+
'.li_table li > span:after{content:"";position:absolute;top:-18px;left:-10px;width:28px;height:28px;background-image:url(../images/pt_smile.png);background-repeat:no-repeat;background-size:contain;background-position:center center;z-index:99997}'+
'.li_table li > span.sad:after{background-image:url(../images/pt_sad.png)}'+
'.li_table li > span.angry:after{background-image:url(../images/pt_angry.png)}');
    win.document.write('<'+'/'+'style'+'><'+'/'+'head'+'><'+'body'+'>');
    win.document.write(printThis);
    win.document.write('<'+'/'+'body'+'><'+'/'+'html'+'>');
    win.document.close();
    win.print();
    win.close();
  }
  
  
</script>
<!--<a href="javascript:printIt(document.getElementById('neungOrgChart').innerHTML)">Print</a>-->
<script type="text/javascript">
<!--
var compteur = 10;
window.onload=function(){
	$(".li_table").draggable();
	$('.li_table').mousewheel(function(event, deltaY) {
		$('body').on('scroll touchmove mousewheel', function(e) {
			return false;
		});

//		var leftPosition = $('.li_table').css("left").replace("px","");

		if(deltaY > 0){
			if(compteur < 20){
				compteur=compteur+0.2;
//				leftPosition = parseInt(leftPosition) - ((compteur*3)*0.8);
			}
		}

		if(deltaY < 0){
			if(compteur > 2){
				compteur=compteur-0.2;
//				leftPosition = parseInt(leftPosition) + ((compteur*3)*0.8);
			}
		}

		var scale = compteur/10;
		$('.li_table').css({
			'transform':'scale('+scale+','+scale+')',
			'-webkit-transform':'scale('+scale+','+scale+')',
			'-moz-transform':'scale('+scale+','+scale+')',
			'-o-transform':'scale('+scale+','+scale+')',
			'-ms-transform':'scale('+scale+','+scale+')',
			'transform-origin': 'top center',
		});

//		$('.li_table').css("left",leftPosition+"px");
	});


}


function  html2img(){
/*	$("#neungOrgChart").printThis({
		debug: true,
		importCSS: true,
		importStyle: true,
		loadCSS: "css/neungChart.css",
		header: "<h1>조직도</h1>"
	});
*/

	html2canvas($("#neungOrgChart").parent(), {
		onrendered: function(canvas) {
		var imgData = canvas.toDataURL('image/png');
		console.log('Report Image URL: '+imgData);
		var doc = new jsPDF('p', 'mm', [297, 210]);
		doc.addImage(imgData, 'PNG', 10, 10, 190, 95);
		doc.save('sample-file.pdf');
		}
	});
}


//-->
</script>

<?

echo "<div id=\"neungOrgChart\">";
echo "<div class=\"li_table\">";
echo preg_replace("/<ul>/", "<ul id=\"organisation\">", $data, 1);
echo "</div>";
echo "</div>";
?>
<?
echo exit;

function getAllMemeber()
{
	$data="";
	$sql = "SELECT mb_id, mb_name, renewal FROM g5_member where mb_id <> 'admin'";
	$result = mysql_query($sql);
	while( $row = mysql_fetch_array($result) ) {
		$data[$row['mb_id']] = $row['mb_name'];
	}
	$data['999999999999'] = '+';
	return $data;
}

function jsonMemberTreeDetail($MBID) {
	$sql = "SELECT * FROM orgChartTable WHERE n = '1'";
	$row = mysql_fetch_array(mysql_query($sql));
	$DATA = json_decode($row['jsonData'],true);
	return $DATA;
}



$POSITION=0;
function printTreeDetail($tree) {
	global $POSITION,$DATAHTML;
    if(!is_null($tree) && count($tree) > 0) {
        $DATAHTML .= '<ul>';
		$POSITION++;
        foreach($tree as $key => $node) {
			$ADDC = (isset($node['renewal'])&&$node['renewal'])?$node['renewal']:"";
			if(isset($node['name'])&&$node['name']){
				if($node['name']=="+"){
					$DATAHTML .="<li>";
					$DATAHTML .="<table><tr><td></td><td></td></tr></table>";
					$DATAHTML .="<span>".$node['name']."</span>";
					$DATAHTML .="</table>";
				} else {
					$DATACLASS = ($node['childrenCNT']=="1")?'OrgSingle':'OrgDoublue';
					$SUBCLASS = ($node['team']=="1")?'OrgLeftLine':'OrgRightLine';
					$NOSPCLASS = $node['renewal'];
					$DATAHTML .="<li class='unit ".$DATACLASS." ".$SUBCLASS."'>";
					$DATAHTML .="<table><tr><td></td><td></td></tr></table>";

					$DATAHTML .="<span class='".$node['renewal']."' OnClick=\"detailMember('".$node['mid']."','".$node['recommName']."');\" >(" . $node['team'] . ")" .$node['name'] . "</span>";
					$DATAHTML .="<table><tr><td style='border-right:1px solid #000000;height:18px;'></td><td></td></tr></table>";
				}
			}
            printTreeDetail($node['children']);
            $DATAHTML .=  '</li>';
        }
        $DATAHTML .=  '</ul>';
    }
	return $DATAHTML;
}


function getList($MID) {
	global $MEMBER;
	$DATA;
	$sql = "SELECT * FROM orgChartTable WHERE n = '2'";
	$row = mysql_fetch_array(mysql_query($sql));
	$DATA = $row['jsonData'];

	$TMP = explode('selfmid="'.$MID.'"',$DATA);
	$DATA = str_replace("`","'",$TMP[1]);
	$DATA = "<ul><li><a href=\"#none\" OnClick=\"detailMember('".$MID."','".$MEMBER[$MID]."');\" selfmid=\"".$MID."\"" . $DATA;
	return $DATA;
}
?>
