<?php 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>

<?php

$account ='';
$signinbtn ='';

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
		
		$account.='<li class="dropdown active">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"> MY Account <i class="fa fa-angle-down"></i></a>
						<ul class="dropdown-menu">
							<li><a class="text-center" style="font-size: 13px;" href="orderhistory.php"> Order History </a></li>
							<li><a class="text-center" style="font-size: 13px;" href="editclient.php"> Edit your details </a></li>
							<li><a class="text-center" style="font-size: 13px;" href="signout.php"> Sign Out </a></li>
						</ul>
					</li>';
	}
} else {
	echo '<script type="text/javascript">
				location.replace("index.php");
			  </script>';
	//header("location: index.php"); 
	exit();
}
?>

<?php 

$output = "";
// Check to see the clientid variable is set and that it exists in the database
if (isset($_SESSION["clientid"])) {
	// Connect to the MySQL database  
    include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/connect_to_mysql.php";
	$id = preg_replace('#[^0-9]#i', '', $_SESSION["clientid"]); 
	/* Use this var to check to see if this ID exists, if yes then get the client order 
	 details, if no then exit this script and give message why*/
	
	$sql = "SELECT * FROM orders WHERE C_id='$id' ORDER BY O_id DESC";
	$result = mysqli_query($db_conx, $sql);
	
	$rowcount=mysqli_num_rows($result);
	
    if ($rowcount > 0) {
		// get all the order details
		while($row = mysqli_fetch_assoc($result)){
			
			
			$id = $row['O_id'];
			$Total = $row['Total'];
			$C_id = $row['C_id'];
            $Date = $row['Date'];
			$Status = $row['Status'];
            
			$date_added = strftime("%b %d, %Y", strtotime($row["Date"]));
            
            $output .= '<tr class="order_tr" style="margin-top: 10px">
                        <td class="pull-left order_td_detail">
                        <p>Ordered: '.$date_added.'</p>
                        <p>Order Number: '.$id.'</p>
                        <p>Total: Rs. '.$Total.'</p>
                        </td>
                        <td class="pull-right order_td_button"><a class="btn btn-primary" href="Client_Orderdetail.php?Oid='.$id.'"> View Details </a></td>
                    </tr>';
			
			
			
         }
		 
	} else {
        echo "No Orders Has been placed yet!";
	    exit();
	}
		
} else {
	echo '<script type="text/javascript">
				location.replace("index.php");
			  </script>';
	//header("location: index.php");
	exit();
}
mysqli_close($db_conx);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> Order History </title>

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
                <ol class="breadcrumb" >
                    <li>
                        <i class="fa fa-home"></i>  <a href="index.php"> Home </a>
                    </li>
                    <li class="active">
                        Orders
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <div class="row" style="margin-top:3%">
            <div class="col-xs-offset-1 col-xs-10 col-xs-offset-1">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tbody>
                            <?php print("$output"); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- /.row -->
        
    </div><!-- /.container -->
    
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