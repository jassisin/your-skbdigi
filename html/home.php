<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require('session.php');

include 'calendar.php';
include 'connection.php';

$timezone = new DateTimeZone("Asia/Kolkata" );
    $date = new DateTime();
    $date->setTimezone($timezone );
    $date=$date->format( 'Y-m-d' );
$calendar = new Calendar($date);
// Initialize an array to store event counts per date
$date_counts = [];

// First SQL query: actions
$sql = "SELECT * FROM actions WHERE active=0";
$res = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_array($res)) {
    $acdate = $row['acdate'];
    $notes = $row['notes'];
    $name = $row['Name'];
    $stage = $row['stage'];
    $pre = substr($stage, 0, 2);
    $final = $pre . "/" . $name . "/" . $notes;

    // Increment event count for the given date
    if (!isset($date_counts[$acdate])) {
        $date_counts[$acdate] = 0;
    }
    $date_counts[$acdate]++;

    $calendar->add_event($final, $acdate, 1, 'green');
}

// Second SQL query: ordertab
$sql_other = "SELECT * FROM ordertab WHERE delete_status=0";
$res_other = mysqli_query($conn, $sql_other);
while ($row_other = mysqli_fetch_array($res_other)) {
    $reminder = $row_other['reminder'];
    $customer = $row_other['customer'];
    $prname = $row_other['product_name'];
    $amount = $row_other['billamount'];
    $remark = $row_other['remark'];

    $final_other = $customer . "/" . $prname . "/" . $amount . "/" . $remark;

    // Increment event count for the given date
    if (!isset($date_counts[$reminder])) {
        $date_counts[$reminder] = 0;
    }
    $date_counts[$reminder]++;

    $calendar->add_event($final_other, $reminder, 1, 'blue');
}

// Third SQL query: resource
$sql_other = "SELECT * FROM resource WHERE active=0";
$res_other = mysqli_query($conn, $sql_other);
while ($row_other = mysqli_fetch_array($res_other)) {
    $reminder = $row_other['expdate'];
    $customer = $row_other['staff'];
    $amount = $row_other['amount'];
    $remark = $row_other['remark'];

    $final_other = $customer . "/" . $amount . "/" . $remark;

    // Increment event count for the given date
    if (!isset($date_counts[$reminder])) {
        $date_counts[$reminder] = 0;
    }
    $date_counts[$reminder]++;

    $calendar->add_event($final_other, $reminder, 1, 'purple');
}

// Fourth SQL query: expense_forcast
$sql_expenses = "SELECT follow_up_date, frequency, particulars, total FROM expense_forcast"; 
$res_expenses = mysqli_query($conn, $sql_expenses);

while ($row_expense = mysqli_fetch_array($res_expenses)) {
    $start_date = $row_expense['follow_up_date'];
    $frequency = $row_expense['frequency'];
    $particulars = $row_expense['particulars'];
    $total = $row_expense['total'];

    // Prepare the event description
    $final_event = $particulars . "/" . $total;

    // Add events based on frequency
    $current_date = date('Y-m-d'); // Today's date for reference
    $end_date = date('Y-m-d', strtotime('+1 year')); // Limit to one year ahead

    while (strtotime($start_date) <= strtotime($end_date)) {
        // Check if the start_date is today or in the future
        if (strtotime($start_date) >= strtotime($current_date)) {
            $calendar->add_event($final_event, $start_date, 1, 'orange');
            
            // Increment event count for the given date
            if (!isset($date_counts[$start_date])) {
                $date_counts[$start_date] = 0;
            }
            $date_counts[$start_date]++;
        }

        // Calculate the next occurrence based on the frequency
        if ($frequency === 'Weekly') {
            $start_date = date('Y-m-d', strtotime($start_date . ' +1 week'));
        } elseif ($frequency === 'Monthly') {
            $start_date = date('Y-m-d', strtotime($start_date . ' +1 month'));
        } elseif ($frequency === 'Half-Yearly') {
            $start_date = date('Y-m-d', strtotime($start_date . ' +6 months'));
        } elseif ($frequency === 'Yearly') {
            $start_date = date('Y-m-d', strtotime($start_date . ' +1 year'));
        } else {
            break; // Skip if frequency is not defined
        }
    }
}

