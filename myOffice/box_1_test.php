<style>
    #lodingall{position: absolute;top: 45%;left: 50%;transform: translate(-50%,-50%); }
    #vmplogoimg{position: absolute;top: 38%;left: 50%;transform: translate(-50%,-50%); width:150px;}
    #lodingimg{position: absolute;top: 100px;left: 50%;transform: translate(-50%,-50%); width:150px;}

</style>





<?php

if( isset( $_GET['mb_id'] ) && isset( $_GET['boxCheck'] ) ) {
    echo "<script type=\"text/javascript\">
            window.onload = pageLoad;
            function pageLoad(){
                 location.href=\"box_test.php?mb_id={$_GET['mb_id']}&boxCheck=true\"; 
            };
        </script>";
} else if( isset( $_GET['mb_id'] ) ) {
    echo "<script type=\"text/javascript\">
            window.onload = pageLoad;
            function pageLoad(){
                 location.href=\"box_test.php?mb_id={$_GET['mb_id']}\"; 
            };
        </script>";
} else {
    echo "<script type=\"text/javascript\">
            window.onload = pageLoad;
            function pageLoad(){
                 location.href=\"box_test.php\"; 
            };
        </script>";
}



?>



<body id="body">
<div id="lodingall">
    <img src="images/VMP logo.png" alt="로고" id="vmplogoimg">
    <img src="images/loding.gif" alt="로딩이미지" id="lodingimg">
</div>
</body>







