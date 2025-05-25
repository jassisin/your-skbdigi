<?php

$query2 = "SELECT column_name, is_visible FROM address_visibility";
$stmt2 = mysqli_query($conn, $query2);

if (!$stmt2) {
    die("Query failed: " . mysqli_error($conn));
}

$columnVisibility2 = [];
while ($row2 = mysqli_fetch_assoc($stmt2)) {
    $columnVisibility2[$row2['column_name']] = (int)$row2['is_visible'];
}

mysqli_free_result($stmt2);

// Construct the SQL query to select only the visible columns
$visibleColumns2 = array_keys(array_filter($columnVisibility2));
if (empty($visibleColumns2)) {
    die('No columns to display');
}

$columnsToSelect2 = implode(', ', $visibleColumns2);
if($username=='admin1'){
$query2 = "SELECT $columnsToSelect2 FROM addressbook where active=0";
}
else{
    $query2 = "SELECT $columnsToSelect2 FROM addressbook WHERE active=0 and addedby='$username'";
}
$stmt2 = mysqli_query($conn, $query2);

if (!$stmt2) {
    die("Query failed: " . mysqli_error($conn));
}

$data2 = [];
while ($row2 = mysqli_fetch_assoc($stmt2)) {
    $data2[] = $row2;
}

mysqli_free_result($stmt2);

// Handle export
if (isset($_POST["export_data3"])) {
    $filename = "address-list_" . date('Ymd') . ".xls";
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $show_column = false;

    if (!empty($data2)) {
        foreach ($data2 as $record) {
            if (!$show_column) {
                echo implode("\t", array_keys($record)) . "\n";
                $show_column = true;
            }
            echo implode("\t", array_values($record)) . "\n";
        }
    }
    exit;
}



	
if(isset($_POST["export_datam"])) {	
    $sql_query = "SELECT id,product_barcode,product_name,sub_category,department,sub_department,class,sub_class,landing,without_gst,gst,dp,mrp,margin,rcp,material_dis,meta_keyword,manufacture_details,available_stock,image,image_meta,vendor_name,hsncode,insert_date,pritvimart_margin,store_margin,pm_profit,store_profit,sgst,cgst,cess FROM master WHERE active=0 ";
$resultset = mysqli_query($conn, $sql_query) or die("database error:". mysqli_error($conn));
$developer_records = array();
while( $rows = mysqli_fetch_assoc($resultset) ) {
	$developer_records[] = $rows;
}
	$filename = "product-list_".date('Ymd') . ".xls";			
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=\"$filename\"");	
	$show_coloumn = false;
	if(!empty($developer_records)) {
	  foreach($developer_records as $record) {
		if(!$show_coloumn) {
		  // display field/column names in first row
		  echo implode("\t", array_keys($record)) . "\n";
		  $show_coloumn = true;
		}
		echo implode("\t", array_values($record)) . "\n";
	  }
	}
	exit;  
}
if(isset($_POST["export_dataex"])) {	
    $sql_query = "SELECT id,name,staff,head,category,expdate,barcode,amount,frequency,dedicate,marked,balance FROM resource where active=0";
$resultset = mysqli_query($conn, $sql_query) or die("database error:". mysqli_error($conn));
$developer_records = array();
while( $rows = mysqli_fetch_assoc($resultset) ) {
	$developer_records[] = $rows;
}
	$filename = "expense-list_".date('Ymd') . ".xls";			
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=\"$filename\"");	
	$show_coloumn = false;
	if(!empty($developer_records)) {
	  foreach($developer_records as $record) {
		if(!$show_coloumn) {
		  // display field/column names in first row
		  echo implode("\t", array_keys($record)) . "\n";
		  $show_coloumn = true;
		}
		echo implode("\t", array_values($record)) . "\n";
	  }
	}
	exit;  
}
if(isset($_POST["export_donor"])) {	
    $sql_query = "SELECT Fullname,Email,Mobile_no,Pincode,Cusaddress,Add_date FROM customer where active=0";
$resultset = mysqli_query($conn, $sql_query) or die("database error:". mysqli_error($conn));
$developer_records = array();
while( $rows = mysqli_fetch_assoc($resultset) ) {
	$developer_records[] = $rows;
}
	$filename = "expense-list_".date('Ymd') . ".xls";			
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=\"$filename\"");	
	$show_coloumn = false;
	if(!empty($developer_records)) {
	  foreach($developer_records as $record) {
		if(!$show_coloumn) {
		  // display field/column names in first row
		  echo implode("\t", array_keys($record)) . "\n";
		  $show_coloumn = true;
		}
		echo implode("\t", array_values($record)) . "\n";
	  }
	}
	exit;  
}
if(isset($_POST["export_donation"])) {	
    $sql_query = "SELECT recno, store ,mode,receiptdate,receiptno,donor,proname,amount,remindate,payment,reference,occation,added_by,indate FROM customer where active=0";
$resultset = mysqli_query($conn, $sql_query) or die("database error:". mysqli_error($conn));
$developer_records = array();
while( $rows = mysqli_fetch_assoc($resultset) ) {
	$developer_records[] = $rows;
}
	$filename = "Donationlist_".date('Ymd') . ".xls";			
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=\"$filename\"");	
	$show_coloumn = false;
	if(!empty($developer_records)) {
	  foreach($developer_records as $record) {
		if(!$show_coloumn) {
		  // display field/column names in first row
		  echo implode("\t", array_keys($record)) . "\n";
		  $show_coloumn = true;
		}
		echo implode("\t", array_values($record)) . "\n";
	  }
	}
	exit;  
}
$query = "SELECT column_name, is_visible FROM actions_visibility";
$stmt = mysqli_query($conn, $query);

