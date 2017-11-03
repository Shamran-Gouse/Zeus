<?php 

session_start();
// Script Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Connect to the MySQL database  
include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/connect_to_mysql.php";
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
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 1 (if user attempts to add something to the cart from the product page)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_POST['pid'])) {
    $pid = $_POST['pid'];
	$orderquantity = $_POST['orderquantity'];
	$wasFound = false;
	$i = 0;
	
	
	// See if the orderquantity is larger than the product in stock or not
	$sql = "SELECT quantity FROM products WHERE id='$pid' LIMIT 1";
	$result = mysqli_query($db_conx, $sql);
	
	while($row = mysqli_fetch_assoc($result)){
			
		$quantityinstock = $row['quantity'];
    }
	
	if ($orderquantity < 1) { $orderquantity = 1; }
	if ($orderquantity == "") { $orderquantity = 1; }
	
	
	if($quantityinstock>=$orderquantity){
			// If the cart session variable is not set or cart array is empty
		if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) { 
			// RUN IF THE CART IS EMPTY OR NOT SET
			$_SESSION["cart_array"] = array(0 => array("item_id" => $pid, "quantity" => $orderquantity));
		}
		else 
		{
			// RUN IF THE CART HAS AT LEAST ONE ITEM IN IT
			foreach ($_SESSION["cart_array"] as $each_item) {
			  $i++;
			  while (list($key, $value) = each($each_item)) {
					if ($key == "item_id" && $value == $pid) {
						$orderquantity = $each_item['quantity'] + $orderquantity;
						
						if($quantityinstock>=$orderquantity){
						// That item is in cart already so let's adjust its quantity using array_splice()
						array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $pid, "quantity" => $orderquantity)));
						$wasFound = true;
						} else {
							
							// That item is in cart already so let's adjust its quantity using array_splice()
							array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $pid, "quantity" => $quantityinstock)));
							$wasFound = true;
							
							$_SESSION["largequantity"] = $quantityinstock;
							$_SESSION["largequantityid"] = $pid;
							echo '<script type="text/javascript">
								location.replace("cart.php?error");
							  </script>';
						//header("location: cart.php?error");
							exit();
						}
					} // close if condition
			  } // close while loop
		   } // close foreach loop
		   
		   if ($wasFound == false) {
			   array_push($_SESSION["cart_array"], array("item_id" => $pid, "quantity" => $orderquantity));
		   }
		}
		
		echo '<script type="text/javascript">
				location.replace("cart.php");
			  </script>';
		
		//header("location: /home/shamrang/zeus.shamrangouse.com/cart.php");
		exit();
	}
	else 
	{
		
		// If the cart session variable is not set or cart array is empty
		if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) { 
			// RUN IF THE CART IS EMPTY OR NOT SET
			$_SESSION["cart_array"] = array(0 => array("item_id" => $pid, "quantity" => $quantityinstock));
			
		} 
		else 
		{
			foreach ($_SESSION["cart_array"] as $each_item) {
			  $i++;
			  while (list($key, $value) = each($each_item)) {
					if ($key == "item_id" && $value == $pid) {
						$orderquantity = $each_item['quantity'] + $orderquantity;
						
						if($quantityinstock>=$orderquantity){
						// That item is in cart already so let's adjust its quantity using array_splice()
						array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $pid, "quantity" => $orderquantity)));
						$wasFound = true;
						} else {
							
							// That item is in cart already so let's adjust its quantity using array_splice()
							array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $pid, "quantity" => $quantityinstock)));
							$wasFound = true;
							
							$_SESSION["largequantity"] = $quantityinstock;
							$_SESSION["largequantityid"] = $pid;
							echo '<script type="text/javascript">
								location.replace("cart.php?error");
							  </script>';
						//header("location: cart.php?error");
							exit();
						}
					} // close if condition
			  } // close while loop
		    } // close foreach loop
			
		}
		
		// That item is in cart already so let's adjust its quantity using array_splice()
		//array_push($_SESSION["cart_array"], array("item_id" => $pid, "quantity" => $quantityinstock));
		
		
		$_SESSION["largequantity"] = $quantityinstock;
		$_SESSION["largequantityid"] = $pid;
		echo '<script type="text/javascript">
				location.replace("cart.php?error");
			  </script>';
		//header("location: cart.php?error");
		exit();		
	}	
}
?>

