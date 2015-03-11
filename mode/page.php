<?php
$admin = htmlentities($_SESSION['username']['group'], ENT_QUOTES, 'UTF-8') >= 1;

//MySQL
$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);

//Load data
$aid = (int) $_GET['id'];

if (empty($aid)) {
	$homepage = "SELECT homepage FROM blg_generic";
	$hopa = $mysqli->prepare($homepage);
	$hopa->execute();
	$hopa->bind_result($hp);
	while ($hopa->fetch()) $hoppa = $hp;
	$hopa->close();
}

if (empty($aid))	$sql = "SELECT * FROM blg_pages WHERE id = ".$hoppa;
else			$sql = "SELECT * FROM blg_pages WHERE id = ".$aid;

$stmt = $mysqli->prepare($sql);
$stmt->execute();

$stmt->bind_result($pid, $ptitle, $pbody, $ppriv);

include("module/postfunctions.php");
while ($stmt->fetch()) {
	if ($ppriv <= 1 || $admin) {
		if ($admin) echo "<div id='AddButton' class='AddButton'><a href='?mode=pageedit&id=$aid'>Edit</a> | <a href='?mode=pagedel&id=$aid'>Delete</a></div>";
    	echo "<div id='BlogTitle'>$ptitle</div><br /><div id='BlogBody'>".getBBCode(getSmile($pbody));
		echo "</div><br /><br />";
	}
}

$stmt->close();
$mysqli->close();
?>
