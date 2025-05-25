<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
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
    $s_vendor = mysqli_real_escape_string($conn, $_POST['vendor']);
    $s_store = $_POST['store'];
    $category = $_POST['category'];

    $s_mode = $_POST['mode'];
    $s_invno = $_POST['invno'];
    $s_date = $_POST['date'];
    $invdate = $_POST['invdate'];
    $tid = $_POST['tid'];
    $mrp = $_POST['mrp'];
    $pr_name = $_POST['pr_name'];
    $expiry = $_POST['expiry'];
    $totalamount = $_POST['FTotal'];
    $hsncode = $_POST['hsncode'];
    $gst = $_POST['gst'];
    $landing = $_POST['landing'];
    $qty = $_POST['qty'];
    $rcp = $_POST['rcp'];
    $withoutgst = $_POST['withoutgst'];
    $dp = $_POST['dp'];
    $total = $_POST['total'];
    $s_billamt = $_POST['FGST'];
    $remark = $_POST['remark'];
    $reminder = $_POST['reminder'];
    
    foreach ($barcode as $index => $s_barcode) {
        $s_tid = $tid[$index];
        $s_pr_name = mysqli_real_escape_string($conn, $pr_name[$index]);
        $s_expiry = $expiry[$index];
        $s_hsncode = $hsncode[$index];
        $s_category = $category;
        $s_gst = $gst[$index];
        $s_landing = $landing[$index];
        $s_qty = (float)$qty[$index];
        $s_rcp = $rcp[$index];
        $s_mrp = $mrp[$index];
        $s_withoutgst = $withoutgst[$index];
        $s_dp = $dp[$index];
        $s_total = $total[$index];
        // $s_reminder = $reminder[$index];
        $timezone = new DateTimeZone("Asia/Kolkata");
        $indate = new DateTime();
        $indate->setTimezone($timezone);
        $indate = $indate->format('Y-m-d');
        
        if ($s_barcode != '' || $s_barcode != 'NULL') {
            $sql8 = "SELECT * FROM master WHERE product_barcode='$s_barcode' AND active=0";
            $res8 = mysqli_query($conn, $sql8);
            $row8=mysqli_fetch_array($res8);
            // $head=$row8['head'];
        //  $category=$row8['category'];
            if (mysqli_num_rows($res8) > 0) {
                $sql = "INSERT INTO entry(aid, vendor, store, mode, invno, indate, date_invoice, barcode, product_name, expiry_date, hsncode, gst, landing, qty, withoutgst, mrp, amount, total_billamount, totalamount, rcp, actual_dp, remark,reminder,category) VALUES ('$s_tid', '$s_vendor', '$s_store', '$s_mode', '$s_invno', '$s_date', '$invdate', '$s_barcode', '$s_pr_name', '$s_expiry', '$s_hsncode', '$s_gst', '$s_landing', '$s_qty', '$s_withoutgst', '$s_mrp', '$s_total', '$s_billamt', '$totalamount', '$s_rcp', '$s_dp','$remark','$reminder','$s_category')";
                $res = mysqli_query($conn, $sql);
                $sql2="INSERT INTO expreport(cate_id,invno,name,expense,ondate) values('$s_category','$s_invno','$s_vendor','$s_billamt','$indate')";
                $res2=mysqli_query($conn,$sql2);
            }
        }
        
        if ($s_landing != $landingm || $s_mrp != $mrpm || $s_dp != $dpm) {
            $rcpm = ((($s_dp - $s_landing) / 3) * 1) + $s_landing;
            $data = array(
                'landing' => $s_landing,
                'rcp' => $rcpm,
                'dp' => $s_dp,
                'mrp' => $s_mrp,
            );
            edit_row4('master', $data, $conn, $s_barcode);
        }

        if ($s_mode == 'IN') {
            handle_stock_in($conn, $s_barcode, $s_store, $s_qty, $indate, $s_pr_name, $s_vendor, $s_dp, $s_rcp, $s_mrp, $s_gst, $s_hsncode);
        }

        // if ($s_mode == 'TRANSFER') {
        //     handle_stock_transfer($conn, $s_barcode, $s_vendor, $s_store, $s_qty, $indate, $s_pr_name, $s_mode);
        // }

        // if ($s_mode == 'OUT') {
        //     handle_stock_out($conn, $s_barcode, $s_store, $s_qty, $indate, $s_pr_name, $s_mode);
        // }
       // Make sure variables are set and not empty

$checkQuery = "SELECT * FROM entry WHERE invno='$s_invno' AND barcode='$s_barcode' AND date_invoice='$invdate' AND vendor='$s_vendor'";

// Debugging output

$checkResult = mysqli_query($conn, $checkQuery);


// Proceed if the query is successful
if (mysqli_num_rows($checkResult) == 0) {
    $sql = "INSERT INTO entry(aid, vendor, store, mode, invno, indate, date_invoice, barcode, product_name, expiry_date, hsncode, gst, landing, qty, withoutgst, mrp, amount, total_billamount, totalamount, rcp, actual_dp, pm_margin, store_margin, pm_profit, store_profit, remark) 
            VALUES ('$s_tid', '$s_vendor', '$s_store', '$s_mode', '$s_invno', '$s_date', '$invdate', '$s_barcode', '$s_pr_name', '$s_expiry', '$s_hsncode', '$s_gst', '$s_landing', '$s_qty', '$s_withoutgst', '$s_mrp', '$s_total', '$s_billamt', '$totalamount', '$s_rcp', '$s_dp', '$pm_margin', '$store_margin', '$pm_profit', '$store_profit', '$remark')";
    $res = mysqli_query($conn, $sql);
    if (!$res) {
        die('Insert Query Error: ' . mysqli_error($conn));
    }
} else {
    // Handle duplicate entry, e.g., update existing record or skip
    echo "Duplicate entry for barcode: $s_barcode on date: $invdate with invoice number: $s_invno";
}

    }

    header("Location: product-entry.php");
    echo '<script> window.location.href = "product-entry.php"; </script>';
}

