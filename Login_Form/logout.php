<?php
  require('db_conn.php');
  session_start();
  $email = $_SESSION['email'];
  $update_q = "update users_upgraded set logged_now=0 where email='$email'";

  if(!mysqli_query($con,$update_q)){
    header('Location: homepage.php'); 
  }else{
    session_destroy();
    // Redirect to the login page:
    header('Location: login.php');
  }
?>
