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

<?php 
// Check to see the URL variable is set and that it exists in the database
if (isset($_GET['id'])) {
	// Connect to the MySQL database  
    include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/connect_to_mysql.php";
	$id = preg_replace('#[^0-9]#i', '', $_GET['id']); 
	// Use this var to check to see if this ID exists, if yes then get the product 
	// details, if no then exit this script and give message why
	
	$sql = "SELECT * FROM products WHERE id='$id' LIMIT 1";
	$result = mysqli_query($db_conx, $sql);
	//$sql = mysql_query("SELECT * FROM products WHERE id='$id' LIMIT 1");
	$rowcount=mysqli_num_rows($result);
	//$productCount = mysql_num_rows($sql); // count the output amount
	
    if ($rowcount > 0) {
		// get all the product details
		while($row = mysqli_fetch_assoc($result)){
			
			
			$id = $row['id'];
			$product_name = $row['product_name'];
			
			
			$quantity = $row['quantity'];
			if($quantity <= 0){
				$quantity = '<p>Out Of Stock</p>';
			}
			
			
			$price = $row['price'];
			$details = $row['details'];
			$date_added = strftime("%b %d, %Y", strtotime($row["date_added"])); //$row['date_added'];
         }
		 
	} else {
		echo '<script type="text/javascript">
				location.replace("404.php");
			  </script>';
		//header("location: 404.php"); 
	    exit();
	}
		
} else {
	echo "Data to render this page is missing.";
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

    <title> Product Details </title>

    <?php include('stylelink_Client.php'); ?>
	
	<style>
#name {
    width: 100%;
	margin-left: auto;
	margin-right: auto;
	background: #fed136;
    height: auto;
	display: inline-block;
    line-height: 25px;
    color: #FFFFFF;
    padding: 10px;
	margin-bottom: 20px;
    font-size: 16px;
	margin-top: 10px;
    font-family: open_sanslight;
}


.row > h4 { display: inline-block; }

.row > span > p { 
	display: inline-block;
	color: #ff0000;
	margin-top: 10px;
    font-family: OpenSans-Regular;
    font-size: 20px;
    line-height: 30px;
}


#details { margin-left: 100px; height: auto;
	margin-top: 10px;
	padding: 10px;
    font-family: OpenSans-Regular;
    font-size: 20px;
    line-height: 30px; }


		</style>

	
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
						<li>
							<i class="fa fa-fw fa-tags"></i><a href="products.php"> Products </a>
						</li>
						<li class="active">
							Product Details
						</li>
					</ol>
				</div>
			</div>
		
			<div class="row" style="margin-top:6%">
				<div class="col-sm-4 text-center">
					<img src="Product_images/<?php echo $id; ?>.jpg" alt="<?php echo $product_name; ?>" />
				</div>
				<div class="col-sm-8">
					<div class="row text-center" id="name">
						<?php echo $product_name; ?>
					</div>
					
					<div class="col-md-6">
						<div class="row">
							<h4 class="item-intro"> Price : </h4>
							<span id="details"><?php echo "Rs. ".$price; ?></span>
						</div>
						
						<div class="row">
							<h4 class="item-intro"> In Stock : </h4>
							<span id="details"><?php echo $quantity;?></span>
						</div>
						
						<div class="row">
							<div class="form-group">
								<div style="margin-top: 10%">
									<form id="form1" name="form1" method="post" action="cart.php">
										<input type="hidden" name="pid" id="pid" value="<?php echo $id; ?>" />
										<div class="col-md-6 text-center">
											<input type="text" class="form-control" placeholder="Quantity" id="orderquantity" name="orderquantity" required>
										</div>
										<div class="col-md-6 text-center">
											<button class="btn btn-primary" type="submit" name="button" id="button"> <i class="fa fa-shopping-cart fa-lg"></i> &nbsp; Add to Cart </button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="row">
							<h4 class="item-intro"><label for="Name"> Details : </label></h4>
							<span><?php echo $details; ?></span>
						</div>
					</div>
				</div>
			</div>
        </div>

    
    <footer class="fixed-footer">
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
