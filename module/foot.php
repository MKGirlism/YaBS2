<?php
	$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);
	
	$sql = "SELECT `site_name` FROM `blg_generic`";
	$stmt = $mysqli->prepare($sql);
	echo $mysqli->error;
	
	$stmt->execute();
	
	$stmt->bind_result($gsname);
	
	while ($stmt->fetch()) {
		echo "<br /><br />$gsname, Powered by <a href='http://www.yamisoft.wtf'>YaBS 2.0</a>.";
	}
	
	$stmt->close();
	$mysqli->close();
?>
