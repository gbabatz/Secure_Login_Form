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
    // connection to database 
    require('db_conn.php');
    session_start();

    // prevent sql injection
    mysqli_set_charset($con, 'utf8md4');

    // When form submitted, insert values into the database.
    // its working because you cannot press submit unless all inputs are filled
    // so when submit is pressed username would be set
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

      //check if email is valid
      $email_regexp = preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email);
      if(!$email_regexp){
        $_SESSION['invalid_email'] = 1; 
        header("Location: register.php");

      // if any of regular expressions dont match then weak password = 1/true
      }else if(!$uppercase || !$lowercase || !$number || !$specialChars) {
        $_SESSION["weak_password"] = 1;

        // variables = 1 if regular expression did not match any	
        $_SESSION["uppercase"] = !$uppercase;
        $_SESSION["lowercase"] = !$lowercase;
        $_SESSION["number"] = !$number;
        $_SESSION["special"] = !$specialChars;

        header("Location: register.php");
      }else{  
        // here we are sure that user inputs are valid

       	//check if user already exists 
	      $stmt = mysqli_prepare($con, "SELECT id from users_upgraded where email=?");
        mysqli_stmt_bind_param($stmt,'s', $email);
        //fetch results
        mysqli_stmt_execute($stmt);
	      mysqli_stmt_store_result($stmt);
        if(mysqli_stmt_num_rows($stmt)>=1){
          $_SESSION['user_exists'] = 1;
          header("Location: register.php");	
        }else{
          // here we can add the user to the database
          // prepare statement
          $stmt = mysqli_prepare($con, "INSERT into users_upgraded (username, password, email) VALUES ( ?, sha2( ? ,224) , ? )");
        mysqli_stmt_bind_param($stmt,'sss', $username, $password, $email);

          //fetch results
          $result = mysqli_stmt_execute($stmt);

          if ($result) {
            echo "<div class='form'>
                  <h2>You are registered successfully.</h2><br/>
                  <p class='link'>Click here to <a href='login.php'>Login</a></p>
                  </div>";
          }
        }
      }
    }else{
    ?>
    <div class="register">
      <h1>Create New User</h1>
      <form action="" method="POST">
        <label for="username">
          <i class="fas fa-user"></i>
        </label>
        <input type="text" name="username" placeholder="Username" id="username" maxlength="50" required>
        <label for="password">
          <i class="fas fa-archive"></i>
        </label>
        <input type="text" name="email" placeholder="Email" id="email" required>
        <label for="email">
          <i class="fas fa-lock"></i>
        </label>
        <div class='password-container'>
        <input type="password" name="password" placeholder="Password" id="password-field" minlength="8" required>
        <i id='pass-status' class='fa fa-eye' aria-hidden='true' onClick="viewPassword()"></i>
        </div>
        <input type="submit" value="Sign Up">
      </form>
      
    <?php 
    // user messages implementation if weak password or invalid email during register
    if(isset($_SESSION["weak_password"]) && $_SESSION["weak_password"] == 1){
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

    if(isset($_SESSION['invalid_email']) && $_SESSION['invalid_email'] == 1){
      echo "<div class='invalid_email'>";
      echo "<h3>Please give a valid email address.</h3>";
      echo "</div>";
      $_SESSION['invalid_email'] = 0;
    }

    if(isset($_SESSION['user_exists']) && $_SESSION['user_exists'] == 1){
      echo "<div class='user_exists'>";
      echo "<h3>user already exists.</h3>";
      echo "</div>";
      $_SESSION['user_exists'] = 0;
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
        }else{
          passwordInput.type='password';
          passStatus.className='fa fa-eye';
        }
      }
    </script>

  </body>
</html>
