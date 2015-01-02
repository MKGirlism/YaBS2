<?php
$id = (int) $_GET['uid'];
if ($id < 1)
{
        header('Location: index.php');
        exit();
}

require("config.php");
$admin = htmlentities($_SESSION['uname']['group'], ENT_QUOTES, 'UTF-8') == 1;

//MySQL
$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);

//Load data
$aid = (int) $_GET['uid'];
$sql = "SELECT uid, uname, email, ava FROM Users WHERE uid=".$aid;

$stmt = $mysqli->prepare($sql);
$stmt->execute();

$stmt->bind_result($uid, $uname, $email, $ava);

$owner = htmlentities($_SESSION['uname']['uname'], ENT_QUOTES, 'UTF-8');

while ($stmt->fetch()) {
        if ($admin || $owner == $uname) echo "<div id='AddButton' class='AddButton'><a href='?mode=editprofile&uid=$aid'>Edit</a></div>";
        echo "<img src='$ava' /><br />$uname";
	if ($admin || $owner == $uname) echo "<br />$email";
	echo "<br /><br />";
}

$stmt->close();
$mysqli->close();
?>
