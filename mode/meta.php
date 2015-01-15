<?php
// Ensure, that nobody, other than the Admin, can come here.
ob_start();
$powza = htmlentities($_SESSION['uname']['group'], ENT_QUOTES, 'UTF-8');
if ($powza <= 0)
{
        header('Location: index.php');
        exit();
}
ob_end_clean();
?>
<?php
// MySQL
$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);

if ($_POST['Submit']) {
	$sname = $mysqli->real_escape_string($_POST['Name']);
	$sdec = $mysqli->real_escape_string($_POST['Decro']);
	$stags = $mysqli->real_escape_string($_POST['Tags']);
	
	$update = "UPDATE General SET Name='$sname', Decro='$sdec', Tags='$stags'";
	$result = $mysqli->query($update);
	
	if ($result) {
        	$result->close();
        	$mysqli->close();
	}
	
	echo "<h2>Edit Site Information</h2>";
	echo "Done. <a href='index.php'>Return</a>.</div>";
}

else {
	$sql = "SELECT * FROM General";
	
	$stmt = $mysqli->prepare($sql);
	$stmt->execute();
	
	$stmt->bind_result($sname, $sdec, $stags);
	
	while ($stmt->fetch()) {
		echo "<script type='text/javascript' src='module/codebutton.js'></script>";
		echo "<h2>Edit Site Information</h2>";
		echo "<a href='index.php'>Return</a><br /><br />";
		echo "<form action='?mode=meta' method='post'>";
		echo "Site Name: <input type='text' name='Name' value='".$sname."'><br />";
		echo "Description: <input type='text' name='Decro' value='".$sdec."'><br />";
		echo "Tags: <input type='text' name='Tags' value='".$stags."'><br />";
		echo "<input name='Submit' type='submit' value='Edit'>
</form><br /><br />";
	}
	
	$stmt->close();
	$mysqli->close();
}
?>
