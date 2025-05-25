<?php
require('session.php');
include('connection.php');
include('functions.php');
if(isset($_SESSION['main_admin'])){
  $username=$_SESSION['main_admin'];
}


if(isset($_POST['submit'])){
  
  $barcode=$_POST['barcode'];
  $itemname=$_POST['itemname'];
  $quantity=$_POST['quantity'];
  $price=$_POST['price'];
  $unit=$_POST['unit'];

  $date=$_POST['date'];
  $measurement=$_POST['measurement'];
  $cost=$_POST['cost'];
  $rcp=$_POST['rcp'];
  $amt=$_POST['amt'];
  $expdate=$_POST['expdate'];
  $discription=$_POST['discription'];
  $marginamt=$_POST['marginamt'];
  $dp=$_POST['dp'];
  
  
  foreach($barcode as $index => $barcodes){
  $s_barcode = $barcodes;
  
  $s_itemname = $itemname[$index];
  $s_quantity = $quantity[$index];
  $s_price = $price[$index];
  $s_unit = $unit[$index];
  

  
  
$timezone = new DateTimeZone("Asia/Kolkata" );
$indate = new DateTime();
$indate->setTimezone($timezone );
$indate=$indate->format( 'Y-m-d H:i:s' );
  $sql="insert into production(barcode,itemname,quantity,price,unit,adate,measurement,cost,rcp,amt,expdate,discription,marginamt,dp,indate) values('$s_barcode','$s_itemname','$s_quantity','$s_price','$s_unit','$date','$measurement','$cost','$rcp','$amt',' $expdate','$discription','$marginamt','$dp','$indate')";
  $res=mysqli_query($conn,$sql);
  }
  header("Location: production.php");
  echo '<script> window.location.href = "production.php"; </script>';
  
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

    <title>Tables - Basic Tables | Materio - Bootstrap Material Design Admin Template</title>

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
            id="layout-navbar" s>
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
              <h4 class="py-3 mb-4"><span class="text-muted fw-light">Tables /</span> Basic Tables</h4>

             
              <hr class="my-5" />
              <a href="purchase.php"><button type="button" class="btn btn-primary" style="margin-top:50px;background: #29913c;">Back to Home</button></a>
              <a href="purchase.php"><button type="button" class="btn btn-primary" style="margin-top:50px;background: #29913c;">EDIT PRODUCTION</button></a>
          
                  <div class="card" style="margin-top: 100px;margin-bottom: 100px;">
                      <div class="card-header text-center">
                        <h4>ADD PRODUCTION</h4>
                      </div>
                      <div class="card-body">
                          <form action="" method="post">
                          
          
          
                          <table class="table table-bordered">
                              <thead class="table-success">
                                <tr>
                                  <th scope="col">#</th>
                                  <th scope="col">Barcode</th>
                                 <th scope="col" class="text-end">Item/Resource name</th>
                                  <th scope="col" class="text-end">Quantity %</th>
                                  <th scope="col" class="text-end">Price</th>
                                  <th scope="col" class="text-end">Unit</th>
                                  
                                  <th scope="col" class="NoPrint">                         
                                      <button type="button" class="btn btn-sm btn-success" onclick="BtnAdd()">+</button>
                                    
                                  </th>
          
                                </tr>
                              </thead>
                              <tbody id="TBody">
                                <tr id="TRow" >
                                  <th scope="row">1</th>
                                  <td><input type="text" class="form-control text-end barcode" name="barcode[]" ></td>
          
                                  <td><input type="text" class="form-control text-end itemname" name="itemname[]" ></td>
          
                                  <td><input type="text" class="form-control text-end landing" name="quantity[]" ></td>
                                  <td><input type="text" class="form-control text-end gst" name="price[]"  ></td>
          
          
                                  <td><input type="text" class="form-control text-end withgstprice" name="unit[]" ></td>
                                 
                                  
                                  <!-- <td class="NoPrint"><button type="button" class="btn btn-sm btn-danger" onclick="BtnDel(this)">X</button></td> -->
                                </tr>
                              </tbody>
                            </table>
          
          
                            <div class="row">
                             
                              <div class="col-4">
                                 
                                  <div class="input-group mb-3">
                                      <span class="input-group-text" >Date</span>
                                      <input type="date" class="form-control text-end" id="date" name="date" >
                                  </div>
                                  
                                 
                                  <div class="input-group mb-3">
                                      <input type="text" class="form-control" placeholder="Measurement" name="measurement" >
                                  </div>
                                  
                                 
                                  <div class="input-group mb-3">
                                      <input type="text" class="form-control" placeholder="Cost" name="cost" >
                                  </div>
                                  
                                 
                                  <div class="input-group mb-3">
                                      <input type="text" class="form-control" placeholder="RCP" name="rcp" >
                                  </div>
                                  
                                 
                                  <!-- <div class="input-group mb-3">
                                      <span class="input-group-text" >Net Amt</span>
                                      <input type="number" class="form-control text-end" id="FNet" name="FNet" disabled="">
                                  </div> -->
          
          
                              </div>
                              <div class="col-4">
                              <div class="input-group mb-3">
                                      <span class="input-group-text" >Ex. Date</span>
                                      <input type="date" class="form-control text-end" id="date" name="expdate" >
                                  </div>
                                  <div class="input-group mb-3">
                                      <input type="text" class="form-control" placeholder="Discription(Malayalam)" name="discription" >
                                  </div>
                                  <div class="input-group mb-3">
                                      <input type="text" class="form-control" placeholder="Margin Amount" name="marginamt" >
                                  </div>
                                  <div class="input-group mb-3">
                                      <input type="text" class="form-control" placeholder="DP" name="dp" >
                                  </div>
                              <button type="submit" name="submit" class="btn btn-primary" >Add</button>
                              <button type="submit" name="submit" class="btn btn-primary" >Update</button>
          
                                  <!-- <button type="button" class="btn btn-primary" onclick="GetPrint()">Print</button> -->
          
                              </div>
                              <div class="col-4">
                              <div class="input-group mb-3">
                                      <input type="text" class="form-control" placeholder="New / Existing Barcode" name="barcode" >
                                  </div>
                                  <div class="input-group mb-3">
                                      <!-- <span class="input-group-text" >New / Existing product name</span> -->
                                      <input type="text" class="form-control" placeholder="New / Existing product name" name="productname" >
                                  </div>
                                  <div class="input-group mb-3">
                                      <input type="text" class="form-control" placeholder="Landing" name="landing" >
                                  </div>
                                  
                                  <div class="input-group mb-3">
                                      <input type="text" class="form-control" placeholder="MRP" name="mrp" >
                                  </div>
                                  <!-- <button type="button" class="btn btn-primary" onclick="GetPrint()">Print</button> -->
          
                              </div>
                          </div>
          
                         </form>
                       </div>
                    </div>
            
              <!-- / Content -->
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
