<?php
require('session.php');
include('connection.php');
include('functions.php');

if(isset($_SESSION['main_admin'])){
  $username=$_SESSION['main_admin'];
}
include('export_data3.php');

if (isset($_POST['submit1'])) {
  $staff = $_POST['staff'];
  $head = $_POST['head'];
  $category = $_POST['category'];
  $expdate = $_POST['expdate'];

  $name = $_POST['name'];
  $barcode = $_POST['barcode'];
  $amount = $_POST['amount'];
  $frequency = $_POST['frequency'];
  $paydate = $_POST['paydate'];
  $postedfor = $_POST['postedfor'];
  $remark = $_POST['remark'];

  foreach ($barcode as $index => $barcodes) {
      $s_barcode = $barcodes;
      $s_name = $name[$index];
      $s_amount = $amount[$index];
      $s_frequency = $frequency[$index];
      $s_paydate = $paydate[$index];
      $s_postedfor = $postedfor[$index];
      $s_remark = $remark[$index];

      $timezone = new DateTimeZone("Asia/Kolkata");
      $indate = new DateTime();
      $indate->setTimezone($timezone);
      $indate = $indate->format('Y-m-d H:i:s');

      $sql = "INSERT INTO resource (staff, category, head, name, barcode, amount, frequency, paydate, postedfor, expdate, remark, indate, active) 
      VALUES ('$staff', '$category', '$head', '$s_name', '$s_barcode', '$s_amount', '$s_frequency', '$s_paydate', '$s_postedfor', '$expdate', '$s_remark', '$indate', 0)";

$res = mysqli_query($conn, $sql);
$sql2="INSERT INTO expreport(invno,cate_id,name,expense,ondate) values('$s_barcode','$category','$staff','$s_amount','$indate')";
$res2=mysqli_query($conn,$sql2);
if (!$res) {
  die("Query failed: " . mysqli_error($conn));
}
  }
  header("Location: expense-list.php");
  exit;  // Stop further execution
}
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

      }   $staff=$row['0'];
      $category=$row['1'];
      $head=$row['2'];
      $expdate=$row['3'];
           $name=$row['4'];
           $barcode=$row['5'];
          $amount=$row['6'];
          $frequency=$row['7'];
          $dedicate=$row['8'];
          $marked=$row['9'];
          $balance=$row['10'];

        
          $timezone = new DateTimeZone("Asia/Kolkata" );
          $in_date = new DateTime();
          $in_date->setTimezone($timezone );
          $in_date=$in_date->format( 'Y-m-d H:i:s' );
         $insert_query = mysqli_query($conn, "insert into resource set staff='$staff',category='$category',head='$head',expdate='$expdate',name='$name',barcode='$barcode',amount='$amount',frequency ='$frequency',dedicate='$dedicate',marked='$marked',active=0");




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
              <h4 class="py-3 mb-4"><span class="text-muted fw-light">Tables /</span> Basic Tables</h4>

             
              <hr class="my-5" />
              <a href="exphead.php"><button type="button" class="btn btn-primary" style="margin-top:50px;background: #29913c;">NEW EXPENSES HEAD</button></a>
              <a href="expcategory.php"><button type="button" class="btn btn-primary" style="margin-top:50px;background: #29913c;">NEW CATEGORY HEAD</button></a>
              <a href="expname.php"><button type="button" class="btn btn-primary" style="margin-top:50px;background: #29913c;">NEW EXPENSE NAME</button></a>
              <a href="expense-list.php"><button type="button" class="btn btn-primary" style="margin-top:50px;background: #29913c;">VIEW/EDIT EXPENSES</button></a>
              <a href="expenses_format.xlsx" download style="float:right;margin-bottom:30px;"><button class="btn btn-primary btn-lg" type="button" >Download Expense Format</button></a>


        <form method="post">      
              <input type="file" name="excel" value="" required>

<button class="btn btn-primary btn-lg" type="submit" name="submit2">Add New Expense</button>




