<style>
    #lodingall{position: absolute;top: 45%;left: 50%;transform: translate(-50%,-50%); }
    #vmplogoimg{position: absolute;top: 38%;left: 50%;transform: translate(-50%,-50%); width:150px;}
    #lodingimg{position: absolute;top: 100px;left: 50%;transform: translate(-50%,-50%); width:150px;}
    
</style>





<?php
    $temp = "";
    if( isset( $_GET['mb_id'] ) ) {
        $temp = "&mb_id=" . $_GET['mb_id'];
    }
        
    if( isset( $_GET['team'] ) ) {
        echo "<script type=\"text/javascript\">
            window.onload = pageLoad;
            function pageLoad(){
                 location.href=\"tree_1.php?team={$_GET['team']}{$temp}\"; 
            };
        </script>";
    } else {
        echo "<script type=\"text/javascript\">
            window.onload = pageLoad;
            function pageLoad(){
                 location.href=\"tree_1.php?{$temp}\"; 
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







