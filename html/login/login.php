<?php
	session_start(); // Starting Session

include('connection.php');
$connection = new createConnection(); //created a new object
$connection_ref = $connection->connectToDatabase(); // $connection->selectDatabase();


$timezone = new DateTimeZone("Asia/Kolkata" );
$date = new DateTime();
$date->setTimezone($timezone );
$date=$date->format( 'Y-m-d H:i:s' );

	if (isset($_POST['submit'])) 
	{
		if (empty($_POST['username']) || empty($_POST['password'])) 
		{
			//Username or Password is invalid";
			header("Location: index.php"); // Redirecting To Other Page		
		}
		else
		{
			$username=$_POST['username'];
			$password=$_POST['password'];
			// Establishing Connection with Server by passing server_name, user_id and password as a parameter
			
//$connection = mysql_connect("localhost", "root", "");
			// To protect MySQL injection for Security purpose
			$username = stripslashes($username);
			$password = stripslashes($password);
			$username = mysqli_real_escape_string($connection_ref,$username);
			$password = md5(mysqli_real_escape_string($connection_ref,$password));
			// Selecting Database
			
//$db = mysql_select_db("db_s_vv", $connection);
			// SQL query to fetch information of registerd users and finds user match.
			$query = mysqli_query($connection_ref, "select * from admin_log where password='$password' AND username='$username' AND ac_tive=0")or die(mysql_error());
			$rows = mysqli_num_rows($query);
			// echo $rows;die();
			if ($rows == 1) 
			{
				$_SESSION['login_admin']=$username; // Initializing Session
				
					$sql ="UPDATE admin_log SET log_date='$date' WHERE username='$username' ";
                    if ($connection_ref->query($sql) === TRUE) {} 
				
				header("Location: home.php"); // Redirecting To Other Page
				echo '<script> window.location.href = "../home.php"; </script>';
			} 
			else 
			{
				//Username or Password is invalid";
				header("Location: index.php"); // Redirecting To Other Page		
				echo '<script> window.location.href = "index.php"; </script>';
			}
			mysql_close($connection); // Closing Connection
		}
	}
?>