<?php
require('session.php');

include('connection.php');
if(isset($_SESSION['main_admin'])){
    $username=$_SESSION['main_admin'];
  }
$var='';
 
if(isset($_POST['submit1'])){
    $head=$_POST['head'];
    $query5="insert into  exphead(head,active) values('$head',0)";  
    $result5=mysqli_query($conn,$query5);
    header("Location: expcategory.php");
    echo '<script> window.location.href = "expcategory.php"; </script>';
} 

if(isset($_POST['submit'])){
$head = mysqli_real_escape_string($conn, $_POST['head_id']);
  $category = mysqli_real_escape_string($conn, $_POST['category']);
  echo "Head: " . $head . "<br>";
    echo "Category: " . $category . "<br>";
    $query5="insert into  expcategory(category,head,active) values('$category','$head',0)";  
    $result5=mysqli_query($conn,$query5);
    // header("Location: expcategory.php");
    // echo '<script> window.location.href = "expcategory.php"; </script>';
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

    <title>Edit</title>

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
          </nav>

          <!-- / Navbar -->






          
              <!-- Bootstrap Table with Header - Dark -->
    <div class="card">
                <h5 class="card-header">Head</h5>
                <div class="table-responsive text-nowrap">
                  <table class="table">
                    <thead class="table-dark">
                      <tr>
                        <th>Head</th>
                        <th>Edit</th>
                         <th>Delete</th> <!-- New column for Delete -->
                       
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      <?php
                      $sql3="select * from exphead";
                      $res3=mysqli_query($conn,$sql3);
                      while ($row3 = $res3->fetch_assoc()) {
                      ?>
                      <tr>
                        <td>
                          <i class="mdi mdi-wallet-travel mdi-20px text-danger me-3"></i
                          ><span class="fw-medium"><?=$row3['head']?></span>
                        </td>
                        <td> <a class="dropdown-item" href="expheadedit.php?id=<?=$row3['id']?>"
                                ><i class="mdi mdi-pencil-outline me-1"></i> Edit</a
                              ></td>
                              <td>
                            <!-- Delete button -->
                            <a class="dropdown-item" href="expheaddelete.php?id=<?= $row3['id'] ?>" 
                               onclick="return confirm('Are you sure you want to delete this item?');">
                                <i class="mdi mdi-delete-outline me-1"></i> Delete
                            </a>
                        </td>
                     
                      </tr>
                      <?php } ?>
                      
                    
                    
                    </tbody>
                  </table>
                </div>
              </div>
            
            
            
            
            

            <!-- Default -->
            <div class="col-md">
                  <div class="card mb-4">
                    <h5 class="card-header">Add expenses head</h5>
                    <div class="card-body">
                        <form method="post">
                      <div>
                    
                        <input
                          type="text"
                          class="form-control"
                          id="defaultFormControlInput"
                          placeholder=""
                          aria-describedby="defaultFormControlHelp" name="head" value=""/><br>
                          <button type="submit" name="submit1" class="btn btn-primary">Submit</button>

                      </div>
                      </form>
                    </div>
                  </div>
                </div>
                
                
                
                
                
                
                
                
          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
    <!-- Bootstrap Table with Header - Dark -->
    <div class="card">
                <h5 class="card-header">Category</h5>
                <div class="table-responsive text-nowrap">
                  <table class="table">
                    <thead class="table-dark">
                      <tr>
                        <th>Category</th>
                        <th>Edit</th>
                        <th>Delete</th>
                       
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      <?php
                      $sql3="select * from expcategory";
                      $res3=mysqli_query($conn,$sql3);
                      while ($row3 = $res3->fetch_assoc()) {
                      ?>
                      <tr>
                        <td>
                          <i class="mdi mdi-wallet-travel mdi-20px text-danger me-3"></i
                          ><span class="fw-medium"><?=$row3['category']?> / <?=$row3['head']?></span>

                        </td>
                        <td> <a class="dropdown-item" href="expedit.php?id=<?=$row3['id']?>"
                                ><i class="mdi mdi-pencil-outline me-1"></i> Edit</a
                              ></td>
                               <td>
                            <!-- Delete button -->
                            <a class="dropdown-item" href="expcategorydelete.php?id=<?= $row3['id'] ?>" 
                               onclick="return confirm('Are you sure you want to delete this item?');">
                                <i class="mdi mdi-delete-outline me-1"></i> Delete
                            </a>
                        </td>
                     
                      </tr>
                      <?php } ?>
                      
                    
                    
                    </tbody>
                  </table>
                </div>
              </div>
              <!--/ Bootstrap Table with Header Dark -->

            <!-- Default -->
            <div class="col-md">
                  <div class="card mb-4">
                 
                    <div class="card-body">
                        <form method="post">
                      <div>
                    
                    
                    
                    
                    
                     <!-- Connect with Head Dropdown -->
            <label for="headDropdown" class="form-label">Connect with Head</label>
            <select class="form-select" id="headDropdown" name="head_id" required>
              <option value="">Select Head</option>
              <?php
      $sqlHead = "SELECT head FROM exphead";
  $resHead = mysqli_query($conn, $sqlHead);
  while ($rowHead = $resHead->fetch_assoc()) {
      echo '<option value="' . htmlspecialchars($rowHead['head'], ENT_QUOTES, 'UTF-8') . '">' . $rowHead['head'] . '</option>';
  }
  ?>
            </select><br>
                    
                       <h5 class="card-header">Add expenses category</h5>
                    
                    
                        <input
                          type="text"
                          class="form-control"
                          id="defaultFormControlInput"
                          placeholder=""
                          aria-describedby="defaultFormControlHelp" name="category" value=""/><br>
                          <button type="submit" name="submit" class="btn btn-primary">Submit</button>

                      </div>
                      </form>
                    </div>
                  </div>
                </div>

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
