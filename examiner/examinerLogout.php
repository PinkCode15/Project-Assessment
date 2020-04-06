<?php
session_start(); 
if(session_destroy()){// Destroying All Sessions { 
  header("Location: examinerLogin.php"); // Redirecting To Home Page 
}
?>