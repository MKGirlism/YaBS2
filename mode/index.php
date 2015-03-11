<?php
include("module/postfunctions.php");

$admin = htmlentities($_SESSION['username']['group'], ENT_QUOTES, 'UTF-8') >= 1;

// MySQL
$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);

// Load Data
if (isset($_GET["page"])) $page = $_GET["page"]; else $page = 1;
$start_from = ($page-1) * 10;
$sql = "SELECT b.id, b.title, b.message, b.user_id, b.post_date, b.privacy, u.id, u.username, u.avatar FROM blg_blogs AS b, $userb AS u WHERE b.user_id = u.id ORDER BY b.post_date DESC LIMIT $start_from, 10";
$stmt = $mysqli->prepare($sql);
$stmt->execute();

$stmt->bind_result($bid, $btitle, $bbody, $buid, $bdate, $bpriv, $uuid, $uuname, $uava);

// Show this to the Admin.
if (htmlentities($_SESSION['username']['group'], ENT_QUOTES, 'UTF-8') >= 1) echo "<div id='AddButton' class='AddButton'><a href='?mode=postadd'>Add</a></div>";

// Fetch all Rows.
while ($stmt->fetch()) {
	if ($bpriv == 0 || $admin) {
		echo "<div id='BlogTitle'><a href='?mode=post&id=$bid'>$btitle</a></div><br />
		<div id='BlogData'><img src='$uava' height=25 /> <a href='?mode=profile&uid=$uuid'>$uuname</a> made this Post on ".date('d-m-Y G:i', $bdate);
		if (htmlentities($_SESSION['username']['group'], ENT_QUOTES, 'UTF-8') >= 1) echo "<div id='AddButton' class='AddButton'><a href='?mode=postedit&id=$bid'>Edit</a> | <a href='?mode=postdel&id=$bid'>Delete</a></div>";
		
		echo "</div><div id='BlogBody'>".ReadMore(getBBCode(getSmile($bbody)))."... (<a href='?mode=post&id=$bid'>Read More</a>)</div><br /><br />";
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
