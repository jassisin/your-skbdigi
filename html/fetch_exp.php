<?php
include('connection.php');

$output = '';

// Prepare the base query
$query = "SELECT id, invno, name, cate_id, income, expense, ondate FROM expreport ";
$params = [];
$types = '';

// Check if date filters are provided
if (isset($_POST['date_from']) && $_POST['date_from'] != '' && isset($_POST['date_to']) && $_POST['date_to'] != '') {
    $date_from = $_POST['date_from'];
    $date_to = $_POST['date_to'];

    // Add the WHERE clause and date range to the query with placeholders for prepared statement
    $query .= " WHERE ondate BETWEEN ? AND ?";
    $params = [$date_from, $date_to];
    $types = 'ss'; // string types for dates
} else {
    // If no date filter is provided, fetch only the current day's data
    $query .= " WHERE ondate = CURDATE()";
}

// Prepare and execute the statement
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params); // Corrected here
}
$stmt->execute();
$result = $stmt->get_result();

// Check if the query was successful
if (!$result) {
    die("Error executing query for 'expreport': " . $conn->error);
}

// Check if the query has results
if ($result->num_rows > 0) {
    $output .= '<div class="card">
                    <h5 class="card-header">DAILY TRANSACTION</h5>
                    <div class="table-responsive text-nowrap">
                        <table class="table" id="orders_table">
                            <thead>
                                <tr class="text-nowrap">
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Income</th>
                                    <th>Expense</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">';

    // Loop through result set and append to output
    $sumex = 0;
    $sumin = 0;
    while ($row2 = $result->fetch_assoc()) {
        $sumex += intval($row2['expense']);
        $sumin += intval($row2['income']);
        $output .= '
                    <tr>
                        <td>' . $row2['invno'] . '</td>
                        <td>' . $row2['name'] . '</td>
                        <td>' . $row2['cate_id'] . '</td>
                        <td>' . $row2['income'] . '</td>
                        <td>' . $row2['expense'] . '</td>
                        <td>' . $row2['ondate'] . '</td>
                    </tr>';
    }

    $output .= '
    <tr>
        <td></td>
        <td>Total</td>
        <td></td>
        <td>' . $sumin . '</td>
        <td>' . $sumex . '</td>
        <td></td>
    </tr></tbody></table></div></div>';
} else {
    $output .= '<div class="card"><h5 class="card-header">No transactions have been made today</h5></div>';
}

echo $output;

$conn->close();
?>
