<?php
	require('db_conn.php');

    	mysqli_set_charset($con, 'utf8md4');

	$ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDER_FOR']) ? $_SERVER['HTTP_X_FORWARDER_FOR'] : $_SERVER['REMOTE_ADDR'];

        $stmt = mysqli_prepare($con, "INSERT into users (username, password, email) VALUES ( ?, sha2( ? ,224) , ? )");
	
?>
