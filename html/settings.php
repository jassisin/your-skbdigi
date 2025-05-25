<?php
require('session.php');
include('connection.php'); // Assuming this file establishes the connection and sets $conn

if (isset($_SESSION['main_admin'])) {
    $username = $_SESSION['main_admin'];
}

if (isset($_POST['submit'])) {
    $columns = $_POST['columns']; // Array of column names to show
    $query = "UPDATE actions_visibility SET is_visible = CASE column_name ";
    $params = [];
    foreach ($columns as $column) {
        $query .= "WHEN ? THEN 1 ";
        $params[] = $column;
    }
    $query .= "ELSE 0 END";

    // Prepare and execute the statement with mysqli
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        // Dynamically bind the parameters
        $types = str_repeat('s', count($params));
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        die("Query preparation failed: " . mysqli_error($conn));
    }
}

if (isset($_POST['submit2'])) {
  $columns = $_POST['columns']; // Array of column names to show
  $query = "UPDATE address_visibility SET is_visible = CASE column_name ";
  $params = [];
  foreach ($columns as $column) {
      $query .= "WHEN ? THEN 1 ";
      $params[] = $column;
  }
  $query .= "ELSE 0 END";

  // Prepare and execute the statement with mysqli
  $stmt = mysqli_prepare($conn, $query);
  if ($stmt) {
      // Dynamically bind the parameters
      $types = str_repeat('s', count($params));
      mysqli_stmt_bind_param($stmt, $types, ...$params);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
  } else {
      die("Query preparation failed: " . mysqli_error($conn));
  }
}


if (isset($_POST['submit3'])) {
  $columns = $_POST['columns']; // Array of column names to show
  $query = "UPDATE task_visibility SET is_visible = CASE column_name ";
  $params = [];
  foreach ($columns as $column) {
      $query .= "WHEN ? THEN 1 ";
      $params[] = $column;
  }
  $query .= "ELSE 0 END";

  // Prepare and execute the statement with mysqli
  $stmt = mysqli_prepare($conn, $query);
  if ($stmt) {
      // Dynamically bind the parameters
      $types = str_repeat('s', count($params));
      mysqli_stmt_bind_param($stmt, $types, ...$params);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
  } else {
      die("Query preparation failed: " . mysqli_error($conn));
  }
}
// Retrieve column visibility settings
$query = "SELECT column_name, is_visible FROM actions_visibility";
$stmt = mysqli_query($conn, $query);

if (!$stmt) {
    die("Query failed: " . mysqli_error($conn));
}

$columnVisibility = [];
while ($row = mysqli_fetch_assoc($stmt)) {
    $columnVisibility[$row['column_name']] = (int)$row['is_visible'];
}

mysqli_free_result($stmt);

// Retrieve column visibility settings address
$query2 = "SELECT column_name, is_visible FROM address_visibility";
$stmt2 = mysqli_query($conn, $query2);

if (!$stmt2) {
    die("Query failed: " . mysqli_error($conn));
}

$columnVisibility2 = [];
while ($row2 = mysqli_fetch_assoc($stmt2)) {
    $columnVisibility2[$row2['column_name']] = (int)$row2['is_visible'];
}

mysqli_free_result($stmt2);

// Retrieve column visibility settings task
$query3 = "SELECT column_name, is_visible FROM task_visibility";
$stmt3 = mysqli_query($conn, $query3);

if (!$stmt3) {
    die("Query failed: " . mysqli_error($conn));
}

$columnVisibility3 = [];
while ($row3 = mysqli_fetch_assoc($stmt3)) {
    $columnVisibility3[$row3['column_name']] = (int)$row3['is_visible'];
}

mysqli_free_result($stmt3);


?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Tables - Basic Tables | Materio - Bootstrap Material Design Admin Template</title>
    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap" rel="stylesheet" />

    <link rel="stylesheet" href="../assets/vendor/fonts/materialdesignicons.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
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
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <h5 class="card-header">Hide and unhide action list columns</h5>
                            <div class="row row-bordered g-0">
                                <div class="col-md p-4">
                                    
                                    
                                        <form method="post">
                        <?php foreach ($columnVisibility as $column => $isVisible): ?>
                            <label>
                                <input type="checkbox" name="columns[]" value="<?php echo $column; ?>" <?php echo $isVisible ? 'checked' : ''; ?>>
                                <?php echo ucfirst($column); ?>
                            </label><br>
                        <?php endforeach; ?>
                        <button type="submit" name="submit">Save</button>
                    </form>

                                   
                                </div>
                               
                            </div>
                            <hr class="m-0" />
                        </div>
                    </div>





                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <h5 class="card-header">Hide and unhide address list columns</h5>
                            <div class="row row-bordered g-0">
                                <div class="col-md p-4">
                                    
                                    
                                        <form method="post">
                        <?php foreach ($columnVisibility2 as $column2 => $isVisible2): ?>
                            <label>
                                <input type="checkbox" name="columns[]" value="<?php echo $column2; ?>" <?php echo $isVisible2 ? 'checked' : ''; ?>>
                                <?php echo ucfirst($column2); ?>
                            </label><br>
                        <?php endforeach; ?>
                        <button type="submit" name="submit2">Save</button>
                    </form>

                                   
                                </div>
                               
                            </div>
                            <hr class="m-0" />
                        </div>
                    </div>


                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <h5 class="card-header">Hide and unhide task list columns</h5>
                            <div class="row row-bordered g-0">
                                <div class="col-md p-4">
                                    
                                    
                                        <form method="post">
                        <?php foreach ($columnVisibility3 as $column3 => $isVisible3): ?>
                            <label>
                                <input type="checkbox" name="columns[]" value="<?php echo $column3; ?>" <?php echo $isVisible3 ? 'checked' : ''; ?>>
                                <?php echo ucfirst($column3); ?>
                            </label><br>
                        <?php endforeach; ?>
                        <button type="submit" name="submit3">Save</button>
                    </form>

                                   
                                </div>
                               
                            </div>
                            <hr class="m-0" />
                        </div>
                    </div>


                    <!-- <form method="post">
                        <?php foreach ($columnVisibility as $column => $isVisible): ?>
                            <label>
                                <input type="checkbox" name="columns[]" value="<?php echo $column; ?>" <?php echo $isVisible ? 'checked' : ''; ?>>
                                <?php echo ucfirst($column); ?>
                            </label><br>
                        <?php endforeach; ?>
                        <button type="submit">Save</button>
                    </form> -->

                   

                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl"style="background-color: <?php echo $footer_color; ?>;">
                            <div class="footer-container d-flex align-items-center justify-content-between py-3 flex-md-row flex-column">
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
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
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
