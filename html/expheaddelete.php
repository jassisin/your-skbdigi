<?php
include 'connection.php'; // Include database connection file

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the record from the database
    $sql = "DELETE FROM exphead WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $id); // Bind the id parameter
        if ($stmt->execute()) {
            // Redirect to the page where the table is displayed after successful deletion
            header('Location: expcategory.php');
            exit;
        } else {
            // Handle error
            echo "Error deleting record: " . $conn->error;
        }
    }
}
?>
