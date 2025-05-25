<?php



function user_exist($username,$conn,$uid)
{

if($uid==''){
$query = "select * from admin_log where username='{$username}'";
	$resultset = mysqli_query($conn,$query);
	if($resultset && mysqli_num_rows($resultset)>0){return true;}
	else{return false;}
	}
else {

$query = "select * from admin_log where username='{$username}' AND id!='$uid'";
$resultset = mysqli_query($conn,$query);
	if($resultset && mysqli_num_rows($resultset)>0){return true;}
	else{return false;}
}	
		
}




function delete_row($table,$uid,$conn)
{
	$ip_address = $_SERVER['REMOTE_ADDR'];
	$d_user = $_SESSION['login_user'];
	$timezone = new DateTimeZone("Asia/Kolkata" );
$date = new DateTime();
$date->setTimezone($timezone );
$date=$date->format( 'Y-m-d H:i:s' );
	
	
$sql = "UPDATE  {$table} SET status=1, ip_address = '$ip_address'  WHERE id='{$uid}'";
if ($conn->query($sql) === TRUE) {	return true;} 
else { return false; }
}



function delete_row_aid($table,$uid,$conn)
{
	$ip_address = $_SERVER['REMOTE_ADDR'];
	$d_user = $_SESSION['login_user'];
	$timezone = new DateTimeZone("Asia/Kolkata" );
$date = new DateTime();
$date->setTimezone($timezone );
$date=$date->format( 'Y-m-d H:i:s' );
	
	
$sql = "UPDATE  {$table} SET status=1, ip_address = '$ip_address', d_user = '$d_user', del_date = '$date'  WHERE aid='{$uid}'";
if ($conn->query($sql) === TRUE) {	return true;} 
else { return false; }
}




function diactive_row($table,$uid,$conn)
{
$sql = "UPDATE {$table} SET ac_tive=2 WHERE id='$uid' ";
if ($conn->query($sql) === TRUE) {	return true;} 
else { return false; }
}





function active_row($table,$uid,$conn)
{
$sql = "UPDATE {$table} SET ac_tive=0 WHERE id='$uid' ";
if ($conn->query($sql) === TRUE) {	return true;} 
else { return false; }
}




function diactive_row_aid($table,$uid,$conn)
{
$sql = "UPDATE {$table} SET ac_tive=2 WHERE aid='$uid' ";
if ($conn->query($sql) === TRUE) {	return true;} 
else { return false; }
}

function active_row_aid($table,$uid,$conn)
{
$sql = "UPDATE {$table} SET ac_tive=0 WHERE aid='$uid' ";
if ($conn->query($sql) === TRUE) {	return true;} 
else { return false; }
}






function edit_row($table,$array,$conn,$uid)
{

$query = "UPDATE {$table} SET ";
 global $mysqli;
 $parts = array();
 foreach ($array as $key => $value) {
        $parts[] = "`" . $key . "`= '".$value."'";
    }
 $query = $query . implode(",", $parts) . " WHERE `id` ='$uid'";
if ($conn->query($query) === TRUE) {	return true;} 
else { return false; }

}


function edit_row5($table,$array,$conn,$uid)
{

$query = "UPDATE {$table} SET ";
 global $mysqli;
 $parts = array();
 foreach ($array as $key => $value) {
        $parts[] = "`" . $key . "`= '".$value."'";
    }
 $query = $query . implode(",", $parts) . " WHERE `uid` ='$uid'";
if ($conn->query($query) === TRUE) {	return true;} 
else { return false; }

}





function edit_row2($table,$array,$conn,$uid)
{

$query = "UPDATE {$table} SET ";
 global $mysqli;
 $parts = array();
 foreach ($array as $key => $value) {
        $parts[] = "" . $key . "= '".$value."'";
    }
$query = $query . implode(",", $parts) . " WHERE id ='$uid'";
if ($conn->query($query) === TRUE) {	return true;} 
else { return false; }

}

