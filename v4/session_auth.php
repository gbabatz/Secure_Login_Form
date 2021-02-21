<?php
  session_start();
  require('db_con.php');
		

  //need to check here if ip is the same and if logged_now = 1
  $ip = $_SERVER['REMOTE_ADDR'];
  $email = $_SESSION['email'];

  $q = "select last_ip, logged_now from users_upgraded where email=$email";
  if($res = mysqli($con,$q){

  }else{
   header("Location: login.php");
   exit();
  } 
    
?>
