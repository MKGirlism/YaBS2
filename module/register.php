<?php
	require("common.php");
	if ($_POST["sec"] != 4)
		echo "";
	else {
		if(!empty($_POST)) {
			if(empty($_POST['uname'])) {
				die("Please enter a username.");
			}
			
			if(empty($_POST['passwd'])) {
				die("Please enter a password.");
			}
			
			if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				die("Invalid Email Address");
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
			
			$join = date_create();
			
			$query = "INSERT INTO Users (uname, passwd, salt, email, joined) VALUES (:uname, :passwd, :salt, :email, ".date_timestamp_get($join).")";
			
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
		}
	}
?>
