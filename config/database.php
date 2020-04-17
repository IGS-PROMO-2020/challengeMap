<?php

    $db = mysqli_connect('localhost','root','root','challengegeo');

    if(!$db){
        die('Failed to connect database '.mysqli_connect_error());
    }
?>
