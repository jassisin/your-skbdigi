<?php
include('connection.php');

$output = '';

$query = "SELECT salesman, amount, 
               RANK() OVER (ORDER BY amount DESC) AS rank
        FROM salesman where active=0";

if (isset($_POST['query']) && $_POST['query'] != '') {
    $search = $_POST['query'];
    $query .= " AND (salesman LIKE '%$search%' )";
}




$result = $conn->query($query);

if ($result->num_rows > 0) {
    $output .= '<div class="card">
    <h5 class="card-header">Ranking</h5>
    <div class="table-responsive text-nowrap">
      <table class="table" id="orders_table">
      <thead>
      <tr class="text-nowrap">
        <th>Rank</th>
        <th>Name</th>
        <th>Amount</th>
    
      </tr>
    </thead>
    <tbody class="table-border-bottom-0">';
    while($row = $result->fetch_assoc()) {
        $output .= '<tr>
                        <td>'.$row["rank"].'</td>
                        <td>'.$row["salesman"].'</td>
                        <td>'.$row["amount"].'</td>
                      
                    </tr>';
    }
    $output .= '</tbody></table></div></div>';
} else {
    $output .= '<div class="card"><h5 class="card-header">No results found</h5></div>';
}

echo $output;

$conn->close();
?>
