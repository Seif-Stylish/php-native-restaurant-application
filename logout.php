<?php

    session_start();
    session_destroy();
    if(isset($_COOKIE["remember_me"]))
    {
        setcookie("remember_me" , "" , time() - 3600 , "/");
    }
    header("location:login.php");die;

?>