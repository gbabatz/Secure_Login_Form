<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Register</title>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link href="register.css" rel="stylesheet" type="text/css">
    </head>
	<body>
    <?php
	require('db_conn.php');
	// prevent sql injection
	mysqli_set_charset('utf8md4');
	// When form submitted, insert values into the database.
        if (isset($_REQUEST['username'])) {
	    // removes backslashes
	    $username = strip_tags($_POST['username']); // XSS Protection
		$username = stripslashes($username);
		//escapes special characters in a string
		$username = mysqli_real_escape_string($con, $username);
		$email    = stripslashes($_POST['email']);
		$email    = mysqli_real_escape_string($con, $email);
		$password = stripslashes($_POST['password']);
		$password = mysqli_real_escape_string($con, $password);

		// Validate password strength
		$uppercase = preg_match('@[A-Z]@', $password);
		$lowercase = preg_match('@[a-z]@', $password);
		$number    = preg_match('@[0-9]@', $password);
		$specialChars = preg_match('@[^\w]@', $password);

		if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
			$_SESSION['weak_password'] = 1;
			header("Location: register.php");
		}else{
			//with prepared statements
			$stmt = mysqli_prepare($con, "INSERT into users (username, password, email) VALUES ( ?, md5( ? ) , ? )");
			mysqli_stmt_bind_param($stmt,'sss', $username, $password, $email);
	
			$result = mysqli_stmt_execute($stmt);
			//construct query
			$query    = "INSERT into `users` (username, password, email)
						VALUES ('$username', '" . md5($password) . "', '$email')";
			//$result   = mysqli_query($con, $query);
			if ($result) {
				echo "<div class='form'>
					<h3>You are registered successfully.</h3><br/>
					<p class='link'>Click here to <a href='login.php'>Login</a></p>
					</div>";
			}
		}
        } else {
    ?>
		<div class="register">
			<h1>Create New User</h1>
			<form action="" method="POST">
				<label for="username">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="username" placeholder="Username" id="username" maxlength="20" required>
				<label for="password">
					<i class="fas fa-archive"></i>
                </label>
                <input type="text" name="email" placeholder="Email" id="email" required>
				<label for="email">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="Password" id="password" minlength="8" required>
				<input type="submit" value="Sign Up">
			</form>
			<?php 
			if(isset($_SESSION['weak_password'])){
				if($_SESSION['weak_password'] == 1){
					echo "<div class='weak_password'>"; 
					echo "<h3> Weak Password!</h3>";
					echo "<h3>- 8 characters long</h3>";
					echo "<h3>- at least one upper case letter</h3>";
					echo "<h3>- at least one number and special character</h3>";
					echo "</div>";

					$_SESSION['weak_password'] = 0;
				}
				}
				?>
        </div>
    <?php
        }
    ?>
	</body>
</html>
