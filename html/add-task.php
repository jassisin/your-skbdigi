<?php
require('session.php');

include('connection.php');
if(isset($_SESSION['main_admin'])){
  $username=$_SESSION['main_admin'];
}
if(isset($_GET['id'])&&($_GET['id']!='')){
  $edit=1;
    $id=$_GET['id'];
    $sql2="select * from addressbook where id='$id'";
    $res2=mysqli_query($conn,$sql2);
    $row2=mysqli_fetch_array($res2);
    $name=$row2['customername'];
    $phone=$row2['phone'];
    }
if(isset($_POST["submit"])){
    $cusname=$_POST['name'];
    $short_dis=$_POST['short-dis'];
    $long_dis=$_POST['long-dis'];
    $importance=$_POST['importance'];
    $active=$_POST['active'];
   $phone=$_POST['phone'];
    $timezone = new DateTimeZone("Asia/Kolkata" );
    $date = new DateTime();
    $date->setTimezone($timezone );
    $date=$date->format( 'Y-m-d ' );
    $query="INSERT INTO task (aid,customer,short_dis,long_dis,importance,active,indate,addedby,phone) VALUES ('$id','$cusname','$short_dis','$long_dis','$importance','$active','$date','$username','$phone')";  
$result=mysqli_query($conn,$query);

    // Query executed successfully, redirect to index.php
    header("Location: task-list.php");
    echo '<script> window.location.href = "task-list.php"; </script>';
   
    
// header("Location: index.php");
//   echo '<script> window.location.href = "index.php"; </script>';     
}

require 'vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

            }   
                 $name=$row['0'];
                 $short_dis=$row['1'];
               
                $long_dis=$row['2'];
                $contact_per=$row['3'];
                $importance=$row['4'];
                $active=$row['5'];
              

                $timezone = new DateTimeZone("Asia/Kolkata" );
                $in_date = new DateTime();
                $in_date->setTimezone($timezone );
                $in_date=$in_date->format( 'Y-m-d H:i:s' );
               $insert_query = mysqli_query($conn, "customer,short_dis,long_dis,importance,active,indate) VALUES ('$cusname','$short_dis','$long_dis','$importance','$active','$in_date'");




              //  if($insert_query)
              //  {

              //   $msg = "file imported successfully";
              //  }
              //  else{
              //   $msg = "not imported";
              //  }
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

    <title>Task</title>

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
                      <a class="dropdown-item pb-2 mb-1" href="#">
                        <div class="d-flex align-items-center">
                          <div class="flex-shrink-0 me-2 pe-1">
                            <div class="avatar avatar-online">
                              <img src="../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <h6 class="mb-0">John Doe</h6>
                            <small class="text-muted">Admin</small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <i class="mdi mdi-account-outline me-1 mdi-20px"></i>
                        <span class="align-middle">My Profile</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <i class="mdi mdi-cog-outline me-1 mdi-20px"></i>
                        <span class="align-middle">Settings</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <span class="d-flex align-items-center align-middle">
                          <i class="flex-shrink-0 mdi mdi-credit-card-outline me-1 mdi-20px"></i>
                          <span class="flex-grow-1 align-middle ms-1">Billing</span>
                          <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                        </span>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider my-1"></div>
                    </li>
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

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="py-3 mb-4"> Add Task</h4>
              <form action="" enctype="multipart/form-data" method="post">
                  <input type="file" name="excel" value="" required>

                    <button class="btn btn-primary btn-lg" type="submit" name="submit2">Add Bulk Task</button>

                    


                    </form><br>
                    <a href="task-format.xlsx" download style=""><button class="btn btn-primary btn-lg" type="button" >Download Task bulk upload format</button></a><br><br>
              <!-- Basic Layout -->
              <div class="row">
                <div class="col-xl">
                  <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <!-- <h5 class="mb-0">Basic Layout</h5>
                      <small class="text-muted float-end">Default label</small> -->
                    </div>
                    <div class="card-body">
                      <form method="post">
                        <div class="form-floating form-floating-outline mb-4">
                          <input type="text" class="form-control" id="basic-default-fullname" name="name" placeholder="John Doe" value="<?=$name;?>"/>
                          <label for="basic-default-fullname">Customer Name</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                          <input type="text" class="form-control" id="basic-default-company" name="short-dis" placeholder="ACME Inc." />
                          <label for="basic-default-company">Short Discription</label>
                        </div>
                        <input type="hidden" id="custId" name="phone" value="<?=$phone?>">

                        <div class="form-floating form-floating-outline mb-4">
                          <input type="text" class="form-control" id="basic-default-company" name="long-dis" placeholder="ACME Inc." />
                          <label for="basic-default-company">Long Discription</label>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text customer" >Importance</span>
                           
                            <?php


                                $result=mysqli_query($conn,"select * from importance where active=0");

                                echo"<select id='searchddl' class='form-select' name='importance'   >";
                                 while($row=mysqli_fetch_array($result))
                                {
                                     echo"<option>$row[importance]</option>";
                                }
                                echo"</select>";



                           ?>
                           </div>
                       
                         <!-- Custom select -->

                          
                         <div class="input-group mb-3">
                            <span class="input-group-text customer" >Action</span>
                           
                            <?php


                                $result=mysqli_query($conn,"select * from action where active=0");

                                echo"<select id='searchddl' name='action' class='form-select'  >";
                                 while($row=mysqli_fetch_array($result))
                                {
                                     echo"<option>$row[action]</option>";
                                }
                                echo"</select>";



                           ?>
                           </div>
                          

                          
                        

                        <button type="submit" name="submit" class="btn btn-primary">Send</button>
                      </form>
                    </div>
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
