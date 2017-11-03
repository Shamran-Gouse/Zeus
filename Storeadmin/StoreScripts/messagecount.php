<?php 

	include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/connect_to_mysql.php";

	$messagecount = "";
	
    $sql = "SELECT * FROM contact_message WHERE status = '0' ";
	$result = mysqli_query($db_conx, $sql);
	
	$rows = mysqli_num_rows($result);
	
	if($rows > 0){
		$messagecount = $rows;
	}


?>