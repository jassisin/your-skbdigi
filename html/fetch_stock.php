<?php
include('connection.php');

$output = '';
if(isset($_POST['query'])) {
    $search = $_POST['query'];
    $sql = "SELECT * FROM stocktab WHERE product_name LIKE ? AND active = 0";
    $stmt = $conn->prepare($sql);
    $searchParam = "%$search%";
    $stmt->bind_param('s', $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM stocktab WHERE active = 0";
    $result = $conn->query($sql);
}

if ($result->num_rows > 0) {
    $output .= '<div class="card">
    <h5 class="card-header">Stock Details</h5>
              
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="demo-inline-spacing">
                    <form method="post">
                        <div class="input-group mb-3">
                            <span class="input-group-text store">Store</span>';

    // Fetching stores dynamically
    $storeResult = $conn->query("SELECT DISTINCT store FROM entry");
    if ($storeResult->num_rows > 0) {
        $output .= '<select id="searchddl" name="store" style="height:30px;">';
        while ($storeRow = $storeResult->fetch_assoc()) {
            $output .= '<option>' . $storeRow['store'] . '</option>';
        }
        $output .= '</select>';
    }

    $output .= '</div>
                <div class="input-group mb-3">
                    <span class="input-group-text store">Vendor</span>';

    // Fetching vendors dynamically
    $vendorResult = $conn->query("SELECT DISTINCT vendor FROM entry");
    if ($vendorResult->num_rows > 0) {
        $output .= '<select id="searchddl" name="vendor" style="height:30px;">';
        while ($vendorRow = $vendorResult->fetch_assoc()) {
            $output .= '<option>' . $vendorRow['vendor'] . '</option>';
        }
        $output .= '</select>';
    }

    $output .= '</div>
                <button type="submit" name="submit" class="btn btn-primary">Generate PO</button>
                <button type="button" class="btn btn-primary">View/Edit PO</button>
            </form>
        </div>
    </div>
</div>
<div class="table-responsive text-nowrap">
    <table class="table">
        <thead>
            <tr class="text-nowrap">
                <th>Serial No</th>
                <th>Barcode</th>
                <th>Product Name</th>
                <th>Stock</th>
                <th>Expiry</th>
                <th>MBQ</th>
                <th>Assigned Day</th>
                <th>Cost</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">';

    while($row = $result->fetch_assoc()) {
        $output .= '<tr>
                        <td>' . $row["id"] . '</td>
                        <td>' . $row["barcode"] . '</td>
                        <td>' . $row["product_name"] . '</td>
                        <td>' . $row["closing_stock"] . '</td>
                        <td>' . $row["dp"] . '</td>
                    </tr>';
    }
    
    $output .= '</tbody></table></div></div></div>';
} else {
    $output .= '0 results found';
}

echo $output;

// Close connection
$conn->close();
?>
