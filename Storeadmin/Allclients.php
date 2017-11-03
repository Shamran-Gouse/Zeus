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
		 echo "Your login session data is not on record in the database.";
		 exit();
	}
?>

<?php 

	$output = "";
	include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/connect_to_mysql.php";
	
	
	$query = "SELECT * FROM clients";
	$result = mysqli_query($db_conx, $query);

	if(mysqli_num_rows($result) == 0){
		 $output = '<div class="noproducts"> No Clients to Display </div>';
	}
	else
	{
		while($row = mysqli_fetch_assoc($result)){
			
			$id = $row['id'];
			$Name = $row['clientname'];
			$com_hos = $row['company_hospital'];
			$Phone = $row['phone'];
			
			$output .= '<tr>
			<td><a href="Client_details.php?cid='.$id.'">'.$Name.'</td></a>
			<td><a href="Client_details.php?cid='.$id.'">'.$com_hos.'</td></a>
			<td>'.$Phone.'</td>
			</tr>';
		}
	}

?>

<!DOCTYPE html>
<html lang="en">

	<head>

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title> Zeus - Clients </title>

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
								Clients
							</h1>
							<ol class="breadcrumb">
								<li>
									<i class="fa fa-dashboard"></i>  <a href="index.php">Dashboard</a>
								</li>
								<li class="active">
									<i class="fa fa-fw fa-users"></i> Clients
								</li>
							</ol>
						</div>
					</div>
					<!-- /.row -->					
						
					<div class="row">
						<div class="col-xs-offset-1 col-xs-10 col-xs-offset-1">
							<div class="table-responsive">
								<table class="table table-hover table-striped" data-link="row">
									<thead>
										<tr>
											<th> Name </th>
											<th> Hospital / Company </th>
											<th> Phone Number </th>
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