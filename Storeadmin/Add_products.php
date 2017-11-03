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
		 echo "Your login session data is not on record in the database.";
		 exit();
	}
?>

<?php 

include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/connect_to_mysql.php";
if(isset($_POST['Name'])){
	
	$Name = mysqli_real_escape_string($db_conx,$_POST['Name']);
	$quantity = $_POST['quantity'];
	$reorder = $_POST['reorder'];
	$Price = $_POST['Price'];
	$Details = $_POST['Details'];
	
	// See if that product name is an identical match to another product in the system
	$sql = "SELECT id FROM products WHERE product_name='$Name' LIMIT 1";
	$result = mysqli_query($db_conx, $sql);
	
	$productMatch = mysqli_num_rows($result); // count the output amount
	
    if ($productMatch > 0) {
		
		echo '<script type="text/javascript">
				location.replace("Add_products.php?error");
			  </script>';
		//header("location: Add_products.php?error"); 
		exit();
	}

	// Add this product into the database now
	$sql = "INSERT INTO products (product_name, quantity, reorder, price, details, date_added) VALUES ('$Name','$quantity','$reorder','$Price','$Details',now())" or die (mysql_error());
    //$pid = mysqli_insert_id($db_conx);
	 
	$result = mysqli_query($db_conx, $sql);


	if (mysqli_errno($db_conx) === 0) {
	$last_insert_id = mysqli_insert_id($db_conx);
	}
	 

	// Place image in the folder 
	$newname = "$last_insert_id.jpg";
	move_uploaded_file($_FILES['file']['tmp_name'], "../Product_images/$newname");
	
	echo '<script type="text/javascript">
				location.replace("Add_products.php?success");
			  </script>';
	//header("location: Add_products.php?success"); 
	exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> Zeus - Add Products </title>

    <?php include('Adminstyle.php'); ?>
	
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
                            Add Product
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="index.php">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-fw fa-plus-square"></i> Add Product
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
			
			
			
				<div class="row">
					<div class="col-md-offset-2 col-md-8">
						<div class="panel panel-default" style="margin:4% 0% 5% 6%;">
							<div class="panel-heading">
								<strong> Add Products to Inventry </strong>
							</div> <!--END pannel heading-->
							<div class="panel-body" style="margin:3% 5% 4% 3%;  width:94%">
								<div id="Message">
									<?php
										
										if (isset($_GET['success'])==true) {
											echo '<div class="center">
													<div class="text-center">
														<div class="alert alert-success"">
															<strong> Successfully Added.</strong>
														</div>
													</div>
												  </div>';
										}
									
										if (isset($_GET['error'])==true) {
											echo '<div class="center" style="padding-top: 15px; padding-bottom: 15px;">
													<div class="text-center">
														<div class="alert alert-danger"">
															<strong> Sorry you tried to place a duplicate Product Name into the system.</strong>
														</div>
													</div>
												  </div>';
										}
									?>
								</div>
								<form action="Add_products.php" method="post" enctype="multipart/form-data">							
									<div class="form-group col-md-offset-1">
										<div class="row">
											<div class="col-md-4">
												<label for="Name"> Product Name </label>
											</div>
											<div class="col-md-7">
												<input type="text" class="form-control" id="Name" name="Name" required>
											</div>
										</div>
									</div>
										
									<div class="form-group col-md-offset-1">
										<div class="row">
											<div class="col-md-4">
												<label for="quantity"> Product quantity </label>
											</div>
											<div class="col-md-7">
												<input type="text" class="form-control" id="quantity" name="quantity" required>
											</div>
										</div>
									</div>
									
									<div class="form-group col-md-offset-1">
										<div class="row">
											<div class="col-md-4">
												<label for="reorder"> Reorder quantity </label>
											</div>
											<div class="col-md-7">
												<input type="text" class="form-control" id="reorder" name="reorder" required>
											</div>
										</div>
									</div>
									
									<div class="form-group col-md-offset-1">
										<div class="row">
											<div class="col-md-4">
												<label for="Price"> Unit Price </label>
											</div>
											<div class="col-md-7">
												<input type="text" class="form-control" id="Price" name="Price" required>
											</div>
										</div>
									</div>
										
									<div class="form-group col-md-offset-1">
										<div class="row">
											<div class="col-md-4">
												<label for="Details"> Product Details </label>
											</div>
											<div class="col-md-7">
												<textarea rows="5" id="Details" class="form-control"  name="Details" required></textarea>
											</div>
										</div>
									</div>
									
									<div class="form-group col-md-offset-1" style="margin-top:30px;">
										<div class="row">
											<div class="col-md-4">
												<label for="Image"> Product Image </label>
											</div>
											<div class="col-md-7">
												<input type="file" name="file" required>
											</div>
										</div>
									</div>
										
									<div class="form-group">
										<div class="row">
											<div class="col-md-offset-4 col-md-4" style="margin-top:5%">
												<button type="submit" class="btn btn-primary" id="submitted"> Add Product</button>
											</div>
										</div>
									</div>
										
								</form>
							</div> <!--END pannel body-->
						</div> <!--END pannel-->
					</div> <!--END column-->
								
								
								
								
								
								
								
								
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
