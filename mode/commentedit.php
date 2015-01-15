<?php
// MySQL
$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);

$aid = (int) $_GET['id'];
$powza = htmlentities($_SESSION['uname']['group'], ENT_QUOTES, 'UTF-8');

$uid = htmlentities($_SESSION['uname']['uid'], ENT_QUOTES, 'UTF-8');
$select = "SELECT uid FROM Comments WHERE uid = ".$uid;
$results = $mysqli->query($select);

include("module/postfunctions.php");
if ($powza <= 0 || $uid != $_SESSION['uname']['uid']) {
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
		
       		$update = "UPDATE Comments SET Message='$cont2', lastedit=$le WHERE id=".$aid;
       		$result = $mysqli->query($update);
		
       		if ($result) {
       			$mysqli->close();
       		}
		echo "<h2>Edit Comment</h2>";
		echo "Done. <a href='index.php'>Return</a>.</div>";
	}
	else {
		$sql = "SELECT id, Message FROM Comments WHERE id = ".$aid;
		
		$stmt = $mysqli->prepare($sql);
		$stmt->execute();

		$stmt->bind_result($id, $msg);
		
		while($stmt->fetch()) {
			echo "<script type='text/javascript' src='module/codebutton.js'></script>";
			echo "<h2>Edit Comment</h2>";
			echo "<a href='index.php'>Return</a><br /><br />";
			echo "<form action='?mode=commentedit&id=$aid' method='post'>
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
echo "<textarea name='Content' id='message' rows='20' cols='160' wrap='hard'>".$msg."</textarea><br />
<input name='Submit' type='submit' value='Edit'></div>
</form><br /><br />";
		}
		
		$stmt->close();
		$mysqli->close();
	}
}
?>
