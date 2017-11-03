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
	$signinbtn .='<a href="signin.php" class="btn btn-xl">SIGN IN / SIGN UP</a>';
}
?>

<?php

	$output = "";
	include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/connect_to_mysql.php";
	
	
	$query = "SELECT * FROM products ORDER BY date_added DESC LIMIT 8";
	$result = mysqli_query($db_conx, $query);

	if(mysqli_num_rows($result) == 0){ // evaluate the count
		 $output = '<div class="noproducts"> No Products to Display</div>';
	}
	else
	{
		while($row = mysqli_fetch_assoc($result)){
			
			$id = $row['id'];
			$product_name = $row['product_name'];
			//$quantity = $row['quantity'];
			//$price = $row['price'];
			$details = $row['details'];
			$date_added = strftime("%b %d, %Y", strtotime($row["date_added"]));
			
			
			$output .='<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
				<div class="hovereffect">
					<img class="img-responsive center-block" src="Product_images/'.$id.'.jpg" alt="$'.$product_name.'">
					<div class="overlay">
					   <h2> '.$product_name.'</h2>
					   <a class="info" href="productdetails.php?id='.$id.'"> details </a>
					</div>
				</div>
				</div>';
		}
	}

?>

<?php 
include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/connect_to_mysql.php";
if(isset($_POST['name'])){
	
	$name = $_POST['name'];
	$subject = $_POST['subject'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$message = $_POST['message'];
	

	// Add this message into the database
	$sql = "INSERT INTO contact_message (name, subject, email, phone, message, date, status) VALUES ('$name','$subject','$email','$phone','$message',now(), '0')" or die (mysql_error());
	 
	$result = mysqli_query($db_conx, $sql);
	 
	/*echo "<script> alert('Message added Successfully') </script>";*/
	
	echo '<script type="text/javascript">
				location.replace("contact_thanks.php");
			  </script>';
	//header("location: contact_thanks.php"); 
	exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> Zeus Onlinestore </title>

    <?php include('stylelink_Client.php'); ?>
	
	<style>
		a.back-to-top {
	display: none;
	width: 60px;
	height: 60px;
	text-indent: -9999px;
	position: fixed;
	z-index: 999;
	right: 20px;
	bottom: 20px;
	background: #fed136 url("img/up-arrow.png") no-repeat center 43%;
	-webkit-border-radius: 30px;
	-moz-border-radius: 30px;
	border-radius: 30px;
}
	</style>
	

	<script>
	
		var amountScrolled = 300;

		$(window).scroll(function() {
			if ( $(window).scrollTop() > amountScrolled ) {
				$('a.back-to-top').fadeIn('slow');
			} else {
				$('a.back-to-top').fadeOut('slow');
			}
		});


		$('a.back-to-top').click(function() {
			$('html, body').animate({
				scrollTop: 0
			}, 700);
			return false;
		});

	</script>
	
</head>

<body id="page-top">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand page-scroll" href="#page-top"> Zeus Pvt Ltd</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#Products">Products</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#about">About</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#contact">Contact</a>
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

    <!-- Header -->
    <header>
        <div class="container">
            <div class="intro-text">
                <div class="intro-lead-in" style="background: #000000; padding: 5px;">Welcome To Zeus!</div>
                <div class="intro-heading" style="background: #000000">It's Nice To Meet You</div>
				<?php echo $signinbtn; ?>
            </div>
        </div>
    </header>
	
	
	<!--Back-to-top-->
	<a href="#" class="back-to-top"></a>

    <!-- Products Grid Section -->
    <section id="Products" class="bg-light-gray">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 page-header text-center">
                    <h2 class="section-heading">Feature Products</h2>
                </div>
            </div>
			
			<div class="row">
				<?php print("$output"); ?>
			</div>
			
			<div class="row">
				<div class="text-center" style="margin-top:4%; margin-bottom:3%;">
					<a href="products.php" class="btn btn-xl">View All</a>
				</div>
			</div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">About US</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h3 class="section-subheading">Zeus.com, is brought to you by Zeus Pvt Ltd – is a professionally managed "POWER" brand distributor, supplying Nutrion supplements to patients who are suffering from Diabetes in Sri Lanka. At Zeus.com, Clients can buy Nutrion supplements from any corner of the country - with just a few clicks of the mouse.</h3>
                </div>
            </div>
        </div>
    </section>


    <!-- Contact Section -->
    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center" style="margin-bottom:75px;">
                    <h2 class="section-heading">Contact Us</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <form action="index.php" method="post" id="contactForm" novalidate>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Your Name *" id="name" name="name" required data-validation-required-message="Please enter your name.">
                                    <p class="help-block text-danger"></p>
                                </div>
								
								
								<div class="form-group">
                                    <input type="text" class="form-control" placeholder="Subject *" id="subject" name="subject" required data-validation-required-message="Please enter subject.">
                                    <p class="help-block text-danger"></p>
                                </div>
								
								
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Your Email *" id="email" name="email" required data-validation-required-message="Please enter your email address.">
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <input type="tel" class="form-control" placeholder="Your Phone *" id="phone" name="phone" required data-validation-required-message="Please enter your phone number.">
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Your Message *" id="message" name="message" required data-validation-required-message="Please enter a message."></textarea>
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 text-center">
                                <div id="success"></div>
                                <button type="submit" class="btn btn-xl">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer>
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

    <!-- Plugin JavaScript -->
    <script src="js/classie.js"></script>
    <script src="js/cbpAnimatedHeader.js"></script>

	
	<!-- Contact Form JavaScript -->
    <script src="js/jqBootstrapValidation.js"></script>
    <script>
	$(function() {

    $("#contactForm input,#contactForm textarea").jqBootstrapValidation({
        
        submitError: function($form, event, errors) {
            // additional error messages or events
        },
    });

    $("a[data-toggle=\"tab\"]").click(function(e) {
        e.preventDefault();
        $(this).tab("show");
    });
});


/*When clicking on Full hide fail/success boxes */
$('#name').focus(function() {
    $('#success').html('');
});

	</script>

    <!-- Custom Theme JavaScript -->
    <script src="js/custom.js"></script>

</body>

</html>
