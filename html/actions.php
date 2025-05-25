<?php
require('session.php');


include('connection.php');
include('functions.php');
if(isset($_SESSION['main_admin'])){
  $username=$_SESSION['main_admin'];
}

$phone='';
$acdate='';
$stage='';
$salesman='';
$notes='';
$edit='';
$status='';
if(isset($_GET['eid'])&&($_GET['eid']!='')){
  $edit=1;
    $eid=$_GET['eid'];
    $sql2="select * from actions where id='$eid'";
    $res2=mysqli_query($conn,$sql2);
    $row2=mysqli_fetch_array($res2);
    // $id=$row2['taskid'];
    $cusname=trim($row2['customer']);
   
    $phone=$row2['phone'];
    $acdate=$row2['acdate'];
    $stage=$row2['stage'];
    $salesman=$row2['salesman'];
    $notes=$row2['notes'];
  $status=$row2['status'];
  
    }
if(isset($_GET['id'])){
    $id=$_GET['id'];
    $query1="select * from `addressbook` WHERE id=$id ";
	 $res1=mysqli_query($conn,$query1) or die(mysqli_error());
	 if(mysqli_num_rows($res1)>=1)
	 {
	   $row=mysqli_fetch_array($res1);
    $cusname=$row['customername'];
    $phone=$row['phone'];
    }
}

if(isset($_POST["submit"])){
  if($edit!=1){
  // $taskid=$_POST['taskid'];
    $cusname=$_POST['name'];
    $phone=$_POST['phone'];
    $acdate=$_POST['acdate'];
    $stage=$_POST['stage'];
    $salesman=$_POST['salesman'];
    $notes=$_POST['notes'];
    $status=$_POST['status'];
    $timezone = new DateTimeZone("Asia/Kolkata" );
    $date = new DateTime();
    $date->setTimezone($timezone );
    $date=$date->format( 'Y-m-d ' );
    $query="INSERT INTO actions (Name,phone,acdate,stage,salesman,notes,status,addate,active,managed_by) VALUES ('$cusname','$phone','$acdate','$stage','$salesman','$notes','$status','$date',0,'$username')";  
$result=mysqli_query($conn,$query);
if (!$result) {
  die("Error in query: " . mysqli_error($conn));
}
    // Query executed successfully, redirect to index.php
    header("Location: actions-list.php");
    echo '<script> window.location.href = "actions-list.php"; </script>';
  }
  elseif($edit==1){
    // $taskide=$_POST['taskid'];
    $cusnamee=$_POST['name'];
    $phonee=$_POST['phone'];
    $acdatee=$_POST['acdate'];
    $stagee=$_POST['stage'];
    $salesmane=$_POST['salesman'];
    $notese=$_POST['notes'];
$statuse=$_POST['status'];
    $timezone = new DateTimeZone("Asia/Kolkata" );
    $date = new DateTime();
    $date->setTimezone($timezone );
    $date=$date->format( 'Y-m-d ' );
    $data = array(
   
      'Name' => $cusnamee,
      'phone' => $phonee,
      'acdate' => $acdatee,
      'stage' => $stagee,
      'salesman' => $salesman,
      'notes' => $notese,
      'status' => $statuse,
      'addate' =>$date,
      
      );
      edit_row2('actions',$data,$conn,$id);
      header("Location: actions-list.php");
      echo '<script> window.location.href = "actions-list.php"; </script>';
      
  }

// header("Location: index.php");
//   echo '<script> window.location.href = "index.php"; </script>';     
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

    <title>Actions</title>

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
              <h4 class="py-3 mb-4"> Add Actions</h4>

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
                          <input type="text" class="form-control" id="basic-default-fullname" name="name" placeholder="John Doe" value="<?=$cusname;?>"/>
                          <label for="basic-default-fullname">Customer Name</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                          <input type="text" class="form-control" id="basic-default-company" name="phone" placeholder="ACME Inc." value="<?=$phone?>"/>
                          <label for="basic-default-company">Phone Number</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                          <input type="date" class="form-control" id="basic-default-company" name="acdate" placeholder="ACME Inc."  value="<?=$acdate?>"/>
                          <label for="basic-default-company">Date</label>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text customer" >Stage/Category</span>
                           
                            <?php


                                $result=mysqli_query($conn,"select * from stage where active=0");

                                echo"<select id='searchddl' name='stage' class='form-select'  value='<?=$stage?>'>";
                                 while($row=mysqli_fetch_array($result))
                                {
                                     echo"<option>$row[stage]</option>";
                                }
                                echo"</select>";



                           ?>
                           </div>
                           <div class="input-group mb-3">
                            <span class="input-group-text customer" >Salesman/Service Provider</span>
                           
                            <?php


                                $result=mysqli_query($conn,"select * from salesman where active=0");

                                echo"<select id='searchddl' name='salesman' class='form-select'  value='<?=$salesman?>'>";
                                 while($row=mysqli_fetch_array($result))
                                {
                                     echo"<option>$row[salesman]</option>";
                                }
                                echo"</select>";



                           ?>
                           </div>
                           <div class="input-group mb-3">
                            <span class="input-group-text customer" >Status/Transaction</span>
                           
                            <?php


                                $result=mysqli_query($conn,"select * from status where active=0");

                                echo"<select id='searchddl' name='status' class='form-select'  value='<?=$status?>'>";
                                 while($row=mysqli_fetch_array($result))
                                {
                                     echo"<option>$row[status]</option>";
                                }
                                echo"</select>";



                           ?>
                           </div>
                        <div class="form-floating form-floating-outline mb-4">
                          <input type="text" class="form-control" id="basic-default-company" name="notes" placeholder="ACME Inc." value="<?=$notes?>"/>
                          <label for="basic-default-company">Notes/Remarks</label>
                        </div>
                         <!-- Custom select -->

                          
                        
                          

                          
                        

                         <button type="submit" name="submit" class="btn btn-primary">Submit</button>&nbsp;
                        &nbsp;&nbsp;
                        <button type="submit" name="" class="btn btn-primary">Donor</button>
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
