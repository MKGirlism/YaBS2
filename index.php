<?php
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
$curPage = $_GET['mode'];
include("module/head.php");
include("module/menu.php");

if (empty($curPage)) {
	include("mode/index.php");
}
else {
	include("mode/".$curPage.".php");
}

include("module/foot.php");
?>
</body>
</html>
