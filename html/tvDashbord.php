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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
            overflow-x: hidden;
        }

        .dashboard-container {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        /* Video Section - 70% width */
        .video-section {
            flex: 0 0 70%;
            background: rgba(0, 0, 0, 0.9);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            border-right: 3px solid rgba(255, 255, 255, 0.2);
        }

        .video-container {
            width: 100%;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .hospital-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 0;
        }

        .hospital-video-alt {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 0;
        }

        .video-container-alt {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                45deg,
                rgba(79, 70, 229, 0.1) 0%,
                rgba(124, 58, 237, 0.1) 100%
            );
            pointer-events: none;
        }

        .video-header {
            position: absolute;
            top: 30px;
            left: 30px;
            right: 30px;
            z-index: 10;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .video-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #4f46e5;
            margin-bottom: 8px;
            text-align: center;
        }

        .video-subtitle {
            color: #6b7280;
            font-size: 1.1rem;
            text-align: center;
        }

        /* Patient Table Section - 30% width */
        .patient-section {
            flex: 0 0 30%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            border-left: 3px solid rgba(79, 70, 229, 0.3);
        }

        .patient-header {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            padding: 24px;
            text-align: center;
            border-bottom: 3px solid rgba(255, 255, 255, 0.2);
        }

        .patient-header h2 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .patient-header p {
            opacity: 0.9;
            font-size: 1rem;
        }

        .patient-table-wrapper {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }

        .patient-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .patient-table th {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            padding: 16px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .patient-table td {
            padding: 16px 12px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
            font-size: 14px;
        }

        .patient-table tr:hover {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            transform: scale(1.001);
            transition: all 0.2s ease;
        }

        .patient-pid {
            font-weight: 700;
            color: #4f46e5;
            font-size: 16px;
        }

        .patient-name {
            color: #1e293b;
            font-weight: 500;
        }

        .patient-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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

        .current-time {
            position: absolute;
            bottom: 30px;
            right: 30px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
            z-index: 10;
        }

        .time-display {
            font-size: 1.5rem;
            font-weight: 700;
            color: #4f46e5;
            margin-bottom: 4px;
        }

        .date-display {
            font-size: 0.9rem;
            color: #6b7280;
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

        /* Responsive Design */
        @media (max-width: 1024px) {
            .dashboard-container {
                flex-direction: column;
            }
            
            .video-section {
                flex: 0 0 60%;
                border-right: none;
                border-bottom: 3px solid rgba(255, 255, 255, 0.2);
            }
            
            .patient-section {
                flex: 0 0 40%;
                border-left: none;
                border-top: 3px solid rgba(79, 70, 229, 0.3);
            }
        }

        @media (max-width: 768px) {
            .video-title {
                font-size: 2rem;
            }
            
            .patient-header h2 {
                font-size: 1.5rem;
            }
            
            .patient-table th,
            .patient-table td {
                padding: 12px 8px;
                font-size: 12px;
            }
        }

        /* Auto-scroll animation for patient table */
        .patient-table-wrapper {
            animation: autoScroll 30s linear infinite;
        }

        @keyframes autoScroll {
            0%, 20% { transform: translateY(0); }
            80%, 100% { transform: translateY(-20px); }
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Video Section (70% width) -->
        <div class="video-section">
            <div class="video-container">
                <!-- Hospital Video - YouTube Embed with Full Video Playback -->
                <iframe class="hospital-video" 
                        src="https://www.youtube.com/embed/cDDWvj_q-o8?autoplay=1&mute=1&loop=1&playlist=cDDWvj_q-o8&controls=0&showinfo=0&rel=0&modestbranding=1&start=0&end=0&iv_load_policy=3&fs=0&disablekb=1" 
                        frameborder="0" 
                        allow="autoplay; encrypted-media; fullscreen" 
                        allowfullscreen
                        title="Hospital Video">
                </iframe>
                
                <!-- Alternative Video Container for better control -->
                <div id="videoContainer" class="video-container-alt" style="display: none;">
                    <video class="hospital-video-alt" autoplay muted loop playsinline>
                        <source src="hospital_video.mp4" type="video/mp4">
                        <source src="hospital_video.webm" type="video/webm">
                    </video>
                </div>
                
                <!-- Fallback for when video doesn't work -->
                <div class="video-fallback" id="videoFallback" style="display: none; align-items: center; justify-content: center; height: 100%; background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; font-size: 2rem; text-align: center;">
                    <div>
                        <div style="font-size: 4rem; margin-bottom: 20px;">🏥</div>
                        <div>Hospital Care Excellence</div>
                        <div style="font-size: 1.2rem; margin-top: 10px; opacity: 0.8;">Your Health, Our Priority</div>
                        <button onclick="tryLoadVideo()" style="margin-top: 20px; padding: 10px 20px; background: white; color: #4f46e5; border: none; border-radius: 8px; cursor: pointer;">Try Again</button>
                    </div>
                </div>
                
                <div class="video-overlay"></div>
                
                <!-- Current Time Display -->
                <div class="current-time">
                    <div class="time-display" id="currentTime">00:00:00</div>
                    <div class="date-display" id="currentDate">Loading...</div>
                </div>
            </div>
        </div>

        <!-- Patient Table Section (30% width) -->
        <div class="patient-section">
            <div class="patient-header">
                <h2>📋 Patient Queue</h2>
                <p>Current patients waiting for service</p>
            </div>
            
            <div class="patient-table-wrapper">
                <table class="patient-table">
                    <thead>
                        <tr>
                            <th>PID</th>
                            <th>Patient Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="patientTableBody">
                        <?php
                            // Fetch patients from database
                            $sql = "SELECT PID, name, status FROM reception WHERE status IN ('RECEPTION_ENTRY', 'NURSING_VITAL', 'MEDICAL', 'DENTAL', 'NURSING_CARE', 'PHARMACY') ORDER BY created_date ASC";
                            $result = mysqli_query($conn, $sql);
                            
                            if ($result && mysqli_num_rows($result) > 0):
                                while ($row = mysqli_fetch_assoc($result)):
                                    $pid = htmlspecialchars($row['PID']);
                                    $name = htmlspecialchars($row['name']);
                                    $status = htmlspecialchars($row['status']);
                                    
                                    // Determine status display and class
                                    $statusDisplay = '';
                                    $statusClass = 'status-waiting';
                                    
                                    switch($status) {
                                        case 'RECEPTION_ENTRY':
                                            $statusDisplay = 'Waiting';
                                            $statusClass = 'status-waiting';
                                            break;
                                        case 'NURSING_VITAL':
                                        case 'MEDICAL':
                                        case 'DENTAL':
                                        case 'NURSING_CARE':
                                            $statusDisplay = 'Consulting';
                                            $statusClass = 'status-consulting';
                                            break;
                                        case 'PHARMACY':
                                            $statusDisplay = 'Pharmacy';
                                            $statusClass = 'status-pharmacy';
                                            break;
                                        default:
                                            $statusDisplay = 'In Process';
                                            $statusClass = 'status-waiting';
                                    }
                                ?>
                                <tr>
                                    <td><strong class="patient-pid"><?php echo $pid; ?></strong></td>
                                    <td class="patient-name"><?php echo $name; ?></td>
                                    <td><span class="patient-status <?php echo $statusClass; ?>"><?php echo $statusDisplay; ?></span></td>
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
    </div>

    <script>
        // Handle video loading and full playback
        function tryLoadVideo() {
            const iframe = document.querySelector('.hospital-video');
            const fallback = document.getElementById('videoFallback');
            
            // Try to reload the iframe
            if (iframe) {
                iframe.src = iframe.src;
                fallback.style.display = 'none';
            }
        }

        // Check if iframe loads properly
        function checkVideoLoad() {
            const iframe = document.querySelector('.hospital-video');
            const fallback = document.getElementById('videoFallback');
            
            // Set a timeout to check if video loads
            setTimeout(() => {
                try {
                    // Try to access iframe content (will fail if blocked)
                    if (iframe && iframe.contentDocument === null) {
                        console.log('Video iframe may be blocked');
                    }
                } catch (e) {
                    console.log('Video loading normally');
                }
            }, 3000);
        }

        // Alternative: Use HTML5 video if YouTube is blocked
        function useLocalVideo() {
            const iframe = document.querySelector('.hospital-video');
            const videoContainer = document.getElementById('videoContainer');
            const fallback = document.getElementById('videoFallback');
            
            if (iframe) iframe.style.display = 'none';
            if (videoContainer) {
                videoContainer.style.display = 'block';
                const video = videoContainer.querySelector('video');
                if (video) {
                    video.play().catch(e => {
                        console.log('Local video failed to play:', e);
                        fallback.style.display = 'flex';
                    });
                }
            }
        }

        // Update current time and date
        function updateDateTime() {
            const now = new Date();
            
            // Format time
            const timeOptions = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            };
            const timeString = now.toLocaleTimeString('en-US', timeOptions);
            
            // Format date
            const dateOptions = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            const dateString = now.toLocaleDateString('en-US', dateOptions);
            
            document.getElementById('currentTime').textContent = timeString;
            document.getElementById('currentDate').textContent = dateString;
        }

        // Auto-refresh patient table every 30 seconds
        function refreshPatientTable() {
            // Only refresh the patient table, not the whole page
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

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateDateTime();
            setInterval(updateDateTime, 1000); // Update every second
            setInterval(refreshPatientTable, 30000); // Refresh every 30 seconds
            
            // Check video loading
            checkVideoLoad();
            
            // If you want to test local video instead, uncomment the line below:
            // setTimeout(useLocalVideo, 5000);
        });
    </script>
</body>
</html>

<?php
    // Include footer
    include 'footer_section.php';
?>