// Add the event count for each date to the calendar
foreach ($date_counts as $date => $count) {
    $event_text = "Expense: " . $count;
    $calendar->add_event($event_text, $date, 1, 'green');  // You can use any color here for count display
}





$sql_entry = "SELECT vendor, total_billamount, remark, reminder FROM entry WHERE active=0"; 
$res_entry = mysqli_query($conn, $sql_entry);

if (!$res_entry) {
    die("Query Failed: " . mysqli_error($conn)); // Debugging for query errors
}

// Array to hold counts of entries per date
$date_counts = [];

while ($row_entry = mysqli_fetch_array($res_entry)) {
    $vendor = $row_entry['vendor'];
    $total_billamount = $row_entry['total_billamount'];
    $remark = $row_entry['remark'];
    $reminder = $row_entry['reminder'];  // Assuming this is the date

    $final_entry = $vendor . "/" . $total_billamount . "/" . $remark;

    // Count the entries by date (reminder field)
    if (!isset($date_counts[$reminder])) {
        $date_counts[$reminder] = 0;
    }
    $date_counts[$reminder]++;

    // Add the event to the calendar
    $calendar->add_event($final_entry, $reminder, 1, 'red');
}

// Add the entry count for each date to the calendar
foreach ($date_counts as $date => $count) {
    // Assuming $calendar->add_event() method can accept custom text, e.g., entry counts.
    // Format: "Entries: X" will be displayed for each date on the calendar.
    $event_text = "Purchase: " . $count;
   $url = "purchase_details.php?final_entry=" . urlencode($final_entry);

    // Add the event with a clickable link
    $calendar->add_event($event_text, $date, 1, 'green', $url);
}











if(isset($_SESSION['main_admin'])){
  $username=$_SESSION['main_admin'];
}
if(isset($_POST['submit'])){

  $status=$_POST['status'];
  $action=$_POST['action'];
  $importance=$_POST['importance'];
  $stage=$_POST['stage'];
  $pre=substr($stage,0,2);
  $salesman=$_POST['salesman'];
  $timezone = new DateTimeZone("Asia/Kolkata" );
    $indate = new DateTime();
    $indate->setTimezone($timezone );
    $indate=$indate->format( 'Y-m-d' );
    if($status!=''){
  $query="INSERT INTO status (status,indate,managedby,active) VALUES ('$status','$indate','$username',0)";  
  $result1=mysqli_query($conn,$query);
    }
    if($action!=''){
  $query2="INSERT INTO action (action,indate,managedby,active) VALUES ('$action','$indate','$username',0)";  
  $result2=mysqli_query($conn,$query2);
    }
    if($importance!=''){
  $query3="INSERT INTO importance (importance,indate,managedby,active) VALUES ('$importance','$indate','$username',0)";  
  $result3=mysqli_query($conn,$query3);
    }
    if($stage!=''){
  $query4="INSERT INTO stage (stage,prefix,indate,managedby,active) VALUES ('$stage','$pre','$indate','$username',0)";  
  $result4=mysqli_query($conn,$query4);
    }
    if($salesman!=''){
  $query5="INSERT INTO salesman (salesman,indate,managedby,active) VALUES ('$salesman','$indate','$username',0)";  
  $result5=mysqli_query($conn,$query5);
    }
  header("Location: dashboard");
  echo '<script> window.location.href = "dashboard"; </script>';

}
if(isset($_GET['satusdid'])&&($_GET['satusdid']!=''))
{
  $did=$_GET['satusdid'];
  $sql2="update status set active=1 where id='$did'";
  $res2=mysqli_query($conn,$sql2);
  header("Location: dashboard");
 echo '<script> window.location.href = "dashboard"; </script>';
}
if(isset($_GET['actiondid'])&&($_GET['actiondid']!=''))
{
  $did=$_GET['actiondid'];
  $sql2="update action set active=1 where id='$did'";
  $res2=mysqli_query($conn,$sql2);
  header("Location: dashboard");
 echo '<script> window.location.href = "dashboard"; </script>';
}
if(isset($_GET['impdid'])&&($_GET['impdid']!=''))
{
  $did=$_GET['impdid'];
  $sql2="update importance set active=1 where id='$did'";
  $res2=mysqli_query($conn,$sql2);
  header("Location: dashboard");
 echo '<script> window.location.href = "dashboard"; </script>';
}




