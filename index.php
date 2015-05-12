<?php
include("module/mobile.php");
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
$bloguse = "SELECT blog_purpose FROM blg_generic";

$usage = $mysqli->prepare($bloguse);
$usage->execute();
$usage->bind_result($bp);

while ($usage->fetch()) $blogpur = $bp;

$usage->close();
$mysqli->close();
?>
<?php if ($isMobile) echo "<div style='text-align: left;'><a id='simple-menu' href='#sidr'>MENU</a></div>"; ?>
<div id='Container'>
	<?php $curPage = $_GET['mode']; ?>
	<div id='Logo'> <?php include("module/head.php"); ?> </div>
	<div id='Links'> <?php include("module/topbar.php"); ?> </div>
	
	<?php if (!$isMobile) { ?>
	<div id='User'> <?php include("module/menu.php"); ?> </div>
	<?php }?>
	
	<?php if ($isMobile) { ?>
	<div id='sidr'> <?php include("module/menu.php"); ?> </div>
	<script>
		$(document).ready(function() {
			$('#simple-menu').sidr();
		});
	</script>
	<?php } ?>
	
	<div id='Main'>
	<?php
		if (empty($curPage)) {
			if ($blogpur == 1)	include("mode/index.php");
			else			include("mode/page.php");
		}
		else {
			if (file_exists("mode/".$curPage.".php")) {
				include("mode/".$curPage.".php");
			}
			else {
				include("mode/error.php");
			}
		}
	?>
	</div>

	<?php include("module/foot.php"); ?>
</div>

</body>
</html>
