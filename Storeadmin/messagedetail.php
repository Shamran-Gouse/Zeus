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

<?php 

	include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/connect_to_mysql.php";
	
	
	// Gather this product's full information for inserting automatically into the edit form below on page
if (isset($_GET['pid'])) {
	$targetID = $_GET['pid'];
	$read = "";
	
    $sql = "SELECT * FROM contact_message WHERE id='$targetID' LIMIT 1";
	$result = mysqli_query($db_conx, $sql);
	
	if(mysqli_num_rows($result) > 0){ // evaluate the count
		
		while($row = mysqli_fetch_assoc($result)){
			
			$id = $row['id'];
			$name = $row['name'];
			$subject = $row['subject'];
			$email = $row['email'];
			$phone = $row['phone'];
			$message = $row['message'];
			$date = $row['date'];
			$status = $row['status'];
			
		}
		
			if($status == 1){
				
				$read .= 'Mark as Unread';
				
			} else {
				
				$read .= 'Mark as Read';
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

// Changing as read
if (isset($_POST['thisID'])) {
	
	$pid = $_POST['thisID'];
	$oldstatus = $_POST['status'];
	
	if($oldstatus == 0){
		
		$status = "1";
		$sql = "UPDATE contact_message SET status='$status' WHERE id='$pid'";
		$result = mysqli_query($db_conx, $sql);
		
		echo '<script type="text/javascript">
				location.replace("mailbox.php");
			  </script>';
		//header("location: mailbox.php");
		exit();
		
	}else{
		
		$status = "0";
		$sql = "UPDATE contact_message SET status='$status' WHERE id='$pid'";
		$result = mysqli_query($db_conx, $sql);
		
		echo '<script type="text/javascript">
				location.replace("mailbox.php");
			  </script>';
		//header("location: mailbox.php");
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

    <title> Messages </title>

    
	<?php include('Adminstyle.php'); ?>

</head>

<body>

    <div id="wrapper">
		<?php include('admin_navigation.php'); ?>
		
        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- MAILBOX BEGIN -->
				<div class="mailbox row">
					<div class="col-xs-12">
						<div class="box box-solid" style="margin-top: 15px;">
							<div class="box-body">
								<div class="row">
									<div class="col-md-3 col-sm-4">
										<!-- BOXES are complex enough to move the .box-header around.
											 This is an example of having the box header within the box body -->
										<div class="box-header">
											<i class="fa fa-inbox"></i>
											<h3 class="box-title">INBOX</h3>
										</div>
										<!-- Navigation - folders-->
										<div style="margin-top: 15px;">
											<ul class="nav nav-pills nav-stacked">
												<li class="active"><a href="mailbox.php"><i class="fa fa-inbox"></i> Inbox <?php echo '('.$messagecount.')'; ?></a></li>
											</ul>
										</div>
									</div><!-- /.col (LEFT) -->

									<div class="col-md-9 col-sm-8">
									
										<!-- THE MESSAGES -->
										<div class="row" style="padding: 15px;">
												<div class="pull-right">
													<p><?php print("$date"); ?></p>
													<form action="messagedetail.php" method="post" style="margin-right: 20px;">
														<input name="thisID" type="hidden" value="<?php echo $targetID; ?>">
														<input name="status" type="hidden" value="<?php echo $status; ?>">
														<div class="clearfix"></div>
														<div class="pull-right">
															<button type="submit" class="btn btn-primary"> <?php echo $read; ?> </button>
														</div>
													</form>
												</div>
										</div>
										
										<div class="row">
											<table>
											  <tr>
												<td><strong style="padding-left: 15px;"><?php print("$name"); ?></strong>&nbsp; &laquo; <?php print("$email"); ?> &raquo; </td>
											  </tr>
											  <tr>
												<td><strong style="padding-left: 15px;"> Tel: </strong><?php print("$phone"); ?></td>
											  </tr>
											  <tr>
												<td><p style="padding-left: 15px;"><br><br><br><strong> Message: </strong></p></td>
											  </tr>
											  <tr>
												<td><p style="padding-left: 5%; padding-bottom: 8%; width: 95%; height: auto;"><?php print("$message"); ?></P></td>
											  </tr>
											</table>
										</div>
										<!-- /.THE MESSAGES -->
									</div><!-- /.col (RIGHT) -->
								</div><!-- /.row -->
							</div><!-- /.box-body -->
						</div><!-- /.box -->
					</div><!-- /.col (MAIN) -->
				</div>
				<!-- MAILBOX END -->
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
