<?php 

session_start();

session_destroy();

header("Location: /Capstone_System/login.php");

?>