<html>
<head>
	<title>Reservation</title>
	<link rel="stylesheet" type="text/css" href="library.css" />
</head>
<body>
<?php
	//connect to database
	$db = mysql_connect('localhost', 'root', '') or die(mysql_error());
	if($db == FALSE) die('Fail message');
?>
	<!--Title at the top of page-->
	<div class = "head">Reservation Complete</div>
	<!--Navigation Bar-->
	<ul><li><a class="nav" href="search.php">Search For Book</a></li>
		<li><a class="nav" href="reserved.php">My Reserved Books</a></li>
	</ul>
	<?php
		mysql_select_db("librarydb") or die(mysql_error());
		session_start();
		
		//check which button was pressed and store the isbn, title and author of that book
		if(isset($_POST['button1']))
		{
			$isbn = $_SESSION['isbn1'];
			$title = $_SESSION['title1'];
			$author = $_SESSION['author1'];
		}
		
		if(isset($_POST['button2']))
		{
			$isbn = $_SESSION['isbn2'];
			$title = $_SESSION['title2'];
			$author = $_SESSION['author2'];
		}
		
		if(isset($_POST['button3']))
		{
			$isbn = $_SESSION['isbn3'];
			$title = $_SESSION['title3'];
			$author = $_SESSION['author3'];
		}
		
		if(isset($_POST['button4']))
		{
			$isbn = $_SESSION['isbn4'];
			$title = $_SESSION['title4'];
			$author = $_SESSION['author4'];
		}
		
		if(isset($_POST['button5']))
		{
			$isbn = $_SESSION['isbn5'];
			$title = $_SESSION['title5'];
			$author = $_SESSION['author5'];
		}
		//get the user
		$user = $_SESSION['user'];
		//insert this book into the reserved table by this user
		$insert = "INSERT INTO reserved (isbn, username, reserveDate)
				   VALUES ('$isbn', '$user', CURDATE())";
		mysql_query($insert);
		//update the book in the books table to it is reserved
		$update = "UPDATE books SET reserved = 'Y' WHERE isbn = '$isbn';";
		mysql_query($update);
		
		//success message
		echo("You have sucessfully reserved " . $title . " by " . $author);
	?>
	</br>
	<!--Link back to search page-->
	<a href = "search.php">Return to search page</a>
</body>
</html>