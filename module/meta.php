<?php
	error_reporting(0);
	$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);
	
	$sql = "SELECT site_name, description, tags, theme FROM blg_generic";
	$stmt = $mysqli->prepare($sql);
	$stmt->execute();
	
	$stmt->bind_result($gsname, $gdecro, $gtags, $gtheme);
	
	while ($stmt->fetch()) {
	echo "<meta name=description content='$gdecro'>
		<meta name=keywords content='$gtags'>
		<meta name=generator content='YaBS 2.1'>
		<link rel=stylesheet href='theme/$gtheme/style.css' type='text/css' media=screen id=stylesheet />
		<title>$gsname</title>";
	}
	
	$stmt->close();
	$mysqli->close();
?>
