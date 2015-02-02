<?php
	$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);
	
	$sql = "SELECT `Name` FROM `General`";
	$stmt = $mysqli->prepare($sql);
echo $mysqli->error;
	$stmt->execute();
	
	$stmt->bind_result($sname);
	
	while ($stmt->fetch()) {
		echo "<br /><br />$sname, Blog Software by <a href='http://www.yamisoft.wtf'>Yamisoft</a>.";
	}
	
	$stmt->close();
	$mysqli->close();
?>