<?php 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 2 (if user chooses to empty their shopping cart)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_GET['cmd']) && $_GET['cmd'] == "emptycart") {
    unset($_SESSION["cart_array"]);
}
?>

<?php 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 3 (if user chooses to adjust item quantity)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_POST['item_to_adjust']) && $_POST['item_to_adjust'] != "") {
    // execute some code
	$item_to_adjust = $_POST['item_to_adjust'];
	$quantity = $_POST['quantity'];
	$quantity = preg_replace('#[^0-9]#i', '', $quantity); // filter everything but numbers
	//if ($quantity >= 100) { $quantity = 99; }
	if ($quantity < 1) { $quantity = 1; }
	if ($quantity == "") { $quantity = 1; }
	$_SESSION["largequantity"] = "";
	$_SESSION["largequantityid"] = "";
	
	
	// See if the orderquantity is larger than the product in stock or not
	$sql = "SELECT quantity FROM products WHERE id='$item_to_adjust' LIMIT 1";
	$result = mysqli_query($db_conx, $sql);
	
	while($row = mysqli_fetch_assoc($result)){
			
		$quantityinstock = $row['quantity'];
    }
	
	if($quantityinstock>=$quantity){$i = 0;
		foreach ($_SESSION["cart_array"] as $each_item) { 
				  $i++;
				  while (list($key, $value) = each($each_item)) {
					  if ($key == "item_id" && $value == $item_to_adjust) {
						  // That item is in cart already so let's adjust its quantity using array_splice()
						  array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $item_to_adjust, "quantity" => $quantity)));
					  } // close if condition
				  } // close while loop
		} // close foreach loop
	} else {
		$_SESSION["largequantity"] = $quantityinstock;
		$_SESSION["largequantityid"] = $item_to_adjust;
			echo '<script type="text/javascript">
			location.replace("cart.php?error");
		  </script>';
		//header("location: cart.php?error"); 
		//echo 'You Cant Add more than ' . $quantityinstock . '&nbsp; <a href="cart.php"> click here</a>';
		exit();
		
	}
	
}
?>

<?php 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 4 (if user wants to remove an item from cart)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_POST['index_to_remove']) && $_POST['index_to_remove'] != "") {
    // Access the array and run code to remove that array index
 	$key_to_remove = $_POST['index_to_remove'];
	if (count($_SESSION["cart_array"]) <= 1) {
		unset($_SESSION["cart_array"]);
	} else {
		unset($_SESSION["cart_array"]["$key_to_remove"]);
		sort($_SESSION["cart_array"]);
	}
}
?>

