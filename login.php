<?php
	header("Content-Type: text/html; charset=utf-8");
	require("common.php");
	$submitted_username = '';
	
	// If you Login.
	if(!empty($_POST)) {
		// MySQL
		$query = "SELECT * FROM Users WHERE uname = :uname";
		$query_params = array(':uname' => $_POST['uname']);
		
		// Execute the Query, or give a meaningful Error Message.
		try {
			$stmt = $db->prepare($query);
        		$result = $stmt->execute($query_params);
		}
		catch(PDOException $ex) {
			die("Failed to run query: " . $ex->getMessage());
		}
		
		$login_ok = false;
		$row = $stmt->fetch();
		
		// Check if the Password matches SHA256 and Salt Encryption.
		if($row) {
			$check_password = hash('sha256', $_POST['passwd'] . $row['salt']);
			for($round = 0; $round < 65536; $round++) {
				$check_password = hash('sha256', $check_password . $row['salt']); 
			}
			
			// If everything goes well, proceed the Login.
			if($check_password === $row['passwd']) {
				$login_ok = true;
			}
		}
		
		// Forget about the Password, and set the Username and Group. After that, go to the Index.
		if($login_ok) {
			unset($row['salt']);
			unset($row['passwd']);
			$_SESSION['uname'] = $row;
			$_SESSION['group'] = $row;
			header("Location: index.php");
			die("Redirecting to: index.php");
		}

		// Execute this, if it fails.
		else {
			print("Login Failed. Username given: "); 
			$submitted_username = htmlentities($_POST['uname'], ENT_QUOTES, 'UTF-8');
			print($submitted_username);
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
	<h1>Login</h1>
	<form action="login.php" method="post">
		Username: <input type="text" name="uname" value="<?php echo $submitted_username; ?>" /><br />
		Password: <input type="password" name="passwd" value="" /><br />
		<input type="submit" value="Login" />
	</form>
	<a href="register.php">Register</a>
	</div>
	
	<?php include("module/foot.php"); ?>
</div>
</body>
</html>
