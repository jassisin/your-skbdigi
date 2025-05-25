<?php 
 include('connection.php');
   
  
  $barcode=$_POST['x'];
  $data = array();
  $sql="select * from master where product_barcode='$barcode' and active=0 ";
  $res=mysqli_query($conn,$sql);
  if(mysqli_num_rows($res)>0){
  while($rows=mysqli_fetch_array($res)){
      $data['prname'] = $rows['product_name'];
      $data['hsncode'] = $rows['hsncode'];
      $data['landing'] =$rows['landing'];
      $data['withoutgst'] =$rows['without_gst'];
      $data['dp'] =$rows['dp'];
      $data['mrp'] =$rows['mrp'];
      $data['gst'] = $rows['gst'];
      $data['id'] = $rows['id'];
      $data['rcp'] = $rows['rcp'];
  }

  
}
  else{
    $data['prname'] ='';
      $data['hsncode'] = '';
      $data['landing'] ='';
      $data['withoutgst'] ='';
      $data['dp'] ='';
      $data['gst'] ='';
      $data['tid'] ='';
      $data['rcp'] ='';
      $data['mrp'] ='';

  }
  // $sql2="select hsncode from vendorstock where item_name='$itemname' order by id DESC LIMIT 1";
  // $res2=mysqli_query($conn,$sql2);
  // if(mysqli_num_rows($row2)){
  //   $row2=mysqli_fetch_array($res2);
  
  //   $data['hsncode']=$row2['hsncode'];
  // }
  // else{
  //   $data['hsncode']='';
  // }

  echo json_encode($data);


?>
