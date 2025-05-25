<?php
session_start();// Starting Session
if(!isset($_SESSION['login_admin']))
{
	header("location:index.php");
	echo '<script> window.location.href = "index.php"; </script>';
}
?>