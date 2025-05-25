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
    $sql2="select * from receipt where recno='$invno'";
    $result2 = mysqli_query($conn,$sql2);
    $row2=mysqli_fetch_array($result2);
    $customer=$row2['donor'];
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
    <title>Receipt Printing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }
        .receipt-container {
            width: 80%;
            margin: 20px auto;
            border: 2px solid #F05A28;
            padding: 20px;
            position: relative;
        }
        .receipt-header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #F05A28;
            padding-bottom: 10px;
        }
        .receipt-header h1 {
            font-size: 18px;
            color: #F05A28;
        }
        .receipt-header img {
            width: 70px;
            height: auto;
        }
        .receipt-details {
            margin: 20px 0;
        }
        .receipt-details p {
            margin: 0;
            line-height: 1.6;
        }
        .receipt-footer {
            text-align: right;
            margin-top: 20px;
        }
        .receipt-footer p {
            margin: 0;
        }
        .signature {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }
        .signature div {
            width: 30%;
            text-align: center;
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
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <img src="logo.png" alt="Logo">
            <h1>Pisharikkal Gramaseva Samithy</h1>
        </div>
        <div class="receipt-details">
            <p><strong>Receipt No:</strong> <?=$invno?></p>
            <p><strong>Date:</strong> <?=$tdate?></p>
            <p><strong>Received with thanks from Shri/Smt:</strong> <?=$customer;?></p>
            <p><strong>Address:</strong> <?=$row3['Cusaddress'];?></p>
            <p><strong>Occtaiom:</strong> <?=$row2['occation']?></p>
            <p><strong>A sum of Rupees:</strong> <?=$row2['amount']?></p>
            <p><strong>By cash/cheque/DD No:</strong> <?=$row2['payment']?></p>
        </div>
        <div class="signature">
            <div>
                <p>President</p>
            </div>
            <div>
                <p>Signature</p>
            </div>
            <div>
                <p>Treasurer</p>
            </div>
        </div>
        <div class="receipt-footer">
            <p>Towards donation.</p>
        </div>
    </div>
    <button id="btnPrint" class="hidden-print btn-sty">Print</button>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
    const $btnPrint = document.querySelector("#btnPrint");
    $btnPrint.addEventListener("click", () => {
        window.print();
    });
    </script>
</body>
</html>
