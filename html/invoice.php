<?php
require('session.php');
include('connection.php');
include('functions.php');
if(isset($_SESSION['main_admin'])){
  $username=$_SESSION['main_admin'];
}

if(isset($_SESSION['prefix'])){
$prefix=$_SESSION['prefix'];
}

if(isset($_SESSION['user'])){
  $user=$_SESSION['user'];
  }

$timezone = new DateTimeZone("Asia/Kolkata" );
$tdate1 = new DateTime();
$tdate1->setTimezone($timezone );
$tdate=$tdate1->format( 'd-m-Y' );

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
function fill_unit_select_box($conn)
{
	$output = '';

	$query = "SELECT * FROM itemstock ORDER BY itemname ASC";

	$result = $conn->query($query);

	foreach($result as $row)
	{
		$output .= '<option value="'.$row["itemname"].'">'.$row["itemname"] . '</option>';
	}

	return $output;
}
$timezone = new DateTimeZone("Asia/Kolkata" );
$indate = new DateTime();
$indate->setTimezone($timezone );
$indate=$indate->format( 'Y-m-d' );



if(isset($_POST['submit'])){
  $barcode=$_POST['barcode'];
  $customer1=$_POST['customer'];
  $customer=mysqli_real_escape_string($conn,$customer1);

  $store1=$_POST['store'];
  $store=mysqli_real_escape_string($conn,$store1);

  if(isset($_POST['paymethod'])){
    $payment=$_POST['paymethod'];
}
else{
  $payment='';
}
// if($payment==''){

// }
  $mode=$_POST['mode'];
  if($mode=='SALE'){
    $mode='OUT';
  }
  $date=$_POST['date'];
  // $remark1=$_POST['remark'];
  // $remark=mysqli_real_escape_string($conn,$remark1);

  $pr_name=$_POST['pr_name'];
  $referel=$_POST['referel'];

$dp=$_POST['dp'];
  $mrp=$_POST['mrp'];
  $gst=$_POST['gst'];

  $qty=$_POST['qty'];

$reminder=$_POST['reminder'];
  

$dis=$_POST['dis'];
  
$net=$_POST['net'];
$category1 = isset($_POST['category']) ? $_POST['category'] : [];
$category = implode(',', $category1); 
$remark=$_POST['remark'];


  $total=$_POST['total'];
  $s_billamt=$_POST['FGST'];
$totaltamt=$_POST['FTotal'];
$invno=generate_order_code($conn);

  foreach($barcode as $index => $barcodes)
  {
  $s_barcode = $barcodes;
  
  $s_pr_name1=$pr_name[$index];
  $s_pr_name=mysqli_real_escape_string($conn,$s_pr_name1);
  $s_mrp=$mrp[$index];
  $s_gst=$gst[$index];

  $s_qty=(float)$qty[$index];
  $s_dis=$dis[$index];
    $s_net=$net[$index];
    $s_referel=$referel[$index];
  $s_dp=$dp[$index];
  $s_total=$total[$index];
 
//   $timezone = new DateTimeZone("Asia/Kolkata" );
// $indate = new DateTime();
// $indate->setTimezone($timezone );
// $indate=$indate->format( 'Y-m-d' );
if($s_barcode!='' || $s_barcode!='NULL'){
  
$sql8="select * from master where product_barcode='$s_barcode' and active=0";
            $res8=mysqli_query($conn,$sql8);
            $row8=mysqli_fetch_array($res8);
           
            // $mrp=$row8['mrp'];
       
         
          
            $m_mrp = $row8['mrp'];
     
           $landingm=$row8['landing'];
       
     $hsncode=$row8['hsncode'];
   $withoutgst=$row8['without_gst'];
   $s_rcp=$row8['rcp'];
    
        $pm_margin = $row8['pritvimart_margin'];
 
       
  
      $store_margin = $row8['store_margin'];
 
     
  
    $pm_profit = $row8['pm_profit'];

   


  $store_profit = $row8['store_profit'];

  $sgst = $row8['sgst'];
  $cgst = $row8['cgst'];
  $cess = $row8['cess'];

  }
  




  
  if($mode=='OUT'){
                
    $sql2="select * from stocktab where barcode='$s_barcode' and location='$store'";
    $res2=mysqli_query($conn,$sql2);
    $row2=mysqli_fetch_array($res2);
    if(mysqli_num_rows($res2)>=1){
    $close_stock=$row2['closing_stock'];
    if($close_stock==NULL){
      $close_stock=0;
    }
    $stock_out=$row2['stock_out'];
  if($stock_out==NULL){
    $stock_out=0;
  }
    $closestock=$close_stock-$s_qty;
    $stockout=$stock_out+$s_qty;
    
    
    $data = array(
      
      'stock_out' => $stockout,
      'closing_stock' => $closestock,
      'add_date' => $indate,
      'mode'  => $mode,
      );
    
      edit_row3('stocktab',$data,$conn,$s_barcode,$store);
    }
    else{
      $data = array(
        'barcode'  => $s_barcode,
        'product_name' => $s_pr_name,
        'location' => $store,
        'stock_out' => $s_qty,
       'closing_stock' => $s_qty,
       'add_date' => $indate,
       'mode'  => $mode,
        );
      
        insert_row('stocktab',$data,$conn);
    }
    }
$totalamount='NULL';
$s_expiry='NULL';
$sql1="insert into ordertab(invoiceno,customer,store,mode,barcode,product_name,dp,mrp,gst,qty,item_total_amt,billamount,billtallyamount,indate,delete_status,pr_status,payment,discount_percentage,reminder,hsncode,sgst,cgst,cess,netvalue,category,remark,referel) values('$invno','$customer','$store','$mode','$s_barcode','$s_pr_name','$s_dp','$s_mrp','$s_gst','$s_qty','$s_total','$s_billamt','$totaltamt','$indate',0,0,'$payment','$s_dis','$reminder','$hsncode','$sgst','$cgst','$cess','','$category','$remark','$s_referel')";
$res1=mysqli_query($conn,$sql1);
$orderid= mysqli_insert_id($conn);



$sql="insert into entry(vendor,store,mode,invno,indate,date_invoice,barcode,product_name,hsncode,gst,landing,qty,withoutgst,mrp,amount,total_billamount,totalamount,rcp,actual_dp,pm_margin,store_margin,pm_profit,store_profit,totaltallyamt,orderid,discount,payment,reminder,netvalue) values('$customer','$store','$mode','$invno','$indate','$indate','$s_barcode','$s_pr_name','$hsncode','$s_gst','$landingm','$s_qty',' $withoutgst','$m_mrp','$s_total','$s_billamt','$totalamount','$s_rcp','$s_dp','$pm_margin','$store_margin','$pm_profit','$store_profit','$totaltamt','$orderid','$s_dis','$payment','$reminder','$s_net')";
  $res=mysqli_query($conn,$sql);
  
//  var_dump($_POST['category']);
// exit; 
  
  
  $sql2="INSERT INTO expreport(invno,cate_id,name,income,ondate) values('$invno','$category','$customer','$s_net','$indate')";
$res2=mysqli_query($conn,$sql2);



  }

  // header("Location: invoice?id=$invno");
  echo '<script>window.open("billing.php?id=' . $invno . '", "_blank");</script>';     
        // $sql = "SELECT * FROM ordertab where invoiceno='$invno'";
        // $result = mysqli_query($conn,$sql);
        // while ($row = mysqli_fetch_array($result)) {
        //     echo "<p>Name: " . $row["invoiceno"] . "</p>";
        //     echo "<p>product_name: " . $row["product_name"] . "</p>";
        //     // Add more fields as needed
        // }
        // echo "<script>window.print();</script>";
  // $message = "Order Added Successfully";
  // echo "<script type='text/javascript'>alert('$message');</script>";
  // header("Location: billing");
  // echo '<script> window.location.href = "billing"; </script>';

  
}

