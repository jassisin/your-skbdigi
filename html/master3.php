<?php 
include('connection.php');

$prname = $_POST['x'];
$data = array();

$sql = "SELECT * FROM master WHERE product_name='$prname' AND active=0";
$res = mysqli_query($conn, $sql);

if (mysqli_num_rows($res) > 0) {
    while ($rows = mysqli_fetch_array($res)) {
        $data['dp'] = $rows['dp'];
        $data['barcode'] = $rows['product_barcode'];
    }
} else {
    $data['dp'] = '';
    $data['barcode'] = '';
}

echo json_encode($data);
?>
