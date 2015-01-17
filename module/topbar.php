<?php
	$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);
	
	$top = "SELECT Topbar FROM General";
	$toppy = $mysqli->prepare($top);
	$toppy->execute();
	
	$toppy->bind_result($gtop);
	
	$topbar = 99;
	
	while ($toppy->fetch()) {
		$topbar = $gtop;
	}
	
	$toppy->close;
	
	if (isset($_SESSION['uname']['uname'])) {
		$member = $_SESSION['uname']['uname'];
	}
	
	$time = time();
	$past = $time - 600;
	$static = $time + 1;
	$timeout = $time - $past;
	
	$joke = "SELECT Text FROM Jokes ORDER BY rand() LIMIT 1";
	$uonline = "SELECT uid, uname, ava, ontime, online FROM Users WHERE uname = '$member' AND ontime > $timeout";
	
	// Random Jokes.
	$joker = $mysqli->prepare($joke);
	$joker->execute();
	
	$joker->bind_result($jtext);
	
	if ($topbar == 1) {
		echo "<b>Random Joke: </b> ";
		while ($joker->fetch()) {
		        echo $jtext;
		}
	}
	
	$joker->close();
	
	// Users Online.
	$uon = $mysqli->prepare($uonline);
	$uon->execute();
	
	$uon->bind_result($uid, $usname, $uava, $uontime, $uonliner);
	
	if (!isset($usname)) {
		$uon->close();
		$sqli = "UPDATE Users SET ontime = $time, online = 1 WHERE uname = '$member'";
		$mysqli->query($sqli);
	}
	
	$mysqli->close();
	
	$con = mysqli_connect($hosty, $uname, $paswd, $dbnme);
	
	$query = "SELECT uid, uname, ava, ontime, online FROM Users WHERE ontime > $past";
	$prep = mysqli_query($con, $query);
	$row = mysqli_fetch_assoc($prep);
	
	if ($topbar == 2) {
		echo "<b>Users online: </b>";
		if ($prep = mysqli_query($con, $query)) {
			if (isset($row['uname'])) {
				if ($static > $past) mysqli_query($con, "UPDATE Users SET online = 0 WHERE uname = '$member'");
				
				while ($row = mysqli_fetch_assoc($prep)) {
					echo "<a href='profile.php?uid=".$row['uid']."'><img src='".$row['ava']."' width='20px' /> ".$row['uname']."</a>";
					echo " ";
				}
			}
			else {
				echo "#nobody";
			}
		}
	}
	
	mysqli_free_result($query);
	mysqli_close();
	
	// Search.
	if ($topbar == 3) {
		?>
		<form action = "?mode=search" method = "post">
		Search for <input name = "query" value = "" />
		in <select name = "app">
		<option value = "Pages">Pages</option>
		<option value = "Blog">Blog</option>
		</select>
		<input type = "submit" value = "Search" />
		</form>
		<?php
	}
	
	//$mysqli->close();
?>
