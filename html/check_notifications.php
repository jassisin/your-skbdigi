<?php
include 'connection.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the current page/department from the request
$current_department = $_GET['department'] ?? '';
$last_check = $_GET['last_check'] ?? date('Y-m-d H:i:s', strtotime('-1 minute'));

// Define department mappings
$department_mappings = [
    'reception' => ['RECEPTION_ENTRY', 'RECEPTION_BILL'],
    'nursing' => ['NURSING_VITAL', 'NURSING_CARE'],
    'medical' => ['MEDICAL'],
    'dental' => ['DENTAL'],
    'pharmacy' => ['PHARMACY'],
    'office' => ['OFFICE']
];

$notifications = [];

if (isset($department_mappings[$current_department])) {
    $statuses = $department_mappings[$current_department];
    $status_list = "'" . implode("','", $statuses) . "'";
    
    // First check if created_at column exists
    $column_check = mysqli_query($conn, "SHOW COLUMNS FROM tv_dashboard LIKE 'created_at'");
    $has_created_at = mysqli_num_rows($column_check) > 0;
    
    if ($has_created_at) {
        // Use created_at column for timestamp comparison
        $sql = "SELECT DISTINCT td.pid, td.patient_name, td.status, td.room, td.created_at 
                FROM tv_dashboard td 
                WHERE td.status IN ($status_list) 
                AND td.created_at > ? 
                ORDER BY td.created_at DESC";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 's', $last_check);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        while ($row = mysqli_fetch_assoc($result)) {
            $notifications[] = [
                'pid' => $row['pid'],
                'patient_name' => $row['patient_name'],
                'status' => $row['status'],
                'room' => $row['room'],
                'time' => $row['created_at'],
                'message' => "New patient: {$row['patient_name']} (PID: {$row['pid']}) assigned to {$row['status']}"
            ];
        }
        
        mysqli_stmt_close($stmt);
    } else {
        // Fallback: get recent entries without timestamp filtering
        // This will show all current patients in the status (for testing)
        $sql = "SELECT DISTINCT td.pid, td.patient_name, td.status, td.room, td.created_date 
                FROM tv_dashboard td 
                WHERE td.status IN ($status_list) 
                ORDER BY td.id DESC 
                LIMIT 5";
        
        $result = mysqli_query($conn, $sql);
        
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $notifications[] = [
                    'pid' => $row['pid'],
                    'patient_name' => $row['patient_name'],
                    'status' => $row['status'],
                    'room' => $row['room'],
                    'time' => $row['created_date'],
                    'message' => "Patient: {$row['patient_name']} (PID: {$row['pid']}) in {$row['status']} (No timestamp filtering)"
                ];
            }
        }
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'notifications' => $notifications,
    'count' => count($notifications),
    'last_check' => date('Y-m-d H:i:s')
]);

mysqli_close($conn);
?>
