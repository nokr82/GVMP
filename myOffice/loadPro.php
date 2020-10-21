


<style>
    #lodingall{position: absolute;top: 45%;left: 50%;transform: translate(-50%,-50%); }
    #vmplogoimg{position: absolute;top: 38%;left: 50%;transform: translate(-50%,-50%); width:150px;}
    #lodingimg{position: absolute;top: 100px;left: 50%;transform: translate(-50%,-50%); width:150px;}
    
</style>





<?php

// 로딩 화면 처리하는 소스코드입니다



?>



<body id="body">
    <div id="lodingall">
    <img src="images/VMP logo.png" alt="로고" id="vmplogoimg">
    <img src="images/loding.gif" alt="로딩이미지" id="lodingimg">
    </div>
</body>






<script type="text/javascript">
    window.onload = pageLoad;
    function pageLoad(){
         location.href="<?=$_GET["uri"]?>"; 
    };
</script>
