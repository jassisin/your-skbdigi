<?php 
 include('connection.php');
   
  
  $fullname=$_POST['x'];
  $mobno=$_POST['y'];
  $category=$_POST['z'];
  $address=$_POST['a'];
  $gst=$_POST['e'];
 
  $data = array();
 

  $sql="INSERT INTO vendor (name,phnumber,gst,address,category) VALUES ('$fullname','$mobno', '$gst','$address','$category')";
  $res=mysqli_query($conn,$sql);

  echo json_encode($data);


?>
