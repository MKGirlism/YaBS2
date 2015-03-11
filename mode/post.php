<?php
$admin = htmlentities($_SESSION['uname']['group'], ENT_QUOTES, 'UTF-8') >= 1;

//MySQL
$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);

//Load data
$aid = (int) $_GET['id'];
$sql = "SELECT b.id, b.title, b.message, b.user_id, b.post_date, b.privacy, u.id, u.username, u.avatar FROM blg_blogs AS b, $userb AS u WHERE b.user_id = u.id AND b.id = ".$aid;

$stmt = $mysqli->prepare($sql);
$stmt->execute();

$stmt->bind_result($bid, $btitle, $bbody, $buid, $bdate, $bpriv, $uuid, $uuname, $uava);

include("module/postfunctions.php");
while ($stmt->fetch()) {
	if ($bpriv <= 1 || $admin) {
		if (htmlentities($_SESSION['username']['group'], ENT_QUOTES, 'UTF-8') >= 1) echo "<div id='AddButton' class='AddButton'><a href='?mode=postedit&id=$bid'>Edit</a> | <a href='?mode=postdel&id=$bid'>Delete</a></div>";
    	echo "<div id='BlogTitle'><a href='?mode=post&id=$bid'>$btitle</a></div><br /><div id='BlogData'><img src='$uava' height=25 /> <a href='?mode=profile&uid=$uuid'>$uuname</a> made this Post on ".date('d-m-Y G:i', $bdate)."</div><div id='BlogBody'>".getSmile(getBBCode($bbody));
		include("module/blogcomments.php");
		echo "</div><br /><br />";
	}
}

$stmt->close();
$mysqli->close();
?>
