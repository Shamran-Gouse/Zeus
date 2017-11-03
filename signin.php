<?php 
session_start();
if (isset($_SESSION["clientemail"])) {
	echo '<script type="text/javascript">
				location.replace("index.php");
			  </script>';
    //header("location: index.php"); 
    exit();
}

?>

<?php
// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>

<?php 

// Datase Connection
include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/connect_to_mysql.php";

if(isset($_POST['email'])){
	$sql = "SELECT * FROM clients WHERE email = '$_POST[email]' AND password = '$_POST[password]'";
	$result = mysqli_query($db_conx, $sql);
	
	
	
	if(mysqli_num_rows($result)){
		
		$id = mysqli_fetch_array($result,MYSQLI_NUM);
		$_SESSION['clientid'] = $id[0];
		$_SESSION['clientemail'] = $_POST['email'];
		$_SESSION['clientpassword'] = $_POST['password'];
		
		echo '<script type="text/javascript">
				location.replace("index.php");
			  </script>';
		//header('Location: index.php');
		exit();
		
	} else {
		echo '<script type="text/javascript">
				location.replace("signin.php?error");
			  </script>';
		//header("location: signin.php?error");
		exit();
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport"    content="width=device-width, initial-scale=1.0">
	<meta name="Sign In" content="">

	<title> Sign in </title>
	
	<?php include('stylelink_Client.php'); ?>
	
	<script type="text/javascript">
      // Form validation code
      function validate()
      {
		  var isvalid = true;
		  var emailID = document.getElementById("email").value
          atpos = emailID.indexOf("@");
          dotpos = emailID.lastIndexOf(".");
		  
      
         if ( document.getElementById("email").value == "" )
         {
            document.getElementById("Email").innerHTML = "Please enter your email address.";
            document.signin.email.focus() ;
            isvalid = false;
         }
		 else if (atpos < 1 || ( dotpos - atpos < 2 )) 
         {
            document.getElementById("Email").innerHTML = "Not a valid email address.";
            document.signin.email.focus() ;
            isvalid = false;
         }
		 else if( document.signin.password.value == "")
         {
            document.getElementById("Password").innerHTML = "Please enter a Password.";
            document.signin.password.focus() ;
            isvalid = false;
         }
		 
		 return isvalid;
      }
</script>
	
	<style>
		#home {
	position: fixed;
	right: 20px;
	bottom: 20px;
}
	</style>

</head>

<body class="page-signin">

	<div class="container" style="padding-top: 25%">

		<div class="col-md-3 section-signin">
		
			<div class="panel panel-default">
				<div class="panel-body">
		
						<!-- sign in -->
						
						
							<h3 class="text-center">Sign in</h3>
							<p class="text-center">
								<br>
								Please use the form below to sign in to your account.
								<br>
							</p>
							
							<div id="Message">
								<?php
									if (isset($_GET['error'])) {
										echo '<div class="text-center">
													<div class="alert alert-danger"">
														The email and password you entered did not match.
													</div>
											  </div>';
									}
									
									if (isset($_GET['signin'])) {
										echo '<div class="text-center">
													<div class="alert alert-success"">
														Please Sign in to continue your Purchase.
													</div>
											  </div>';
									}
								?>
							</div>
							<!---->
							<form method="post" action="signin.php" name="signin" id="signin" >
								
								<div class="form-group">
									<input type="email" class="form-control" placeholder="Email *" name="email" id="email">
									<p id="Email" class="help-block text-danger text-center"></p>
								</div>
								
								<div class="form-group">
									<input type="Password" class="form-control" placeholder="Password *" name="password" id="password">
									<p id="Password" class="help-block text-danger text-center"></p>
								</div>
								
								<div class="form-group text-center" style="padding-top: 3%; padding-bottom: 3%;">
									<button class="btn btn-primary" type="submit" onclick="javascript:return validate();">Sign in</button>
								</div>
							</form>
							
							
						<!-- end of sign in -->
					
					<!-- the switcher between forms-->
					<ul class="toggler text-center small list-unstyled" >
						<li>Don't have an account? <a class="text-blue" href="signup.php">Register one now</a></li>
						<li><a class="text-blue" href="resetpassword.php">Forgot password?</a></li>
					</ul>
					
					<a href="index.php" id="home"><i class="fa fa-home fa-4x"></i></a>

				</div>
			</div>
			<!-- /panel -->
		</div>
	</div>
	<!-- /container -->

	<!-- jQuery -->
    <script src="js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
	
</body>
</html>
