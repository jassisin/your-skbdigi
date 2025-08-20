<?php
include 'connection.php';

// Simple test to add a patient to tv_dashboard for testing notifications
if ($_POST['action'] === 'add_test_patient') {
    $pid = $_POST['pid'] ?? 'TEST' . time();
    $patient_name = $_POST['patient_name'] ?? 'Test Patient';
    $status = $_POST['status'] ?? 'NURSING_VITAL';
    $room = $_POST['room'] ?? 'Test Room';
    
    // Add or update patient in tv_dashboard
    $sql = "INSERT INTO tv_dashboard (pid, patient_name, status, room, created_date, created_at, isAnnounced) 
            VALUES (?, ?, ?, ?, NOW(), NOW(), 0)
            ON DUPLICATE KEY UPDATE 
            patient_name = VALUES(patient_name),
            status = VALUES(status), 
            room = VALUES(room),
            created_date = NOW(),
            created_at = NOW(),
            isAnnounced = 0";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssss', $pid, $patient_name, $status, $room);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => 'Test patient added successfully']);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Test Tool</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { 
            background: #f8f9fa; 
            padding: 20px; 
            margin: 20px 0; 
            border-radius: 8px; 
            border: 1px solid #dee2e6; 
        }
        .btn { 
            background: #007bff; 
            color: white; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            margin: 5px; 
        }
        .btn:hover { background: #0056b3; }
        .btn.success { background: #28a745; }
        .btn.danger { background: #dc3545; }
        .form-group { margin: 10px 0; }
        .form-group label { display: block; font-weight: bold; }
        .form-group input, .form-group select { 
            width: 200px; 
            padding: 5px; 
            margin: 5px 0; 
        }
        #status { 
            padding: 10px; 
            margin: 10px 0; 
            border-radius: 4px; 
            display: none; 
        }
        #status.success { 
            background: #d4edda; 
            color: #155724; 
            border: 1px solid #c3e6cb; 
        }
        #status.error { 
            background: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb; 
        }
    </style>
