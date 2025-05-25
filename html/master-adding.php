<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('session.php');
include('connection.php');
include('functions.php');

if (isset($_SESSION['main_admin'])) {
    $username = $_SESSION['main_admin'];
}
include('export_data3.php');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_POST['submit'])) {
    $file = $_FILES['excel']['tmp_name'];
    $extension = pathinfo($_FILES['excel']['name'], PATHINFO_EXTENSION);

    if ($extension == 'xlsx' || $extension == 'xls' || $extension == 'csv') {
        $obj = PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $data = $obj->getActiveSheet()->toArray();

        // Initialize a flag to skip the first row (header).
        $headerSkipped = false;
        foreach ($data as $row) {
            // Skip the first row (header).
            if (!$headerSkipped) {
                $headerSkipped = true;
                continue;
            }

            $barcode = $row[0];
            $product_name = mysqli_real_escape_string($conn, $row[1]);
            $sub_cat = $row[2];
            $dep = $row[3];
            $sub_dep = $row[4];
            $class = $row[5];
            $sub_class = $row[6];
            $landing = $row[7];
            $without_gst = $row[8];
            $gst = $row[9];
            $dp = $row[10];
            $mrp = $row[11];
            $margin = $row[12];
            // $rcpr = ((($dp - $landing) / 3) * 1) + $landing;
            
            
                        // Debugging the values and types
            var_dump($dp, $landing);
            
            // Ensure both are numeric
            $dp = floatval($dp);       // or intval($dp) if integers are expected
            $landing = floatval($landing); // or intval($landing)
            
            // Perform the calculation
            $rcpr = ((($dp - $landing) / 3) * 1) + $landing;

            
            
            
            $material_dis = $row[14];
            $meta_key = mysqli_real_escape_string($conn, $row[15]);
            $manufacture_det = mysqli_real_escape_string($conn, $row[16]);
            $image = mysqli_real_escape_string($conn, $row[17]);
            $img_key = mysqli_real_escape_string($conn, $row[18]);
            $vendor = mysqli_real_escape_string($conn, $row[19]);
            $hsncode = mysqli_real_escape_string($conn, $row[20]);
            $pritvi_margin = $row[21];
            $store_margin = $row[22];
            $pm_profit = $row[23];
            $store_profit = $row[24];
            $sgst = $row[25];
            $cgst = $row[26];
            $cess = $row[27];
            $head = $row[28];
            $category = $row[29];

            $timezone = new DateTimeZone("Asia/Kolkata");
            $in_date = new DateTime();
            $in_date->setTimezone($timezone);
            $in_date = $in_date->format('Y-m-d H:i:s');

            $checkQuery = "SELECT * FROM master WHERE product_barcode='$barcode'";
            $checkResult = mysqli_query($conn, $checkQuery);

            if (mysqli_num_rows($checkResult) == 0) {
                $insert_query = "INSERT INTO master (product_barcode, product_name, sub_category, department, sub_department, class, sub_class, landing, without_gst, gst, dp, mrp, margin, rcp, material_dis, meta_keyword, manufacture_details,image, image_meta, vendor_name, hsncode, insert_date, pritvimart_margin, store_margin, pm_profit, store_profit, active, sgst, cgst, cess) VALUES ('$barcode', '$product_name', '$sub_cat', '$dep', '$sub_dep', '$class', '$sub_class', '$landing', '$without_gst', '$gst', '$dp', '$mrp', '$margin', '$rcpr', '$material_dis', '$meta_key', '$manufacture_det',  '$image', '$img_key', '$vendor', '$hsncode', '$in_date', '$pritvi_margin', '$store_margin', '$pm_profit', '$store_profit', 0, '$sgst', '$cgst', '$cess')";

                if (mysqli_query($conn, $insert_query)) {
                    $msg = "File imported successfully.";
                } else {
                    $msg = "Insert Query Error: " . mysqli_error($conn);
                }
            } else {
                echo "Duplicate entry for barcode: $barcode.";
            }
        }
    } else {
        $msg = "Invalid file format.";
    }
}

