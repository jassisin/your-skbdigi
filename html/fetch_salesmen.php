<?php
include('connection.php');

$query = "SELECT DISTINCT salesman FROM actions WHERE active=0";
$result = $conn->query($query);

$options = "";
while ($row = $result->fetch_assoc()) {
    $options .= "<option value='{$row['salesman']}'>{$row['salesman']}</option>";
}

echo $options;
$conn->close();
?>
