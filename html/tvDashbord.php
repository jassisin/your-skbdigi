<?php
    require 'session.php';
    include 'connection.php';

    // Set username
    if (isset($_SESSION['main_admin'])) {
        $username = $_SESSION['main_admin'];
    } else {
        $username = 'Guest';
    }

    // Include header
    include 'header_section.php';
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Hospital TV Dashboard</title>

    <meta name="description" content="Hospital TV Dashboard with Patient Information" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="../assets/vendor/fonts/materialdesignicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #fff;
            min-height: 100vh;
            color: #333;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .section-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 700;
            color: #4f46e5;
            margin-bottom: 8px;
        }

        .section-subtitle {
            color: #6b7280;
            font-size: 1rem;
        }

        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            box-shadow: 0 4px 16px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.9);
            color: #4f46e5;
            border: 2px solid #e5e7eb;
        }

        .btn-secondary:hover {
            background: white;
            border-color: #4f46e5;
            transform: translateY(-1px);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .patient-table-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            overflow-x: auto;
            margin-bottom: 24px;
        }

        .patient-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        .patient-table th {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            padding: 16px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .patient-table td {
            padding: 16px 12px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .patient-table tr {
            transition: all 0.2s ease;
        }

        .patient-table tr:hover {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            transform: scale(1.001);
        }

        .patient-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-waiting {
            background: #fef3c7;
            color: #92400e;
        }

        .status-consulting {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-pharmacy {
            background: #d1fae5;
            color: #065f46;
        }

        .no-patients {
            text-align: center;
            padding: 40px 20px;
            color: #6b7280;
        }

        .no-patients-icon {
            font-size: 3rem;
            margin-bottom: 16px;
            opacity: 0.5;
        }
 .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .clock-box {
            background: linear-gradient(135deg, #f8fafc, #e0e7ff 80%);
            border: 2px solid #4f46e5;
            border-radius: 12px;
            padding: 10px 22px;
            min-width: 230px;
            text-align: right;
            font-size: 1.1rem;
            color: #4f46e5;
            font-weight: 600;
            box-shadow: 0 2px 10px rgba(79,70,229,0.07);
            letter-spacing: 0.5px;
            transition: box-shadow 0.2s;
        }
        .clock-box:hover {
            box-shadow: 0 4px 18px rgba(79,70,229,0.13);
        }
        @media (max-width: 768px) {
            .container { padding: 12px; }
            .header-content { flex-direction: column; align-items: stretch; }
            .header-actions { justify-content: center; }
            .patient-table { font-size: 12px; }
            .patient-table th, .patient-table td { padding: 8px 6px; }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Section Header -->
        <div class="section-header">
            <div class="header-content">
                <div>
                    <h1 class="section-title">📋 Patient Queue</h1>
                    <p class="section-subtitle">Current patients waiting for service</p>
                </div>
                 <div class="header-actions">
                    <div id="clock" class="clock-box"></div>
                </div>
            </div>
        </div>

        <!-- Patient List Table -->
        <div class="patient-table-container">
            <table class="patient-table">
                <thead>
                    <tr>
                         <th>PID</th>
                        <th>Patient Name</th>
                        <th>Room</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="patientTableBody">
                    <?php
                        // Fetch patients from database
                        $sql    = "SELECT * FROM tv_dashboard ORDER BY created_date ASC";
                        $result = mysqli_query($conn, $sql);

                        if ($result && mysqli_num_rows($result) > 0):
                            while ($row = mysqli_fetch_assoc($result)):
                                $pid = isset($row['PID']) ? htmlspecialchars($row['PID']) : '';
                                // Use 'patient_name' as per your table definition, fallback to empty string if not set
                                $name = isset($row['patient_name']) ? htmlspecialchars($row['patient_name']) : '';
                                // Room can be null or missing
                                $room   = isset($row['room']) && $row['room'] ? htmlspecialchars($row['room']) : '';
                                $status = isset($row['status']) ? htmlspecialchars($row['status']) : '';
                        // ...existing code...

                                // Determine status display and class
                                $statusDisplay = '';
                                $statusClass   = 'status-waiting';

                                switch ($status) {
                                    case 'RECEPTION_ENTRY':
                                        $statusDisplay = 'Waiting';
                                        $statusClass   = 'status-waiting';
                                        break;
                                    case 'NURSING_VITAL':
                                    case 'MEDICAL':
                                    case 'DENTAL':
                                    case 'NURSING_CARE':
                                        $statusDisplay = 'Consulting';
                                        $statusClass   = 'status-consulting';
                                        break;
                                    case 'PHARMACY':
                                        $statusDisplay = 'Pharmacy';
                                        $statusClass   = 'status-pharmacy';
                                        break;
                                    default:
                                        $statusDisplay = 'In Process';
                                        $statusClass   = 'status-waiting';
                                }
                            ?>
		                            <tr>
		                                <td><strong><?php echo $pid; ?></strong></td>
		                                <td><?php echo $name; ?></td>
		                                <td><?php echo $room; ?></td>
		                               <td>
             <span class="patient-status <?php echo $statusClass; ?>">
            <?php
                // Show user-friendly status label
                switch ($status) {
                    case 'RECEPTION_ENTRY':  echo 'RECEPTION - ENTRY'; break;
                    case 'NURSING_VITAL':    echo 'NURSING - VITAL'; break;
                    case 'MEDICAL':          echo 'MEDICAL'; break;
                    case 'DENTAL':           echo 'DENTAL'; break;
                    case 'NURSING_CARE':     echo 'NURSING - CARE'; break;
                    case 'PHARMACY':         echo 'PHARMACY'; break;
                    case 'RECEPTION_BILL':   echo 'RECEPTION - BILL'; break;
                    default:                 echo htmlspecialchars($status); break;
                }
            ?>
        </span>
        </td>	                                                                <?php echo $statusClass; ?>"><?php echo $statusDisplay; ?></span></td>
		                                 <td>
		                                    <button class="btn btn-primary" style="padding:6px 16px;font-size:13px;" title="Announce">
		                                        <span style="font-size:1.3em;">🔊</span>
		                                    </button>
		                                </td>
		                            </tr>
		                            <?php
                                            endwhile;
                                        else:
                                    ?>
                    <tr>
                        <td colspan="3" class="no-patients">
                            <div class="no-patients-icon">😊</div>
                            <div>No patients in queue</div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Clock function
        function updateClock() {
            const now = new Date();
            const options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
            const dateStr = now.toLocaleDateString(undefined, options);
            const timeStr = now.toLocaleTimeString(undefined, { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            document.getElementById('clock').textContent = dateStr + ' ' + timeStr;
        }
        setInterval(updateClock, 1000);
        updateClock();
        // Auto-refresh patient table every 30 seconds
        function refreshPatientTable() {
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTableBody = doc.getElementById('patientTableBody');
                    const currentTableBody = document.getElementById('patientTableBody');

                    if (newTableBody && currentTableBody) {
                        currentTableBody.innerHTML = newTableBody.innerHTML;
                    }
                })
                .catch(error => {
                    console.log('Failed to refresh patient table:', error);
                });
        }

        // Initialize auto-refresh
        document.addEventListener('DOMContentLoaded', function() {
            setInterval(refreshPatientTable, 30000); // Refresh every 30 seconds
        });
    </script>
</body>
</html>

<?php
    // Include footer
include 'footer_section.php';
?>