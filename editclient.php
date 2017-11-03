<?php 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>

<?php 

if (isset($_SESSION["clientemail"])) {
	
	// Be sure to check that this User SESSION value is in fact in the database
	$id = preg_replace('#[^0-9]#i', '', $_SESSION['clientid']); // filter everything but numbers and letters
	$email = $_SESSION['clientemail']; // Can't filter because of email address
	$password = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION['clientpassword']); // filter everything but numbers and letters
	// Run mySQL query to be sure that this person is an admin and that their password session var equals the database information

	// Connect to the MySQL database  
	include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/connect_to_mysql.php";
	$sql = "SELECT * FROM clients WHERE id='$id' AND email='$email' AND password='$password' LIMIT 1";
	$result = mysqli_query($db_conx, $sql); // query the person

	// ------- MAKE SURE PERSON EXISTS IN DATABASE ---------
	if(mysqli_num_rows($result) == 0){ // evaluate the count
		 echo "Your login session data is not on record in the database.";
		 exit();
	}
} else {
	echo '<script type="text/javascript">
				location.replace("index.php");
			  </script>';
	//header("location: index.php"); 
	exit();
}
?>

<?php include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/connect_to_mysql.php"; ?>

<?php
// Gather client account details for inserting automatically into the edit form below on page
if (isset($_SESSION['clientid'])) {
	
	$targetid = preg_replace('#[^0-9]#i', '', $_SESSION['clientid']); // filter everything but numbers and letters
    $sql = "SELECT * FROM clients WHERE id='$targetid' LIMIT 1";
	$result = mysqli_query($db_conx, $sql);
	
	if(mysqli_num_rows($result) > 0){ // evaluate the count
		
		while($row = mysqli_fetch_assoc($result)){
			
			$id = $row['id'];
			$name = $row['clientname'];
			$hospital = $row['company_hospital'];
			$email = $row['email'];
			$password = $row['password'];
			$address = $row['address'];
			$phone = $row['phone'];
		}
		
	}else {
		echo '<script type="text/javascript">
				location.replace("404.php");
			  </script>';
	    //header("location: 404.php");
		exit();
    }
}
?>

<?php 
// Parse the form data and update client account details

if (isset($_POST['name'])) {
	
	$targetid = $_POST['thisid'];
	
    $name = $_POST['name'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$hospital = $_POST['hospital'];
	$phone = $_POST['phone'];
	$address = $_POST['address'];
	
	// See if that product name is an identical match to another product in the system
	$sql = "UPDATE clients SET clientname='$name', company_hospital='$hospital', email='$email', password='$password', address='$address', phone='$phone' WHERE id='$targetid'";
	if($result = mysqli_query($db_conx, $sql)){
		
		session_start();
	
		$_SESSION['clientid'] = $_POST['thisid'];
		$_SESSION['clientemail'] = $email;
		$_SESSION['clientpassword'] = $password;
		
		echo '<script type="text/javascript">
				location.replace("index.php");
			  </script>';
		//header("location: index.php"); 
		exit();
	} else {
		echo '<script type="text/javascript">
				location.replace("editclient.php?error");
			  </script>';
		//header("location: editclient.php?error");
		exit();
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport"    content="width=device-width, initial-scale=1.0">
	<meta name="Sign Up" content="">

	<title> Manage profile </title>

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
	
</head>

<body class="page-signin">
	
	<div class="container" style="padding-top: 8%">

		<div class="panel panel-default">
			<div class="panel-body">
					<div class="col-md-12">
						
						<h3 class="text-center"> Manage Your Profile </h3>
						<br>
						<br>
						
						<form method="post" id="editclient" action="editclient.php" novalidate>
							
							<div class="row">
								<div class="col-md-6">
						
									<div class="form-group">
										<input type="text" class="form-control" value="<?php echo $name; ?>"  name="name" id="name" required data-validation-required-message="Please enter your name.">
										<p class="help-block text-danger"></p>
									</div>
									
									<div class="form-group">
										<input type="email" class="form-control" value="<?php echo $email; ?>" name="email" id="email" required data-validation-required-message="Please enter your email address.">
										<p class="help-block text-danger"></p>
									</div>
									
									<div class="form-group">
										<input type="Password" class="form-control" value="<?php echo $password; ?>" name="password" id="password" required data-validation-required-message="Please enter a Password.">
										<p class="help-block text-danger"></p>
									</div>
									
									<div class="form-group">
										<input type="Password" class="form-control" value="<?php echo $password; ?>" name="confirmpassword" id="confirmpassword" data-validation-matches-match="password" data-validation-matches-message="Must match Password entered above" >
										<p class="help-block text-danger"></p>
									</div>
								</div>
								
								<div class="col-md-6">
									
									<div class="form-group">
										<input type="text" class="form-control" value="<?php echo $hospital; ?>" name="hospital" id="hospital" required data-validation-required-message="Please enter Company / Hospital name.">
										<p class="help-block text-danger"></p>
									</div>
									
									<div class="form-group">
										<input type="tel" class="form-control" value="<?php echo $phone; ?>" name="phone" id="phone" required data-validation-required-message="Please enter your phone number.">
										<p class="help-block text-danger"></p>
									</div>
									
									<div class="form-group">
										<textarea class="form-control" rows="4" name="address" id="address" required data-validation-required-message="Please enter a Address."><?php echo $address; ?></textarea>
										<p class="help-block text-danger"></p>
									</div>
								</div>
							</div>
							
							<div class="clearfix"></div>
                            
							<div class="form-group text-center" style="padding-top: 3%; padding-bottom: 3%;">
								<input name="thisid" type="hidden" value="<?php echo $id; ?>">
								<button class="btn btn-primary" type="submit" style="padding-left: 15px; padding-right: 15px;"> Save </button>
							</div>
						</form>
						<div id="Message">
							<?php
								if (isset($_GET['error'])==true) {
									echo '<div class="center">
											<div class="text-center">
												<div class="alert alert-danger"">
													<strong> Error </strong> Please try again.
												</div>
											</div>
										  </div>';
								}								
							?>
						</div>
					</div>
				
				
				<!-- the switcher between forms-->
				<ul class="toggler text-center small list-unstyled" >
					<li>Changed your mind? <a class="text-blue" href="index.php">Back to Home page</a></li>
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
	
	<!-- Contact Form JavaScript -->
    <script src="js/jqBootstrapValidation.js"></script>
    <script>
	$(function() {

    $("#editclient input,#editclient textarea").jqBootstrapValidation({
        
        submitError: function($form, event, errors) {
            // additional error messages or events
        },
    });

    $("a[data-toggle=\"tab\"]").click(function(e) {
        e.preventDefault();
        $(this).tab("show");
    });
});


/*When clicking on Full hide fail/success boxes */
$('#name').focus(function() {
    $('#success').html('');
});
	</script>
</body>
</html>
