<?php
    require 'session.php';
    include 'connection.php';

    // Handle search filters
    $search_by    = $_GET['search_by'] ?? '';
    $search_value = $_GET['search_value'] ?? '';
 
    $today = date('Y-m-d');
    if (! empty($search_by) && ! empty($search_value)) {
        $search_value = mysqli_real_escape_string($conn, $search_value);
        if ($search_by === "name") {
            $sql = "SELECT * FROM office WHERE name LIKE '%$search_value%'";
        } elseif ($search_by === "status") {
            $sql = "SELECT * FROM office WHERE status LIKE '%$search_value%'";
        } elseif ($search_by === "phone") {
            $sql = "SELECT * FROM office WHERE phone LIKE '%$search_value%'";
        } else {
          $sql = "SELECT * FROM office WHERE DATE(created_date) = '$today' OR next_visit_date = '$today'";
        }
    } else {
          $sql = "SELECT * FROM office WHERE DATE(created_date) = '$today' OR next_visit_date = '$today'";
    }

    $result = mysqli_query($conn, $sql);

    if (isset($_POST["delete"]) && isset($_POST["delete_id"])) {
        $delete_id = intval($_POST["delete_id"]);
        $del_sql   = "DELETE FROM office WHERE id = $delete_id";
        mysqli_query($conn, $del_sql);
        echo "<script>window.location.href='office.php';</script>";
        exit();
    }

    // Set page-specific variables
    $page_title         = "Office";
    $page_description   = "Office page description";
    $page_heading_color = "#6f42c1"; // Purple color
    $footer_color       = "#f8f9fa"; // Light gray

    // Set username
    if (isset($_SESSION['main_admin'])) {
        $username = $_SESSION['main_admin'];
    } else {
        $username = 'Guest';
    }

    // Set role for header.php (fallback to guest if not set)
    $role = 'guest';
    if (isset($_SESSION['role']) && !empty($_SESSION['role'])) {
        $role = $_SESSION['role'];
    }

    // Include header
    include 'header_section.php';
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-6 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Search Office</h4>
                        <form method="GET" action="office.php">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="search_by">Search By:</label>
                                        <select name="search_by" id="search_by" class="form-control">
                                            <option value="name" <?= isset($_GET['search_by']) && $_GET['search_by'] === 'name' ? 'selected' : '' ?>>Name</option>
                                            <option value="status" <?= isset($_GET['search_by']) && $_GET['search_by'] === 'status' ? 'selected' : '' ?>>Status</option>
                                            <option value="phone" <?= isset($_GET['search_by']) && $_GET['search_by'] === 'phone' ? 'selected' : '' ?>>Phone</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="search_value">Search Value:</label>
                                        <input type="text" name="search_value" id="search_value" class="form-control" 
                                               value="<?= htmlspecialchars($_GET['search_value'] ?? '') ?>" 
                                               placeholder="Enter search value">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="office.php" class="btn btn-secondary">Clear</a>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title">Patient Queue</h4>
                            <button id="clearQueue" class="btn btn-sm btn-warning">Clear Queue</button>
                        </div>
                        <div id="patientQueue" class="list-group">
                            <!-- Patient queue will be populated via AJAX -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Office List</h4>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Serial</th>
                                        <th>PID</th>
                                        <th>Name</th>
                                        <th>Age</th>
                                        <th>Status</th>
                                        <th>Phone</th>
                                        <th>Company</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial = 1;
                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            echo "<td>" . $serial++ . "</td>";
                                            echo "<td>" . htmlspecialchars($row['PID'] ?? '') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['age'] ?? '') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['phone'] ?? '') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['company'] ?? '') . "</td>";
                                            echo "<td>
                                                    <button class='btn btn-success btn-sm call-patient' 
                                                            data-name='" . htmlspecialchars($row['name']) . "' 
                                                            data-id='" . $row['id'] . "'
                                                            data-pid='" . htmlspecialchars($row['PID'] ?? '') . "'
                                                            data-type='OFFICE'>
                                                        Call
                                                    </button>
                                                    <a href='edit_patient.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm'>Edit</a>
                                                    <form method='post' style='display:inline-block;'>
                                                        <input type='hidden' name='delete_id' value='" . $row['id'] . "' />
                                                        <input type='submit' name='delete' value='Delete' class='btn btn-danger btn-sm' 
                                                               onclick='return confirm(\"Are you sure you want to delete this record?\");' />
                                                    </form>
                                                  </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='8' class='text-center'>No records found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ensure jQuery and Bootstrap are loaded -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Wait for jQuery to be available
$(document).ready(function() {
    console.log('Office.php jQuery loaded successfully');
    
    // Load patient queue on page load
    loadPatientQueue();
    
    // Clear queue functionality
    $('#clearQueue').on('click', function() {
        if(confirm('Are you sure you want to clear the patient queue?')) {
            $.ajax({
                url: 'clear_queue.php',
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    loadPatientQueue();
                    if (response.success) {
                        alert('Queue cleared successfully');
                    } else {
                        alert('Error: ' + (response.error || 'Unknown error'));
                    }
                },
                error: function() {
                    alert('Error clearing queue');
                }
            });
        }
    });
    
    // Call patient functionality
    $('.call-patient').on('click', function() {
        const patientName = $(this).data('name');
        const patientId = $(this).data('id');
        const patientPid = $(this).data('pid');
        const status = $(this).data('type'); // OFFICE
        
        callPatient(patientPid || patientId, patientName, status);
    });
});

function loadPatientQueue() {
    $.ajax({
        url: 'fetch_queue.php',
        method: 'GET',
        success: function(response) {
            $('#patientQueue').html(response);
        },
        error: function(xhr, status, error) {
            console.error('Error loading queue:', error);
            $('#patientQueue').html('<div class="alert alert-danger">Error loading queue</div>');
        }
    });
}

function callPatient(pid, patientName, status) {
    if (!pid || !patientName || !status) {
        alert('Missing patient information');
        return;
    }

    console.log('Calling patient:', {pid, patientName, status});

    // Send data to PHP to store in tv_dashboard
    fetch('store_tv_dashboard.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `pid=${encodeURIComponent(pid)}&patient_name=${encodeURIComponent(patientName)}&room=&status=${encodeURIComponent(status)}`
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            alert('Patient called successfully: ' + patientName);
            loadPatientQueue(); // Refresh queue
        } else {
            alert('Failed to call patient: ' + (data.error || 'Unknown error'));
        }
    })
    .catch((error) => {
        console.error('Error:', error);
        alert('An error occurred while calling patient.');
    });
}
</script>

<!-- Desktop Notification System -->
<script src="js/notifications.js"></script>
<script>
    // Additional office-specific notification handling
    function refreshPageData() {
        // Refresh the office patient data
        location.reload();
    }
</script>

<?php 
// Include footer
include 'footer_section.php';
?>
