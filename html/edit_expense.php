<?php
require('session.php');
include('connection.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM expense_forcast WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
}

if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $particulars = $_POST['particulars'];
    $frequency = $_POST['frequency'];
    $unitprice = $_POST['unitprice'];
    $quantity = $_POST['quantity'];
    $total = $_POST['total'];
    $follow_up_date = $_POST['follow_up_date'];
    $remarks = $_POST['remarks'];
    $vendor = $_POST['vendor'];
    $category = $_POST['category'];
     $category = $_POST['staff'];
    $date = $_POST['date'];

    $update_sql = "UPDATE expense_forcast 
                   SET particulars = '$particulars', 
                       frequency = '$frequency', 
                       unitprice = '$unitprice', 
                       quantity = '$quantity', 
                       total = '$total', 
                       follow_up_date = '$follow_up_date', 
                       remarks = '$remarks', 
                       vendor = '$vendor', 
                       category = '$category', 
                       staff = '$staff', 
                       date = '$date'
                   WHERE id = $id";

    if (mysqli_query($conn, $update_sql)) {
        header('Location:edit_forcast_expense.php');
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Expense</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        h4 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            font-size: 1.1rem;
            color: #555;
        }

        input[type="text"], input[type="date"], button {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
            color: #333;
        }

        input[type="text"]:focus, input[type="date"]:focus {
            outline: none;
            border-color: #007bff;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        button:hover {
            background-color: #0056b3;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            box-sizing: border-box;
        }

        .form-group input[type="text"], .form-group input[type="date"] {
            width: 100%;
        }

        /* Responsive styling for smaller screens */
        @media (max-width: 600px) {
            .container {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h4>Edit Expense</h4>
        <form method="post" action="">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            
            <div class="form-group">
                <label>Particulars:</label>
                <input type="text" name="particulars" value="<?php echo $row['particulars']; ?>" required>
            </div>

            <div class="form-group">
                <label>Frequency:</label>
                <input type="text" name="frequency" value="<?php echo $row['frequency']; ?>" required>
            </div>

            <div class="form-group">
                <label>Unit Price:</label>
                <input type="text" name="unitprice" value="<?php echo $row['unitprice']; ?>" required>
            </div>

            <div class="form-group">
                <label>Quantity:</label>
                <input type="text" name="quantity" value="<?php echo $row['quantity']; ?>" required>
            </div>

            <div class="form-group">
                <label>Total:</label>
                <input type="text" name="total" value="<?php echo $row['total']; ?>" required>
            </div>

            <div class="form-group">
                <label>Follow-up Date:</label>
                <input type="date" name="follow_up_date" value="<?php echo $row['follow_up_date']; ?>" required>
            </div>

            <div class="form-group">
                <label>Remarks:</label>
                <input type="text" name="remarks" value="<?php echo $row['remarks']; ?>" required>
            </div>

            <div class="form-group">
                <label>Vendor:</label>
                <input type="text" name="vendor" value="<?php echo $row['vendor']; ?>" required>
            </div>

            <div class="form-group">
                <label>Category:</label>
                <input type="text" name="category" value="<?php echo $row['category']; ?>" required>
            </div>
            <div class="form-group">
                <label>Staff:</label>
                <input type="text" name="staff" value="<?php echo $row['staff']; ?>" required>
            </div>

            <div class="form-group">
                <label>Date:</label>
                <input type="date" name="date" value="<?php echo $row['date']; ?>" required>
            </div>

            <div class="form-group">
                <button type="submit" name="update">Update</button>
            </div>
        </form>
    </div>
</body>
</html>
