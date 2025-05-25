<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch page settings from the database
$result = mysqli_query($conn, "SELECT * FROM page_settings WHERE id=1");
$page_settings = mysqli_fetch_assoc($result);

$page_heading = $page_settings['page_heading'];
$page_heading_color = $page_settings['page_heading_color'];
$footer_color = $page_settings['footer_color'];
$logo_image = $page_settings['logo_image'];
if (isset($_SESSION['role'])) {
  $role = $_SESSION['role'];
}

?>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="index.html" class="app-brand-link">
      <img src="../assets/img/<?php echo $logo_image; ?>" alt="Logo" class="w-px-40 h-auto rounded-circle" />
      <span class="app-brand-text demo menu-text fw-semibold ms-2" style="color: <?php echo $page_heading_color; ?>;"><?php echo $page_heading; ?></span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="mdi menu-toggle-icon d-xl-block align-middle mdi-20px"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <!-- Dashboards -->
    <?php if($role!='executive'){ ?>
    <li class="menu-item active">
      <a href="dashboard" class="menu-link">
        <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
        <div data-i18n="Analytics">Dashboard</div>
      </a>
    </li>

    <!-- Layouts -->
    <!--<li class="menu-item">-->
    <!--  <a href="javascript:void(0);" class="menu-link menu-toggle">-->
    <!--    <i class="menu-icon tf-icons mdi mdi-window-maximize"></i>-->
    <!--    <div data-i18n="Layouts">Settings</div>-->
    <!--  </a>-->

    <!--  <ul class="menu-sub">-->
       
      
    <!--  <li class="menu-item">-->
    <!--      <a href="new-resource.php" class="menu-link">-->
    <!--        <div data-i18n="Without menu">Business Analysis</div>-->
    <!--      </a>-->
    <!--    </li>-->
    <!--    <?php if($username=='admin1' || $username=='superadmin'){ ?>-->
    <!--    <li class="menu-item">-->
    <!--      <a href="settings.php" class="menu-link">-->
    <!--        <div data-i18n="Without menu">Settings</div>-->
    <!--      </a>-->
    <!--    </li>-->
    <!--    <li class="menu-item">-->
    <!--      <a href="admin-details.php" class="menu-link">-->
    <!--        <div data-i18n="Without menu">Admin Details</div>-->
    <!--      </a>-->
    <!--    </li>-->
    <!--    <li class="menu-item">-->
    <!--      <a href="explist.php" class="menu-link">-->
    <!--        <div data-i18n="Without menu">Detail Report</div>-->
    <!--      </a>-->
    <!--    </li>-->
    <!--    <?php } if($username=='superadmin'){ ?>-->
    <!--    <li class="menu-item">-->
    <!--      <a href="superadmin.php" class="menu-link">-->
    <!--        <div data-i18n="Without menu">Super Admin</div>-->
    <!--      </a>-->
    <!--    </li>-->
    <!--    <?php } ?>-->
    <!--  </ul>-->
    <!--</li>-->
    <!--<li class="menu-item">-->
    <!--  <a href="javascript:void(0);" class="menu-link menu-toggle">-->
    <!--    <i class="menu-icon tf-icons mdi mdi-window-maximize"></i>-->
    <!--    <div data-i18n="Layouts">CRM</div>-->
    <!--  </a>-->
    <!--  <?php }?>-->
    <!--  <ul class="menu-sub">-->
      
    <!--    <li class="menu-item">-->
    <!--      <a href="quick-address.php" class="menu-link">-->
    <!--        <div data-i18n="Without menu">Add Address (Quick)</div>-->
    <!--      </a>-->
    <!--    </li>-->
    <!--    <?php if($role!='executive'){ ?>-->
    <!--    <li class="menu-item">-->
    <!--      <a href="address.php" class="menu-link">-->
    <!--        <div data-i18n="Without menu">Add Address</div>-->
    <!--      </a>-->
    <!--    </li>-->
    <!--    <li class="menu-item">-->
    <!--      <a href="address-book.php" class="menu-link">-->
    <!--        <div data-i18n="Without menu">Address Book</div>-->
    <!--      </a>-->
    <!--    </li>-->
    
    <!--    <li class="menu-item">-->
    <!--      <a href="actions-list.php" class="menu-link">-->
    <!--        <div data-i18n="Without menu">Actions</div>-->
    <!--      </a>-->
    <!--    </li> -->
     
       
     
    <!--  </ul>-->
    <!--</li>-->
    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons mdi mdi-window-maximize"></i>
        <div data-i18n="Layouts">PBB</div>
      </a>
      <ul class="menu-sub">
      <!--<li class="menu-item">-->
      <!--<a href="javascript:void(0);" class="menu-link menu-toggle">-->
      <!--  <i class="menu-icon tf-icons mdi mdi-window-maximize"></i>-->
      <!--  <div data-i18n="Layouts">Purchase</div>-->
      <!--</a>-->
      <!--<ul class="menu-sub">-->
      <!--<li class="menu-item">-->
      <!--    <a href="seva-projects.php" class="menu-link">-->
      <!--      <div data-i18n="Without menu">New Service Entry</div>-->
      <!--    </a>-->
      <!--  </li> -->
      <!--  <li class="menu-item">-->
      <!--    <a href="product-entry.php" class="menu-link">-->
      <!--      <div data-i18n="Without menu">Purchase Entry</div>-->
      <!--    </a>-->
      <!--  </li>-->
      <!--  <li class="menu-item">-->
      <!--    <a href="master-adding.php" class="menu-link">-->
      <!--      <div data-i18n="Without menu">Product Master</div>-->
      <!--    </a>-->
      <!--  </li>-->
      <!--  <li class="menu-item">-->
      <!--    <a href="entry-list.php" class="menu-link">-->
      <!--      <div data-i18n="Without menu">Purchases</div>-->
      <!--    </a>-->
      <!--  </li>-->
      <!--  <li class="menu-item">-->
      <!--    <a href="add-vendor.php" class="menu-link">-->
      <!--      <div data-i18n="Without menu">Vendors</div>-->
      <!--    </a>-->
      <!--  </li>-->
      <!--  <li class="menu-item">-->
      <!--    <a href="stock-check.php" class="menu-link">-->
      <!--      <div data-i18n="Without menu">Stock Check</div>-->
      <!--    </a>-->
      <!--  </li>-->
      <!--  </ul>-->
      <!--  </li>-->
      <!--  <li class="menu-item">-->
      <!--<a href="javascript:void(0);" class="menu-link menu-toggle">-->
      <!--  <i class="menu-icon tf-icons mdi mdi-window-maximize"></i>-->
      <!--  <div data-i18n="Layouts">Bill</div>-->
      <!--</a>-->
      <!--<ul class="menu-sub">-->
      <!--<li class="menu-item">-->
      <!--    <a href="invoice.php" class="menu-link">-->
      <!--      <div data-i18n="Without menu">Invoice</div>-->
      <!--    </a>-->
      <!--  </li>-->
      <!--  <li class="menu-item">-->
      <!--    <a href="quick-invoice.php" class="menu-link">-->
      <!--      <div data-i18n="Without menu">Quick Invoice</div>-->
      <!--    </a>-->
      <!--  </li>-->
      <!--  <li class="menu-item">-->
      <!--    <a href="donations.php" class="menu-link">-->
      <!--      <div data-i18n="Without menu">Payments</div>-->
      <!--    </a>-->
      <!--  </li>-->
        <!-- <li class="menu-item">
      <!--    <a href="invoice.php" class="menu-link">-->
      <!--      <div data-i18n="Without menu">Invoice</div>-->
      <!--    </a>-->
      <!--  </li> -->-->
      <!--  <li class="menu-item">-->
      <!--    <a href="customer-list.php" class="menu-link">-->
      <!--      <div data-i18n="Without menu">Customers</div>-->
      <!--    </a>-->
      <!--  </li>-->
      <!--  </ul>-->
      <!--  </li>-->
        <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons mdi mdi-window-maximize"></i>
        <div data-i18n="Layouts">Budget</div>
      </a>
      <ul class="menu-sub">
       
        <!-- <li class="menu-item">
          <a href="production.php" class="menu-link">
            <div data-i18n="Without menu">Add Production</div>
          </a>
        </li> -->
        <!--<li class="menu-item">-->
        <!--  <a href="new-expenses.php" class="menu-link">-->
        <!--    <div data-i18n="Without menu">Expenses</div>-->
        <!--  </a>-->
        <!--</li>-->
        <li class="menu-item">
          <a href="forcast_expense.php" class="menu-link">
            <div data-i18n="Without menu">Forcast Expenses</div>
          </a>
        </li>
       
        <li class="menu-item">
          <a href="expcategory.php" class="menu-link">
            <div data-i18n="Without menu">Category</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="add-vendor.php" class="menu-link">
            <div data-i18n="Without menu">Vendor</div>
          </a>
        </li>
        <!--<li class="menu-item">-->
        <!--  <a href="ranking.php" class="menu-link">-->
        <!--    <div data-i18n="Without menu">Referal Ranking</div>-->
        <!--  </a>-->
        <!--</li>-->
        </ul>
        </li>
        <?php } ?>
      </ul>
    </li>

    <!--<li class="menu-header fw-medium mt-4">-->
    <!--  <span class="menu-header-text">Customer and Client Portal</span>-->
    <!--</li>-->
    <!--<li class="menu-item">-->
    <!--  <a href="javascript:void(0);" class="menu-link menu-toggle">-->
    <!--    <i class="menu-icon tf-icons mdi mdi-window-maximize"></i>-->
    <!--    <div data-i18n="Layouts">CCI</div>-->
    <!--  </a>-->
    <!--  <ul class="menu-sub">-->
    <!--    <li class="menu-item">-->
    <!--      <a href="upload-details.php" class="menu-link">-->
    <!--        <div data-i18n="Without menu">Upload Details</div>-->
    <!--      </a>-->
    <!--    </li>-->
    <!--    <li class="menu-item">-->
    <!--      <a href="add-course.php" class="menu-link">-->
    <!--        <div data-i18n="Without menu">Add Manual</div>-->
    <!--      </a>-->
    <!--    </li>-->
    <!--    <li class="menu-item">-->
    <!--      <a href="student-list.php" class="menu-link">-->
    <!--        <div data-i18n="Without menu">Customers</div>-->
    <!--      </a>-->
    <!--    </li>-->
    <!--    <li class="menu-item">-->
    <!--      <a href="delete-students.php" class="menu-link">-->
    <!--        <div data-i18n="Without menu">Customer Chats</div>-->
    <!--      </a>-->
    <!--    </li>-->
    <!--  </ul>-->
    <!--</li>-->
  </ul>
</aside>
