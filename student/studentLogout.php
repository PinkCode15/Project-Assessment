<?php
session_start(); 
if(session_destroy()){// Destroying All Sessions { 
  header("Location: studentLogin.php"); // Redirecting To Home Page 
}
?>