if(isset($_GET['stagedid'])&&($_GET['stagedid']!=''))
{
  $did=$_GET['stagedid'];
  $sql2="update stage set active=1 where id='$did'";
  $res2=mysqli_query($conn,$sql2);
  header("Location: dashboard");
 echo '<script> window.location.href = "dashboard"; </script>';
}
if(isset($_GET['salesmandid'])&&($_GET['salesmandid']!=''))
{
  $did=$_GET['salesmandid'];
  $sql2="update salesman set active=1 where id='$did'";
  $res2=mysqli_query($conn,$sql2);
  header("Location: dashboard");
 echo '<script> window.location.href = "dashboard"; </script>';
}

if(isset($_POST['submit3'])){

  $cus_name=$_POST['cus_name'];
  $pstatus=$_POST['pstatus'];
  $pstage=$_POST['pstage'];
  $psales=$_POST['psales'];
  if (empty($cus_name)) {
    $cus_name='';
}
if (empty($pstatus)) {
  $pstatus='';
}
if (empty($pstage)) {
  $pstage='';
}
if (empty($psales)) {
  $psales='';
}
  header("Location: process-analysis.php?name=$cus_name&st=$pstatus&sg=$pstage&sa=$psales");
    echo '<script> window.location.href = "process-analysis.php?name='.$cus_name.'&st='.$pstatus.'&sg='.$pstage.'&sa='.$psales.'"; </script>';
}
?>

<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-menu-fixed layout-compact"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
      rel="stylesheet" />

    <link rel="stylesheet" href="../assets/vendor/fonts/materialdesignicons.css" />

    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="../assets/vendor/libs/node-waves/node-waves.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->
    <link href="style.css" rel="stylesheet" type="text/css">
		<link href="calendar.css" rel="stylesheet" type="text/css">
    <!-- Helpers -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript"></script>
     <script>
    $(document).ready(function() {
    load_data();

    function load_data(query = '', date_from = '', date_to = '') {
        $.ajax({
            url: "fetch_exp.php",
            method: "POST",
            data: { query: query, date_from: date_from, date_to: date_to},
            success: function(data) {
                $('#result').html(data);
            }
        });
    }

    $('#search').keyup(function() {
        var search = $(this).val();
        var date_from = $('#date_from').val();
        var date_to = $('#date_to').val();
        load_data(search, date_from, date_to);
    });

    $('#filter_button').click(function() {
        var search = $('#search').val();
        var date_from = $('#date_from').val();
        var date_to = $('#date_to').val();
        load_data(search, date_from, date_to);
    });

    $('#download_button').click(function() {
        download_table_as_csv('orders_table');
    });


    function download_table_as_csv(table_id) {
        var table = document.getElementById(table_id);
        var rows = table.querySelectorAll('tr');
        var csv = [];
        
        rows.forEach(function(row) {
            var cols = row.querySelectorAll('td, th');
            var row_csv = [];
            cols.forEach(function(col) {
                row_csv.push(col.innerText);
            });
            csv.push(row_csv.join(','));
        });
        
        var csv_string = csv.join('\n');
        var filename = 'orders_' + new Date().toLocaleDateString() + '.csv';
        var link = document.createElement('a');
        link.style.display = 'none';
        link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv_string));
        link.setAttribute('download', filename);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
});
</script>
    <script src="../assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
    <style>
        
    </style>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <?php include("header.php"); ?>


        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar" style="background-color: <?php echo $page_heading_color; ?>;">
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="mdi mdi-menu mdi-24px"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <!-- Search -->
              <div class="navbar-nav align-items-center">
                <div class="nav-item d-flex align-items-center">
                  <i class="mdi mdi-magnify mdi-24px lh-0"></i>
                  <input
                    type="text"
                    class="form-control border-0 shadow-none bg-body"
                    placeholder="Search..."
                    aria-label="Search..." />
                </div>
              </div>
              <!-- /Search -->

              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Place this tag where you want the button to render. -->
                <li class="nav-item lh-1 me-3">
                  <?=$username?>
                </li>

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a
                    class="nav-link dropdown-toggle hide-arrow p-0"
                    href="javascript:void(0);"
                    data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      <img src="../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end mt-3 py-2">
                    <li>
                      <a class="dropdown-item pb-2 mb-1" href="#">
                        <div class="d-flex align-items-center">
                          <div class="flex-shrink-0 me-2 pe-1">
                            <div class="avatar avatar-online">
                              <img src="../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <!-- <h6 class="mb-0">John Doe</h6> -->
                            <small class="text-muted">Admin</small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider my-1"></div>
                    </li>
                   
                    <li>
                      <a class="dropdown-item" href="logout.php">
                        <i class="mdi mdi-power me-1 mdi-20px"></i>
                        <span class="align-middle">Log Out</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->














            <div class="container-xxl flex-grow-1 container-p-y">

            <div class="navbar-nav align-items-center">
  <div class="nav-item d-flex align-items-center">
    <label for="date_from">From: </label>
    <input type="date" id="date_from" class="form-control border-0 shadow-none bg-body mx-2" />
    <label for="date_to">To: </label>
    <input type="date" id="date_to" class="form-control border-0 shadow-none bg-body mx-2" />
    <!-- <label for="category">category: </label>
    <input type="text" id="category" class="form-control border-0 shadow-none bg-body mx-2" /> -->

    <button id="filter_button" class="btn btn-primary">Filter</button>
  </div>