function edit_row3($table,$array,$conn,$barcode,$store)
{

$query = "UPDATE {$table} SET ";
 global $mysqli;
 $parts = array();
 foreach ($array as $key => $value) {
        $parts[] = "`" . $key . "`= '".$value."'";
    }
 $query = $query . implode(",", $parts) . " WHERE `barcode` ='$barcode' and `location` ='$store'";
if ($conn->query($query) === TRUE) {	return true;} 
else { return false; }

}

function edit_row7($table,$array,$conn,$tid)
{

$query = "UPDATE {$table} SET ";
 global $mysqli;
 $parts = array();
 foreach ($array as $key => $value) {
        $parts[] = "`" . $key . "`= '".$value."'";
    }
 $query = $query . implode(",", $parts) . " WHERE `id` ='$tid'";
if ($conn->query($query) === TRUE) {	return true;} 
else { return false; }

}
function edit_row4($table,$array,$conn,$barcode)
{

$query = "UPDATE {$table} SET ";
 global $mysqli;
 $parts = array();
 foreach ($array as $key => $value) {
        $parts[] = "`" . $key . "`= '".$value."'";
    }
 $query = $query . implode(",", $parts) . " WHERE `product_barcode` ='$barcode' and active=0";
if ($conn->query($query) === TRUE) {	return true;} 
else { return false; }

}


function insert_row($table,$array,$conn)
{

$query = "INSERT INTO `{$table}` ";
 global $mysqli;
 $parts = array();
 foreach ($array as $key => $value) {
        $parts[] = "`$key`";
		$parts2[] = "'$value'";
    }
  $query = $query . "(".implode(",",$parts) . ") VALUES". "(".implode(",", $parts2) .")" ;
if ($conn->query($query) === TRUE) {	return true;} 
else { return false; }

}






function select_all($table,$conn){

         $query = ("SELECT * FROM admin_log");
        $result = mysqli_query($conn,$query);

    return $result;
}



function file_upload($uid,$conn,$table)
{
	
	//file upload
if( $_FILES["resume"]["name"]!='' ) 
	{ 
		$file_name=$_FILES["resume"]["name"];
		$file_size=$_FILES["resume"]["size"]/1024; // size in Kilo Bytes
		$file_type=$_FILES["resume"]["type"];
		$file_tmp_name=$_FILES["resume"]["tmp_name"];	
		  
		if($file_type=="application/msword" || $file_type=="text/plain" || 
		$file_type=="application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $file_type=="application/octet-stream")
		{
			
			if(($file_size<=200)&& ($file_size>0))
			{ 
  				$random=rand(111,999);
  				$new_file_name=$random.$file_name;
  				$upload_path="resume/".$new_file_name;
				
  				if( move_uploaded_file($_FILES["resume"]["tmp_name"],"resume/".$new_file_name))
  				{
				
					
  					$qry_res = "update {$table} set `resume`='$new_file_name' where `id`='$uid' ";
  					$res3 = mysqli_query($conn,$qry_res);
  				}
				
			}
			
			else 
			{
  				$str.='Max upload file size limit is 200kb<br />';
  			
			}
			
		}
		
		else
		{
			$str.='You can only upload word doc or docx or text file.<br />';
			
		}
		
		 
	}
	
	
	
	
}



function GetAccounts($username){
  require("dbconn.php");
  $result = mysql_query("SELECT * FROM `accounts` WHERE `username` = '$username' ") or trigger_error(mysql_error()); 
  return $result;
 }


 function generate_order_code($conn)
 {
	 $select = mysqli_query($conn,"SELECT `id` FROM `ordertab` ORDER BY `id` DESC LIMIT 1");
	 $code = "RNO".date('Y') ;
	 if(mysqli_num_rows($select) > 0)
	 {
		 $data = mysqli_fetch_array($select);
		 for($i = 5; $i> strlen(($data['id']+1)); $i--)
		 {
			 $code .= '0' ;
		 }
		 $code .= $data['id']+1 ;
	 }
	 else
	 {
		 $code .= "00001";
	 }
 
	 return $code ;
 }
 


?>