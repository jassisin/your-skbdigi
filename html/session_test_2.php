<?php
// Start the session
session_start();

echo "<h1>Session Test - Page 2</h1>";
echo "<p>This page checks if the session variables from Page 1 were successfully carried over.</p>";


// Check if session is active
if (session_status() == PHP_SESSION_ACTIVE) {
    echo "<p style='color:green;'>Session is active.</p>";
} else {
    echo "<p style='color:red;'>Session is NOT active.</p>";
}

echo "<p><b>Session ID:</b> " . session_id() . "</p>";
echo "<p><b>Session variables received:</b></p>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

if (isset($_SESSION['test_variable'])) {
    echo "<p style='color:green;'><b>Success:</b> Successfully retrieved 'test_variable': '" . $_SESSION['test_variable'] . "'</p>";
} else {
    echo "<p style='color:red;'><b>Failure:</b> Could not retrieve 'test_variable'.</p>";
}

if (isset($_SESSION['main_admin'])) {
    echo "<p style='color:green;'><b>Success:</b> Successfully retrieved 'main_admin': '" . $_SESSION['main_admin'] . "'</p>";
} else {
    echo "<p style='color:red;'><b>Failure:</b> Could not retrieve 'main_admin'. This is likely the reason you are being redirected from other pages.</p>";
}

echo "<hr>";
echo "<p>If the Session ID is different on this page than on Page 1, or if the session variables are empty, it means sessions are not configured correctly on your server.</p>";
?>
