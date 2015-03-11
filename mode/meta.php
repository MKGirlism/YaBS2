<?php
// Ensure, that nobody, other than the Admin, can come here.
ob_start();
$powza = htmlentities($_SESSION['username']['group'], ENT_QUOTES, 'UTF-8');
if ($powza <= 1)
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
	$top = $_POST['Topbar'];
	$stheme = $_POST['Theme'];
	$spurp = $_POST['BlogPurpose'];
	if ($spurp == 1)	$shome = 0;
	else			$shome = $_POST['Homepage'];
	
	$update = "UPDATE blg_generic SET site_name = '$sname', description = '$sdec', tags = '$stags', topbar = '$top', theme = '$stheme', blog_purpose = '$spurp', homepage = '$shome'";
	$result = $mysqli->query($update);
	
	if ($result) {
        	$result->close();
        	$mysqli->close();
	}
	
	echo "<h2>Edit Site Information</h2>";
	echo "Done. <a href='index.php'>Return</a>.</div>";
}

else {
	$sql = "SELECT * FROM blg_generic";
	$sql2 = "SELECT id, title FROM blg_pages";
	
	$stmt = $mysqli->prepare($sql);
	$stmt->execute();
	
	$stmt->bind_result($gsname, $gdec, $gtags, $gtop, $gtheme, $gpurp, $ghome);
	
	while ($stmt->fetch()) {
		echo "<script type='text/javascript' src='module/codebutton.js'></script>";
		echo "<h2>Edit Site Information</h2>";
		echo "<a href='index.php'>Return</a><br /><br />";
		echo "<form action='?mode=meta' method='post'>";
		echo "Site Name: <input type='text' name='Name' value='".$gsname."'><br />";
		echo "Description: <input type='text' name='Decro' value='".$gdec."'><br />";
		echo "Tags: <input type='text' name='Tags' value='".$gtags."'><br /><br />";
		echo "Topbar:<br />
		<select name='Topbar'>";
?>
			<option value='0' <?php if ($gtop == 0) echo "selected='selected'"; ?>>Off</option>
			<option value='1' <?php if ($gtop == 1) echo "selected='selected'"; ?>>Random Jokes</option>
			<option value='2' <?php if ($gtop == 2) echo "selected='selected'"; ?>>Users Online</option>
			<option value='3' <?php if ($gtop == 3) echo "selected='selected'"; ?>>Search</option>
<?php
		echo "</select><br />";
		echo "Theme:<br />
		<select name='Theme'>";
			$themes = array_map("htmlspecialchars", scandir("theme"));
			$themes = array_diff($themes, array('..', '.', 'common'));
			foreach ($themes as $theme) {
				echo "<option value='$theme'";
				if ($stheme == $theme) echo "selected='selected'";
				echo ">$theme</option>";
			}
		echo "</select><br /><br />";
		echo "Blog has (a) 
		<select name='BlogPurpose'>";
?>
			<option value='1' <?php if ($gpurp == 1) echo "selected='selected'"; ?>>Main</option>
			<option value='2' <?php if ($gpurp == 2) echo "selected='selected'"; ?>>Side</option>
			<option value='3' <?php if ($gpurp == 3) echo "selected='selected'"; ?>>No</option>
<?php
		echo "</select> purpose.<br />";
		$hpage = $ghome;
		$stmt->close();
		
		$stmt2 = $mysqli->prepare($sql2);
		$stmt2->execute();
		
		$stmt2->bind_result($pid, $ptitle);
		
		echo "Homepage (if Side or No Purpose):<br />
		<select name='Homepage'>";
			while ($stmt2->fetch()) {
//				echo "<select value='Homepage'>";
				echo "<option value='$pid'";
				if ($hpage == $pid) echo "selected='selected'";
				echo ">$pid. $ptitle</option>";
//				echo "</select><br />";
			}
		echo "</select><br />";
		echo "<input name='Submit' type='submit' value='Edit'>
		</form><br /><br />";
		$stmt2->close();
	}
	
	$mysqli->close();
}
?>
