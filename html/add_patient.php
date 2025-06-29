<?php
require('session.php');

require('connection.php');

if(isset($_SESSION['main_admin'])){
    $username=$_SESSION['main_admin'];
  }
// Fetch all data from the reception table
$sql = "SELECT * FROM reception";
$result = mysqli_query($conn, $sql);

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

// Generate PID function
    function generatePID($conn) {
        // Get the last PID from the database
        $query = "SELECT PID FROM reception WHERE PID IS NOT NULL AND PID != '' ORDER BY 
                  SUBSTRING(PID, 1, 1), 
                  CAST(SUBSTRING(PID, 3) AS UNSIGNED) DESC LIMIT 1";
        $result = mysqli_query($conn, $query);
        
        if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $lastPID = $row['PID'];
            
            // Extract letter and number parts (assuming format is A-0001)
            $parts = explode('-', $lastPID);
            $letter = $parts[0];
            $number = intval($parts[1]);
            
            // Increment the number
            $number++;
            
            // Check if we need to move to next letter
            if($number > 9999) {
                $letter = chr(ord($letter) + 1);
                $number = 1;
                
                // Check if we've exceeded Z
                if($letter > 'Z') {
                    return null; // Maximum limit reached
                }
            }
            
            // Format new PID with hyphen
            $newPID = $letter . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        } else {
            // First entry, start with A-0001
            $newPID = 'A-0001';
        }
        
        // Double-check that this PID doesn't already exist (in case of concurrent requests)
        $checkQuery = "SELECT id FROM reception WHERE PID = '" . mysqli_real_escape_string($conn, $newPID) . "'";
        $checkResult = mysqli_query($conn, $checkQuery);
        
        if(mysqli_num_rows($checkResult) > 0) {
            // PID already exists, recursively try to get next one
            return generatePID($conn);
        }
        
        return $newPID;
    }
    
    // Generate unique PID
    $pid = generatePID($conn);
    
    if($pid === null) {
        echo "<script>alert('Maximum PID limit reached (Z-9999). Cannot add more records.'); window.history.back();</script>";
        exit;
    }

    // Insert into DB
    $sql = "INSERT INTO reception 
        (name, nationality, phone, whatsapp, area, residence, camp_boss, hr_staff, hr_phone, company, refferal, gate_service_site, status, notes, PID, created_date)
        VALUES
        ('$name', '$nationality', '$phone', '$whatsapp', '$area', '$residence', '$camp_boss', '$hr_staff', '$hr_phone', '$company', '$refferal', '$gate_service_site', '$status', '$notes', '$pid', '$created_date')";
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

    <title>Edit</title>
    <style>
          .form-group {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        .form-group label {
            min-width: 150px; /* Adjust width as needed */
            margin: 0; /* Reset margin for consistency */
        }
        .card {
            margin-top: 20px;
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
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h4 class="py-3 mb-4"><u>Add Patient Details</u></h4>
        </div>
        <div class="card-body">
            <form action="add_patient.php" method="post">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="nationality">Nationality:</label>
                    <input type="text" class="form-control" id="nationality" name="nationality" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>
                
                <div class="form-group">
                    <label for="whatsapp">Whatsapp:</label>
                    <input type="text" class="form-control" id="whatsapp" name="whatsapp">
                </div>
                
                <div class="form-group">
                    <label for="area">Area:</label>
                    <input type="text" class="form-control" id="area" name="area" required>
                </div>
                
                <div class="form-group">
                    <label for="residence">Residence:</label>
                    <input type="text" class="form-control" id="residence" name="residence" required>
                </div>
                
                <div class="form-group">
                    <label for="camp_boss">Camp Boss:</label>
                    <input type="text" class="form-control" id="camp_boss" name="camp_boss">
                </div>
                
                <div class="form-group">
                    <label for="hr_staff">HR Staff:</label>
                    <input type="text" class="form-control" id="hr_staff" name="hr_staff">
                </div>
                
                <div class="form-group">
                    <label for="company_phone">Company Phone:</label>
                    <input type="text" class="form-control" id="company_phone" name="company_phone">
                </div>
                
                <div class="form-group">
                    <label for="company">Company:</label>
                    <input type="text" class="form-control" id="company" name="company" required>
                </div>
                
                <div class="form-group">
                    <label for="referral">Referral:</label>
                    <input type="text" class="form-control" id="referral" name="referral">
                </div>
                
                <div class="form-group">
                    <label for="gate_service_site">Gate Service Site:</label>
                    <input type="text" class="form-control" id="gate_service_site" name="gate_service_site">
                </div>
                
                   <div class="form-group">
                    <label for="status">Status:</label>
                    <select class="form-control" id="status" name="status" required>
                       <option value="">Select Status</option> 
                       <option value="RECEPTION_ENTRY">RECEPTION - ENTRY</option>
                       <option value="NURSING_VITAL">NURSING - VITAL</option>   
                        <option value="MEDICAL">MEDICAL</option>
                        <option value="DENTAL">DENTAL</option>
                        <option value="NURSING_CARE">NURSING - CARE</option>
                        <option value="PHARMACY">PHARMACY</option>
                        <option value="RECEPTION_BILL">RECEPTION - BILL</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="notes">Notes:</label>
                    <textarea class="form-control" id="notes" name="notes" rows="4"></textarea>
                </div>

                <div class="form-group justify-content-end">
                    <!-- <button type="button" class="btn btn-secondary" onclick="autofillForm()">Autofill</button> -->
                    <button type="submit" name="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
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
