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
	
	$update = "UPDATE blg_blogs SET title = '$nameE', message = '$contE', privacy = $prE WHERE id=".$aid;
	$result = $mysqli->query($update);
	
	if ($result) {
        	$result->close();
        	$mysqli->close();
	}
	
	echo "<h2>Edit Post</h2>";
	echo "Done. <a href='index.php'>Return</a>.</div>";
}

else {
	$sql = "SELECT b.id, b.title, b.message, b.user_id, b.post_date, b.privacy, u.id, u.username, u.avatar FROM blg_blogs AS b, $userb AS u WHERE b.user_id = u.id AND b.id = ".$aid;
	
	$stmt = $mysqli->prepare($sql);
	$stmt->execute();
	
	$stmt->bind_result($bid, $btitle, $bbody, $buid, $bdate, $bpriv, $uuid, $uuname, $uava);
	
	while ($stmt->fetch()) {
		echo "<script type='text/javascript' src='module/codebutton.js'></script>";
		echo "<h2>Edit Post</h2>";
		echo "<a href='index.php'>Return</a><br /><br />";
		echo "<form action='?mode=postedit&id=$aid' method='post'>
<div id='BlogTitle'><input type='text' name='Name' value='".$btitle."'></div><br />
<div id='BlogData'>".$uuname." | ".$bdate."</div>
<div id='BlogBody'>";
include ("module/textbuttons.php");

echo "<textarea name='Content' id='message' rows='40' cols='160' wrap='hard'>".$bbody."</textarea><br />
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
