<?php
    require 'session.php';
    include 'connection.php';

    // Set page-specific variables
    $page_title         = "Nursing";
    $page_description   = "Nursing page description";
    $page_heading_color = "#6f42c1"; // Purple color
    $footer_color       = "#f8f9fa"; // Light gray

    // Set username
    if (isset($_SESSION['main_admin'])) {
        $username = $_SESSION['main_admin'];
    } else {
        $username = 'Guest';
    }

    // Include header
    include 'header_section.php';
?>
<html
  lang="en"
  class="light-style layout-menu-fixed layout-compact"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Nursing Dashboard</title>

    <meta name="description" content="Nursing patient management" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
      rel="stylesheet" />

    <link rel="stylesheet" href="../assets/vendor/fonts/materialdesignicons.css" />

    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="../assets/vendor/libs/node-waves/node-waves.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <style>
         * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            padding: 12px;
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
            background: #f8fafc;
            border-radius: 18px;
            box-shadow: 0 6px 24px rgba(79,70,229,0.08), 0 1.5px 4px rgba(0,0,0,0.04);
            border: 1.5px solid #e0e7ef;
            overflow: hidden;
            margin-bottom: 32px;
            padding: 0 0 8px 0;
        }

        .patient-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 900px;
            background: transparent;
        }

        .patient-table thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            background: linear-gradient(90deg, #4f46e5 0%, #7c3aed 100%);
            color: #fff;
            font-weight: 800;
            font-size: 17px;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            padding: 22px 18px 22px 18px;
            border-bottom: 3.5px solid #ede9fe;
            box-shadow: 0 4px 16px rgba(79,70,229,0.13);
        }
        .patient-table thead th:first-child {
            border-top-left-radius: 16px;
        }
        .patient-table thead th:last-child {
            border-top-right-radius: 16px;
        }

        .patient-table tbody tr {
            background: #fff;
            transition: box-shadow 0.2s, background 0.2s;
        }

        .patient-table tbody tr:hover {
            background: #f3f4f6;
            box-shadow: 0 2px 12px rgba(79,70,229,0.08);
        }

        .patient-table td {
            padding: 16px 14px;
            border-bottom: 1.5px solid #e5e7eb;
            vertical-align: middle;
            font-size: 15px;
            color: #22223b;
        }

        .patient-table td input,
        .patient-table td select {
            font-size: 14px;
        }

        .patient-table td:first-child,
        .patient-table th:first-child {
            border-top-left-radius: 12px;
        }
        .patient-table td:last-child,
        .patient-table th:last-child {
            border-top-right-radius: 12px;
        }

        .patient-table tbody tr:last-child td {
            border-bottom: none;
        }

        .status-dropdown {
            width: 100%;
            padding: 8px 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 13px;
            background: white;
            transition: all 0.2s ease;
        }

        .status-dropdown:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .date-input {
            width: 100%;
            padding: 8px 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 13px;
            background: white;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .date-input:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .date-input::-webkit-calendar-picker-indicator {
            cursor: pointer;
            filter: invert(0.6);
        }

        .date-input::-webkit-calendar-picker-indicator:hover {
            filter: invert(0.4);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-icon {
            padding: 8px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-call {
            background: #10b981;
            color: white;
        }

        .btn-call:hover {
            background: #059669;
            transform: scale(1.1);
        }

        .btn-update {
            background: #f59e0b;
            color: white;
        }

        .btn-update:hover {
            background: #d97706;
            transform: scale(1.05);
        }

        .mockup-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .calendar-header {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: 600;
            border-radius: 12px;
        }

        .calendar-day {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .calendar-day:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .calendar-day.today {
            position: relative;
            border: 2px solid #f59e0b;
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
        }

        .prev-month {
            opacity: 0.4;
            background: #f9fafb !important;
            color: #9ca3af;
        }

        .calendar-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            background: rgba(255, 255, 255, 0.9);
            padding: 15px 25px;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .nav-btn {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            border: none;
            border-radius: 12px;
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
        }

        .current-month h3 {
            color: #1f2937;
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
        }

        .calendar-day.has-appointments {
            border-color: #10b981;
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
        }

        .calendar-day.no-appointments {
            border-color: #f87171;
            background: linear-gradient(135deg, #fef2f2, #fecaca);
        }

        .day-number {
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 8px;
        }

        .appointment-count {
            background: #4f46e5;
            color: white;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .no-patients {
            color: #f87171;
            font-size: 0.8rem;
            font-weight: 600;
        }
    </style>

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
    <script src="../assets/js/config.js"></script>
  </head>
  <body>
<!-- Your page content goes here -->
          <div class="content-wrapper">
             <div class="container">

        <!-- Section Header -->
        <div class="section-header">
                            <div class="header-content">
                                <div>
                                    <h1 class="section-title" id="sectionTitle">Patients for Nursing</h1>
                                    <p class="section-subtitle" id="sectionSubtitle">Manage nursing consultations and patient care</p>
                                </div>
                                <div class="header-actions">
                                    <button class="btn btn-secondary" onclick="refreshPatientList()">
                                        🔄 Refresh List
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="patient-table-container">
                            <table class="patient-table">
                                <thead>
                                    <tr>
                                        <th>PID</th>
                                        <th>Name</th>
                                        <th>Time Arrived</th>
                                        <th>Notes</th>
                                        <th>Next Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="nursingTableBody">
                                   <?php
                                        $today = date('Y-m-d');

                                        $sql = "SELECT PID, name, notes, status, created_date 
                                                FROM nursing_table 
                                                WHERE (DATE(created_date) = '$today' OR next_visit_date = '$today') 
                                                AND (status = 'NURSING_VITAL' OR status = 'NURSING_CARE')";

                                        $result = mysqli_query($conn, $sql);
                                        if ($result && mysqli_num_rows($result) > 0):
                                            while ($row = mysqli_fetch_assoc($result)):
                                                $timeArrived = date('h:i A', strtotime($row['created_date']));
                                                $pid = htmlspecialchars($row['PID']);
                                    ?>
                                    <tr>
                                        <td><strong><?php echo $pid ?></strong></td>
                                        <td><?php echo htmlspecialchars($row['name']) ?></td>
                                        <td><?php echo $timeArrived ?></td>
                                        <td>
                                            <input type="text" class="form-control" id="notes-<?php echo $pid ?>" value="">
                                        </td>
                                        <td>
                                            <input type="date" class="date-input" id="nextdate-<?php echo $pid ?>"
                                                   min="<?php echo date('Y-m-d') ?>"
                                                   title="Select next appointment date">
                                        </td>
                                        <td>
                                            <select class="status-dropdown" id="status-<?php echo $pid ?>">
                                                <option value="RECEPTION_ENTRY" <?php echo $row['status'] == 'RECEPTION_ENTRY' ? 'selected' : '' ?>>RECEPTION - ENTRY</option>
                                                <option value="NURSING_VITAL" <?php echo $row['status'] == 'NURSING_VITAL' ? 'selected' : '' ?>>NURSING - VITAL</option>
                                                <option value="MEDICAL" <?php echo $row['status'] == 'MEDICAL' ? 'selected' : '' ?>>MEDICAL</option>
                                                <option value="DENTAL" <?php echo $row['status'] == 'DENTAL' ? 'selected' : '' ?>>DENTAL</option>
                                                <option value="NURSING_CARE" <?php echo $row['status'] == 'NURSING_CARE' ? 'selected' : '' ?>>NURSING - CARE</option>
                                                <option value="PHARMACY" <?php echo $row['status'] == 'PHARMACY' ? 'selected' : '' ?>>PHARMACY</option>
                                                <option value="RECEPTION_BILL" <?php echo $row['status'] == 'RECEPTION_BILL' ? 'selected' : '' ?>>RECEPTION - BILL</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn-icon btn-update" onclick="updatePatientStatus('<?php echo $pid ?>')" title="Update Status">✅</button>
                                                <button class="btn-icon btn-call" onclick="callPatient('<?php echo $pid ?>')" title="Call Patient">📞</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                            endwhile;
                                        else:
                                    ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No nursing patients found.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mockup-section">
                            <div class="header-content">
                                <h2>Calendar Dashboard</h2>
                            </div>
                            <div class="calendar-navigation">
                                <button class="nav-btn" onclick="changeMonth(-1)"><span>‹</span></button>
                                <div class="current-month"><h3 id="monthName"></h3></div>
                                <button class="nav-btn" onclick="changeMonth(1)"><span>›</span></button>
                            </div>
                            <div class="calendar-grid" id="calendarGrid"></div>
                        </div>
</div>
<script>
         const calendarGrid = document.getElementById("calendarGrid");
        const monthName = document.getElementById("monthName");
        let currentDate = new Date();
        let patientData = {};

        function fetchPatientCountsAndRender(date) {
            fetch('get_nursing_counts.php')
                .then(response => response.json())
                .then(data => {
                    patientData = data;
                    renderCalendar(date);
                })
                .catch(() => {
                    patientData = {};
                    renderCalendar(date);
                });
        }

        fetchPatientCountsAndRender(currentDate);

        function renderCalendar(date) {
          const year = date.getFullYear();
          const month = date.getMonth();
          const today = new Date();
          calendarGrid.innerHTML = "";

          const firstDay = new Date(year, month, 1);
          const lastDay = new Date(year, month + 1, 0);
          const prevLastDay = new Date(year, month, 0);

          const startDay = firstDay.getDay();
          const totalDays = lastDay.getDate();

          const monthDisplay = date.toLocaleString("default", { month: "long", year: "numeric" });
          monthName.textContent = monthDisplay;

          const headers = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
          headers.forEach(day => {
            const el = document.createElement("div");
            el.className = "calendar-header";
            el.textContent = day;
            calendarGrid.appendChild(el);
          });

          for (let i = startDay - 1; i >= 0; i--) {
            const d = prevLastDay.getDate() - i;
            const el = document.createElement("div");
            el.className = "calendar-day prev-month";
            el.innerHTML = `<div class="day-number">${d}</div>`;
            calendarGrid.appendChild(el);
          }

          for (let d = 1; d <= totalDays; d++) {
            const fullDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
            const el = document.createElement("div");
            el.className = "calendar-day";

            if (
              today.getFullYear() === year &&
              today.getMonth() === month &&
              today.getDate() === d
            ) {
              el.classList.add("today");
            }

            const count = patientData[fullDate];
            if (count > 0) {
              el.classList.add("has-appointments");
              el.innerHTML = `<div class="day-number">${d}</div><div class="appointment-count">${count} Patients</div>`;
            } else {
              el.classList.add("no-appointments");
              el.innerHTML = `<div class="day-number">${d}</div><div class="no-patients">No Patients</div>`;
            }

           el.onclick = () => {
                   if (count > 0) {
                     fetchPatientsByDate(fullDate);
                  }
                };
            calendarGrid.appendChild(el);
          }

          const totalCells = calendarGrid.children.length;
          const extra = 42 - totalCells;
          for (let i = 1; i <= extra; i++) {
            const el = document.createElement("div");
            el.className = "calendar-day next-month";
            el.innerHTML = `<div class="day-number">${i}</div>`;
            calendarGrid.appendChild(el);
          }
        }

        function changeMonth(offset) {
          currentDate.setMonth(currentDate.getMonth() + offset);
          renderCalendar(currentDate);
        }

        function callPatient(pid) {
            const patientRow = document.querySelector(`#status-${pid}`).closest('tr');
            const patientNameCell = patientRow.querySelector('td:nth-child(2)');
            const statusSelect = document.querySelector(`#status-${pid}`);
            const patientName = patientNameCell ? patientNameCell.textContent.trim() : '';
            const status = statusSelect ? statusSelect.value : '';

            fetch('store_tv_dashboard.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `pid=${encodeURIComponent(pid)}&patient_name=${encodeURIComponent(patientName)}&room=&status=${encodeURIComponent(status)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Patient added to TV Dashboard.');
                } else {
                    alert('Failed to add patient to TV Dashboard: ' + (data.error || 'Unknown error'));
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                alert('An error occurred while adding patient to TV Dashboard.');
            });
        }

        function fetchPatientsByDate(dateStr) {
            fetch('get_nursing_patients_by_date.php?date=' + encodeURIComponent(dateStr))
                .then(response => response.text())
                .then(html => {
                    document.getElementById('nursingTableBody').innerHTML = html;
                })
                .catch(() => {
                    document.getElementById('nursingTableBody').innerHTML =
                        '<tr><td colspan="7" class="text-center">Failed to load patients for this date.</td></tr>';
                });
        }

        function refreshPatientList() {
            location.reload();
        }

        function updatePatientStatus(pid) {
            const statusSelect = document.querySelector(`#status-${pid}`);
            const notesInput = document.querySelector(`#notes-${pid}`);
            const nextDateInput = document.querySelector(`#nextdate-${pid}`);

            if (statusSelect && notesInput && nextDateInput) {
                const status = statusSelect.value;
                const notes = notesInput.value;
                const next_visit_date = nextDateInput.value;

                fetch('update_nursing_patient.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `pid=${encodeURIComponent(pid)}&status=${encodeURIComponent(status)}&notes=${encodeURIComponent(notes)}&next_visit_date=${encodeURIComponent(next_visit_date)}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Patient updated successfully!');
                    } else {
                        alert('Update failed: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(() => {
                    alert('An error occurred while updating the patient.');
                });
            }
        }
    </script>
    
    <!-- Desktop Notification System -->
    <script src="js/notifications.js"></script>
    <script>
        // Additional nursing-specific notification handling
        function refreshPageData() {
            // Refresh the nursing patient data
            location.reload();
        }
        
        // Show notification when page loads if there are new patients
        document.addEventListener('DOMContentLoaded', function() {
            // Check for URL parameters indicating a new patient notification
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('notify') === 'new_patient') {
                const patientName = urlParams.get('patient_name');
                const pid = urlParams.get('pid');
                if (patientName && pid) {
                    setTimeout(() => {
                        if (window.departmentNotifications) {
                            window.departmentNotifications.showInPageNotification({
                                patient_name: patientName,
                                pid: pid,
                                status: 'NURSING',
                                message: `New patient assigned: ${patientName} (PID: ${pid})`
                            });
                        }
                    }, 1000);
                }
            }
        });
    </script>
</body>
</html>

<?php
// Include footer
include 'footer_section.php';
?>