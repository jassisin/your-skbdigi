<?php class createConnection 				//create a class for make connection
{

var $host='localhost';
var $username='root';    		
 //specify the sever details for mysql
var $password='';
var $database='sbkdigi_your';
var $myconn;

//  var $host='localhost';
// var $username='majojohn_ayur';    		
//  // specify the sever details for mysql
// var $password='wU8=qd3i_Y2a';
// var $database='majojohn_ayur';
//  var $myconn;



function connectToDatabase() 		// create a function for connect database
{
$conn= mysqli_connect($this->host,$this->username,$this->password, $this->database);
if(!$conn)// testing t<em></em>he connection

{

die ("Cannot connect to the database");




}



else

{

$this->myconn = $conn;
 
//echo "Connection established";

}



return $this->myconn;



    }



    function selectDatabase() 			// selecting the database.

    {

        mysql_select_db($this->database);  	//use php inbuild functions for select database



        if(mysql_error()) 			// if error occured display the error message

        {



            echo "Cannot find the database ".$this->database;



        }

						//         echo "Database selected..";       

    }



    function closeConnection() 			// close the connection

    {

        mysql_close($this->myconn);



						//        echo "Connection closed";

    }



}







$connection = new createConnection();

$conn = $connection->connectToDatabase();

$baseurl ="http://localhost/jomol/shoplogin/";


?>