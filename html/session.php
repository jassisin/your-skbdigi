<?php
// Start the session at the very beginning of the script
session_start();

// Check if the session variable 'main_admin' is set
if (!isset($_SESSION['main_admin'])) {
    // If not set, redirect to the login page
    echo '<script> window.location.href = "dashboard"; </script>';
    exit();
}
?>
