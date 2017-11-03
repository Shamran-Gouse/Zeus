<?php
// start Session
session_start();

unset($_SESSION['clientid'],$_SESSION['clientemail'],$_SESSION['clientpassword']); //Delete the client cach

echo '<script type="text/javascript">
				location.replace("signin.php");
			  </script>';
//header('Location: signin.php'); // Redirect to admin_login.php
?>