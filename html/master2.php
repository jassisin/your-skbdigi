<?php 
 include('connection.php');
   
  
  $barcode=$_POST['x'];
  $data = array();
  $sql="select * from master where product_barcode='$barcode' and active=0 ";
  $res=mysqli_query($conn,$sql);
  if(mysqli_num_rows($res)>0){
  while($rows=mysqli_fetch_array($res)){
           $data['prname'] = $rows['product_name'];
           $data['dp'] =$rows['dp'];
           $data['gst'] = $rows['gst'];
  }

  
}
  else{
      $data['prname'] ='';
      $data['dp'] ='';
      $data['gst'] ='';
  }
 
  echo json_encode($data);


?>
