<?php
header("Content-Type: text/html; charset=utf-8");
session_start();
?>
<!DOCTYPE HTML>
<html>
<head>
<title>YaBS 2.0 Installation</title>
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
\$uname = \"".$_POST['username2']."\";
\$paswd = \"".$_POST['password2']."\";
\$dbnme = \"".$_POST['dbname']."\";

\$userb = \"blg_users\";
?>";
	fwrite($configFile, $config);
	fclose($configFile);
	
	echo "Config File successfully made.<br /><br />";
	
	include("../config.php");
	
	$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);
	
	$mysqli->query("CREATE TABLE IF NOT EXISTS `blg_blogs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) NOT NULL,
  `post_date` int(10) NOT NULL,
  `privacy` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0;");
	
	echo $mysqli->error;
	
	echo "Table 'Blogs' successfully created.<br />";

	$mysqli->query("INSERT INTO `blg_blogs` (`id`, `title`, `message`, `user_id`, `post_date`, `privacy`) VALUES
(1, 'Hi, World!', 'Hi there, welcome to [b]YaBS[/b]! :awsum:<br />\r\nAs you can see, Installation was a Success! Yay!<br />\r\nEnjoy it, all you can!', 1, 0, 0);");
	
	echo "Table 'Blogs' successfully inserted.<br />";
	
	$mysqli->query("CREATE TABLE IF NOT EXISTS `blg_comments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `post_id` int(10) NOT NULL,
  `message` text NOT NULL,
  `user_id` int(10) NOT NULL,
  `post_date` int(10) NOT NULL,
  `delete` int(1) NOT NULL DEFAULT '0',
  `last_edit` int(10) NOT NULL,
  `ip_address` varchar(15) NOT NULL DEFAULT '0.0.0.0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0;");
	
	echo "Table 'Comments' successfully created.<br />";
	
	$mysqli->query("INSERT INTO `blg_comments` (`id`, `post_id`, `message`, `user_id`, `post_date`, `delete`, `last_edit`, `ip_address`) VALUES
(1, 1, 'Test Comment.', 1, 0, 0, 0, '0.0.0.0');");
	
	echo "Table 'Comments' successfully inserted.<br />";
	
	$mysqli->query("CREATE TABLE IF NOT EXISTS `blg_generic` (
  `site_name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `tags` varchar(255) NOT NULL,
  `topbar` int(1) NOT NULL DEFAULT '1',
  `theme` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT 'Yamisoft',
  `blog_purpose` int(1) NOT NULL DEFAULT '1',
  `homepage` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");
	
	echo "Table 'General' successfully created.<br />";
	
	$sitename = $_POST['sitename'];
	$sitedecro = $_POST['sitedecro'];
	$sitetags = $_POST['sitetags'];
	
	$mysqli->query("INSERT INTO `blg_generic` (`site_name`, `description`, `tags`, `topbar`, `theme`, `blog_purpose`, `homepage`) VALUES
('$sitename', '$sitedecro', '$sitetags', 1, 'Yamisoft', 1, 0);");
	
	echo "Table 'General' successfully inserted.<br />";
	
	$mysqli->query("CREATE TABLE IF NOT EXISTS `blg_uploads` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0;");
	
	echo "Table 'Images' successfully created.<br />";
	
	$mysqli->query("CREATE TABLE IF NOT EXISTS `blg_jokes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `joke` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0;");
	
	echo "Table 'Jokes' successfully created.<br />";
	
	$mysqli->query("INSERT INTO `blg_jokes` (`id`, `joke`) VALUES
(1, 'Jokes need to be Added, through PHPMyAdmin.');");
	
	echo "Table 'Jokes' successfully inserted.<br />";
	
	$mysqli->query("CREATE TABLE IF NOT EXISTS `blg_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0;");
	
	echo "Table 'Links' successfully created.<br />";
	
	$mysqli->query("INSERT INTO `blg_links` (`id`, `title`, `url`) VALUES
(1, 'Yamisoft', 'http://www.yamisoft.wtf');");
	
	echo "Table 'Links' successfully inserted.<br />";
	
	$mysqli->query("CREATE TABLE IF NOT EXISTS `blg_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(65) NOT NULL,
  `message` text NOT NULL,
  `privacy` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0;");
	
	echo "Table 'Pages' successfully created.<br />";
	
	$mysqli->query("INSERT INTO `blg_pages` (`id`, `title`, `message`, `privacy`) VALUES
(1, 'About', 'Nothing.', 0);");
	
	echo "Table 'Pages' successfully inserted.<br />";
	
	$mysqli->query("CREATE TABLE IF NOT EXISTS `blg_users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` char(255) NOT NULL,
  `salt` char(255) NOT NULL,
  `group` int(1) NOT NULL DEFAULT '0',
  `email` varchar(255) NOT NULL,
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'assets/avatars/haznoavaz.png',
  `ontime` int(10) NOT NULL DEFAULT '0',
  `online` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0;");
	
	echo "Table 'Users' successfully created.<br />";

	echo "Database stuff completed successfully!<br /><br />";
	
	// Create the Admin Account.
	require("../common.php");
	$query = "INSERT INTO `blg_users` (`username`, `password`, `salt`, `email`, `group`)
	VALUES (:username, :password, :salt, :email, :group)";
	
	$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
	$passwd = hash('sha256', $_POST['password'] . $salt);
	
	for($round = 0; $round < 65536; $round++) {
		$passwd = hash('sha256', $passwd . $salt);
	}

	$query_params = array(':username' => $_POST['username'], ':password' => $passwd, ':salt' => $salt, ':email' => $_POST['email'], ':group' => 3);
	
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
Username: <input type='text' name='username2' /><br />
Password: <input type='password' name='password2' /><br />
Database Name: <input type='text' name='dbname' />
<h1>Admin Account</h1>
Username: <input type='text' name='username' /><br />
Password: <input type='password' name='password' /><br />
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
