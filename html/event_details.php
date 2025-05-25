<?php
// event_details.php

// Check if the 'event' parameter is set in the URL
if (isset($_GET['event'])) {
    $final_entry = urldecode($_GET['event']);
} else {
    // If no event is passed, display an error or default message
    $final_entry = "No event details available.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details</title>
</head>
<body>
    <h1>Event Details</h1>
    <p><strong>Event Information:</strong></p>
    <p><?php echo $final_entry; ?></p>
</body>
</html>