</head>
<body>
    <h1>🔔 Notification System Test Tool</h1>
    
    <div class="test-section">
        <h3>Quick Notification Test</h3>
        <p>This tool helps you test notifications by adding test patients to different departments.</p>
        
        <div id="status"></div>
        
        <h4>1. Grant Notification Permission First</h4>
        <button class="btn" onclick="requestPermission()">Request Notification Permission</button>
        <p id="permissionStatus">Permission: <span id="permission">Unknown</span></p>
        
        <h4>2. Add Test Patient to Department</h4>
        <div class="form-group">
            <label>Patient Name:</label>
            <input type="text" id="patientName" value="John Doe" />
        </div>
        
        <div class="form-group">
            <label>Patient ID:</label>
            <input type="text" id="patientId" value="" placeholder="Auto-generated" />
        </div>
        
        <div class="form-group">
            <label>Department/Status:</label>
            <select id="status">
                <option value="NURSING_VITAL">Nursing (Vital)</option>
                <option value="NURSING_CARE">Nursing (Care)</option>
                <option value="MEDICAL">Medical</option>
                <option value="DENTAL">Dental</option>
                <option value="PHARMACY">Pharmacy</option>
                <option value="RECEPTION_ENTRY">Reception (Entry)</option>
                <option value="RECEPTION_BILL">Reception (Bill)</option>
                <option value="OFFICE">Office</option>
            </select>
        </div>
        
        <button class="btn success" onclick="addTestPatient()">Add Test Patient</button>
        
        <h4>3. Start Notification System for Testing</h4>
        <div class="form-group">
            <label>Test Department:</label>
            <select id="testDepartment">
                <option value="">Select Department</option>
                <option value="nursing">Nursing</option>
                <option value="medical">Medical</option>
                <option value="dental">Dental</option>
                <option value="pharmacy">Pharmacy</option>
                <option value="reception">Reception</option>
                <option value="office">Office</option>
            </select>
        </div>
        
        <button class="btn" onclick="initNotifications()">Start Monitoring</button>
        <button class="btn danger" onclick="stopNotifications()">Stop Monitoring</button>
        
        <div id="monitoringStatus" style="margin-top: 10px;"></div>
    </div>
    
    <div class="test-section">
        <h3>Manual Notification Test</h3>
        <button class="btn" onclick="testDesktopNotification()">Test Desktop Notification</button>
        <button class="btn" onclick="testInPageNotification()">Test In-Page Notification</button>
    </div>

    <script src="js/notifications.js"></script>
    <script>
        let currentNotifications = null;
        
        function updateStatus(message, type = 'success') {
            const statusDiv = document.getElementById('status');
            statusDiv.style.display = 'block';
            statusDiv.className = type;
            statusDiv.textContent = message;
            setTimeout(() => {
                statusDiv.style.display = 'none';
            }, 5000);
        }
        
        function updatePermissionStatus() {
            const permission = Notification.permission;
            document.getElementById('permission').textContent = permission;
            document.getElementById('permissionStatus').style.color = 
                permission === 'granted' ? 'green' : 
                permission === 'denied' ? 'red' : 'orange';
        }
        
        async function requestPermission() {
            try {
                const permission = await Notification.requestPermission();
                updatePermissionStatus();
                updateStatus(`Permission ${permission}`, permission === 'granted' ? 'success' : 'error');
            } catch (error) {
                updateStatus('Error requesting permission: ' + error.message, 'error');
            }
        }
        
        async function addTestPatient() {
            const patientName = document.getElementById('patientName').value;
            const patientId = document.getElementById('patientId').value || 'TEST' + Date.now();
            const status = document.getElementById('status').value;
            
            try {
                const response = await fetch('test_notification_tool.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=add_test_patient&pid=${encodeURIComponent(patientId)}&patient_name=${encodeURIComponent(patientName)}&status=${encodeURIComponent(status)}&room=Test Room`
                });
                
                const data = await response.json();
                if (data.success) {
                    updateStatus(`✅ Added test patient: ${patientName} to ${status}`, 'success');
                } else {
                    updateStatus(`❌ Error: ${data.error}`, 'error');
                }
            } catch (error) {
                updateStatus(`❌ Error adding patient: ${error.message}`, 'error');
            }
        }
        
        function initNotifications() {
            const department = document.getElementById('testDepartment').value;
            if (!department) {
                updateStatus('Please select a department', 'error');
                return;
            }
            
            if (currentNotifications) {
                currentNotifications.destroy();
            }
            
            currentNotifications = new DepartmentNotifications(department);
            updateStatus(`🔔 Started monitoring ${department} notifications`, 'success');
            
            document.getElementById('monitoringStatus').innerHTML = 
                `<strong>Monitoring:</strong> ${department} | <strong>Check interval:</strong> 15 seconds`;
        }
        
        function stopNotifications() {
            if (currentNotifications) {
                currentNotifications.destroy();
                currentNotifications = null;
                updateStatus('🔕 Stopped monitoring notifications', 'success');
                document.getElementById('monitoringStatus').innerHTML = '';
            }
        }
        
        function testDesktopNotification() {
            if (Notification.permission === 'granted') {
                const notif = new Notification('Test Desktop Notification', {
                    body: 'This is a test desktop notification',
                    icon: '../assets/img/favicon.ico',
                    tag: 'test'
                });
                setTimeout(() => notif.close(), 5000);
                updateStatus('Desktop notification sent', 'success');
            } else {
                updateStatus('Please grant notification permission first', 'error');
            }
        }
        
        function testInPageNotification() {
            if (currentNotifications) {
                currentNotifications.showInPageNotification({
                    patient_name: 'Test Patient',
                    pid: 'TEST123',
                    status: 'TEST',
                    room: 'Test Room',
                    message: 'This is a test in-page notification'
                });
                updateStatus('In-page notification shown', 'success');
            } else {
                updateStatus('Please start notification monitoring first', 'error');
            }
        }
        
        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            updatePermissionStatus();
        });
    </script>
</body>
</html>