function handle_stock_in($conn, $s_barcode, $s_store, $s_qty, $indate, $s_pr_name, $s_vendor, $s_dp, $s_rcp, $s_mrp, $s_gst, $s_hsncode) {
    $sql2 = "SELECT * FROM stocktab WHERE barcode='$s_barcode' AND location='$s_store'";
    $res2 = mysqli_query($conn, $sql2);
    if (mysqli_num_rows($res2) >= 1) {
        $row2 = mysqli_fetch_array($res2);
        $close_stock = $row2['closing_stock'] ?? 0;
        $stock_in = $row2['stock_in'] ?? 0;
        $closestock = $close_stock + $s_qty;
        $stockin = $stock_in + $s_qty;

        $data = array(
            'stock_in' => $stockin,
            'closing_stock' => $closestock,
            'add_date' => $indate,
        );
        edit_row3('stocktab', $data, $conn, $s_barcode, $s_store);
    } else {
        $data = array(
            'barcode' => $s_barcode,
            'product_name' => $s_pr_name,
            'location' => $s_store,
            'stock_in' => $s_qty,
            'closing_stock' => $s_qty,
            'add_date' => $indate,
            'mode' => 'IN',
            'vendor_name' => $s_vendor,
            'dp' => $s_dp,
            'rcp' => $s_rcp,
            'mrp' => $s_mrp,
            'gst' => $s_gst,
            'hsncode' => $s_hsncode,
        );
        insert_row('stocktab', $data, $conn);
    }
}

// function handle_stock_transfer($conn, $s_barcode, $s_vendor, $s_store, $s_qty, $indate, $s_pr_name, $s_mode) {
//     $sql2 = "SELECT * FROM stocktab WHERE barcode='$s_barcode' AND location='$s_vendor'";
//     $res2 = mysqli_query($conn, $sql2);
//     if (mysqli_num_rows($res2) >= 1) {
//         $row2 = mysqli_fetch_array($res2);
//         $close_stock = $row2['closing_stock'] ?? 0;
//         $stock_in = $row2['transfer_in'] ?? 0;
//         $closestock = $close_stock + $s_qty;
//         $stockin = $stock_in + $s_qty;

//         $data = array(
//             'transfer_in' => $stockin,
//             'closing_stock' => $closestock,
//             'add_date' => $indate,
//             'mode' => $s_mode,
//         );
//         edit_row3('stocktab', $data, $conn, $s_barcode, $s_vendor);
//     } else {
//         $data = array(
//             'barcode' => $s_barcode,
//             'product_name' => $s_pr_name,
//             'location' => $s_vendor,
//             'transfer_in' => $s_qty,
//             'closing_stock' => $s_qty,
//             'add_date' => $indate,
//             'mode' => $s_mode,
//         );
//         insert_row('stocktab', $data, $conn);
//     }

