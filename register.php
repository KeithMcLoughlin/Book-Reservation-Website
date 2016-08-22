<html>
<head>
	<title>Registration Page</title>
	<link rel="stylesheet" type="text/css" href="library.css" />
	<script>
		function checkPassword()
		{
			var pass = document.getElementById("pass").value;
			var confirm = document.getElementById("confirm").value;
			if (pass != confirm)
			{
				alert("Passwords don't match");
				return false;
			}
		}
	</script>
</head>
<body>
	<!--Title at the top of page-->
	<div class = "head">Registration Page</div>
	
	<h3>Please enter your details here</h3>
	<!--Registration Form-->
	<form name="register" method="post" onsubmit = "return checkPassword()">
		First Name: </br>
		<input name="firstName" size="20" maxlength="20" autofocus required /></br>
		Surname: </br>
		<input name="surname" size="20" maxlength="20" required /></br>
		Address Line 1: </br>
		<input name="address1" size="40" maxlength="40" required /></br>
		Address Line 2: </br>
		<input name="address2" size="40" maxlength="40" required /></br>
		City: </br>
		<input name="city" size="20" maxlength="20" required /></br>
		Telephone: </br>
		<input name="phone" size="20" maxlength="20" required /></br>
		Mobile: </br>
		<input name="mobile" size="20" maxlength="20" required /></br>
		Username: </br>
		<input name="username" size="20" maxlength="20" required /></br>
		Password: </br>
		<input type = "password" name="password" id = "pass" placeholder = "must be 6 characters" size="20" maxlength="6" minlength = "6" required /></br>
		Confirm Password: </br>
		<input type = "password" name="confirmPassword" id = "confirm" placeholder = "re-enter password above" size="20" maxlength="6" required /></br>
		</br>
		<input type = "submit" name = "register" value = "Register">
	</form>
	
	<?php
		//connect to the "librarydb" database
		$db = mysql_connect('localhost', 'root', '') or die(mysql_error());
		if($db == FALSE) die('Fail message');
		mysql_select_db("librarydb") or die(mysql_error());
		
		//check if the fields have been set in the form above
		if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['firstName']) && isset($_POST['surname']) && isset($_POST['address1'])
			&& isset($_POST['address2']) && isset($_POST['city']) && isset($_POST['phone']) && isset($_POST['mobile'])) 
		{
			$first = $_POST["firstName"];
			$last = $_POST["surname"];
			$addr1 = $_POST["address1"];
			$addr2 = $_POST["address2"];
			$city = $_POST["city"];
			$tel = $_POST["phone"];
			$mob = $_POST["mobile"];
			$user = $_POST["username"];
			$pass = $_POST["password"];
			//insert this new account and details into the users table
			$sql = "INSERT INTO users (username, password, firstName, surname, addressLine1, addressLine2, city, telephone, mobile)
			VALUES ('$user', '$pass', '$first', '$last', '$addr1', '$addr2', '$city', '$tel', '$mob')";
			mysql_query($sql);
			//return back to the login page
			header("location: login.html");
		}
	?>
</body>
<footer>
  <p><strong>Created by:</strong> Keith Mc Loughlin    <strong>Student No:</strong> C14337491</p>
</footer>
</html>