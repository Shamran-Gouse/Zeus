<?php 
session_start();
// Erorr Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>

<?php 

if (!isset($_SESSION["username"])) {
	echo '<script type="text/javascript">
				location.replace("admin_login.php");
			  </script>';
    //header("location: admin_login.php"); 
    exit();
}

	// Be sure to check that this User SESSION value is in fact in the database
	$userid = preg_replace('#[^0-9]#i', '', $_SESSION["id"]); // filter everything but numbers and letters
	$user = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["username"]); // filter everything but numbers and letters
	$password = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["password"]); // filter everything but numbers and letters
	// Run mySQL query to be sure that this person is an admin and that their password session var equals the database information

	// Connect to the MySQL database  
	include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/connect_to_mysql.php";
	$sql = "SELECT * FROM admin WHERE id='$userid' AND username='$user' AND password='$password' LIMIT 1";
	$result = mysqli_query($db_conx, $sql); // query the person

	// ------- MAKE SURE PERSON EXISTS IN DATABASE ---------
	if(mysqli_num_rows($result) == 0){ // evaluate the count
	//header("location: admin_login.php");
		 echo "Your login session data is not on record in the database.";
		 exit();
	}
?>

<?php

$output = "";

include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/connect_to_mysql.php";

if(isset($_POST['search'])){
	
	$searchq = $_POST['search'];
	//$searchq = preg_replace("#[^0-9a-z]#i"," ",$searchq);
	
	$query = "SELECT * FROM orders WHERE O_id LIKE '%$searchq%' OR C_id LIKE '%$searchq%' OR Date LIKE '%$searchq%'";
	$result = mysqli_query($db_conx, $query);

	if(mysqli_num_rows($result) == 0){ // evaluate the count
	
		echo '<script type="text/javascript">
				location.replace("search_order.php?error");
			  </script>';
		 //header("location: search_order.php?error"); 
		 exit();
	}
	else
	{
		while($row = mysqli_fetch_assoc($result)){
			
			$O_id = $row['O_id'];
			$Total = $row['Total'];
			$C_id = $row['C_id'];
			$Date = $row['Date'];
			$Status = $row['Status'];
			
            if($Status == 0)
			{
				$output .= '<tr class="unread">
			<td class="text-center"><a href="Orderdetail.php?Oid='.$O_id.'">'.$O_id.'</a></td>
			<td class="text-center"><a href="Orderdetail.php?Oid='.$O_id.'">'.$Total.'</a></td>
			<td class="text-center"><a href="Orderdetail.php?Oid='.$O_id.'">'.$C_id.'</a></td>
			<td class="text-center"><a href="Orderdetail.php?Oid='.$O_id.'">'.$Date.'</a></td>
			<td class="text-center"><a href="Orderdetail.php?Oid='.$O_id.'">NOT PAID</a></td>
			</tr>';
			
			} else {
				
				$output .= '<tr class="read">
			<td class="text-center"><a href="Orderdetail.php?Oid='.$O_id.'">'.$O_id.'</a></td>
			<td class="text-center"><a href="Orderdetail.php?Oid='.$O_id.'">'.$Total.'</a></td>
			<td class="text-center"><a href="Orderdetail.php?Oid='.$O_id.'">'.$C_id.'</a></td>
			<td class="text-center"><a href="Orderdetail.php?Oid='.$O_id.'">'.$Date.'</a></td>
			<td class="text-center"><a href="Orderdetail.php?Oid='.$O_id.'">PAID</a></td>
			</tr>';
			
			}
		}
	}
	
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Zeus - Orders Search</title>

    <?php include('Adminstyle.php'); ?>

</head>

<body>

    <div id="wrapper">
        <div id="page-wrapper">
			<?php include('admin_navigation.php'); ?>
            <div class="container-fluid">
			
				<!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Search
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="index.php">Dashboard</a>
                            </li>
                            <li class="active">
								<span class="fa fa-fw fa-shopping-cart"></span>&nbsp; Search Orders
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
				
				<ul class="nav nav-tabs">
				  <li><a href="search_product.php"> Product </a></li>
				  <li><a href="search_Admin_User.php"> Admin User </a></li>
				  <li><a href="search_Client.php"> Client </a></li>
				  <li class="active"><a href="search_order.php"> Order </a></li>
				</ul>
				
				<div class="row">
					<div class="col-xs-offset-1 col-xs-10 col-xs-offset-1" style="margin-top:20px; margin-bottom:20px;">
						<form action="search_order.php" method="post">
								<div class="form-group input-group">
									<input type="text" class="form-control" name="search" placeholder="Search Item">
									<!--span class="input-group-btn"><button class="btn btn-default" type="button" name="search"><i class="fa fa-search"></i></button></span-->
									<span class="input-group-btn"><input class="btn btn-default" type="submit" name="button"></span>
								</div>
						</form>
						<div id="Message">
							<?php
								if (isset($_GET['error'])==true) {
									echo '<div class="center" style="padding-top: 15px;">
											<div class="text-center">
												<div class="alert alert-danger"">
													<strong> NO Data on record.</strong>
												</div>
											</div>
										  </div>';
								}
							?>
						</div>
					</div>
				</div>
					
					
				<div class="row">
					<div class="col-xs-offset-1 col-xs-10 col-xs-offset-1">
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th class="text-center">ID</th>
										<th class="text-center"> Total </th>
										<th class="text-center"> Client ID </th>
										<th class="text-center"> Date Added </th>
										<th class="text-center"> Status </th>
									</tr>
								</thead>
								<tbody>							
									
									
									<?php print("$output"); ?>
									
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!-- /.row -->
				
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
	
	<!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>