<?php 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 5  (render the cart for the user to view on the page)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$cartOutput = "";
$cartTotal = "";
$product_id_array = '';
if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) {
	$cartOutput = '<div class="text-center" style="padding: 5%;">
						<div class="alert alert-danger"">
							<h2 align="center" >Your shopping cart is empty</h2>.
						</div>
					</div>';
} else {
	// Start the For Each loop
	$i = 0; 
    foreach ($_SESSION["cart_array"] as $each_item) {
		$item_id = $each_item['item_id'];
		//$sql = mysql_query("SELECT * FROM products WHERE id='$item_id' LIMIT 1");
		$sql = "SELECT * FROM products WHERE id='$item_id' LIMIT 1";
		$result = mysqli_query($db_conx, $sql);
		
		while ($row = mysqli_fetch_assoc($result)) { //$row = mysql_fetch_array($sql)
			$product_name = $row["product_name"];
			$price = $row["price"];
			$details = $row["details"];
		}
		$pricetotal = $price * $each_item['quantity'];
		$cartTotal = $pricetotal + $cartTotal;
		
		//$x = $i + 1;
		// Create the product array variable
		$product_id_array .= "$item_id-".$each_item['quantity'].","; 
		// Dynamic table row assembly
		$cartOutput .= '<tr>';
		$cartOutput .= '<td><img class="thumbnail" style="margin: 0 0 0 0;" src="Product_images/' . $item_id . '.jpg" alt="' . $product_name. '" width="110" height="110" /></td>';
		$cartOutput .= '<td><h4 style="margin: 7% 0 0 0;"><a href="productdetails.php?id=' . $item_id . '">' . $product_name . '</a></h4><p>Product Code: ' . $item_id . '</p></td>';
		$cartOutput .= '<td><p style="margin: 30% 0 0 0; color:#696763; font-size: 18px">Rs.' . $price . '</p></td>';
		$cartOutput .= '<td><form action="cart.php" method="post">
		<input style="margin: 16% 0 0 0;" name="quantity" type="text" value="' . $each_item['quantity'] . '" size="2" maxlength="5">
		<input class="btn btn-primary" name="adjustBtn' . $item_id . '" type="submit" value="change" /> 
		<input name="item_to_adjust" type="hidden" value="' . $item_id . '" />
		</form></td>';
		$cartOutput .= '<td><p style="margin: 30% 0 0 0;">' . $pricetotal . '</p></td>';
		$cartOutput .= '<td><form style="margin: 25% 0 0 5%;" action="cart.php" method="post"> <button name="deleteBtn' . $item_id . '" class="btn btn-primary" type="submit"><i class="fa fa-times"></i></button> <input name="index_to_remove" type="hidden" value="' . $i . '" /></form></td>';
		$cartOutput .= '</tr>';
		$i++; 
    }
	$shoppingTotal = $cartTotal;
	$cartTotal = "<div style='font-size:18px; margin-top:12px;' align='right'>Cart Total : Rs.".$cartTotal."</div>";
}
?>

<?php 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 6 (if user chooses to Checkout)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/connect_to_mysql.php";

