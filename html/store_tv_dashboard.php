
<?php
include 'connection.php';

$pid = $_POST['pid'] ?? '';
$patient_name = $_POST['patient_name'] ?? '';
$room = $_POST['room'] ?? '';
$status = $_POST['status'] ?? '';

// Log the received data for debugging
error_log("Received data: PID=$pid, Name=$patient_name, Room=$room, Status=$status");

if ($pid && $patient_name) {
    try {
        // Determine room based on status
        $room_assignment = '';
        switch(strtoupper($status)) {
            case 'NURSING_VITAL':
            case 'NURSING_CARE':
                $room_assignment = 'Room 2';
                break;
            case 'MEDICAL':
                $room_assignment = 'Room 4';
                break;
            case 'DENTAL':
                $room_assignment = 'Room 5';
                break;
            case 'PHARMACY':
                $room_assignment = 'Pharmacy';
                break;
            case 'RECEPTION_ENTRY':
            case 'RECEPTION_BILL':
                $room_assignment = 'Reception';
                break;
            default:
                $room_assignment = $room; // fallback to original room if status doesn't match
        }
        
        // Check if record with this PID already exists
        $checkStmt = $conn->prepare("SELECT id FROM tv_dashboard WHERE PID = ?");
        if (!$checkStmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $checkStmt->bind_param("s", $pid);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        
        if ($result->num_rows > 0) {
            // Record exists, update it
            $stmt = $conn->prepare("UPDATE tv_dashboard SET patient_name = ?, room = ?, status = ?, created_at = NOW(), created_date = NOW(), isAnnounced = 0 WHERE PID = ?");
            if (!$stmt) {
                throw new Exception("Update prepare failed: " . $conn->error);
            }
            $stmt->bind_param("ssss", $patient_name, $room_assignment, $status, $pid);
            $success = $stmt->execute();
            if (!$success) {
                throw new Exception("Update failed: " . $stmt->error);
            }
            error_log("Updated existing record for PID: $pid");
        } else {
            // Record doesn't exist, insert new one
            $stmt = $conn->prepare("INSERT INTO tv_dashboard (PID, patient_name, room, status, created_at, created_date, isAnnounced) VALUES (?, ?, ?, ?, NOW(), NOW(), 0)");
            if (!$stmt) {
                throw new Exception("Insert prepare failed: " . $conn->error);
            }
            $stmt->bind_param("ssss", $pid, $patient_name, $room_assignment, $status);
            $success = $stmt->execute();
            if (!$success) {
                throw new Exception("Insert failed: " . $stmt->error);
            }
            error_log("Inserted new record for PID: $pid");
        }
        
        $checkStmt->close();
        $stmt->close();
        echo json_encode(['success' => true, 'message' => 'Patient added to TV Dashboard successfully']);
    } catch (Exception $e) {
        error_log("Exception: " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    error_log("Missing required fields: PID=$pid, Name=$patient_name");
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
}
?>
