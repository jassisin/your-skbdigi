<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require('session.php');
include('connection.php');
include('functions.php');

if(isset($_SESSION['main_admin'])){
  $username=$_SESSION['main_admin'];
}



// Fetching categories

// Fetching items from itemstock
$query = "SELECT itemname FROM itemstock";
$itemStockResult = mysqli_query($conn, $query);

$categoryQuery = "SELECT category,head FROM expcategory"; // Replace with your actual query
$categoryResult = mysqli_query($conn, $categoryQuery);

$staff = "SELECT name from staff";

$staffResult = mysqli_query($conn, $staff);


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

      }   $particulars=$row['0'];
      $frequency=$row['1'];
      $unitprice=$row['2'];
      $quantity=$row['3'];
           $total=$row['4'];
           $follow_up_date=$row['5'];
          $remarks=$row['6'];
          $vendor=$row['7'];
          $category=$row['8'];
          $date=$row['9'];
       

        
          $timezone = new DateTimeZone("Asia/Kolkata" );
          $in_date = new DateTime();
          $in_date->setTimezone($timezone );
          $in_date=$in_date->format( 'Y-m-d H:i:s' );
         $insert_query = mysqli_query($conn, "insert into expense_forcast set particulars='$particulars',frequency='$frequency',unitprice='$unitprice',quantity='$quantity',total='$total',follow_up_date='$follow_up_date',remarks='$remarks',vendor ='$vendor',category='$category',date='$date'");




         if($insert_query){

          $msg = "file imported successfully";
         }
         else{
          $msg = "not imported";
         }
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

    <title>Forcast Expenses</title>

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
    <link rel="stylesheet"  href="css/invoice.css">

    <script src="js/invoice.js"></script>
   
    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
    <style>
         .form-group {
            /*margin: 20px 0;*/
            float: right;
        }

        label {
            font-size: 16px;
            margin-bottom: 5px;
            display: block;
        }

        input[type="date"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 200px;
        }
         table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .add-btn {
            cursor: pointer;
            color: #007BFF;
            font-size: 20px;
            font-weight: bold;
        }
        .center-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 18px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .center-btn:hover {
            background-color: #218838;
        }
    </style>
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
              <h4 class="py-3 mb-4"><u>Forcast Expenses</u></h4>

              <hr class="my-3" />
              <a href="edit_forcast_expense.php"><button type="button" class="btn btn-primary" style="margin-top:50px;background: #29913c;">View/Edit Expense Forcast</button></a>
              <a href="expcategory.php"><button type="button" class="btn btn-primary" style="margin-top:50px;margin-left:30px;background: #29913c;">Create Category</button></a>
              <a href="add-vendor.php"><button type="button" class="btn btn-primary" style="margin-top:50px;margin-left:30px;background:green;">Add Vendor</button></a>
              <a href="actual_expense.php"><button type="button" class="btn btn-primary" style="margin-top:50px;margin-left:30px;background: #29913c;">Update Expenses</button></a>

              <!-- Bulk Upload and Download Section -->
              <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 20px; margin-top: 20px; font-size: 16px;">
               
        <form method="post">      
              <input type="file" name="excel" value="" required>

<button class="btn btn-primary btn-lg" type="submit" name="submit2">Add bulk Expense</button>




</form>
                <a href="expenses_format.xlsx" download style="margin: 0;">
                    <button class="btn btn-primary" type="button" style="font-size: 14px; padding: 5px 10px;">Download Expense Forcast Format</button>
                </a>
                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" style="margin: 0;">
                    <button type="submit" id="export_data" name="export_dataex" value="Export to excel" class="btn btn-primary" style="font-size: 14px; padding: 5px 10px;">Download Expenses Forcast</button>
                </form>
              </div>
              

              <!-- Dropdowns for Utility Vendors, Categories, and Item Stocks -->
              <form method="POST" action="your_processing_script.php">
                <!-- Utility Vendors Dropdown -->
                 <!-- Item Stock Dropdown -->
                
            </div>
              </form>
