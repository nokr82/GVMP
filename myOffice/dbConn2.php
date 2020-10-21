<?php
    $connection = mysqli_connect("192.168.0.30", "root", "Neungsoft1!", "gyc5", "3306");
    
    
    
    mysqli_query($connection, "set session character_set_connection=utf8") or die("ERROR");
    mysqli_query($connection, "set session character_set_results=utf8") or die("ERROR");
    mysqli_query($connection, "set session character_set_client=utf8") or die("ERROR");

    // mysqli로 DB 연결하는 로직
    
?>