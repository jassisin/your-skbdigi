<?php
require('session.php');
include('connection.php');

if (!isset($_SESSION['main_admin'])) {
  header('Location: login.php');
  exit();
}

$action = $_POST['action'];
$header_name = $_POST['header_name'];

if ($action == 'edit') {
  $new_header_name = $_POST['new_header_name'];
  $query = "UPDATE actions_table SET name='$new_header_name' WHERE name='$header_name'";
} elseif ($action == 'delete') {
  $query = "DELETE FROM table_columns WHERE name='$header_name'";
}

if (mysqli_query($conn, $query)) {
  echo "Operation successful.";
} else {
  echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
header('Location: superadmin.php');
exit();
?>
