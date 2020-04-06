<?php
$hostname = "localhost";
$username = "root";
$password = "compilewiththe3";
$database = "projectassessment";
date_default_timezone_set("Africa/Lagos");
//Create Connection
try{
	$conn = new PDO("mysql:host$hostname;dbanme=$database",$username,$password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $ex){
	echo "Sorry, something went wrong\n".$ex->getMessage();

}
?>