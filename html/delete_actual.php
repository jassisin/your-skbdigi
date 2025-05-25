<?php
require('session.php');
include('connection.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Delete the record from the database
    $delete_sql = "DELETE FROM actual_expense WHERE slno = $id";
    
    if (mysqli_query($conn, $delete_sql)) {
        // Redirect back to the expense forecast page after deletion
        header('Location: edit_actual_expense.php');
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
?>