//     handle_transfer_out($conn, $s_barcode, $s_store, $s_qty, $indate, $s_pr_name, $s_mode);
// }

// function handle_transfer_out($conn, $s_barcode, $s_store, $s_qty, $indate, $s_pr_name, $s_mode) {
//     $sql3 = "SELECT * FROM stocktab WHERE barcode='$s_barcode' AND location='$s_store'";
//     $res3 = mysqli_query($conn, $sql3);
//     if (mysqli_num_rows($res3) >= 1) {
//         $row3 = mysqli_fetch_array($res3);
//         $close_stock = $row3['closing_stock'] ?? 0;
//         $stock_out = $row3['transfer_out'] ?? 0;
//         $closestock = $close_stock - $s_qty;
//         $stockout = $stock_out + $s_qty;

//         $data = array(
//             'transfer_out' => $stockout,
//             'closing_stock' => $closestock,
//             'add_date' => $indate,
//             'mode' => $s_mode,
//         );
//         edit_row3('stocktab', $data, $conn, $s_barcode, $s_store);
//     } else {
//         $data = array(
//             'barcode' => $s_barcode,
//             'product_name' => $s_pr_name,
//             'location' => $s_store,
//             'transfer_out' => $s_qty,
//             'closing_stock' => $s_qty,
//             'add_date' => $indate,
//             'mode' => $s_mode,
//         );
//         insert_row('stocktab', $data, $conn);
//     }
// }

// function handle_stock_out($conn, $s_barcode, $s_store, $s_qty, $indate, $s_pr_name, $s_mode) {
//     $sql2 = "SELECT * FROM stocktab WHERE barcode='$s_barcode' AND location='$s_store'";
//     $res2 = mysqli_query($conn,$sql2);
//     if (mysqli_num_rows($res2) >= 1) {
//         $row2 = mysqli_fetch_array($res2);
//         $close_stock = $row2['closing_stock'] ?? 0;
//         $stock_out = $row2['stock_out'] ?? 0;
//         $closestock = $close_stock - $s_qty;
//         $stockout = $stock_out + $s_qty;

