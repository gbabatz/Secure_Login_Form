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
	session_start();
	// prevent sql injection
	mysqli_set_charset($con, 'utf8md4');
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
		if(!$uppercase || !$lowercase || !$number || !$specialChars) {
			$_SESSION["weak_password"] = 1;
			//if not defined it has warning depending on the situtation
			//if isset is not used when showing the message below the form
			$_SESSION["uppercase"] = 0;
			$_SESSION["lowercase"] = 0;
			$_SESSION["number"] = 0;
			$_SESSION["special"] = 0;

			if(!$uppercase){
				$_SESSION["uppercase"] = 1;
			}
			if(!$lowercase){
			        $_SESSION["lowercase"] = 1;
			}
			if(!$number){
				$_SESSION["number"] = 1;
	                }
			if(!$specialChars){	
				$_SESSION["special"] = 1;
			}
			header("Location: register.php");
		}else{
			//with prepared statements
			$stmt = mysqli_prepare($con, "INSERT into users (username, password, email) VALUES ( ?, md5( ? ) , ? )");
			mysqli_stmt_bind_param($stmt,'sss', $username, $password, $email);
	
			$result = mysqli_stmt_execute($stmt);
			//construct query
			//$query    = "INSERT into `users` (username, password, email)
			//			VALUES ('$username', '" . md5($password) . "', '$email')";
			//$result   = mysqli_query($con, $query);
			if ($result) {
				echo "<div class='form'>
				        <h2>You are registered successfully.</h2><br/>
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
				<div class='password-container'>
				<input type="password" name="password" placeholder="Password" id="password-field" minlength="4" required>
				<i id='pass-status' class='fa fa-eye' aria-hidden='true' onClick="viewPassword()"></i>
				</div>
				<input type="submit" value="Sign Up">
			</form>
			<?php 
			if(isset($_SESSION["weak_password"])){
				if($_SESSION["weak_password"] == 1){
					echo "<div class='weak_password'>"; 
					echo "<h3> Weak Password!</h3>";
					if($_SESSION["lowercase"] == 1 || $_SESSION["uppercase"] == 1){
						echo "<h3>- at least one uppercase and one lowercase letter</h3>";
						$_SESSION["uppercase"] = 0;
						$_SESSION["lowercase"] = 0;
					}
					if($_SESSION["number"] == 1 || $_SESSION["special"] == 1){
						echo "<h3>- at least one number and special character</h3>";
						$_SESSION["number"] = 0;
						$_SESSION["special"] = 0;
					}
					echo "</div>";

					$_SESSION['weak_password'] = 0;
				}
				}
				?>
        </div>
    <?php
        }
    ?>
	<script>
	function viewPassword()
	{
	  var passwordInput = document.getElementById('password-field');
	  var passStatus = document.getElementById('pass-status');
	 
	  if (passwordInput.type == 'password'){
	    passwordInput.type='text';
	    passStatus.className='fa fa-eye-slash';
	    
	  }
	  else{
	    passwordInput.type='password';
	    passStatus.className='fa fa-eye';
	  }
	}
	</script>

</body>
</html>
