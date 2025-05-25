<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('session.php');
include('connection.php');
include('functions.php');

if (isset($_SESSION['main_admin'])) {
    $username = $_SESSION['main_admin'];
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
function fill_unit_select_box($conn) {
    $output = '';
    $query = "SELECT * FROM itemstock ORDER BY itemname ASC";
    $result = $conn->query($query);
    foreach ($result as $row) {
        $output .= '<option value="' . $row["itemname"] . '">' . $row["itemname"] . '</option>';
    }
    return $output;
}
if(isset($_GET['invid'])&&($_GET['invid']!=''))
{
  $eid=$_GET['invid'];
  $sql2="select * from entry where id='$eid'";
  $res2=mysqli_query($conn,$sql2);
  $row=mysqli_fetch_array($res2);
  $invno=$row['invno'];
  $vendor=$row['vendor'];
  $store=$row['store'];
  $mode=$row['mode'];

  $indate=$row['indate'];
  $date_invoice=$row['date_invoice'];
  $barcode=$row['barcode'];
  $product_name=$row['product_name'];

  
}
if (isset($_POST['submit'])) {
    $barcode = $_POST['barcode'];
    $prname = $_POST['pr_name'];
    $description = $_POST['discrip'];
    $amount = $_POST['amount'];
    $landing = $_POST['landing'];
    $gst = $_POST['gst'];
    $withoutgst = $_POST['withoutgst'];


    $timezone = new DateTimeZone("Asia/Kolkata");
    $in_date = new DateTime();
    $in_date->setTimezone($timezone);
    $in_date = $in_date->format('Y-m-d H:i:s');
    foreach($barcode as $index => $barcodes)
    {
    $s_barcode = $barcodes;
    
    $s_prname=$prname[$index];
    
    $s_description=$description[$index];
    $s_amount=$amount[$index];
    $s_landing=$landing[$index];
    $s_gst=$gst[$index];
    $s_withoutgst=$withoutgst[$index];


    $query="INSERT INTO sevapro (barcode, projectname, description,amount,landing,gst,withoutgst,addedby,indate) VALUES ('$s_barcode', '$s_prname', '$s_description', '$s_amount','$s_landing', '$s_gst','$s_withoutgst', '$username','$in_date')";  
    $result=mysqli_query($conn,$query);
    $query="INSERT INTO master (product_barcode, product_name,meta_keyword,dp,landing,gst,without_gst,insert_date,active) VALUES ('$s_barcode', '$s_prname', '$s_description', '$s_amount','$s_landing', '$s_gst','$s_withoutgst','$in_date',0)";
    
    $result=mysqli_query($conn,$query);
    //  echo "<script>alert('Data successfully submitted!');</script>";
 
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

    <title>Product entry</title>

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


    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
    <script src="js/invoice.js"></script>
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
              <!-- <h4 class="py-3 mb-4"> Product entry</h4> -->

             
              <hr class="my-5" />
  
        <div class="card" style="margin-top: 100px;margin-bottom: 100px;">
            <div class="card-header text-center">
              <h4>Enter New Services</h4>
            </div>
            <div class="card-body">
                <form action="" method="post">
             

              <div >
                <table class="table table-bordered">
                    <thead class="table-success">
                      <tr>
                        <th scope="col">#</th>
                       
                        <th scope="col">Barcode</th>
                        <th scope="col" class="text-end">Name</th>
                       
                        <th scope="col" class="text-end">Description</th>
                        <th scope="col" class="text-end">Landing</th>

                        <th scope="col" class="text-end">GST</th>
                        <th scope="col" class="text-end">HSN CODE</th>
                        <th scope="col" class="text-end">Amount</th>

                      
                        <th scope="col" class="NoPrint">                         
                            <button type="button" class="btn btn-sm btn-success" onclick="BtnAdd()">+</button>
                          
                        </th>

                      </tr>
                    </thead>
                    <tbody id="TBody">
                      <tr id="TRow" >
                        <th scope="row">1</th>
                       
                        <td ><input style="width:100px;" type="text" class="form-control text-end barcode" name="barcode[]" onchange="Master(this);"></td>
                        <td ><input style="width:100px;" type="text" class="form-control text-end pr_name" name="pr_name[]" ></td>
                       


                        <td ><input style="width:100px;" type="text" class="form-control text-end discrip" name="discrip[]" ></td>
                        <td ><input style="width:100px;" type="text" class="form-control text-end landing" name="landing[]" ></td>

                        <td ><input style="width:100px;" type="text" class="form-control text-end amount" name="gst[]" ></td>
                        <td ><input type="text" style="width:100px;" class="form-control text-end category" name="withoutgst[]" value="" ></td>
                        <td ><input type="text" style="width:100px;" class="form-control text-end category" name="amount[]" value="" ></td>


                        
                      
                      </tr>
                    </tbody>
                  </table>
              </div>

                  <div class="row">
                   
                    <div class="col-6">
                      
                        <!-- <div class="input-group mb-3">
                            <span class="input-group-text" >Net Amt</span>
                            <input type="number" class="form-control text-end" id="FNet" name="FNet" disabled="">
                        </div> -->


                    </div>
                    <div class="col-6">
                    <button type="submit" name="submit" class="btn btn-primary" >Add</button>
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
    <!-- <script>
document.querySelector('form').addEventListener('submit', function() {
    this.querySelector('button[type="submit"]').disabled = true;
});
</script> -->
    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>