</div>
<!-- /Date filters -->

<!-- Download button -->
<div class="navbar-nav align-items-center">
  <button id="download_button" class="btn btn-success">Download CSV</button>
</div>


<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Expense Forecast</h4>
    <div class="card">
        <div class="card-body">
            <!-- Category Filter Dropdown -->
            <div class="d-flex justify-content-end mb-3">
                <select id="categoryFilter" class="form-select" onchange="filterByCategory()">
                    <option value="">Select Category</option>
                  <?php
// Fetch categories and heads from expcategory table
$categoryQuery = "SELECT category, head FROM expcategory";
$categoryResult = mysqli_query($conn, $categoryQuery);

while ($categoryRow = mysqli_fetch_array($categoryResult)) {
    // Combine category and head into a single value
    $categoryValue = $categoryRow['category'] . "/" . $categoryRow['head'];
    // Pass the combined value to the JavaScript function
    echo "<option value='" . $categoryValue . "' onclick='filterByCategory(\"" . $categoryValue . "\")'>" 
        . $categoryRow['category'] . " / " . $categoryRow['head'] 
        . "</option>";
}
?>
                </select>
            </div>

            <!-- Expense Table -->
            <table class="table table-bordered" id="expenseTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Posted Date</th>
                        <th>Particulars</th>
                        <th>Category</th>
                        <th>Forecast Amount</th>
                        <th>Actual Amount</th>
                        <th>Variance</th>
                        <th>Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                 <?php
   $filterCategory = isset($_GET['category']) ? $_GET['category'] : '';

$sql2 = "
SELECT 
    a.id, 
    MAX(e.date) AS date,  -- Get the latest date
    a.particulars, 
    e.category, 
    e.total,  
    a.amount,
    e.remarks, 
    a.slno
FROM 
    actual_expense a
LEFT JOIN 
    expense_forcast e 
ON 
    a.id = e.id
WHERE 
    1=1
";

// Add a condition for category if selected
if ($filterCategory != '') {
    $sql2 .= " AND e.category = '$filterCategory'";
}

// Group by 'id' and get the latest record based on the maximum 'date'
$sql2 .= " GROUP BY a.id ORDER BY a.id DESC";

$result2 = mysqli_query($conn, $sql2);

// Initialize variables to calculate totals
$totalForecast = 0;
$totalActual = 0;
$totalVariance = 0;

