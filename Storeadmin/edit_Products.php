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
// Gather this product's full information for inserting automatically into the edit form below on page
if (isset($_GET['pid'])) {
	$targetID = $_GET['pid'];
	
    $sql = "SELECT * FROM products WHERE id='$targetID' LIMIT 1";
	$result = mysqli_query($db_conx, $sql);
	
	if(mysqli_num_rows($result) > 0){ // evaluate the count
		
		while($row = mysqli_fetch_assoc($result)){
			
			$id = $row['id'];
			$product_name = $row['product_name'];
			$quantity = $row['quantity'];
			$reorder = $row['reorder'];
			$price = $row['price'];
			$details = $row['details'];
			$date_added = $row['date_added'];
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
// Parse the form data and add inventory item to the system

if (isset($_POST['Name'])) {
	
	$pid = $_POST['thisID'];
	
    $product_name = $_POST['Name'];
	$quantity = $_POST['quantity'];
	$reorder = $_POST['reorder'];
	$price = $_POST['Price'];
	$details = $_POST['Details'];
	
	
	// See if that product name is an identical match to another product in the system
	$sql = "UPDATE products SET product_name='$product_name', quantity='$quantity', reorder='$reorder', price='$price', details='$details' WHERE id='$pid'";
	$result = mysqli_query($db_conx, $sql);
	
	if($_FILES['file']['tmp_name'] != "") {
		// Place image in the folder 
		$newname = "$pid.jpg";
		move_uploaded_file($_FILES['file']['tmp_name'], "../Product_images/$newname");
	}
	
	if($result){
		
		echo '<script type="text/javascript">
				location.replace("AllProducts.php");
			  </script>';
		//header("location: AllProducts.php"); 
		exit();
		
	} else {
		
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

    <title> Zeus - Edit Products </title>

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
                            Edit
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="index.php">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-fw fa-times"></i> Edit Products
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
			
			
			
				<div class="row">
					<div class="col-md-8">
						<div class="panel panel-default" style="margin:3% 0% 4% 10%;">
							<div class="panel-heading">
								<strong> Edit Products In Inventry </strong>
							</div> <!--END pannel heading-->
							<div class="panel-body" style="margin:3% 5% 4% 3%;  width:94%">
								<form action="edit_Products.php" method="post" enctype="multipart/form-data">							
									<div class="form-group">
										<div class="row">
											<div class="col-md-4">
												<label for="Name"> Product Name </label>
											</div>
											<div class="col-md-6">
												<input type="text" class="form-control" id="Name" name="Name" value="<?php echo $product_name; ?>" required>
											</div>
										</div>
									</div>
										
									<div class="form-group">
										<div class="row">
											<div class="col-md-4">
												<label for="quantity"> Product quantity </label>
											</div>
											<div class="col-md-6">
												<input type="text" class="form-control" id="quantity" name="quantity" value="<?php echo $quantity; ?>" required>
											</div>
										</div>
									</div>
									
									<div class="form-group">
										<div class="row">
											<div class="col-md-4">
												<label for="reorder"> Reorder quantity </label>
											</div>
											<div class="col-md-6">
												<input type="text" class="form-control" id="reorder" name="reorder" value="<?php echo $reorder; ?>" required>
											</div>
										</div>
									</div>
										
									<div class="form-group">
										<div class="row">
											<div class="col-md-4">
												<label for="Price"> Unit Price </label>
											</div>
											<div class="col-md-6">
												<input type="text" class="form-control" id="Price" name="Price" value="<?php echo $price; ?>" required>
											</div>
										</div>
									</div>
									
									<div class="form-group">
										<div class="row">
											<div class="col-md-4">
												<label for="Details"> Product Details </label>
											</div>
											<div class="col-md-6">
												<textarea rows="5" id="Details" class="form-control"  name="Details" required> <?php echo $details; ?> </textarea>
											</div>
										</div>
									</div>
										
										<div class="form-group" style="margin-top:30px;">
											<div class="row">
												<div class="col-md-4">
													<label for="Image"> Product Image </label>
												</div>
												<div class="col-md-6">
													<input type="file" name="file">
												</div>
											</div>
										</div>
										
										<div class="form-group">
										<br>
										<br>
											<div class="row">
												<div class="text-center">
													<input name="thisID" type="hidden" value="<?php echo $id; ?>">
													<input type="submit" class="btn btn-primary" name="button" id="button" value="Update Product" />
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
