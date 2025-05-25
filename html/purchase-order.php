<?php
require('session.php');
if (isset($_SESSION['prefix'])) {
    $prefix = $_SESSION['prefix'];
}
include('connection.php');
include('functions.php');
$timezone = new DateTimeZone("Asia/Kolkata");
$tdate1 = new DateTime();
$tdate1->setTimezone($timezone);
$tdate = $tdate1->format('d-m-Y');

if (isset($_GET['s']) && ($_GET['s'] != '') && isset($_GET['v']) && ($_GET['v'] != '')) {
    $store = $_GET['s'];
    $vendor = $_GET['v'];
    // $sql2 = "select * from stocktab where vendor_name='$vendor' and closing_stock < 5";
    // $result2 = mysqli_query($conn, $sql2);
    // $row2 = mysqli_fetch_array($result2);
    // $customer = $row2['customer'];
    // $sql3 = "select * from customer where Fullname='$customer'";
    // $res3 = mysqli_query($conn, $sql3);
    // $row3 = mysqli_fetch_array($res3);
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bill Printing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
        }
        .container {
            width: 80%;s
            margin: 0 auto;
            border: 1px solid #000;
            padding: 10px;
        }
        .header {t
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .vendor, .ship-to {
            width: 45%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .centered {
            text-align: center;
        }
        .heading {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .hidden-print, .hidden-print * {
            display: none !important;
        }
        .btn-sty {
            border-radius: 5px;
            padding: 6px;
            font-size: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        $sql = "select * from stocktab where vendor_name='$vendor' and closing_stock < 10";
        $result = mysqli_query($conn, $sql);
        $sum = 0;
        $serial_no = 1;
        ?>
        <div class="ticket">
            <div class="heading">PURCHASE ORDER</div>
            <div class="header row">
                <div class="vendor col-6">
                    <p>
                        <b>NAKSHATHRA HOME BAKER'S</b><br>
                        24/203 Ambalanada, Chalakudi Road<br>
                        Chalakudy PO, Kerala<br>
                        Phone: 9074113865<br>
                        Email: nakshathrahomebakers@gmail.com<br>
                        GST No: 32ACYPT8551B17
                    </p>
                </div>
                <div class="ship-to col-6">
                    <p>
                        <b>SHIP TO</b><br>
                        NAKSHATHRA HOME BAKER'S<br>
                        24/203 Ambalanada, Chalakudi Road<br>
                        Chalakudy PO, Kerala<br>
                        Phone: 9074113865<br>
                        Email: nakshathrahomebakers@gmail.com
                    </p>
                </div>
                <div>
                    <p>
                        <b>Date: <?=$tdate?></b><br>
                        <!-- <b>PO #: <?=$invno?></b><br> -->
                    </p>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Barcode</th>
                        <th></th>
                        <th>QTY</th>
                        <th>Unit Price</th>
                        <th>Tax</th>
                        <th>Total</th>
                      
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_array($result)) {
                     
                        $qty = 10;
                    ?>
                    <tr>
                      
                        <td><?= $row['barcode'];?></td>
                        <td><?= $row['product_name'];?></td>
                        <td><?= $qty;?></td>
                        <td><?= $row['dp'];?></td>
                        <td><?= $row['gst'];?></td>

                        <td><?=$sum2= $row['dp'] * $qty;?></td>
                       
                    </tr>
                    <?php
                     $sum += $sum2;
                    }
                    ?>
                    <tr>
                        <td colspan="5" class="centered"><b>Grand Total</b></td>
                        <td><b><?= $sum; ?></b></td>
                    </tr>
                </tbody>
            </table>
            <p class="centered">GST is included in all products</p>
        </div>
        <button id="btnPrint" class="hidden-print btn-sty">Print</button>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
    const $btnPrint = document.querySelector("#btnPrint");
    $btnPrint.addEventListener("click", () => {
        window.print();
    });
    </script>
</body>
</html>
