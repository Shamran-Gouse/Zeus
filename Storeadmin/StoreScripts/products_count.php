<?php 

	include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/connect_to_mysql.php";

	$productscount = "";
	
    $sql = "SELECT * FROM products";
	$result = mysqli_query($db_conx, $sql);
	
	$rows = mysqli_num_rows($result);
	
	if($rows > 0){
		$productscount = $rows;
	}


?>