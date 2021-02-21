<?php
  session_start();
	$ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDER_FOR']) ? $_SERVER['HTTP_X_FORWARDER_FOR'] : $_SERVER['REMOTE_ADDR'];
	echo $ip;
?>
