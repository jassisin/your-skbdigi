<?php
require('session.php');
include('connection.php');
if (!isset($_SESSION['main_admin'])) {
  header('Location: login.php');
  exit();
}

$page_heading = $_POST['page_heading'];
$page_heading_color = $_POST['page_heading_color'];
$footer_color = $_POST['footer_color'];
$logo_image = $_FILES['logo_image']['name'];

if ($logo_image) {
  $target_dir = "../assets/img/";
  $target_file = $target_dir . basename($logo_image);
  move_uploaded_file($_FILES['logo_image']['tmp_name'], $target_file);
  $logo_image_query = ", logo_image='$logo_image'";
} else {
  $logo_image_query = "";
}

$query = "UPDATE page_settings SET page_heading='$page_heading', page_heading_color='$page_heading_color', footer_color='$footer_color'$logo_image_query WHERE id=1";

if (mysqli_query($conn, $query)) {
  echo '<script> window.location.href = "superadmin.php"; </script>';
} else {
  echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
