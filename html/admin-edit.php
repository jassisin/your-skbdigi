<?php
require "session.php";
include "connection.php";

// Set page-specific variables
$page_title = "Edit";
$page_description = "Edit page description";
$page_heading_color = "#6f42c1"; // Purple color
$footer_color = "#f8f9fa"; // Light gray

// Set username
if (isset($_SESSION["main_admin"])) {
    $username = $_SESSION["main_admin"];
} else {
    $username = "Guest";
}
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM admin_log WHERE id = $id";
    $res = mysqli_query($conn, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $userData = mysqli_fetch_assoc($res);
    }
}
// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'create_user') {
    $Name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $log_date = date('Y-m-d H:i:s');
    $ac_tive = '0';

    $sql = "INSERT INTO admin_log (Name, username, password, role, log_date, ac_tive, email)
            VALUES ('$Name', '$username', '$password', '$role', '$log_date', '$ac_tive', '$email')";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
    }
    exit;
}

// Include header
include "header_section.php";
?>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Add Users</title>

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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            color: #333;
         }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .section-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 700;
            color: #4f46e5;
            margin-bottom: 8px;
        }

        .section-subtitle {
            color: #6b7280;
            font-size: 1rem;
        }

        /* Custom responsive fixes */
        .layout-navbar {
            min-height: 70px;
            padding: 0;
            width: 100%;
        }
        
        .layout-navbar .container-xxl {
            padding: 0.75rem 1rem;
        }
        
        .content-footer {
            min-height: 70px;
            padding: 0;
            width: 100%;
        }
        
        .content-footer .container-xxl {
            padding: 0.75rem 1rem;
        }
        
        /* Username visibility fix */
        .navbar .nav-item span {
            color: #ffffff !important;
            font-weight: 500;
        }
        
        /* Dropdown text visibility */
        .dropdown-item-text span {
            color: #333 !important;
        }
        .section-header {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
        }
        
        /* Mobile responsive adjustments */
        @media (max-width: 768px) {
            .container { 
                padding: 12px; 
            }
            
            .header-content { 
                flex-direction: column; 
                align-items: stretch; 
            }
            
            .layout-navbar .container-xxl {
                padding: 0.5rem 0.75rem;
            }
            
            .content-footer .container-xxl {
                padding: 0.5rem 0.75rem;
            }
            
            .layout-navbar {
                min-height: 60px;
            }
            
            .content-footer {
                min-height: 60px;
            }
            
            .navbar-nav .nav-item {
                margin: 0.25rem 0;
            }
            
            .footer-container {
                text-align: center;
            }
            
            .footer-container .d-none.d-lg-inline-block {
                display: block !important;
                margin-top: 0.5rem;
            }
            
        }
        
        @media (max-width: 576px) {
            .layout-navbar .navbar-nav-right {
                flex-wrap: wrap;
            }
            
            .layout-navbar .navbar-nav.align-items-center {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            
            .layout-navbar .navbar-nav.flex-row {
                justify-content: center;
            }
        } 
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: 0.3s;
         }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
        }
    </style>

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
    <script src="../assets/js/config.js"></script>
  </head>
  <body>
         <div class="content-wrapper">
             <div class="container">

        <!-- Section Header -->
        <div class="section-header">
            <div class="header-content">
                <div>
                    <h1 class="section-title" id="sectionTitle">Add New User</h1>
                    <p class="section-subtitle" id="sectionSubtitle">Manage user and Role permission</p>
                </div>
            </div>
        </div>
        <!-- User Form -->
  <div class="section-header">
    <form id="userForm" method="post">
   <div class="form-group">
       <label class="form-label" for="username">Username *</label>
       <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($userData['username'] ?? '') ?>" required>
   </div>
   <div class="form-group">
       <label class="form-label" for="name">Name *</label>
       <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($userData['Name'] ?? '') ?>" required>
   </div>
   <div class="form-group">
        <label class="form-label" for="userEmail">Email *</label>
        <input type="email" class="form-control" id="userEmail" name="email" value="<?= htmlspecialchars($userData['Email'] ?? '') ?>" required>
   </div>
   <div class="form-group" id="passwordGroup">
       <label class="form-label" for="userPassword">Password *</label>
       <input type="password" class="form-control" id="userPassword" name="password" value="<?= htmlspecialchars($userData['password'] ?? '') ?>" required>
   </div>
   <div class="form-group">
        <label class="form-label" for="role">Role *</label>
        <select class="form-control" id="role" name="role" required>
           <option value="">Select Role</option> 
          <option value="ADMIN" <?= (isset($userData['role']) && $userData['role']=='ADMIN') ? 'selected' : '' ?>>ADMIN</option>  
           <option value="NURSING" <?= (isset($userData['role']) && $userData['role']=='NURSING') ? 'selected' : '' ?>>NURSING</option>
           <option value="MEDICAL" <?= (isset($userData['role']) && $userData['role']=='MEDICAL') ? 'selected' : '' ?>>MEDICAL</option>
           <option value="DENTAL" <?= (isset($userData['role']) && $userData['role']=='DENTAL') ? 'selected' : '' ?>>DENTAL</option>
           <option value="PHARMACY" <?= (isset($userData['role']) && $userData['role']=='PHARMACY') ? 'selected' : '' ?>>PHARMACY</option>
           <option value="STAFF" <?= (isset($userData['role']) && $userData['role']=='STAFF') ? 'selected' : '' ?>>STAFF</option>
        </select>
   </div>
   <br/>
           <div class="class="header-actions">
                       <button type="button" class="btn btn-secondary" id="createUserBtn"  onclick="onclickBack()">
                            <i class="fas fa-plus"></i> Back
                        </button>
                        <button type="button" class="btn btn-primary" id="createUserBtn"  onclick="createUser()">
                            <i class="fas fa-plus"></i> Create/Update User
                        </button>
                    </div>
</form>
</div>
</div>
</body>
<script>
function createUser() {
    const form = document.getElementById('userForm');
    const formData = new FormData(form);
    formData.append('action', 'create_user');

    fetch('admin-edit.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            form.reset();
        } else {
            alert('Error: ' + data.message);
        }
    });
}
</script>
<?php // Include footer
include "footer_section.php";
?>
