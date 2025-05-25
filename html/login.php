<?php
session_start(); // Starting Session

include('connection.php');
$connection = new createConnection(); // Create a new object
$connection_ref = $connection->connectToDatabase(); // Connect to the database

$timezone = new DateTimeZone("Asia/Kolkata");
$date = new DateTime();
$date->setTimezone($timezone);
$date = $date->format('Y-m-d H:i:s');

if (isset($_POST['submit'])) {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        // Username or Password is invalid
        header("Location: index.php"); // Redirecting to the login page
    } else {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Protect against SQL injection
        $username = stripslashes($username);
        $password = stripslashes($password);
        $username = mysqli_real_escape_string($connection_ref, $username);
        $password = mysqli_real_escape_string($connection_ref, $password);

        // Prepare the SQL query using prepared statements
        $stmt = $connection_ref->prepare("SELECT * FROM admin_log WHERE username=? AND password=? AND ac_tive=0");
        if ($stmt === false) {
            die('prepare() failed: ' . htmlspecialchars($connection_ref->error));
        }
        $stmt->bind_param("ss", $username, $password); // 'ss' denotes two string parameters
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->num_rows;
        $row2 = $result->fetch_assoc();
        $role = $row2['role'];

        if ($rows == 1) {
            $_SESSION['main_admin'] = $username; // Initialize Session
            $_SESSION['role'] = $role;
            // Update the log_date
            $stmt_update = $connection_ref->prepare("UPDATE admin_log SET log_date=? WHERE username=?");
            if ($stmt_update === false) {
                die('prepare() failed: ' . htmlspecialchars($connection_ref->error));
            }
            $stmt_update->bind_param("ss", $date, $username);
            $stmt_update->execute();

            if ($role == 'owner_admin') {
                echo '<script> window.location.href = "admin-details.php"; </script>';
                exit();
            } elseif($role == 'executive') {
                echo '<script> window.location.href = "quick-address.php"; </script>';
                exit();
            }
            else{
                echo '<script> window.location.href = "dashboard"; </script>';
                exit();
            }
        } else {
            // Username or Password is invalid
            header("Location: index.php"); // Redirecting to the login page
            echo '<script> window.location.href = "index.php"; </script>';
        }

        // Close the prepared statement
        $stmt->close();
        $stmt_update->close();
    }
}
?>
