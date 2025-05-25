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

// Check if the update button is clicked
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $particulars = $_POST['particulars'];
    $sub_particulars = $_POST['sub_particulars'];
    $amount = $_POST['amount'];

    // Insert the data into the actual-expense table
    $insertSql = "INSERT INTO actual_expense (id, particulars, sub_particulars, amount) VALUES ('$id', '$particulars', '$sub_particulars', '$amount')";
    if (mysqli_query($conn, $insertSql)) {
        echo "Record added successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Fetching categories

// Fetching items from itemstock
$query = "SELECT itemname FROM itemstock";
$itemStockResult = mysqli_query($conn, $query);

$categoryQuery = "SELECT category FROM expcategory"; // Replace with your actual query
$categoryResult = mysqli_query($conn, $categoryQuery);

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

    <title>Actual Expenses</title>

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
       table.table {
    width: 100%;
    border-collapse: collapse;
}

table.table th, table.table td {
    background-color: #f8f9fa; /* Adjust to your desired color */
    padding: 8px;
    border: 1px solid #ddd;
    text-align: left;
}

table.table th {
    background-color: #e9ecef; /* Slightly darker for headers */
    font-weight: bold;
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
              <h4 class="py-3 mb-4"><u>Actual Expenses</u></h4>

              <hr class="my-3" />
              <a href="edit_actual_expense.php"><button type="button" class="btn btn-primary" style="margin-top:50px;background: #29913c;">View/Edit Expense Actual</button></a>
            
              </div>
              

              <!-- Dropdowns for Utility Vendors, Categories, and Item Stocks -->
              <form method="POST" action="your_processing_script.php">
                <!-- Utility Vendors Dropdown -->
                 <!-- Item Stock Dropdown -->
                
            </div>
              </form>

               

              
                
               <div class="form-group">
  <form method="GET" action="">
    <label for="select_date">Select Date:</label>
    <input type="date" id="date" name="date" value="<?php echo isset($_GET['date']) ? $_GET['date'] : ''; ?>" required>
    <button type="submit">Filter</button>
  </form>
</div>
                <br/>
       <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4">Expense Forcast</h4>
            <div class="card">
              <div class="card-body">
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
                      <th>Posted Date</th>
                      <th>Sub Particulars</th>
                            <th>Amount</th>
                            <th>Action</th>
                     
                    </tr>
                  </thead>
                 <tbody>
    <?php
    $selectedDate = isset($_GET['date']) ? $_GET['date'] : '';
$sql = "SELECT * FROM expense_forcast";
    if (!empty($selectedDate)) {
        $sql .= " WHERE date = '$selectedDate'";
    }

    $result2 = mysqli_query($conn, $sql);
    
    if(mysqli_num_rows($result2) > 0) {
        while($row2 = mysqli_fetch_array($result2)) {
    ?>
     <tr>
        <form method="POST" action="">
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
            <td><?php echo $row2['date']; ?></td>
            <td>
                <input type="hidden" name="id" value="<?php echo $row2['id']; ?>">
                 <input type="hidden" name="particulars" value="<?php echo $row2['particulars']; ?>">
                <input type="text" name="sub_particulars" value="" class="form-control">
            </td>
            <td>
                <input type="number" name="amount" value="" class="form-control">
            </td>
            <td>
                <button type="submit" name="update" class="btn btn-sm btn-primary">Update</button>
            </td>
        </form>
                        
    </tr>
 <?php
        }
    }
    ?>
</tbody>
                </table>
              </div>
              </div>
  

    <!--<button type="submit" class="center-btn">Add </button>-->
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
