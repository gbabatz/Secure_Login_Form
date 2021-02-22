<?php
  session_start();
  require('db_conn.php');
		

  //need to check here if ip is the same and if logged_now = 1
  $ip = $_SERVER['REMOTE_ADDR'];
  $email = $_SESSION['email'];

  $q = "select username, last_ip, logged_now from users_upgraded where email='$email'";
  $res = mysqli_query($con,$q);
  $row = mysqli_fetch_array($res,MYSQLI_ASSOC);

  if($row['logged_now'] && ($row['last_ip'] == $ip)){
    $_SESSION['username'] = $row['username'];
    //if condition is true till here then we connect normally
  }else{
   header("Location: login.php");
   exit();
  } 
?>
