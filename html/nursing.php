<?php
    require 'session.php';
    include 'connection.php';

    // Set page-specific variables
    $page_title         = "Edit";
    $page_description   = "Edit page description";
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

    <title>Edit</title>

    <meta name="description" content="" />

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

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
          /*  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
            min-height: 100vh;
            color: #333;
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
                    <h1 class="section-title" id="sectionTitle">Patients for Nursing</h1>
                    <p class="section-subtitle" id="sectionSubtitle">Manage nursing consultations and patient care</p>
                </div>
                <div class="header-actions">
                    <button class="btn btn-secondary" onclick="refreshPatientList()">
                        🔄 Refresh List
                    </button>
                    <button class="btn btn-primary" onclick="testVoiceAnnouncement()">
                        🔊 Test Voice
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
                       $sql    = "SELECT PID, name, notes, status, created_date FROM reception WHERE status IN ('NURSING_VITAL', 'NURSING_CARE')";
                       $result = mysqli_query($conn, $sql);
                       if ($result && mysqli_num_rows($result) > 0):
                           while ($row = mysqli_fetch_assoc($result)):
                               // Format time as HH:MM AM/PM
                               $timeArrived = date('h:i A', strtotime($row['created_date']));
                               $pid         = htmlspecialchars($row['PID']);
                           ?>
		    <tr>
		        <td><strong><?php echo $pid?></strong></td>
		        <td><?php echo htmlspecialchars($row['name'])?></td>
		        <td><?php echo $timeArrived?></td>
		        <td>
		            <input type="text" class="form-control" id="notes-<?php echo $pid?>" value="">
		        </td>
		        <td>
		            <input type="date" class="date-input" id="nextdate-<?php echo $pid?>"
		                   min="<?php echo date('Y-m-d')?>"
		                   title="Select next appointment date">
		        </td>
		        <td>
		            <select class="status-dropdown" id="status-<?php echo $pid?>">
		                <option value="RECEPTION_ENTRY" <?php echo $row['status'] == 'RECEPTION_ENTRY' ? 'selected' : ''?>>RECEPTION - ENTRY</option>
		                <option value="NURSING_VITAL" <?php echo $row['status'] == 'NURSING_VITAL' ? 'selected' : ''?>>NURSING - VITAL</option>
		                <option value="MEDICAL" <?php echo $row['status'] == 'MEDICAL' ? 'selected' : ''?>>MEDICAL</option>
		                <option value="DENTAL" <?php echo $row['status'] == 'DENTAL' ? 'selected' : ''?>>DENTAL</option>
		                <option value="NURSING_CARE" <?php echo $row['status'] == 'NURSING_CARE' ? 'selected' : ''?>>NURSING - CARE</option>
		                <option value="PHARMACY" <?php echo $row['status'] == 'PHARMACY' ? 'selected' : ''?>>PHARMACY</option>
		                <option value="RECEPTION_BILL" <?php echo $row['status'] == 'RECEPTION_BILL' ? 'selected' : ''?>>RECEPTION - BILL</option>
		            </select>
		        </td>
		        <td>
		            <div class="action-buttons">
		                <button class="btn-icon btn-update" onclick="updatePatientStatus('<?php echo $pid?>')" title="Update Status">
		                    ✅
		                </button>
		                <button class="btn-icon btn-call" onclick="callPatient('<?php echo $pid?>')" title="Call Patient">
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
        <td colspan="7" class="text-center">No nursing patients found.</td>
    </tr>
<?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Voice Announcement JavaScript -->
<script>
// Function to handle voice announcement when call button is clicked
function callPatient(pid) {
    // Check if browser supports speech synthesis
    if ('speechSynthesis' in window) {
        try {
            // Get patient name from the table row
            const patientRow = document.querySelector(`#status-${pid}`).closest('tr');
            const patientNameCell = patientRow.querySelector('td:nth-child(2)');
            const patientName = patientNameCell.textContent.trim();

            // Create the announcement message
            const message = `Patient ${pid} ${patientName} please go to room 2`;

            // Create speech synthesis utterance
            const utterance = new SpeechSynthesisUtterance(message);

            // Configure voice settings
            utterance.rate = 0.8; // Slightly slower for clarity
            utterance.pitch = 1.0; // Normal pitch
            utterance.volume = 0.9; // High volume

            // Optional: Set specific voice (English)
            const voices = speechSynthesis.getVoices();
            const englishVoice = voices.find(voice =>
                voice.lang.startsWith('en') && voice.name.includes('Female')
            ) || voices.find(voice => voice.lang.startsWith('en'));

            if (englishVoice) {
                utterance.voice = englishVoice;
            }

            // Event handlers for speech
            utterance.onstart = function() {
                console.log('Voice announcement started for patient:', pid);
                // Optional: Disable button during announcement
                const callButton = document.querySelector(`button[onclick="callPatient('${pid}')"]`);
                if (callButton) {
                    callButton.disabled = true;
                    callButton.innerHTML = '🔊';
                }
            };

            utterance.onend = function() {
                console.log('Voice announcement completed for patient:', pid);
                // Re-enable button after announcement
                const callButton = document.querySelector(`button[onclick="callPatient('${pid}')"]`);
                if (callButton) {
                    callButton.disabled = false;
                    callButton.innerHTML = '📞';
                }
            };

            utterance.onerror = function(event) {
                console.error('Speech synthesis error:', event.error);
                alert('Voice announcement failed. Please check your browser settings.');
                // Re-enable button on error
                const callButton = document.querySelector(`button[onclick="callPatient('${pid}')"]`);
                if (callButton) {
                    callButton.disabled = false;
                    callButton.innerHTML = '📞';
                }
            };

            // Stop any ongoing speech and start new announcement
            speechSynthesis.cancel();
            speechSynthesis.speak(utterance);

        } catch (error) {
            console.error('Error in callPatient function:', error);
            alert('Unable to make voice announcement. Please try again.');
        }
    } else {
        // Fallback for browsers that don't support speech synthesis
        alert(`Patient ${pid} please go to room 2`);
        console.warn('Speech synthesis not supported in this browser');
    }
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

// Function to update patient status (now includes next date)
function updatePatientStatus(pid) {
    const statusSelect = document.querySelector(`#status-${pid}`);
    const notesInput = document.querySelector(`#notes-${pid}`);
    const nextDateInput = document.querySelector(`#nextdate-${pid}`);

    if (statusSelect && notesInput && nextDateInput) {
        const status = statusSelect.value;
        const notes = notesInput.value;
        const nextDate = nextDateInput.value;

        // Here you would typically send an AJAX request to update the database
        console.log(`Updating patient ${pid}: Status=${status}, Notes=${notes}, Next Date=${nextDate}`);

        // Placeholder alert with next date info
        let alertMessage = `Patient ${pid} status updated to: ${status}`;
        if (nextDate) {
            const formattedDate = new Date(nextDate).toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            alertMessage += `\nNext appointment: ${formattedDate}`;
        }
        alert(alertMessage);
    }
}

// Function to format and validate date input
function formatDateInput(pid) {
    const dateInput = document.querySelector(`#nextdate-${pid}`);
    if (dateInput && dateInput.value) {
        const selectedDate = new Date(dateInput.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0); // Reset time for comparison

        if (selectedDate < today) {
            alert('Please select a future date for the next appointment.');
            dateInput.value = '';
            return false;
        }
    }
    return true;
}

// Add event listeners for date validation when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Add change event listeners to all date inputs
    const dateInputs = document.querySelectorAll('.date-input');
    dateInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            const pid = this.id.replace('nextdate-', '');
            formatDateInput(pid);
        });
    });
});

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