<?php
//require("config.php");
$admin = htmlentities($_SESSION['username']['group'], ENT_QUOTES, 'UTF-8') >= 1;

// MySQL
$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);
$u = $_SESSION['username']['username'];
$i = $_SESSION['username']['id'];

// Load Data
//$sql = "SELECT * FROM Pages ORDER BY Title ASC";
$sql = "SELECT p.id, p.title, p.message, p.privacy, g.blog_purpose, g.homepage FROM blg_pages AS p, blg_generic AS g ORDER BY p.title ASC";
$link = "SELECT * FROM blg_links ORDER BY title ASC";

$stmt = $mysqli->prepare($sql);
$stmt->execute();

$stmt->bind_result($pid, $ptitle, $pbody, $ppriv, $gblopur, $ghome);

//Start Menu.
echo "<table id='Nav' class='outline margin'><tr class='NavHead1'><th>User</th></tr>";
echo "<tr class='NavCell0'><td>";

echo "<ul id='userpanel' class='stackedmenu'><li>";
// Greet the User, if Logged in, otherwise, greet the Guest.
if (!empty($_SESSION['username'])) {
        echo "<a href='?mode=profile&uid=$i'>".htmlentities($u, ENT_QUOTES, 'UTF-8')."</a>";
}
else {
        echo "<a href='#'>Guest</a>";
}
echo "</li>";

// Show if Logged In.
if (!empty($_SESSION['username'])) {
	echo "<li><a href='?mode=uploader'>Uploader</a></li>";
	if ($admin) {
		echo "<li><a href='?mode=meta'>Edit Site Information</a>";
		echo "<li><a href='?mode=memlst'>User List</a>";
	}
	echo "<li><a href='logout.php'>Logout</a></li>";
}

// Otherwise, show this.
else {
	echo "<li><a href='login.php'>Login</a></li>";
	echo "<li><a href='register.php'>Register</a></li>";
}
echo "</ul></td></tr>";

echo "<tr class='NavHead1'><th>Menu";
if ($admin) echo " | <a href='?mode=pageadd'>Add</a>";
echo "</th></tr>";
echo "<tr class='NavCell0'><td>";
echo "<ul id='userpanel' class='stackedmenu'>";

// Menu
$once = false;
echo "<li><a href='index.php'>Home</a></li>";
while ($stmt->fetch()) {
	if (!$once) {
		$once = true;
		if ($gblopur == 2) echo "<li><a href='?mode=index'>Blog</a></li>";
	}
        if ($ppriv <= 0 || $admin) if ($pid != $phome) echo "<li><a href='?mode=page&id=$pid'>$ptitle</a></li>";
}
echo "</ul></td></tr>";

$stmt->close();

$stmt2 = $mysqli->prepare($link);
$stmt2->execute();

$stmt2->bind_result($lid, $ltitle, $lurl);

echo "<tr class='NavHead1'><th>Links";
if ($admin) echo " | <a href='?mode=linkadd'>Add</a>";
echo "</th></tr>";
echo "<tr class='NavCell0'><td>";
echo "<ul id='userpanel' class='stackedmenu'>";

// Links
while ($stmt2->fetch()) {
	echo "<li><a href='$lurl'>$ltitle</a>";
	if ($admin) echo " <a href='?mode=linkdel&id=$lid'>X</a>";
	echo "</li>";
}

$stmt2->close();
$mysqli->close();

echo "</ul></td></tr></table>";
?>
