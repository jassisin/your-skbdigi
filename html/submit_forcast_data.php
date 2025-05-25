<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('session.php');
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $vendor = $_POST['vendor'];
    $category = $_POST['category'];
    $staff =$_POST['staff'];
    $date = $_POST['date'];
    
    $particulars = $_POST['particulars'];
    $frequencies = $_POST['frequency'];
    $unitPrices = $_POST['unit_price'];
    $quantities = $_POST['quantity'];
    $totals = $_POST['total'];
    $followUpDates = $_POST['follow_up_date'];
    $remarks = $_POST['remarks'];

    // Insert each row into the database
    for ($i = 0; $i < count($particulars); $i++) {
        $particular = mysqli_real_escape_string($conn, $particulars[$i]);
        $frequency = mysqli_real_escape_string($conn, $frequencies[$i]);
        $unitPrice = mysqli_real_escape_string($conn, $unitPrices[$i]);
        $quantity = mysqli_real_escape_string($conn, $quantities[$i]);
        $total = mysqli_real_escape_string($conn, $totals[$i]);
        $followUpDate = mysqli_real_escape_string($conn, $followUpDates[$i]);
        $remark = mysqli_real_escape_string($conn, $remarks[$i]);

        // Insert into expense_forcast table
        $query = "INSERT INTO expense_forcast (particulars, frequency, unitprice, quantity, total, follow_up_date, remarks, vendor, category,staff, date)
                  VALUES ('$particular', '$frequency', '$unitPrice', '$quantity', '$total', '$followUpDate', '$remark', '$vendor', '$category','$staff', '$date')";
        
        if (!mysqli_query($conn, $query)) {
            echo "Error: " . mysqli_error($conn);
        }
    }

    // Redirect to a success page or display a success message
    header("Location: forcast_expense.php"); // Or another page
    exit();
}
?>
