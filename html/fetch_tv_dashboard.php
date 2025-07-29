<?php
require 'connection.php';

$timestampColumn = 'created_date';
$checkColumns = mysqli_query($conn, "SHOW COLUMNS FROM tv_dashboard");
if ($checkColumns) {
    while ($col = mysqli_fetch_assoc($checkColumns)) {
        if ($col['Field'] == 'timestamp') {
            $timestampColumn = 'timestamp';
            break;
        } else if ($col['Field'] == 'created_date') {
            $timestampColumn = 'created_date';
            break;
        }
    }
}

$sql = "SELECT * FROM tv_dashboard ORDER BY $timestampColumn ASC";
$result = mysqli_query($conn, $sql);
$patients = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Room logic based on status
        $status = isset($row['status']) ? $row['status'] : '';
        switch ($status) {
            case 'NURSING_VITAL':
            case 'NURSING_CARE':
                $room = 'Room 2';
                break;
            case 'MEDICAL':
                $room = 'Room 4';
                break;
            case 'DENTAL':
                $room = 'Room 5';
                break;
            case 'PHARMACY':
                $room = 'Pharmacy';
                break;
            case 'RECEPTION':
            case 'RECEPTION_ENTRY':
            case 'RECEPTION_BILL':
                $room = 'Reception';
                break;
            default:
                $room = isset($row['room']) ? $row['room'] : '';
        }
        $patients[] = [
            'id' => $row['id'],
            'PID' => $row['PID'],
            'patient_name' => $row['patient_name'],
            'room' => $room,
            'status' => $status,
            'isAnnounced' => isset($row['isAnnounced']) ? $row['isAnnounced'] : 0
        ];
    }
}
header('Content-Type: application/json');
echo json_encode(['patients' => $patients]);
?>
