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

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> Zeus - Clients details </title>

    <?php include('Adminstyle.php'); ?>
	
</head>

<body>

    <div id="wrapper">
        <div id="page-wrapper">
		
			<?php include('admin_navigation.php'); ?>
			
            <div class="container-fluid">
			     
                <div class="row">
                    <div class="col-xs-offset-1 col-xs-10 col-xs-offset-1">

                        <!-- Main content -->
                        <section class="content" style="margin: 20% 0;">

                            <div class="error-page">
                                <h2 class="headline text-info"> 404</h2>
                                <div class="error-content">
                                    <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>
                                    <p>
                                        We could not find the page you were looking for. 
                                        Meanwhile, you may <a href='index.html'>return to Dashboard.</a>
                                    </p>

                                </div><!-- /.error-content -->
                            </div><!-- /.error-page -->

                        </section><!-- /.content -->

                    </div>
                </div>
                
			</div>
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
