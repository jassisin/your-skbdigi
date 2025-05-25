<?php
include('connection.php');

$output = '';

$query = "SELECT id, vendor, store, invno, barcode, product_name, indate, date_invoice, expiry_date, landing, qty, amount, total_billamount 
          FROM entry 
          WHERE active = 0 and mode!='OUT'";

if (isset($_POST['query']) && $_POST['query'] != '') {
    $search = $_POST['query'];
    $query .= " AND (invno LIKE '%$search%')";
}

if (isset($_POST['date_from']) && $_POST['date_from'] != '' && isset($_POST['date_to']) && $_POST['date_to'] != '') {
    $date_from = $_POST['date_from'];
    $date_to = $_POST['date_to'];
    $query .= " AND indate BETWEEN '$date_from' AND '$date_to'";
}

$query .= " GROUP BY invno 
            ORDER BY id ASC";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    $output .= '<div class="card">
                    <h5 class="card-header">Purchase</h5>
                    <div class="table-responsive text-nowrap">
                        <table class="table" id="orders_table">
                            <thead>
                                <tr class="text-nowrap">
                                    <th>ID</th>
                                    <th>Vendor</th>
                                    <th>Store</th>
                                    <th>Inv No</th>
                                    <th>Barcode</th>
                                    <th>Product Name</th>
                                    <th>Purchase Entry Date</th>
                                    <th>Invoice Date</th>
                                    <th>Expiry Date</th>
                                    <th>Landing</th>
                                    <th>Qty</th>
                                    <th>Amount</th>
                                    <th>Total Bill Amount</th>
                                    <th>Edit</th>
                                    <th>Print</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">';
    while ($row2 = $result->fetch_assoc()) {
        $output .= '<tr>
                        <td>' . $row2['id'] . '</td>
                        <td>' . $row2['vendor'] . '</td>
                        <td>' . $row2['store'] . '</td>
                        <td>' . $row2['invno'] . '</td>
                        <td>' . $row2['barcode'] . '</td>
                        <td>' . $row2['product_name'] . '</td>
                        <td>' . $row2['indate'] . '</td>
                        <td>' . $row2['date_invoice'] . '</td>
                        <td>' . $row2['expiry_date'] . '</td>
                        <td>' . $row2['landing'] . '</td>
                        <td>' . $row2['qty'] . '</td>
                        <td>' . $row2['amount'] . '</td>
                        <td>' . $row2['total_billamount'] . '</td>
                        <td><a href="purchase-edit.php?invid=' . $row2['id'] . '">Edit</a></td>
                        <td><a href="billing.php?id=' . $row2['invno'] . '" target="_blank">Print</a></td>
                        <td><a href="entry-list.php?did=' . $row2['invno'] . '" onClick="return confirm(\'Are you sure you want to delete?\')" style="color: purple; padding-left: 60px;">Delete</a></td>
                    </tr>';
    }
    $output .= '</tbody></table></div></div>';
} else {
    $output .= '<div class="card"><h5 class="card-header">No results found</h5></div>';
}

echo $output;

$conn->close();
?>
