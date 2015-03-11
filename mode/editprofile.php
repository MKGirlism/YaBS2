<?php
	require("common.php");
	if(empty($_SESSION['username'])) {
                header("Location: login.php");
                die("Redirecting to login.php");
        }
	if(!empty($_POST)) {
		if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                        die("Invalid Email Address");
                }
		
                if($_POST['email'] != $_SESSION['username']['email']) {
                        $query = "SELECT 1 FROM $userb WHERE email = :email";
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
                                die("This Email address is already in use");
                        }
                }
		
                if(!empty($_POST['passwd'])) {
                        $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
                        $passwd = hash('sha256', $_POST['passwd'] . $salt);
                        for($round = 0; $round < 65536; $round++) {
                                $passwd = hash('sha256', $passwd . $salt);
                        }
                }
                else {
                        $passwd = null;
                        $salt = null;
                }
		
		$query_params = array(':email' => $_POST['email'], ':id' => $_POST['id']);
                $query_params[':avatar'] = $_POST['avatar'];
		
		if($passwd !== null) {
                        $query_params[':password'] = $passwd;
                        $query_params[':salt'] = $salt;
                }
		
                $query = "UPDATE $userb SET email = :email, avatar = :avatar";
		
                if($passwd !== null) {
                        $query .= ", password = :password, salt = :salt";
                }
		
                $query .= " WHERE id = :id";
		
		try {
                        $stmt = $db->prepare($query);
                        $result = $stmt->execute($query_params);
                }
                catch(PDOException $ex) {
                        die("Failed to run query: " . $ex->getMessage());
                }
		
                $_SESSION['uname']['email'] = $_POST['email'];
		
                header("Location: index.php");
                die("Redirecting to index.php");
	}
	else {
		$aid = $_GET['uid'];
		
		$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);
		$stmt = $mysqli->prepare("SELECT `id`, `username`, `email`, `avatar` FROM $userb WHERE id = ".$aid);
		$stmt->execute();
		
		$stmt->bind_result($uid, $uuname, $uemail, $uava);
		
		while ($stmt->fetch()) {
echo "<center><h1>Edit Account</h1></center>
<form action=\"?mode=editprofile\" method=\"post\">
        Username: <b>$uuname</b><br /><br />
	<input type=\"text\" name=\"id\" value=\"$uid\" hidden>
        Email:<br /><input type=\"text\" name=\"email\" value=\"$uemail\" /><br />
        Avatar:<br /><input type=\"text\" name=\"avatar\" value=\"$uava\" /><br />
        Password:<br /><input type=\"password\" name=\"password\" value=\"\" /><br />
        <i>(Leave blank if you don't want to change your password)</i><br />
        <input type=\"submit\" value=\"Update Account\" /><br />
</form><br />";
		}
		
		$stmt->close();
		$mysqli->close();
	}
?>
