<?php
require("config.php");
$admin = htmlentities($_SESSION['uname']['group'], ENT_QUOTES, 'UTF-8') == 1;

// MySQL
$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);

// Load data
if (isset($_GET["page"])) $page = $_GET["page"]; else $page = 1;
$start_from = ($page-1) * 10;
$sql = "SELECT c.id, c.pid, c.message, c.uid, c.date, c.delete, c.lastedit, c.ipaddress, u.uid, u.uname, u.ava FROM Comments AS c, Users AS u WHERE u.uid = c.uid AND c.pid = $id ORDER BY c.id ASC LIMIT $start_from, 10";
$stmt = $mysqli->prepare($sql);
$stmt->execute();

$stmt->bind_result($cid, $pid, $message, $uidC, $date, $del, $le, $ip, $uidU, $uname, $ava);

$ts1 = GetAll($message);
?>
<hr />
<h1>Comments</h1>
<table border="0" cellpadding="2" width="100%">
<?php

$owner = htmlentities($_SESSION['uname']['uname'], ENT_QUOTES, 'UTF-8');
while ($stmt->fetch()) {
	echo "<tr><td><img src='$ava' alt='$uname\'s Avatar' height=50 /> <strong style='font-size: 20px;'><a href='profile.php?uid=$uidU'>$uname</a>";
	if ($del == 0) {
		if ($admin || $owner == $uname) echo "<div id='AddButton' class='AddButton'><a href='?mode=commentedit&id=$cid'>Edit</a>";
		if ($admin) echo " | <a href='?mode=commentdel&id=$cid'>Delete</a>";
		if ($admin || $owner == $uname) echo "</div>";
	}
	else {
		if ($admin) echo "<div id='AddButton' class='AddButton'><a href='?mode=commentdel&id=$cid'>Undelete</a></div>";
	}
	
	if ($admin || $owner == $uname) echo " ($ip)";
	echo " made this Comment on ".date('d-m-Y G:i', $date)."</strong></td></tr>";
	if ($del == 0) {
		echo "<tr><td valign='top' height=90px>".GetAll($message);
		if ($le != 0) echo "<br /><br /><i>Last Edited: ".date('d-m-Y G:i', $le)."</i>";
	}
	else {
		echo "<tr><td valign='top' height=90px><i>-removed-</i>";
	}
	echo "</td></tr>";
}
echo "</table>";
if (!empty($_SESSION['uname'])) {
?>
<form name="form1" method="post" action="?mode=commentpost&id=<? echo $id; ?>">
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
