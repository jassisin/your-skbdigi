<?php
require('session.php');
include('connection.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Fetch the existing expense details from the database
    $sql = "SELECT * FROM actual_expense WHERE slno = $id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    
    if (!$row) {
        // If no record found, redirect back to the expense list page
        header('Location: edit_actual_expense.php');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the updated values from the form
    $particulars = mysqli_real_escape_string($conn, $_POST['particulars']);
    $sub_particulars = mysqli_real_escape_string($conn, $_POST['sub_particulars']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    
    // Update the record in the database
    $update_sql = "UPDATE actual_expense SET particulars = '$particulars', sub_particulars = '$sub_particulars', amount = '$amount' WHERE slno = $id";
    
    if (mysqli_query($conn, $update_sql)) {
        // Redirect back to the expense forecast page after the update
        header('Location: edit_actual_expense.php');
        exit();
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
            background-color: #f4f7fa;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: #5f6368;
            margin-top: 20px;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 16px;
        }

        input[type="text"], input[type="number"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-btn {
            text-align: center;
            margin-top: 20px;
        }

        .back-btn a {
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }

        .back-btn a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Edit Expense</h2>
    <div class="container">
        <form method="POST">
            <label for="particulars">Particulars:</label>
            <input type="text" id="particulars" name="particulars" value="<?php echo htmlspecialchars($row['particulars']); ?>" required>

            <label for="sub_particulars">Sub Particulars:</label>
            <input type="text" id="sub_particulars" name="sub_particulars" value="<?php echo htmlspecialchars($row['sub_particulars']); ?>" required>

            <label for="amount">Amount:</label>
            <input type="number" id="amount" name="amount" value="<?php echo htmlspecialchars($row['amount']); ?>" required>

            <button type="submit">Update Expense</button>
        </form>

        <div class="back-btn">
            <a href="edit_actual_expense.php">Back to Expense Forecast</a>
        </div>
    </div>
</body>
</html>
