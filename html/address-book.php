<?php

require('session.php');
include('connection.php');


if(isset($_SESSION['main_admin'])){
  $username=$_SESSION['main_admin'];
}

include('export_data3.php');
if(isset($_GET['did']) && ($_GET['did']!='')) {
  $did=$_GET['did'];
  $sql2="UPDATE addressbook SET active=1 WHERE id='$did'";
  $res2=mysqli_query($conn, $sql2);
  header("Location: address-book.php");
  echo '<script> window.location.href = "address-book.php"; </script>';
  exit;
}

// Retrieve column visibility settings
$query = "SELECT column_name, is_visible FROM address_visibility";
$stmt = mysqli_query($conn, $query);

if (!$stmt) {
    die("Query failed: " . mysqli_error($conn));
}

$columnVisibility = [];
while ($row = mysqli_fetch_assoc($stmt)) {
    $columnVisibility[$row['column_name']] = (int)$row['is_visible'];
}

mysqli_free_result($stmt);

// Ensure 'id' column is always selected
$columnVisibility['id'] = 1; 

$visibleColumns = array_keys(array_filter($columnVisibility));
if (empty($visibleColumns)) {
    die('No columns to display');
}

$columnsToSelect = implode(', ', $visibleColumns);
if($username=='admin1'){
  $query = "SELECT $columnsToSelect FROM addressbook where active=0";
}
else{
$query = "SELECT $columnsToSelect FROM addressbook where active=0 and addedby='$username'";
}
$stmt = mysqli_query($conn, $query);

if (!$stmt) {
    die("Query failed: " . mysqli_error($conn));
}

$data = [];
while ($row = mysqli_fetch_assoc($stmt)) {
    $data[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Address - List</title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/vendor/fonts/materialdesignicons.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <script src="../assets/vendor/js/helpers.js"></script>
    <script src="../assets/js/config.js"></script>
</head>

<body>
<div class="layout-wrapper layout-content-navbar">
  <div class="layout-container">
    <?php include("header.php"); ?>
    <div class="layout-page">
      <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar"  style="background-color: <?php echo $page_heading_color; ?>;">
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
          <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="mdi mdi-menu mdi-24px"></i>
          </a>
        </div>
        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
          <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center">
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
                  <a class="dropdown-item
                  pb-2 mb-1" href="#">
                    <div class="d-flex align-items-center">
                      <div class="flex-shrink-0 me-2 pe-1">
                        <div class="avatar avatar-online">
                          <img src="../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                        </div>
                      </div>
                      <div class="flex-grow-1">
                        <small class="text-muted">Admin</small>
                      </div>
                    </div>
                  </a>
                </li>
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
        <div class="container-xxl flex-grow-1 container-p-y">
          <div class="card">
            <h5 class="card-header">Address Book</h5>
            <div class="table-responsive text-nowrap">
              <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">					
                <button type="submit" id="export_data" name='export_data3' value="Export to excel" class="btn btn-primary btn-lg">Download Address Book</button>
              </form>
              <table class="table">
                <thead>
                  <tr class="text-nowrap">
                    <?php foreach ($visibleColumns as $column): ?>
                      <th style="color:#fff"><?php echo ucfirst($column); ?></th>
                    <?php endforeach; ?>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($data as $row): ?>
                    <tr>
                      <?php foreach ($visibleColumns as $column): ?>
                        <td><?php echo htmlspecialchars($row[$column]); ?></td>
                      <?php endforeach; ?>
                      <?php if (isset($row['id'])): ?>
                        <td>
                          <a href="actions.php?id=<?=$row['id']?>">Add Action</a>
                         
                        </td>
                        <td>
                          <a href="address.php?eid=<?=$row['id']?>"><i class="mdi mdi-pencil-outline text-info mdi-24px me-1"></i></a>
                         
                        </td>
                        
                        <td>
                          <a href="address-book.php?did=<?=$row['id']?>">Delete</a>
                         
                        </td>
                      <?php else: ?>
                        <td>Invalid ID</td>
                      <?php endif; ?>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <footer class="content-footer footer bg-footer-theme">
          <div class="container-xxl" style="background-color: <?php echo $footer_color; ?>;">
            <div class="footer-container d-flex align-items-center justify-content-between py-3 flex-md-row flex-column">
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
    </div>
  </div>
  <div class="layout-overlay layout-menu-toggle"></div>
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
