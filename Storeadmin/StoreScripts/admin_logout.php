<?php
// start Session
session_start();

session_unset($_SESSION['id'],$_SESSION['username'],$_SESSION['password']); //Delete the username key
//unset($_SESSION['username']); //Delete id key
//unset($_SESSION['password']); //Delete password key

session_destroy(); //this would delete all off the session keys

echo '<script type="text/javascript">
				location.replace("/Storeadmin/admin_login.php");
			  </script>';

//header('Location: ../admin_login.php'); // Redirect to admin_login.php
?>