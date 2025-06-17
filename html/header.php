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

<style>
/* Enhanced Sidebar Styling */
.layout-menu {
  transition: all 0.3s ease;
  box-shadow: 2px 0 10px rgba(0,0,0,0.1);
  border-right: 1px solid rgba(0,0,0,0.08);
}

.app-brand {
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid rgba(0,0,0,0.08);
  background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
}

.app-brand-link {
  display: flex;
  align-items: center;
  text-decoration: none;
  transition: all 0.3s ease;
}

.app-brand-link:hover {
  transform: translateX(2px);
}

.app-brand img {
  border: 2px solid rgba(255,255,255,0.2);
  transition: all 0.3s ease;
}

.app-brand-link:hover img {
  transform: scale(1.05);
  border-color: rgba(255,255,255,0.4);
}

.app-brand-text {
  font-size: 1.25rem;
  font-weight: 600;
  letter-spacing: -0.025em;
}

.menu-inner {
  padding: 0.5rem 0;
}

.menu-item {
  margin: 0.125rem 0.75rem;
}

.menu-link {
  display: flex;
  align-items: center;
  padding: 0.75rem 1rem;
  border-radius: 0.5rem;
  text-decoration: none;
  color: rgba(67, 89, 113, 0.8);
  transition: all 0.3s ease;
  position: relative;
  font-weight: 500;
}

.menu-link:hover {
  background: linear-gradient(135deg, rgba(115, 103, 240, 0.08) 0%, rgba(115, 103, 240, 0.04) 100%);
  color: #7367f0;
  transform: translateX(4px);
  box-shadow: 0 2px 8px rgba(115, 103, 240, 0.15);
}

