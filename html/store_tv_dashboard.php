<?php
// filepath: c:\xampp\htdocs\your-skbdigi\html\store_tv_dashboard.php
require '../connection.php';

$pid = $_POST['pid'] ?? '';
$patient_name = $_POST['patient_name'] ?? '';
$room = $_POST['room'] ?? '';
$status = $_POST['status'] ?? '';

if ($pid && $patient_name && $status) {
    $stmt = $conn->prepare("INSERT INTO tv_dashboard (PID, patient_name, room, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $pid, $patient_name, $room, $status);
    $success = $stmt->execute();
    $stmt->close();

    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
}
?>