?><!DOCTYPE html>

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

    <title>Invoice</title>

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
    <link rel="stylesheet"  href="css/invoice.css">

<script src="js/invoice.js"></script>
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
              <h4 class="py-3 mb-4">Billing</h4>

             
              <hr class="my-5" />
              <div class="container ">
                <a href="logout.php"><button type="button" class="btn btn-primary" style="margin-top:50px;background: #29913c;">Log Out</button></a>
                <a href="orders.php"><button type="button" class="btn btn-primary" style="margin-top:50px;background: #29913c;">Orders</button></a>
                    <div class="card" style="margin-top: 100px;margin-bottom: 100px;">
                        <div class="card-header text-center">
                          <h4>INVOICE</h4>
                        </div>
                        <div class="card-body">
                            <form action="" method="post" >
                            <div class="row">
                              
                                <div class="col-6">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text customer" >Customer</span>
                                       
                                        <?php
            
            
                                            $result=mysqli_query($conn,"select * from customer");
            
                                            echo"<select id='searchddl' name='customer' style='height:30px;' >";
                                            
                                             while($row=mysqli_fetch_array($result))
                                            {
                                                 echo"<option>$row[Fullname]</option>";
                                            }
                                            echo"</select>";
            
            
            
                                       ?>
                                       </div>
                                       </div>
            <div class="col-lg-4 col-md-6">
                      <div class="mt-3">
                        <!-- Button trigger modal -->
                        <button
                          type="button"
                          class="btn btn-primary"
                          data-bs-toggle="modal"
                          data-bs-target="#basicModal">
                          Add Customer
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1">Modal title</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-4 mt-2">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control fullname" id="fullname" type="text" name="fullname">
                            <label for="fullname">Name</label>
                        </div>
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col mb-2">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control mobno" id="mobno" type="text" name="mobno">
                            <label for="mobno">Mobile Number</label>
                        </div>
                    </div>
                    <div class="col mb-2">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control email" id="email" type="text" name="email">
                            <label for="email">Email</label>
                        </div>
                    </div>
                    <div class="col mb-2">
                        <div class="form-floating form-floating-outline">
                            <textarea class="form-control address" id="address" name="address"></textarea>
                            <label for="address">Address</label>
                        </div>
                    </div>
                </div>
                <div class="col mb-2">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control pin" id="pin" type="text" name="pin">
                        <label for="pin">GST number</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" name="addcus" onclick="Customer(this);" data-bs-dismiss="modal">Add Customer</button>
            </div>
        </div>
    </div>