<form id="dataForm" action="submit_forcast_data.php" method="POST">
    <label for="item_stock">Utility Vendors:</label>
                <select name="vendor" id="vendor">
                    <option value="">Select Item</option>
                    <?php while ($item = mysqli_fetch_assoc($itemStockResult)) { ?>
                        <option value="<?= $item['itemname']; ?>"><?= $item['itemname']; ?></option>
                    <?php } ?>
                </select>
                <br/>

                <!-- Categories Dropdown -->
                <label for="category">Category:</label>
                <select name="category" id="category">
                   <?php 
    // Query to fetch category and head from the database
    $categoryQuery = "SELECT category, head FROM expcategory";
    $categoryResult = mysqli_query($conn, $categoryQuery);

    // Loop through each result and display in the dropdown
    while ($category = mysqli_fetch_assoc($categoryResult)) { 
        // Combine category and head with a separator (e.g., '/')
        $categoryValue = $category['category'] . ' / ' . $category['head'];
    ?>
        <option value="<?= $categoryValue; ?>"><?= $categoryValue; ?></option>
    <?php } ?>
                </select>

               
                      <label for="staff">Staff:</label>
               <select name="staff" id="staff">
                    <option value="">Select staff</option>
                    <?php while ($staff = mysqli_fetch_assoc($staffResult)) { ?>
                        <option value="<?= $staff['name']; ?>"><?= $staff['name']; ?></option>
                    <?php } ?>
                </select>

              
                
               <div class="form-group">
                <label for="select_date">Select Date:</label>
                <input type="date" id="date" name="date" required>
                </div>
    <table id="dataTable">
        <thead>
            <tr>
                <th>PARTICULARS</th>
                <th>FREQUENCY</th>
                <th>UNIT PRICE</th>
                <th>QUANTITY</th>
                <th>TOTAL</th>
                <th>FOLLOW UP DATE</th>
                <th>REMARKS</th>
                <th></th> <!-- Column for the + button -->
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="text" name="particulars[]" required></td>
      <td>
                <select name="frequency[]" required>
                    <option value="Weekly">Weekly</option>
                    <option value="Monthly">Monthly</option>
                    <option value="Half-Yearly">Half-Yearly</option>
                    <option value="Yearly">Yearly</option>
                </select>
            </td>
                <td><input type="number" name="unit_price[]" step="0.01" required></td>
                <td><input type="number" name="quantity[]" required></td>
                <td><input type="number" name="total[]" step="0.01" readonly></td>
                <td><input type="date" name="follow_up_date[]" required></td>
                <td><input type="text" name="remarks[]"></td>
                <td><span class="add-btn" onclick="addRow()">+</span></td>
            </tr>
        </tbody>
    </table>

  

    <button type="submit" class="center-btn">Add </button>
</form>

    <script>
        // Function to add a new row to the table
        function addRow() {
            const table = document.getElementById("dataTable").getElementsByTagName('tbody')[0];
            const newRow = table.insertRow(table.rows.length);
            
            // Create cells for each column
            newRow.innerHTML = `
                <td><input type="text" name="id[]" required></td>
                <td><input type="text" name="particulars[]" required></td>
                <td><input type="text" name="frequency[]" required></td>
                <td><input type="number" name="unit_price[]" step="0.01" required></td>
                <td><input type="number" name="quantity[]" required></td>
                <td><input type="number" name="total[]" step="0.01" readonly></td>
                <td><input type="date" name="follow_up_date[]" required></td>
                <td><input type="text" name="remarks[]"></td>
                <td><span class="add-btn" onclick="addRow()">+</span></td>
            `;
        }

        // Update total price whenever unit price or quantity changes
        document.getElementById("dataForm").addEventListener("input", function (e) {
            if (e.target.name === "unit_price[]" || e.target.name === "quantity[]") {
                const row = e.target.closest('tr');
                const unitPrice = parseFloat(row.querySelector('[name="unit_price[]"]').value) || 0;
                const quantity = parseFloat(row.querySelector('[name="quantity[]"]').value) || 0;
                const total = row.querySelector('[name="total[]"]');
                total.value = (unitPrice * quantity).toFixed(2);
            }
        });
    </script>
            </div>
          </div>
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
      </div>
    </div>

    <!-- Core JS -->
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
