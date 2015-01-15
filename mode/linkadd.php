<?php
// Ensure, that nobody, other than the Admin, can come here.
ob_start();
$powza = htmlentities($_SESSION['uname']['group'], ENT_QUOTES, 'UTF-8');
if ($powza <= 0)
{
        header('Location: index.php');
        exit();
}
ob_end_clean();
?>
<h1>Add</h1>
<?php
// Initialise MySQL Connection.
$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);

// Execute after clicking "Submit".
if ($_POST['Submit']) {
	// Escape the Strings in the Title, and Content, otherwise, you'll get into trouble.
	// Also, automatically add Enters, in the Content.
	$name = $mysqli->real_escape_string($_POST['Title']);
	$url = $mysqli->real_escape_string($_POST['URL']);

	// Put it in a MySQL Insert Query, and execute it!
	$insert = "INSERT INTO Links (id, Title, URL) VALUES (NULL, '$name', '$url')";
	$result = $mysqli->query($insert);

	// Make sure you ALWAYS close the Query, and go to the next one.
	if ($result) {
		$result->close();
		$mysqli->next_result();
	}

	echo "<h2>Add Page</h2>";
	echo "Done. <a href='index.php'>Return</a>.</div>";
}

else {
	echo "<script type='text/javascript' src='module/codebutton.js'></script>";
	echo "<h2>Add Page</h2>";
	echo "<form action='?mode=linkadd' method='post'>
<div id='BlogBody'>
Title: <input type='text' name='Title'><br />
URL: <input type='text' name='URL'><br />
<input name='Submit' type='submit' value='Add'></div>
</form><br /><br />";
}

// Close MySQL Connection.
$mysqli->close();
?>
