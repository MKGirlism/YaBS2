<?php
require("config.php");
$admin = htmlentities($_SESSION['username']['group'], ENT_QUOTES, 'UTF-8') >= 1;

// MySQL
$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);

// Load data
if (isset($_GET["page"])) $page = $_GET["page"]; else $page = 1;
$start_from = ($page-1) * 10;
$sql = "SELECT c.id, c.post_id, c.message, c.user_id, c.post_date, c.delete, c.last_edit, c.ip_address, u.id, u.username, u.avatar FROM blg_comments AS c, $userb AS u WHERE u.id = c.user_id AND c.post_id = $bid ORDER BY c.id ASC LIMIT $start_from, 10";
$stmt = $mysqli->prepare($sql);
$stmt->execute();

$stmt->bind_result($cid, $cpid, $cmessage, $cuid, $cdate, $cdel, $cle, $cip, $uuid, $uuname, $uava);

$ts1 = GetAll($message);
?>
<hr />
<h1>Comments</h1>
<table border="0" cellpadding="2" width="100%">
<?php

$owner = htmlentities($_SESSION['username']['username'], ENT_QUOTES, 'UTF-8');
while ($stmt->fetch()) {
	echo "<tr><td>";
	if (!empty($uava)) echo "<img src='$uava' alt='$uuname\'s Avatar' height=50 /> ";
	echo "<strong style='font-size: 20px;'><a href='profile.php?uid=$uuid'>$uuname</a>";
	if ($cdel == 0) {
		if ($admin || $owner == $uuname) echo "<div id='AddButton' class='AddButton'><a href='?mode=commentedit&id=$cid'>Edit</a>";
		if ($admin) echo " | <a href='?mode=commentdel&id=$cid'>Delete</a>";
		if ($admin || $owner == $uuname) echo "</div>";
	}
	else {
		if ($admin) echo "<div id='AddButton' class='AddButton'><a href='?mode=commentdel&id=$cid'>Undelete</a></div>";
	}
	
	if ($admin || $owner == $uuname) echo " ($cip)";
	echo " made this Comment on ".date('d-m-Y G:i', $cdate)."</strong></td></tr>";
	if ($cdel == 0) {
		echo "<tr><td valign='top' height=90px>".GetAll($cmessage);
		if ($cle != 0) echo "<br /><br /><i>Last Edited: ".date('d-m-Y G:i', $cle)."</i>";
	}
	else {
		echo "<tr><td valign='top' height=90px><i>-removed-</i>";
	}
	echo "</td></tr>";
}
echo "</table>";
if (!empty($_SESSION['username'])) {
?>
<form name="form1" method="post" action="?mode=commentpost&id=<? echo $bid; ?>">
Add Reply<br />
<br />
<script type="text/javascript" src="module/codebutton.js"></script>
<b>Message</b><br />
<?php include ("module/textbuttons.php"); ?>

<textarea name="message" id="message" cols="166" rows="10"></textarea><br />
<input type="submit" name="submit" id="submit" value="Comment" />
</form>
<?php
}
else {
	echo "<hr />";
	echo "You must be Logged In, to Comment.<br />";
}
?>
