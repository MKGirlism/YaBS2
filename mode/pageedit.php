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
<h1>Edit</h1>
<?php
// MySQL
$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);

// Load ID
$aid = (int) $_GET['id'];

if ($_POST['Submit']) {
	$nameE = $mysqli->real_escape_string($_POST['Name']);
	$contE = $mysqli->real_escape_string($_POST['Content']);
	$prE = $_POST['Privacy'];

	$update = "UPDATE Pages SET Title='$nameE', Content='$contE', Privacy=$prE WHERE id=".$aid;
	$result = $mysqli->query($update);

	if ($result) {
        	$result->close();
        	$mysqli->close();
	}

	echo "<h2>Edit Page</h2>";
	echo "Done. <a href='index.php'>Return</a>.</div>";
}

else {
	$sql = "SELECT * FROM Pages WHERE id=".$aid;
	
	$stmt = $mysqli->prepare($sql);
	$stmt->execute();

	$stmt->bind_result($id, $title, $body, $priv);
	
	while ($stmt->fetch()) {
		echo "<script type='text/javascript' src='module/codebutton.js'></script>";
		echo "<h2>Edit Page</h2>";
		echo "<a href='index.php'>Return</a><br /><br />";
		echo "<form action='?mode=pageedit&id=$aid' method='post'>
<div id='BlogTitle'><input type='text' name='Name' value='".$title."'></div><br />
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
echo "<textarea name='Content' id='message' rows='40' cols='160' wrap='hard'>".$body."</textarea><br />
<select name='Privacy'>
  <option value='0'>Public</option>
  <option value='1'>Hidden</option>
  <option value='2'>Private</option>
</select><br />
<input name='Submit' type='submit' value='Edit'></div>
</form><br /><br />";
	}

	$stmt->close();
	$mysqli->close();
}
?>
