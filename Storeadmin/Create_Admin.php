<?php 
session_start();
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
// Erorr Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>

<?php 

include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/connect_to_mysql.php";
if(isset($_POST['name'])){
	
	$user = $_POST['name'];
	$password = $_POST['password'];
	$adminpassword = $_POST['adminpassword'];
	
	if($adminpassword == "123")
	{
		$query = "INSERT INTO admin (username,password,last_log_date) VALUES ('$user','$password',now())";
		$result = mysqli_query($db_conx, $query);

		if($result){
			
			echo '<script type="text/javascript">
				location.replace("Create_Admin.php?success");
			  </script>';
			//header("location: Create_Admin.php?success"); 
			exit();
		} else {
			
			echo '<script type="text/javascript">
				location.replace("Create_Admin.php?error");
			  </script>';
			//header("location: Create_Admin.php?error"); 
			exit();
		}
	}
	else
	{
		echo '<script type="text/javascript">
				location.replace("Create_Admin.php?adminerror");
			  </script>';
		//header("location: Create_Admin.php?adminerror"); 
		exit();
	}
	
	
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> Zeus - Create Admin User </title>

    <?php include('Adminstyle.php'); ?>

	<script type="text/javascript">
      // Form validation code
	  
	  
	  
      function validate()
      {
		  var isvalid = true;
		  document.getElementById("Name").innerHTML = "";
		  document.getElementById("Password").innerHTML = "";
		  document.getElementById("ConfirmPassword").innerHTML = "";
		  document.getElementById("AdminPassword").innerHTML = "";
      
         if( document.Create_Admin.name.value == "" )
         {
			document.getElementById("Name").innerHTML = "Please provide Name.";
            document.Create_Admin.name.focus() ;
            isvalid = false;
         }
		 else if( document.Create_Admin.password.value == "")
         {
            document.getElementById("Password").innerHTML = "Please enter a Password.";
            document.Create_Admin.password.focus() ;
            isvalid = false;
         }
		 else if( document.Create_Admin.confirmpassword.value == "")
         {
            document.getElementById("ConfirmPassword").innerHTML = "Please provide confirmpassword";
            document.Create_Admin.confirmpassword.focus() ;
            isvalid = false;
         }
		 else if( document.Create_Admin.password.value != document.Create_Admin.confirmpassword.value)
         {
            document.getElementById("ConfirmPassword").innerHTML = "Please provide same password in both fields";
            document.Create_Admin.confirmpassword.focus() ;
            isvalid = false;
         }
		 else if( document.Create_Admin.adminpassword.value == "")
         {
            document.getElementById("AdminPassword").innerHTML = "Please enter Admin password.";
            document.Create_Admin.adminpassword.focus() ;
            isvalid = false;
         }
         return isvalid;
      }
</script>
	
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
                            Create Admin User
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="index.php">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-fw fa-user-plus"></i> Create Admin User
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
			
			
			
				<div class="row">
					<div class="col-md-4 col-md-offset-4">
						<div class="panel panel-default" style="margin:10% 0 20% 0">
							<div class="panel-heading">
								<strong> Create Admin User </strong>
							</div> <!--END pannel heading-->
							<div class="panel-body">
								<div id="Message">
									<?php
										
										if (isset($_GET['success'])==true) {
											echo '<div class="center">
													<div class="text-center">
														<div class="alert alert-success"">
															<strong> Successfully Created.</strong>
														</div>
													</div>
												  </div>';
										}
									
										if (isset($_GET['error'])==true) {
											echo '<div class="center" style="padding-top: 15px; padding-bottom: 15px;">
													<div class="text-center">
														<div class="alert alert-danger"">
															<strong> Sorry Something went wrong.</strong>
														</div>
													</div>
												  </div>';
										}
										
										if (isset($_GET['adminerror'])==true) {
											echo '<div class="center" style="padding-top: 15px; padding-bottom: 15px;">
													<div class="text-center">
														<div class="alert alert-danger"">
															<strong> Admin Password did not match.</strong>
														</div>
													</div>
												  </div>';
										}
									?>
								</div>
								
								<form method="post" action="Create_Admin.php" id="Create_Admin" name="Create_Admin">
									
									<div class="form-group">
										<label for="username">User Name</label>
										<input type="text" class="form-control" placeholder="User Name *" id="name" name="name">
										<p id="Name" class="help-block text-danger text-center"></p>
									</div>
									
									<div class="form-group">
										<label for="exampleInputPassword1">Password</label>
										<input type="Password" class="form-control" placeholder="Password *" name="password" id="password">
										<p id="Password" class="help-block text-danger text-center"></p>
									</div>
									
									<div class="form-group">
										<label for="exampleInputPassword1"> Confirm Password</label>
										<input type="Password" class="form-control" placeholder="Confirm Password *" name="confirmpassword" id="confirmpassword">
										<p id="ConfirmPassword" class="help-block text-danger text-center"></p>
									</div>
									
									<div class="form-group">
										<label for="exampleInputPassword1"> Zeus Admin Password</label>
										<input type="Password" class="form-control" placeholder="Zeus Admin Password *" name="adminpassword" id="adminpassword">
										<p id="AdminPassword" class="help-block text-danger text-center"></p>
									</div>
									
									<div class="text-center">
										<button type="submit" class="btn btn-default" onclick="javascript:return validate();" id="submitted">Submit</button>
									</div>
								</form>
							</div> <!--END pannel body-->
						</div> <!--END pannel-->
					</div> <!--END column-->
				</div> <!--END row-->
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