.menu-link.active,
.menu-item.active > .menu-link {
  background: linear-gradient(135deg, #7367f0 0%, #9c88ff 100%);
  color: white;
  box-shadow: 0 4px 12px rgba(115, 103, 240, 0.4);
}

.menu-icon {
  width: 1.375rem;
  height: 1.375rem;
  margin-right: 0.75rem;
  opacity: 0.8;
  transition: all 0.3s ease;
}

.menu-link:hover .menu-icon,
.menu-link.active .menu-icon {
  opacity: 1;
  transform: scale(1.1);
}

.menu-toggle::after {
  content: '\f0142';
  font-family: 'Material Design Icons';
  position: absolute;
  right: 1rem;
  transition: transform 0.3s ease;
  font-size: 1rem;
}

.menu-toggle[aria-expanded="true"]::after {
  transform: rotate(90deg);
}

.menu-sub {
  background: rgba(0,0,0,0.02);
  border-radius: 0.5rem;
  margin: 0.25rem 0;
  padding: 0.25rem 0;
  border-left: 2px solid rgba(115, 103, 240, 0.2);
  margin-left: 2.5rem;
}

.menu-sub .menu-item {
  margin: 0.125rem 0.5rem;
}

.menu-sub .menu-link {
  padding: 0.5rem 1rem;
  font-size: 0.875rem;
  font-weight: 500;
}

.menu-sub .menu-link:hover {
  background: rgba(115, 103, 240, 0.06);
  border-left: 3px solid #7367f0;
  margin-left: -3px;
}

.layout-menu-toggle {
  padding: 0.5rem;
  border-radius: 0.375rem;
  transition: all 0.3s ease;
}

.layout-menu-toggle:hover {
  background: rgba(115, 103, 240, 0.1);
  color: #7367f0;
}

/* Mobile Responsive */
@media (max-width: 1200px) {
  .layout-menu {
    position: fixed;
    top: 0;
    left: -280px;
    height: 100vh;
    z-index: 1050;
    transition: left 0.3s ease;
  }
  
  .layout-menu.show {
    left: 0;
    box-shadow: 0 0 20px rgba(0,0,0,0.3);
  }
}

@media (max-width: 768px) {
  .app-brand {
    padding: 1rem;
  }
  
  .app-brand-text {
    font-size: 1.1rem;
  }
  
  .menu-item {
    margin: 0.125rem 0.5rem;
  }
  
  .menu-link {
    padding: 0.625rem 0.75rem;
    font-size: 0.875rem;
  }
  
  .menu-sub {
    margin-left: 1.5rem;
  }
}

/* Scrollbar Styling */
.menu-inner::-webkit-scrollbar {
  width: 4px;
}

.menu-inner::-webkit-scrollbar-track {
  background: transparent;
}

.menu-inner::-webkit-scrollbar-thumb {
  background: rgba(115, 103, 240, 0.3);
  border-radius: 2px;
}

.menu-inner::-webkit-scrollbar-thumb:hover {
  background: rgba(115, 103, 240, 0.5);
}

/* Badge/Counter Styling */
.menu-badge {
  background: linear-gradient(135deg, #ff4757 0%, #ff3742 100%);
  color: white;
  font-size: 0.75rem;
  padding: 0.125rem 0.375rem;
  border-radius: 0.75rem;
  margin-left: auto;
  font-weight: 600;
}
</style>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="index.html" class="app-brand-link">
      <img src="../assets/img/<?php echo $logo_image; ?>" alt="Logo" class="w-px-40 h-auto rounded-circle" />
      <span class="app-brand-text demo menu-text fw-semibold ms-2" style="color: <?php echo $page_heading_color; ?>;"><?php echo $page_heading; ?></span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
      <i class="mdi menu-toggle-icon d-block align-middle mdi-20px"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <?php if($role!='executive'){ ?>
    
    <!-- Token Management Section -->
    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle" data-bs-toggle="collapse" data-bs-target="#tokenMenu" aria-expanded="false">
        <i class="menu-icon tf-icons mdi mdi-ticket-account"></i>
        <div data-i18n="Layouts">Token Management</div>
      </a>
      <ul class="menu-sub collapse" id="tokenMenu">
        <li class="menu-item">
          <a href="reception.php" class="menu-link">
            <i class="menu-icon tf-icons mdi mdi-desk"></i>
            <div data-i18n="Without menu">Reception</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="nursing.php" class="menu-link">
            <i class="menu-icon tf-icons mdi mdi-medical-bag"></i>
            <div data-i18n="Without menu">Nursing</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="medical.php" class="menu-link">
            <i class="menu-icon tf-icons mdi mdi-stethoscope"></i>
            <div data-i18n="Without menu">Medical</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="dental.php" class="menu-link">
            <i class="menu-icon tf-icons mdi mdi-tooth"></i>
            <div data-i18n="Without menu">Dental</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="pharmacy.php" class="menu-link">
            <i class="menu-icon tf-icons mdi mdi-pill"></i>
            <div data-i18n="Without menu">Pharmacy</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="marketing.php" class="menu-link">
            <i class="menu-icon tf-icons mdi mdi-bullhorn"></i>
            <div data-i18n="Without menu">Marketing</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="office.php" class="menu-link">
            <i class="menu-icon tf-icons mdi mdi-office-building"></i>
            <div data-i18n="Without menu">Office</div>
          </a>
        </li>
      </ul>
    </li>

    <!-- PBB Section -->
    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle" data-bs-toggle="collapse" data-bs-target="#pbbMenu" aria-expanded="false">
        <i class="menu-icon tf-icons mdi mdi-chart-line"></i>
        <div data-i18n="Layouts">PBB Management</div>
      </a>
      <ul class="menu-sub collapse" id="pbbMenu">
        <li class="menu-item">
          <a href="dashboard" class="menu-link">
            <i class="menu-icon tf-icons mdi mdi-view-dashboard"></i>
            <div data-i18n="Analytics">Dashboard</div>
          </a>
        </li>
        
        <!-- Budget Submenu -->
        <li class="menu-item">
          <a href="javascript:void(0);" class="menu-link menu-toggle" data-bs-toggle="collapse" data-bs-target="#budgetMenu" aria-expanded="false">
            <i class="menu-icon tf-icons mdi mdi-calculator"></i>
            <div data-i18n="Layouts">Budget & Finance</div>
          </a>
          <ul class="menu-sub collapse" id="budgetMenu">
            <li class="menu-item">
              <a href="forcast_expense.php" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-trending-up"></i>
                <div data-i18n="Without menu">Forecast Expenses</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="expcategory.php" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-tag-multiple"></i>
                <div data-i18n="Without menu">Categories</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="add-vendor.php" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-store"></i>
                <div data-i18n="Without menu">Vendor Management</div>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </li>

    <!-- Role Management Section -->
    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle" data-bs-toggle="collapse" data-bs-target="#roleMenu" aria-expanded="false">
        <i class="menu-icon tf-icons mdi mdi-account-group"></i>
        <div data-i18n="Layouts">Role Management</div>
      </a>
      <ul class="menu-sub collapse" id="roleMenu">
        <li class="menu-item">
          <a href="admin-edit.php" class="menu-link">
            <i class="menu-icon tf-icons mdi mdi-account-cog"></i>
            <div data-i18n="Without menu">Manage Role</div>
          </a>
        </li>
      </ul>
    </li>
    
    <?php } ?>
  </ul>
</aside>

<!-- Mobile Menu Overlay -->
<div class="layout-overlay layout-menu-toggle d-xl-none" onclick="toggleMobileMenu()"></div>

<script>
// Enhanced menu functionality
document.addEventListener('DOMContentLoaded', function() {
  // Initialize menu toggles
  const menuToggles = document.querySelectorAll('.menu-toggle');
  
  menuToggles.forEach(toggle => {
    toggle.addEventListener('click', function(e) {
      e.preventDefault();
      
      const target = this.getAttribute('data-bs-target');
      const submenu = document.querySelector(target);
      
      if (submenu) {
        const isExpanded = this.getAttribute('aria-expanded') === 'true';
        
        // Close other open menus
        menuToggles.forEach(otherToggle => {
          if (otherToggle !== this) {
            otherToggle.setAttribute('aria-expanded', 'false');
            const otherTarget = otherToggle.getAttribute('data-bs-target');
            const otherSubmenu = document.querySelector(otherTarget);
            if (otherSubmenu) {
              otherSubmenu.classList.remove('show');
            }
          }
        });
        
        // Toggle current menu
        this.setAttribute('aria-expanded', !isExpanded);
        submenu.classList.toggle('show');
      }
    });
  });
  
  // Set active menu item
  const currentPage = window.location.pathname.split('/').pop();
  const menuLinks = document.querySelectorAll('.menu-link[href]');
  
  menuLinks.forEach(link => {
    const href = link.getAttribute('href');
    if (href === currentPage || href.includes(currentPage)) {
      link.classList.add('active');
      
      // Expand parent menus
      let parentMenu = link.closest('.menu-sub');
      while (parentMenu) {
        parentMenu.classList.add('show');
        const parentToggle = document.querySelector(`[data-bs-target="#${parentMenu.id}"]`);
        if (parentToggle) {
          parentToggle.setAttribute('aria-expanded', 'true');
        }
        parentMenu = parentMenu.parentElement.closest('.menu-sub');
      }
    }
  });
});

// Mobile menu toggle
function toggleMobileMenu() {
  const menu = document.getElementById('layout-menu');
  const overlay = document.querySelector('.layout-overlay');
  
  menu.classList.toggle('show');
  overlay.style.display = menu.classList.contains('show') ? 'block' : 'none';
}

// Close mobile menu when clicking menu items
document.querySelectorAll('.menu-link[href]').forEach(link => {
  link.addEventListener('click', function() {
    if (window.innerWidth <= 1200) {
      const menu = document.getElementById('layout-menu');
      const overlay = document.querySelector('.layout-overlay');
      
      menu.classList.remove('show');
      overlay.style.display = 'none';
    }
  });
});

// Handle window resize
window.addEventListener('resize', function() {
  if (window.innerWidth > 1200) {
    const menu = document.getElementById('layout-menu');
    const overlay = document.querySelector('.layout-overlay');
    
    menu.classList.remove('show');
    overlay.style.display = 'none';
  }
}); 
</script>