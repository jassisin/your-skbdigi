<?php
require('session.php');
if(isset($_SESSION['prefix'])){
    $prefix=$_SESSION['prefix'];
}
include('connection.php');
include('functions.php');
$timezone = new DateTimeZone("Asia/Kolkata" );
$tdate1 = new DateTime();
$tdate1->setTimezone($timezone );
$tdate=$tdate1->format( 'd-m-Y' );

if(isset($_GET['id'])&&($_GET['id']!='')){
    $invno=$_GET['id'];
    $sql2="select customer from ordertab where invoiceno='$invno'";
    $result2 = mysqli_query($conn,$sql2);
    $row2=mysqli_fetch_array($result2);
    $customer=$row2['customer'];
    $sql3="select * from customer where Fullname='$customer'";
    $res3=mysqli_query($conn,$sql3);
    $row3=mysqli_fetch_array($res3);
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
            width: 50%;
            margin: 0 auto;
            border: 1px solid #000;
            padding: 10px;
        }
        .header {
            display: flex;
            justify-content: space-between;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
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
        }
        @media print {
            .hidden-print, .hidden-print * {
                display: none !important;
            }
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
        $sql = "SELECT * FROM ordertab where invoiceno='$invno' and pr_status=0";
        $result = mysqli_query($conn,$sql);
        $sum = 0;
        $serial_no = 1;
        ?>
        <div class="ticket">
            <div class="header">
                <div>
                    <h1 class="heading">LACHOOS LUSH BEAUTY SALOON</h1>
                    <p>
                        <b>GST No: 32ACYPT8551B17</b><br>
                        <b>FSSAI Reg No: 13220008000378</b>
                        <b>Date: <?=$tdate?></b><br>
                    <b>Inv No: <?=$invno?></b>
                    </p>
                </div>
                <div>
                    <p>
                    <b>Customer: <?=$customer;?></b><br>
                    <b>Mob No: <?=$row3['Mobile_no'];?></b><br>
                    <b>Gst Number: <?=$row3['Pincode'];?></b><br>
                    <b>Address: <?=$row3['Cusaddress'];?></b><br>

                    </p>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Serial no</th>
                        <th>Description</th>
                      
                        <th>HSN code</th>
                        <th>Price</th>
                        <th>Discount</th>
                        <th>GST</th>
                        <th>Refferrel</th>
                        <th>Net Value</th>
                        <th>Qty</th>

                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_array($result)) {
                        $sum += $row['item_total_amt'];
                        $qty=(int)$row['qty']+$row['free'];
                    ?>
                    <tr>
                        <td><?= $serial_no++; ?></td>
                        <td><?= $row['product_name'];?></td>
                       

                        <td><?= $row['hsncode'];?></td>
                        <td><?= $row['dp'];?></td>
                        <td><?= $row['discount_percentage'];?></td>
                        <td><?= $row['gst'];?></td>
                        <td><?= $row['referel'];?></td>
                      
                        <td><?= number_format($row['netvalue'], 2); ?></td>

                        <td><?= $qty?></td>
                        <td><?= $row['item_total_amt'];?></td>
                    </tr>
                   
                    <tr>
                        <td colspan="9" class="centered">Grand Total</td>
                        <td><?= ceil($sum)?></td>
                    </tr>
                </tbody>
            </table>
            <p class="centered">Remark :<?= $row['remark'];?><br>Category :<?= $row['category'];?><br> GST is included in all products</p>
            <?php
                    }
                    ?>
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
