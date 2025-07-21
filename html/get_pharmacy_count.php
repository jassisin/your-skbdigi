<?php
require 'connection.php';

$counts = [];

$sql = "SELECT next_visit_date, COUNT(*) as count FROM pharmacy_table WHERE next_visit_date IS NOT NULL GROUP BY next_visit_date";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $counts[$row['next_visit_date']] = (int)$row['count'];
}

header('Content-Type: application/json');
echo json_encode($counts);
?>