<?php
include 'connection.php';

// Check current page_settings
$result = mysqli_query($conn, "SELECT * FROM page_settings WHERE id=1");
if ($row = mysqli_fetch_assoc($result)) {
    echo "Current logo_image value: " . htmlspecialchars($row['logo_image']) . "\n";
    echo "Full database record:\n";
    print_r($row);
} else {
    echo "No page_settings record found\n";
}

// Update the logo to a safe default
$update_sql = "UPDATE page_settings SET logo_image = 'logo.jpg' WHERE id = 1";
if (mysqli_query($conn, $update_sql)) {
    echo "\nLogo updated to logo.jpg successfully\n";
} else {
    echo "\nError updating logo: " . mysqli_error($conn) . "\n";
}

mysqli_close($conn);
?>