if (isset($_GET['cart']) == "Checkout") {
	
	if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) {
		$cartOutput = "<h2 align='center'>Your shopping cart is empty</h2>";
	} 
	
	else {

		if (isset($_SESSION["clientemail"])) 
		{
			
			
			
			$m_id = preg_replace('#[^0-9]#i', '', $_SESSION['clientid']); // filter everything but numbers and letters
			
			$sql = "INSERT INTO orders (Total, C_id, Date) VALUES ('$shoppingTotal', '$m_id', CURRENT_TIMESTAMP)";
            
			if($resultinorder = mysqli_query($db_conx, $sql))
			{				
		
				if (mysqli_errno($db_conx) === 0) {
					$last_insert_id = mysqli_insert_id($db_conx);
					}
		
				// Start the For Each loop
				foreach ($_SESSION["cart_array"] as $each_item) 
				{
					
					$item_id = $each_item['item_id'];
					$quantity = "";
					
					$sql = "SELECT * FROM products WHERE id='$item_id' LIMIT 1";
					$result = mysqli_query($db_conx, $sql);
					
					while ($row = mysqli_fetch_assoc($result)) { 
						$quantity = $row["quantity"];
					}
					
					
					if($quantity < $each_item['quantity'])
					{
						
						$_SESSION["largequantity"] = $quantity;
						$_SESSION["largequantityid"] = $item_id;
						echo '<script type="text/javascript">
							location.replace("cart.php?error");
						  </script>';
						//header("location: cart.php?error"); 
						exit();
						
					} 
					else 
					{
						
						$order_quantity = $each_item['quantity'];
						$item_quantity = $quantity - $order_quantity;
						
						
						$sql = "INSERT INTO `cart` (`S_id`, `O_id`, `P_id`, `Quantity`) VALUES (NULL, '$last_insert_id', '$item_id', '$order_quantity')";
								 
						if($resultincart = mysqli_query($db_conx, $sql))
						{
							$sql = "UPDATE products SET quantity='$item_quantity' WHERE id='$item_id'";
							$resultup = mysqli_query($db_conx, $sql);
						}
					}
				}
				
				unset($_SESSION["cart_array"]);
				echo '<script type="text/javascript">
					location.replace("order_thanks.php");
				  </script>';
				//header("location: order_thanks.php");
				exit();
				
			}
			else
			{
				$cartOutput = "<h2 align='center'> SomeThing Went Wrong</h2>";
			}
					
			
		} 
		else 
		{
			echo '<script type="text/javascript">
					location.replace("signin.php?signin");
				  </script>';
			//header("location: signin.php?signin");
			exit();	
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
		
		<title> Your Cart </title>

		<?php include('stylelink_Client.php'); ?>
		
		<style>
		#tablerow {
			background: #337ab7;
			color: #fff;
			font-size: 16px;
			font-family: 'Roboto', sans-serif;
			font-weight: normal;
		}
		#tabledata {
			padding-top: 10px; padding-bottom: 10px;"
		}
		</style>
	
	</head>

	<body id="page-top">
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
                            <a class="page-scroll" href="products.php">Products</a>
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

                        <li class="active">
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
	
		<div class="container" >
		
			<div class="row content_top">
				<div class="col-xs-12">
					<ol class="breadcrumb">
						<li>
							<i class="fa fa-home"></i>  <a href="index.php"> Home </a>
						</li>
						<li class="active">
							<i class="fa fa-shopping-cart"></i> CART
						</li>
					</ol>
				</div>
			</div>
		
		
			<div class="table-responsive" style="margin-top: 1%;">
				<table class="table table-condensed">
					<thead>
						<tr id="tablerow">
							<td id="tabledata">Product Description</td>
							<td id="tabledata"> Product Description </td>
							<td id="tabledata">Unit Price</td>
							<td id="tabledata">Quantity</td>
							<td id="tabledata">Total</td>
							<td id="tabledata">Remove</td>
						</tr>
					</thead>
					<tbody>			
						
						<?php echo $cartOutput; ?>
						
					</tbody>
				</table>
				<div style="display: display:inline-block;">
					<div class="text-center">
						<?php
							if (isset($_GET['error'])==true) {
								echo '<div class="center">
										<div class="text-center">
											<div class="alert alert-danger"">
												 You Cant Add more than <strong>'.$_SESSION["largequantity"].'</strong> on <strong> Product Code: '.$_SESSION["largequantityid"].'</strong>
											</div>
										</div>
									  </div>';
							}
							if (isset($_GET['success'])==true) {
								echo '<div class="center">
										<div class="text-center">
											<div class="alert alert-success"">
												<strong> Successfully Added.</strong>
											</div>
										</div>
									  </div>';
							}
						?>
					</div>
					
					<div class="pull-right">
						<?php echo $cartTotal; ?>
					</div>
				</div>
			</div>
			
			<div style="display: display:inline-block; padding-top: 6%; padding-bottom: 10%;">
				<div class="col-md-3 text-center">
					<a href="products.php" class="btn btn-primary"> <i class="fa fa-arrow-circle-left"></i> &nbsp; Continue Shopping </a>
				</div>
				
				<div class="col-md-6 text-center">
					<a href="cart.php?cmd=emptycart" class="btn btn-primary" > Click Here to Empty Your Shopping Cart &nbsp; <i class="fa fa-times"></i></a>
				</div>
				<div class="col-md-3 text-center">					
					<a href="cart.php?cart=Checkout" class="btn btn-primary"> Check out &nbsp; <i class="fa fa-arrow-circle-right"></i> </a>
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