<html>
<head>
	<title>Search Page</title>
	<link rel="stylesheet" type="text/css" href="library.css" />
</head>
<body>
<?php
	//connect to database
	$db = mysql_connect('localhost', 'root', '') or die(mysql_error());
	if($db == FALSE) die('Fail message');
?>
	<!--Title at the top of page-->
	<div class = "head">Search Page</div>
	<!--Navigation Bar-->
	<ul><li><a class="nav" href="search.php">Search For Book</a></li>
		<li><a class="nav" href="reserved.php">My Reserved Books</a></li>
	</ul>
	<!--Search form for title or author or both-->
	<form name = "searchAuthorOrTitle" method = "post" action = "results.php">
		<input name = "title" size = "50" maxlength = "50" placeholder = "search by book title" />
		<input name = "author" size = "50" maxlength = "50" placeholder = "search by author" />
		</br>
		<input type = "submit" value = "Search">
	</form>
	<!--Search form for category-->
	<form name = "searchCategory" method = "post" action = "results.php">
		<select name = "category">
			<?php
				mysql_select_db("librarydb") or die(mysql_error());
				//retrieve all the categories
				$query = mysql_query("SELECT * FROM category");
				while ( $row = mysql_fetch_array($query) )
				{
					//make each value of the drop down menu the category names
					echo "<option value = \"" . $row['categoryID'] . "\">" . $row['categoryDes'] . "</option>";
				}
			?>
		</select>
		<input type = "submit" value = "Search">
	</form>
	</br>
</body>
<footer>
  <p><strong>Created by:</strong> Keith Mc Loughlin    <strong>Student No:</strong> C14337491</p>
</footer>
</html>