if (mysqli_num_rows($result2) > 0) {
    while ($row2 = mysqli_fetch_array($result2)) {
        $variance = abs($row2['amount'] - $row2['total']);
        // Accumulate totals
        $totalForecast += $row2['total'];
        $totalActual += $row2['amount'];
        $totalVariance += $variance;
?>
        <tr>
            <td><?php echo $row2['id']; ?></td>
            <td><?php echo $row2['date']; ?></td>
            <td><?php echo $row2['particulars']; ?></td>
            <td><?php echo $row2['category']; ?></td>
            <td><?php echo number_format($row2['total'], 2); ?></td>
            <td><?php echo number_format($row2['amount'], 2); ?></td>
            <td><?php echo number_format($variance, 2); ?></td>
            <td><?php echo $row2['remarks']; ?></td>
            <td>
                <a href="edit_actual.php?id=<?php echo $row2['slno']; ?>" class="btn btn-primary btn-sm" style="display: inline-block; margin-right: 5px;">Update</a>
                <a href="view_history.php?id=<?php echo $row2['id']; ?>" class="btn btn-danger btn-sm" style="display: inline-block; margin-top: 5px;">View History</a>
            </td>
        </tr>
<?php
    }
} else {
    echo "<tr><td colspan='9'>No records found</td></tr>";
}
?>

                    ?>
                </tbody>
            </table>
            
            
             <!-- Totals Row -->
            <div class="d-flex justify-content-start" style="margin-left:325px">
                <table class="table table-bordered" style="width: 30%;">
                    <tr>
                        <th>Total</th>
                        <td style='padding:45px'><?php echo number_format($totalForecast, 2); ?></td> <!-- Total Forecast Amount -->
                        <td style='padding:45px'><?php echo number_format($totalActual, 2); ?></td> <!-- Total Actual Amount -->
                        <td style='padding:45px'><?php echo number_format($totalVariance, 2); ?></td> <!-- Total Variance -->
                    </tr>
                </table>
            </div>
           <?php
// Connect to the database
 // Update with your connection file

// Fetch data from the database
$sql = "
SELECT 
    e.particulars AS title,
    a.amount AS actual,
    e.total AS forecast
FROM 
    actual_expense a
LEFT JOIN 
    expense_forcast e 
ON 
    a.id = e.id
WHERE 
    a.id IN (
        SELECT id 
        FROM actual_expense 
        GROUP BY e.particulars  -- Grouping by title (particulars)
        HAVING COUNT(e.particulars) = 1  -- Non-repeating titles
    )
    OR a.id IN (
        SELECT MAX(a2.id)  -- Most recent (last entered) for repeating titles
        FROM actual_expense a2
        LEFT JOIN expense_forcast e2 ON a2.id = e2.id
        GROUP BY e2.particulars  -- Group by title to get the latest for each repeating title
    )
ORDER BY 
    a.id DESC";




 // Adjust limit as per your requirement

$result = mysqli_query($conn, $sql);

// Initialize categories array
$categories = [];
$addedTitles = []; // To keep track of added titles

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Check if this title has already been added
        if (!in_array($row['title'], $addedTitles)) {
            // If not, add it to the categories array
            $categories[] = [
                'icon' => '🏠', // Default icon; you can add logic to assign specific icons for each category
                'title' => $row['title'],
                'actual' => $row['actual'],
                'forecast' => $row['forecast']
            ];
            // Mark this title as added
            $addedTitles[] = $row['title'];
        }
    }
}

?>

<div style="background-color: purple; color: white; padding: 20px; border-radius: 10px;">
    <!-- Monthly Summary Heading -->
    <h2 style="text-align: center; margin-bottom: 20px;"></h2>
    
    <!-- Summary Items -->
    <div style="display: flex; justify-content: space-around; align-items: center;">
        <?php foreach ($categories as $category) { ?>
            <div style="text-align: center; width: 20%;">
                <div style="font-size: 40px;"><?php echo $category['icon']; ?></div>
                <div style="font-size: 24px; font-weight: bold;">MonthlySummary : ₹<?php echo number_format($category['actual'], 2); ?></div>
                <div style="font-size: 18px; margin-top: 5px;"><?php echo $category['title']; ?>
                
                </div>
                <div style="margin-top: 10px; font-size: 16px;">Forecast: ₹<?php echo number_format($category['forecast'], 2); ?></div>
            </div>
        <?php } ?>
    </div>
</div>
            
        </div>
    </div>
</div>

