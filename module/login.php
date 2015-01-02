<?php
	require("common.php");
	$submitted_username = '';
	$submitted_id = '';
	$value = "something";
	
	if(!empty($_POST)) {
		$query = "SELECT * FROM Users WHERE uname = :uname";
		$query_params = array(':uname' => $_POST['uname']);
		
		try {
			$stmt = $db->prepare($query);
        		$result = $stmt->execute($query_params);
		}
		catch(PDOException $ex) {
			die("Failed to run query: " . $ex->getMessage());
		}
		
		$login_ok = false;
		$row = $stmt->fetch();
		
		if($row) {
			$check_password = hash('sha256', $_POST['passwd'] . $row['salt']);
			for($round = 0; $round < 65536; $round++) {
				$check_password = hash('sha256', $check_password . $row['salt']);
			}
			
			if($check_password === $row['passwd']) {
				$login_ok = true;
			}
		}
		
		if($login_ok) {
			unset($row['salt']);
			unset($row['passwd']);
			$_SESSION['uname'] = $row;
			$_SESSION['uid'] = $row;
			$_SESSION['group'] = $row;
			
			setcookie("storelogin", $value, time()+3600*24*30, '/');
			
			header("Location: index.php");
			die("Redirecting to: index.php");
		}
		else {
			//print("Login Failed."); 
			$submitted_username = htmlentities($_POST['uname'], ENT_QUOTES, 'UTF-8');
		}
	}
?>
