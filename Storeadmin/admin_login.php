<?php 
session_start();
if (isset($_SESSION["username"])) {
	
	echo '<script type="text/javascript">
				location.replace("index.php");
			  </script>';
    //header("location: index.php"); 
    exit();
}

?>

<?php

// Datase Connection
include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/connect_to_mysql.php";

if($_POST){
	$sql = "SELECT * FROM admin WHERE username = '$_POST[username]' AND password = '$_POST[password]'";
	$result = mysqli_query($db_conx, $sql);
	
	
	
	if(mysqli_num_rows($result) == 1){
		
		$id = mysqli_fetch_array($result,MYSQLI_NUM);
		//$id = mysqli_insert_id($db_conx);
		$_SESSION['id'] = $id[0];
		$_SESSION['username'] = $_POST['username'];
		$_SESSION['password'] = $_POST['password'];
		
		echo '<script type="text/javascript">
				location.replace("index.php");
			  </script>';
		//header('Location: index.php');
		exit();
	} else {
		
		echo '<script type="text/javascript">
				location.replace("admin_login.php?error");
			  </script>';
		//header("location: admin_login.php?error"); 
		exit();
	}
}
?>

<?php
// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title> Admin Log In </title>
	
	<?php include('Adminstyle.php'); ?>
	
  </head>  

<body background="../img/admin-login.jpg">
	
	<div class="container">
	    <div class="row" style="margin: 10% 0 15% 0;">
		    <div class="col-md-4 col-md-offset-4">
				<div class="panel panel-default" >
					<div class="panel-heading">
						<strong>Log In</strong>
					</div> <!--END pannel heading-->
					<div class="panel-body">				
					
						<form action="admin_login.php" method="post" style="margin-top: 7%; margin-bottom: 7%;">
							<div class="form-group">
								<input type="text" class="form-control" id="username" name="username" placeholder="User Name" required>
							</div>
						
							<div class="form-group">
								<input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
							</div>
							<div class="text-center">					
								<button type="submit" class="btn btn-default"> Login </button>
							</div>
						</form>
						
						<div id="Message">
							<?php
								if (isset($_GET['error'])==true) {
									echo '<div class="center" style="padding-top: 15px;">
											<div class="text-center">
												<div class="alert alert-danger"">
													<strong> User Name or Password error.</strong>
												</div>
											</div>
										  </div>';
								}
							?>
						</div>
						
					</div> <!--END pannel body-->
				</div> <!--END pannel-->
			</div> <!--END column-->
		</div> <!--END row-->
	</div> <!--END container-->
	 
    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>