<?php 

	include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/connect_to_mysql.php";

	$Ordercount = "";
	
    $sql = "SELECT * FROM orders WHERE status = '0' ";
	$result = mysqli_query($db_conx, $sql);
	
	$rows = mysqli_num_rows($result);
	
	if($rows > 0){
		$Ordercount = $rows;
	}


?>