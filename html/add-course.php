<?php
require('session.php');

include('connection.php');
include('functions.php');

if(isset($_SESSION['main_admin'])){
  $username=$_SESSION['main_admin'];
}
$edit='';
if(isset($_GET['eid'])&&($_GET['eid']!='')){
    $edit=1;
      $eid=$_GET['eid'];
      $sql2="select * from course where id='$eid' and active=0";
      $res2=mysqli_query($conn,$sql2);
      $row2=mysqli_fetch_array($res2);
      $coursename=($row2['course_name']);
     
      $discription=$row2['discription'];
      $duration=$row2['duration'];
      $subjects=$row2['subjects'];
     
    
    
      }
if(isset($_POST["submit"])){
    if($edit=''){
    $coursename=$_POST['name'];
    
    $coursede=$_POST['coursede'];
    $coursedu=$_POST['coursedu'];
    $sub=$_POST['subjects'];
    $subjects=$conn->real_escape_string($sub);
   
    $timezone = new DateTimeZone("Asia/Kolkata" );
    $date = new DateTime();
    $date->setTimezone($timezone );
    $date=$date->format( 'Y-m-d ' );
    $data = array(
        'course_name' => $coursename,
        'discription' => $coursede,
        'duration' => $coursedu,
        'subjects' => $subjects,
        'addedby' =>$username,
        'indate' => $date,
        'active' => '0',
        );
      insert_row('course',$data,$conn);
    
header("Location: courses.php");
  echo '<script> window.location.href = "courses.php"; </script>';  
    }
    elseif($edit==1){
        $coursenamee=$_POST['name'];
    
    $coursedee=$_POST['coursede'];
    $coursedue=$_POST['coursedu'];
    $sube=$_POST['subjects'];
    $subjectse=$conn->real_escape_string($sube);
        $timezone = new DateTimeZone("Asia/Kolkata" );
        $date = new DateTime();
        $date->setTimezone($timezone );
        $date=$date->format( 'Y-m-d' );
        $data = array(
          'course_name' => $coursenamee,
          'discription' => $coursedee,
          'duration' => $coursedue,
          'subjects' => $subjectse,
          
          'addate' =>$date,
          
          );
          edit_row2('actions',$data,$conn,$id);
          header("Location: course.php");
          echo '<script> window.location.href = "course.php"; </script>';
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

    <title>Tables - Basic Tables | Materio - Bootstrap Material Design Admin Template</title>

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
              <h4 class="py-3 mb-4"> Add Product/Service Manual & Frequently asked questions</h4>

              <!-- Basic Layout -->
              <div class="row">
                <div class="col-xl">
                  <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <!-- <h5 class="mb-0">Basic Layout</h5>
                      <small class="text-muted float-end">Default label</small> -->
                    </div>
                    <div class="card-body">
                      <form method="post">
                        <div class="form-floating form-floating-outline mb-4">
                          <input type="text" class="form-control" id="basic-default-fullname" name="name" placeholder="John Doe" />
                          <label for="basic-default-fullname">Name</label>
                        </div>
                       
                        <div class="form-floating form-floating-outline mb-4">
                          <input type="text" class="form-control" id="basic-default-fullname" name="coursede" placeholder="John Doe" />
                          <label for="basic-default-fullname">Details</label>
                        </div>
                        
                        <div class="form-floating form-floating-outline mb-4">
                          <input type="text" class="form-control" id="basic-default-fullname" name="coursedur" placeholder="John Doe" />
                          <label for="basic-default-fullname">Faq</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                          <input type="text" class="form-control" id="basic-default-fullname" name="subjects" placeholder="John Doe" />
                          <label for="basic-default-fullname">Answer</label>
                          <div id="floatingInputHelp" class="form-text">
                          Add all subjects seperated with a coma(,).
                        </div>
                        </div>
                         <!-- Custom select -->

                          
                       
                          

                          
                        

                        <button type="submit" name="submit" class="btn btn-primary">Send</button>
                        <h6 class="py-3 mb-4"> Please contact SBK to updrage CRM Soft Portal</h6>
                      </form>
                    </div>
                  </div>
                </div>
               
              </div>
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
