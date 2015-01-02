<?php
include("module/postfunctions.php");

require("config.php");
$admin = htmlentities($_SESSION['uname']['group'], ENT_QUOTES, 'UTF-8') == 1;

// MySQL
$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);

// Load Data
if (isset($_GET["page"])) $page = $_GET["page"]; else $page = 1;
$start_from = ($page-1) * 10;
$sql = "SELECT b.id, b.Name, b.Content, b.uid, b.Date, b.Privacy, u.uid, u.uname, u.ava FROM Blogs AS b, Users AS u WHERE b.uid = u.uid ORDER BY id DESC LIMIT $start_from, 10";
$stmt = $mysqli->prepare($sql);
$stmt->execute();

$stmt->bind_result($id, $title, $body, $uidB, $date, $priv, $uidU, $uname, $ava);

// Show this to the Admin.
if (htmlentities($_SESSION['uname']['group'], ENT_QUOTES, 'UTF-8') == 1) echo "<div id='AddButton' class='AddButton'><a href='?mode=postadd'>Add</a></div>";

// Fetch all Rows.
while ($stmt->fetch()) {
	if ($priv == 0 || $admin) {
		echo "<div id='BlogTitle'><a href='?mode=post&id=$id'>$title</a></div><br />
		<div id='BlogData'><img src='$ava' height=25 /> <a href='?mode=profile&uid=$uidU'>$uname</a> made this Post on ".date('d-m-Y G:i', $date);
		if (htmlentities($_SESSION['uname']['group'], ENT_QUOTES, 'UTF-8') == 1) echo "<div id='AddButton' class='AddButton'><a href='?mode=postedit&id=$id'>Edit</a> | <a href='?mode=postdel&id=$id'>Delete</a></div>";
		
		echo "</div><div id='BlogBody'>".ReadMore(getBBCode(getSmile($body)))."... (<a href='?mode=post&id=$id'>Read More</a>)</div><br /><br />";
	}
}

$row = mysqli_fetch_row(mysqli_query($mysqli,"SELECT COUNT(Name) FROM Blogs"));
$total_records = $row[0];
$total_pages = ceil($total_records / 10);
for ($i = 1; $i <= $total_pages; $i++)
	echo "<a href='?page=".$i."'>".$i."</a>";

$stmt->close();
$mysqli->close();
?>