<script>
function filterByCategory() {
    var category = document.getElementById('categoryFilter').value;
    window.location.href = "?category=" + category;
}
</script>





            <div class="container-xxl flex-grow-1 container-p-y">
               <!--<h4 class="py-3 mb-4"><span class="text-muted fw-light">Tables </span> Basic Tables</h4> -->
              <div class="container-xxl flex-grow-1 container-p-y">
    <div id="result"></div>
</div>

            
<div class="container-xxl flex-grow-1 container-p-y">
              <div class="row gy-4">
              <h5 class="pb-1 mb-4">Summary</h5>

                <!-- Congratulations card -->
                 <?php
                 $sql5="SELECT 
    SUM(income) AS total_income, 
    SUM(expense) AS total_expense
FROM expreport
WHERE YEAR(ondate) = YEAR(CURDATE()) 
AND MONTH(ondate) = MONTH(CURDATE())";
                 $res5=mysqli_query($conn,$sql5);
$row5=mysqli_fetch_array($res5);
$bal=$row5['total_income']-$row5['total_expense'];
                 ?>
                <div class="col-md-12 col-lg-4">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title mb-1">INCOME</h4>
                      <p class="pb-0">Month income</p>
                      <h4 class="text-primary mb-1"><?=$row5['total_income']?></h4>
                   
                    </div>
                   
                  </div>
                </div>
                <div class="col-md-12 col-lg-4">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title mb-1">EXPENSE</h4>
                      <p class="pb-0">Month expense</p>
                      <h4 class="text-primary mb-1"><?=$row5['total_expense']?></h4>
                   
                    </div>
                   
                  </div>
                </div>
                <div class="col-md-12 col-lg-4">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title mb-1">Balance</h4>
                      <p class="pb-0">Month Balance</p>
                      <h4 class="text-primary mb-1"><?=$bal?></h4>
                   
                    </div>
                   
                  </div>
                </div>

            </div>
          </div>

             
    <!--     <div class="container">-->
    <!--<div class="filter-container">-->
    <!--<h5 class="pb-1 mb-4">Process Analysis</h5>-->
    <!--<form method="post">-->
    <!--    <select id="customer-name" class='form-select' style="width:auto" name="cus_name">-->
    <!--        <option value="">Select Customer Name</option>-->
    <!--    </select>-->
    <!--    <br>-->
    <!--    <select id="status" class='form-select' style="width:auto" name="pstatus">-->
    <!--        <option value="">Select Status</option>-->
    <!--    </select>-->
    <!--    <br>-->
    <!--    <select id="stage" class='form-select' style="width:auto" name="pstage">-->
    <!--        <option value="">Select Stage</option>-->
    <!--    </select>-->
    <!--    <br>-->
    <!--    <select id="salesman" class='form-select' style="width:auto" name="psales">-->
    <!--        <option value="">Select Salesman</option>-->
    <!--    </select>-->
    <!--    <br>-->
    <!--</div>-->
    <!--<button type="submit" name="submit3" class="btn btn-primary">Send</button>-->
    <!--</form>-->
    <!--<br>-->
    <!--<br>-->
    
                <!-- <div class="col-md-6 col-lg-4 mb-3">
                  <div class="card">
                  
                    <div class="card-body">
                      <h5 class="card-title">Notes</h5>
                      <p class="card-text result-container" id="result">
                       
                      </p>
                     
                    </div>
                  </div>
                </div> -->
</div>
                <div class="col-md-12 col-lg-4">
                <nav class="navtop">
	    	<div>
	    		<h1>Event Calendar</h1>
	    	</div>
	    </nav>
		<div class="content home">
			<?=$calendar?>
