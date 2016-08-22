<html>
<head>
	<title>My Reserved Books</title>
	<link rel="stylesheet" type="text/css" href="library.css" />
</head>
<body>
<?php
	//connect to database
	$db = mysql_connect('localhost', 'root', '') or die(mysql_error());
	if($db == FALSE) die('Fail message');
?>
	<!--Title at the top of page-->
	<div class = "head">My Reserved Books</div>
	<!--Navigation Bar-->
	<ul><li><a class="nav" href="search.php">Search For Book</a></li>
		<li><a class="nav" href="reserved.php">My Reserved Books</a></li>
	</ul>
	<?php
		mysql_select_db("librarydb") or die(mysql_error());
		session_start();
		//get the user
		$username = $_SESSION['user'];
		
		//column titles
		echo '<table>'."\n";
			echo "<tr><th>";	echo("ISBN");
			echo("</th><th>");	echo("Title");
			echo("</th><th>");	echo("Author");
			echo("</th><th>");	echo("Edition");
			echo("</th><th>");	echo("Year");
			echo("</th><th>");	echo("Category");
		echo("</tr>\n");
				
		//select the books reserved by this user
		$result = mysql_query("SELECT books.isbn, books.bookTitle, books.author, books.edition, books.year, books.category FROM books 
							   INNER JOIN reserved ON books.isbn=reserved.isbn WHERE reserved.username = '$username';"); 
		$numOfRows = mysql_num_rows($result);
		//if the user has not reserved any books
		if($numOfRows == 0)
		{
			echo "You have no books reserved";
		}
		else
		{
			//get the books the user reserved
			$query = mysql_query("SELECT books.isbn, books.bookTitle, books.author, books.edition, books.year, books.category FROM books 
										INNER JOIN reserved ON books.isbn=reserved.isbn WHERE reserved.username = '$username';");
			$numOfRows = mysql_num_rows($query);
			
			$perpage = 5;
			if($numOfRows == 5)
			{
				$last_amount = 5;
			}
			else
			{
				$last_amount = $numOfRows % $perpage;
			}
			//get page number or start at 1
			$page = isset($_GET['page']) ? $_GET['page'] : 1;
			$pages_count = ceil($numOfRows / $perpage);

			$is_first = $page == 1;
			$is_last  = $page == $pages_count;
			$prev = max(1, $page - 1);
			$next = min($pages_count , $page + 1);

			if($pages_count > 0) 
			{
				if(!$is_first) 
				{
					//previous page link
					echo '<a href="reserved.php?page='.$prev.'">Previous</a>&nbsp&nbsp';
				}

				if(!$is_last)
				{
					//next page link
					echo '<a href="reserved.php?page='.$next.'">Next</a>';
				}
				if($page == $pages_count)
				{
					$perpage = $last_amount;
				}
				
				//limit the number of results to the number of results perpage
				$query = "SELECT books.isbn, books.bookTitle, books.author, books.edition, books.year, books.category FROM books 
												   INNER JOIN reserved ON books.isbn=reserved.isbn WHERE reserved.username = '$username' 
												   LIMIT ".(int)(5 * ($page - 1)).", ".(int)$perpage;

				$result = mysql_query($query);

				$i = 1;
				//display the books columns
				while ( $row = mysql_fetch_row($result) )
				{
					echo("<form method = 'post'>");
					echo "<tr><td>";	echo($row[0]);	$_SESSION['isbn'.$i] = $row[0];
					echo("</td><td>");	echo($row[1]);
					echo("</td><td>");	echo($row[2]);
					echo("</td><td>");	echo($row[3]);
					echo("</td><td>");	echo($row[4]);
					echo("</td><td>");	echo($row[5]);
					echo("</td><td>");	echo("<input type = 'submit' value = 'Cancel' name = 'cancel".$i."'>");	//cancel reservation button
					echo("</tr>\n");
					echo("</form>");
					$i = $i + 1;
				}
			}
		}
	?>
	<?php
		$isbn = 0;
		//check which cancellation button was pressed and store the isbn of that book
		if(isset($_POST['cancel1']))
		{
			$isbn = $_SESSION['isbn1'];
		}
		if(isset($_POST['cancel2']))
		{
			$isbn = $_SESSION['isbn2'];
		}
		if(isset($_POST['cancel3']))
		{
			$isbn = $_SESSION['isbn3'];
		}
		if(isset($_POST['cancel4']))
		{
			$isbn = $_SESSION['isbn4'];
		}
		if(isset($_POST['cancel5']))
		{
			$isbn = $_SESSION['isbn5'];
		}
		if($isbn != 0)
		{
			//update the books table so the book with that isbn is no longer reserved
			$update = "UPDATE books SET reserved = 'N' WHERE isbn = '$isbn';";
			mysql_query($update);
				
			//delete that reservation from the reserved table
			$rm = "DELETE FROM reserved WHERE isbn = '$isbn'";
			mysql_query($rm);
			
			//return back to the reserved books page
			header("location: reserved.php");
		}
	?>
</body>
</html>