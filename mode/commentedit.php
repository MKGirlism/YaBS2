<?php
// MySQL
$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);

$aid = (int) $_GET['id'];
$admin = htmlentities($_SESSION['username']['group'], ENT_QUOTES, 'UTF-8') <= 0;
$owner = htmlentities($_SESSION['username']['username'], ENT_QUOTES, 'UTF-8');

$uid = htmlentities($_SESSION['username']['id'], ENT_QUOTES, 'UTF-8');
$select = $mysqli->prepare("SELECT u.id, c.user_id FROM $userb AS u, blg_comments AS c WHERE u.id = c.user_id AND c.id = ".$aid);

$select->execute();
$select->bind_result($uuid, $cuid);
$select->store_result();

$gotoelse = 0;
include("module/postfunctions.php");
while ($select->fetch()) {
	while (true) {
		if ($admin && $gotoelse == 0) {
			if ($uid != $cuid) {
				header('Location: index.php');
			        exit();
				break;
			}
			else {
				$gotoelse = 1;
				continue;
			}
		}
		else {
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
		break;
	}
}
}
?>
