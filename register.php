<?php
	header("Content-Type: text/html; charset=utf-8");
	require("common.php");
	$row = null;
	
	if(!empty($_POST)) {
		if ($_POST["sec"] != 4) {
			echo "Wrong answer, try again.";
		}
		else {
			if(empty($_POST['uname'])) {
				die("Please enter a username.");
			}
			
			if(empty($_POST['passwd'])) {
				die("Please enter a password.");
			}
			
			if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				die("Invalid E-Mail Address");
			}
			
			$query = "SELECT 1 FROM Users WHERE uname = :uname";
			$query_params = array(':uname' => $_POST['uname']);
			
			try {
				$stmt = $db->prepare($query);
				$result = $stmt->execute($query_params);
			}
			catch(PDOException $ex) {
				die("Failed to run query: " . $ex->getMessage());
			}
			
			if($row) {
				die("This username is already in use");
			}
			
			$query = "SELECT 1 FROM Users WHERE email = :email";
			$query_params = array(':email' => $_POST['email']);
			
			try {
				$stmt = $db->prepare($query);
				$result = $stmt->execute($query_params);
			}
			catch(PDOException $ex) {
				die("Failed to run query: " . $ex->getMessage());
			}
			
			$row = $stmt->fetch();
			
			if($row) {
				die("This email address is already registered");
			}
			
			$query = "INSERT INTO Users (uname, passwd, salt, email)
			VALUES (:uname, :passwd, :salt, :email)";
			
			$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
			$passwd = hash('sha256', $_POST['passwd'] . $salt);
			
			for($round = 0; $round < 65536; $round++) {
				$passwd = hash('sha256', $passwd . $salt);
			}
			
			$query_params = array(':uname' => $_POST['uname'], ':passwd' => $passwd, ':salt' => $salt, ':email' => $_POST['email']);
			
			try {
				$stmt = $db->prepare($query);
				$result = $stmt->execute($query_params);
			}
			catch(PDOException $ex) {
				die("Failed to run query: " . $ex->getMessage());
			}
			header("Location: index.php");
			die("Redirecting to index.php");
		}
	}
?>
<!DOCTYPE HTML>
<html>
<head>
        <?php include("module/meta.php"); ?>
</head>
<body>
<div id='Container'>
	<div id='Logo'> <?php include("module/head.php"); ?> </div>
	<div id='Links'> <?php include("module/topbar.php"); ?> </div>
	<div id='User'> <?php include("module/menu.php"); ?> </div>
	
	<div id='Main'>
	<h1>Register</h1>
	<form action="register.php" method="post">
		Username: <input type="text" name="uname" value="" /><br />
		Email: <input type="text" name="email" value="" /><br />
		Password: <input type="password" name="passwd" value="" /><br />
		How much is two plus three minus one? (Numeric answer) <input type="text" name="sec" /><br />
		<input type="submit" value="Register" />
	</form>
	</div>
	
	<?php include("module/foot.php"); ?>
</div>
</body>
</html>
