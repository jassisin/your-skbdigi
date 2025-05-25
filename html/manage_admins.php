<?php
require('session.php');
include('connection.php');
if (!isset($_SESSION['main_admin'])) {
  header('Location: login.php');
  exit();
}

$action = $_POST['action'];
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

if ($action == 'add') {
  $query = "INSERT INTO admin_log (username, password) VALUES ('$username', '$password')";
} elseif ($action == 'edit') {
  $query = "UPDATE admin_log SET password='$password' WHERE username='$username'";
} elseif ($action == 'delete') {
  $query = "DELETE FROM admin_log WHERE username='$username'";
}

if (mysqli_query($conn, $query)) {
  echo "Operation successful.";
} else {
  echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
