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
		<link rel=stylesheet href='theme/$gtheme/style.css' type='text/css' media=screen id=stylesheet />";
		if (!$isMobile) {
			echo "<style>
				#Logo, #Links {
					width: 100%;
				}
				#Main {
					margin-right: 140px;
				}
				#BlogBody, #BlogData {
					width: 97%;
				}
				</style>";
		}
		else {
			echo "<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no' />
				<meta name='HandheldFriendly' content='true' />
				<script src='module/jquery.js'></script>
				<script src='theme/common/jquery.sidr.min.js'></script>
				<link rel=stylesheet href='theme/common/jquery.sidr.dark.css' type='text/css' media=screen id=stylesheet />";
			echo "<style>
				#Logo, #Links {
					width: 95%;
				}
				#Main {
					width: 95%;
				}
				#BlogBody, #BlogData {
					width: 90%;
				}
				</style>";
		}
		echo "<title>$gsname</title>";
	}
	
	$stmt->close();
	$mysqli->close();
?>