</div>

                                       
            
                                    </div>
                        
                                   
                        
                                   
                                   
            
                                </div>
                                <div class="col-6">
                                <!-- <div class="input-group mb-3">
                                        <span class="input-group-text" >Inv. No</span>
                                        <input type="text" class="form-control invno" placeholder=""  name="invno" >
                                    </div> -->
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" >Store</span>
                                        <!-- <input type="text" class="form-control store" placeholder=""  name="store" > -->
                                        <select name="store" class="form-control store" data-live-search="true"   required>
                                        <option value="store">Store</option>
                                       
                                       
                                      </select>
                                    </div>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" >Date</span>
                                        <input type="text" class="form-control date"  name="date" value="<?=$tdate?>" required readonly>
                                    </div>
            
                                    <div class="input-group mb-3">
                                    <span class="input-group-text" >Mode</span>
                                    <select name="mode" class="form-control mode" data-live-search="true"   >
                                        <option value="OUT">SALE</option>
                                       
                                      </select>
                                    </div>
                                    <!-- <div class="input-group mb-3">
                                        <span class="input-group-text" >Remark</span>
                                        <input type="text" class="form-control"  name="remark" >
                                    </div> -->
                                    <!-- <div class="input-group mb-3">
                                        <span class="input-group-text" >Inv Date</span>
                                        <input type="date" class="form-control"  name="invdate" required>
                                    </div> -->
                                   
                                </div>
                            </div>
                            <!-- style="width:100px" -->
                          <div style="overflow-x:scroll">
                            <table class="table table-bordered">
                                <thead class="table-success">
                                  <tr>
                                    <th scope="col">#</th>
                                   
                                    <th scope="col" style="text-align:center;" required >Barcode</th>
                                    <th scope="col" class="" style="text-align:center;">Product Name</th>
                                   

                                    
                                    <th scope="col" class="" style="width:100px; text-align:center;"> WITHOUT GST</th>
                                    <th scope="col" class="" style="width:100px;text-align:center;">Discount%</th>

                                    <th scope="col" class="" style="width:100px; text-align:center;">Refferrel</th>
                                   
                                    <th scope="col" class="" style="width:100px;text-align:center;">GST</th>
                                    
                               
                                    <th scope="col" class="" style="width:100px;text-align:center;">Net value</th>

                                    
                                    <th scope="col" class="" style="width:100px;text-align:center;">QTY</th>

                                    <th scope="col" class="" style="width:150px;text-align:center;">Total</th>

                                    <th scope="col" class="NoPrint">                         
                                        <button type="button" class="btn btn-sm btn-success" onclick="BtnAdd()">+</button>
                                      
                                    </th>
            
                                  </tr>
                                </thead>
                                <tbody id="TBody">
                                  <tr id="TRow" >
                                    <th scope="row">1</th>
                                   
                                    <td><input style="width:100px;" type="text" class="form-control text-end barcode" name="barcode[]"  oninput="MasterT(this);"onchange="Offer(this);"></td>
                                    <td><input style="width:100px;" type="text" class="form-control text-end pr_name" name="pr_name[]" ></td>
                                   
                                 
                                   
                                    <td><input style="width:100px;" type="text" class="form-control text-end dp" name="dp[]" readonly value=""></td>
                                    <td><input style="width:100px;" type="text" class="form-control text-end dis" name="dis[]"  oninput="Offer(this);"></td>

                                    <td>
                                  
                                    <select class="form-control text-end" name="referel[]" style="width:100px;">
                    <?php
