<?php
include 'connection.php';

echo "<h2>🔧 Fix Notification Database</h2>";

// Check if created_at column exists
$column_check = mysqli_query($conn, "SHOW COLUMNS FROM tv_dashboard LIKE 'created_at'");
$has_created_at = mysqli_num_rows($column_check) > 0;

if ($has_created_at) {
    echo "<p style='color: green;'>✅ Column 'created_at' already exists!</p>";
} else {
    echo "<p style='color: orange;'>⚠️ Column 'created_at' is missing. Adding it now...</p>";
    
    // Add the created_at column
    $sql = "ALTER TABLE `tv_dashboard` ADD COLUMN `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
    if (mysqli_query($conn, $sql)) {
        echo "<p style='color: green;'>✅ Successfully added 'created_at' column!</p>";
        
        // Update existing records
        $update_sql = "UPDATE `tv_dashboard` SET `created_at` = `created_date` WHERE `created_at` IS NULL";
        if (mysqli_query($conn, $update_sql)) {
            echo "<p style='color: green;'>✅ Updated existing records with created_at timestamps!</p>";
        } else {
            echo "<p style='color: red;'>❌ Error updating existing records: " . mysqli_error($conn) . "</p>";
        }
        
        // Add indexes
        $index_queries = [
            "CREATE INDEX `idx_tv_dashboard_created_at` ON `tv_dashboard`(`created_at`)",
            "CREATE INDEX `idx_tv_dashboard_status` ON `tv_dashboard`(`status`)",
            "CREATE INDEX `idx_tv_dashboard_status_created` ON `tv_dashboard`(`status`, `created_at`)"
        ];
        
        foreach ($index_queries as $index_sql) {
            if (mysqli_query($conn, $index_sql)) {
                echo "<p style='color: green;'>✅ Added index successfully</p>";
            } else {
                // Ignore index errors (might already exist)
                echo "<p style='color: orange;'>⚠️ Index might already exist (this is OK)</p>";
            }
        }
        
    } else {
        echo "<p style='color: red;'>❌ Error adding column: " . mysqli_error($conn) . "</p>";
    }
}

// Show updated table structure
echo "<h3>Updated Table Structure:</h3>";
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
}

// Test the notification system
echo "<h3>Test Notification System:</h3>";
echo "<p><a href='debug_notifications.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>🔍 Run Diagnostics Again</a></p>";
echo "<p><a href='test_notification_tool.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>🧪 Test Notifications</a></p>";

mysqli_close($conn);
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
table { border-collapse: collapse; }
th, td { padding: 8px; border: 1px solid #ddd; }
th { background: #f5f5f5; }
</style>
