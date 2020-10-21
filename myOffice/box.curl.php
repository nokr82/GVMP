<?
ini_set('memory_limit','-1');
ini_set('max_execution_time', 300);
set_time_limit(300);
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="./js/jQuery.print.js"></script>
<?
require($_SERVER['DOCUMENT_ROOT'].'/myOffice/Snoopy-2.0.0/Snoopy.class.php');
$snoopy = new Snoopy;
$snoopy->fetch('http://gvmp.company/myOffice/box.devtest.php?mb_id='.$_GET['mb_id']);

echo $snoopy->results;

function get_content($url) {
	$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)';
	$curlsession = curl_init ();
	curl_setopt ($curlsession, CURLOPT_URL, $url);
	curl_setopt ($curlsession, CURLOPT_HEADER, 0);
	curl_setopt ($curlsession, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($curlsession, CURLOPT_POST, 0);
	curl_setopt ($curlsession, CURLOPT_USERAGENT, $agent);
	curl_setopt ($curlsession, CURLOPT_REFERER, "");
	curl_setopt ($curlsession, CURLOPT_TIMEOUT, 3);
	$buffer = curl_exec ($curlsession);
	$cinfo = curl_getinfo($curlsession);
	curl_close($curlsession);
	if ($cinfo['http_code'] != 200)
	{
		return $cinfo['http_code'];
	}
	return $buffer;
}
?>
<script type="text/javascript">
<!--

window.take = function() {
	html2canvas(document.getElementById("neungOrgChart"), {
		onrendered: function (canvas) {
			$("body").html("");
//			$("body").append(canvas);
			$("body").html('<img src=' + canvas.toDataURL("image/png") + '>');
		}
	});
}

window.onload = function() {
	$("#neungOrgChart").print({
		globalStyles : true,
		mediaPrint : false,
		iframe : false,
		noPrintSelector : ".avoid-this",
		deferred: $.Deferred().done(function() {opener.window.close();window.close();})
	});
}
//-->
</script>
