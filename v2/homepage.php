<?php
    //include auth_session.php file on all user panel pages
    require('db.php');
    include("session_auth.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>HomePage</title>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link href="home.css" rel="stylesheet" type="text/css">
    </head>
	<body>
		<div class="container">
            <h2>Secure login page - Ασφάλεια Συστημάτων Υπολογιστών</h2>
            <h2>Successfully Logged In! Welcome, <?php echo $_SESSION['username'];?>!</h2>
            <form action="logout.php" method="post">
                <input type="submit" value="Logout">
            </form>	
		</div>
	</body>
</html>