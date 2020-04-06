<?php
	require('db.php');
	require('adminheader.php');
	session_start(); 
	// var_dump($_SESSION);
	// echo $_SESSION['name'];
	if(!isset($_SESSION['login_user'])){ 
	  header("location: adminLogin.php"); // Redirecting To Home Page 
	}
?>
<body onload="students()">
<?php
	require('adminbody.php');
?>	