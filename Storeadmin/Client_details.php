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
if (isset($_GET['cid'])) {
	$targetID = $_GET['cid'];
	
    $sql = "SELECT * FROM clients WHERE id='$targetID' LIMIT 1";
	$result = mysqli_query($db_conx, $sql);
	
	if(mysqli_num_rows($result) > 0){ // evaluate the count
		
		while($row = mysqli_fetch_assoc($result)){
			
			$id = $row['id'];
			$clientname = $row['clientname'];
			$company_hospital = $row['company_hospital'];
			$email = $row['email'];
			$address = $row['address'];
			$phone = $row['phone'];
			$join_date = $row['signup_date'];
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

$output = "";
// Check to see the clientid variable is set and that it exists in the database
if (isset($_GET['cid'])) {
	// Connect to the MySQL database  
    include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/connect_to_mysql.php";
	$id = preg_replace('#[^0-9]#i', '', $_GET['cid']);
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
                        <td class="pull-right order_td_button"><a class="btn btn-primary" href="Orderdetail.php?Oid='.$id.'"> View Details </a></td>
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

    <title> Zeus - Clients details </title>

    <?php include('Adminstyle.php'); ?>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    
    <script type="text/javascript">
    $(document).ready(function(){
        $("#myTab a").click(function(e){
            e.preventDefault();
            $(this).tab('show');
        });
    });
    </script>
	
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
                            Clients Details
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="index.php">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-fw fa-users"></i> Clients Details
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
			     
                
                <div class="col-xs-offset-1 col-xs-10 col-xs-offset-1">

                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#details" aria-controls="details" role="tab" data-toggle="tab">details</a></li>
                    <li role="presentation"><a href="#orderdetail" aria-controls="orderdetail" role="tab" data-toggle="tab">orderdetail</a></li>
                  </ul>

                  <!-- Tab panes -->
                  <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="details">
                        <div class="row">
                            <div class="col-xs-offset-1 col-xs-10 col-xs-offset-1">
                                <div class="panel panel-default" style="margin:2% 0% 4% 0;">
                                    <div class="panel-heading">
                                        <strong> Clients Details </strong>
                                    </div> <!--END pannel heading-->

                                    <div class="panel-body" style="margin:3% 5% 0;  width:94%">
                                        <div class="row" style="padding-bottom: 15px;">
                                            <div class="col-md-5">
                                                <label for="Name"> Client ID : </label>
                                            </div>
                                            <div class="col-md-7">
                                                <label for="Name"> <?php print("$id"); ?> </label>
                                            </div>
                                        </div>

                                        <div class="row" style="padding-bottom: 15px;">
                                            <div class="col-md-5">
                                                <label for="Name"> Client Name : </label>
                                            </div>
                                            <div class="col-md-7">
                                                <label for="Name"> <?php print("$clientname"); ?> </label>
                                            </div>
                                        </div>

                                        <div class="row" style="padding-bottom: 15px;">
                                            <div class="col-md-5">
                                                <label for="Name"> Company / Hospital: </label>
                                            </div>
                                            <div class="col-md-7">
                                                <label for="Name"> <?php print("$company_hospital"); ?> </label>
                                            </div>
                                        </div>

                                        <div class="row" style="padding-bottom: 15px;">
                                            <div class="col-md-5">
                                                <label for="Name"> Email : </label>
                                            </div>
                                            <div class="col-md-7">
                                                <label for="Name"> <?php print("$email"); ?></label>
                                            </div>
                                        </div>

                                        <div class="row" style="padding-bottom: 15px;">
                                            <div class="col-md-5">
                                                <label for="Name"> Address : </label>
                                            </div>
                                            <div class="col-md-7">
                                                <label for="Name"> <?php print("$address"); ?> </label>
                                            </div>
                                        </div>

                                        <div class="row" style="padding-bottom: 15px;">
                                            <div class="col-md-5">
                                                <label for="Name"> Phone : </label>
                                            </div>
                                            <div class="col-md-7">
                                                <label for="Name"> <?php print("$phone"); ?> </label>
                                            </div>
                                        </div>

                                        <div class="row" style="padding-bottom: 15px;">
                                            <div class="col-md-5">
                                                <label for="Name"> Join Date : </label>
                                            </div>
                                            <div class="col-md-7">
                                                <label for="Name"> <?php print("$join_date"); ?> </label>
                                            </div>
                                        </div>
                                    </div> <!--END pannel body-->
                                </div> <!--END pannel-->
                            </div> <!--END column-->		
                        </div><!-- /.row -->
                    </div><!-- /.tab 1 -->
                      
                    <div role="tabpanel" class="tab-pane" id="orderdetail">
                         <div class="row" style="margin-top:3%;">
                            <div class="col-xs-offset-1 col-xs-10 col-xs-offset-1">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <tbody>							
                                            <?php print("$output"); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.row -->
                    </div>
                  </div>

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
