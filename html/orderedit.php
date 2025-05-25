<?php
require('session.php');

include('connection.php');
if(isset($_SESSION['main_admin'])){
  $username=$_SESSION['main_admin'];
}

if(isset($_GET['id'])&&($_GET['id']!=''))
{
  $id=$_GET['id'];
  $sql2="select * from ordertab where id='$id'";
  $res2=mysqli_query($conn,$sql2);
  $row=mysqli_fetch_array($res2);
  $invno=$row['invoiceno'];
  $customer=$row['customer'];
  $indate=$row['indate'];
  $store=$row['store'];
}



if(isset($_POST['submit'])){
    $edit=1;
 $tid=$_POST['tid'];
 $prname=$_POST['prname'];
 $dp=$_POST['dp'];
 $qty=$_POST['qty'];
 $itemamt=$_POST['itemamt'];
 $billtotal=$_POST['billtotal'];
 foreach($tid as $index => $tids)
 {
$s_tid=$tids;
$s_prname1=$prname[$index];
$s_prname=mysqli_real_escape_string($conn,$s_prname1);
$s_dp=$dp[$index];
$s_qty=$qty[$index];
$s_itemamt=$itemamt[$index];

$sql4="UPDATE ordertab SET qty = '$s_qty',item_total_amt='$s_itemamt',billamount='$billtotal',billtallyamount='$billtotal' WHERE id ='$s_tid'";
$res4=mysqli_query($conn,$sql4);
header("Location: orders.php");
echo '<script> window.location.href = "orders.php"; </script>';
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

    <title>Order edit</title>

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
            id="layout-navbar"  style="background-color: <?php echo $page_heading_color; ?>;">
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

          <!-- Content wrapper -->
          <div class="content-wrapper">
           
         
          <section class="checkout spad">
        <div class="container">
           
            <div class="checkout__form">
                <h4>Billing Details</h4>
                <form action="#">
                    <div class="row">
                        <div class="col-lg-12 col-md-6">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="checkout__input">
                                        <p>Customer Name</p>
                                        <input type="text" value="<?=$customer?>">
                                       
                                      
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="checkout__input">
                                        <p>Date</p>
                                        <input type="date" value="<?=$indate?>">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="checkout__input">
                                        <p>Invoice Number</p>
                                        <input type="text" value="<?=$invno?>" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="checkout__input">
                                        <p>Store</p>
                                        <input type="text" value="<?=$store?>" readonly>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                       
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- Checkout Section End -->
 <!-- Shoping Cart Section Begin -->
 <section class="shoping-cart spad"  style="padding-top:0px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping__cart__table">
                        <table>
                            <thead>
                                <tr>
                                    <th class="shoping__product">Products</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php
                                      $sql3="select * from ordertab where invoiceno='$invno' and pr_status=0";
                                      $res3=mysqli_query($conn,$sql3);
                                     while($row3=mysqli_fetch_array($res3)){

                                     
                                    ?>
                                    <form method="post">
                                    <td class="shoping__cart__item">
                                       
                                        <h5><input class=""name="prname[]" value="<?=$row3['product_name']?>" ></h5>
                                    </td>
                                    
                                    <td class="shoping__cart__quantity">
                                        <!-- <div class="quantity">
                                            <div class="pro-qty"> -->
                                                <input name="dp[]" class="dp inc" type="text" value="<?=$row3['dp']?>"  readonly>
                                            <!-- </div>
                                        </div> -->
                                    </td>
                                    <td class="shoping__cart__quantity" style="display:none;">
                                        <!-- <div class="quantity">
                                            <div class="pro-qty"> -->
                                            <input type="hidden" class="form-control text-end tid" name="tid[]" value="<?=$row3['id']?>">
                                            <!-- </div>
                                        </div> -->
                                    </td>
                                    <td class="shoping__cart__quantity">
                                        <!-- <div class="quantity">
                                            <div class="pro-qty"> -->
                                                <input name="qty[]" class="qty inc" type="text" value="<?=$row3['qty']?>" oninput="Totalcal(this);">
                                            <!-- </div>
                                        </div> -->
                                    </td>
                                    <td class="shoping__cart__quantity">
                                        <!-- <div class="quantity">
                                            <div class="pro-qty "> -->
                                                <input name="itemamt[]" class="itemamt inc" type="text" value="<?=$row3['item_total_amt']?>" readonly>
                                            <!-- </div>
                                        </div> -->
                                    </td>
                                    <td class="shoping__cart__item__close">
                                       
                                        <button class="icon_close closes" type="button" value="<?=$row3['id']?>" onclick="prDel(this);">Delete</button>
                                    </td>
                                </tr>
                            <?php
                                     }
                            ?>
                              
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                
                
                <div class="col-lg-6">
                    <div class="shoping__checkout">
                        <h5>Cart Total</h5>
                        <ul>
                            <li>Total <span>
                                <!-- <div class="pro-qty tot"> -->
                                                <input name="billtotal"class="TTotal inc" type="text"  id="TTotal" readonly>
                                            <!-- </div> -->
                                        </span></li>
                            <!-- <li>Total <span>$454.98</span></li> -->
                        </ul>
                        <button type="submit" name="submit" class="primary-btn">Save</button>
                                    </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
           
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