//         $data = array(
//             'stock_out' => $stockout,
//             'closing_stock' => $closestock,
//             'add_date' => $indate,
//             'mode' => $s_mode,
//         );
//         edit_row3('stocktab', $data, $conn, $s_barcode, $s_store);
//     } else {
//         $data = array(
//             'barcode' => $s_barcode,
//             'product_name' => $s_pr_name,
//             'location' => $s_store,
//             'stock_out' => $s_qty,
//             'closing_stock' => $s_qty,
//             'add_date' => $indate,
//             'mode' => $s_mode,
//         );
//         insert_row('stocktab', $data, $conn);
//     }
// }
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
              <h4 class="py-3 mb-4"> Product entry</h4>

             
              <hr class="my-5" />
    <a href="add-vendor.php"><button type="button" class="btn btn-primary" style="margin-top:50px;background: #29913c;">Add vendor</button></a>
    <a href="master-adding.php"><button type="button" class="btn btn-primary" style="margin-top:50px;background: #29913c;">Add products</button></a>

        <div class="card" style="margin-top: 100px;margin-bottom: 100px;">
            <div class="card-header text-center">
              <h4>ENTRY</h4>
            </div>
            <div class="card-body">
                <form action="" method="post">
                <div class="row">
                  
                    <div class="col-6">
                        <div class="input-group mb-3">
                            <span class="input-group-text" >Vendor</span>
                            <select name="vendor" class="form-control itemname" data-live-search="true"  onchange="Disval(this);" placeholder="--Select vendor--">
                            <?php echo fill_unit_select_box($conn); ?>
                          </select>
                        
                         
                        </div>
            
                        <div class="input-group mb-3">
                            <span class="input-group-text" >Store</span>
                            <!-- <input type="text" class="form-control store" placeholder=""  name="store" > -->
                            <select name="store" class="form-control store" data-live-search="true"   >
                            <option value="Store">Store</option>
                           



                          </select>
                        </div>
            
                        <div class="input-group mb-3">
                        <span class="input-group-text" >Mode</span>
                        <select name="mode" class="form-control itemname" data-live-search="true"   >
                            <option value="IN">IN</option>
                            
                           
                          </select>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text" >Remark</span>
                            <input type="text" class="form-control"  name="remark" >
                        </div>
                       

                    </div>
                    <div class="col-6">
                    <div class="input-group mb-3">
                            <span class="input-group-text" >Inv. No</span>
                            <input type="text" class="form-control invno" placeholder=""  name="invno" >
                        </div>
                       
                        <div class="input-group mb-3">
                            <span class="input-group-text" >Date</span>
                            <input type="date" class="form-control"  name="date" required>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text" >Inv Date</span>
                            <input type="date" class="form-control"  name="invdate" required>
                        </div>
                       
                       
                       
                                       <div class="input-group mb-3">
                    <span class="input-group-text">Category</span>
                    <select class="form-control" name="category">
                        <option value="" disabled selected>Select Category</option>
                        <?php
                        // Fetch categories from the 'expcategory' table
                        $query = "SELECT category FROM expcategory";
                        $result = mysqli_query($conn, $query);
                
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<option value="' . htmlspecialchars($row['category']) . '">' . htmlspecialchars($row['category']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                       
                    </div>
                </div>

              <div style="overflow-x:scroll;">
                <table class="table table-bordered">
                    <thead class="table-success">
                      <tr>
                        <th scope="col">#</th>
                       
                        <th scope="col">Barcode</th>
                        <th scope="col" class="text-end">Product Name</th>
                       
                        <th scope="col" class="text-end">Expiry Date</th>
                        <th scope="col" class="text-end">Hsn Code</th>
                        <th scope="col" class="text-end"> WITHOUT GST</th>
                        <!--<th scope="col" class="text-end"> Category</th>-->

                        <th scope="col" class="text-end"> MRP</th>

                        <th scope="col" class="text-end">Landing</th>
                        <th scope="col" class="text-end">WITH GST</th>
                        <th scope="col" class="text-end">GST</th>
                        
                   
                        
                        <th scope="col" class="text-end">OHTER TAX</th>
                        <th scope="col" class="text-end">QTY</th>
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
                        <input type="hidden" class="form-control text-end tid" name="tid[]">
                        <td ><input style="width:100px;" type="text" class="form-control text-end pr_name" name="pr_name[]" ></td>
                       


                        <td ><input  type="date" class="form-control expiry"  name="expiry[]" required></td>
                        <td ><input style="width:100px;" type="text" class="form-control text-end hsncode" name="hsncode[]" ></td>
                        <td ><input type="text" style="width:100px;" class="form-control text-end dp" name="dp[]" value="" ></td>
                        <!--<td ><input style="width:100px;" type="text" class="form-control text-end category" name="category[]" value="" ></td>-->

                        <td ><input style="width:100px;" type="text" class="form-control text-end mrp" name="mrp[]" value="" ></td>

                        <td ><input style="width:100px;" type="text" class="form-control text-end landing" name="landing[]" value="" oninput="CalcR(this);"></td>
                        <td ><input style="width:100px;" type="text" class="form-control text-end rcp" name="rcp[]" ></td>
                        <td ><input style="width:100px;" type="text" class="form-control text-end gst" name="gst[]" ></td>
                       
                      
                        <td ><input style="width:100px;" type="text" class="form-control text-end withoutgst" name="withoutgst[]" value="" ></td>
                        <td ><input style="width:100px;" type="text" class="form-control text-end qty" name="qty[]"  oninput="CalcT(this);"></td>
                       
                        <td ><input style="width:100px;" type="text" class="form-control text-end total" name="total[]" value="" readonly></td>
                      
                      </tr>
                    </tbody>
                  </table>
              </div>

                  <div class="row">
                   
                    <div class="col-6">
                        <div class="input-group mb-3">
                            <span class="input-group-text" >Total</span>
                            <input type="number" class="form-control text-end" id="FTotal" name="FTotal" disabled="">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text" >Bill Amt</span>
                            <input type="number" class="form-control text-end" id="FGST" name="FGST" required>
                        </div>
                        <!-- <div class="input-group mb-3">
                            <span class="input-group-text" >Net Amt</span>
                            <input type="number" class="form-control text-end" id="FNet" name="FNet" disabled="">
                        </div> -->


                    </div>
                    <div class="col-6">
                                <div class="input-group mb-3">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" >Payment post to</span>
                                        <input type="date" class="form-control remindday"  name="reminder" value="" >
                                    </div>
                                  </div>
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