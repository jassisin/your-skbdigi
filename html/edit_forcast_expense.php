<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require('session.php');
include('connection.php');
include('functions.php');

if(isset($_SESSION['main_admin'])){
  $username = $_SESSION['main_admin'];
}

$sql = "SELECT * FROM expense_forcast";
$result = mysqli_query($conn, $sql);


?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Edit Expense Forcast</title>
    <meta name="description" content="" />
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/vendor/fonts/materialdesignicons.css" />
    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="css/invoice.css">
    <script src="js/invoice.js"></script>
    <!-- Page CSS -->
    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
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
          <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar" style="background-color: <?php echo $page_heading_color; ?>;">
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="mdi mdi-menu mdi-24px"></i>
              </a>
            </div>
            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <li class="nav-item lh-1 me-3">
                  <?=$username?>
                </li>
                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      <img src="../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end mt-3 py-2">
                    <li><a class="dropdown-item" href="logout.php"><i class="mdi mdi-power me-1 mdi-20px"></i> <span class="align-middle">Log Out</span></a></li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>
          <!-- / Navbar -->
          
          <!-- Content wrapper -->
          <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4">Expense Forcast</h4>
            <div class="card">
              <div class="card-body">
               <div style="overflow-x: auto;">
  <table class="table table-bordered" id="expenseTable">
      <thead>
                    <tr>
                      <th>ID</th>
                      <th>Particulars</th>
                      <th>Frequency</th>
                      <th>Unit Price</th>
                      <th>Quantity</th>
                      <th>Total</th>
                     <th>Follow up date</th>
                      <th>Remarks</th>
                      <th>Vendor</th>
                      <th>Category</th>
                                            <th>Staff</th>
                      <th>Posted Date</th>
                       <th>Actions</th>
                     
                    </tr>
                  </thead>
                 <tbody>
    <?php
$sql2 = "SELECT * from expense_forcast";   
 $result2 = mysqli_query($conn, $sql2);
    
    if(mysqli_num_rows($result2) > 0) {
        while($row2 = mysqli_fetch_array($result2)) {
    ?>
    <tr>
      <td><?php echo $row2['id']; ?></td>
      <td><?php echo $row2['particulars']; ?></td>
      <td><?php echo $row2['frequency']; ?></td>
      <td><?php echo $row2['unitprice']; ?></td>
      <td><?php echo $row2['quantity']; ?></td>
      <td><?php echo $row2['total']; ?></td>
      <td><?php echo $row2['follow_up_date']; ?></td>
      <td><?php echo $row2['remarks']; ?></td>
      <td><?php echo $row2['vendor']; ?></td>
      <td><?php echo $row2['category']; ?></td>
          <td><?php echo $row2['staff']; ?></td>
      <td><?php echo $row2['date']; ?></td>  
      <td>
      <!-- Edit Button -->
      <a href="edit_expense.php?id=<?php echo $row2['id']; ?>" >Edit</a>
      <br/>
      <!-- Delete Button -->
      <a href="delete_expense.php?id=<?php echo $row2['id']; ?>" 
         onclick="return confirm('Are you sure you want to delete this expense?');" 
         >Delete</a>
    </td>
      
     
      
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

          <!-- / Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>
      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>
    <script src="../assets/js/main.js"></script>

  </body>
</html> 