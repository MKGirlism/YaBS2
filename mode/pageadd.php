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
require("config.php");

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
	$insert = "INSERT INTO Pages (id, Title, Content, Privacy) VALUES (NULL, '$name', '$cont2', $pr)";
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
?>
<input type="button" value="Bold" onclick="bbButton ('b');" />
<input type="button" value="Italics" onclick="bbButton ('i');" />
<input type="button" value="Underline" onclick="bbButton ('u');" />
<input type="button" value="Stripe" onclick="bbButton ('s');" />
<input type="button" value="Overline" onclick="bbButton ('o');" />
<input type="button" value="Image" onclick="bbButton ('img');" />
<input type="button" value="YouTube" onclick="bbButton ('yt');" />
<input type="button" value="Link" onclick="bbButton2 ('link');" />
<input type="button" value="Sound" onclick="bbButton ('sound');" />
<input type="button" value="Size" onclick="bbButton2 ('size');" />
<input type="button" value="Colour" onclick="bbButton2 ('colour');" />
<?php
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
