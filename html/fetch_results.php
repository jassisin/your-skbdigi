<?php
include('connection.php');
$customer_name = $_POST['customer_name'];
$status = $_POST['status'];
$stage = $_POST['stage'];
$salesman = $_POST['salesman'];

$query = "SELECT notes FROM actions WHERE 1=1";

if (!empty($customer_name)) {
    $query .= " AND customer = '$customer_name'";
}
if (!empty($status)) {
    $query .= " AND status = '$status'";
}
if (!empty($stage)) {
    $query .= " AND stage = '$stage'";
}
if (!empty($salesman)) {
    $query .= " AND salesman = '$salesman'";
}

$result = $conn->query($query);

$output = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= "<p>{$row['notes']}</p>";
    }
} else {
    $output = "<p>No results found.</p>";
}

echo $output;
$conn->close();
?>
