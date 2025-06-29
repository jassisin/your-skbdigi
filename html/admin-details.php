<?php
require('session.php');
include('connection.php');

// Set username
if(isset($_SESSION['main_admin'])){
    $username = $_SESSION['main_admin'];
} else {
    $username = 'Guest';
}


// Fetch data from admin_log table
$query = "SELECT * FROM admin_log WHERE ac_tive = 1 ORDER BY Name DESC";
$result = mysqli_query($conn, $query);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_id') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    // Soft delete: set ac_tive = 1 for the user
    $sql = "DELETE From admin_log  WHERE id = '$id'";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
    }
    exit;
}
include('header_section.php');
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Role Management</title>

    <link rel="stylesheet" href="../assets/vendor/css/core.css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            color: #333;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            max-width: 1400px;
            margin: auto;
            padding: 20px;
        }

        .section-header {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
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
        }

        .section-subtitle {
            color: #6b7280;
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

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .user-table-container {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
        }

        .table-header {
            padding: 20px;
            border-bottom: 1px solid #e2e8f0;
            background: #f9fafb;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
        }

        .user-table th,
        .user-table td {
            padding: 16px 12px;
            text-align: left;
        }

        .user-table th {
            background: #4f46e5;
            color: #fff;
            font-size: 13px;
            text-transform: uppercase;
        }

        .user-table tr:hover {
            background: #f9fafb;
        }

        .user-table td {
            border-bottom: 1px solid #e2e8f0;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-sm {
            padding: 8px 12px;
            font-size: 14px;
            min-width: 40px;
            justify-content: center;
        }

        .role-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .role-admin {
            background: #fef3c7;
            color: #92400e;
        }

        .role-super-admin {
            background: #dbeafe;
            color: #1e40af;
        }

        .role-moderator {
            background: #d1fae5;
            color: #065f46;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #6b7280;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .container { padding: 12px; }
            .header-content { flex-direction: column; align-items: stretch; }
            .user-table th, .user-table td { padding: 8px; font-size: 12px; }
            .action-buttons { flex-direction: column; }
        }
    </style>

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
                        <h1 class="section-title">Role Management</h1>
                        <p class="section-subtitle">Manage admin accounts and permissions</p>
                    </div>
                    <div class="header-actions">
                        <button class="btn btn-primary" onclick="window.location.href='admin-edit.php'">
                            <i class="fas fa-plus"></i> Add New User
                        </button>
                    </div>
                </div>
            </div>

            <!-- User List Table -->
            <div class="user-table-container">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                      <tbody class="table-border-bottom-0">
                    <?php
                    if($username=='superadmin' ){
                      $sql="select id, Name, role,username,Email from admin_log where ac_tive='0'";

                    }
                    elseif($username=='admin'||$username=='admin1'){
                      $sql="select id, Name, role,username,Email from admin_log where ac_tive='0' and id NOT IN (8)";

                    }
                    else{
                      $sql="select id, Name, role,username,Email from admin_log where ac_tive='0' and id NOT IN (1,2,8)";
                    }
                      $res=mysqli_query($conn,$sql);
                      if(mysqli_num_rows($res)>0)
                    {
                       while($row=mysqli_fetch_array($res))
                      { ?>     
                      <tr>
                       <td><?= $row['Name']; ?></td>
                       <td><?= $row['username']; ?></td>
   
                       <td><?= $row['Email']; ?></td>
                       <td><?= $row['role']; ?></td>
                       <td>
                            <a href="admin-edit.php?id=<?php echo $row[
                                           "id"
                                       ]; ?>" title="Edit">
                                       <i class="mdi mdi-pencil" style="font-size:20px;color:#1976d2;"></i>
                            </a>
                        </td>
                        <td>
                         <form method="post" action="" onsubmit="return confirm('Are you sure you want to delete this patient?');" style="display:inline;">
                          <input type="hidden" name="delete_id" value="<?php echo $row["id"]; ?>">
                          <input type="hidden" name="action" value="delete_id">
                          <button type="submit" name="delete" class="btn btn-link p-0" title="Delete">
                                <i class="mdi mdi-trash-can" style="font-size:20px;color:#d32f2f;"></i>
                         </button>
                        </form>
                        </td>
                        <?php 
                      }
                     }?>
                      </tr>
                   
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function editUser(username) {
            // Redirect to edit page with username parameter
            window.location.href = 'admin-edit.php?username=' + encodeURIComponent(username);
        }

        function deleteUser(username) {
            if (confirm('Are you sure you want to delete this user?')) {
                // Send AJAX request to delete user
                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=delete_user&username=' + encodeURIComponent(username)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Reload page to update table
                    } else {
                        alert('Error deleting user: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the user');
                });
            }
        }
    </script>
</body>
</html>

<?php 
// Close database connection
if (isset($conn)) {
    mysqli_close($conn);
}
include('footer_section.php'); 
?>