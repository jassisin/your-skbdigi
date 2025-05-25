<?php
	session_start();
	unset($_SESSION['main_admin']);
	if(session_destroy()) // Destroying All Sessions
	{
		header("Location:index.php"); // Redirecting To Home Page
		echo '<script> window.location.href = "index.php"; </script>';
	
		}
?>