<?
$imgurl = str_replace("data:image/png;base64,","",$_REQUEST['imgurl']);
$imgurl = base64_decode($imgurl);
header( "Content-type: application/octet-stream;charset=KSC5601");
header( "Content-Disposition: attachment; filename=print.png" );
header( "Content-Description: PHP4 Generated Data" );
echo $imgurl;
?>