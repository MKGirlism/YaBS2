<?php
require("config.php");
$admin = htmlentities($_SESSION['uname']['group'], ENT_QUOTES, 'UTF-8') == 1;

//MySQL
$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);

//Load data
$aid = (int) $_GET['id'];
$sql = "SELECT b.id, b.Name, b.Content, b.uid, b.Date, b.Privacy, u.uid, u.uname, u.ava FROM Blogs AS b, Users AS u WHERE b.uid = u.uid AND id=".$aid;

$stmt = $mysqli->prepare($sql);
$stmt->execute();

$stmt->bind_result($id, $title, $body, $uidB, $date, $priv, $uidU, $uname, $ava);

include("module/postfunctions.php");
while ($stmt->fetch()) {
	if ($priv <= 1 || $admin) {
		if (htmlentities($_SESSION['uname']['group'], ENT_QUOTES, 'UTF-8') == 1) echo "<div id='AddButton' class='AddButton'><a href='?mode=postedit&id=$id'>Edit</a> | <a href='?mode=postdel&id=$id'>Delete</a></div>";
    	echo "<div id='BlogTitle'><a href='?mode=post&id=$id'>$title</a></div><br /><div id='BlogData'><img src='$ava' height=25 /> <a href='?mode=profile&uid=$uidU'>$uname</a> made this Post on ".date('d-m-Y G:i', $date)."</div><div id='BlogBody'>".getSmile(getBBCode($body));
		include("module/blogcomments.php");
		echo "</div><br /><br />";
	}
}

$stmt->close();
$mysqli->close();
?>
