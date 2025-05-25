<?php
include('connection.php');

$output = '';

$query = "SELECT * FROM ordertab WHERE delete_status=0";

if (isset($_POST['query']) && $_POST['query'] != '') {
    $search = $_POST['query'];
    $query .= " AND (customer LIKE '%$search%' OR invoiceno LIKE '%$search%' OR payment LIKE '%$search%')";
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
    <h5 class="card-header">Bills</h5>
    <div class="table-responsive text-nowrap">
      <table class="table" id="orders_table">
      <thead>
      <tr class="text-nowrap">
        <th>Invoice No</th>
        <th>Customer</th>
       
        <th>Barcode</th>
     
      <th>Service name</th>
        <th>Without GST</th>
        <th>GST</th>
          <th>Discount</th>
          <th>Net Value</th>
         <th>QTY</th>
          <th>Amount</th>
          <th>Payment</th>
      </tr>
    </thead>
    <tbody class="table-border-bottom-0">';
    while($row = $result->fetch_assoc()) {
        $output .= '<tr>
                        <td>'.$row["invoiceno"].'</td>
                        <td>'.$row["customer"].'</td>
                         <td>'.$row["barcode"].'</td>
                        <td>'.$row["product_name"].'</td>
                        <td>'.$row["dp"].'</td>
                        <td>'.$row["gst"].'</td>
                         <td>'.$row["discount_percentage"].'</td>
                        <td>'.$row["netvalue"].'</td>
                          <td>'.$row["qty"].'</td>
                        <td>'.$row["billamount"].'</td>
                         <td>'.$row["payment"].'</td>
                        <td><a href="billing.php?id='.$row["invoiceno"].'" target="_blank">Print</a></td>
                    </tr>';
    }
    $output .= '</tbody></table></div></div>';
} else {
    $output .= '<div class="card"><h5 class="card-header">No results found</h5></div>';
}

echo $output;

$conn->close();
?>
