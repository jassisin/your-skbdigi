<?php
include 'connection.php';

echo "<h2>Notification System Diagnostics</h2>";

// Check if tv_dashboard table exists and has required columns
echo "<h3>1. Database Table Structure Check</h3>";
$result = mysqli_query($conn, "DESCRIBE tv_dashboard");
if ($result) {
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>Column</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$row['Field']}</td>";
        echo "<td>{$row['Type']}</td>";
        echo "<td>{$row['Null']}</td>";
        echo "<td>{$row['Key']}</td>";
        echo "<td>{$row['Default']}</td>";
        echo "<td>{$row['Extra']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>❌ Error: tv_dashboard table not found or accessible</p>";
}

// Check current data in tv_dashboard
echo "<h3>2. Current TV Dashboard Data</h3>";
$result = mysqli_query($conn, "SELECT * FROM tv_dashboard ORDER BY id DESC LIMIT 5");
if ($result && mysqli_num_rows($result) > 0) {
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>ID</th><th>PID</th><th>Patient Name</th><th>Status</th><th>Room</th><th>Created Date</th><th>Created At</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['PID']}</td>";
        echo "<td>{$row['patient_name']}</td>";
        echo "<td>{$row['status']}</td>";
        echo "<td>{$row['room']}</td>";
        echo "<td>" . ($row['created_date'] ?? 'NULL') . "</td>";
        echo "<td>" . ($row['created_at'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: orange;'>⚠️ No data found in tv_dashboard table</p>";
}

// Test notification query for each department
echo "<h3>3. Test Notification Queries</h3>";
$departments = [
    'nursing' => ['NURSING_VITAL', 'NURSING_CARE'],
    'medical' => ['MEDICAL'],
    'dental' => ['DENTAL'],
    'pharmacy' => ['PHARMACY'],
    'reception' => ['RECEPTION_ENTRY', 'RECEPTION_BILL'],
    'office' => ['OFFICE']
];

$last_check = date('Y-m-d H:i:s', strtotime('-1 hour')); // Check last hour

foreach ($departments as $dept => $statuses) {
    echo "<h4>Department: " . strtoupper($dept) . "</h4>";
    $status_list = "'" . implode("','", $statuses) . "'";
    
    // Check if created_at column exists first
    $column_check = mysqli_query($conn, "SHOW COLUMNS FROM tv_dashboard LIKE 'created_at'");
    $has_created_at = mysqli_num_rows($column_check) > 0;
    
    if ($has_created_at) {
        // Use created_at for filtering
        $sql = "SELECT DISTINCT td.pid, td.patient_name, td.status, td.room, td.created_date, td.created_at
                FROM tv_dashboard td WHERE td.status IN ($status_list) 
                AND td.created_at > '$last_check' ORDER BY td.id DESC LIMIT 3";
    } else {
        // Fallback without created_at filtering
        $sql = "SELECT DISTINCT td.pid, td.patient_name, td.status, td.room, td.created_date
                FROM tv_dashboard td WHERE td.status IN ($status_list) 
                ORDER BY td.id DESC LIMIT 3";
    }
    
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        echo "<p style='color: green;'>✅ Found " . mysqli_num_rows($result) . " potential notifications" . ($has_created_at ? "" : " (no timestamp filtering)") . "</p>";
        echo "<ul>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li>{$row['patient_name']} (PID: {$row['pid']}) - Status: {$row['status']}";
            if (isset($row['created_at'])) {
                echo " - Created: {$row['created_at']}";
            } else {
                echo " - Created Date: {$row['created_date']}";
            }
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: orange;'>⚠️ No notifications found for $dept</p>";
    }
    
    if (!$has_created_at) {
        echo "<p style='color: red;'>❌ Missing 'created_at' column - notifications won't work properly!</p>";
        echo "<p><strong>Fix:</strong> Run this SQL: <code>ALTER TABLE tv_dashboard ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;</code></p>";
    }
}

// Check if notification files exist
echo "<h3>4. File Existence Check</h3>";
$files = [
    'js/notifications.js' => file_exists('js/notifications.js'),
    'check_notifications.php' => file_exists('check_notifications.php'),
    'store_tv_dashboard.php' => file_exists('store_tv_dashboard.php')
];

foreach ($files as $file => $exists) {
    if ($exists) {
        echo "<p style='color: green;'>✅ $file exists</p>";
    } else {
        echo "<p style='color: red;'>❌ $file missing</p>";
    }
}

// Test notification API endpoint
echo "<h3>5. API Test</h3>";
echo "<p>Test the notification API by clicking these links:</p>";
foreach (array_keys($departments) as $dept) {
    $test_url = "check_notifications.php?department=$dept&last_check=" . urlencode($last_check);
    echo "<p><a href='$test_url' target='_blank'>Test $dept notifications</a></p>";
}

mysqli_close($conn);
?>

<script>
// JavaScript diagnostic
console.log("=== Notification System JavaScript Diagnostics ===");

// Check if notification API is accessible
console.log("1. Browser Support Check:");
console.log("- Notifications supported:", 'Notification' in window);
console.log("- Current permission:", Notification.permission);
console.log("- Fetch API supported:", 'fetch' in window);

// Check if notification script is loaded
console.log("2. Script Loading Check:");
console.log("- DepartmentNotifications class:", typeof window.DepartmentNotifications);

// Test a simple notification
if (Notification.permission === 'granted') {
    console.log("3. Testing simple notification...");
    try {
        const testNotif = new Notification("Test Notification", {
            body: "This is a test notification from the diagnostic page",
            icon: '../assets/img/favicon.ico'
        });
        setTimeout(() => testNotif.close(), 3000);
        console.log("✅ Test notification sent successfully");
    } catch (error) {
        console.error("❌ Error sending test notification:", error);
    }
} else {
    console.log("⚠️ Notification permission not granted");
}

// Test fetch to notification API
console.log("4. Testing API connectivity...");
fetch('check_notifications.php?department=nursing&last_check=' + encodeURIComponent(new Date().toISOString().slice(0, 19).replace('T', ' ')))
    .then(response => {
        console.log("✅ API response status:", response.status);
        return response.json();
    })
    .then(data => {
        console.log("✅ API response data:", data);
    })
    .catch(error => {
        console.error("❌ API test failed:", error);
    });
</script>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
table { border-collapse: collapse; }
th, td { padding: 8px; border: 1px solid #ddd; }
th { background: #f5f5f5; }
h2, h3, h4 { color: #333; }
</style>
