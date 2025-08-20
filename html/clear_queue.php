<?php
include 'connection.php';

// Clear all patients from tv_dashboard queue
$sql = "DELETE FROM tv_dashboard";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Queue cleared successfully']);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to clear queue']);
}

mysqli_close($conn);
?>
