<?php
// MySQL
$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);

$aid = (int) $_GET['id'];
$powza = htmlentities($_SESSION['username']['group'], ENT_QUOTES, 'UTF-8');

$uid = htmlentities($_SESSION['username']['id'], ENT_QUOTES, 'UTF-8');
$select = "SELECT user_id FROM blg_comments WHERE user_id = ".$uid;
$results = $mysqli->query($select);

include("module/postfunctions.php");
if ($powza <= 0 || $uid != $_SESSION['username']['id']) {
	if ($results) {
		$results->close();
        }
	header('Location: index.php');
        exit();
}
else {
	if ($results) {
                $results->close();
        }
	if ($_POST['Submit']) {
		$cont2 = clear($_POST['Content']);
	       	$le = mktime();
		
       		$update = "UPDATE blg_comments SET message = '$cont2', last_edit = $le WHERE id = ".$aid;
       		$result = $mysqli->query($update);
		
       		if ($result) {
       			$mysqli->close();
       		}
		echo "<h2>Edit Comment</h2>";
		echo "Done. <a href='index.php'>Return</a>.</div>";
	}
	else {
		$sql = "SELECT id, message FROM blg_comments WHERE id = ".$aid;
		
		$stmt = $mysqli->prepare($sql);
		$stmt->execute();

		$stmt->bind_result($cid, $cmsg);
		
		while($stmt->fetch()) {
			echo "<script type='text/javascript' src='module/codebutton.js'></script>";
			echo "<h2>Edit Comment</h2>";
			echo "<a href='index.php'>Return</a><br /><br />";
			echo "<form action='?mode=commentedit&id=$aid' method='post'>
<div id='BlogBody'>";
include ("module/textbuttons.php");

echo "<textarea name='Content' id='message' rows='20' cols='160' wrap='hard'>".$cmsg."</textarea><br />
<input name='Submit' type='submit' value='Edit'></div>
</form><br /><br />";
		}
		
		$stmt->close();
		$mysqli->close();
	}
}
?>
