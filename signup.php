<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>

<?php 

include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/connect_to_mysql.php";
if(isset($_POST['name'])){
	
	$name = $_POST['name'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$hospital = $_POST['hospital'];
	$phone = $_POST['phone'];
	$address = $_POST['address'];
	
	// See if the email address is an identical match to email address in the system
	$sql = "SELECT id FROM clients WHERE email='$email' LIMIT 1";
	$result = mysqli_query($db_conx, $sql);
	
	$productMatch = mysqli_num_rows($result); // count the output amount
	
    if ($productMatch > 0) {
		echo '<script type="text/javascript">
				location.replace("signup.php?error");
			  </script>';
		//header("location: signup.php?error");
		exit();
	}

	// Add user into the database now
	$sql = "INSERT INTO clients (clientname, company_hospital, email, password, address, phone, signup_date) VALUES ('$name','$hospital','$email','$password','$address','$phone',now())" or die (mysql_error());
	$result = mysqli_query($db_conx, $sql);
	
	echo '<script type="text/javascript">
				location.replace("signup.php?success");
			  </script>';
	//header("location: signup.php?success"); 
	exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport"    content="width=device-width, initial-scale=1.0">
	<meta name="Sign Up" content="">

	<title> Sign up </title>

	<?php include('stylelink_Client.php'); ?>
	
	<style>
		#home {
	position: fixed;
	right: 20px;
	bottom: 20px;
}

	.center {
    margin: auto;
    width: 30%;
}
	</style>
	
	<script type="text/javascript">
      // Form validation code
      function validate()
      {
		  var isvalid = true;
		  var emailID = document.signup.email.value;
          atpos = emailID.indexOf("@");
          dotpos = emailID.lastIndexOf(".");
		  var phoneno = /^\d{10}$/;
      
         if( document.signup.name.value == "" )
         {
            alert( "Please provide your name!" );
            document.signup.name.focus() ;
            isvalid = false;
         }
         else if( document.signup.email.value == "" )
         {
            alert( "Please provide your Email!" );
            document.signup.email.focus() ;
            isvalid = false;
         }
		 else if (atpos < 1 || ( dotpos - atpos < 2 )) 
         {
            alert("Please enter correct Email ID")
            document.signup.email.focus() ;
            isvalid = false;
         }
		 else if( document.signup.password.value == "")
         {
            alert( "Please provide password!" );
            document.signup.password.focus() ;
            isvalid = false;
         }
		 else if( document.signup.confirmpassword.value == "")
         {
            alert( "Please provide confirmpassword!" );
            document.signup.confirmpassword.focus() ;
            isvalid = false;
         }
		 else if( document.signup.password.value != document.signup.confirmpassword.value)
         {
            alert( "Please provide same password in both fields" );
            document.signup.confirmpassword.focus() ;
            isvalid = false;
         }
		 else if( document.signup.hospital.value == "")
         {
            alert( "Please provide Company / Hospital Name!" );
            document.signup.hospital.focus() ;
            isvalid = false;
         }
		 else if( document.signup.phone.value == "")
         {
            alert( "Please provide Phone Number!" );
            document.signup.phone.focus() ;
            isvalid = false;
         }
		 else if( document.signup.phone.value == "")
         {
            alert( "Please provide Phone Number!" );
            document.signup.phone.focus() ;
            isvalid = false;
         }
		 else if( document.signup.address.value == "")
         {
            alert( "Please provide Address!" );
            document.signup.address.focus() ;
            isvalid = false;
         }
         return isvalid;
      }
</script>
	
</head>

<body class="page-signin">
	
	<div class="container" style="padding-top: 8%">
		<div class="panel panel-default">
			<div class="panel-body">

					<!-- sign up-->
					<div class="col-md-12">
						
						<h3 class="text-center">Registration</h3>
						<p class="text-center">
							<br>
							Please fill in the form below and we will get back to you shortly.
							<br>
						</p>
						
						<form method="post" action="signup.php" id="signup" name="signup">
							
							<div class="row">
								<div class="col-md-6">
						
									<div class="form-group">
										<input type="text" class="form-control" placeholder="Your Name *"  name="name" id="name">
										<p class="help-block text-danger"></p>
									</div>
									
									<div class="form-group">
										<input type="email" class="form-control" placeholder="Email *" name="email" id="email">
										<p class="help-block text-danger"></p>
									</div>
									
									<div class="form-group">
										<input type="Password" class="form-control" placeholder="Password *" name="password" id="password">
										<p class="help-block text-danger"></p>
									</div>
									
									<div class="form-group">
										<input type="Password" class="form-control" placeholder="Confirm Password *" name="confirmpassword" id="confirmpassword">
										<p class="help-block text-danger"></p>
									</div>
								</div>
								
								<div class="col-md-6">
									
									<div class="form-group">
										<input type="text" class="form-control" placeholder="Company / Hospital *" name="hospital" id="hospital">
										<p class="help-block text-danger"></p>
									</div>
									
									<div class="form-group">
										<input type="tel" class="form-control" placeholder="Your Phone *" name="phone" id="phone">
										<p class="help-block text-danger"></p>
									</div>
									
									<div class="form-group">
										<textarea class="form-control" rows="4" placeholder="Address *" name="address" id="address"></textarea>
										<p class="help-block text-danger"></p>
									</div>
								</div>
							</div>
							
							<div class="clearfix"></div>
                            
							<div class="form-group text-center" style="padding-top: 3%; padding-bottom: 3%;">
								<button class="btn btn-primary" type="submit" onclick="javascript:return validate();">Register</button>
							</div>
						</form>
						<div id="Message">
							<?php
								if (isset($_GET['error'])==true) {
									echo '<div class="center">
											<div class="text-center">
												<div class="alert alert-danger"">
													<strong> Email address already exist.</strong>
												</div>
											</div>
										  </div>';
								}
								
								if (isset($_GET['success'])==true) {
									echo '<div class="center">
											<div class="text-center">
												<div class="alert alert-success"">
													<strong> Successfully Added.</strong>
												</div>
											</div>
										  </div>';
								}
								
							?>
						</div>
					</div>
				
				
				<!-- the switcher between forms-->
				<ul class="toggler text-center small list-unstyled" >
					<li>Changed your mind? <a class="text-blue" href="signin.php">Back to sign in form</a></li>
					<li><a class="text-blue" href="resetpassword.php">Forgot password?</a></li>
				</ul>
				
				<a href="index.php" id="home"><i class="fa fa-home fa-4x"></i></a>

			</div>
		</div>
		<!-- /panel -->
	</div>

	<!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>
</html>
