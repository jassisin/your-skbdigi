<?php
require('session.php');

require('connection.php');

if(isset($_SESSION['main_admin'])){
    $username=$_SESSION['main_admin'];
  }
// Fetch all data from the reception table
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid patient ID.');
}
$id = intval($_GET['id']);

// Fetch patient data
$sql = "SELECT * FROM reception WHERE id = $id";
$result = mysqli_query($conn, $sql);
if (!$result || mysqli_num_rows($result) == 0) {
    die('Patient not found.');
}
$row = mysqli_fetch_assoc($result);

// Handle form submission
if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $nationality = mysqli_real_escape_string($conn, $_POST['nationality']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $whatsapp = mysqli_real_escape_string($conn, $_POST['whatsapp']);
    $area = mysqli_real_escape_string($conn, $_POST['area']);
    $residence = mysqli_real_escape_string($conn, $_POST['residence']);
    $camp_boss = mysqli_real_escape_string($conn, $_POST['camp_boss']);
    $hr_staff = mysqli_real_escape_string($conn, $_POST['hr_staff']);
    $hr_phone = mysqli_real_escape_string($conn, $_POST['hr_phone']);
    $company = mysqli_real_escape_string($conn, $_POST['company']);
    $refferal = mysqli_real_escape_string($conn, $_POST['refferal']);
    $gate_service_site = mysqli_real_escape_string($conn, $_POST['gate_service_site']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);

    $update_sql = "UPDATE reception SET
        name='$name',
        nationality='$nationality',
        phone='$phone',
        whatsapp='$whatsapp',
        area='$area',
        residence='$residence',
        camp_boss='$camp_boss',
        hr_staff='$hr_staff',
        hr_phone='$hr_phone',
        company='$company',
        refferal='$refferal',
        gate_service_site='$gate_service_site',
        status='$status',
        notes='$notes'
        WHERE id=$id";
    if (mysqli_query($conn, $update_sql)) {
        // Also update the corresponding department table based on status
        $pid = $row['PID']; // Get the patient's PID
        $old_status = $row['status']; // Get the previous status
        
        // Determine old and new target tables based on status
        function getTargetTable($status) {
            switch (strtoupper($status)) {
                case 'DENTAL':
                    return 'dental_table';
                case 'MEDICAL':
                    return 'medical_table';
                case 'NURSING_VITAL':
                case 'NURSING_CARE':
                    return 'nursing_table';
                case 'PHARMACY':
                    return 'pharmacy_table';
                default:
                    return null; // For RECEPTION_ENTRY, RECEPTION_BILL, etc.
            }
        }
        
        $old_target_table = getTargetTable($old_status);
        $new_target_table = getTargetTable($status);
        
        // If status changed and both old and new statuses have department tables
        if ($old_target_table && $new_target_table && $old_target_table !== $new_target_table && $pid) {
            // Update old department table to mark as completed/transferred
            $updateOldQuery = "UPDATE $old_target_table SET status = 'TRANSFERRED', notes = CONCAT(IFNULL(notes, ''), ' - Transferred to " . strtoupper($status) . "') WHERE PID = ?";
            $stmt = $conn->prepare($updateOldQuery);
            $stmt->bind_param("s", $pid);
            if (!$stmt->execute()) {
                error_log("Error updating old table $old_target_table: " . $stmt->error);
            }
        }
        
        // Handle new department table
        if ($new_target_table && $pid) {
            // Check if PID already exists in the new target table
            $checkQuery = "SELECT id FROM $new_target_table WHERE PID = ?";
            $stmt = $conn->prepare($checkQuery);
            $stmt->bind_param("s", $pid);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $next_visit_date = null;
            $updated_date = date('Y-m-d H:i:s');
            
            if ($result->num_rows > 0) {
                // Update existing record in new table
                $updateQuery = "UPDATE $new_target_table SET name=?, status=?, notes=? WHERE PID=?";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param("ssss", $name, $status, $notes, $pid);
            } else {
                // Insert new record in new table
                $insertQuery = "INSERT INTO $new_target_table (PID, name, status, next_visit_date, notes, created_date) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bind_param("ssssss", $pid, $name, $status, $next_visit_date, $notes, $updated_date);
            }
            
            if (!$stmt->execute()) {
                error_log("Error updating $new_target_table: " . $stmt->error);
            }
        }
        // If status didn't change but is still a department status, just update the current table
        else if ($new_target_table && $pid && (!$old_target_table || $old_target_table === $new_target_table)) {
            // Check if PID already exists in the target table
            $checkQuery = "SELECT id FROM $new_target_table WHERE PID = ?";
            $stmt = $conn->prepare($checkQuery);
            $stmt->bind_param("s", $pid);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $next_visit_date = null;
            $updated_date = date('Y-m-d H:i:s');
            
            if ($result->num_rows > 0) {
                // Update existing record
                $updateQuery = "UPDATE $new_target_table SET name=?, status=?, notes=? WHERE PID=?";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param("ssss", $name, $status, $notes, $pid);
            } else {
                // Insert new record
                $insertQuery = "INSERT INTO $new_target_table (PID, name, status, next_visit_date, notes, created_date) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bind_param("ssssss", $pid, $name, $status, $next_visit_date, $notes, $updated_date);
            }
            
            if (!$stmt->execute()) {
                error_log("Error updating $new_target_table: " . $stmt->error);
            }
        }
        
        echo "<script>alert('Patient updated successfully!'); window.location='reception.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}

// save information to the reception table
if (isset($_POST['submit'])) {
    // Collect form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $nationality = mysqli_real_escape_string($conn, $_POST['nationality']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $whatsapp = mysqli_real_escape_string($conn, $_POST['whatsapp']);
    $area = mysqli_real_escape_string($conn, $_POST['area']);
    $residence = mysqli_real_escape_string($conn, $_POST['residence']);
    $camp_boss = mysqli_real_escape_string($conn, $_POST['camp_boss']);
    $hr_staff = mysqli_real_escape_string($conn, $_POST['hr_staff']);
    $hr_phone = mysqli_real_escape_string($conn, $_POST['company_phone']);
    $company = mysqli_real_escape_string($conn, $_POST['company']);
    $refferal = mysqli_real_escape_string($conn, $_POST['referral']);
    $gate_service_site = mysqli_real_escape_string($conn, $_POST['gate_service_site']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
    $created_date = date('Y-m-d H:i:s');
    $next_visit_date = mysqli_real_escape_string($conn, $_POST['next_visit_date']);

    // Generate PID
    $pid = '';
    $lastPidResult = mysqli_query($conn, "SELECT PID FROM reception ORDER BY id DESC LIMIT 1");
    if ($lastPidResult && mysqli_num_rows($lastPidResult) > 0) {
        $row = mysqli_fetch_assoc($lastPidResult);
        $lastPid = $row['PID'];
        // Extract letter and number
        $letter = substr($lastPid, 0, 1);
        $number = intval(substr($lastPid, 1));
        if ($number < 9999) {
            $number++;
        } else {
            $number = 1;
            $letter = chr(ord($letter) + 1);
            if ($letter > 'Z') $letter = 'A'; // Wrap around if needed
        }
        $pid = $letter . str_pad($number, 4, '0', STR_PAD_LEFT);
    } else {
        $pid = 'A0001';
    }

    // Insert into DB
    $sql = "INSERT INTO reception 
    (name, nationality, phone, whatsapp, area, residence, camp_boss, hr_staff, hr_phone, company, refferal, gate_service_site, status, notes, PID, created_date, next_visit_date)
    VALUES
    ('$name', '$nationality', '$phone', '$whatsapp', '$area', '$residence', '$camp_boss', '$hr_staff', '$hr_phone', '$company', '$refferal', '$gate_service_site', '$status', '$notes', '$pid', '$created_date', '$next_visit_date')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Patient added successfully!'); window.location='reception.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}
?>                  
<!DOCTYPE html>

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

    <title>Update Patient Info</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #fff;
            min-height: 100vh;
            color: #333;
            padding: 20px;
        }
        .container {
            max-width: 700px;
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
        .form-container {
            background: #f8fafc;
            border-radius: 18px;
            box-shadow: 0 6px 24px rgba(79,70,229,0.08), 0 1.5px 4px rgba(0,0,0,0.04);
            border: 1.5px solid #e0e7ef;
            padding: 32px 28px 18px 28px;
            margin-bottom: 32px;
        }
        .form-group {
            margin-bottom: 18px;
            display: flex;
            align-items: center;
        }
        .form-group label {
            min-width: 150px;
            margin: 0;
            font-weight: 500;
            color: #4f46e5;
        }
        .form-control, .form-select {
            flex: 1;
            padding: 10px 14px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 15px;
            background: white;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
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
        @media (max-width: 768px) {
            .container { padding: 12px; }
            .header-content { flex-direction: column; align-items: stretch; }
            .form-container { padding: 16px 8px; }
            .form-group label { min-width: 100px; font-size: 14px; }
        }
    </style>
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

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <?php include("header.php"); ?>

        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar" style="background-color: <?php echo $page_heading_color; ?>;">
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="mdi mdi-menu mdi-24px"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <!-- Search -->
              <div class="navbar-nav align-items-center">
                <div class="nav-item d-flex align-items-center">
                  <i class="mdi mdi-magnify mdi-24px lh-0"></i>
                  <input
                    type="text"
                    class="form-control border-0 shadow-none bg-body"
                    placeholder="Search..."
                    aria-label="Search..." />
                </div>
              </div>
              <!-- /Search -->

              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Place this tag where you want the button to render. -->
                <li class="nav-item lh-1 me-3">
                  <?=$username?>
                </li>

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a
                    class="nav-link dropdown-toggle hide-arrow p-0"
                    href="javascript:void(0);"
                    data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      <img src="../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end mt-3 py-2">
              
                    <li>
                      <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="logout.php">
                        <i class="mdi mdi-power me-1 mdi-20px"></i>
                        <span class="align-middle">Log Out</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>

          <!-- / Navbar -->




            <!-- Default -->
         

<div class="container">
    <!-- Section Header -->
    <div class="section-header">
        <div class="header-content">
            <div>
                <h1 class="section-title">Update Patient Details</h1>
                <p class="section-subtitle">Edit and update patient information</p>
            </div>
        </div>
    </div>
    <!-- Form Container -->
    <div class="form-container">
        <form method="post">
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($row['name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Nationality:</label>
                <input type="text" name="nationality" class="form-control" value="<?php echo htmlspecialchars($row['nationality']); ?>">
            </div>
            <div class="form-group">
                <label>Phone:</label>
                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($row['phone']); ?>">
            </div>
            <div class="form-group">
                <label>Whatsapp:</label>
                <input type="text" name="whatsapp" class="form-control" value="<?php echo htmlspecialchars($row['whatsapp']); ?>">
            </div>
            <div class="form-group">
                <label>Area:</label>
                <input type="text" name="area" class="form-control" value="<?php echo htmlspecialchars($row['area']); ?>">
            </div>
            <div class="form-group">
                <label>Residence:</label>
                <input type="text" name="residence" class="form-control" value="<?php echo htmlspecialchars($row['residence']); ?>">
            </div>
            <div class="form-group">
                <label>Camp Boss:</label>
                <input type="text" name="camp_boss" class="form-control" value="<?php echo htmlspecialchars($row['camp_boss']); ?>">
            </div>
            <div class="form-group">
                <label>Hr Staff:</label>
                <input type="text" name="hr_staff" class="form-control" value="<?php echo htmlspecialchars($row['hr_staff']); ?>">
            </div>
            <div class="form-group">
                <label>Phone (HR):</label>
                <input type="text" name="hr_phone" class="form-control" value="<?php echo htmlspecialchars($row['hr_phone']); ?>">
            </div>
            <div class="form-group">
                <label>Company:</label>
                <input type="text" name="company" class="form-control" value="<?php echo htmlspecialchars($row['company']); ?>">
            </div>
            <div class="form-group">
                <label>Referral:</label>
                <input type="text" name="refferal" class="form-control" value="<?php echo htmlspecialchars($row['refferal']); ?>">
            </div>
            <div class="form-group">
                <label>Gate Service Site:</label>
                <input type="text" name="gate_service_site" class="form-control" value="<?php echo htmlspecialchars($row['gate_service_site']); ?>">
            </div>
            <div class="form-group">
                <label>Next Visit Date:</label>
                <input type="date" name="next_visit_date" class="form-control" value="<?php echo htmlspecialchars($row['next_visit_date'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Status:</label>
                <select name="status" id="status" class="form-select" required>
                    <option value="">Select Status</option>
                    <option value="RECEPTION_ENTRY" <?php if($row['status']=='RECEPTION_ENTRY') echo 'selected'; ?>>RECEPTION - ENTRY</option>
                    <option value="NURSING_VITAL" <?php if($row['status']=='NURSING_VITAL') echo 'selected'; ?>>NURSING - VITAL</option>
                    <option value="MEDICAL" <?php if($row['status']=='MEDICAL') echo 'selected'; ?>>MEDICAL</option>
                    <option value="DENTAL" <?php if($row['status']=='DENTAL') echo 'selected'; ?>>DENTAL</option>
                    <option value="NURSING_CARE" <?php if($row['status']=='NURSING_CARE') echo 'selected'; ?>>NURSING - CARE</option>
                    <option value="PHARMACY" <?php if($row['status']=='PHARMACY') echo 'selected'; ?>>PHARMACY</option>
                    <option value="RECEPTION_BILL" <?php if($row['status']=='RECEPTION_BILL') echo 'selected'; ?>>RECEPTION - BILL</option>
                </select>
            </div>
            <div class="form-group">
                <label>Notes:</label>
                <input type="text" name="notes" class="form-control" value="<?php echo htmlspecialchars($row['notes']); ?>">
            </div>
            <div class="form-group" style="justify-content: flex-end; gap: 10px;">
                <button type="submit" name="submit" class="btn btn-primary">Update</button>
                <a href="reception.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl" style="background-color: <?php echo $footer_color; ?>;">
                <div
                  class="footer-container d-flex align-items-center justify-content-between py-3 flex-md-row flex-column">
                  <div class="text-body mb-2 mb-md-0">
                    ©
                    <script>
                      document.write(new Date().getFullYear());
                    </script>
                  
                  </div>
                  <div class="d-none d-lg-inline-block">
                    <a href="https://sbkdigi.in/" class="footer-link me-3" target="_blank">SBK Details</a>
                    
                  </div>
                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>
    <script>
function autofillForm() {
    document.getElementById('name').value = 'John Doe';
    document.getElementById('nationality').value = 'Indian';
    document.getElementById('phone').value = '9876543210';
    document.getElementById('whatsapp').value = '9876543210';
    document.getElementById('area').value = 'Kochi';
    document.getElementById('residence').value = 'Flat 12A';
    document.getElementById('camp_boss').value = 'Mr. Raj';
    document.getElementById('hr_staff').value = 'Ms. Priya';
    document.getElementById('company_phone').value = '9123456780';
    document.getElementById('company').value = 'ABC Corp';
    document.getElementById('referral').value = 'Mr. Mathew';
    document.getElementById('gate_service_site').value = 'Site A';
    document.getElementById('status').value = 'NURSING';
    document.getElementById('notes').value = 'No remarks';
}
</script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
