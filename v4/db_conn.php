<?php
  // Enter your host name, database username, password, and database name.
  // If you have not set database password on localhost then set empty.
  //$con = mysqli_connect("localhost","restricted_user","1234","Login_Form");
  $con = mysqli_connect("localhost","root","admin23","Login_Form");
  // Check connection
  if(mysqli_connect_errno()){
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
?>
