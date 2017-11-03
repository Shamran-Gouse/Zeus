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

	$messageoutput = "";
	include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/connect_to_mysql.php";
	
	
	$query = "SELECT * FROM contact_message ORDER BY id DESC";
	$result = mysqli_query($db_conx, $query);

	if(mysqli_num_rows($result) == 0){ // evaluate the count if not ($result->num_rows == 0)
		echo '<script type="text/javascript">
				location.replace("404.php");
			  </script>';
		 //header("location: 404.php");
		 exit();
	}
	else
	{
		while($row = mysqli_fetch_assoc($result)){
			
			$id = $row['id'];
			$name = $row['name'];
			$subject = $row['subject'];
			$email = $row['email'];
			$phone = $row['phone'];
			$message = $row['message'];
			$date = $row['date'];
			$status = $row['status'];			
			
			if($status == 1){
				
				$messageoutput .= '<tr class="read">
			<td class="name"><a href="messagedetail.php?pid='.$id.'">'.$name.'</a></td>
			<td class="text-center"><a href="messagedetail.php?pid='.$id.'">'.$subject.'</a></td>
			<td class="time">'.$date.'</a></td>
			</tr>';
				
			} else {
				
				$messageoutput .= '<tr class="unread">
			<td class="name"><a href="messagedetail.php?pid='.$id.'">'.$name.'</a></td>
			<td class="text-center"><a href="messagedetail.php?pid='.$id.'">'.$subject.'</a></td>
			<td class="time">'.$date.'</a></td>
			</tr>';
			}
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
                                                    <li class="active"><a href="#"><i class="fa fa-inbox"></i> Inbox <?php echo '('.$messagecount.')'; ?></a></li>
                                                </ul>
                                            </div>
                                        </div><!-- /.col (LEFT) -->

                                        <div class="col-md-9 col-sm-8">
                                            <div class="table-responsive" style="padding-top: 10%; margin-bottom: 15px;">
                                                <!-- THE MESSAGES -->
                                                <table class="table table-mailbox">
       
													<?php print("$messageoutput"); ?>
							
                                                </table>
                                            </div><!-- /.table-responsive -->
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