if (isset($_POST['submit2'])) {
    $file = $_FILES['edit']['tmp_name'];
    $extension = pathinfo($_FILES['edit']['name'], PATHINFO_EXTENSION);

    if ($extension == 'xlsx' || $extension == 'xls' || $extension == 'csv') {
        $obj = PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $data = $obj->getActiveSheet()->toArray();

        // Initialize a flag to skip the first row (header).
        $headerSkipped = false;
        foreach ($data as $row) {
            // Skip the first row (header).
            if (!$headerSkipped) {
                $headerSkipped = true;
                continue;
            }

            $barcode = $row[0];
            $landing = $row[7];
            $gst = $row[9];

            $dp = $row[10];
            $mrp = $row[11];
            $sgst=$row[26];
            $cgst=$row[27];
            $cess=$row[28];

            $timezone = new DateTimeZone("Asia/Kolkata");
            $in_date = new DateTime();
            $in_date->setTimezone($timezone);
            $in_date = $in_date->format('Y-m-d H:i:s');

            $update_query = "UPDATE master SET landing = '$landing', dp = '$dp', mrp = '$mrp',gst='$gst',sgst='$sgst',cgst='$cgst',cess='$cess' WHERE product_barcode = '$barcode' AND active = 0";

            if (mysqli_query($conn, $update_query)) {
                $msg = "File updated successfully.";
            } else {
                $msg = "Update Query Error: " . mysqli_error($conn);
            }
        }
    } else {
        $msg = "Invalid file format.";
    }
}

if (isset($_GET['did']) && ($_GET['did'] != '')) {
    $did = $_GET['did'];
    $sql2 = "UPDATE master SET active=1 WHERE id='$did'";
    $res2 = mysqli_query($conn, $sql2);
    header("Location: master-adding.php");
    echo '<script>window.location.href = "master-adding.php";</script>';
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

    <title>Invoice</title>

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
        .tab-content:not(.doc-example-content) .tab-pane {
            opacity: 1 !important;
        }
    </style>
     <link rel="stylesheet"  href="css/invoice.css">

<script src="js/invoice.js"></script>
    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
    <!-- Template customizer & Theme config files -->
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
                        <h4 class="fw-bold py-3 mb-4">
                            <span class="text-muted fw-light">Admin /</span> Master
                        </h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <h5 class="card-header">Upload Product Data</h5>
                                    <div class="card-body">
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="mb-3">
                                                <label for="formFile" class="form-label">Choose Excel File</label>
                                                <input class="form-control" type="file" id="formFile" name="excel" required />
                                            </div>
                                            <button type="submit" name="submit" class="btn btn-primary">Upload</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <h5 class="card-header">Update Product Data</h5>
                                    <div class="card-body">
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="mb-3">
                                                <label for="formFile" class="form-label">Choose Excel File</label>
                                                <input class="form-control" type="file" id="formFile" name="edit" required />
                                            </div>
                                            <button type="submit" name="submit2" class="btn btn-primary">Upload</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-4">
                                    <h5 class="card-header">Export Options</h5>
                                    <div class="card-body">
                                    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">					
				<button type="submit" id="export_data" name='export_datam' value="Export to excel" class="btn btn-primary btn-lg">Download Master</button>
        <a href="master_upload_template.xlsx" download >  <button type="button" name="export-master-format" class="btn btn-secondary">Download Master Format</button></a>

			</form>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-4">
                                    <h5 class="card-header">Master List</h5>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Barcode</th>
                                                        <th>Name</th>
                                                      
                                                        <th>Landing</th>
                                                        <th>GST</th>
                                                        <th>Without GST</th>
                                                        <th>MRP</th>
                                                       
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $query = "SELECT * FROM master WHERE active = 0";
                                                    $result = mysqli_query($conn, $query);

                                                    while ($row = mysqli_fetch_array($result)) {
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $row['product_barcode']; ?></td>
                                                            <td><?php echo $row['product_name']; ?></td>
                                                           
                                                            <td><?php echo $row['landing']; ?></td>
                                                            <td><?php echo $row['gst']; ?></td>
                                                            <td><?php echo $row['dp']; ?></td>
                                                            <td><?php echo $row['mrp']; ?></td>
                                                           
                                                            <td>
                                                                <a href="master-adding.php?did=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
            <div class="layout-overlay layout-menu-toggle"></div>
        </div>
    </div>
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>
