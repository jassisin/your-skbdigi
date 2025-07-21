<?php
require 'connection.php';

$pid = $_POST['pid'] ?? '';
$status = $_POST['status'] ?? '';
$notes = $_POST['notes'] ?? '';
$next_visit_date = $_POST['next_visit_date'] ?? '';

$response = ['success' => false];

if ($pid) {
    // Always update nursing_table
    $stmt = $conn->prepare("UPDATE nursing_table SET status=?, notes=?, next_visit_date=? WHERE PID=?");
    $stmt->bind_param("ssss", $status, $notes, $next_visit_date, $pid);
    $nursing_updated = $stmt->execute();
    $stmt->close();

    // Always update reception table (only status and next_visit_date)
    $stmt2 = $conn->prepare("UPDATE reception SET status=?, next_visit_date=? WHERE PID=?");
    $stmt2->bind_param("sss", $status, $next_visit_date, $pid);
    $reception_updated = $stmt2->execute();
    $stmt2->close();

    // If status is not PHARMACY, update/insert in other department tables
    $status_upper = strtoupper($status);
    $target_table = null;
    if ($status_upper === 'DENTAL') {
        $target_table = 'dental_table';
    } elseif ($status_upper === 'MEDICAL') {
        $target_table = 'medical_table';
    } elseif ($status_upper === 'PHARMACY') {
        $target_table = 'pharmacy_table';
    }

    if ($target_table) {
        // Fetch data from reception for this PID
        $receptionQuery = "SELECT name, status, next_visit_date, notes, created_date FROM reception WHERE PID = ?";
        $receptionStmt = $conn->prepare($receptionQuery);
        $receptionStmt->bind_param("s", $pid);
        $receptionStmt->execute();
        $receptionResult = $receptionStmt->get_result();
        if ($receptionResult && $receptionResult->num_rows > 0) {
            $receptionRow = $receptionResult->fetch_assoc();
            $name = $receptionRow['name'];
            $dept_status = $receptionRow['status'];
            $dept_next_visit_date = $receptionRow['next_visit_date'];
            $dept_notes = $receptionRow['notes'];
            $created_date = $receptionRow['created_date'];
        } else {
            $name = '';
            $dept_status = $status;
            $dept_next_visit_date = $next_visit_date;
            $dept_notes = $notes;
            $created_date = date('Y-m-d H:i:s');
        }
        $receptionStmt->close();

        // Check if PID already exists in the target table
        $checkQuery = "SELECT id FROM $target_table WHERE PID = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("s", $pid);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result && $result->num_rows > 0) {
            // Update existing record
            $updateQuery = "UPDATE $target_table SET name=?, status=?, next_visit_date=?, notes=? WHERE PID=?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("sssss", $name, $dept_status, $dept_next_visit_date, $dept_notes, $pid);
            $updateStmt->execute();
            $updateStmt->close();
        } else {
            // Insert new record
            $insertQuery = "INSERT INTO $target_table (PID, name, status, next_visit_date, notes, created_date) VALUES (?, ?, ?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param("ssssss", $pid, $name, $dept_status, $dept_next_visit_date, $dept_notes, $created_date);
            $insertStmt->execute();
            $insertStmt->close();
        }
        $checkStmt->close();
    }

    if ($nursing_updated && $reception_updated) {
        $response['success'] = true;
    } else {
        $response['error'] = 'Update failed: ' . $conn->error;
    }
} else {
    $response['error'] = 'Missing PID';
}

echo json_encode($response);
?>