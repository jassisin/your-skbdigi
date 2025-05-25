<?php
require('session.php');
include('connection.php');
if (!isset($_SESSION['main_admin'])) {
  header('Location: login.php');
  exit();
}

$action = $_POST['action'];
$column_name = $_POST['column_name'];

if ($action == 'create') {
  // Code to create a new column
  $query = "ALTER TABLE your_table_name ADD $column_name VARCHAR(255)";
} elseif ($action == 'edit') {
  // Code to edit an existing column
  $new_column_name = $_POST['new_column_name'];
  $query = "ALTER TABLE your_table_name CHANGE $column_name $new_column_name VARCHAR(255)";
} elseif ($action == 'delete') {
  // Code to delete a column
  $query = "ALTER TABLE your_table_name DROP COLUMN $column_name";
}

if (mysqli_query($conn, $query)) {
  echo "Operation successful.";
} else {
  echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
