<?php
$id = (int) $_GET['uid'];
if ($id < 1)
{
        header('Location: index.php');
        exit();
}

$admin = htmlentities($_SESSION['username']['group'], ENT_QUOTES, 'UTF-8') >= 1;

//MySQL
$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);

//Load data
$aid = (int) $_GET['uid'];
$sql = "SELECT id, username, email, avatar FROM $userb WHERE id = ".$aid;

$stmt = $mysqli->prepare($sql);
$stmt->execute();

$stmt->bind_result($uuid, $uuname, $uemail, $uava);

$owner = htmlentities($_SESSION['username']['username'], ENT_QUOTES, 'UTF-8');

while ($stmt->fetch()) {
        if ($admin || $owner == $uuname) echo "<div id='AddButton' class='AddButton'><a href='?mode=editprofile&uid=$aid'>Edit</a></div>";
        echo "<img src='$uava' /><br />$uuname";
	if ($admin || $owner == $uuname) echo "<br />$uemail";
	echo "<br /><br />";
}

$stmt->close();
$mysqli->close();
?>
