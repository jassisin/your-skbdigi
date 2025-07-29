
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
            width: 25%;
        }

        .patient-table td {
            padding: 16px 12px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
            width: 25%;
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

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
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
                    </tr>
                </thead>
                <tbody id="patientTableBody">
                    <?php
                        // Fetch patients from database
                        // First check what timestamp column name exists
                        $checkColumns = mysqli_query($conn, "SHOW COLUMNS FROM tv_dashboard");
                        $timestampColumn = 'created_date'; // Default
                        
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
                        
                        $sql = "SELECT * FROM tv_dashboard";
                        try {
                            // Try ordering by the determined timestamp column
                            $sql .= " ORDER BY $timestampColumn ASC";
                            $result = mysqli_query($conn, $sql);
                        } catch (Exception $e) {
                            // If ordering fails, just select without ordering
                            error_log("TV Dashboard error: " . $e->getMessage());
                            $sql = "SELECT * FROM tv_dashboard";
                            $result = mysqli_query($conn, $sql);
                        }

                        if ($result && mysqli_num_rows($result) > 0):
                            while ($row = mysqli_fetch_assoc($result)):
                                $pid = isset($row['PID']) ? htmlspecialchars($row['PID']) : '';
                                // Use 'patient_name' as per your table definition, fallback to empty string if not set
                                $name = isset($row['patient_name']) ? htmlspecialchars($row['patient_name']) : '';
                                // Read room directly from table
                                $room = isset($row['room']) ? htmlspecialchars($row['room']) : '';
                                $status = isset($row['status']) ? htmlspecialchars($row['status']) : '';

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
        </td>
                                
                                    </tr>
                                    <?php
                                            endwhile;
                                        else:
                                    ?>
                    <tr>
                        <td colspan="4" class="no-patients">
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
        
        // Function to announce patient using speech synthesis
        function announcePatient(pid, name, status) {
            if ('speechSynthesis' in window) {
                // Create announcement message based on status
                let destination = '';
                switch (status) {
                    case 'NURSING_VITAL': destination = 'Nursing for vital signs'; break;
                    case 'MEDICAL': destination = 'Medical consultation'; break;
                    case 'DENTAL': destination = 'Dental consultation'; break;
                    case 'NURSING_CARE': destination = 'Nursing care'; break;
                    case 'PHARMACY': destination = 'Pharmacy'; break;
                    case 'RECEPTION_BILL': destination = 'Reception for billing'; break;
                    default: destination = 'Reception'; break;
                }
                
                // Format the announcement
                const message = `Patient ${name} with ID ${pid}, please proceed to ${destination}`;
                
                // Create utterance and set properties
                const utterance = new SpeechSynthesisUtterance(message);
                utterance.rate = 0.9;  // Slightly slower rate for clarity
                utterance.pitch = 1.0;
                utterance.volume = 1.0;
                
                // Cancel any current speech and speak the new announcement
                speechSynthesis.cancel();
                speechSynthesis.speak(utterance);
                
                // Visual feedback that announcement was made
                const button = event.currentTarget;
                button.classList.add('btn-success');
                button.disabled = true;
                
                setTimeout(() => {
                    button.classList.remove('btn-success');
                    button.disabled = false;
                }, 3000);
            } else {
                alert('Speech synthesis is not supported in your browser.');
            }
        }
        
        // Auto-refresh patient table every 30 seconds
        function refreshPatientTable() {
            fetch('fetch_tv_dashboard.php')
                .then(response => response.json())
                .then(data => {
                    const currentTableBody = document.getElementById('patientTableBody');
                    if (currentTableBody && Array.isArray(data.patients)) {
                        let html = '';
                        data.patients.forEach(patient => {
                            // Display status labels properly
                            let statusDisplay = '';
                            switch (patient.status) {
                                case 'RECEPTION_ENTRY':  statusDisplay = 'RECEPTION - ENTRY'; break;
                                case 'NURSING_VITAL':    statusDisplay = 'NURSING - VITAL'; break;
                                case 'MEDICAL':          statusDisplay = 'MEDICAL'; break;
                                case 'DENTAL':           statusDisplay = 'DENTAL'; break;
                                case 'NURSING_CARE':     statusDisplay = 'NURSING - CARE'; break;
                                case 'PHARMACY':         statusDisplay = 'PHARMACY'; break;
                                case 'RECEPTION_BILL':   statusDisplay = 'RECEPTION - BILL'; break;
                                default:                 statusDisplay = patient.status; break;
                            }
                            
                            html += `<tr><td><strong>${patient.PID}</strong></td><td>${patient.patient_name}</td><td>${patient.room}</td><td><span class='patient-status'>${statusDisplay}</span></td></tr>`;
                            // Check if patient hasn't been announced yet
                            if (patient.isAnnounced == 0) {
                                announcePatientMulti(patient.PID, patient.patient_name, patient.room);
                                // Update isAnnounced in backend
                                fetch('update_announce.php', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                    body: `id=${encodeURIComponent(patient.id)}`
                                });
                            }
                        });
                        currentTableBody.innerHTML = html;
                    }
                })
                .catch(error => {
                    console.log('Failed to refresh patient table:', error);
                });
        }

        // Announce in both English and Hindi
        function announcePatientMulti(pid, name, room) {
            if ('speechSynthesis' in window) {
                // English announcement
                let engMsg = `Patient ${name} with ID ${pid}, please proceed to ${room}`;
                // Hindi announcement (simple translation)
                let hindiMsg = `मरीज ${name} आईडी ${pid}, कृपया ${room} पर जाएं`;
                let utterEng = new SpeechSynthesisUtterance(engMsg);
                utterEng.lang = 'en-US';
                utterEng.rate = 0.9;
                utterEng.pitch = 1.0;
                utterEng.volume = 1.0;
                let utterHindi = new SpeechSynthesisUtterance(hindiMsg);
                utterHindi.lang = 'hi-IN';
                utterHindi.rate = 0.9;
                utterHindi.pitch = 1.0;
                utterHindi.volume = 1.0;
                speechSynthesis.cancel();
                speechSynthesis.speak(utterEng);
                setTimeout(() => {
                    speechSynthesis.speak(utterHindi);
                }, 2000);
            }
        }

        // Initialize auto-refresh
        document.addEventListener('DOMContentLoaded', function() {
            setInterval(refreshPatientTable, 5000); // Refresh every 5 seconds
        });
    </script>
</body>
</html>

<?php
    // Include footer
include 'footer_section.php';
?>