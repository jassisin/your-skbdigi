<?php
include 'connection.php';

// Fetch patients from tv_dashboard (queue)
$sql = "SELECT pid, patient_name, status, room FROM tv_dashboard ORDER BY id DESC LIMIT 10";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $status_class = '';
        switch(strtoupper($row['status'])) {
            case 'OFFICE':
                $status_class = 'bg-primary';
                break;
            case 'NURSING':
                $status_class = 'bg-success';
                break;
            case 'MEDICAL':
                $status_class = 'bg-info';
                break;
            case 'DENTAL':
                $status_class = 'bg-warning';
                break;
            case 'PHARMACY':
                $status_class = 'bg-danger';
                break;
            default:
                $status_class = 'bg-secondary';
        }
        
        echo '<div class="list-group-item d-flex justify-content-between align-items-center">';
        echo '<div>';
        echo '<strong>' . htmlspecialchars($row['patient_name']) . '</strong><br>';
        echo '<small class="text-muted">PID: ' . htmlspecialchars($row['pid']) . '</small>';
        if (!empty($row['room'])) {
            echo '<br><small class="text-muted">Room: ' . htmlspecialchars($row['room']) . '</small>';
        }
        echo '</div>';
        echo '<span class="badge ' . $status_class . '">' . htmlspecialchars($row['status']) . '</span>';
        echo '</div>';
    }
} else {
    echo '<div class="list-group-item text-center text-muted">';
    echo '<em>No patients in queue</em>';
    echo '</div>';
}

mysqli_close($conn);
?>
