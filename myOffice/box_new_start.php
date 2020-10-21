<style>
    #lodingall{position: absolute;top: 45%;left: 50%;transform: translate(-50%,-50%); }
    #vmplogoimg{position: absolute;top: 38%;left: 50%;transform: translate(-50%,-50%); width:150px;}
    #lodingimg{position: absolute;top: 100px;left: 50%;transform: translate(-50%,-50%); width:150px;}
    
</style>



<script>
    //window.open('about:blank').location.href=\"box.dev3.php?mb_id={$_GET['mb_id']}\"; 
</script>

<?php

    if( isset( $_GET['mb_id'] ) ) {
        echo "<script type=\"text/javascript\">
            window.onload = pageLoad;
            function pageLoad(){
                    window.open('about:blank').location.href=\"box.devtmp.php?mb_id={$_GET['mb_id']}\"; 
                   location.href=\"index_start.php?mb_id={$_GET['mb_id']}\"; 
            };
        </script>";
    } else {
        echo "<script type=\"text/javascript\">
            window.onload = pageLoad;
            function pageLoad(){
                 window.open('about:blank').location.href=\"box.devtmp.php\"; 
                 location.href=\"index_start.php\"; 
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







