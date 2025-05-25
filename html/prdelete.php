<?php 
 include('connection.php');
   
  
  $tid=$_POST['x'];
  $data = array();
  $sql="UPDATE ordertab SET pr_status=1 WHERE id ='$tid'";
  $res=mysqli_query($conn,$sql);
 


  echo json_encode($data);


?>
