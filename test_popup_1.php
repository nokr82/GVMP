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

<div id="main_popup" class="main_popup" style="position: absolute; z-index:10000; top:0px; left:0; display: none;">
	<a href="" ><img src="" style="width:100%;height:400px;"/></a>
	<div class="popup_bottom">
		<a href="javascript:closePopupNotToday()" class="white">오늘하루 그만보기</a>
		<a class="pull-right white" href="javascript:closeMainPopup();">닫기</a>
	</div>
</div>





    <script>
    if(getCookie("notToday")!="Y"){
		$("#main_popup").show('fade');
}

function closePopupNotToday(){	             
		setCookie('notToday','Y', 1);
		$("#main_popup").hide('fade');
}
function setCookie(name, value, expiredays) {
	var today = new Date();
	    today.setDate(today.getDate() + expiredays);

	    document.cookie = name + '=' + escape(value) + '; path=/; expires=' + today.toGMTString() + ';'
}

function getCookie(name) 
{ 
    var cName = name + "="; 
    var x = 0; 
    while ( x <= document.cookie.length ) 
    { 
        var y = (x+cName.length); 
        if ( document.cookie.substring( x, y ) == cName ) 
        { 
            if ( (endOfCookie=document.cookie.indexOf( ";", y )) == -1 ) 
                endOfCookie = document.cookie.length;
            return unescape( document.cookie.substring( y, endOfCookie ) ); 
        } 
        x = document.cookie.indexOf( " ", x ) + 1; 
        if ( x == 0 ) 
            break; 
    } 
    return ""; 
}
function closeMainPopup(){
	$("#main_popup").hide('fade');
}


    </script>

    <style>
        a{text-decoration:none; color:white}
        .pull-right{float:right}
        .main_popup{width:350px; height:430px; border:3px solid #3E4156;background-color: white;}
        .popup_bottom{
        background-color: #3E4156;
         color: white;
          height: 25px;
         padding: 2px 10px 3px 10px;
         width:330px;
}
        
    </style>

    
</body>
</html>

