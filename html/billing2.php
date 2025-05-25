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
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
            width: 58mm;
            margin: 0 auto;
        }
        .container {
            width: 100%;
            padding: 10px;
        }
        .header {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
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
            font-size: 16px;
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
        ?>

        <div class="ticket">
            <h1 class="heading centered">NAKSHATHRA HOME BAKER'S</h1>
            <p class="centered">
                <b>Inv No: <?=$invno;?></b><br>
                <b>GST No: 32AAZFP7171Q1Z6</b><br>
                <b>FSSI Reg No: 21323197000456</b><br>
                <b>Name: <?=$row3['Fullname'];?></b><br>
                <b>Mob No: <?=$row2['customer'];?></b>
            </p>

            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>MRP</th>
                        <th>Q.</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_array($result)) {
                        $sum += $row['item_total_amt'];
                    ?>
                    <tr>
                        <td><?= $row['product_name'];?></td>
                        <td><?= $row['mrp'];?></td>
                        <td><?= (int)$row['qty'];?></td>
                        <td><?= $row['dp'];?></td>
                        <td><?= $row['item_total_amt'];?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="4">Grand Total</td>
                        <td><?= ceil($sum)?></td>
                    </tr>
                </tbody>
            </table>
            <p class="centered">GST is included in all products</p>
        </div>
        <button id="btnPrint" class="hidden-print btn-sty">Print</button>
    </div>

    <script>
        const $btnPrint = document.querySelector("#btnPrint");
        $btnPrint.addEventListener("click", () => {
            window.print();
        });
    </script>
</body>
</html>
