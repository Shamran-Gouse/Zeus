<?php 

	include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/connect_to_mysql.php";

	$reordercount = "";
	
    $sql = "SELECT * FROM products WHERE `quantity` <= `reorder`";
	$result = mysqli_query($db_conx, $sql);
	
	$rows = mysqli_num_rows($result);
	
	if($rows > 0){
		$reordercount = $rows;
	}


?>