if (!$stmt) {
    die("Query failed: " . mysqli_error($conn));
}

$columnVisibility = [];
while ($row = mysqli_fetch_assoc($stmt)) {
    $columnVisibility[$row['column_name']] = (int)$row['is_visible'];
}

mysqli_free_result($stmt);

// Construct the SQL query to select only the visible columns
$visibleColumns = array_keys(array_filter($columnVisibility));
if (empty($visibleColumns)) {
    die('No columns to display');
}

$columnsToSelect = implode(', ', $visibleColumns);
if($username=='admin1'){
$query = "SELECT $columnsToSelect FROM actions WHERE active=0";
}
else{
    $query = "SELECT $columnsToSelect FROM actions WHERE active=0 and managed_by='$username'";
}
$stmt = mysqli_query($conn, $query);

if (!$stmt) {
    die("Query failed: " . mysqli_error($conn));
}

$data = [];
while ($row = mysqli_fetch_assoc($stmt)) {
    $data[] = $row;
}

mysqli_free_result($stmt);

// Handle export
if (isset($_POST["export_data"])) {
    $filename = "actions-list_" . date('Ymd') . ".xls";
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $show_column = false;

    if (!empty($data)) {
        foreach ($data as $record) {
            if (!$show_column) {
                echo implode("\t", array_keys($record)) . "\n";
                $show_column = true;
            }
            echo implode("\t", array_values($record)) . "\n";
        }
    }
    exit;
}


$query3 = "SELECT column_name, is_visible FROM task_visibility";
$stmt3 = mysqli_query($conn, $query3);

if (!$stmt3) {
    die("Query failed: " . mysqli_error($conn));
}

$columnVisibility3 = [];
while ($row3 = mysqli_fetch_assoc($stmt3)) {
    $columnVisibility3[$row3['column_name']] = (int)$row3['is_visible'];
}

mysqli_free_result($stmt3);

// Construct the SQL query to select only the visible columns
$visibleColumns3 = array_keys(array_filter($columnVisibility3));
if (empty($visibleColumns3)) {
    die('No columns to display');
}

$columnsToSelect3 = implode(', ', $visibleColumns3);
if($username=='admin1'){
$query3 = "SELECT $columnsToSelect3 FROM task WHERE active=0";
}
else{
    $query3 = "SELECT $columnsToSelect3 FROM task WHERE active=0 and addedby='$username'";

}
$stmt3 = mysqli_query($conn, $query3);

if (!$stmt3) {
    die("Query failed: " . mysqli_error($conn));
}

$data3 = [];
while ($row3 = mysqli_fetch_assoc($stmt3)) {
    $data3[] = $row3;
}

mysqli_free_result($stmt3);

// Handle export
if (isset($_POST["export_data1"])) {
    $filename = "task-list_" . date('Ymd') . ".xls";
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $show_column = false;

    if (!empty($data3)) {
        foreach ($data3 as $record) {
            if (!$show_column) {
                echo implode("\t", array_keys($record)) . "\n";
                $show_column = true;
            }
            echo implode("\t", array_values($record)) . "\n";
        }
    }
    exit;
}




// $sql_query = "SELECT id,customer,short_dis,long_dis,importance,active,indate FROM task ";
// $resultset = mysqli_query($conn, $sql_query) or die("database error:". mysqli_error($conn));
// $developer_records = array();
// while( $rows = mysqli_fetch_assoc($resultset) ) {
// 	$developer_records[] = $rows;
// }	
// if(isset($_POST["export_data1"])) {	
// 	$filename = "task-list_".date('Ymd') . ".xls";			
// 	header("Content-Type: application/vnd.ms-excel");
// 	header("Content-Disposition: attachment; filename=\"$filename\"");	
// 	$show_coloumn = false;
// 	if(!empty($developer_records)) {
// 	  foreach($developer_records as $record) {
// 		if(!$show_coloumn) {
// 		  // display field/column names in first row
// 		  echo implode("\t", array_keys($record)) . "\n";
// 		  $show_coloumn = true;
// 		}
// 		echo implode("\t", array_values($record)) . "\n";
// 	  }
// 	}
// 	exit;  
// }



$sql_query = "SELECT id,student_name,parent_name,dob,address,accomodation,parent_phone,parent_email,course,doa,course_details,image,remarks,parents_username,parents_password,student_username,student_password,addedby,indate,active  FROM details ";
$resultset = mysqli_query($conn, $sql_query) or die("database error:". mysqli_error($conn));
$developer_records = array();
while( $rows = mysqli_fetch_assoc($resultset) ) {
	$developer_records[] = $rows;
}	
if(isset($_POST["export_data5"])) {	
	$filename = "students-list_".date('Ymd') . ".xls";			
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=\"$filename\"");	
	$show_coloumn = false;
	if(!empty($developer_records)) {
	  foreach($developer_records as $record) {
		if(!$show_coloumn) {
		  // display field/column names in first row
		  echo implode("\t", array_keys($record)) . "\n";
		  $show_coloumn = true;
		}
		echo implode("\t", array_values($record)) . "\n";
	  }
	}
	exit;  
}



?>