<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Login</title>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	  <link href="style.css" rel="stylesheet" type="text/css">
	  <link rel="icon" href="data:;base64,=">
  </head>
	<body>
    <?php

      require('db_conn.php');
	    session_start();

      // redirect if you back from homepage without logout
      if(isset($_SESSION['username'])){
        header("Location: homepage.php");
      }

      // prevent sql injection
      mysqli_set_charset($con, 'utf8md4');	

      // When form submitted, check and create user session.
      if (isset($_POST["username"])) {
        // prevent sql injection with prepared statements
        $username = strip_tags($_POST['username']); // XSS Protection	
        $username = stripslashes($username);    // removes backslashes	
        $username = mysqli_real_escape_string($con, $username);

        $password = stripslashes($_POST['password']);
        $password = mysqli_real_escape_string($con, $password);
            
        $answer = stripslashes($_POST["answer"]);
        $answer = mysqli_real_escape_string($con, $answer);
          
        $stmt = mysqli_prepare($con, "SELECT * FROM users WHERE username=? AND password=sha2(?,224)");
        mysqli_stmt_bind_param($stmt,'ss', $username, $password);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        // Check user is exist in the database
        $rows = mysqli_stmt_num_rows($stmt);

        mysqli_stmt_close($stmt);
        if (($rows >= 1) && (strcasecmp($answer,$_SESSION['correct_answer'])==0)){
          $_SESSION["username"] = $username;
          // Redirect to user dashboard page
          unset($_SESSION['correct_answer']);
          header("Location: homepage.php");
        }else{	     
          $_SESSION['failed_login'] = 1;
          header("Location: login.php");
        }
     
      }else{

        $random_num = rand(1,10); //random case scenario for test question
        $query_quest = "SELECT question,answer FROM questions WHERE id=$random_num";
        $result_quest = mysqli_query($con, $query_quest);	
        $que = mysqli_fetch_array($result_quest,MYSQLI_ASSOC);	
        $_SESSION['correct_answer'] = $que['answer'];

    ?>
		<h2>Secure login page - Ασφάλεια Συστημάτων Υπολογιστών</h2>
		<div class="login">
			<h1>Login</h1>
			<form action="" method="POST">
				<label for="username">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="username" placeholder="Username" id="username" maxlength="20" required>
				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<div class='password-container'>
				  <input type="password" name="password" placeholder="Password" id="password-field" minlength="4" required>
				  <i id='pass-status' class='fa fa-eye' aria-hidden='true' onClick="viewPassword()"></i>
				</div>
				<div class='row'>
			    <?php echo "<h5>" . $que["question"] . "</h5>"; ?> 
				</div>
				<input type="text" name="answer" placeholder="Sanity Check" id="answer" required>
				<input type="submit" id="submit" name="submit" value="Login">
      </form>

	    <?php 
        if(isset($_SESSION['failed_login']) && $_SESSION['failed_login'] == 1){
          echo "<div class='failed_login'>"; 
          echo "<h3> Wrong Credentials.</h3>";
          echo "<h3> are you a hacker?</h3>";
          echo "</div>";

          $_SESSION['failed_login'] = 0;
        }
      ?>

      <h4>New here? <a href="register.php">Sign Up!</a></h4>
    </div>

    <?php
      }
    ?>

    <script>
      function viewPassword()
      {
        var passwordInput = document.getElementById('password-field');
        var passStatus = document.getElementById('pass-status');
       
        if(passwordInput.type == 'password'){
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
