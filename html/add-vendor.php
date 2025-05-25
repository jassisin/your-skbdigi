<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('session.php');

include('connection.php');

if(isset($_SESSION['main_admin'])){
  $username=$_SESSION['main_admin'];
}
if(isset($_GET['id']) && ($_GET['id']!='')){
  $id=$_GET['id'];
$sql5="update itemstock set active=1 where id='$id'";
$res5=mysqli_query($conn,$sql5);
header("Location: add-vendor.php"); // Redirecting To Other Page
echo '<script> window.location.href = "add-vendor.php"; </script>';

}
if (isset($_POST['submit'])) {
    // Sanitize input to prevent SQL Injection
    $vname = mysqli_real_escape_string($conn, $_POST['vname']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mob']);
    $gst = mysqli_real_escape_string($conn, $_POST['gst']);
    $remark = mysqli_real_escape_string($conn, $_POST['rmk']);
    
    // Validate inputs (example: ensure fields are not empty)
    if (!empty($vname) && !empty($mobile) && !empty($gst) && !empty($remark)) {
        // Insert into the database
        $sql = "INSERT INTO itemstock (itemname, mobile, gstno, remark) VALUES ('$vname', '$mobile', '$gst', '$remark')";
        $res = mysqli_query($conn, $sql);

     
    } else {
        echo "<script>alert('Please fill in all fields');</script>";
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

    <title>Add vendor</title>

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

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <!-- <h4 class="py-3 mb-4"><span class="text-muted fw-light">Tables /</span> Basic Tables</h4> -->

              <div class="col-md-6">
                  <div class="card mb-4">
                    <h5 class="card-header">Add vendor</h5>
                    <form method="post" >
                    <div class="card-body">
                      <div>
                        <label for="defaultFormControlInput" class="form-label">Vendor Name</label>
                        <input
                          type="text"
                          class="form-control"
                          id="defaultFormControlInput"
                          aria-describedby="defaultFormControlHelp" name="vname" />
                           <label for="defaultFormControlInput" class="form-label">Mobile</label>
                        <input
                          type="text"
                          class="form-control"
                          id="defaultFormControlInput"
                          aria-describedby="defaultFormControlHelp" name="mob" />
                           <label for="defaultFormControlInput" class="form-label">GST Number</label>
                        <input
                          type="text"
                          class="form-control"
                          id="defaultFormControlInput"
                          aria-describedby="defaultFormControlHelp" name="gst" />
                           <label for="defaultFormControlInput" class="form-label">Remarks</label>
                        <input
                          type="text"
                          class="form-control"
                          id="defaultFormControlInput"
                          aria-describedby="defaultFormControlHelp" name="rmk" />
                         <button type="submit" name="submit" class="btn btn-primary" style="margin-top:50px;background: #29913c;">Add</button>
                      </div>
                    </div>
                    </form>
                  </div>
                </div>
              <!-- Responsive Table -->
              <div class="card">
                <h5 class="card-header">Vendor Names</h5>
                <div class="table-responsive text-nowrap">
                  <table class="table">
                   <thead>
  <tr class="text-nowrap">
    <th>#</th>
    <th>Vendor Name</th>
    <th>Mobile</th>
    <th>GST Number</th>
    <th>Remark</th>
    <th>Action</th>
  </tr>
</thead>
<tbody>
  <?php
  $sql2 = "SELECT * FROM itemstock WHERE active=0";
  $res = mysqli_query($conn, $sql2);
  while ($row = mysqli_fetch_array($res)) {
  ?>
    <tr>
      <th scope="row"><?=$row['id']?></th>
      <td><?=$row['itemname']?></td>
      <td><?=$row['mobile']?></td>
      <td><?=$row['gstno']?></td>
      <td><?=$row['remark']?></td>
    
    
      <td>
          
            <a href="edit-vendor.php?id=<?=$row['id']?>" class="btn btn-warning btn-sm" onclick="editVendor(<?=$row['id']?>)">Edit</a>
          <a href="add-vendor.php?id=<?=$row['id']?>" class="btn btn-danger btn-sm" onClick="return confirm('Are you sure you want to delete?')">Delete</a></td>
    </tr>
  <?php
  }
  ?>
</tbody>



                  </table>
                </div>
              </div>
              <!--/ Responsive Table -->
            </div>
            <!-- / Content -->

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
