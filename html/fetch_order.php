<?php
include('connection.php');

$output = '';

$query = "SELECT MIN(indate) AS indate, MIN(id) AS id, invoiceno, MIN(customer) AS customer, MIN(store) AS store, MIN(billamount) AS billamount FROM ordertab WHERE delete_status = 0 ";

if (isset($_POST['query']) && $_POST['query'] != '') {
    $search = $_POST['query'];
    $query .= " AND (customer LIKE '%$search%' OR store LIKE '%$search%' OR invoiceno LIKE '%$search%')";
}

if (isset($_POST['date_from']) && $_POST['date_from'] != '' && isset($_POST['date_to']) && $_POST['date_to'] != '') {
    $date_from = $_POST['date_from'];
    $date_to = $_POST['date_to'];
    $query .= " AND indate BETWEEN '$date_from' AND '$date_to'";
}

$query .= " GROUP BY invoiceno ORDER BY invoiceno";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    $output .= '<div class="card">
    <h5 class="card-header">Orders</h5>
    <div class="table-responsive text-nowrap">
      <table class="table" id="orders_table">
      <thead>
      <tr class="text-nowrap">
        <th>Date</th>
        <th>Invoice Number</th>
        <th>Customer</th>
        <th>Store</th>
        <th>Total bill amount</th>
        <th>Edit</th>
        <th>Print</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody class="table-border-bottom-0">';
    while($row = $result->fetch_assoc()) {
        $output .= '<tr>
                        <td>'.$row["indate"].'</td>
                        <td>'.$row["invoiceno"].'</td>
                        <td>'.$row["customer"].'</td>
                        <td>'.$row["store"].'</td>
                        <td>'.$row["billamount"].'</td>
                        <td><a href="orderedit.php?id='.$row["id"].'">Edit</a></td>
                        <td><a href="billing.php?id='.$row["invoiceno"].'" target="_blank">Print</a></td>
                        <td><a href="orders.php?did='.$row["id"].'" onClick="return confirm(\'Are you sure you want to delete?\')" style="color:purple;">Delete</a></td>
                    </tr>';
    }
    $output .= '</tbody></table></div></div>';
} else {
    $output .= '<div class="card"><h5 class="card-header">No results found</h5></div>';
}

echo $output;

$conn->close();
?>
