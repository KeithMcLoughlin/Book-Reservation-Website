<html>
<head>
	<title>Search Results</title>
	<link rel="stylesheet" type="text/css" href="library.css" />
</head>
<body>
<?php
	//connect to database
	$db = mysql_connect('localhost', 'root', '') or die(mysql_error());
	if($db == FALSE) die('Fail message');
?>
	<!--Title at the top of page-->
	<div class = "head">Search Results</div>
	<!--Navigation Bar-->
	<ul><li><a class="nav" href="search.php">Search For Book</a></li>
		<li><a class="nav" href="reserved.php">My Reserved Books</a></li>
	</ul>
	<?php
		mysql_select_db("librarydb") or die(mysql_error());
		session_start();
		//the titles of each column 
		echo '<table>'."\n";
			echo "<tr><th>";	echo("ISBN");
			echo("</th><th>");	echo("Title");
			echo("</th><th>");	echo("Author");
			echo("</th><th>");	echo("Edition");
			echo("</th><th>");	echo("Year");
			echo("</th><th>");	echo("Category");
			echo("</th><th>");	echo("Reserve");
		echo("</tr>\n");
		
		//checks if title was only entered
		if(isset($_POST['title']) && empty($_POST['author']) && !empty($_POST['title']))
		{
			//clear author variable from session so the author wont carry over
			//from the last search
			unset($_SESSION['author']);
			$title = $_POST['title'];
			//select the books which have this title anywhere in their title
			$query = "SELECT * FROM books WHERE bookTitle LIKE '%$title%'";
			//store the query and title in the session
			$_SESSION['query'] = $query;
			$_SESSION['title'] = $title;
		}
		//checks if author was only entered
		if(isset($_POST['author']) && !empty($_POST['author']) && empty($_POST['title']))
		{
			//clear title variable from session so the title wont carry over
			//from the last search
			unset($_SESSION['title']);
			$author = $_POST['author'];
			//select the books which have this author
			$query = "SELECT * FROM books WHERE author LIKE '%$author%'";
			//store the query and author in the session
			$_SESSION['query'] = $query;
			$_SESSION['author'] = $author;
		}
		//check if both author and title were entered
		if(!empty($_POST['author']) && !empty($_POST['title']))
		{
			$title = $_POST['title'];
			$author = $_POST['author'];
			//select the books that have this title and author
			$query = "SELECT * FROM books WHERE bookTitle LIKE '%$title%' AND author LIKE '%$author%'";
			//store the query, author and title in the session
			$_SESSION['query'] = $query;
			$_SESSION['title'] = $title;
			$_SESSION['author'] = $author;
		}
		if(isset($_POST['category']))
		{
			//clear both title and author so it wont search for the previous ones aswell
			unset($_SESSION['title']);
			unset($_SESSION['author']);
			$cat = $_POST['category'];
			//select the books from this category
			$query = "SELECT * FROM books WHERE category = $cat";
			//store the query and category in the session
			$_SESSION['query'] = $query;
			$_SESSION['category'] = $cat;
		}
		//check if both title and author are entered but are blank
		if(isset($_POST['title']) && isset($_POST['author']) && empty($_POST['author']) && empty($_POST['title']))
		{
			$title = ' ';
			$author = ' ';
			//selects all books
			$query = "SELECT * FROM books WHERE bookTitle LIKE '%$title%' AND author LIKE '%$author%'";
			//stores the variables in the session
			$_SESSION['query'] = $query;
			$_SESSION['title'] = $title;
			$_SESSION['author'] = $author;
		}

		$query = $_SESSION['query'];
		//run the query stored in the session
		$query = mysql_query($query);
		//check how many results there are
		$numOfRows = mysql_num_rows($query);
		//the number of results to be display on each page
		$perpage = 5;
		
		if($numOfRows == 5)
		{
			$last_amount = 5;
		}
		else
		{
			//the number of results that will be displayed on the last page
			$last_amount = $numOfRows % $perpage;
		}
		//check what page we are on and starts on page 1
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		//number of pages we will have
		$pages_count = ceil($numOfRows / $perpage);

		$is_first = $page == 1;
		$is_last  = $page == $pages_count;
		$prev = max(1, $page - 1);
		$next = min($pages_count , $page + 1);

		if($pages_count > 0) 
		{
			if(!$is_first) 
			{
				//if we are not on the first page, display a previous page link
				echo '<a href="results.php?page='.$prev.'">Previous</a>&nbsp&nbsp';
			}

			if(!$is_last)
			{
				//if we are not on the last page, display a next page link
				echo '<a href="results.php?page='.$next.'">Next</a>';
			}
			
			if($page == $pages_count)
			{
				$perpage = $last_amount;
			}
			
			//check if title was only entered
			if (!empty($_SESSION['title']) && empty($_POST['author']) && $_SESSION['title'] != ' ')
			{
				if(!empty($_SESSION['title']))
				{
					$i = 1;
					$title = $_SESSION['title'];
					$result = mysql_query("SELECT * FROM books WHERE bookTitle LIKE '%$title%' LIMIT ".(int)(5 * ($page - 1)).", ".(int)$perpage);
					while ( $row = mysql_fetch_row($result) )
					{
						//display each column of each result
						echo("<form method = 'post' action = 'reserve.php'>");
						echo "<tr><td>";	echo($row[0]);	$_SESSION['isbn'.$i] = $row[0];
						echo("</td><td>");	echo($row[1]);	$_SESSION['title'.$i] = $row[1];
						echo("</td><td>");	echo($row[2]);	$_SESSION['author'.$i] = $row[2];
						echo("</td><td>");	echo($row[3]);	$_SESSION['edition'.$i] = $row[3];
						echo("</td><td>");	echo($row[4]);	$_SESSION['year'.$i] = $row[4];
						echo("</td><td>");	echo($row[5]);	$_SESSION['category'.$i] = $row[5];
						echo("</td><td>");	if($row[6] == 'N')
											//reserve button
											echo("<input type = 'submit' value = 'Reserve' name = 'button".$i."'>");
											else echo("Reserved!");
						echo("</tr>\n");
						echo("</form>");
						$i = $i + 1;
					}
					$numOfRows = mysql_num_rows($result);
					//if there is no search results
					if($numOfRows == 0)
					{
						echo "No search results for '$title'";
					}
				}
			}
			//check if author was only entered
			if (empty($_POST['title']) && !empty($_SESSION['author']) && $_SESSION['author'] != ' ')
			{
				if(!empty($_SESSION['author']))
				{
					$i = 1;
					$author = $_SESSION['author'];
					$result = mysql_query("SELECT * FROM books WHERE author LIKE '%$author%' LIMIT ".(int)(5 * ($page - 1)).", ".(int)$perpage); 
					while ( $row = mysql_fetch_row($result) )
					{
						echo("<form method = 'post' action = 'reserve.php'>");
						echo "<tr><td>";	echo($row[0]);	$_SESSION['isbn'.$i] = $row[0];
						echo("</td><td>");	echo($row[1]);	$_SESSION['title'.$i] = $row[1];
						echo("</td><td>");	echo($row[2]);	$_SESSION['author'.$i] = $row[2];
						echo("</td><td>");	echo($row[3]);	$_SESSION['edition'.$i] = $row[3];
						echo("</td><td>");	echo($row[4]);	$_SESSION['year'.$i] = $row[4];
						echo("</td><td>");	echo($row[5]);	$_SESSION['category'.$i] = $row[5];
						echo("</td><td>");	if($row[6] == 'N')
											//reserve button
											echo("<input type = 'submit' value = 'Reserve' name = 'button".$i."'>");
											else echo("Reserved!");
						echo("</tr>\n");
						echo("</form>");
						$i = $i + 1;
					}
					$numOfRows = mysql_num_rows($result);
					//if no search results
					if($numOfRows == 0)
					{
						echo "No search results for '$author'";
					}
				}
			}
			//check if both title and author were entered
			if (!empty($_SESSION['author']) && !empty($_SESSION['title']))
			{
				$i = 1;
				$title = $_SESSION['title'];
				$author = $_SESSION['author'];
				$result = mysql_query("SELECT * FROM books WHERE bookTitle LIKE '%$title%' AND author LIKE '%$author%' LIMIT ".(int)(5 * ($page - 1)).", ".(int)$perpage); 
				while ( $row = mysql_fetch_row($result) )
				{
					echo("<form method = 'post' action = 'reserve.php'>");
					echo "<tr><td>";	echo($row[0]);	$_SESSION['isbn'.$i] = $row[0];
					echo("</td><td>");	echo($row[1]);	$_SESSION['title'.$i] = $row[1];
					echo("</td><td>");	echo($row[2]);	$_SESSION['author'.$i] = $row[2];
					echo("</td><td>");	echo($row[3]);	$_SESSION['edition'.$i] = $row[3];
					echo("</td><td>");	echo($row[4]);	$_SESSION['year'.$i] = $row[4];
					echo("</td><td>");	echo($row[5]);	$_SESSION['category'.$i] = $row[5];
					echo("</td><td>");	if($row[6] == 'N')
										//reserve button
										echo("<input type = 'submit' value = 'Reserve' name = 'button".$i."'>");
										else echo("Reserved!");
					echo("</tr>\n");
					echo("</form>");
					$i = $i + 1;
				}
				$numOfRows = mysql_num_rows($result);
				//if there are no search results
				if($numOfRows == 0)
				{
					echo "No search results for '$title' by '$author'";
				}
			}
			//check if category was entered
			if (isset($_POST['category']))
			{
				$i = 1;
				$cat = $_POST['category'];
				$result = mysql_query("SELECT * FROM books WHERE category = $cat LIMIT ".(int)(5 * ($page - 1)).", ".(int)$perpage); 
				while ( $row = mysql_fetch_row($result) )
				{
					echo("<form method = 'post' action = 'reserve.php'>");
					echo "<tr><td>";	echo($row[0]);	$_SESSION['isbn'.$i] = $row[0];
					echo("</td><td>");	echo($row[1]);	$_SESSION['title'.$i] = $row[1];
					echo("</td><td>");	echo($row[2]);	$_SESSION['author'.$i] = $row[2];
					echo("</td><td>");	echo($row[3]);	$_SESSION['edition'.$i] = $row[3];
					echo("</td><td>");	echo($row[4]);	$_SESSION['year'.$i] = $row[4];
					echo("</td><td>");	echo($row[5]);	$_SESSION['category'.$i] = $row[5];
					echo("</td><td>");	if($row[6] == 'N')
										//reserve button
										echo("<input type = 'submit' value = 'Reserve' name = 'button".$i."'>");
										else echo("Reserved!");
					echo("</tr>\n");
					echo("</form>");
					$i = $i + 1;
				}
				$numOfRows = mysql_num_rows($result);
				//if no search results
				if($numOfRows == 0)
				{
					echo "No search results for this category";
				}
			}
			echo "</table>\n";
		}
		else
		{
			echo "No search results!";
		}
	?>
</body>
</html>