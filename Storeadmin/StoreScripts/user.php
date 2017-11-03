<?php

//database connection
include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/connect_to_mysql.php";

$query = "SELECT * FROM admin WHERE username = '$_SESSION[username]'";
$result = mysqli_query($db_conx, $query);

$user = mysqli_fetch_assoc($result);

?>