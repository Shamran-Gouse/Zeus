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
//Database Connetion
include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/connect_to_mysql.php";
// This first query is just to get the total count of rows
$sql = "SELECT * FROM products";
$result = mysqli_query($db_conx, $sql);
$rowcount=mysqli_num_rows($result); //$row = mysqli_fetch_assoc($result); //mysqli_fetch_row($query)

/*while($row = mysqli_fetch_assoc($query)){
	$id = $row['id'];
}*/
//Here we have the total row count
$rows = $rowcount; //$id;
//This is the number of result we want to display per page
$page_rows = 8;
// This tells us the page number of our last page
$last = ceil($rows/$page_rows);
// This makes sure $last cannot be less than 1
if($last < 1){
	$last = 1;
}
// Establish the $pagenum variable
$pagenum = 1;
// Get pagenum from URL vars if it is present, else it is 1
if(isset($_GET['pn'])){
	$pagenum = preg_replace('#[^0-9]#', '', $_GET['pn']);
}
// This makes sure the page number isn't below 1, or more than last page
if ($pagenum < 1){
	$pagenum = 1;
} else if ($pagenum > $last){
	$pagenum = $last;
}
// This sets the range of rows to query for the chosen $pagenum
$limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;
// This is your query again, it is for grabing just one page worth of rows by applying $limit
$sql = "SELECT * FROM products ORDER BY id DESC $limit";
$query = mysqli_query($db_conx, $sql);
// This shows the user what page they are on, and the total number of pages
$textline1 = "Showing (<b>$rows</b>)";
$textline2 = "Page <b>$pagenum</b> of <b>$last</b>";
//Establish the $paginationCtrls variable
$paginationCtrls = '';
// If there is more than 1 page worth of results
if($last != 1){
	if($pagenum > 1){
		$previous = $pagenum - 1;
		$paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$previous.'"> &laquo; </a></li>';
		// Render clickable number links that should appear on the left of the target page number
		for($i = $pagenum-4; $i < $pagenum; $i++){
			if($i > 0){
				$paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a></li>';
			}
		}
	}
	// Render the target page number, but without it being a link
	$paginationCtrls .= '<li class="active"><a href="'.$_SERVER['PHP_SELF'].'?pn='.$pagenum.'">'.$pagenum.'</a></li>';
	// Render clickable number links that should appear on the right of the target page number
	for($i = $pagenum+1; $i <= $last; $i++){
		$paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a></li>';
		if($i >= $pagenum+4){
			break;
		}
	}
	// This does the same as above, only checking if we are on the last page, and then generating the "next"
	if($pagenum != $last){
		$next = $pagenum + 1;
		$paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$next.'"> &raquo; </a></li>';
	}
}

	$list = '';
	while($row = mysqli_fetch_assoc($query)){ //mysqli_fetch_array($query, MYSQLI_ASSOC)
		$id = $row['id'];
		$product_name = $row['product_name'];
		$quantity = $row['quantity'];
		$price = $row['price'];
		$details = $row['details'];
		$date_added = $row['date_added'];
		
		/*$list .='<p><a href="product.php?id='.$id.'">'.$product_name.' '.$quantity.' products</a> - Click the link to view this product <br>
		date added '.$date_added.'</p>';*/
		
		$list .='<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
				<div class="hovereffect">
					<img class="img-responsive center-block" src="Product_images/'.$id.'.jpg" alt="$'.$product_name.'">
					<div class="overlay">
					   <h2> '.$product_name.'</h2>
					   <a class="info" href="productdetails.php?id='.$id.'"> details </a>
					</div>
				</div>
				</div>';
	}

// Close your data base connection 
mysqli_close($db_conx);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> Products </title>

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

    <!-- Products Grid Section -->
        <div class="container">
		
			<div class="row content_top">
				<div class="col-xs-12">
					<ol class="breadcrumb">
						<li>
							<i class="fa fa-home"></i>  <a href="index.php"> Home </a>
						</li>
						<li>
							<i class="fa fa-fw fa-tags"></i>Products 
						</li>
					</ol>
				</div>
			</div>
		
			<div class="row">
				<div class="page-header text-center">
					<p style="font-size:17px"> <?php echo $textline1; ?> </p>
				</div>
			</div>
	
			<div class="row">
				<p><?php echo $textline2; ?></p>
				<p><?php echo $list; ?></p>
			</div>
		
			<div class="row">
				<div class="text-center">
					<ul class="pagination" style="margin-top: 8%">
						<?php echo $paginationCtrls; ?>
					</ul>
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
