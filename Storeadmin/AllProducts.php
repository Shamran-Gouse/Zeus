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

	$output = "";
	include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/connect_to_mysql.php";
	
	
	$query = "SELECT * FROM products";
	$result = mysqli_query($db_conx, $query);

	if(mysqli_num_rows($result) == 0){ // evaluate the count if not ($result->num_rows == 0)
		 $output = '<div class="noproducts"> No Products to Display </div>';
	}
	else
	{
		while($row = mysqli_fetch_assoc($result)){
			
			$id = $row['id'];
			$product_name = $row['product_name'];
			$quantity = $row['quantity'];
			$reorder = $row['reorder'];
			$price = $row['price'];
			$details = $row['details'];
			$date_added = $row['date_added'];
            
            
            if($reorder < $quantity){
                $output .= '<tr>
                    <td>'.$id.'</td>
                    <td>'.$product_name.'</td>
                    <td>'.$quantity.'</td>
                    <td>'.$reorder.'</td>
                    <td>'.$price.'</td>
                    <td>'.$date_added.'</td>
                    <td>
                    <a href="edit_Products.php?pid='.$id.'" class="btn btn-xs btn-default"><span class="pull-right"><i class="fa fa-pencil-square-o"></i></span></a>
                    
                    
                    <button type="button" class="btn btn-xs btn-default" data-toggle="modal" data-target="#p'.$id.'"><span class="pull-right"><i class="fa fa-times"></i></button>
                    
                            <div id="p'.$id.'" class="modal fade" role="dialog">
                              <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Delete Parmanently</h4>
                                  </div>
                                  <div class="modal-body">
                                    <p>Do you really want to delete product with ID of '.$id.'?</p>
                                  </div>
                                  <div class="modal-footer">
                                    <a href="AllProducts.php?yesdelete='.$id.'" type="button" class="btn btn-danger" id="confirm">Delete</a>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                  </div>
                                </div>

                              </div>
                            </div>
                    
                    </td>
                    </tr>';
            } else {
                $output .= '<tr class="danger">
                    <td>'.$id.'</td>
                    <td>'.$product_name.'</td>
                    <td>'.$quantity.'</td>
                    <td>'.$reorder.'</td>
                    <td>'.$price.'</td>
                    <td>'.$date_added.'</td>
                    <td>
                    <a href="edit_Products.php?pid='.$id.'" class="btn btn-xs btn-default"><span class="pull-right"><i class="fa fa-pencil-square-o"></i></span></a>
                    
                    
                    
                    <button type="button" class="btn btn-xs btn-default" data-toggle="modal" data-target="#p'.$id.'"><span class="pull-right"><i class="fa fa-times"></i></button>
                    
                            <div id="p'.$id.'" class="modal fade" role="dialog">
                              <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Delete Parmanently</h4>
                                  </div>
                                  <div class="modal-body">
                                    <p>Do you really want to delete product with ID of '.$id.'?</p>
                                  </div>
                                  <div class="modal-footer">
                                    <a href="AllProducts.php?yesdelete='.$id.'" type="button" class="btn btn-danger" id="confirm">Delete</a>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                  </div>
                                </div>

                              </div>
                            </div>
                    
                    </td>
                    </tr>';
            }
			
			
		}
	}

?>

<?php

if(isset($_GET['deleteid']) && !empty($_GET['deleteid'])){
	$delete_id = $_GET['deleteid'];
	
	echo '
	<div class="modal-dialog" aria-hidden="true" style="padding:100px 0; text-align:center">
		<div class="modal-content">
			<div class="modal-header">
				<a href="AllProducts.php" type="button" class="close" >&times;</a>
				<h4 class="modal-title">Delete Parmanently</h4>
			</div>
			<div class="modal-body">
				<p>Do you really want to delete product with ID of '.$delete_id.'?</p>
			</div>
			<div class="modal-footer">
				<a href="AllProducts.php" type="button" class="btn btn-default" data-dismiss="modal">Cancel</a>
				<a href="AllProducts.php?yesdelete='.$delete_id.'" type="button" class="btn btn-danger" id="confirm">Delete</a>
			</div>
		</div>
	</div>';
}

if (isset($_GET['yesdelete'])) {// remove item from system and delete its picture

	// delete from database
	$id_to_delete = $_GET['yesdelete'];
	
	$sql = "DELETE FROM products WHERE id = '$id_to_delete'";
	mysqli_query($db_conx, $sql);
	
	
	
	// unlink (Remove) the image from server
    $pictodelete = ("../Product_images/$id_to_delete.jpg");
    if (file_exists($pictodelete)) {
       		    unlink($pictodelete);
    }
	
	echo '<script type="text/javascript">
				location.replace("AllProducts.php?success");
			  </script>';
	//header("location: AllProducts.php?success");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> Zeus - All Products </title>

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
                            All Products
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="index.php">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-fw fa-search"></i> All Products
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->					
					
				<div class="row">
					<div class="col-xs-offset-1 col-xs-10 col-xs-offset-1">
						<?php				
							if (isset($_GET['success'])==true) {
								echo '<div class="center">
										<div class="text-center">
											<div class="alert alert-success"">
												<strong> Successfully deleted.</strong>
											</div>
										</div>
									  </div>';
							}
						?>
					
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th>ID</th>
										<th>Product Name</th>
										<th> Quantity </th>
										<th> Reorder quantity </th>
										<th> Price </th>
										<th> Date Added </th>
										<th> Actions </th>
									</tr>
								</thead>
								<tbody>							
									
									
									<?php print("$output"); ?>
									
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!-- /.row -->
				
            </div>
            <!-- /.container-fluid -->

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