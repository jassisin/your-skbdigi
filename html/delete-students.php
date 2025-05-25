<?php
require('session.php');

include('connection.php');
if(isset($_SESSION['main_admin'])){
  $username=$_SESSION['main_admin'];
}
require 'vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    if(isset($_POST['submit2'])){
        $file = $_FILES['excel']['tmp_name'];
        $extension = pathinfo($_FILES['excel']['name'], PATHINFO_EXTENSION);
        IF($extension == 'xlsx' || $extension== 'xls' || $extension == 'csv'){
            $obj = PhpOffice\PhpSpreadsheet\IOFactory::load($file);
            $data = $obj->getActiveSheet()->toArray();
            // Initialize a flag to skip the first row (header).
        $headerSkipped = false;
            foreach($data as $row){
                 // Skip the first row (header).
            if (!$headerSkipped) {
                $headerSkipped = true;
                continue;

            }   
                 $studentname=$row['0'];
                 $parentname=$row['1'];
                $dob=$row['2'];
                $address=$row['3'];
                $pphone=$row['4'];
                $pemail=$row['5'];
                $course=$row['6'];
                $doa=$row['7'];
                $coursedet=$row['8'];
                $pusername=$row['9'];
                $ppassword=$row['10'];
                $susername=$row['11'];
                $spassword=$row['12'];


                $timezone = new DateTimeZone("Asia/Kolkata" );
                $in_date = new DateTime();
                $in_date->setTimezone($timezone );
                $in_date=$in_date->format( 'Y-m-d H:i:s' );
               $insert_query = mysqli_query($conn, "select * from details where active=0");
              $row=mysqli_fetch_array($conn,$insert_query);
              $id=$row['id'];
              $sname=$row['student_name'];
              $pname=$row['parent_name'];
              $dobt=$row['dob'];
              $addresst=$row['address'];
              $pphonet=$row['parent_phone'];
              $pemailt=$row['parent_email'];
              $courset=$row['course'];
              $doat=$row['doa'];
              $coursedt=$row['course_details'];
              $pusernamet=$row['parents_username'];
              $ppasswordt=$row['parents_password'];
              $susernamet=$row['student_username'];
              $spasswordt=$row['student_password'];
              if($studentname==$sname && $parentname==$pname && $dob==$dobt && $address==$addresst && $pphone==$pphonet && $pemailt==$pemail && $courset==$course && $doa==$doat && $coursedet==$coursedt && $pusername==$pusernamet && $ppassword==$ppasswordt && $susername==$susernamet && $spassword==$spasswordt){
                $insert_query2 = mysqli_query($conn, "update details set active=1 where id='$id'");
              }
              //  if($insert_query)
              //  {

              //   $msg = "file imported successfully";
              //  }
              //  else{
              //   $msg = "not imported";
              //  }
            }
        }
        else{
            $msg = "invalid file";
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

    <title>Bulk delete students</title>

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
            id="layout-navbar">
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
                      <a class="dropdown-item" href="javascript:void(0);">
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

          <!-- Content wrapper -->
          <div class="content-wrapper">
           
          <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="py-3 mb-4"> Customer Chats</h4>

              <form action="" enctype="multipart/form-data" method="post">
                  <input type="file" name="excel" value="" required>

                    <button class="btn btn-primary btn-lg" type="submit" name="submit">Upload Chat</button>

                    


                    </form><br>
                    <a href="address-format.xlsx" download style=""><button class="btn btn-primary btn-lg" type="button" >Format for Download Customer Client Conversation</button></a><br><br>

              <!-- Basic Layout -->
           
            </div>
           
            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl">
                <div
                  class="footer-container d-flex align-items-center justify-content-between py-3 flex-md-row flex-column">
                  <div class="text-body mb-2 mb-md-0">
                    ©
                    <script>
                      document.write(new Date().getFullYear());
                    </script>
                   
                  </div>
                  <div class="d-none d-lg-inline-block">
                    <a href="" class="footer-link me-3" target="_blank">SBK Details</a>
                    
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

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