</form>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">					
				<button type="submit" id="export_data" name='export_dataex' value="Export to excel" class="btn btn-primary btn-lg">Download Expenses</button>
			</form>

                  <div class="card" style="margin-top: 100px;margin-bottom: 100px;">
                      <div class="card-header text-center">
                        <h4>ADD EXPENSES</h4>
                      </div>
                      <div class="card-body">
                          <form action="" method="post">
                          
          
                          <div class="row">
                  
                  <div class="col-8">
                      <div class="input-group mb-3">
                          <span class="input-group-text" >Staff</span>
                          <input type="text" class="form-control "  id="vendor"  name="staff"  required>
                      </div>

          
                   
                      <div class="input-group mb-3">
                                        <span class="input-group-text customer" >Category</span>
                                       
                                        <?php
            
            
                                            $result=mysqli_query($conn,"select * from expcategory where active=0");
            
                                            echo"<select id='searchddl' name='category' style='height:30px;' >";
                                            
                                             while($row=mysqli_fetch_array($result))
                                            {
                                                 echo"<option>$row[category]</option>";
                                            }
                                            echo"</select>";
            
            
            
                                       ?>
                                       </div>
          
                     
                  </div>
                  <div class="col-4">
                    
                  <div class="input-group mb-3">
                                        <span class="input-group-text customer" >Head</span>
                                       
                                        <?php
            
            
                                            $result=mysqli_query($conn,"select * from exphead where active=0");
            
                                            echo"<select id='searchddl' name='head' style='height:30px;' >";
                                            
                                             while($row=mysqli_fetch_array($result))
                                            {
                                                 echo"<option>$row[head]</option>";
                                            }
                                            echo"</select>";
            
            
            
                                       ?>
                                       </div>
                      <div class="input-group mb-3">
                          <span class="input-group-text" >Date</span>
                          <input type="date" class="form-control"  name="expdate" required>
                      </div>



                  </div>
              </div>

                          <table class="table table-bordered">
                              <thead class="table-success">
                                <tr>
                                  <th scope="col">#</th>
                                  <th scope="col">Name</th>
                                
                                  <th scope="col" class="text-end">Barcode</th>
                                  <th scope="col" class="text-end">Amount</th>
                                  <th scope="col" class="text-end">Frequency</th>
                                  <th scope="col" class="text-end">Payment Date</th>
                                  <th scope="col" class="text-end">Posted for</th>
                                  <th scope="col" class="text-end">Remarks</th>
                                  
                                  <th scope="col" class="NoPrint">                         
                                      <button type="button" class="btn btn-sm btn-success" onclick="BtnAdd()">+</button>
                                    
                                  </th>
          
                                </tr>
                              </thead>
                              <tbody id="TBody">
                                <tr id="TRow" >
                                  <th scope="row">1</th>
                                  <td>
                <!-- Convert the input field into a select box -->
                <select class="form-control text-end" name="name[]" style="width:100px;">
                    <?php
                    $query = "SELECT * FROM sevapro WHERE active=0";
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<option value='" . $row['projectname'] . "'>" . $row['projectname'] . "</option>";
                    }
                    ?>
                </select>
            </td>
                                  <td><input type="text" class="form-control text-end barcode" name="barcode[]" ></td>
          
                                  <td><input type="text" class="form-control text-end itemname" name="amount[]" ></td>
          
                                  <td><input type="text" class="form-control text-end landing" name="frequency[]" ></td>
                                  <td><input type="date" class="form-control text-end gst" name="paydate[]"  ></td>
          
          
                                  <td><input type="text" class="form-control text-end withgstprice" name="postedfor[]" ></td>
                                  <td><input type="text" class="form-control text-end withgstprice" name="remark[]" ></td>

                                  
                                  <!-- <td class="NoPrint"><button type="button" class="btn btn-sm btn-danger" onclick="BtnDel(this)">X</button></td> -->
                                </tr>
                              </tbody>
                            </table>
          
          
                            <div class="col-6">
                    <button type="submit" name="submit1" class="btn btn-primary" >Add</button>
                        <!-- <button type="button" class="btn btn-primary" onclick="GetPrint()">Print</button> -->

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
