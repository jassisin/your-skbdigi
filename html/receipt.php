<?php
require('session.php');
include('connection.php');
include('functions.php');

$username = isset($_SESSION['main_admin']) ? $_SESSION['main_admin'] : '';
$prefix = isset($_SESSION['prefix']) ? $_SESSION['prefix'] : '';
$user = isset($_SESSION['user']) ? $_SESSION['user'] : '';

$timezone = new DateTimeZone("Asia/Kolkata");
$tdate1 = new DateTime();
$tdate1->setTimezone($timezone);
$tdate = $tdate1->format('d-m-Y');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function fill_unit_select_box($conn) {
    $output = '';
    $query = "SELECT * FROM itemstock ORDER BY itemname ASC";
    $result = $conn->query($query);

    foreach($result as $row) {
        $output .= '<option value="' . $row["itemname"] . '">' . $row["itemname"] . '</option>';
    }

    return $output;
}


if(isset($_POST['submit'])) {
    $receno=$_POST['receno'];

    $donor = $_POST['donor'];
    $store = 'store';
   
    $mode = 'OUT';
    $date = $_POST['date'];
    $reminder = $_POST['reminder'];
    $amount = $_POST['amount'];
   
    $proname = $_POST['proname'];
    $paymethod=$_POST['paymethod'];
    $reference=$_POST['reference'];
    $occation=$_POST['occation'];

    $totaltamt = $_POST['FTotal'];
    $invno = generate_order_code($conn);
   $sum=0;
   $indate = new DateTime();
$indate->setTimezone($timezone);
$indate = $indate->format('Y-m-d');

    foreach($receno as $index => $recenos)
    {
    $s_receno = $recenos;
        $s_prname = $proname[$index];
      
        $s_donor = $donor[$index];
        $s_reminder = $reminder[$index];
        $s_amount = $amount[$index];
        $sum=$sum+$s_amount;

        $sql2="SELECT * FROM sevapro WHERE projectname='$s_prname'";
  $res2 = mysqli_query($conn, $sql2);
  if(mysqli_num_rows($res2)>0){
    $row2 = mysqli_fetch_array($res2);
    $head=$row2['head'];
    $category=$row2['category'];
  }


  $sql4="UPDATE salesman SET amount='$sum' WHERE salesman='$reference'";
  $res4 = mysqli_query($conn, $sql4);


  $sql = "INSERT INTO receipt (recno, store, mode, receiptdate, receiptno, donor, proname, amount, remindate, payment, reference, occation, head,category,active, added_by,indate)
  VALUES ('$invno', '$store', '$mode', '$date', '$s_receno', '$s_donor', '$s_prname', '$s_amount', '$s_reminder', '$paymethod', '$reference', '$occation','$head','$category', '0', '$username',$indate)";

$res = mysqli_query($conn, $sql);

$sql2="INSERT INTO expreport(cate_id,invno,name,income,ondate) values('$category','$invno','$s_prname','$s_amount','$indate')";
$res2=mysqli_query($conn,$sql2);
       
       
      


    }

    echo '<script>window.open("receiptbill.php?id=' . $invno . '", "_blank");</script>';
}
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Invoice</title>
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
    <link rel="stylesheet" href="css/invoice.css">
    <script src="js/invoice.js"></script>
    <script src="../assets/vendor/js/helpers.js"></script>
    <script src="../assets/js/config.js"></script>
