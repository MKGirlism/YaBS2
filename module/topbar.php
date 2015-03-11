<?php
	$mysqli = new mysqli($hosty, $uname, $paswd, $dbnme);
	
	$top = "SELECT topbar FROM blg_generic";
	$toppy = $mysqli->prepare($top);
	$toppy->execute();
	
	$toppy->bind_result($gtop);
	
	$topbar = 99;
	
	while ($toppy->fetch()) {
		$topbar = $gtop;
	}
	
	$toppy->close;
	
	if (isset($_SESSION['username']['username'])) {
		$member = $_SESSION['username']['username'];
	}
	
	$time = time();
	$past = $time - 600;
	$static = $time + 1;
	$timeout = $time - $past;
	
	$joke = "SELECT joke FROM blg_jokes ORDER BY rand() LIMIT 1";
	$uonline = "SELECT id, username, avatar, ontime, online FROM $userb WHERE username = '$member' AND ontime > $timeout";
	
	// Random Jokes.
	$joker = $mysqli->prepare($joke);
	$joker->execute();
	
	$joker->bind_result($jjoke);
	
	if ($topbar == 1) {
		echo "<b>Random Joke: </b> ";
		while ($joker->fetch()) {
		        echo $jjoke;
		}
	}
	
	$joker->close();
	
	// Users Online.
	$uon = $mysqli->prepare($uonline);
	$uon->execute();
	
	$uon->bind_result($uid, $uuname, $uava, $uontime, $uonliner);
	
	if (!isset($uuname)) {
		$uon->close();
		$sqli = "UPDATE $userb SET ontime = $time, online = 1 WHERE username = '$member'";
		$mysqli->query($sqli);
	}
	
	$mysqli->close();
	
	$con = mysqli_connect($hosty, $uname, $paswd, $dbnme);
	
	$query = "SELECT id, username, avatar, ontime, online FROM $userb WHERE ontime > $past";
	$prep = mysqli_query($con, $query);
	$row = mysqli_fetch_assoc($prep);
	
	if ($topbar == 2) {
		echo "<b>Users online: </b>";
		if ($prep = mysqli_query($con, $query)) {
			if (isset($row['username'])) {
				if ($static > $past) mysqli_query($con, "UPDATE $userb SET online = 0 WHERE username = '$member'");
				
				while ($row = mysqli_fetch_assoc($prep)) {
					echo "<a href='?mode=profile&uid=".$row['id']."'><img src='".$row['avatar']."' width='20px' /> ".$row['username']."</a>";
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
?>
