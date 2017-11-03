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
$outputimage = "";
$Client_id = preg_replace('#[^0-9]#i', '', $_SESSION['clientid']); // filter everything but numbers and letters

// Gather this product's full information for inserting automatically into the edit form below on page
if (isset($_GET['Oid'])) {
	$targetID = $_GET['Oid'];
	
    $sql = "SELECT * FROM `orders` JOIN cart JOIN products ON cart.O_id = orders.O_id AND products.id = cart.P_id WHERE orders.O_id='$targetID' AND `C_id` = '$Client_id'";
	$result = mysqli_query($db_conx, $sql);
	
	if(mysqli_num_rows($result) > 0){ // evaluate the count
		
		while($row = mysqli_fetch_assoc($result)){
			
			/*order table*/
			$O_id = $row['O_id'];
			$Total = $row['Total'];
			$C_id = $row['C_id'];
			$Date = $row['Date'];
			$Status = $row['Status'];
			
			/*cart table*/
			$S_id = $row['S_id'];
			$P_id = $row['P_id'];
			$Quantity = $row['Quantity'];
			
			/*product table*/
			$product_name = $row['product_name'];
			$price = $row['price'];
			
			/* Subtotal */
			$subtotal = $Quantity * $price;
			
			$sqlin = "SELECT * FROM clients WHERE id='$C_id'";
			$resultin = mysqli_query($db_conx, $sqlin);
			
			if(mysqli_num_rows($resultin) > 0){
				
				while($rowin = mysqli_fetch_assoc($resultin)){
					
					/*client table*/
					$clientname = $rowin['clientname'];
					$company_hospital = $rowin['company_hospital'];
					$address = $rowin['address'];
					$phone = $rowin['phone'];
					$email = $rowin['email'];
				}
			}
			
            
			$output .= '<tr>
			<td>'.$Quantity.'</td>
			<td>'.$P_id.'</td>
			<td>'.$product_name.'</td>
			<td>'.$subtotal.'</td>
			</tr>';
		}
        
        if($Status == 1){
            $outputimage .='<br><img src="./img/paid.png" width="135" height="100">';
        } else{
            $outputimage .='<img src="./img/notpaid.png" width="140" height="130">';
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

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> Order Details </title>

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
                    
                    <li>
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
    
    <div class="row content_top">
        <div class="col-xs-offset-1 col-xs-10 col-xs-offset-1">
            <section class="content invoice" style="margin: 15px 20px 10px 0">                    
                <!-- title row -->
                <div class="row">
                    <div class="col-xs-12">
                        <h2 class="invoice-header">
                             Zeus, Pvt Ltd.
                            <small class="pull-right">Date: 2/10/2014</small>
                        </h2>                            
                    </div><!-- /.col -->
                </div>

                <!-- info row -->
                <div class="row" style="margin: 20px auto">
                    <div class="col-sm-4 invoice-col">
                        From
                        <address>
                            <strong>Zeus, Pvt Ltd.</strong><br>
                            No. 125 Galle Road, Colombo 4<br>
                            Sri Lanka<br>
                            Phone: 0348745232<br/>
                            Email: info@Zeus.com
                        </address>
                    </div><!-- /.col -->

                    <div class="col-sm-4 invoice-col">
                        To
                        <address>
                            <strong><?php echo $clientname ; ?></strong><br>
                            <?php echo $company_hospital ; ?><br>
                            <?php echo $address ; ?><br>
                            Phone: <?php echo $phone ; ?><br/>
                            Email: <?php echo $email ; ?>
                        </address>
                    </div><!-- /.col -->

                    <div class="col-sm-4 invoice-col">
                        <b>Order ID:</b> <?php echo $O_id; ?> <br/>
                        
                        <div class="row">
                            <div class="col-xs-12">
                                <?php print("$outputimage"); ?>
                            </div>
                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->

                <!-- Table row -->
                <div class="row" style="margin: 20px auto">
                    <div class="col-xs-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Qty</th>
                                    <th>Product Code</th>
                                    <th>Product</th>
                                    <th>Subtotal</th>
                                </tr>                                    
                            </thead>
                            <tbody>

                                <?php print("$output"); ?>

                            </tbody>
                        </table>                            
                    </div><!-- /.col -->
                </div><!-- /.row -->

                <div class="row">
                    <div style='font-size:18px; margin:12px;' align='right'> Total : <?php print("$Total"); ?> </div>
                </div><!-- /.row -->
            </section><!-- /.content -->
            
            <div class="row no-print" style="margin-top: 2%;">
                <div class="col-xs-12">
                    <div class="pull-right">
                        <button class="btn btn-primary" onclick="window.print();"><i class="fa fa-print"></i> Print</button>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    
    <footer class="no-print static-footer" style="margin-top: 4%;">
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