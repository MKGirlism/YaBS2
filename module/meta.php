<?php
	error_reporting(0);
	$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);
	
	$sql = "SELECT * FROM General";
	$stmt = $mysqli->prepare($sql);
	$stmt->execute();
	
	$stmt->bind_result($sname, $sdecro, $stags);
	
	while ($stmt->fetch()) {
	echo "<meta name=description content='$sdecro'>
		<meta name=keywords content='$stags'>
		<meta name=generator content='YaBS'>
		<link rel=stylesheet href='theme/style.css' type='text/css' media=screen id=stylesheet />
		<script type='text/javascript' src='theme/sticky.js'></script>
		<title>$sname</title>";
	}
	
	$stmt->close();
	$mysqli->close();
?>
