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
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Edit</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
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
             max-height: 400px;     /* Set max height for vertical scroll */
            overflow-y: auto;
        }

        .table-header {
            padding: 20px 24px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
        }

        .table-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
        }

        .patient-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
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

        /* Date input styling */
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

        .btn-sm {
            padding: 8px 12px;
            font-size: 12px;
            border-radius: 8px;
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

        .section-tabs {
            display: flex;
            gap: 4px;
            margin-bottom: 24px;
            background: rgba(255, 255, 255, 0.9);
            padding: 4px;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        .tab-btn {
            padding: 12px 24px;
            border: none;
            background: transparent;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            flex: 1;
            text-align: center;
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3);
        }

        .tab-btn:not(.active) {
            color: #6b7280;
        }

        .tab-btn:not(.active):hover {
            background: rgba(79, 70, 229, 0.1);
            color: #4f46e5;
        }

        @media (max-width: 768px) {
            .container { padding: 12px; }
            .header-content { flex-direction: column; align-items: stretch; }
            .header-actions { justify-content: center; }
            .patient-table { font-size: 12px; }
            .patient-table th, .patient-table td { padding: 8px 6px; }
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 16px;
            opacity: 0.5;
        }
      /* Custom responsive fixes */
      .layout-navbar {
        min-height: 70px;
        padding: 0;
        width: 100%;
      }

      .layout-navbar .container-xxl {
        padding: 0.75rem 1rem;
      }

      .content-footer {
        min-height: 70px;
        padding: 0;
        width: 100%;
      }

      .content-footer .container-xxl {
        padding: 0.75rem 1rem;
      }

      /* Username visibility fix */
      .navbar .nav-item span {
        color: #ffffff !important;
        font-weight: 500;
      }

      /* Dropdown text visibility */
      .dropdown-item-text span {
        color: #333 !important;
      }

      /* Mobile responsive adjustments */
      @media (max-width: 768px) {
        .layout-navbar .container-xxl {
          padding: 0.5rem 0.75rem;
        }

        .content-footer .container-xxl {
          padding: 0.5rem 0.75rem;
        }

        .layout-navbar {
          min-height: 60px;
        }

        .content-footer {
          min-height: 60px;
        }

        .navbar-nav .nav-item {
          margin: 0.25rem 0;
        }

        .footer-container {
          text-align: center;
        }

        .footer-container .d-none.d-lg-inline-block {
          display: block !important;
          margin-top: 0.5rem;
        }
      }

      @media (max-width: 576px) {
        .layout-navbar .navbar-nav-right {
          flex-wrap: wrap;
        }

        .layout-navbar .navbar-nav.align-items-center {
          width: 100%;
          margin-bottom: 0.5rem;
        }

        .layout-navbar .navbar-nav.flex-row {
          justify-content: center;
        }
      }
       * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .mockup-container {
            max-width: 1400px;
            margin: 0 auto;
            margin-bottom: 40px;
        }

        .mockup-title {
            text-align: center;
            color: white;
            font-size: 2.5rem;
            margin-bottom: 40px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
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

        .mockup-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .mockup-header h2 {
            color: #4f46e5;
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .mockup-header p {
            color: #6b7280;
            font-size: 1rem;
        }

        /* Mockup 1: Calendar Grid Layout */
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

        .calendar-day.selected {
            border: 3px solid #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.2);
            transform: scale(1.05);
        }

        .calendar-day.today {
            position: relative;
            border: 2px solid #f59e0b;
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
        }

        .today-indicator {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #f59e0b;
            color: white;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 10px;
            font-weight: 600;
        }

        .today-count {
            background: #f59e0b !important;
        }

        .prev-month {
            opacity: 0.4;
            background: #f9fafb !important;
            color: #9ca3af;
        }

        .prev-month:hover {
            opacity: 0.7;
            transform: none;
        }

        /* Calendar Navigation */
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

        /* Date Details Panel */
        .date-details-panel {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            margin-top: 25px;
            overflow: hidden;
            border: 2px solid #4f46e5;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .details-header {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            padding: 20px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .details-header h3 {
            margin: 0;
            font-size: 1.3rem;
        }

        .close-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }

        .details-content {
            padding: 25px;
        }

        .patient-breakdown {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .breakdown-card {
            background: #f8fafc;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            border-left: 4px solid #4f46e5;
        }

        .breakdown-number {
            font-size: 2rem;
            font-weight: 700;
            color: #4f46e5;
            margin-bottom: 5px;
        }

        .breakdown-label {
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .patient-list {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
        }

        .patient-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .patient-item:last-child {
            border-bottom: none;
        }

        .patient-name {
            font-weight: 600;
            color: #1f2937;
        }

        .patient-time {
            color: #6b7280;
            font-size: 0.9rem;
        }

        .patient-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-medical {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-dental {
            background: #fef3c7;
            color: #d97706;
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
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
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
                        <h1 class="section-title" id="sectionTitle">Patients for pharmacy</h1>
                        <p class="section-subtitle" id="sectionSubtitle">Manage pharmacy consultations and patient care
                        </p>
                    </div>
                    <div class="header-actions">
                        <button class="btn btn-secondary" onclick="refreshPatientList()">
                            🔄 Refresh List
                        </button>
                    </div>
                </div>
            </div>

            <!-- Patient List Table -->
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
                            $sql    = "SELECT PID, name, notes, status, created_date FROM pharmacy_table WHERE DATE(created_date) = '$today' OR next_visit_date = '$today'";
                            $result = mysqli_query($conn, $sql);
                            if ($result && mysqli_num_rows($result) > 0):
                                while ($row = mysqli_fetch_assoc($result)):
                                    // Format time as HH:MM AM/PM
                                    $timeArrived = date('h:i A', strtotime($row['created_date']));
                                    $pid         = htmlspecialchars($row['PID']);
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
						                                    <option value="RECEPTION_ENTRY"
						                                        <?php echo $row['status'] == 'RECEPTION_ENTRY' ? 'selected' : '' ?>>RECEPTION - ENTRY
						                                    </option>
						                                    <option value="NURSING_VITAL"						                                                                 				                                                                  <?php echo $row['status'] == 'NURSING_VITAL' ? 'selected' : '' ?>>
						                                        NURSING - VITAL</option>
						                                    <option value="MEDICAL"						                                                           				                                                            <?php echo $row['status'] == 'MEDICAL' ? 'selected' : '' ?>>MEDICAL
						                                    </option>
						                                    <option value="DENTAL"						                                                          				                                                           <?php echo $row['status'] == 'DENTAL' ? 'selected' : '' ?>>DENTAL</option>
						                                    <option value="NURSING_CARE"						                                                                				                                                                 <?php echo $row['status'] == 'NURSING_CARE' ? 'selected' : '' ?>>
						                                        NURSING - CARE</option>
						                                    <option value="PHARMACY"						                                                            				                                                             <?php echo $row['status'] == 'PHARMACY' ? 'selected' : '' ?>>PHARMACY
						                                    </option>
						                                    <option value="RECEPTION_BILL"
						                                        <?php echo $row['status'] == 'RECEPTION_BILL' ? 'selected' : '' ?>>RECEPTION - BILL</option>
						                                </select>
						                            </td>
						                            <td>
						                                <div class="action-buttons">
						                                    <button class="btn-icon btn-update" onclick="updatePatientStatus('<?php echo $pid ?>')"
						                                        title="Update Status">
						                                        ✅
						                                    </button>
						                                    <button class="btn-icon btn-call" onclick="callPatient('<?php echo $pid ?>')"
						                                        title="Call Patient">
						                                        📞
						                                    </button>
						                                </div>
						                            </td>
						                        </tr>
						                        <?php
                                                        endwhile;
                                                    else:
                                                ?>
                        <tr>
                            <td colspan="6" class="text-center">No nursing patients found.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
          <div class="mockup-section">
     <div class="header-content">
                <h2>Calendar Dashboard</h2>
    </div>

 <div class="calendar-navigation">
    <button class="nav-btn" onclick="changeMonth(-1)">
        <span>‹</span>
    </button>
    <div class="current-month">
        <h3 id="monthName"></h3>
    </div>
    <button class="nav-btn" onclick="changeMonth(1)">
        <span>›</span>
    </button>
</div>

  <div class="calendar-grid" id="calendarGrid"></div>
</div>
    </div>
    <script>
           const calendarGrid = document.getElementById("calendarGrid");
    const monthName = document.getElementById("monthName");
    let currentDate = new Date();

    // Dummy data for patient counts
 let patientData = {};

function fetchPatientCountsAndRender(date) {
    fetch('get_pharmacy_count.php')
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

// On page load, fetch counts and render calendar
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

      // Previous month days
      for (let i = startDay - 1; i >= 0; i--) {
        const d = prevLastDay.getDate() - i;
        const el = document.createElement("div");
        el.className = "calendar-day prev-month";
        el.innerHTML = `<div class="day-number">${d}</div>`;
        calendarGrid.appendChild(el);
      }

      // Current month days
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
          el.innerHTML = `<div class="day-number">${d}</div><div class="no-appointments">No Patients</div>`;
        }

       el.onclick = () => {
         if (count > 0) {
           fetchPharmacyPatientsByDate(fullDate);
        }
      };
        calendarGrid.appendChild(el);
      }

      // Fill in next month days to complete 6 weeks (if needed)
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

    renderCalendar(currentDate);
    // Function to handle voice announcement when call button is clicked
    function callPatient(pid) {
     // Get patient name and status from the table row
    const patientRow = document.querySelector(`#status-${pid}`).closest('tr');
    const patientNameCell = patientRow.querySelector('td:nth-child(2)');
    const roomCell = patientRow.querySelector('td:nth-child(3)');
    const statusSelect = document.querySelector(`#status-${pid}`);

    const patientName = patientNameCell ? patientNameCell.textContent.trim() : '';
    const room = roomCell ? roomCell.textContent.trim() : '';
    const status = statusSelect ? statusSelect.value : '';

    // Send data to PHP to store in tv_dashboard
    fetch('store_tv_dashboard.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `pid=${encodeURIComponent(pid)}&patient_name=${encodeURIComponent(patientName)}&room=${encodeURIComponent(room)}&status=${encodeURIComponent(status)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Patient added to TV Dashboard.');
        } else {
            alert('Failed to add patient to TV Dashboard: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(() => {
        alert('An error occurred while adding patient to TV Dashboard.');
    });
    }

    // Function to test voice synthesis
    function testVoiceAnnouncement() {
        if ('speechSynthesis' in window) {
            const testMessage = "Voice announcement system is working properly";
            const utterance = new SpeechSynthesisUtterance(testMessage);
            utterance.rate = 0.8;
            utterance.pitch = 1.0;
            utterance.volume = 0.9;

            speechSynthesis.cancel();
            speechSynthesis.speak(utterance);
        } else {
            alert('Speech synthesis not supported in this browser');
        }
    }

    // Function to refresh patient list (placeholder)
    function refreshPatientList() {
        location.reload();
    }
function fetchPharmacyPatientsByDate(dateStr) {
    fetch('get_pharmacy_patients_by_date.php?date=' + encodeURIComponent(dateStr))
        .then(response => response.text())
        .then(html => {
            document.getElementById('nursingTableBody').innerHTML = html;
        })
        .catch(() => {
            document.getElementById('nursingTableBody').innerHTML =
                '<tr><td colspan="7" class="text-center">Failed to load patients for this date.</td></tr>';
        });
}
    // Function to update patient status (placeholder)
function updatePatientStatus(pid) {
    const statusSelect = document.querySelector(`#status-${pid}`);
    const notesInput = document.querySelector(`#notes-${pid}`);
    const nextDateInput = document.querySelector(`#nextdate-${pid}`);

    if (statusSelect && notesInput && nextDateInput) {
        const status = statusSelect.value;
        const notes = notesInput.value;
        const next_visit_date = nextDateInput.value;

        fetch('update_pharmacy_patient.php', {
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

    // Initialize voices when page loads
    window.addEventListener('load', function() {
        if ('speechSynthesis' in window) {
            // Load voices
            speechSynthesis.getVoices();

            // Some browsers need this event listener
            speechSynthesis.addEventListener('voiceschanged', function() {
                console.log('Voices loaded:', speechSynthesis.getVoices().length);
            });
        }
    });
    </script>
</body>

</html>
<?php
    // Include footer
include 'footer_section.php';
?>