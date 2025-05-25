<?php
require('session.php');
include('connection.php');
if(isset($_SESSION['main_admin'])){
  $username=$_SESSION['main_admin'];
} 

// Fetch current admin users
$admins = mysqli_query($conn, "SELECT * FROM admin_log");

// Fetch current page settings
$page_settings = mysqli_query($conn, "SELECT * FROM page_settings LIMIT 1");
$settings = mysqli_fetch_assoc($page_settings);

// Fetch column headings from database
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Super Admin</title>

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
<div class="layout-wrapper layout-content-navbar">
  <div class="layout-container">
    <?php include("header.php"); ?>
    <div class="layout-page">
      <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar" style="background-color: <?php echo $page_heading_color; ?>;">
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
          <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="mdi mdi-menu mdi-24px"></i>
          </a>
        </div>
        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse" >
          <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center" >
              <i class="mdi mdi-magnify mdi-24px lh-0"></i>
              <input type="text" class="form-control border-0 shadow-none bg-body" placeholder="Search..." aria-label="Search..." />
            </div>
          </div>
          <ul class="navbar-nav flex-row align-items-center ms-auto">
            <li class="nav-item lh-1 me-3">
              <?=$username?>
            </li>
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
              <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
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
          </ul>
        </div>
      </nav>
      <div class="content-wrapper">
        <div class="container-xxl">
          <div class="row">
            <div class="col-md-12">
              <h4>Super Admin Panel</h4>
             
              <div class="card mb-4">
                <div class="card-header">
                  <h5>Page Settings</h5>
                </div>
                <div class="card-body">
                  <form action="manage_settings.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                      <label for="page_heading" class="form-label">Page Heading</label>
                      <input type="text" id="page_heading" name="page_heading" class="form-control" value="<?=$settings['page_heading']?>" required>
                    </div>
                    <div class="mb-3">
                      <label for="page_heading_color" class="form-label">Page Heading Color</label>
                      <input type="color" id="page_heading_color" name="page_heading_color" class="form-control" value="<?=$settings['page_heading_color']?>" required>
                    </div>
                    <div class="mb-3">
                      <label for="logo_image" class="form-label">Logo Image</label>
                      <input type="file" id="logo_image" name="logo_image" class="form-control">
                    </div>
                    <div class="mb-3">
                      <label for="footer_color" class="form-label">Footer Color</label>
                      <input type="color" id="footer_color" name="footer_color" class="form-control" value="<?=$settings['footer_color']?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <footer class="content-footer footer bg-footer-theme">
          <div class="container-xxl d-flex align-items-center justify-content-between py-3" style="background-color: <?php echo $footer_color; ?>;">
            <div class="text-body mb-2 mb-md-0">
              © <script>document.write(new Date().getFullYear());</script>
            </div>
            <div class="d-none d-lg-inline-block">
              <a href="" class="footer-link me-3" target="_blank">SBK Details</a>
            </div>
          </div>
        </footer>
      </div>
    </div>
    <div class="layout-overlay layout-menu-toggle"></div>
  </div>
</div>
<script src="../assets/vendor/libs/jquery/jquery.js"></script>
<script src="../assets/vendor/libs/popper/popper.js"></script>
<script src="../assets/vendor/js/bootstrap.js"></script>
<script src="../assets/vendor/libs/node-waves/node-waves.js"></script>
<script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="../assets/vendor/js/menu.js"></script>
<script src="../assets/js/main.js"></script>
<script async defer src="https://buttons.github.io/buttons.js"></script>
</body>
</html>