$query = "SELECT DISTINCT salesman FROM salesman WHERE active=0";
$result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<option value='" . $row['salesman'] . "'>" . $row['salesman'] . "</option>";
                    }
                    ?>
                </select></td> 
                                    <td><input style="width:100px;" type="text" class="form-control text-end gst" name="gst[]" ></td>
                                   
                                    <td><input style="width:100px;" type="text" class="form-control text-end net" name="net[]"  ></td>
     
                                    <td><input style="width:100px;" type="text" class="form-control text-end qty" name="qty[]"  oninput="CalcTo(this);"></td>

                                    <td><input style="width:100px;" type="text" class="form-control text-end total" name="total[]" value="" readonly></td>

                                    <td class="NoPrint"><button type="button" class="btn btn-sm btn-danger" onclick="BtnDel(this)">X</button></td>
            
                                  </tr>
                                </tbody>
                              </table>
                          </div>
            
                              <div class="row">
                               
                                <div class="col-6">
                                <div class="input-group mb-3">
                                        <span class="input-group-text" >Category</span>
                                        <select class="form-control text-end" name="category[]" style="width:100px;">
                    <?php
                    $query = "SELECT DISTINCT category FROM expcategory WHERE active=0";
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<option value='" . $row['category'] . "'>" . $row['category'] . "</option>";
                    }
                    ?>
                </select>                                    </div>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" >Total</span>
                                        <input type="number" class="form-control text-end totaltbillamt" id="FTotal" name="FTotal" readonly>
                                    </div>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" >Bill Amt</span>
                                        <input type="number" class="form-control text-end totalbillamt" id="FGST" name="FGST" required>
                                    </div>
                                 
                                    <!-- <div class="input-group mb-3">
                                        <span class="input-group-text" >Net Amt</span>
                                        <input type="number" class="form-control text-end" id="FNet" name="FNet" disabled="">
                                    </div> -->
            
            
                                </div>
                                     
                                <div class="col-6">
                                <div class="input-group mb-3">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" >Reminder Days</span>
                                        <input type="date" class="form-control remindday"  name="reminder" value="" >
                                    </div>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" >Remark</span>
                                        <input type="text" class="form-control remindday"  name="remark" value="" >
                                    </div>
                                    
                                <label for="payment">
                                                    Online Payment
                                                    <input type="radio" class="onilnepay" name="paymethod" value="Online Payment" id="payment">
                                                    <span class="checkmark"></span>
                                                </label>
                                           
                                           
                                                <label for="paypal" style="padding-left:50px;">
                                                    Credit
                                                    <input type="radio" class="credit" name="paymethod" value="Credit" id="paypal">
                                                    <span class="checkmark"></span>
                                                </label>
                                    </div>
                                   
                                
                                <button  type="submit" name="submit" class="btn btn-primary "  >Add</button>
                                    <!-- <button type="button" class="btn btn-primary"  onclick="GetPrint()">Print</button> -->
            
                                </div>
                            </div>
            
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
