<?php
$admin = htmlentities($_SESSION['uname']['group'], ENT_QUOTES, 'UTF-8') == 1;

//MySQL
$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);

//Load data
$aid = (int) $_GET['id'];
$sql = "SELECT * FROM Pages WHERE id=".$aid;

$stmt = $mysqli->prepare($sql);
$stmt->execute();

$stmt->bind_result($id, $title, $body, $priv);

include("module/postfunctions.php");
while ($stmt->fetch()) {
	if ($priv <= 1 || $admin) {
		if ($admin) echo "<div id='AddButton' class='AddButton'><a href='?mode=pageedit&id=$aid'>Edit</a> | <a href='?mode=pagedel&id=$aid'>Delete</a></div>";
    	echo "<div id='BlogTitle'>$title</div><br /><div id='BlogBody'>".getBBCode(getSmile($body));
		echo "</div><br /><br />";
	}
}

$stmt->close();
$mysqli->close();
?>
