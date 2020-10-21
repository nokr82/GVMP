<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<html lang="ko">
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1 ">
	<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
        <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js" ></script>
        <script type="text/javascript" src="https://service.iamport.kr/js/iamport.payment-1.1.5.js"></script>
        <link rel="stylesheet" href="point_pop.css" />
        <!--달력테스트-->
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.8.18/themes/base/jquery-ui.css" type="text/css" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script src="http://code.jquery.com/ui/1.8.18/jquery-ui.min.js"></script> 
	<title>vmp point</title>
</head>
<body>
 <script type="text/javascript">
 jQuery(document).ready(function($) {
    pevent();
});
 
function pevent(){
    function getCookie(name){
        var nameOfCookie = name + "=";
        var x = 0;
        while (x <= document.cookie.length){
            var y = (x + nameOfCookie.length);
            if (document.cookie.substring(x, y) == nameOfCookie){
            if ((endOfCookie = document.cookie.indexOf(";", y)) == -1){
            endOfCookie = document.cookie.length;
            }
            return unescape (document.cookie.substring(y, endOfCookie));
            }
            x = document.cookie.indexOf (" ", x) + 1;
            if (x == 0) break;
        }
        return "";
    }
    if (getCookie("popname") != "done"){
        var popUrl = "/test_popup_1.php";
        var popOption = "width=400%, height=235%, resizable=no, scrollbars=no, status=no;";
        window.open(popUrl,"",popOption);
    }
}



</script> 



    
</body>
</html>

