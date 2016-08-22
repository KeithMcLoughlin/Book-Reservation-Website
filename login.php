<html>
<head>
	<title>Login Unsuccessful</title>
	<link rel="stylesheet" type="text/css" href="library.css" />
</head>
<body>
	<?php
			session_start();
			//connect to database
			$db = mysql_connect('localhost', 'root', '') or die(mysql_error());
			if($db == FALSE) die('Fail message');
			mysql_select_db("librarydb") or die(mysql_error());
			
			//check if the username and password were entered on the login form
			if ( isset($_POST['loginUsername']) && isset($_POST['loginPassword']))
			{
				$username = $_POST['loginUsername'];
				//store the username in the session
				$_SESSION['user'] = $username;
				$password = $_POST['loginPassword'];
				//check if a row in the users table has this username and password
				$result = mysql_query("SELECT * FROM users WHERE username = '$username' AND password = '$password'");
				$rows = mysql_num_rows($result);
				//if rows equals 1 there is a match
				if ($rows == 1) 
				{
					//go to the home page
					header("location: home.php");
				} 
				else 
				{
					//if the username and password are not in the database, display this message
					echo "Username or Password is invalid";
				}
			}
	?>
	</br>
	<!--Link back to login page-->
	<a href = "login.html">back</a>
</body>
</html>