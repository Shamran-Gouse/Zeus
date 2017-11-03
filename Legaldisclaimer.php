<?php 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>

<?php

$account ='';

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
	} elseif (mysqli_num_rows($result) == 1){
		
		while($row = mysqli_fetch_assoc($result)){
			
			$id = $row['id'];
		}
		
		$account.='<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"> MY Account <i class="fa fa-angle-down"></i></a>
						<ul class="dropdown-menu">
							<li><a class="text-center" style="font-size: 13px;" href="orderhistory.php"> Order History </a></li>
							<li><a class="text-center" style="font-size: 13px;" href="editclient.php"> Edit your details </a></li>
							<li><a class="text-center" style="font-size: 13px;" href="signout.php"> Sign Out </a></li>
						</ul>
					</li>';
	}
} else {
	$account.='<a class="page-scroll" href="signin.php" title="SIGN IN / SIGN UP"> SIGN IN / SIGN UP </a>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> Legal disclaimer </title>

    <?php include('stylelink_Client.php'); ?>
	
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-static-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand page-scroll" href="index.php"> Zeus Pvt Ltd</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    
                    <li class="active">
                        <a class="page-scroll" href="#page-top">Products</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="index.php#about">About</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="index.php#contact">Contact</a>
                    </li>
					<li>
						<?php echo $account; ?>
                    </li>
					
					<li>
                        <a class="page-scroll" href="cart.php" title="Cart"> Cart <i class="fa fa-shopping-cart fa-lg"></i></a>
                    </li>
                    
                    <li>
                        <a class="page-scroll" id="search_button" style="cursor:pointer"> <i class="fa fa-search fa-2x"></i> </a>
                    </li>

                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->

        <div id="search_form" class="navbar-form search_div">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search this site">
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-primary">
                    <span class="glyphicon glyphicon-search"></span>
                    </button>
                </span>
            </div>
        </div>

    </nav>

    <script>
        $( "#search_button" ).click(function() {
          $( "#search_form" ).toggle();
        });
    </script>
    
    <div class="container">
        <div class="row content_top">
            <div class="col-xs-12">
                <!-- Main content -->
                <section class="content">
                    
                    <h2 class="privacy_header">Legal disclaimer</h2>
                    
                    <article>
                        <p>The information contained in this website is for general information purposes only. The information is provided by Zeus and while we endeavour to keep the information up to date and correct, we make no representations or warranties of any kind, express or implied, about the completeness, accuracy, reliability, suitability or availability with respect to the website or the information, products, services, or related graphics contained on the website for any purpose. Any reliance you place on such information is therefore strictly at your own risk.</p>
                    </article>
                    
                    <article>
                        <p>In no event will we be liable for any loss or damage including without limitation, indirect or consequential loss or damage, or any loss or damage whatsoever arising from loss of data or profits arising out of, or in connection with, the use of this website.</p>
                    </article>
                    
                    <article>
                        <p>Through this website you are able to link to other websites which are not under the control of Zeus. We have no control over the nature, content and availability of those sites. The inclusion of any links does not necessarily imply a recommendation or endorse the views expressed within them.</p>
                    </article>
                    
                    <article>
                        <p>Every effort is made to keep the website up and running smoothly. However, Zeus takes no responsibility for, and will not be liable for, the website being temporarily unavailable due to technical issues beyond our control.</p>
                    </article>

                </section><!-- /.content -->
            </div>
        </div>
    </div>
    
    <footer class="static-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <span class="copyright">
					Copyright &copy; <?php echo date("Y"); ?> Zeus Pvt Ltd. All rights reserved.</span>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline social-buttons">
						<li>
							<a href="https://www.facebook.com/Zeuspvtltd/" target="_blank"><i class="fa fa-facebook"></i></a>
						</li>
						<li>
							<a href="https://plus.google.com/u/0/115798558354778694399/posts" target="_blank"><i class="fa fa-google-plus"></i></a>
						</li>
						<li>
							<a href="https://www.youtube.com/channel/UCgoT5hDQEPQBo1XXwFRvbbA" target="_blank"><i class="fa fa-youtube"></i></a>
						</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline quicklinks">
                        <li><a href="Privacy_Policy.php">Privacy Policy</a>
                        </li>
                        <li><a href="Legaldisclaimer.php">Legal disclaimer</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>