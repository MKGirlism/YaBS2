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
<h1>Delete</h1>
<?php
require("config.php");

// Initialise MySQL Connection.
$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);

// Load ID
$aid = (int) $_GET['id'];

echo "<a href='index.php'>Return</a><br /><br />";

// Execute after clicking "Submit".
if ($_POST['Yes']) {
	$update = "DELETE FROM Blogs WHERE id=".$aid;
	$result = $mysqli->query($update);

	if ($result) {
        $result->close();
        $mysqli->close();
	}

	echo "<h2>Delete Post</h2>";
	echo "Done. <a href='index.php'>Return</a>.</div>";
}

else if ($_POST['No']) {
	echo "<h2>Delete Post</h2>";
	echo "Cancelled. <a href='index.php'>Return</a>.</div>";
}

else {
	echo "<h2>Delete Post</h2>";
	echo "<form action='?mode=pagedel&id=$aid' method='post'>";
	echo "Are you sure?<br />";
	echo "<input name='Yes' type='submit' value='Yes'> <input name='No' type='submit' value='No'></div>";
	echo "</form><br /><br />";
}

// Close MySQL Connection.
$mysqli->close();
?>