</head>
<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php include 'header.php'; ?>
            <div class="layout-page">
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
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Invoices /</span> New Invoice</h4>
                        <div class="row">
                            <div class="col-xxl">
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Generate Invoice</h5>
                                        <small class="text-muted float-end">Invoice Form</small>
                                    </div>
                                    <div class="card-body">
                            <form action="" method="post" >
                            <div class="row">
                           

                            <div class="col-6">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text customer" >Donor</span>
                                       
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
                          Add Donor
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
                                    <!-- <div class="input-group mb-3">
                                        <span class="input-group-text" >Reference</span>
                                        <input type="text" class="form-control date"  name="reference" value=""  >
                                    </div> -->
                                    <div class="input-group mb-3">
                                        <span class="input-group-text customer" >Reference</span>
                                       
                                        <?php
            
            
                                            $result=mysqli_query($conn,"select * from salesman where active=0");
            
                                            echo"<select id='searchddl' name='reference' style='height:30px;' >";
                                            
                                             while($row=mysqli_fetch_array($result))
                                            {
                                                 echo"<option>$row[salesman]</option>";
                                            }
                                            echo"</select>";
            
            
            
                                       ?>
                                       </div>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" >Occation</span>
                                        <input type="text" class="form-control date"  name="occation" value=""  >
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
                            <a href="#"><button type="button" class="btn btn-primary" style="margin-top:50px;background: #29913c;">UPDATE / DELETE</button></a><BR>

                            <table class="table table-bordered">
                                <thead class="table-success">
                                  <tr>
                                    <th scope="col">#</th>
                                   
                                    <th scope="col" class="" style="text-align:center;">Receipt No
                               
                                    </th>
                                   
                                 
                                    
                                    <th scope="col" class="" style="width:100px; text-align:center;"> Donor</th>
                                  
                                    
                                    
                                    <th scope="col" class="" style="width:100px;text-align:center;">Project name</th>
            
                                    <th scope="col" class="" style="width:150px;text-align:center;">Amount</th>
                                    <th scope="col" class="" style="width:150px;text-align:center;">Reminder date</th>

                                    <th scope="col" class="NoPrint">                         
                                        <button type="button" class="btn btn-sm btn-success" onclick="BtnAdd()">+</button>
                                      
                                    </th>
            
                                  </tr>
                                </thead>
                                <tbody id="TBody">
                                  <tr id="TRow" >
                                    <th scope="row">1</th>
                                   
                                    <td>
                                    <!-- <input style="width:100px;" type="text" class="form-control text-end pr_name" name="pr_name[]" > -->
                                  
       <input style="width:100px;" type="text" class="form-control text-end receno" name="receno[]"  ></td>
                                   
                                   

            
                                   
                                    <td><input style="width:100px;" type="text" class="form-control text-end donor" name="donor[]"  value="" ></td>
                                   
                                    <td>
                <!-- Convert the input field into a select box -->
                <select class="form-control text-end" name="proname[]" style="width:100px;">
                    <?php
                    $query = "SELECT projectname FROM sevapro WHERE active=0";
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<option value='" . $row['projectname'] . "'>" . $row['projectname'] . "</option>";
                    }
                    ?>
                </select>
            </td>
            
                                    <td><input style="width:100px;" type="text" class="form-control text-end total" name="amount[]" value="" oninput="GetTotalt();"></td>
                                    <td ><input  type="date" class="form-control reminder"  name="reminder[]" required></td>

                                    <td class="NoPrint"><button type="button" class="btn btn-sm btn-danger" onclick="BtnDel(this)">X</button></td>
            
                                  </tr>
                                </tbody>
                              </table>
                          </div>
            
                              <div class="row">
                               
                                <div class="col-6">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" >Total</span>
                                        <input type="number" class="form-control text-end totaltbillamt" id="FTotal" name="FTotal" readonly>
                                    </div>
                                   
                                 
                                    <!-- <div class="input-group mb-3">
                                        <span class="input-group-text" >Net Amt</span>
                                        <input type="number" class="form-control text-end" id="FNet" name="FNet" disabled="">
                                    </div> -->
            
            
                                </div>
                                     
                                <div class="col-6">
                               
                             
            
                                    
                                <label for="payment">
                                                    Check
                                                    <input type="radio" class="onilnepay" name="paymethod" value="Online Payment" id="payment">
                                                    <span class="checkmark"></span>
                                                </label>
                                                   
                                <label for="payment">
                                                    DD
                                                    <input type="radio" class="onilnepay" name="paymethod" value="DD" id="payment">
                                                    <span class="checkmark"></span>
                                                </label>
                                           
                                           
                                                <label for="paypal" style="padding-left:50px;">
                                                    Cash
                                                    <input type="radio" class="credit" name="paymethod" value="Cash" id="paypal">
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
                    </div></div></div>
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
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
    </div>
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
