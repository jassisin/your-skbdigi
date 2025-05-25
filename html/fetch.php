<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "crm";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$output = '';
if(isset($_POST['query'])) {
    $search = $_POST['query'];
    $sql = "SELECT * FROM details WHERE (student_name LIKE '%$search%' OR course LIKE '%$search%' OR parent_phone OR '%$search%') AND active = 0";
} else {
    $sql = "SELECT * FROM details WHERE active = 0";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $output .= '<div class="card">
    <h5 class="card-header">Student Details</h5>
    <div class="table-responsive text-nowrap">
      <table class="table">
      <thead>
      <tr class="text-nowrap">
        <th>Serial No</th>
        <th>Student name</th>
        <th>Parent name</th>
        <th>Parent phone</th>
        <th>Parent email</th>
        <th>Course</th>
        <th>Edit</th>
        <th>Delete</th>
       
      </tr>
    </thead>
    <tbody class="table-border-bottom-0">';
    while($row = $result->fetch_assoc()) {
        $output .= '<tr>
                        <td>'.$row["id"].'</td>
                        <td>'.$row["student_name"].'</td>
                        <td>'.$row["parent_name"].'</td>
                        <td>'.$row["parent_phone"].'</td>
                        <td>'.$row["parent_email"].'</td>
                        <td>'.$row["course"].'</td>
                        <td>
                        
                              
                              <a href="actions.php?id='.$row["id"].'"><i class="mdi mdi-pencil-outline me-1"></i></a>
                           
                            </td>
                            <td><a href="student-list.php?did='.$row["id"].'"><i class="mdi mdi-trash-can-outline me-1"></a></i></td>
                    </tr>';
    }
    $output .= '</table>';
} else {
    $output .= '0 results found';
}

echo $output;

// Close connection
$conn->close();
?>
