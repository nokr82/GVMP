<script src="./js/html2canvas.js"></script>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="./js/canvas2image.js"></script>
<div id="popup_div">
<?
$chHtml = '내용';
echo $chHtml;
?>
</div>
<SCRIPT LANGUAGE="JavaScript">
function  html2img(){
	var canvas ="";
	html2canvas($("#popup_div").parent(), {
		onrendered: function(canvas) {
			$("#imgurl").val(canvas.toDataURL("image/png"));
			//Canvas2Image.saveAsPNG(canvas); 
			//canvas2image.js를 사용하여 바로 저장하려고 했으나 IE에서 이게 안돼서 아래와 같은 방법으로 저장함.
			$("#downform").submit();
		}
	});
}
$(document).ready(function(){
	html2img();
});
//-->
</SCRIPT>
<form id="downform" action="temp1.php" method="post" target="downiframe">
    <input type="hidden" name="imgurl" id="imgurl" />
</form>
<iframe name="downiframe" width="500" height="500" style="display:none"></iframe>