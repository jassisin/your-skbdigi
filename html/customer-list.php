<?php
require('session.php');
include('connection.php');
include('functions.php');
if(isset($_SESSION['main_admin'])){
  $username=$_SESSION['main_admin'];
}
if(isset($_GET['did'])&&($_GET['did']!=''))
{
  $id=$_GET['did'];
  $sql2="update customer set active=1 where id='$id'";
  $res2=mysqli_query($conn,$sql2);
  header("Location: customer-list.php");
 echo '<script> window.location.href = "customer-list.php"; </script>';
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

            $name = $row[0];
            $email = $row[1];
            $number = $row[2];
            $pin = $row[3];
            $address = $row[4];
         
            $timezone = new DateTimeZone("Asia/Kolkata");
            $in_date = new DateTime();
            $in_date->setTimezone($timezone);
            $in_date = $in_date->format('Y-m-d H:i:s');

        
            if (mysqli_num_rows($checkResult) == 0) {
                $insert_query = "INSERT INTO customer (Fullname, Email ,Mobile_no,Pincode,Cusaddress,Add_date,active) VALUES ('$name','$email','$number','$pin','$address','$in_date',0)";

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

    <title>Customer</title>

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
    <link rel="stylesheet"  href="css/invoice.css">

<script src="js/invoice.js"></script>
    <!-- Helpers -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript"></script>
     <script>
    $(document).ready(function() {
    load_data();

    function load_data(query = '', date_from = '', date_to = '') {
        $.ajax({
            url: "fetch_entry.php",
            method: "POST",
            data: { query: query, date_from: date_from, date_to: date_to },
            success: function(data) {
                $('#result').html(data);
            }
        });
    }

    $('#search').keyup(function() {
        var search = $(this).val();
        var date_from = $('#date_from').val();
        var date_to = $('#date_to').val();
        load_data(search, date_from, date_to);
    });

    $('#filter_button').click(function() {
        var search = $('#search').val();
        var date_from = $('#date_from').val();
        var date_to = $('#date_to').val();
        load_data(search, date_from, date_to);
    });

    $('#download_button').click(function() {
        download_table_as_csv('orders_table');
    });

    function download_table_as_csv(table_id) {
        var table = document.getElementById(table_id);
        var rows = table.querySelectorAll('tr');
        var csv = [];
        
        rows.forEach(function(row) {
            var cols = row.querySelectorAll('td, th');
            var row_csv = [];
            cols.forEach(function(col) {
                row_csv.push(col.innerText);
            });
            csv.push(row_csv.join(','));
        });
        
        var csv_string = csv.join('\n');
        var filename = 'orders_' + new Date().toLocaleDateString() + '.csv';
        var link = document.createElement('a');
        link.style.display = 'none';
        link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv_string));
        link.setAttribute('download', filename);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
});
</script>
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
                    aria-label="Search..." id="search"/>
                </div>
              </div>
              <!-- /Search -->

              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Place this tag where you want the button to render. -->
                <li class="nav-item lh-1 me-3">

                  <?=$username;?>
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
            <!-- Content -->
<!-- Date filters -->



            <div class="container-xxl flex-grow-1 container-p-y">
              <!-- <h4 class="py-3 mb-4"><span class="text-muted fw-light">Tables /</span> Basic Tables</h4> -->
              <div class="container-xxl flex-grow-1 container-p-y">

              
              <!-- Responsive Table -->
              <div class="card">
                <h5 class="card-header">Customer List</h5>
                <div class="table-responsive text-nowrap">
                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">					
				<button type="submit" id="export_data" name='export_donor' value="Export to excel" class="btn btn-primary btn-lg">Download Donor list</button>
        <a href="donor-format.xlsx" download >  <button type="button" name="export-master-format" class="btn btn-secondary">Download UPload format</button></a>

			</form>
                <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <h5 class="card-header">Upload Donor Data</h5>
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
                            
                        </div>
                  <table class="table">
                    <thead>
                      <tr class="text-nowrap">
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile Number</th>
                        <th>Pincode</th>
                        <th>Address</th>
                       

                       
                      </tr>
                    </thead>
                    <tbody>
    <?php
$sql2 = "SELECT * from customer where active=0";   
 $result2 = mysqli_query($conn, $sql2);
    
    if(mysqli_num_rows($result2) > 0) {
        while($row2 = mysqli_fetch_array($result2)) {
    ?>
        <tr>
            <th scope="row"><?= $row2['id'] ?></th>
            <td><?= $row2['Fullname'] ?></td>
            <td><?= $row2['Email'] ?></td>
            <td><?= $row2['Mobile_no'] ?></td>
            <td><?= $row2['Pincode'] ?></td>
            <td><?= $row2['Cusaddress'] ?></td>
          
            <td><a href="customer-edit.php?eid=<?= $row2['id'] ?>">Edit</a></td>
            <td><a href="customer-list.php?did=<?= $row2['id'] ?>" onClick="return confirm('Are you sure you want to delete?')" style="color: purple; padding-left: 60px;">Delete</a></td>
        </tr>
    <?php
        }
    }
    ?>
</tbody>
</table>
</div>
              </div>
              </div>
              </div>
             
            <!-- / Content -->
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

    <!-- <div class="buy-now">
      <a
        href="https://themeselection.com/item/materio-bootstrap-html-admin-template/"
        target="_blank"
        class="btn btn-danger btn-buy-now"
        >Upgrade to Pro</a
      >
    </div> -->

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
