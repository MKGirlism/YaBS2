<?php
include("config.php");
header("Content-Type: text/html; charset=utf-8");
session_start();
?>
<!DOCTYPE HTML>
<html>
<head>
	<?php include("module/meta.php"); ?>
</head>
<body>

<?php
$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);
$bloguse = "SELECT BlogPurpose FROM General";

$usage = $mysqli->prepare($bloguse);
$usage->execute();
$usage->bind_result($bp);

while ($usage->fetch()) $blogpur = $bp;

$usage->close();
$mysqli->close();
?>

<div id='Container'>
	<?php $curPage = $_GET['mode']; ?>
	<div id='Logo'> <?php include("module/head.php"); ?> </div>
	<div id='Links'> <?php include("module/topbar.php"); ?> </div>
	
	<div id='User'> <?php include("module/menu.php"); ?> </div>
	
	<div id='Main'>
	<?php
		if (empty($curPage)) {
			if ($blogpur == 1)	include("mode/index.php");
			else			include("mode/page.php");
		}
		else {
			include("mode/".$curPage.".php");
		}
	?>
	</div>

	<?php include("module/foot.php"); ?>
</div>

</body>
</html>
