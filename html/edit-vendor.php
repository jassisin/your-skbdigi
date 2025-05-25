<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('session.php');
include('connection.php');

if (!isset($_SESSION['main_admin'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['main_admin'];

if (isset($_GET['id']) && $_GET['id'] != '') {
    $id = intval($_GET['id']); // Sanitize ID as an integer
    // Fetch the vendor details
    $sql = "SELECT * FROM itemstock WHERE id='$id'";
    $result = mysqli_query($conn, $sql);
    $vendor = mysqli_fetch_assoc($result);

    if (!$vendor) {
        echo "<script>alert('Vendor not found.'); window.location.href = 'add-vendor.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Invalid vendor ID.'); window.location.href = 'add-vendor.php';</script>";
    exit;
}

// Handle form submission for updating vendor details
if (isset($_POST['update'])) {
    $vname = mysqli_real_escape_string($conn, $_POST['vname']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mob']);
    $gst = mysqli_real_escape_string($conn, $_POST['gst']);
    $remark = mysqli_real_escape_string($conn, $_POST['rmk']);

    if (!empty($vname) && !empty($mobile) && !empty($gst) && !empty($remark)) {
        $updateSql = "UPDATE itemstock SET 
                        itemname='$vname', 
                        mobile='$mobile', 
                        gstno='$gst', 
                        remark='$remark' 
                      WHERE id='$id'";
        $updateResult = mysqli_query($conn, $updateSql);

        if ($updateResult) {
            echo "<script>alert('Vendor details updated successfully.'); window.location.href = 'add-vendor.php';</script>";
        } else {
            echo "<script>alert('Error updating vendor details.');</script>";
        }
    } else {
        echo "<script>alert('Please fill in all fields.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vendor</title>
    <link rel="stylesheet" href="../assets/vendor/css/core.css">
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css">
    <link rel="stylesheet" href="../assets/css/demo.css">
</head>
<body>
    <div class="container">
        <h2>Edit Vendor</h2>
        <form method="post">
            <div class="mb-3">
                <label for="vname" class="form-label">Vendor Name</label>
                <input type="text" class="form-control" id="vname" name="vname" value="<?= htmlspecialchars($vendor['itemname']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="mob" class="form-label">Mobile</label>
                <input type="text" class="form-control" id="mob" name="mob" value="<?= htmlspecialchars($vendor['mobile']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="gst" class="form-label">GST Number</label>
                <input type="text" class="form-control" id="gst" name="gst" value="<?= htmlspecialchars($vendor['gstno']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="rmk" class="form-label">Remarks</label>
                <input type="text" class="form-control" id="rmk" name="rmk" value="<?= htmlspecialchars($vendor['remark']) ?>" required>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update</button>
            <a href="add-vendor.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
