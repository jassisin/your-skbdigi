<?php
// Ensure session and connection are available
if (!isset($username)) {
    if(isset($_SESSION['main_admin'])){
        $username = $_SESSION['main_admin'];
    } else {
        $username = 'Guest';
    }
}

// Set default colors if not defined
if (!isset($page_heading_color)) {
    $page_heading_color = '#6f42c1'; // Default purple
}
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    
    <title><?php echo isset($page_title) ? $page_title : 'Admin Panel'; ?></title>
    <meta name="description" content="<?php echo isset($page_description) ? $page_description : ''; ?>" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/vendor/fonts/materialdesignicons.css" />

    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="../assets/vendor/libs/node-waves/node-waves.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page-specific CSS -->
    <?php if (isset($additional_css)) echo $additional_css; ?>

    <style>
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
    <script src="../assets/js/config.js"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu/Sidebar -->
            <?php if (file_exists("header.php")) include("header.php"); ?>
            
            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar" style="background-color: <?php echo $page_heading_color; ?>;">
                    <div class="container-xxl d-flex align-items-center w-100">
                        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                                <i class="mdi mdi-menu mdi-24px"></i>
                            </a>
                        </div>

                        <div class="navbar-nav-right d-flex align-items-center w-100" id="navbar-collapse">
                            <!-- Search -->
                            <div class="navbar-nav align-items-center flex-grow-1">
                                <div class="nav-item d-flex align-items-center w-100">
                                    <i class="mdi mdi-magnify mdi-24px lh-0 me-2"></i>
                                    <input type="text" class="form-control border-0 shadow-none bg-body" placeholder="Search..." aria-label="Search..." style="max-width: 300px;" />
                                </div>
                            </div>
                            <!-- /Search -->

                            <ul class="navbar-nav flex-row align-items-center ms-auto">
                                <!-- User Name -->
                                <li class="nav-item lh-1 me-3 d-none d-sm-block">
                                    <span class="text-white fw-medium"><?=$username?></span>
                                </li>

                                <!-- User -->
                                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                    <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="avatar avatar-online">
                                            <img src="../assets/img/avatars/1.png" alt="User Avatar" class="w-px-40 h-auto rounded-circle" />
                                        </div>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end mt-3 py-2">
                                        <li class="d-block d-sm-none">
                                            <div class="dropdown-item-text">
                                                <span class="fw-medium text-dark"><?=$username?></span>
                                            </div>
                                        </li>
                                        <li class="d-block d-sm-none">
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
                    </div>
                </nav>

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <!-- Page content starts here -->