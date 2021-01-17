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
        } else {
    ?>
		<div class="register">
			<h1>Create New User</h1>
			<form action="" method="POST">
				<label for="username">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="username" placeholder="Username" id="username" required>
				<label for="password">
					<i class="fas fa-archive"></i>
                </label>
                <input type="text" name="email" placeholder="Email" id="email" required>
				<label for="email">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="Password" id="password" required>
				<input type="submit" value="Sign Up">
            </form>
        </div>
    <?php
        }
    ?>
	</body>
</html>
