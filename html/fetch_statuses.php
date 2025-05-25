<?php
include('connection.php');

$query = "SELECT DISTINCT status FROM actions WHERE active=0";
$result = $conn->query($query);

$options = "";
while ($row = $result->fetch_assoc()) {
    $options .= "<option value='{$row['status']}'>{$row['status']}</option>";
}

echo $options;
$conn->close();
?>
