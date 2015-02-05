<?php
header("Content-Type: text/html; charset=utf-8");
session_start();
?>
<!DOCTYPE HTML>
<html>
<head>
<title>YaBS 2 Installation</title>
</head>
<body>
<?php
error_reporting(0);

// Show if Submitted.
if ($_POST['Install']) {
	// Edit the Config File.
	$configFile = fopen("../config.php", "w") or die("Unable to Open File!");
	$config = "<?php
\$hosty = \"".$_POST['hostname']."\";
\$uname = \"".$_POST['username']."\";
\$paswd = \"".$_POST['password']."\";
\$dbnme = \"".$_POST['dbname']."\";
?>";
	fwrite($configFile, $config);
	fclose($configFile);
	
	echo "Config File successfully made.<br /><br />";
	
	include("../config.php");
	
	$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);
	
	$mysqli->query("CREATE TABLE IF NOT EXISTS `Blogs` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `uid` int(11) NOT NULL,
  `Date` int(10) NOT NULL,
  `Privacy` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0;");
	
	echo $mysqli->error;
	
	echo "Table 'Blogs' successfully created.<br />";

	$mysqli->query("INSERT INTO `Blogs` (`id`, `Name`, `Content`, `uid`, `Date`, `Privacy`) VALUES
(1, 'Hi, World!', 'Hi there, welcome to [b]YaBS[/b]! :awsum:<br />\r\nAs you can see, Installation was a Success! Yay!<br />\r\nEnjoy it, all you can!', 1, 0, 0);");
	
	echo "Table 'Blogs' successfully inserted.<br />";
	
	$mysqli->query("CREATE TABLE IF NOT EXISTS `Comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `message` text NOT NULL,
  `uid` int(11) NOT NULL,
  `date` int(10) NOT NULL,
  `delete` int(1) NOT NULL DEFAULT '0',
  `lastedit` int(10) NOT NULL,
  `ipaddress` varchar(15) NOT NULL DEFAULT '0.0.0.0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0;");
	
	echo "Table 'Comments' successfully created.<br />";
	
	$mysqli->query("INSERT INTO `Comments` (`id`, `pid`, `message`, `uid`, `date`, `delete`, `lastedit`, `ipaddress`) VALUES
(1, 1, 'Test Comment.', 1, 0, 0, 0, '0.0.0.0');");
	
	echo "Table 'Comments' successfully inserted.<br />";
	
	$mysqli->query("CREATE TABLE IF NOT EXISTS `General` (
  `Name` varchar(255) NOT NULL,
  `Decro` varchar(255) NOT NULL,
  `Tags` varchar(255) NOT NULL,
  `Topbar` int(1) NOT NULL DEFAULT '1',
  `Theme` varchar(200) NOT NULL DEFAULT 'Yamisoft'
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0;");
	
	echo "Table 'General' successfully created.<br />";
	
	$sitename = $_POST['sitename'];
	$sitedecro = $_POST['sitedecro'];
	$sitetags = $_POST['sitetags'];
	
	$mysqli->query("INSERT INTO `General` (`Name`, `Decro`, `Tags`, `Topbar`) VALUES
('$sitename', '$sitedecro', '$sitetags', 1);");
	
	echo "Table 'General' successfully inserted.<br />";
	
	$mysqli->query("CREATE TABLE IF NOT EXISTS `Images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `URL` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0;");
	
	echo "Table 'Images' successfully created.<br />";
	
	$mysqli->query("CREATE TABLE IF NOT EXISTS `Jokes` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `Text` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0;");
	
	echo "Table 'Jokes' successfully created.<br />";
	
	$mysqli->query("INSERT INTO `Jokes` (`id`, `Text`) VALUES
(1, 'Jokes need to be Added, through PHPMyAdmin.');");
	
	echo "Table 'Jokes' successfully inserted.<br />";
	
	$mysqli->query("CREATE TABLE IF NOT EXISTS `Links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(200) NOT NULL,
  `URL` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0;");
	
	echo "Table 'Links' successfully created.<br />";
	
	$mysqli->query("INSERT INTO `Links` (`id`, `Title`, `URL`) VALUES
(1, 'Yamisoft', 'http://www.yamisoft.wtf');");
	
	echo "Table 'Links' successfully inserted.<br />";
	
	$mysqli->query("CREATE TABLE IF NOT EXISTS `Pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(65) NOT NULL,
  `Content` text NOT NULL,
  `Privacy` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0;");
	
	echo "Table 'Pages' successfully created.<br />";
	
	$mysqli->query("INSERT INTO `Pages` (`id`, `Title`, `Content`, `Privacy`) VALUES
(1, 'About', 'Nothing.', 0);");
	
	echo "Table 'Pages' successfully inserted.<br />";
	
	$mysqli->query("CREATE TABLE IF NOT EXISTS `Users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(65) NOT NULL,
  `passwd` char(64) NOT NULL,
  `salt` char(16) NOT NULL,
  `email` varchar(100) NOT NULL,
  `group` int(1) NOT NULL DEFAULT '0',
  `ava` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'assets/avatars/haznoavaz.png',
  `ontime` int(10) NOT NULL DEFAULT '0',
  `online` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0;");
	
	echo "Table 'Users' successfully created.<br />";

	echo "Database stuff completed successfully!<br /><br />";
	
	// Create the Admin Account.
	require("../common.php");
	$query = "INSERT INTO Users (`uname`, `passwd`, `salt`, `email`, `group`)
	VALUES (:uname, :passwd, :salt, :email, :group)";
	
	$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
	$passwd = hash('sha256', $_POST['passwd'] . $salt);
	
	for($round = 0; $round < 65536; $round++) {
		$passwd = hash('sha256', $passwd . $salt);
	}

	$query_params = array(':uname' => $_POST['uname'], ':passwd' => $passwd, ':salt' => $salt, ':email' => $_POST['email'], ':group' => 1);
	
	try {
		$stmt = $db->prepare($query);
		$result = $stmt->execute($query_params);
	}
	catch(PDOException $ex) {
		die("Failed to run query: " . $ex->getMessage());
	}
	
	echo "Admin Account successfully created!<br /><br />";
	
	echo "Done! Go to <a href='index.php'>your Blog</a>!<br /><br />";
	echo "<b>DO NOT FORGET, TO DELETE \"install/index.php\", AS SOON AS POSSIBLE!</b>";
}
else {
	// Check if the File exists.
	if (!file_exists("../config.php")) {
		echo "I can't find the File \"config.php\" not found.<br />Please create it.";
	}
	// Check if File is overwriteable.
	else if (decoct(fileperms("../config.php")) != 100777) {
		echo "The File \"config.php\" is not writeable.<br />CHMod it to 777, please!";
	}
	// The Form.
	else {
		if (decoct(fileperms("../assets")) != 40777) {
			echo "Warning: The Assets Folder is not writeable.<br />You can still Install, but you won't be able to the the Uploader, until you fix that.";
		}
?>
<form action='index.php' method='post'>
<h1>Database</h1>
Hostname: <input type='text' name='hostname' /><br />
Username: <input type='text' name='username' /><br />
Password: <input type='password' name='password' /><br />
Database Name: <input type='text' name='dbname' />
<h1>Admin Account</h1>
Username: <input type='text' name='uname' /><br />
Password: <input type='password' name='passwd' /><br />
Email: <input type='text' name='email' />
<h1>Site Information</h1>
Website Name: <input type='text' name='sitename' /><br />
Description: <input type='text' name='sitedecro' /><br />
Tags: <input type='text' name='sitetags' /><br />
<input name='Install' type='submit' value='Install' />
</form>
<?php
	}
}
?>
</body>
</html>
