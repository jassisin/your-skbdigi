<?php
// Start the session
session_start();

echo "<h1>Session Test - Page 1</h1>";
echo "<p>This page will start a session, set a test variable, and provide a link to a second page to check if the session persists.</p>";

// Check if session is active
if (session_status() == PHP_SESSION_ACTIVE) {
    echo "<p style='color:green;'>Session is active.</p>";
} else {
    echo "<p style='color:red;'>Session is NOT active.</p>";
}

// Set a session variable
$_SESSION['test_variable'] = 'Hello from Page 1';
$_SESSION['main_admin'] = 'test_user';

echo "<p><b>Session ID:</b> " . session_id() . "</p>";
echo "<p><b>Session variables set:</b></p>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo '<p><a href="session_test_2.php">Go to Page 2 to check the session</a></p>';
?>
