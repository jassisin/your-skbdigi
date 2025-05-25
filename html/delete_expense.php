<?php
require('session.php');
include('connection.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $delete_sql = "DELETE FROM expense_forcast WHERE id = $id";

    if (mysqli_query($conn, $delete_sql)) {
        header('Location: edit_forcast_expense.php');
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
?>