<BR>
<br>

      <a href="https://www.prokerala.com/general/calendar/en-calendar.php#calendar"><button type="button" name="" class="btn btn-primary">FOR MALAYALAM CALANDER DATES</button></a>
		</div>
                 
                </div>
                <!--/ Congratulations card -->

             
        
        
          

        
                <!--/ Congratulations card -->

        
        
            <!--    <div class="container-xxl flex-grow-1 container-p-y">-->
            <!--  <h4 class="py-3 mb-4"> Add Settings</h4>-->

              <!-- Basic Layout -->
            <!--  <div class="row">-->
            <!--    <div class="col-xl">-->
            <!--      <div class="card mb-4">-->
            <!--        <div class="card-header d-flex justify-content-between align-items-center">-->
                      <!-- <h5 class="mb-0">Basic Layout</h5>
            <!--          <small class="text-muted float-end">Default label</small> -->-->
            <!--        </div>-->
            <!--        <div class="card-body">-->
            <!--          <form method="post">-->
                      
            <!--            <div class="form-floating form-floating-outline mb-4">-->
            <!--              <input type="text" class="form-control" id="basic-default-company" name="status" placeholder="ACME Inc." />-->
            <!--              <label for="basic-default-company">Status</label>-->
            <!--            </div>-->
            <!--            <div class="form-floating form-floating-outline mb-4">-->
            <!--              <input type="text" class="form-control" id="basic-default-company" name="action" placeholder="ACME Inc." />-->
            <!--              <label for="basic-default-company">Action</label>-->
            <!--            </div>-->
            <!--            <div class="form-floating form-floating-outline mb-4">-->
            <!--              <input type="text" class="form-control" id="basic-default-company" name="importance" placeholder="ACME Inc." />-->
            <!--              <label for="basic-default-company">Importance</label>-->
            <!--            </div>-->
                        
            <!--            <div class="form-floating form-floating-outline mb-4">-->
            <!--              <input type="text" class="form-control" id="basic-default-company" name="stage" placeholder="ACME Inc." />-->
            <!--              <label for="basic-default-company">Stage</label>-->
            <!--            </div>-->
            <!--            <div class="form-floating form-floating-outline mb-4">-->
            <!--              <input type="text" class="form-control" id="basic-default-company" name="salesman" placeholder="ACME Inc." />-->
            <!--              <label for="basic-default-company">Referral</label>-->
            <!--            </div>-->
                        
  
            <!--            <button type="submit" name="submit" class="btn btn-primary">Send</button>-->
            <!--          </form>-->
            <!--        </div>-->
            <!--      </div>-->
            <!--    </div>-->
               
            <!--  </div>-->
            <!--</div>-->
                 
            <!--<h5 class="pb-1 mb-4">Edit or Alter Settings</h5>-->
             
                
               



             
            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl" style="background-color: <?php echo $footer_color; ?>;">
                <div
                  class="footer-container d-flex align-items-center justify-content-between py-3 flex-md-row flex-column" >
                  <div class="text-body mb-2 mb-md-0">
                    ©
                    <script>
                      document.write(new Date().getFullYear());
                    </script>
                    
                  </div>
                  <div class="d-none d-lg-inline-block">
                    <a href="https://sbkdigi.in/" class="footer-link me-3" target="_blank">SBK Details</a>
                    
                  </div>
                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- <div class="buy-now">
      <a
        href="https://themeselection.com/item/materio-bootstrap-html-admin-template/"
        target="_blank"
        class="btn btn-danger btn-buy-now"
        >Upgrade to Pro</a
      >
    </div> -->

    <!-- Core JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Fetch and populate filters
        populateFilters();

        // Fetch results based on selected filters
        $('.filter-container select').change(function() {
            fetchResults();
        });
    });

    function populateFilters() {
        // Fetch customer names
        $.get('fetch_customers.php', function(data) {
            $('#customer-name').append(data);
        });

        // Fetch statuses
        $.get('fetch_statuses.php', function(data) {
            $('#status').append(data);
        });

        // Fetch stages
        $.get('fetch_stages.php', function(data) {
            $('#stage').append(data);
        });

        // Fetch salesmen
        $.get('fetch_salesmen.php', function(data) {
            $('#salesman').append(data);
        });
    }

    function fetchResults() {
        var customerName = $('#customer-name').val();
        var status = $('#status').val();
        var stage = $('#stage').val();
        var salesman = $('#salesman').val();

        $.post('fetch_results.php', {
            customer_name: customerName,
            status: status,
            stage: stage,
            salesman: salesman
        }, function(data) {
            $('#result').html(data);
        });
    }
</script>

    <!-- build:js assets/vendor/js/core.js -->
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../assets/js/dashboards-analytics.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
   

  </body>
</html>
