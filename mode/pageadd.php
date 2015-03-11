<?php
// Ensure, that nobody, other than the Admin, can come here.
ob_start();
$powza = htmlentities($_SESSION['username']['group'], ENT_QUOTES, 'UTF-8');
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
	$cont = $_POST['Content'];
	$cont2 = $mysqli->real_escape_string(nl2br($cont));
	$pr = $_POST['Privacy'];

	// Put it in a MySQL Insert Query, and execute it!
	$insert = "INSERT INTO blg_pages (id, title, message, privacy) VALUES (NULL, '$name', '$cont2', $pr)";
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
	echo "<form action='?mode=pageadd' method='post'>
<div id='BlogTitle'><input type='text' name='Title'></div><br />
<div id='BlogBody'>";
include ("module/textbuttons.php");

echo "<textarea name='Content' id='message' rows='40' cols='160' wrap='hard'></textarea><br />
<select name='Privacy'>
  <option value='0'>Public</option>
  <option value='1'>Hidden</option>
  <option value='2'>Private</option>
</select><br />
<input name='Submit' type='submit' value='Add'></div>
</form><br /><br />";
}

// Close MySQL Connection.
$mysqli->close();
?>
