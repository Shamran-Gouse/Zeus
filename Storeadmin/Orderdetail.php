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

$output = "";
$outputimage = "";

// Gather this product's full information for inserting automatically into the edit form below on page
if (isset($_GET['Oid'])) {
	$targetID = $_GET['Oid'];
	
    $sql = "SELECT * FROM `orders` JOIN cart JOIN products ON cart.O_id = orders.O_id AND products.id = cart.P_id WHERE orders.O_id='$targetID'";
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
            $outputimage .='<img src="../img/paid.png" width="135" height="100">';
        } else{
            $outputimage .='<form action="Orderdetail.php" method="post" style="margin-right: 20px;">
                                        <input name="O_id" type="hidden" value="'.$O_id.'">
                                        <button class="btn btn-primary no-print"> Mark as paid</button>
                                    </form>';
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
if (isset($_POST['O_id'])) {
	
	$this_Oid = $_POST['O_id'];
	
    $status = "1";
    $sql = "UPDATE orders SET Status='$status' WHERE O_id='$this_Oid'";
    if($result = mysqli_query($db_conx, $sql))
    {
		echo '<script type="text/javascript">
				location.replace("AllOrders.php");
			  </script>';
        //header("location: AllOrders.php");
        exit();
    }

    echo "sssssssssss";
    exit();

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> Zeus - Clients details </title>

    <?php include('Adminstyle.php'); ?>
	
</head>

<body>

    <div id="wrapper">
        <div id="page-wrapper">
		
			<?php include('admin_navigation.php'); ?>
			
            <div class="container-fluid">
                <!-- Main content -->
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
                    <div class="row">
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
							<br/>
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
                
                
                <div class="row no-print">
                    <div class="col-xs-12">
                        <div class="pull-right">
                            <button class="btn btn-primary" onclick="window.print();"><i class="fa fa-print"></i> Print</button>
                        </div>
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
