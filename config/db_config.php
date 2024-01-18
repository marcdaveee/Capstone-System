<?php 
$serverName = "localhost";
$userName = "admin";
$password = "lgusaq";
$db_name  = "it_inventory_asset";

$conn = mysqli_connect($serverName, $userName, $password, $db_name);

if(!$conn){
    die ("Connection Error ".mysqli_connect_error());
}


?>