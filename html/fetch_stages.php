<?php
include('connection.php');

$query = "SELECT DISTINCT stage FROM actions WHERE active=0";
$result = $conn->query($query);

$options = "";
while ($row = $result->fetch_assoc()) {
    $options .= "<option value='{$row['stage']}'>{$row['stage']}</option>";
}

echo $options;
$conn->close();
?>
