<?php 
include('connection.php');

$fullname = $_POST['x'];
$mobno = $_POST['y'];
$pin = $_POST['z'];
$address = $_POST['a'];
$email = $_POST['e'];
$data = array();

// Function to generate a random refer code
// function getName($n) {
//     $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
//     $randomString = '';

//     for ($i = 0; $i < $n; $i++) {
//         $index = rand(0, strlen($characters) - 1);
//         $randomString .= $characters[$index];
//     }

//     return $randomString;
// }

// $n = 4; // length of the refer code
// $code = getName($n);

$timezone = new DateTimeZone("Asia/Kolkata");
$indate = new DateTime();
$indate->setTimezone($timezone);
$indate = $indate->format('Y-m-d');

$sql = "INSERT INTO customer (Fullname, Email, Mobile_no, Pincode, Cusaddress, Add_date) VALUES ('$fullname', '$email', '$mobno', '$pin', '$address', '$indate')";

if (mysqli_query($conn, $sql)) {
    $data['status'] = "success";
} else {
    $data['status'] = "error";
    $data['message'] = mysqli_error($conn);
}

echo json_encode($data);
?>
