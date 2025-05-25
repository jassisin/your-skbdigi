<?php

include('connection.php');

$query = "SELECT DISTINCT customer FROM actions WHERE active=0";
$result = $conn->query($query);

$options = "";
while ($row = $result->fetch_assoc()) {
    $options .= "<option value='{$row['customer']}'>{$row['customer']}</option>";
}

echo $options;
$conn->close();
?>
