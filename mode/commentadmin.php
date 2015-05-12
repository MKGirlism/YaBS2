<?php
include("module/postfunctions.php");
ob_start();
$powza = htmlentities($_SESSION['username']['group'], ENT_QUOTES, 'UTF-8');
if ($powza <= 1)
{
        header('Location: index.php');
        exit();
}
ob_end_clean();

// MySQL
$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);

$postid = (int) $_GET['id'];

// Load data
if (isset($_GET["page"])) $page = $_GET["page"]; else $page = 1;
$start_from = ($page-1) * 10;
$sql = "SELECT c.id, c.post_id, c.message, c.user_id, c.post_date, c.delete, c.last_edit, c.ip_address, u.id, u.username, u.group, u.avatar FROM blg_comments AS c, $userb AS u WHERE u.id = c.user_id ORDER BY c.id DESC LIMIT $start_from, 10";
$stmt = $mysqli->prepare($sql);
$stmt->execute();

$stmt->bind_result($cid, $cpid, $cmessage, $cuid, $cdate, $cdel, $cle, $cip, $uuid, $uuname, $ugroup, $uava);

$ts1 = GetAll($message);
?>
<hr />
<h1><?php echo "Comments"; ?></h1>
<table border="0" cellpadding="2" width="100%">
<?php

$owner = htmlentities($_SESSION['username']['username'], ENT_QUOTES, 'UTF-8');
while ($stmt->fetch()) {
	echo "<tr><td>";
	if (!empty($uava)) echo "<img src='$uava' alt='$uuname\'s Avatar' height=50 /> ";
	echo "<strong style='font-size: 20px;'><a href='?mode=profile&uid=$uuid'>$uuname</a>";
	if ($cdel == 0) {
		if ($admin || $owner == $uuname) echo " | <a href='?mode=commentedit&id=$cid'>Edit</a>";
		if ($admin) echo " | <a href='?mode=commentdel&id=$cid'>Remove</a>";
		if ($admin || $owner == $uuname) echo "</div>";
	}
	else {
		if ($admin) echo "<div id='AddButton' class='AddButton'><a href='?mode=commentdel&id=$cid'>Unremove</a></div>";
	}
	
	if ($admin || $owner == $uuname) echo " ($cip)";
	echo " <a href='?mode=post&id=$cpid'>Made this Comment on</a> ".date('d-m-Y, G:i', $cdate);
	if ($cdel == 0) {
		echo "<tr><td valign='top' height=90px>".getSmile(getBBCode($cmessage));
		if ($cle != 0) echo "<br /><br /><i>Last Edited: ".date('d-m-Y, G:i', $cle)."</i>";
	}
	else {
		echo "</strong></td></tr>";
		echo "<tr><td valign='top' height=90px><i>-removed-</i>";
	}
	echo "</td></tr>";
}
echo "</table>";
?>
